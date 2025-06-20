# ebal

"Ebal" stands for "Every Breath and Life" and is a tiny planning portal for our
worship ministry.  It is a lightweight Yii2 powered REST API that keeps track of
team members and the groups they serve in.  Think of it as a mini Planning
Center tailor‑made for our church with room to grow to other congregations.

The service relies on MySQL and secures requests using JSON Web Tokens (JWT).
Admins can manage members, create groups like *guitarists* or *singers*, and
assign people to any number of teams.  Whether it's rehearsals or Sunday
service, ebal keeps everyone on the same page.

Recent additions include lineup templates which define the desired number of
people from each group, and service lineups that assign actual members for a
particular worship service. Templates make it easy to prefill a lineup and then
swap out members as needed.

This update introduces song management. Song categories organize songs, and each
song records its original key and author. Song lists tie songs to a worship
service lineup in a particular order.

API endpoints for these modules are documented in `openapi.yaml`.

## Database Migrations

Run the following command from the `backend` directory to apply migrations:

```bash
./yii migrate
```

This will create the required tables such as the `user`, `member`, and `group`
tables as well as the join table for member assignments.
It also seeds a default **admin** user with password **admin123**.

## Frontend

A lightweight React frontend lives in the `frontend` directory.  It is a small
Node project managed with **yarn** and built with **Vite**.  Install the
dependencies and start the dev server with live reload using:

```bash
cd frontend
yarn install
yarn dev
```

Run `yarn build` to create a production bundle under `dist/`.

## Docker Development

The project can be run locally using Docker with a multi-stage build process. Build the images and start the
containers with:

```bash
docker-compose up --build
```

When running with Docker, the React frontend is served at
[http://localhost:8080](http://localhost:8080) and API requests are available
under `/api`. Migrations are executed automatically on startup.

The Docker setup includes:
- A multi-stage build process that compiles the React frontend and PHP backend
- An Nginx service that serves static files and proxies API requests
- A MariaDB database with persistent storage
- Automatic database migrations on container startup
- Resource limits for each container to optimize performance
- Configurable server name for Nginx, allowing you to serve the application under a custom domain (e.g., `ebal.yourdomain.com`).
  - To set this locally, create a `.env` file in the project root (you can copy `.env.sample` to `.env` and modify it) with the line `ENV_SERVER_NAME=your.desired.domain` (e.g., `ENV_SERVER_NAME=ebal.localhost`).
  - Your `docker-compose.yml` is already set up to use this variable.
  - Remember to update your local `/etc/hosts` file to point your chosen domain to `127.0.0.1` for local testing.

## Deployment

The application can be deployed to an external server using GitHub Actions. The deployment workflow:

1. Connects to the target server via SSH
2. Pulls the latest code from the main branch
3. Rebuilds and starts the Docker containers

To configure deployment:
1. Add the following secrets to your GitHub repository:
   - `SSH_PRIVATE_KEY`: SSH key for connecting to the server
   - `SSH_USER`: Username for SSH connection
   - `SSH_HOST`: Hostname or IP address of the server
   - `APP_DIRECTORY`: Directory path where the application is located on the server
   - `PROD_SERVER_NAME`: The production domain/subdomain where the application will be served (e.g., `ebal.yourdomain.com`). This is used by Nginx to correctly route requests.

2. Trigger the deployment manually from the GitHub Actions tab.

## Running Backend Tests

PHPUnit tests live in the `backend` folder. Change into that directory before
executing the test suite so the Composer autoloader can be found.

```bash
cd backend
composer install
vendor/bin/phpunit --configuration phpunit.xml \
    --coverage-clover coverage.xml
```
