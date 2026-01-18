# 📦 Teste Full Stack – Pedidos de Viagem

## 🛠️ Tecnologias Utilizadas

### Backend
- PHP 8+
- Laravel
- JWT Authentication
- MySQL
- Docker / Docker Compose
- PHPUnit (Testes automatizados)

### Frontend
- Vue.js 3
- Vite
- Bootstrap
---

## ⚙️ Funcionalidades Implementadas

### 🔐 Autenticação
- Login via API usando JWT
- Rotas protegidas por autenticação
- Diferenciação entre usuário comum e administrador

---

### ✈️ Pedidos de Viagem
- Criar pedido de viagem
- Listar pedidos
  - Usuário comum visualiza apenas os próprios pedidos
  - Administrador visualiza todos
- Filtro por status (solicitado, aprovado, cancelado)
---

### ✅ Regras de Negócio
- Apenas administradores podem:
  - Aprovar pedidos
  - Cancelar pedidos
- Um pedido **aprovado não pode ser cancelado**
- Validações:
  - Destino obrigatório
  - Datas obrigatórias

---

### 🔔 Notificações
- Usuário recebe notificação quando o pedido é:
  - aprovado
  - cancelado
---

### 🖥️ Interface (Frontend)
- Dashboard com listagem de pedidos
- Modal para criação de pedidos
- Feedback visual com spinner e mensagens de sucesso ou erro

---

## 🧪 Testes Automatizados

Testes de API cobrindo:

- Acesso sem autenticação
- Criação de pedidos válidos e inválidos
- Regras de negócio:
  - Usuário comum tentando alterar status
  - Administrador aprovando pedidos
  - Tentativa de cancelar pedido aprovado

---
# Execução

## ▶️ Execução do Projeto

### 📥 Clonar o repositório
Clone o projeto disponível no GitHub:

```bash
git clone https://github.com/braianrocha/gestao_viagem.git
```


##### 🐳 Subir os containers (Backend)

```bash
docker-compose up -d --build
```

Após o build, os containers estarão em execução:

- `travel_app` — Aplicação (API Laravel)
- `travel_db` — Banco de dados (MySQL)

Verifique o status com:

```bash
docker-compose ps
# ou
docker ps --filter "name=travel_app" --filter "name=travel_db"
```


##### 📦 Instalar dependências do Backend 
```md
cd backend
composer install
```


##### 📦 Instalar dependências do Frontend
```md
cd frontend
npm install
npm run dev
```



##### 🔑 Gerar a chave da aplicação e limpar cache:
```md
docker exec -it travel_api php artisan key:generate
docker exec -it travel_api php artisan config:clear
```

##### Criar banco de dados e popular dados iniciais

```md
docker exec -it travel_api php artisan migrate --seed
```

##### 👤 Usuários de Teste

Usuários gerados pelo seeder:

| Perfil | Email | Senha |
|---|---|---|
| Administrador | `admin@teste.com` | `123456` |
| Usuário comum | `user@teste.com` | `123456` |

Observações:
- Use o administrador para testar ações restritas (aprovar/cancelar pedidos).
- O usuário comum apenas cria e visualiza os próprios pedidos.

### 🧪 Rodar os Testes Automatizados

Para executar os testes de API do backend, utilize o comando abaixo:

```bash
docker exec -it travel_api php artisan test

```

 



