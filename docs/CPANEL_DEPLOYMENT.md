# cPanel deployment guide (Laravel 11)

## Requirements

- PHP 8.2+ with extensions: bcmath, ctype, fileinfo, json, mbstring, openssl, pdo_mysql, tokenizer, xml, zip.
- MySQL database and a database user.
- SSH/Terminal access in cPanel (recommended) or the ability to upload `vendor/` from local.

## Recommended setup (document root = public/)

1. Build frontend assets locally:

   ```bash
   npm ci
   npm run build
   ```

2. Upload the project to your hosting account.
3. In cPanel, set the domain document root to `/public`.
4. Create `.env` from `.env.example` and fill in values:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=https://your-domain.tld`
   - `APP_KEY` (run `php artisan key:generate --ansi`)
   - `DB_*` credentials
   - `SESSION_SECURE_COOKIE=true` (requires HTTPS)
   - `MAIL_*` if you send email
5. Install PHP dependencies:

   ```bash
   composer install --no-dev --optimize-autoloader
   ```

   If Composer is not available on the server, run this locally and upload the `vendor/` folder.
6. Make storage writable:
   - `storage/`
   - `bootstrap/cache/`
7. Run migrations:

   ```bash
   php artisan migrate --force
   ```

   This includes the `sessions` table used by `SESSION_DRIVER=database`.
8. Create the storage symlink if you use the public disk:

   ```bash
   php artisan storage:link
   ```

9. Cache config/routes/views (optional but recommended in production):

   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

10. Scheduler (cron, optional):

   ```
   * * * * * php /home/USER/path/to/artisan schedule:run >> /dev/null 2>&1
   ```

11. Queue worker (optional if you use queues):
   - Ask your host for Supervisor support, or run a cron that invokes `php artisan queue:work`.

## If you cannot change the document root

If the domain must point to `public_html/`:

1. Copy the contents of `public/` into `public_html/`.
2. Update `public_html/index.php` to point to the real app path, for example:

   ```php
   require __DIR__.'/../laravel/vendor/autoload.php';
   $app = require_once __DIR__.'/../laravel/bootstrap/app.php';
   ```

3. Keep `app/`, `bootstrap/`, `config/`, `database/`, `storage/`, and `vendor/` outside `public_html/`.
4. Make sure `public_html/.htaccess` stays in place.
