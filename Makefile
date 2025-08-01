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

# Run backend PHP service locally
local-backend:
	php -S 0.0.0.0:9000 -t backend/web backend/web/index.php

# Run frontend Vite web locally
local-frontend:
	cd frontend && npm run dev

# Run PHP database migrations locally
local-migrate:
	DB_DSN="mysql:host=127.0.0.1;port=3306;dbname=app" DB_USER="root" DB_PASS="example" php backend/yii migrate --interactive=0
