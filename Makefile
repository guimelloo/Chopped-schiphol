.PHONY: help setup up down build restart shell migrate fresh seed test logs dev \
        build-assets artisan composer tinker key

DC = docker compose

help:
	@echo ""
	@echo "  setup          First-time setup: copy .env, build, start, key, migrate"
	@echo "  up             Start containers (background)"
	@echo "  down           Stop and remove containers"
	@echo "  build          Build / rebuild images"
	@echo "  restart        Restart all containers"
	@echo "  shell          Open a shell inside the app container"
	@echo "  migrate        Run database migrations"
	@echo "  fresh          Drop all tables and re-run migrations + seeders"
	@echo "  seed           Run database seeders"
	@echo "  test           Run PHPUnit test suite"
	@echo "  logs           Tail logs from all containers"
	@echo "  dev            Start Vite dev server (HMR)"
	@echo "  build-assets   Build frontend assets for production"
	@echo "  artisan        Run an Artisan command  (CMD=\"route:list\")"
	@echo "  composer       Run a Composer command  (CMD=\"require foo/bar\")"
	@echo "  tinker         Open Laravel Tinker REPL"
	@echo "  key            Generate APP_KEY"
	@echo ""

setup:
	@[ -f .env ] || cp .env.docker .env
	$(DC) build
	$(DC) up -d
	$(DC) exec app php artisan key:generate
	$(DC) exec app php artisan migrate

up:
	$(DC) up -d

down:
	$(DC) down

build:
	$(DC) build

restart:
	$(DC) restart

shell:
	$(DC) exec app sh

migrate:
	$(DC) exec app php artisan migrate

fresh:
	$(DC) exec app php artisan migrate:fresh --seed

seed:
	$(DC) exec app php artisan db:seed

test:
	$(DC) exec app php artisan test

logs:
	$(DC) logs -f

dev:
	$(DC) --profile dev up node

build-assets:
	$(DC) run --rm node sh -c "npm install && npm run build"

artisan:
	$(DC) exec app php artisan $(CMD)

composer:
	$(DC) exec app composer $(CMD)

tinker:
	$(DC) exec app php artisan tinker

key:
	$(DC) exec app php artisan key:generate
