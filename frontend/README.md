# Ebal Frontend

This folder contains a small React application written in TypeScript.  It is a
Node project managed with **yarn** and uses **Vite** for automatic reloads and
bundling.

Install dependencies and start the dev server from this directory:

```bash
yarn install
yarn dev
```

TypeScript sources live under `src/` and are bundled into `dist/` when running
`yarn build`.  During development Vite serves them directly.  The app provides
three pages:

* **Home** – basic information and a link to the login form.
* **Login** – posts credentials to the backend `/login` endpoint and stores the returned JWT.
* **Dashboard** – shows a message from the backend when authenticated.

The UI automatically switches between light and dark Bootstrap themes based on your operating system preferences.
