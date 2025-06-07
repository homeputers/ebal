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

The project can be run locally using Docker. Build the images and start the
containers with:

```bash
docker-compose up --build
```

When running with Docker, the React frontend is served at
[http://localhost:8080](http://localhost:8080) and API requests are available
under `/api`. Migrations are executed automatically on startup.

## Running Backend Tests

PHPUnit tests live in the `backend` folder. Change into that directory before
executing the test suite so the Composer autoloader can be found.

```bash
cd backend
composer install
vendor/bin/phpunit --configuration phpunit.xml \
    --coverage-clover coverage.xml
```
