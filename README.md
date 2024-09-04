# Installation

```bash
composer install
```

## Prepare the database

Create the database locally and change the following environment variables in the `.env` file to match your database credentials:

```dotenv
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=secret
```

Then run the following command to create the tables:
```bash
php artisan migrate:install;
php artisan migrate:fresh;
```

## Run the server

```bash
php artisan serve
```

## Execute the tests

```bash
./vendor/bin/phpunit
```

## Execute the E2E tests

```bash
php artisan dusk
```
