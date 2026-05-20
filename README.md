# chopped-schiphol

Laravel-applicatie die draait met Docker.

## Vereisten

- [Docker](https://www.docker.com/) en Docker Compose geïnstalleerd

## Hoe te draaien

### Eerste keer

```bash
make setup
```

Dit doet het volgende: `.env` kopiëren, images bouwen, containers opstarten, de applicatiesleutel genereren en migrations uitvoeren.

### Project opstarten (na de setup)

```bash
make up
```

De applicatie is beschikbaar op [http://localhost:8000](http://localhost:8000).

### Project stoppen

```bash
make down
```

## Andere handige commando's

| Commando | Wat het doet |
|---|---|
| `make dev` | Start Vite met hot reload |
| `make migrate` | Voert de migrations uit |
| `make fresh` | Herstelt de database en voert seeds uit |
| `make test` | Voert de tests uit |
| `make shell` | Opent een shell in de app-container |
| `make logs` | Toont de logs van de containers |
| `make tinker` | Opent Tinker (Laravel REPL) |

Voor alle beschikbare commando's: `make help`
