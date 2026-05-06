# ═══════════════════════════════════════════════════════════════════════════════
# CICA-GPRO — Makefile (raccourcis Docker)
# ═══════════════════════════════════════════════════════════════════════════════

.PHONY: help up down restart build logs shell mysql psql redis fresh seed test queue

# Default
help: ## Afficher cette aide
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}'

# ── Lifecycle ─────────────────────────────────────────────────────────────────

up: ## Demarrer tous les services (MySQL)
	docker compose --env-file .env.docker.mysql up -d --build

up-pg: ## Demarrer tous les services (PostgreSQL)
	docker compose --env-file .env.docker.postgres -f docker-compose.yml -f docker-compose.postgres.yml up -d --build

down: ## Arreter tous les services
	docker compose down

restart: ## Redemarrer tous les services
	docker compose restart

build: ## Rebuild les images sans cache
	docker compose build --no-cache

destroy: ## Tout supprimer (containers + volumes + images)
	docker compose down -v --rmi local

# ── Logs ──────────────────────────────────────────────────────────────────────

logs: ## Voir les logs (tous les services)
	docker compose logs -f --tail=100

logs-app: ## Voir les logs de l'app
	docker compose logs -f app --tail=100

logs-queue: ## Voir les logs du queue worker
	docker compose logs -f queue --tail=100

# ── Shell & DB ────────────────────────────────────────────────────────────────

shell: ## Ouvrir un shell dans le container app
	docker compose exec app bash

mysql: ## Ouvrir le client MySQL
	docker compose exec mysql mysql -u gpro -pgpro_secret_2026 cica_gpro

psql: ## Ouvrir le client PostgreSQL
	docker compose exec postgres psql -U gpro -d cica_gpro

redis: ## Ouvrir le client Redis
	docker compose exec redis redis-cli -a gpro_redis_2026

# ── Laravel ───────────────────────────────────────────────────────────────────

artisan: ## Executer une commande artisan (usage: make artisan CMD="migrate:status")
	docker compose exec app php artisan $(CMD)

migrate: ## Lancer les migrations
	docker compose exec app php artisan migrate --force

seed: ## Lancer les seeders
	docker compose exec app php artisan db:seed --force

fresh: ## Reset complet DB (migrate:fresh + seed)
	docker compose exec app php artisan migrate:fresh --seed --force

test: ## Lancer les tests
	docker compose exec app php artisan test --parallel

cache: ## Reconstruire les caches
	docker compose exec app php artisan config:cache
	docker compose exec app php artisan route:cache
	docker compose exec app php artisan view:cache

clear: ## Vider les caches
	docker compose exec app php artisan cache:clear
	docker compose exec app php artisan config:clear
	docker compose exec app php artisan route:clear
	docker compose exec app php artisan view:clear

# ── Backup ────────────────────────────────────────────────────────────────────

backup-mysql: ## Sauvegarder la base MySQL
	docker compose exec mysql mysqldump -u gpro -pgpro_secret_2026 cica_gpro > backup_$(shell date +%Y%m%d_%H%M%S).sql
	@echo "Backup cree: backup_$$(date +%Y%m%d_%H%M%S).sql"

backup-pg: ## Sauvegarder la base PostgreSQL
	docker compose exec postgres pg_dump -U gpro cica_gpro > backup_$(shell date +%Y%m%d_%H%M%S).sql
	@echo "Backup cree: backup_$$(date +%Y%m%d_%H%M%S).sql"

# ── Mise a jour ───────────────────────────────────────────────────────────────

update: ## Mettre a jour depuis les sources (git pull + rebuild)
	@echo "=== Sauvegarde avant mise a jour ==="
	-$(MAKE) backup-mysql 2>/dev/null || $(MAKE) backup-pg 2>/dev/null || true
	@echo "=== Recuperation des sources ==="
	git pull
	@echo "=== Rebuild de l'image ==="
	docker compose build app
	@echo "=== Redemarrage des services ==="
	docker compose up -d
	@echo "=== Attente demarrage (30s) ==="
	sleep 30
	@echo "=== Migrations ==="
	docker compose exec app php artisan migrate --force
	@echo "=== Cache ==="
	docker compose exec app php artisan config:cache
	docker compose exec app php artisan route:cache
	docker compose exec app php artisan view:cache
	@echo ""
	@echo "Mise a jour terminee !"

update-hub: ## Mettre a jour depuis Docker Hub (sans git)
	@echo "=== Sauvegarde avant mise a jour ==="
	-$(MAKE) backup-mysql 2>/dev/null || $(MAKE) backup-pg 2>/dev/null || true
	@echo "=== Telechargement de la nouvelle image ==="
	docker compose pull app
	@echo "=== Redemarrage des services ==="
	docker compose up -d
	@echo "=== Attente demarrage (30s) ==="
	sleep 30
	@echo "=== Migrations ==="
	docker compose exec app php artisan migrate --force
	@echo "=== Cache ==="
	docker compose exec app php artisan config:cache
	docker compose exec app php artisan route:cache
	docker compose exec app php artisan view:cache
	@echo ""
	@echo "Mise a jour terminee !"

# ── Docker Hub ───────────────────────────────────────────────────────────────

DOCKER_REPO ?= jorgo69/cica-gpro
VERSION ?= latest

push-hub: ## Publier l'image sur Docker Hub (usage: make push-hub VERSION=2.0.0)
	@echo "=== Build de l'image ==="
	docker build -t $(DOCKER_REPO):$(VERSION) -t $(DOCKER_REPO):latest .
	@echo "=== Push sur Docker Hub ==="
	docker push $(DOCKER_REPO):$(VERSION)
	docker push $(DOCKER_REPO):latest
	@echo ""
	@echo "Image publiee: $(DOCKER_REPO):$(VERSION)"

pull-hub: ## Telecharger la derniere image depuis Docker Hub
	docker pull $(DOCKER_REPO):latest
