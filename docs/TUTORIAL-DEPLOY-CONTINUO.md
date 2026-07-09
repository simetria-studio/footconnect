# Tutorial: Deploy contínuo com GitHub Actions

Guia passo a passo para configurar deploy automático em projetos Laravel (ou similares): ao fazer **push na branch `main`**, o GitHub Actions conecta no servidor via SSH e executa `git pull` + build.

> Baseado na configuração validada no FootConnect. Adapte os nomes de pasta, repositório e chaves para cada projeto.

---

## Como funciona

```
Seu PC                    GitHub                    Servidor
  │                         │                          │
  │  git push main          │                          │
  ├────────────────────────►│                          │
  │                         │  GitHub Actions          │
  │                         │  (SSH com chave A)       │
  │                         ├─────────────────────────►│
  │                         │                          │  deploy.sh
  │                         │                          │  git pull (chave B)
  │                         │◄─────────────────────────┤
  │                         │                          │
```

São **duas chaves SSH diferentes** — não misture:

| Chave | Onde fica | Para quê |
|-------|-----------|----------|
| **Chave A** (`projeto_actions`) | Secret no GitHub + `authorized_keys` do servidor | GitHub Actions **entra** no servidor |
| **Chave B** (`projeto_deploy`) | Deploy key no GitHub + `~/.ssh/` do servidor | Servidor **puxa** código do GitHub |

---

## Pré-requisitos

- Repositório no GitHub
- Servidor Linux com SSH, Git, PHP, Composer e (se usar Vite) Node.js/npm
- Acesso root ou usuário com permissão na pasta do projeto
- Projeto clonado no servidor (ex.: `/var/www/meu-projeto`)

### Variáveis do seu projeto

Substitua estes valores em todo o tutorial:

| Variável | Exemplo FootConnect | Seu projeto |
|----------|---------------------|-------------|
| `REPO` | `simetria-studio/footconnect` | `org/meu-repo` |
| `APP_DIR` | `/var/www/footconnect` | `/var/www/meu-projeto` |
| `DEPLOY_USER` | `root` | `deploy` ou `root` |
| `DEPLOY_HOST` | `216.22.5.57` | IP ou domínio |
| `BRANCH` | `main` | `main` |

---

## Parte 1 — Arquivos no repositório

### 1.1 Workflow do GitHub Actions

Crie `.github/workflows/deploy.yml`:

```yaml
name: Deploy

on:
  push:
    branches:
      - main
  workflow_dispatch:

concurrency:
  group: deploy-production
  cancel-in-progress: false

jobs:
  deploy:
    name: Deploy para produção
    runs-on: ubuntu-latest

    steps:
      - name: Deploy via SSH
        uses: appleboy/ssh-action@v1.2.2
        with:
          host: ${{ secrets.DEPLOY_HOST }}
          username: ${{ secrets.DEPLOY_USER }}
          key: ${{ secrets.DEPLOY_SSH_KEY }}
          port: ${{ secrets.DEPLOY_PORT || 22 }}
          command_timeout: 20m
          script: |
            set -euo pipefail
            cd /var/www/meu-projeto
            bash deploy/deploy.sh
```

> **Importante:** não use `script_stop` — foi removido na v1.2.x do `appleboy/ssh-action`. Use `set -e` no script remoto.

### 1.2 Script de deploy no servidor

Crie `deploy/deploy.sh` (veja o arquivo completo em `deploy/deploy.sh` deste repositório).

Para outro projeto Laravel, adapte:

- `APP_DIR` — caminho no servidor
- Nome da chave em `ensure_github_ssh()` (ex.: `meu_projeto_deploy` em vez de `footconnect_deploy`)
- `WEB_USER` — usuário do PHP-FPM (geralmente `www-data`)

Para projetos **sem Laravel**, simplifique o script (remova `artisan`, migrations, etc.) e mantenha apenas:

```bash
git pull --ff-only origin main
composer install --no-dev   # se PHP
npm ci && npm run build     # se Node/Vite
```

---

## Parte 2 — Configurar o servidor

Conecte no servidor:

```bash
ssh root@SEU_SERVIDOR
```

### 2.1 Clonar o projeto (primeira vez)

```bash
mkdir -p /var/www
cd /var/www
git clone https://github.com/org/meu-repo.git meu-projeto
cd meu-projeto

cp .env.example .env
# Edite .env com credenciais de produção
php artisan key:generate
composer install --no-dev
npm ci && npm run build
php artisan migrate --force
```

### 2.2 Chave B — servidor puxa código do GitHub

```bash
ssh-keygen -t ed25519 -C "meu-projeto-deploy" -f ~/.ssh/meu_projeto_deploy -N ""
cat ~/.ssh/meu_projeto_deploy.pub
```

Copie a saída (linha que começa com `ssh-ed25519`).

### 2.3 Registrar GitHub no known_hosts

```bash
mkdir -p ~/.ssh
chmod 700 ~/.ssh
ssh-keyscan -t ed25519,rsa github.com >> ~/.ssh/known_hosts
chmod 600 ~/.ssh/known_hosts
```

### 2.4 Remote Git via SSH

```bash
cd /var/www/meu-projeto
git remote set-url origin git@github.com:org/meu-repo.git
git remote -v
```

### 2.5 Testar pull manualmente

```bash
GIT_SSH_COMMAND="ssh -i ~/.ssh/meu_projeto_deploy -o IdentitiesOnly=yes" git fetch origin main
```

Se funcionar sem erro, a chave B está correta.

### 2.6 Chave A — GitHub Actions entra no servidor

No **seu PC** (Windows PowerShell):

```powershell
ssh-keygen -t ed25519 -C "github-actions-meu-projeto" -f $env:USERPROFILE\.ssh\meu_projeto_actions -N '""'
```

