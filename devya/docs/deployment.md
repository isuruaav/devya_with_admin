# Deployment and Security

The website and the Laravel application remain separate. Locally, the website
is `http://localhost/devya_ceylon/` and the system is
`http://localhost/devya_ceylon/devya/public/admin`. `/devya/` redirects to login.

## Web Server

The checked-in Apache `.htaccess` rules deny private application directories,
dotfiles, logs, database dumps, and confidential legacy uploads. Do not deploy
to a server that ignores these rules. A production server must expose only
the Laravel `public` directory, preferably keeping the application outside
the website's document root.

An Apache virtual host can preserve existing URLs with this configuration
(replace paths and configure TLS for the actual domain):

```apache
DocumentRoot /srv/devya-website
Alias /devya/public /srv/devya-system/public
RedirectMatch 302 ^/devya/?$ /devya/public/admin/login

<Directory /srv/devya-website>
    AllowOverride All
    Require all granted
    Options -Indexes
</Directory>

<Directory /srv/devya-system/public>
    AllowOverride All
    Require all granted
    Options -Indexes
</Directory>
```

Before release, verify that `/.git/HEAD`, `/devya/.env`, and
`/devya/storage/logs/laravel.log` return 403 or 404, while login, website
appointments, public images, and authorized document downloads still work.
If this installation has already been accessible to untrusted users, rotate
exposed database and SMTP credentials. Plan APP_KEY rotation with a backup and
an assessment of encrypted data before changing it.

## Release Commands

Back up the database and `storage/app` first, then run:

```sh
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan documents:privatize
php artisan filament:upgrade
php artisan optimize
```

`documents:privatize` preserves stored paths, verifies SHA-256 content before
removing legacy copies, and can be rerun. Conflicting files stop the migration
without deleting the original. A nonzero missing-reference count needs review.
Confidential documents require both a logged-in user and current permissions;
temporary signed links do not bypass those checks.

Set production values in `.env`, not in source control:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.example/devya/public
SESSION_SECURE_COOKIE=true
```

Use a shared cache such as database or Redis for a multi-server installation
so rate limiting and duplicate-request locks work across servers. Appointment
submissions are limited to five per minute and thirty per hour per IP;
matching recent requests reuse the original booking for twenty minutes.

## Email

Email delivery is disabled until a real transport is configured. Logging and
array mailers must not be reported as successful patient email delivery.
Configure provider-specific settings, with an SMTP credential separate from
any system login password:

```dotenv
MAIL_ENABLED=true
MAIL_MAILER=smtp
MAIL_SCHEME=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-smtp-username
MAIL_PASSWORD=your-smtp-secret
MAIL_FROM_ADDRESS=your-verified-sender
MAIL_FROM_NAME="ISD Tech Hub (Pvt) Ltd"
```

Port 587 uses negotiated STARTTLS. Use `MAIL_SCHEME=smtps` with port 465 only
when the provider requires implicit TLS. Clear cached configuration after
changing settings and verify a test delivery to an address you control before
enabling patient mail. Keep `MAIL_ENABLED=false` without usable credentials.
