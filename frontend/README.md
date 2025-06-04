# Ebal Frontend

This folder contains a tiny React application written in TypeScript.  It is now
a small Node project managed with **yarn** and served using `lite-server` for
automatic reloads.

Install dependencies and start the dev server from this directory:

```bash
yarn install
yarn dev
```

TypeScript sources live under `src/` and compile to `dist/`.  Open `index.html`
after running `yarn build` or let the dev server compile on the fly.  The app
provides three pages:

* **Home** – basic information and a link to the login form.
* **Login** – posts credentials to the backend `/login` endpoint and stores the returned JWT.
* **Dashboard** – shows a message from the backend when authenticated.

The UI automatically switches between light and dark Bootstrap themes based on your operating system preferences.
