# Functioneel Ontwerp — Chopped Schiphol

## 1. Inleiding

**Projectnaam:** Chopped Schiphol  
**Versie:** 1.0  
**Datum:** Juni 2026  

Chopped Schiphol is een webapplicatie voor het beheer van vluchten op Amsterdam Airport Schiphol. Het systeem biedt drie soorten gebruikers: reizigers (publiek), vluchtcoördinatoren en de luchthavens directeur.

---

## 2. Doelstellingen

- Reizigers kunnen beschikbare vluchten bekijken en een boeking plaatsen met een gesimuleerde betaling.
- Coördinatoren beheren vluchten, gates en luchtvaartmaatschappijen via een beveiligd portaal.
- De directeur heeft een overzicht van omzet, boekingen en coördinatoren.

---

## 3. Gebruikersrollen

| Rol | Toegang | Authenticatie |
|-----|---------|---------------|
| Reiziger | Publieke pagina's, vluchten zoeken, boeken | Optioneel (Laravel Auth) |
| Coördinator | Coördinator portaal | Gebruikersnaam + wachtwoord |
| Directeur | Directeur portaal | Gebruikersnaam + wachtwoord |

---

## 4. Functionele vereisten

### 4.1 Reiziger

| ID | Vereiste |
|----|---------|
| F01 | De reiziger kan alle beschikbare vluchten bekijken. |
| F02 | De reiziger kan vluchten filteren op datum, vertrek, aankomst en klasse. |
| F03 | De reiziger kan de detailpagina van een vlucht bekijken. |
| F04 | De reiziger kan een boeking aanmaken (naam, e-mail, telefoon, stoelklasse, voorkeur). |
| F05 | De reiziger ziet een bevestigingspagina met prijsoverzicht incl. BTW (21%). |
| F06 | De reiziger betaalt via een testbetaalformulier (simulatie, geen echte betaling). |
| F07 | Na betaling ontvangt de reiziger een boekingsbevestiging met uniek boekingsnummer. |
| F08 | De reiziger kan inloggen via het gebruikersportaal (Laravel Auth). |

### 4.2 Coördinator

| ID | Vereiste |
|----|---------|
| F09 | De coördinator kan inloggen met gebruikersnaam en wachtwoord. |
| F10 | De coördinator ziet een weekoverzicht van vluchten per luchtvaartmaatschappij. |
| F11 | De coördinator kan vluchten aanmaken, wijzigen en verwijderen. |
| F12 | De coördinator kan gates aanmaken en vluchten aan gates toewijzen. |
| F13 | De coördinator kan luchtvaartmaatschappijen beheren. |
| F14 | De coördinator kan verlanglijstitems aanmaken en verwijderen. |
| F15 | De coördinator kan uitloggen. |

### 4.3 Directeur

| ID | Vereiste |
|----|---------|
| F16 | De directeur kan inloggen met gebruikersnaam en wachtwoord. |
| F17 | De directeur ziet een dashboard met totale omzet, boekingen, coördinatoren en vluchten vandaag. |
| F18 | De directeur kan alle boekingen inzien en filteren op naam, e-mail, status en datum. |
| F19 | De directeur kan coördinatoren aanmaken, wijzigen, activeren/deactiveren en verwijderen. |
| F20 | De directeur kan uitloggen. |

---

## 5. Niet-functionele vereisten

| ID | Vereiste |
|----|---------|
| NF01 | De applicatie draait in Docker (MySQL + PHP-FPM + Nginx). |
| NF02 | De applicatie ondersteunt Nederlands en Engels (i18n). |
| NF03 | De betaling is een simulatie; er worden geen echte gegevens verwerkt. |
| NF04 | Wachtwoorden worden opgeslagen als bcrypt-hash. |
| NF05 | De database wordt automatisch aangemaakt en gevuld bij de eerste Docker-opstart. |
| NF06 | De applicatie is responsief (mobile-first met Tailwind CSS). |

---

## 6. Schermoverzicht

| Scherm | URL | Rol |
|--------|-----|-----|
| Homepage | `/` | Publiek |
| Vluchten overzicht | `/vluchten` | Publiek |
| Vlucht zoeken | `/vluchten/zoek` | Publiek |
| Vlucht detail | `/vluchten/{id}` | Publiek |
| Boeking aanmaken | `/boekingen/nieuw/{vlucht}` | Publiek |
| Boeking bevestigen | `/boekingen/bevestigen` | Publiek |
| Betalen | `/boekingen/betalen` | Publiek |
| Boekingsbevestiging | `/boekingen/{nr}` | Publiek |
| Gebruiker login | `/login` | Reiziger |
| Coördinator login | `/coordinator/inloggen` | Coördinator |
| Coördinator dashboard | `/coordinator/dashboard` | Coördinator |
| Vluchten beheer | `/coordinator/vluchten` | Coördinator |
| Gates beheer | `/coordinator/gates` | Coördinator |
| Maatschappijen beheer | `/coordinator/maatschappijen` | Coördinator |
| Verlanglijst | `/coordinator/verlanglijst` | Coördinator |
| Directeur login | `/directeur/inloggen` | Directeur |
| Directeur dashboard | `/directeur/dashboard` | Directeur |
| Boekingen overzicht | `/directeur/boekingen` | Directeur |
| Coördinatoren beheer | `/directeur/coordinatoren` | Directeur |
