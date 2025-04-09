# 🌐 Snapedia — Scorri la conoscenza

Snapedia è un’app mobile che reinventa l’accesso alla cultura: articoli da Wikipedia e contenuti originali, in formato social-first, ispirato a TikTok. Questo repository contiene **frontend e backend** del progetto.

---

## 🧩 Architettura del Progetto

La repository è organizzata in due macro-cartelle:

```bash
snapedia/
│
└── apps/
    ├── snapedia-fe/   # Frontend (React Native + Expo)
    └── snapedia-be/   # Backend (Laravel API)
```

---

## 📱 Frontend — `apps/snapedia-fe`

Una mobile app sviluppata in **React Native + Expo**, con navigazione a feed verticale, contenuti aggiornati da Wikipedia e interfaccia altamente coinvolgente.

### ⚙️ Tech Stack

- React Native (Expo)
- Zustand (state management)
- REST API consumption
- Firebase (auth)
- Wikipedia API integration
- TailwindCSS (via nativewind)

### 🧭 Features principali

- Feed verticale in stile Reels/TikTok
- Articoli Wikipedia (live) con supporto immagini + parsing
- Like / Save globali
- Dettaglio articoli + onboarding
- Profilo utente, ricerca, modalità premium
- Bottom sheet per commenti
- Supporto dark mode (in arrivo)

Per maggiori dettagli: [📘 Leggi il README del frontend](./apps/snapedia-fe/README.md)

---

## 🖥 Backend — `apps/snapedia-be`

Backend RESTful in **Laravel 12**, responsabile della gestione utenti, articoli originali, statistiche e autenticazione avanzata.

### ⚙️ Tech Stack

- Laravel 12 + PHP 8.3
- PostgreSQL
- Sanctum + Fortify (autenticazione + 2FA)
- Redis (cache + utenti online)
- Eloquent Resources + FormRequest
- Dashboard Admin via web

### 🔐 Feature principali

- API REST `/api/v1/...`
- Gestione utenti, articoli, like, salvataggi, commenti
- Dashboard web per Admin
- Protezione con middleware + rate limiting
- Storage immagini locale e compatibile S3
- Test automatizzati con `php artisan test`

Per dettagli tecnici completi: [📘 Leggi il README del backend](./apps/snapedia-be/README.md)

---

## 🚀 Avvio Locale

### 📦 Clonazione

```bash
git clone https://github.com/nome-utente/snapedia.git
cd snapedia
```

### ▶️ Avvio Frontend

```bash
cd apps/snapedia-fe
npm install
npm start
```

> ⚠️ Richiede Node.js ≥ 18 e Expo CLI (`npm install -g expo-cli`)

### 🔧 Avvio Backend

```bash
cd apps/snapedia-be
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

> ⚠️ Richiede PHP ≥ 8.3, Composer, PostgreSQL

---

## 📐 Convenzioni

- ✅ RESTful API con prefisso `/api/v1/`
- ✅ Validazione centralizzata con `FormRequest`
- ✅ Stato globale solo via Zustand
- ✅ Componenti modulari e riutilizzabili (evitare monoliti)
- ❌ Niente logica complessa nel render
- ❌ Zero errori di linting/TS ammessi

---

## 💡 Contribuire

Ogni PR, suggerimento o bug report è benvenuto.

1. Forka il progetto
2. Crea un branch: `feature/nome-feature`
3. Invia la tua Pull Request

---

## 📄 Licenza

MIT License — Made with ❤️ by Team Snapedia  
Contatti: hello@snapedia.app