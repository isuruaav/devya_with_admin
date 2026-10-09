# devya_with_admin

DEVYA CEYLON public website and Laravel/Filament administration system.

The public website is in the project root. The administration application is in `devya/`.

For local setup, copy `devya/.env.example` to `devya/.env`, configure the database, then run the following from `devya/`:

```sh
composer install
php artisan key:generate
php artisan migrate
npm install
npm run build
```

With this project under Wamp's web directory, the login entrance is `http://localhost/devya_ceylon/devya/`.

Environment secrets, local databases, uploaded documents, session data, and dependency directories are excluded from version control.
