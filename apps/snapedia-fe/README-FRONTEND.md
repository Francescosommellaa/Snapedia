# 📱 Snapedia Frontend — App Mobile React Native

Benvenuto nel **frontend di Snapedia**, un’app mobile social-first sviluppata con **React Native + Expo**, che porta la conoscenza direttamente nel tuo feed. Progettata per un'esperienza coinvolgente, dinamica e accessibile a tutti.

---

## 🎯 Obiettivo

Rendere l'apprendimento semplice, veloce e visivo, mescolando articoli da Wikipedia e contenuti creati dagli utenti in un feed verticale stile TikTok/Reels.

---

## 🚀 Funzionalità principali

- Feed verticale con swipe continuo
- Articoli da Wikipedia (API live)
- Dettaglio articolo con testo esteso
- Like, Save, Commenti sincronizzati
- Bottom sheet per interazione rapida
- Ricerca in stile Pinterest (masonry layout)
- Supporto tema chiaro/scuro (in arrivo)
- Profilo utente e schermata premium

---

## ⚙️ Stack Tecnologico

- **Framework**: React Native + Expo
- **Stato globale**: Zustand
- **Networking**: Axios + API REST Laravel
- **Autenticazione**: Firebase (in arrivo)
- **Stili**: TailwindCSS (via NativeWind)
- **Parsing Wikipedia**: API custom + `wikipediaApi.ts`

---

## 📂 Struttura del Progetto

```bash
snapedia-fe/
├── App.tsx                # Entry point principale
├── app.json               # Configurazione Expo
├── metro.config.js        # Metro bundler
├── package.json           # Script e dipendenze

├── constants/             # Colori, categorie, temi globali
├── services/              # API Wikipedia, Firebase, Backend
├── store/                 # Zustand stores per auth e utente
├── types/                 # Tipi globali e route types
├── utils/                 # Helpers vari (es. imageUtils, slugify)

└── src/
    ├── context/           # UserContext e provider globali
    ├── navigation/        # Stack e Tab navigators
    └── components/
        ├── ui/            # Componenti riutilizzabili
        └── screens/       # Schermate principali (Feed, Profile, Search, ecc.)
```

---

## ▶️ Avvio Locale

### 1. Installazione

```bash
cd apps/snapedia-fe
npm install
```

### 2. Avvio con Expo

```bash
npm start
```

Oppure:

```bash
npx expo start
```

> 📱 Funziona su iOS, Android ed emulatori (via Expo Go)

---

## 🔄 Aggiungere una nuova schermata

1. Crea il file dentro `components/screens/[modulo]/NewScreen.tsx`
2. Aggiungi la route in `MainNavigator.tsx` o `TabNavigator.tsx`
3. Aggiungi la tipizzazione nel file `types.ts`

---

## 🧠 Tips & Tricks

- 🔹 Le immagini vengono adattate alla connessione tramite `navigator.connection`.
- 🔹 Gli ID degli articoli vengono generati con `slugify()` dal titolo.
- 🔹 Gli SVG e le icone sono in `components/ui/Icon.tsx`.
- 🔹 Gli articoli personalizzati possono essere gestiti via backend.

---

## 🧪 Testing

Al momento il progetto non ha test automatici sul frontend. Presto verranno integrati test con Jest + React Testing Library.

---

## ✨ In arrivo

- Autenticazione completa (Firebase o backend)
- Upload articoli utenti
- Modalità offline
- Personalizzazione feed
- Test e2e con Detox

---

## 💡 Contribuire

1. Forka il repo
2. Crea un branch `feature/nome-funzionalità`
3. Apri una Pull Request descrivendo bene cosa hai fatto

---

## 📄 Licenza

MIT — by Team Snapedia  
📩 hello@snapedia.app