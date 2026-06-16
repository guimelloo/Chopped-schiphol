# Technisch Ontwerp — Chopped Schiphol

## 1. Architectuuroverzicht

Chopped Schiphol is een monolithische webapplicatie gebouwd op het **Laravel 13** framework met **Inertia.js** als SPA-brug naar **Vue 3**. De applicatie draait volledig in Docker via drie containers.

```
┌─────────────────────────────────────────────────────┐
│  Browser                                            │
│  Vue 3 (SPA via Inertia.js)                        │
└───────────────────┬─────────────────────────────────┘
                    │ HTTP
┌───────────────────▼─────────────────────────────────┐
│  Nginx (poort 8000)                                 │
│  Statische bestanden + reverse proxy naar PHP-FPM   │
└───────────────────┬─────────────────────────────────┘
                    │ FastCGI
┌───────────────────▼─────────────────────────────────┐
│  PHP-FPM 8.4 (Laravel 13)                          │
│  Controllers · Models · Middleware · Seeder         │
└───────────────────┬─────────────────────────────────┘
                    │ PDO
┌───────────────────▼─────────────────────────────────┐
│  MySQL 8.0                                         │
└─────────────────────────────────────────────────────┘
```

---

## 2. Technologiestack

| Laag | Technologie | Versie |
|------|------------|--------|
| Backend framework | Laravel | 13 |
| Template/SPA bridge | Inertia.js | ^2.0 |
| Frontend framework | Vue | 3 (Composition API) |
| CSS framework | Tailwind CSS | ^3 |
| Build tool | Vite | ^6 |
| Routering (frontend) | Ziggy (tightenco/ziggy) | ^2 |
| Database | MySQL | 8.0 |
| Webserver | Nginx | alpine |
| PHP runtime | PHP-FPM | 8.4-alpine |
| Containerisatie | Docker + Docker Compose | — |

---

## 3. Mapstructuur

```
chopped-schiphol/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── VluchtController.php
│   │   │   ├── BoekingController.php
│   │   │   ├── CoordinatorController.php
│   │   │   └── DirecteurController.php
│   │   └── Middleware/
│   │       ├── CoordinatorMiddleware.php
│   │       └── DirecteurMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Vlucht.php
│       ├── Gate.php
│       ├── Luchtvaartmaatschappij.php
│       ├── Boeking.php
│       ├── Reiziger.php
│       ├── Coordinator.php
│       ├── Directeur.php
│       └── Verlanglijst.php
├── database/
│   ├── migrations/          # Schema per tabel
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   └── js/
│       ├── Layouts/
│       │   ├── SchipholLayout.vue
│       │   └── CoordinatorLayout.vue
│       ├── Pages/
│       │   ├── Welcome.vue
│       │   ├── Vluchten/
│       │   ├── Boekingen/
│       │   ├── Coordinator/
│       │   └── Directeur/
│       └── composables/
│           └── useI18n.js
├── docker/
│   ├── entrypoint.sh
│   └── nginx/default.conf
├── Dockerfile
├── docker-compose.yml
└── docs/
```

---

## 4. Docker-architectuur

### 4.1 Multi-stage build (Dockerfile)

De Dockerfile bestaat uit drie build-stages:

| Stage | Base image | Doel |
|-------|-----------|------|
| `composer-build` | `composer:latest` | PHP-afhankelijkheden installeren (incl. Ziggy) |
| `node-build` | `node:22-alpine` | Frontend assets compileren met Vite |
| Productie | `php:8.4-fpm-alpine` | Runtime met gecompileerde assets |

De node-stage kopieert de `vendor/` map van de composer-stage zodat Ziggy beschikbaar is tijdens de Vite-build.

### 4.2 Volumes

| Volume | Gebruik |
|--------|---------|
| `app-data` | Gedeeld tussen `app` en `nginx`; gevuld vanuit het image bij eerste opstart |
| `mysql-data` | Persistente MySQL-data |

### 4.3 Opstartvolgorde

