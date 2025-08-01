PROJECT_NAME=ebal

# Select which compose file to use (local, qa, prod)
PROFILE ?= qa
COMPOSE_FILE = docker-compose.$(PROFILE).yml

up:
	docker compose -f $(COMPOSE_FILE) up -d

build:
	docker compose -f $(COMPOSE_FILE) build --no-cache

logs:
	docker compose -f $(COMPOSE_FILE) logs -f

down:
	docker compose -f $(COMPOSE_FILE) down

ssh-app:
	docker exec -it $$(docker compose -f $(COMPOSE_FILE) ps -q app) sh

migrate:
	docker exec -it $$(docker compose -f $(COMPOSE_FILE) ps -q app) php yii migrate --interactive=0

rebuild: down build up logs

# Run backend PHP service locally
local-backend:
	php -S 127.0.0.1:9000 -t backend/web backend/web/index.php

# Run frontend Vite web locally
local-frontend:
	cd frontend && npm run dev

# Run PHP database migrations locally
local-migrate:
	DB_DSN="mysql:host=127.0.0.1;port=3306;dbname=app" DB_USER="root" DB_PASS="example" php backend/yii migrate --interactive=0

k8s-apply:
	kubectl apply -f k8s

k8s-delete:
	kubectl delete -f k8s
