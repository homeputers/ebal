# ebal

This project is a simple Yii2 based REST API backend. It uses MySQL for
storage and JSON Web Tokens (JWT) for authentication. Example endpoints are
defined in `SiteController` and demonstrate role based access control.

## Database Migrations

Run the following command from the `backend` directory to apply migrations:

```bash
./yii migrate
```

This will create the required tables such as the `user` table.
