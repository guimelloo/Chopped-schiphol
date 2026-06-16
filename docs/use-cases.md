# Use Cases — Chopped Schiphol

## Actoren

| Actor | Beschrijving |
|-------|-------------|
| Reiziger | Niet-ingelogde of ingelogde bezoeker die vluchten wil boeken |
| Coördinator | Schiphol-medewerker die vluchten en gates beheert |
| Directeur | Leidinggevende met toegang tot omzet- en personeelsdata |
| Systeem | Automatische acties (migraties, seeding, boekingsnummer genereren) |

---

## UC-01: Vlucht zoeken

**Actor:** Reiziger  
**Preconditie:** Geen  
**Postconditie:** Reiziger ziet lijst van passende vluchten  

**Hoofdstroom:**
1. Reiziger navigeert naar `/vluchten/zoek`.
2. Reiziger vult vertrekplaats, aankomstplaats, datum en/of stoelklasse in.
3. Systeem toont passende vluchten gesorteerd op vertrektijd.

**Alternatieve stroom:**
- 3a. Geen vluchten gevonden → systeem toont melding "Geen vluchten gevonden".

---

## UC-02: Vlucht boeken

**Actor:** Reiziger  
**Preconditie:** Reiziger heeft een vlucht geselecteerd  
**Postconditie:** Boeking is aangemaakt met status `bevestigd`  

**Hoofdstroom:**
1. Reiziger klikt op "Boeken" bij een vlucht.
2. Reiziger vult naam, e-mailadres, telefoonnummer, stoelklasse en stoel voorkeur in.
3. Systeem toont bevestigingspagina met prijs excl. en incl. BTW.
4. Reiziger klikt op "Naar betaling".
5. Reiziger klikt op "Vul testkaart automatisch in" en bevestigt betaling.
6. Systeem maakt boeking aan met uniek `SCH-XXXXXXXX` nummer.
7. Reiziger ziet boekingsbevestigingspagina.

**Alternatieve stroom:**
- 5a. Reiziger voert verkeerd kaartnummer in → systeem toont foutmelding "Gebruik de testkaartgegevens".

---

## UC-03: Coördinator inloggen

**Actor:** Coördinator  
**Preconditie:** Coördinator heeft geldige inloggegevens  
**Postconditie:** Coördinator is ingelogd en ziet dashboard  

**Hoofdstroom:**
1. Coördinator navigeert naar `/coordinator/inloggen`.
2. Coördinator vult gebruikersnaam en wachtwoord in.
3. Systeem valideert gegevens via `coordinator` guard.
4. Coördinator wordt doorgestuurd naar dashboard.

**Alternatieve stroom:**
- 3a. Ongeldige gegevens → systeem toont "Gebruikersnaam of wachtwoord is onjuist".
- 3b. Coördinator is inactief → toegang geweigerd.

---

## UC-04: Vlucht aanmaken (Coördinator)

**Actor:** Coördinator  
**Preconditie:** Coördinator is ingelogd  
**Postconditie:** Nieuwe vlucht is opgeslagen in de database  

**Hoofdstroom:**
1. Coördinator navigeert naar "Vluchten toevoegen".
2. Coördinator vult vluchtnummer, maatschappij, luchthavens, tijden, vliegtuigtype, prijzen en status in.
3. Systeem slaat vlucht op en toont bijgewerkt overzicht.

---

## UC-05: Gate toewijzen aan vlucht

**Actor:** Coördinator  
**Preconditie:** Gate en vlucht bestaan  
**Postconditie:** Vlucht is gekoppeld aan gate  

**Hoofdstroom:**
1. Coördinator navigeert naar "Gates".
2. Coördinator selecteert een vlucht bij een gate.
3. Systeem koppelt de vlucht aan de gate (verwijdert eerdere koppeling indien aanwezig).

---

## UC-06: Verlanglijst beheren

**Actor:** Coördinator  
**Preconditie:** Coördinator is ingelogd  
**Postconditie:** Verlanglijstitem is aangemaakt of verwijderd  

**Hoofdstroom:**
1. Coördinator navigeert naar "Verlanglijst".
2. Coördinator voegt bestemming, maatschappij, datum, klasse en prioriteit toe.
3. Systeem slaat item op en toont de lijst gesorteerd op prioriteit.

---

## UC-07: Directeur inloggen

**Actor:** Directeur  
**Preconditie:** Directeur heeft geldige inloggegevens  
**Postconditie:** Directeur ziet dashboard met statistieken  

**Hoofdstroom:**
1. Directeur navigeert naar `/directeur/inloggen`.
2. Directeur vult gebruikersnaam (`directeur`) en wachtwoord in.
3. Systeem valideert via `directeur` guard.
4. Directeur ziet dashboard met omzet, boekingen, coördinatoren en vluchten vandaag.

---

## UC-08: Boekingen inzien (Directeur)

**Actor:** Directeur  
**Preconditie:** Directeur is ingelogd  
**Postconditie:** Directeur ziet gefilterde boekingslijst  

**Hoofdstroom:**
1. Directeur navigeert naar "Boekingen".
2. Directeur filtert optioneel op naam, e-mail, status of datumperiode.
3. Systeem toont gefilterde boekingen met totale omzetstatistieken.

---

## UC-09: Coördinator beheren (Directeur)

**Actor:** Directeur  
**Preconditie:** Directeur is ingelogd  
**Postconditie:** Coördinator is aangemaakt, gewijzigd of verwijderd  

**Hoofdstroom:**
1. Directeur navigeert naar "Coördinatoren".
2. Directeur kan nieuwe coördinator aanmaken met naam, gebruikersnaam, wachtwoord en maatschappij.
3. Directeur kan bestaande coördinator wijzigen of deactiveren.
4. Directeur kan coördinator verwijderen.

---

## UC-10: Systeem start op (Docker)

**Actor:** Systeem  
**Preconditie:** Docker is geïnstalleerd  
**Postconditie:** Database is aangemaakt, gemigreerd en gevuld  

**Hoofdstroom:**
1. `docker compose up --build` wordt uitgevoerd.
2. MySQL-container start en is gezond (healthcheck).
3. PHP-container voert `php artisan migrate --force` uit.
4. Systeem voert `php artisan db:seed --force` uit (alleen eerste keer).
5. PHP-FPM start en Nginx accepteert verbindingen op poort 8000.
