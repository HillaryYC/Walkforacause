# Railway deployment guide (Laravel 11)

This project is configured to deploy with Nixpacks on Railway via `railway.json`.

## 1) Create the Railway project

1. Create a new Railway project and link the repo.
2. Add a database plugin (MySQL or Postgres).

## 2) Configure environment variables

Set these in Railway's service variables:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_KEY` (generate locally with `php artisan key:generate --ansi --show`)
- `APP_URL=https://<your-railway-domain>`
- `DB_CONNECTION=mysql` or `pgsql`
- `DATABASE_URL` (Railway sets this automatically for DB plugins)

Optional but recommended:

- `SESSION_DRIVER=database`
- `QUEUE_CONNECTION=database`
- `CACHE_STORE=database`
- `SESSION_SECURE_COOKIE=true` (requires HTTPS)

## 3) Deploy and run migrations

Deploy the service, then run:

```bash
railway run php artisan migrate --force
```

If you use the public disk:

```bash
railway run php artisan storage:link
```

## 4) Queues and scheduler (optional)

- Queue worker: create a second Railway service or a separate command service with
  `php artisan queue:work --tries=3 --timeout=90`.
- Scheduler: use a Railway cron to run `php artisan schedule:run` every minute.

## Notes

- The build step runs `composer install` and `npm run build`.
- Railway health checks hit `/health`, which returns `OK` without touching the DB.
- File uploads stored on the local disk are ephemeral on Railway; use S3 or a
  Railway volume if you need persistence.