1. MySQL start en is gezond (healthcheck elke 5 seconden, max 10 pogingen).
2. PHP-container voert `entrypoint.sh` uit:
   - `php artisan migrate --force`
   - `php artisan db:seed --force` (faalt stil als data al bestaat)
   - `exec php-fpm`
3. Nginx accepteert verbindingen en stuurt PHP-verzoeken door via FastCGI.

---

## 5. Authenticatie

De applicatie heeft drie onafhankelijke auth-guards:

| Guard | Model | Tabel | Wachtwoordveld | Route-prefix |
|-------|-------|-------|---------------|-------------|
| `web` | `User` | `users` | `password` | `/login` |
| `coordinator` | `Coordinator` | `coordinatoren` | `wachtwoord` | `/coordinator/inloggen` |
| `directeur` | `Directeur` | `directeuren` | `wachtwoord` | `/directeur/inloggen` |

**Belangrijk:** Laravel's `EloquentUserProvider::retrieveByCredentials()` filtert alleen credential-sleutels die de string `"password"` bevatten. Voor de coordinator en directeur guard wordt de sleutel `'password'` gebruikt bij `Auth::attempt()`, terwijl de waarde afkomstig is van het formulierveld `wachtwoord`.

Wachtwoorden worden automatisch bcrypt-gehasht via het `hashed` cast op de modellen.

---

## 6. Inertia.js & Vue 3

### 6.1 Datadoorstroom

```
Laravel Controller
  └─ Inertia::render('Pagina', ['data' => ...])
       └─ Vue Page Component
            ├─ defineProps(['data'])
            └─ usePage().props.auth.user  (shared prop)
```

### 6.2 Shared props

`HandleInertiaRequests::share()` deelt per request:
- `auth.user` — ingelogde gebruiker (of `null`)
- `flash` — session-berichten

### 6.3 i18n

`resources/js/composables/useI18n.js` biedt een `t(key)` functie. Vertalingen zijn in twee locales (`nl`, `en`) gedefinieerd als JavaScript-objecten. De actieve taal wordt in `localStorage` bewaard.

---

## 7. Betalingssimulatie

De betaling is een **testmodus**: er worden geen echte betalingsverwerkers aangesproken.

- Enige geldig testkaartnummer: `4242 4242 4242 4242`
- Validatie vindt plaats in `BoekingController::store()` via Laravel validatieregel `in:4242424242424242`
- Bij een fout kaartnummer wordt een Nederlandse foutmelding getoond
- Na succesvolle validatie wordt de boeking aangemaakt met status `bevestigd`

---

## 8. Databaseschema (samenvatting)

| Tabel | Primaire sleutel | Belangrijke velden |
|-------|-----------------|-------------------|
| `users` | `id` | `name`, `email`, `password` |
| `luchtvaartmaatschappijen` | `id` | `naam`, `iata_code`, `land` |
| `gates` | `id` | `terminal`, `nummer`, `type` |
| `vluchten` | `id` | `vlucht_nummer`, `vertrek_tijd`, `aankomst_tijd`, `prijs_economy`, `prijs_business`, `status` |
| `coordinatoren` | `id` | `gebruikersnaam`, `wachtwoord`, `luchtvaartmaatschappij_id`, `actief` |
| `directeuren` | `id` | `gebruikersnaam`, `wachtwoord` |
| `reizigers` | `id` | `naam`, `email`, `paspoort_nummer` |
| `boekingen` | `id` | `boekings_nummer`, `vlucht_id`, `reiziger_id`, `stoelklasse`, `prijs`, `status` |
| `verlanglijsten` | `id` | `coordinator_id`, `bestemming`, `prioriteit` |

---

## 9. Beveiligingsmaatregelen

| Maatregel | Implementatie |
|-----------|-------------|
| CSRF-beveiliging | Laravel middleware (standaard actief) |
| XSS-bescherming | Inertia.js escapet automatisch |
| Wachtwoord hashing | bcrypt via Laravel `hashed` cast |
| Routebeveiliging | `CoordinatorMiddleware` en `DirecteurMiddleware` |
| SQL injection | Eloquent ORM (prepared statements) |
| Testbetaling | Geen echte kaartgegevens verwerkt |
