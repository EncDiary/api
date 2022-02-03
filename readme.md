# EncDiary API

EncDiary is a web application for keeping an encrypted diary. Symmetric (AES-256-CBC) and asymmetric (RSA) encryption types are used for encryption. The project is based on Lumen PHP server with ReactJS frontend.

This is backend part of EncDiary.

Main Repo is [here](https://github.com/EncDiary/web-app)

## Dependencies

- php >= 7.1.3
- laravel/lumen-framework
- firebase/php-jwt
- ramsey/uuid

## Configuration

Example .env file:

```sh
APP_NAME=EncDiary
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost
APP_TIMEZONE=UTC

LOG_CHANNEL=stack
LOG_SLACK_WEBHOOK_URL=

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret

CACHE_DRIVER=file
QUEUE_CONNECTION=sync

JWT_PRIVATE_KEY="-----BEGIN RSA PRIVATE KEY-----\nTHIS_IS_YOUR_RSA_PRIVATE_KEY\n-----END RSA PRIVATE KEY-----"
JWT_PUBLIC_KEY="-----BEGIN PUBLIC KEY-----\nTHIS_IS_YOUR_RSA_PUBLIC_KEY\n-----END PUBLIC KEY-----"

FRONTEND_APP_URL="https://app1.com, https://app2.com, https://app3.com"

ADMIN_IS_ENABLED=false
```

- APP_ENV - Use `local` in development mode and `production` in production mode
- APP_KEY - You need generate random string
- APP_DEBUG - Turn it off in production mode
- APP_URL - Your API URL (For example: https://api.example.com)
- DB_... - Use your data to connect to your database
- JWT_PRIVATE_KEY & JWT_PUBLIC_KEY - Generate your own keypair (PEM format)
- FRONTEND_APP_URL - Enter url's of your web apps
- ADMIN_IS_ENABLED - Enable it if you use the admin panel

## Production

Upload the API server to the shared hosting (my case). Move the files from the `/public/` folder to `/`. Replace the value of the `$app` variable with `/index.php` on `require __DIR__.'/bootstrap/app.php'`;

Updated code in index.php:

```php
<?php

$app = require __DIR__.'/bootstrap/app.php';
$app->run();
```

## Development

Run development server by executing the following command:

```sh
php -S localhost:8000 -t public
```

## Ping

You can ping server by URL `https://your-api.com/ping`. The server response is `Pong`. If you use Postman or something similar, write Origin explicitly (For example - Origin: https://app1.com)