# Piattaforma SaaS Multi-tenant con Dashboard Google Ads

Una piattaforma SaaS completa costruita con Laravel 11+ che offre funzionalità multi-tenant e un modulo dedicato per la gestione delle campagne Google Ads.

## 🚀 Caratteristiche

### Core Platform
- **Multi-tenancy**: Isolamento completo dei dati per tenant (database-per-tenant)
- **Autenticazione**: Laravel Sanctum per API sicure
- **Architettura modulare**: Sistema modulare per aggiungere nuovi tool

### Modulo Google Ads
- **OAuth 2.0**: Autenticazione completa con Google Ads API
- **Gestione Account**: Visualizzazione albero MCC → sub-account
- **Dashboard Campagne**: KPI, filtri periodo, paginazione
- **Export**: Esportazione dati in formato CSV
- **Rate Limiting**: Protezione API con limiti configurabili

## 📋 Requisiti

- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js 18+ (raccomandato 20+)
- Redis (opzionale, per cache/queue)

## 🛠️ Installazione

### 1. Setup Progetto

```bash
# Clone del repository
git clone <your-repo-url>
cd saas-platform-fresh

# Installa dipendenze PHP
composer install

# Installa dipendenze Node.js
npm install

# Copia e configura environment
cp .env.example .env
php artisan key:generate
```

### 2. Configurazione Database

```bash
# Configura il database nel file .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=saas_platform
DB_USERNAME=root
DB_PASSWORD=

# Esegui le migrazioni
php artisan migrate
```

### 3. Configurazione Google Ads

Ottieni le credenziali Google Ads API:
1. Crea un progetto su [Google Cloud Console](https://console.cloud.google.com)
2. Abilita la Google Ads API
3. Crea credenziali OAuth 2.0
4. Ottieni un Developer Token da Google Ads

Configura nel file `.env`:
```env
GOOGLE_ADS_DEVELOPER_TOKEN=your_developer_token
GOOGLE_ADS_CLIENT_ID=your_client_id.googleusercontent.com
GOOGLE_ADS_CLIENT_SECRET=your_client_secret
GOOGLE_ADS_LOGIN_CUSTOMER_ID=your_mcc_customer_id
```

### 4. Avvio Sviluppo

```bash
# Server Laravel
php artisan serve

# Build assets (se necessario)
npm run dev

# Queue worker (opzionale)
php artisan queue:work
```

## 🔐 API Endpoints

### Autenticazione
Tutti gli endpoint richiedono autenticazione Bearer token (Sanctum).

### Google Ads API Routes

```http
# Salva configurazione Google Ads
POST /api/v1/google/config
Content-Type: application/json
Authorization: Bearer {token}

{
    "developer_token": "string",
    "client_id": "string",
    "client_secret": "string",
    "login_customer_id": "string"
}

# Ottieni URL OAuth
GET /api/v1/google/auth/url?client_id={id}&redirect_uri={uri}

# Callback OAuth
GET /api/v1/google/auth/callback?code={code}&client_id={id}&client_secret={secret}&redirect_uri={uri}&credential_id={id}

# Lista account
GET /api/v1/google/accounts?customer_id={id}

# Lista campagne
GET /api/v1/google/campaigns?customer_id={id}&start_date=2024-01-01&end_date=2024-01-31&status=ENABLED&channel=SEARCH&page=1&per_page=20

# Export CSV
GET /api/v1/google/export/csv?customer_id={id}&start_date=2024-01-01&end_date=2024-01-31
```

## 🏗️ Architettura

### Struttura Moduli
```
modules/
├── GoogleAds/
│   ├── Controllers/     # Controller API REST
│   ├── Services/        # Logica business
│   ├── Repositories/    # Data access layer
│   ├── Models/          # Eloquent models
│   ├── DTOs/           # Data Transfer Objects
│   └── Tests/          # Test unitari
```

### Multi-tenancy
- **Strategia**: Database-per-tenant
- **Middleware**: Inizializzazione automatica tenant
- **Isolamento**: Completo tra tenant

### Sicurezza
- **Credenziali**: Cifratura a riposo con chiave app
- **Rate Limiting**: 60 req/min per utente, 1000/min per tenant
- **Validazione**: Strict validation su tutti gli input

## 🧪 Testing

```bash
# Esegui tutti i test
php artisan test

# Test specifici modulo Google Ads
php artisan test --filter=GoogleAds

# Code coverage
php artisan test --coverage
```

## 📊 Logging & Monitoring

```bash
# Logs in tempo reale
php artisan pail

# Horizon (se configurato)
php artisan horizon
```

## 🔧 Configurazione Avanzata

### Rate Limiting
```env
GOOGLE_ADS_RATE_LIMIT_PER_MINUTE=60
GOOGLE_ADS_RATE_LIMIT_PER_TENANT=1000
```

### Cache & Queue
```env
CACHE_STORE=redis
QUEUE_CONNECTION=redis
```

## 🛡️ Sicurezza

- ✅ **Credenziali cifrate**: Tutti i token sono cifrati nel database
- ✅ **Rate limiting**: Protezione contro abuso API
- ✅ **Validazione strict**: Input validation completa
- ✅ **Multi-tenancy**: Isolamento completo dati
- ✅ **HTTPS**: Obbligatorio in produzione

## 📝 Sviluppo

### Aggiungere un nuovo modulo

1. Crea la struttura in `modules/NuovoModulo/`
2. Aggiungi il namespace in `composer.json`
3. Crea controller, service, repository
4. Aggiungi rotte in `routes/api.php`
5. Scrivi test

### Conventional Commits
- `feat:` Nuove funzionalità
- `fix:` Bug fix
- `test:` Aggiunta test
- `chore:` Manutenzione

## 🤝 Contributing

1. Fork del progetto
2. Crea feature branch (`git checkout -b feat/amazing-feature`)
3. Commit changes (`git commit -m 'feat: add amazing feature'`)
4. Push branch (`git push origin feat/amazing-feature`)
5. Apri Pull Request

## 📄 License

Questo progetto è rilasciato sotto licenza [MIT](LICENSE).
