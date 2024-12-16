# GEsports

## Requisitos do Sistema
- PHP >= 8.1
- Composer
- Node.js
- Python

## Instalação e Configuração

### 1. Clonar o Repositório
```bash
git clone [url-do-repositório]
cd GEsports
```

### 2. Instalar Dependências PHP
```bash
composer install
```

### 3. Instalar Dependências JavaScript
```bash
npm install
```

### 4. Configurar o Ambiente
```bash
# Copiar ficheiro de ambiente
cp .env.example .env

# Gerar chave da aplicação
php artisan key:generate
```

### 5. Configurar Ambiente Python
```bash
# Criar ambiente virtual
python -m venv venv

# Ativar ambiente virtual
# No Windows:
.\venv\Scripts\activate
# No Linux/Mac:
source venv/bin/activate

# Instalar dependências Python
cd GEsportsApi
pip install -r requirements.txt
cd ..
```

### 6. Compilar Assets
```bash
npm run build
```

## Iniciar a Aplicação

### Opção 1: Usando o Script Python
```bash
python run_servers.py
```

### Opção 2: Usando o Batch Script (Windows)
```bash
start_servers.bat
```

### Opção 3: Iniciar Servidores Manualmente
```bash
# Terminal 1 - Laravel
php artisan serve

# Terminal 2 - FastAPI
cd GEsportsApi
uvicorn main:app --reload --port 8001
```

## Acesso à Aplicação
- Laravel: http://localhost:8000
- FastAPI: http://localhost:8001
