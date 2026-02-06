# 🎓 Lumilab - Learning Analytics Dashboard

## 📋 Pré-requisitos

- **PHP 8.0+** (testado em PHP 8.4)
- **MySQL 5.7** ou **MariaDB 10.2+**
- **Composer** (gerenciador de dependências PHP)
- **Acesso a uma base Moodle** (PostgreSQL) para importação de dados

## 🚀 Instalação Rápida

### 1. Instalar Dependências

```bash
composer install
```

### 2. Configurar Variáveis de Ambiente

Crie um arquivo `.env` baseado no exemplo:

```bash
cp .env.example .env
```

Edite `.env` com suas credenciais MySQL e Moodle (veja comentários em `.env.example`).

### 3. Inicializar o Banco de Dados

Execute o script de setup:

```bash
php setup.php
```

Isso vai:
- ✅ Criar o banco de dados MySQL se não existir
- ✅ Executar as migrations
- ✅ Carregar o schema completo
- ✅ Importar dados iniciais

## 🌐 Executar a Aplicação

```bash
php yii serve
```

Acesse: **http://localhost:8080**

## 📁 Estrutura do Projeto

```
lumilab/
├── config/              # Configurações da aplicação
├── controllers/         # Controllers (lógica da aplicação)
├── models/             # Models (modelos de dados)
├── views/              # Templates HTML
├── migrations/         # Versionamento de banco de dados
├── database.sql        # Schema completo e dados iniciais
├── setup.php           # Script de inicialização (execute uma vez)
├── .env                # Variáveis de ambiente (não commita)
├── .env.example        # Modelo de .env com documentação
└── README.md           # Este arquivo
```

## 📊 Sistema de Importação de Dados Moodle

O Lumilab é um dashboard de **Learning Analytics** que sincroniza automaticamente com sua base Moodle.

### Como Funciona

- **Conexão automática** ao Moodle (PostgreSQL) durante `setup.php`
- **Importa dados de:** alunos, cursos, conteúdos, atividades, fóruns, quizzes
- **Senhas dos usuários** são importadas do Moodle e funcionam normalmente
- **Sincronização incremental:** apenas novos dados são importados

### 📁 Scripts de Importação

```
import_lumina/
├── import_local.php    # Sincroniza dados do Moodle
├── curl.php           # Cacheia páginas para performance
└── delete_local.php   # Utilitário para limpeza
```

#### Executar Sincronização Manual

```bash
php import_lumina/import_local.php
```

Isso vai:
- ✅ Conectar ao Moodle (PostgreSQL)
- ✅ Importar novos alunos, cursos e atividades
- ✅ Executar processamento de dados
- ✅ Mostrar progresso em tempo real

#### Via Cron (Automático)

```bash
# Executar a cada hora
0 * * * * cd /caminho/para/lumilab && php import_lumina/import_local.php

# Ou a cada 6 horas
0 */6 * * * cd /caminho/para/lumilab && php import_lumina/import_local.php
```

---

## 📝 Notas Importantes

- **Credenciais Moodle:** Configure no `.env` para sincronizar com sua plataforma
- **Senhas importadas:** As senhas dos usuários vêm automaticamente do Moodle
- **Performance:** O cache de páginas (`curl.php`) melhora a velocidade significativamente
- **Segurança:** O arquivo `.env` contém credenciais sensíveis - **nunca commita no git**

