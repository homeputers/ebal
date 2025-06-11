PROJECT_NAME=ebal

up:
	docker compose up -d

build:
	docker compose build --no-cache

logs:
	docker compose logs -f

down:
	docker compose down

ssh-app:
	docker exec -it $$(docker compose ps -q app) sh

migrate:
	docker exec -it $$(docker compose ps -q app) php yii migrate --interactive=0

rebuild: down build up logs