No **servidor**, adicione a chave **pública** ao `authorized_keys` do usuário de deploy:

```bash
nano ~/.ssh/authorized_keys
# Cole a linha do arquivo meu_projeto_actions.pub
chmod 600 ~/.ssh/authorized_keys
```

Teste do PC:

```powershell
ssh -i $env:USERPROFILE\.ssh\meu_projeto_actions root@SEU_SERVIDOR
```

Deve entrar **sem pedir senha**.

---

## Parte 3 — Configurar o GitHub

### 3.1 Deploy key (chave B)

1. Repositório → **Settings** → **Deploy keys** → **Add deploy key**
2. Title: `meu-projeto`
3. Key: conteúdo de `meu_projeto_deploy.pub` (do servidor)
4. Marque **Allow write access** apenas se precisar de push do servidor (normalmente **não**)

### 3.2 Secrets do Actions (chave A)

1. Repositório → **Settings** → **Secrets and variables** → **Actions**
2. Clique **New repository secret** para cada um:

| Secret | Valor |
|--------|-------|
| `DEPLOY_HOST` | IP ou domínio do servidor |
| `DEPLOY_USER` | Usuário SSH (`root`, `deploy`, etc.) |
| `DEPLOY_SSH_KEY` | Conteúdo **completo** da chave privada `meu_projeto_actions` |
| `DEPLOY_PORT` | (opcional) Porta SSH, padrão `22` |

Para copiar a chave privada no Windows:

```powershell
Get-Content $env:USERPROFILE\.ssh\meu_projeto_actions | Set-Clipboard
```

O secret deve incluir:

```
-----BEGIN OPENSSH PRIVATE KEY-----
...
-----END OPENSSH PRIVATE KEY-----
```

> **Variables** não são necessárias — use apenas **Secrets**.

---

## Parte 4 — Ativar e testar

### 4.1 Enviar arquivos para o GitHub

```bash
git add .github/workflows/deploy.yml deploy/deploy.sh
git commit -m "feat: deploy contínuo via GitHub Actions"
git push origin main
```

### 4.2 Testar manualmente no servidor (opcional)

```bash
cd /var/www/meu-projeto
bash deploy/deploy.sh
```

### 4.3 Testar via GitHub Actions

1. **Actions** → workflow **Deploy** → **Run workflow**
2. Ou faça qualquer push na `main`

---

## Checklist final

### Servidor
- [ ] Projeto em `/var/www/meu-projeto` (ou caminho definido)
- [ ] `.env` de produção configurado
- [ ] Chave `meu_projeto_deploy` em `~/.ssh/` do usuário de deploy
- [ ] `known_hosts` com `github.com`
- [ ] `git remote` apontando para `git@github.com:org/meu-repo.git`
- [ ] `git fetch origin main` funciona com `GIT_SSH_COMMAND`
- [ ] Chave pública `meu_projeto_actions` em `authorized_keys`

### GitHub
- [ ] Deploy key (`meu_projeto_deploy.pub`) adicionada
- [ ] Secret `DEPLOY_HOST`
- [ ] Secret `DEPLOY_USER`
- [ ] Secret `DEPLOY_SSH_KEY` (privada de `meu_projeto_actions`)
- [ ] Secret `DEPLOY_PORT` (se não for 22)
- [ ] `.github/workflows/deploy.yml` na branch `main`
- [ ] `deploy/deploy.sh` na branch `main`

### Teste local
- [ ] `ssh -i meu_projeto_actions USER@HOST` entra sem senha

---

## Solução de problemas

| Erro | Causa | Solução |
|------|-------|---------|
| `unable to authenticate, attempted methods [none publickey]` | Chave A incorreta ou ausente | Verifique `DEPLOY_SSH_KEY` e `authorized_keys` |
| `Host key verification failed` | Servidor não conhece GitHub | `ssh-keyscan github.com >> ~/.ssh/known_hosts` |
| `Could not read from remote repository` | Chave B ausente ou deploy key errada | Verifique `meu_projeto_deploy` e Deploy keys no GitHub |
| `Unexpected input(s) 'script_stop'` | Parâmetro removido na v1.2.x | Remova `script_stop` do workflow; use `set -e` no script |
| `bash deploy/deploy.sh: No such file` | Script não está no servidor | Faça push do `deploy/deploy.sh` ou clone o repo |
| `Permission denied` em `storage` | Permissões incorretas | `chown -R www-data:www-data storage bootstrap/cache` |
| Deploy key "Never used" | Normal até o primeiro `git pull` | Rode `git fetch` manualmente no servidor |

---

## Adaptar para outros tipos de projeto

### Node.js / React / Vue (sem Laravel)

Substitua o bloco Laravel por:

```bash
git pull --ff-only origin main
npm ci
npm run build
pm2 restart meu-app   # se usar PM2
```

### PHP sem Laravel

```bash
git pull --ff-only origin main
composer install --no-dev --optimize-autoloader
# Recarregar PHP-FPM se necessário
sudo systemctl reload php8.3-fpm
```

### Múltiplos ambientes (staging + produção)

- Crie workflows separados: `deploy-staging.yml` e `deploy-production.yml`
- Use branches diferentes (`develop` → staging, `main` → produção)
- Secrets distintos: `DEPLOY_HOST_STAGING`, `DEPLOY_HOST_PRODUCTION`, etc.

---

## Referência — FootConnect

| Item | Valor |
|------|-------|
| Repositório | `github.com/simetria-studio/footconnect` |
| Pasta no servidor | `/var/www/footconnect` |
| Chave Actions | `footconnect_actions` |
| Chave Deploy | `footconnect_deploy` |
| Workflow | `.github/workflows/deploy.yml` |
| Script | `deploy/deploy.sh` |
