#!/usr/bin/env bash
# Deploy do FootConnect em produção (git pull + dependências + Laravel)
# Chamado pelo GitHub Actions após push na branch main.
# Uso manual: bash deploy/deploy.sh

set -euo pipefail

APP_DIR="${APP_DIR:-/var/www/footconnect}"
BRANCH="${BRANCH:-main}"
WEB_USER="${WEB_USER:-www-data}"
PHP_BIN="${PHP_BIN:-$(command -v php)}"
COMPOSER_BIN="${COMPOSER_BIN:-$(command -v composer)}"
NPM_BIN="${NPM_BIN:-$(command -v npm)}"

log() {
    echo "[deploy $(date '+%Y-%m-%d %H:%M:%S')] $*"
}

if [[ ! -d "$APP_DIR" ]]; then
    echo "Erro: diretório não encontrado: $APP_DIR"
    exit 1
fi

if [[ ! -f "$APP_DIR/artisan" ]]; then
    echo "Erro: artisan não encontrado em $APP_DIR"
    exit 1
fi

if [[ -z "$PHP_BIN" ]]; then
    echo "Erro: PHP não encontrado. Defina PHP_BIN=/caminho/para/php"
    exit 1
fi

if [[ -z "$COMPOSER_BIN" ]]; then
    echo "Erro: Composer não encontrado. Instale o Composer no servidor."
    exit 1
fi

cd "$APP_DIR"

log "Entrando em modo de manutenção..."
"$PHP_BIN" artisan down --retry=60 || true

cleanup() {
    log "Saindo do modo de manutenção..."
    "$PHP_BIN" artisan up || true
}
trap cleanup EXIT

log "Atualizando código (origin/$BRANCH)..."
git fetch origin "$BRANCH"
git checkout "$BRANCH"
git pull --ff-only origin "$BRANCH"

log "Instalando dependências PHP..."
"$COMPOSER_BIN" install --no-interaction --prefer-dist --optimize-autoloader --no-dev

if [[ -f package.json ]] && [[ -n "$NPM_BIN" ]]; then
    log "Compilando assets (Vite)..."
    if [[ -f package-lock.json ]]; then
        "$NPM_BIN" ci --no-audit --no-fund
    else
        "$NPM_BIN" install --no-audit --no-fund
    fi
    "$NPM_BIN" run build
else
    log "npm não encontrado ou sem package.json — pulando build de assets."
fi

log "Migrations e cache Laravel..."
"$PHP_BIN" artisan migrate --force
"$PHP_BIN" artisan config:cache
"$PHP_BIN" artisan route:cache
"$PHP_BIN" artisan view:cache
"$PHP_BIN" artisan event:cache 2>/dev/null || true
"$PHP_BIN" artisan queue:restart 2>/dev/null || true

if id "$WEB_USER" &>/dev/null; then
    log "Ajustando permissões de storage e bootstrap/cache..."
    chown -R "$WEB_USER:$WEB_USER" storage bootstrap/cache 2>/dev/null || \
        sudo chown -R "$WEB_USER:$WEB_USER" storage bootstrap/cache
    chmod -R ug+rwx storage bootstrap/cache 2>/dev/null || \
        sudo chmod -R ug+rwx storage bootstrap/cache
fi

log "Deploy concluído com sucesso."
