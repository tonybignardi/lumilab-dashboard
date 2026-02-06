# 🎓 Lumilab - Learning Analytics Dashboard

## 📋 Pré-requisitos

- **PHP 8.0+** (testado em PHP 8.4)
- **MySQL 5.7** ou **MariaDB 10.2+** (banco de dados do dashboard)
- **Composer** (gerenciador de dependências PHP)
- **PostgreSQL Moodle** (opcional - apenas para sincronizar dados)

---

## 🚀 Guia de Instalação

### 1️⃣ Instalar Dependências com Composer

```bash
composer install
```

### 2️⃣ Configurar Variáveis de Ambiente

Crie um arquivo `.env` baseado no exemplo:

```bash
cp .env.example .env
```

Edite `.env` com suas credenciais MySQL locais (obrigatório):

```
DB_HOST=localhost
DB_PORT=3306
DB_NAME=lumix
DB_USER=root
DB_PASSWORD=
```

Se quiser sincronizar dados do Moodle, configure também as credenciais PostgreSQL (opcional - veja comentários em `.env.example`).

### 3️⃣ Inicializar o Banco de Dados

Execute o script de setup:

```bash
php setup.php
```

Isso vai:
- ✅ Criar o banco de dados MySQL local se não existir
- ✅ Carregar o schema do dashboard
- ✅ Importar dados iniciais

### 4️⃣ Importar Dados do Moodle (Opcional)

Se você tem uma base Moodle PostgreSQL e quer sincronizar dados:

**Configurar credenciais no `.env`:**

```
MOODLE_DB_TYPE=pgsql
MOODLE_DB_HOST=seu-servidor-moodle.com
MOODLE_DB_PORT=5432
MOODLE_DB_NAME=moodle
MOODLE_DB_USER=seu_usuario
MOODLE_DB_PASSWORD=sua_senha
```

**Executar sincronização:**

```bash
php import_lumina/import_local.php
```

Isso vai:
- ✅ Conectar ao Moodle PostgreSQL
- ✅ Importar alunos, cursos, conteúdos, atividades, fóruns, quizzes
- ✅ Processar dados e atualizar o MySQL local
- ✅ Gerar arquivos de backup SQL

**Automatizar com Cron:**

```bash
# Sincronizar a cada 6 horas
0 */6 * * * cd /caminho/para/lumilab && php import_lumina/import_local.php
```

### 5️⃣ Executar a Aplicação

```bash
php yii serve
```

Acesse: **http://localhost:8080**

O dashboard está pronto para uso! 🎉

---

## 📁 Estrutura do Projeto

```
lumilab/
├── config/              # Configurações da aplicação
├── controllers/         # Controllers (lógica da aplicação)
├── models/             # Models (modelos de dados MySQL local)
├── views/              # Templates HTML
├── migrations/         # Versionamento de banco de dados
├── database.sql        # Schema completo do dashboard MySQL
├── import_lumina/      # Scripts para sincronizar com Moodle (opcional)
├── setup.php           # Script de inicialização (execute uma vez)
├── .env                # Variáveis de ambiente (não commita)
├── .env.example        # Modelo de .env com documentação
└── README.md           # Este arquivo
```

## 📊 Arquitetura de Dados

O Lumilab funciona com **dois bancos de dados independentes**:

### 🗄️ Banco de Dados Local (MySQL) - **OBRIGATÓRIO**

- Contém o schema do dashboard com tabelas específicas para analytics
- Tabelas: `aluno`, `curso`, `alunoinfo`, `conteudo`, `quiz`, `forum`, etc.
- É o banco que o dashboard utiliza para exibir todos os dados
- Criado automaticamente por `setup.php`

### 🔗 Banco de Dados Moodle (PostgreSQL) - **OPCIONAL**

- Apenas usado para sincronizar dados do Moodle
- Não é consultado durante a operação normal do dashboard
- Configurar apenas se você quer importar dados do Moodle

### 📁 Scripts de Importação

```
import_lumina/
├── import_local.php    # Sincroniza dados Moodle → MySQL local
├── curl.php           # Cacheia páginas para performance
└── delete_local.php   # Utilitário para limpeza de dados
```

---

## 📝 Notas Importantes

- **Banco Local:** O `.env` com credenciais MySQL é **obrigatório** para o dashboard funcionar
- **Moodle:** Credenciais PostgreSQL do Moodle são **opcionais** - configure se vai usar importação
- **Segurança:** O arquivo `.env` contém credenciais - **nunca commita no git**
- **Senhas:** Se sincronizar com Moodle, as senhas dos usuários vêm automaticamente
- **Schema diferente:** O banco local tem tabelas diferentes do Moodle, otimizadas para analytics

