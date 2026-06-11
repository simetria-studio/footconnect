#!/usr/bin/env bash
# Instala o cron do FootConnect em /var/www/footconnect (Linux)
# Uso: sudo bash deploy/install-cron.sh

set -euo pipefail

APP_DIR="/var/www/footconnect"
CRON_NAME="footconnect"
PHP_BIN="${PHP_BIN:-$(command -v php)}"
WEB_USER="${WEB_USER:-www-data}"

if [[ $EUID -ne 0 ]]; then
    echo "Execute com sudo: sudo bash deploy/install-cron.sh"
    exit 1
fi

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

mkdir -p "$APP_DIR/storage/logs"
touch "$APP_DIR/storage/logs/scheduler.log"
chown -R "$WEB_USER:$WEB_USER" "$APP_DIR/storage/logs"

CRON_FILE="/etc/cron.d/$CRON_NAME"

cat > "$CRON_FILE" <<EOF
# FootConnect — Laravel Scheduler (gerenciado pelo deploy/install-cron.sh)
# * * * * * = a cada minuto; schedule:run dispara tarefas agendadas em routes/console.php

* * * * * $WEB_USER cd $APP_DIR && $PHP_BIN artisan schedule:run >> $APP_DIR/storage/logs/scheduler.log 2>&1

EOF

chmod 644 "$CRON_FILE"

echo "Cron instalado em $CRON_FILE"
echo "PHP: $PHP_BIN"
echo "Usuário: $WEB_USER"
echo "App: $APP_DIR"
echo ""
echo "Tarefas agendadas no Laravel:"
cd "$APP_DIR" && sudo -u "$WEB_USER" "$PHP_BIN" artisan schedule:list
echo ""
echo "Log: tail -f $APP_DIR/storage/logs/scheduler.log"
