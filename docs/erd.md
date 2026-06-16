# ERD — Chopped Schiphol

## Entity Relationship Diagram

```mermaid
erDiagram
    USERS {
        bigint id PK
        string name
        string email
        string password
        timestamp email_verified_at
        timestamp created_at
        timestamp updated_at
    }

    LUCHTVAARTMAATSCHAPPIJEN {
        bigint id PK
        string naam
        string iata_code
        string land
        string logo_url
        timestamp created_at
        timestamp updated_at
    }

    GATES {
        bigint id PK
        string terminal
        string nummer
        string type
        timestamp created_at
        timestamp updated_at
    }

    VLUCHTEN {
        bigint id PK
        bigint luchtvaartmaatschappij_id FK
        bigint gate_id FK
        string vlucht_nummer
        string vertrek_luchthaven
        string aankomst_luchthaven
        datetime vertrek_tijd
        datetime aankomst_tijd
        string vliegtuig_type
        decimal prijs_economy
        decimal prijs_business
        int stoelen_economy
        int stoelen_business
        json services
        enum status
        timestamp created_at
        timestamp updated_at
    }

    COORDINATOREN {
        bigint id PK
        bigint luchtvaartmaatschappij_id FK
        string naam
        string gebruikersnaam
        string email
        string wachtwoord
        boolean actief
        timestamp created_at
        timestamp updated_at
    }

    DIRECTEUREN {
        bigint id PK
        string naam
        string gebruikersnaam
        string wachtwoord
        timestamp created_at
        timestamp updated_at
    }

    REIZIGERS {
        bigint id PK
        string naam
        string email
        string telefoon
        string paspoort_nummer
        timestamp created_at
        timestamp updated_at
    }

    BOEKINGEN {
        bigint id PK
        bigint vlucht_id FK
        bigint reiziger_id FK
        string boekings_nummer
        string naam_reiziger
        string email_reiziger
        string telefoon_reiziger
        enum stoelklasse
        enum stoel_voorkeur
        string stoel_nummer
        decimal prijs
        enum status
        timestamp created_at
        timestamp updated_at
    }

    VERLANGLIJSTEN {
        bigint id PK
        bigint coordinator_id FK
        string maatschappij_naam
        string bestemming
        date gewenste_datum
        enum stoelklasse
        enum prioriteit
        text opmerkingen
        timestamp created_at
        timestamp updated_at
    }

    LUCHTVAARTMAATSCHAPPIJEN ||--o{ VLUCHTEN : "exploiteert"
    LUCHTVAARTMAATSCHAPPIJEN ||--o{ COORDINATOREN : "heeft"
    GATES ||--o{ VLUCHTEN : "ontvangt"
    VLUCHTEN ||--o{ BOEKINGEN : "heeft"
    REIZIGERS ||--o{ BOEKINGEN : "plaatst"
    COORDINATOREN ||--o{ VERLANGLIJSTEN : "beheert"
```

---

## Toelichting relaties

| Relatie | Type | Beschrijving |
|---------|------|-------------|
| Luchtvaartmaatschappij → Vluchten | 1:N | Één maatschappij exploiteert meerdere vluchten |
| Luchtvaartmaatschappij → Coordinatoren | 1:N | Één maatschappij heeft meerdere coördinatoren |
| Gate → Vluchten | 1:N | Één gate ontvangt meerdere vluchten (op verschillende tijden) |
| Vlucht → Boekingen | 1:N | Één vlucht heeft meerdere boekingen |
| Reiziger → Boekingen | 1:N | Één reiziger kan meerdere boekingen plaatsen |
| Coordinator → Verlanglijsten | 1:N | Één coördinator heeft een persoonlijke verlanglijst |

---

## Enum waarden

| Tabel | Kolom | Waarden |
|-------|-------|---------|
| vluchten | status | `gepland`, `vertrokken`, `geland`, `geannuleerd` |
| boekingen | stoelklasse | `economy`, `business` |
| boekingen | stoel_voorkeur | `raam`, `midden`, `gangpad` |
| boekingen | status | `in_afwachting`, `bevestigd`, `geannuleerd` |
| verlanglijsten | stoelklasse | `economy`, `business` |
| verlanglijsten | prioriteit | `hoog`, `normaal`, `laag` |
| gates | type | `standaard`, `uitgebreid` |
