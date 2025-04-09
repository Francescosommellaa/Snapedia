# 🖥 Snapedia Backend — API Laravel 12

Benvenuto nel **backend di Snapedia**, il motore API che alimenta l’esperienza mobile dell'app. Sviluppato in **Laravel 12**, questo servizio REST gestisce utenti, articoli, commenti, salvataggi, autenticazione e analytics.

---

## 🚀 Obiettivo

Fornire un backend scalabile, sicuro e performante per una piattaforma di apprendimento social-based, accessibile in tempo reale da dispositivi mobili.

---

## ⚙️ Stack Tecnologico

- **Framework**: Laravel 12
- **Linguaggio**: PHP 8.3+
- **Database**: PostgreSQL
- **Cache & Stats**: Redis
- **Autenticazione**: Laravel Sanctum + Fortify (2FA)
- **Storage Immagini**: filesystem locale / S3
- **Gestione DB**: TablePlus
- **Testing**: PHPUnit + Laravel Test Helpers

---

## 📁 Struttura Principale

```bash
snapedia-be/
├── app/                 # Modelli, Services, Providers, Policies
├── config/              # Configurazioni Laravel
├── database/            # Migrazioni e Seeder
├── public/              # Entry web + immagini pubbliche
├── routes/              # api.php, web.php
├── resources/           # Views per admin (opzionale)
├── storage/             # File locali
└── tests/               # Unit e Feature test
```

---

## 🔐 Sicurezza e Auth

- ✅ Login con email e password (Laravel Fortify)
- ✅ Autenticazione via **Sanctum** (token-based)
- ✅ Supporto per **2FA** (TOTP)
- ✅ Middleware `auth:sanctum` e `is_admin` su rotte protette

---

## 📊 Dashboard Admin

Accessibile via web solo ad admin autenticati:

- 📈 Analytics live (utenti, articoli, like, salvataggi)
- 🟢 Utenti online (heartbeat via Redis)
- ✍️ Articoli creati dagli utenti

Rotte disponibili:

```http
GET /admin/analytics
```

---

## 🧱 Modelli principali

### 🧑‍💻 User

- `id`, `name`, `surname`, `email`, `phone`, `age`
- `profile_image`, `billing_info`, `2fa_secret`
- Relazioni: `articles`, `likedArticles`, `savedArticles`

### 📄 Article

- `id`, `title`, `slug`, `short_text`, `long_text`
- `image_vertical`, `image_horizontal`
- `likes_count`, `comments_count`, `saves_count`

### 💬 Comment, ❤️ Like, 💾 Save

- Pivot table con timestamps e relazioni `belongsToMany`

---

## ▶️ Setup Locale

### Requisiti

- PHP 8.3+
- Composer
- PostgreSQL
- Redis (opzionale ma consigliato)

### Installazione

```bash
git clone https://github.com/nome-utente/snapedia.git
cd snapedia/apps/snapedia-be

composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

---

## 🧪 Testing

```bash
php artisan test
```

---

## 📘 Best Practices

- API RESTful con prefisso `/api/v1/...`
- Validazione con `FormRequest`
- Eloquent Resources per output JSON pulito
- Autenticazione sicura con middleware
- Redis per cache, utenti attivi e performance
- Rate limiting configurato per protezione mobile

---

## 📦 Produzione

- **Hosting**: DigitalOcean (Droplet + PostgreSQL managed)
- **DB**: accessibile via TablePlus
- **Storage**: `/storage/app/public` symlinkato a `public/storage`

---

## 🧠 Contribuire

1. Forka il progetto
2. Crea un branch `feature/nome-funzionalità`
3. Scrivi test dove necessario
4. Apri una Pull Request

---

## 📄 Licenza

MIT — by Team Snapedia  
📩 hello@snapedia.app