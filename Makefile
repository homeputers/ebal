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

k8s-apply:
	kubectl apply -f k8s

k8s-delete:
	kubectl delete -f k8s
