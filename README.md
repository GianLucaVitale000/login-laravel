# Login Laravel

[![Laravel Logo](https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg)](https://laravel.com)

> Un sistema di autenticazione Laravel completo con protezione avanzata contro attacchi brute-force

---

## 📌 Descrizione

**Login Laravel** è un'applicazione web sviluppata con Laravel 13 che fornisce un sistema di autenticazione completo e sicuro. Il progetto include registrazione utenti, login, dashboard protetta e **un avanzato sistema di blocco dopo tentativi falliti** per prevenire attacchi brute-force.

---

## ✨ Funzionalità

### 🔐 Autenticazione

- ✅ Registrazione utenti con validazione
- ✅ Login con credenziali
- ✅ Dashboard protetta (solo per utenti autenticati)
- ✅ Logout sicuro con invalidazione della sessione

### 🛡️ Sicurezza

- ✅ **reCAPTCHA v2** sulla registrazione per prevenire registrazioni automatizzate
- ✅ **Blocco login dopo 5 tentativi falliti** per combinazione email+IP
- ✅ **Blocco per IP dopo 20 tentativi** per prevenire attacchi multi-account
- ✅ **Blocco esponenziale**: 1 min → 5 min → 15 min → 1 ora → 4 ore (max)
- ✅ **Prevenzione user enumeration**: messaggi di errore generici
- ✅ Reset automatico dei contatori dopo login corretto

### 🎨 Interfaccia Utente

- ✅ **Navbar responsive** per desktop
- ✅ **Sidebar mobile** per dispositivi mobili
- ✅ **Tema dark/light** con switch automatico su login/registrazione e manuale in dashboard
- ✅ Design moderno e intuitivo

### 🔐 Gestione Password

- ✅ **Scadenza password automatica** dopo 180 giorni
- ✅ **Banner di avviso** nella dashboard per password scadute
- ✅ **Form dedicato** per il cambio password con validazione dei requisiti:
  - Minimo 8 caratteri
  - Almeno una lettera maiuscola
  - Almeno una lettera minuscola
  - Almeno un numero
  - Almeno un simbolo

### 🔧 Tecnico

- ✅ **Redis** per memorizzazione temporanea dei tentativi di login
- ✅ Blocco basato su **email + IP** per massima sicurezza
- ✅ Blocco **solo IP** per prevenire attacchi su più account
- ✅ TTL automatico: i dati si cancellano automaticamente
- ✅ Nessuna dipendenza da database per il sistema di blocco

---

## 📦 Requisiti

- PHP 8.3+
- Composer
- Laravel 13.8+
- MySQL / SQLite
- **Redis Server** (per il sistema di blocco login)
- Node.js (per asset compilation)

---

## 🚀 Installazione

### 1. Clona il repository

```bash
git clone https://github.com/[tuo-username]/login-laravel.git
cd login-laravel
```

### 2. Installa le dipendenze

```bash
composer install
npm install
```

### 3. Configura l'ambiente

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configura il database

Modifica il file `.env` con le tue credenziali:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=login_laravel
DB_USERNAME=root
DB_PASSWORD=tua_password
```

### 5. Esegui le migrazioni

```bash
php artisan migrate
```

### 6. Configura Redis

Assicurati che Redis sia installato e avviato:

```bash
sudo apt install redis-server
sudo systemctl start redis-server
sudo systemctl enable redis-server
```

Nel file `.env`, verifica che:

```env
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=
```

### 7. Configura reCAPTCHA

Regista il tuo sito su [Google reCAPTCHA](https://www.google.com/recaptcha/admin) e aggiungi le chiavi in `.env`:

```env
RECAPTCHA_SITE_KEY=tua_site_key
RECAPTCHA_SECRET_KEY=tua_secret_key
RECAPTCHA_VERSION=v2
```

### 8. Compila gli asset

```bash
npm run build
```

### 9. Avvia il server

```bash
php artisan serve
```

Accedi a: [http://localhost:8000](http://localhost:8000)

---

## 📖 Utilizzo

### Accesso

- **Login**: `/login`
- **Registrazione**: `/register` (con reCAPTCHA)
- **Dashboard**: `/dashboard` (solo per utenti autenticati)
- **Cambio password**: `/reset-password` (accessibile dalla dashboard)

### Credenziali Test User

Il seeder del database crea automaticamente un utente di test con:

- **Email**: `test@example.com`
- **Password**: `Password123!`

Per popolarlo (dopo aver eseguito le migrazioni):

```bash
php artisan db:seed
```

Oppure per resettare tutto:

```bash
php artisan migrate:fresh --seed
```

### Blocco Login

- Dopo **5 tentativi falliti** con la stessa combinazione email+IP, l'account viene bloccato per **1 minuto** (poi 5, 15, 60, 240 minuti in modo esponenziale)
- Dopo **20 tentativi falliti** dallo stesso IP (indipendentemente dall'email), l'IP viene bloccato per **1 ora**
- I contatori si **resettano automaticamente** dopo un login corretto

---

## 🛠️ Configurazione

### Modificare le soglie di blocco

Modifica i valori nel file `app/Http/Controllers/AuthController.php`:

```php
// Blocco email+IP dopo 5 tentativi
if ($attempts >= 5) { ... }

// Blocco IP dopo 20 tentativi
if ($ipAttempts >= 20) { ... }
```

### Modificare i tempi di blocco

```php
// Tempo di blocco esponenziale (in minuti)
$lockoutMinutes = min(240, 5 * pow(2, $attempts - 5));

// Tempo di blocco IP (in minuti)
$ipLockoutMinutes = 60;
```

---

## 🔧 Comandi utili

| Comando | Descrizione |
| --- | --- |
| `php artisan serve` | Avvia il server di sviluppo |
| `php artisan migrate` | Esegui le migrazioni del database |
| `php artisan tinker` | Avvia il REPL di Laravel |
| `npm run dev` | Compila gli asset in sviluppo |
| `npm run build` | Compila gli asset per la produzione |
| `redis-cli ping` | Verifica che Redis sia attivo |
| `sudo systemctl restart redis-server` | Riavvia Redis |

---

## 📂 Struttura del progetto

```text
login-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── AuthController.php     # Controllore autenticazione con blocco login
│   │   └── ...
│   ├── Models/
│   │   └── User.php                   # Modello utente
│   └── ...
├── database/
│   ├── migrations/                    # Migrazioni database
│   └── ...
├── resources/
│   ├── views/
│   │   ├── auth/                      # Viste login e registrazione
│   │   │   ├── login.blade.php
│   │   │   └── register.blade.php
│   │   └── dashboard.blade.php        # Dashboard protetta
│   └── ...
├── routes/
│   └── web.php                        # Rotte dell'applicazione
├── .env                              # Configurazione ambiente
├── composer.json                     # Dipendenze PHP
└── README.md                         # Questo file
```

---

## 🌐 Tecnologie utilizzate

| Tecnologia | Versione | Utilizzo |
| --- | --- | --- |
| Laravel | 13.8 | Framework PHP |
| PHP | 8.3+ | Linguaggio backend |
| MySQL/SQLite | - | Database |
| Redis | 7.x | Cache e blocco login |
| Bootstrap | 5.x | Framework CSS |
| reCAPTCHA | v2 | Protezione registrazione |

---

## 📄 Licenza

Il progetto è open-source e distribuito sotto la licenza **MIT**. Vedi il file [LICENSE](LICENSE) per dettagli.

---
