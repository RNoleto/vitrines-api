# 🚀 Guia de Configuração do GitHub Actions para Migrations em Produção

Este guia explica como configurar o GitHub Actions no repositório `vitrines-api` para executar automaticamente `php artisan migrate --force` sempre que alterações forem enviadas ou mescladas na branch `main`.

---

## 🔒 Segredos do GitHub (Repository Secrets)

Como a rede local possui bloqueios de firewall para acesso a bancos de dados externos, a execução das migrations ocorre diretamente dos servidores do GitHub Actions via conexão SSL segura.

### Passo a Passo para Configurar os Secrets no GitHub:

1. Acesse o repositório no GitHub: **`RNoleto/vitrines-api`**
2. Vá em **Settings** (Configurações) > **Secrets and variables** > **Actions**
3. Clique no botão **New repository secret** para cada uma das variáveis abaixo:

| Nome do Secret | Descrição / Exemplo | Obrigatoriedade |
|---|---|---|
| `DATABASE_URL` | URL completa de conexão PostgreSQL (ex: `postgres://user:pass@ep-cool-smoke.us-east-1.aws.neon.tech/neondb?sslmode=require`) | ✅ Recomendado |
| `DB_CONNECTION` | Driver do Banco (ex: `pgsql`) | ✅ Recomendado (Padrão: `pgsql`) |
| `DB_HOST` | Host do Banco (ex: `ep-cool-smoke.us-east-1.aws.neon.tech`) | Se não usar `DATABASE_URL` |
| `DB_PORT` | Porta (ex: `5432`) | Se não usar `DATABASE_URL` |
| `DB_DATABASE` | Nome do Banco de Dados | Se não usar `DATABASE_URL` |
| `DB_USERNAME` | Usuário do Banco de Dados | Se não usar `DATABASE_URL` |
| `DB_PASSWORD` | Senha do Banco de Dados | Se não usar `DATABASE_URL` |
| `APP_KEY` | Chave da aplicação Laravel (ex: `base64:...`) | ✅ Obrigatório |

---

## 🤖 Notificações de Migrations no Discord em Tempo Real:

O workflow `.github/workflows/deploy-migrations.yml` está integrado com o módulo de Webhooks do Discord (`DiscordNotifier`). 

Quando as migrations forem executadas na branch `main`, as seguintes notificações são disparadas automaticamente para os bots cadastrados no painel administrativo com o tipo `migrations-log` ou `errors-log`:

- 🟡 **Início da Execução**: Disparado assim que a rotina de migrations é iniciada no GitHub Actions.
- 🟢 **Sucesso**: Disparado quando todas as migrations são aplicadas com sucesso.
- 🔴 **Falha / Erro**: Disparado caso ocorra qualquer erro de execução ou falha de conexão com o banco de dados.

---

## 🔄 Como Funciona a Automação:

- **Gatilho de Disparo Automático**: Qualquer push ou merge na branch `main` dispara o workflow `.github/workflows/deploy-migrations.yml`.
- **Gatilho Manual (Workflow Dispatch)**: Você também pode acessar a aba **Actions** no GitHub, selecionar **Deploy Database Migrations (Production)** e clicar em **Run workflow** a qualquer momento.
