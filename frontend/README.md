# Ebal Frontend

This folder contains a tiny React application written in TypeScript.  It relies on CDN versions of React, React Router and Bootstrap so there are no npm dependencies.

Source files live under `src/` and are compiled to plain JavaScript in `dist/` using the TypeScript compiler:

```bash
cd frontend
tsc
```

Open `index.html` after running `tsc` (or use the precompiled files in `dist`).  The app provides three pages:

* **Home** – basic information and a link to the login form.
* **Login** – posts credentials to the backend `/login` endpoint and stores the returned JWT.
* **Dashboard** – shows a message from the backend when authenticated.

The UI automatically switches between light and dark Bootstrap themes based on your operating system preferences.
