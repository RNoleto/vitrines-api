# Vitrines API 🚀

API REST desenvolvida em **Laravel** para servir como backend do projeto Vitrines. O backend gerencia o cadastro de usuários, autenticação integrada com o Firebase, gerenciamento de vitrines (lojas), contatos vinculados e coleta de estatísticas de acesso (visitas).

---

## 🛠️ Tecnologias Utilizadas

* **Framework:** Laravel 11/12
* **Banco de Dados:** PostgreSQL (Ambiente principal) / Suporte a SQLite
* **Autenticação:** Firebase Authentication SDK
* **Armazenamento de Imagens:** Cloudinary (Produção) / Fallback Local (Desenvolvimento offline)
* **Gerenciamento de Dependências:** Composer
* **Ambiente de Hospedagem:** Vercel (Configurado via `vercel.json` e serverless function)

---

## ⚙️ Diferenciais & Soluções Customizadas

### 🔐 Autenticação Offline (Firebase Local Bypass)
Em redes corporativas restritas com bloqueio de acesso a APIs externas, a autenticação padrão do Firebase (que valida chaves criptográficas na nuvem do Google) falha. 
Para solucionar isso, implementamos um **mecanismo de validação JWT offline** no ambiente `local`:
* O middleware `FirebaseAuthenticate` detecta o ambiente de desenvolvimento local.
* Em vez de bater nos servidores do Google, ele realiza a **decodificação base64 offline** do token recebido no header `Authorization: Bearer <token>` para extrair o `firebase_uid` (claim `sub`), `name` e `email`.
* Isso possibilita o desenvolvimento e os testes locais completos de forma **100% offline**.

### 📁 Uploads Locais Offline
Se a variável `CLOUDINARY_URL` não estiver definida no arquivo `.env` local, o sistema detecta a ausência e salva os arquivos (logos de vitrines, fotos de contatos) diretamente no **storage local** do Laravel (`public/storage`), permitindo que a aplicação funcione localmente sem depender de APIs externas de nuvem.

### 🛡️ Tratamento de Chave Única em Contatos
O banco de dados do PostgreSQL possui uma restrição de chave única para `(user_id, whatsapp)` na tabela de contatos. 
* Se um contato cadastrado anteriormente for deletado (via soft delete), o Laravel mantém a linha na base de dados para integridade histórica.
* Ao tentar cadastrar novamente um contato com o mesmo número de WhatsApp, o backend intercepta o cadastro, **restaura** o contato deletado e o reativa (`ativo = 1`), atualizando nome e fotos sem quebrar o banco de dados.

---

## 🚀 Como Executar o Projeto Localmente

### 1. Requisitos
* PHP 8.2 ou superior instalado
* Composer instalado
* Banco de dados PostgreSQL rodando ou SQLite habilitado

### 2. Passo a Passo

1. **Clonar o repositório:**
   ```bash
   git clone <url-do-repositorio>
   cd vitrines-api
   ```

2. **Instalar as dependências do Composer:**
   ```bash
   composer install
   ```

3. **Configurar o Arquivo `.env`:**
   Copie o arquivo `.env.example` para `.env` e preencha as credenciais do seu banco de dados local:
   ```bash
   cp .env.example .env
   ```
   * Configure a conexão com o PostgreSQL:
     ```env
     DB_CONNECTION=pgsql
     DB_HOST=127.0.0.1
     DB_PORT=5432
     DB_DATABASE=vitrine
     DB_USERNAME=postgres
     DB_PASSWORD=sua_senha
     ```

4. **Gerar a chave da aplicação:**
   ```bash
   php artisan key:generate
   ```

5. **Executar as Migrações e Seeds:**
   Cria as tabelas do banco de dados e popula com dados de teste padrões:
   ```bash
   php artisan migrate --seed
   ```

6. **Criar o link simbólico do Storage:**
   Necessário para que o frontend tenha acesso às imagens salvas localmente:
   ```bash
   php artisan storage:link
   ```

7. **Iniciar o Servidor Laravel:**
   ```bash
   php artisan serve
   ```
   O backend estará disponível em `http://127.0.0.1:8000`.

---

## 📁 Estrutura de Pastas Principal

* `app/Http/Controllers/`: Controladores da API (ex: `StoreController`, `ContactController`).
* `app/Http/Middleware/`: Middleware do Laravel (contém a lógica do `FirebaseAuthenticate`).
* `app/Models/`: Modelos Eloquent (`User`, `Store`, `Contact`).
* `database/migrations/`: Arquivos de criação e estruturação de tabelas.
* `routes/api.php`: Rotas REST da aplicação expostas para consumo do frontend.

---

## 🗺️ Principais Endpoints da API

Todas as rotas privadas necessitam do cabeçalho `Authorization: Bearer <Firebase_ID_Token>`.

| Método | Endpoint | Descrição | Requer Auth |
| :--- | :--- | :--- | :---: |
| **POST** | `/api/login` | Cria ou sincroniza os dados do usuário Firebase no banco local | Sim |
| **GET** | `/api/stores` | Lista todas as vitrines do usuário logado | Sim |
| **POST** | `/api/stores` | Cadastra uma nova vitrine (suporta upload de logo) | Sim |
| **PUT** | `/api/stores/{id}` | Edita os dados de uma vitrine do usuário | Sim |
| **DELETE** | `/api/stores/{id}` | Remove uma vitrine | Sim |
| **GET** | `/api/contacts` | Lista os contatos ativos do usuário logado | Sim |
| **POST** | `/api/contacts` | Cria ou restaura/reativa um contato | Sim |
| **DELETE** | `/api/contacts/{id}` | Realiza a desativação lógica (`ativo = 0`) e soft delete do contato | Sim |
| **GET** | `/api/stores/slug/{slug}`| Retorna os dados públicos da vitrine e computa +1 visita | Não |
