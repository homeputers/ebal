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

API endpoints for these modules are documented in `openapi.yaml`.

## Database Migrations

Run the following command from the `backend` directory to apply migrations:

```bash
./yii migrate
```

This will create the required tables such as the `user`, `member`, and `group`
tables as well as the join table for member assignments.
