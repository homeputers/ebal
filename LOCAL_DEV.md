# Local Development (No Docker)

## Quick Start with Makefile

You can use the provided Makefile for common local development tasks:

```
# Start the backend PHP service (http://localhost:9000)
make local-backend

# Start the frontend Vite dev server (http://localhost:5173)
make local-frontend

# Run PHP database migrations
make local-migrate
```

See below for manual steps if you prefer not to use Makefile.

You can run the backend and frontend directly on your machine, assuming you have MySQL running locally.

## Prerequisites
- Node.js and yarn (or npm)
- PHP 8+
- Composer
- MySQL running on 127.0.0.1 (default user: root, no password, database: app)

## 1. Backend Setup

```
cd backend
composer install
./yii migrate
php -S localhost:9000 -t web
```
- The backend API will be available at http://localhost:9000/api

## 2. Frontend Setup

```
cd frontend
yarn install
yarn dev
```
- The frontend will be available at http://localhost:5173 (default Vite port)
- API requests to /api/* will be proxied to the backend at http://localhost:9000

## 3. Accessing the Site
- Open http://localhost:5173 or http://ebal.lan:5173 in your browser.
- If you want to use http://ebal.lan, add this to your /etc/hosts:
  ```
  127.0.0.1 ebal.lan
  ```

## 4. Configuration
- The backend uses environment variables `DB_DSN`, `DB_USER`, and `DB_PASS` if you want to override the default MySQL connection.
- By default, it connects to `mysql:host=127.0.0.1;dbname=app` as `root` with no password.

## 5. Running Tests
See the main README for backend and frontend test instructions.
