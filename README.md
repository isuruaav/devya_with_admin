# devya_with_admin

DEVYA CEYLON public website and Laravel/Filament administration system.

The public website is in the project root. The administration application is in `devya/`.

## PayHere readiness placeholders

The public services page displays a consultation fee of LKR 3,000 plus an additional booking advance of LKR 1,500 (LKR 4,500 when both charges apply) from `includes/site-config.php`. Replace the provisional `legal_name` and set `legal_name_is_example` to `false` only after verifying the exact Business Registration name.

The Laravel appointment administration can issue a signed PayHere link for the LKR 1,500 advance only after staff confirm a website request. The LKR 3,000 consultation fee remains payable at the Centre. Configure `PAYHERE_MODE=sandbox`, `PAYHERE_MERCHANT_ID`, `PAYHERE_MERCHANT_SECRET`, and a publicly reachable HTTPS `PAYHERE_NOTIFY_URL` in `devya/.env`; never commit merchant credentials. Apply the Laravel migrations before using the payment flow. PayHere cannot reach a localhost notification URL, so test callbacks on a publicly reachable sandbox/staging deployment.

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
