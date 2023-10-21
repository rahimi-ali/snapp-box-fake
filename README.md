## Snapp Box Fake

To allow testing Snapp Box interaction in local and staging environments.

## Install

just run `docker compose up -d` and if it's the first time using it,
run `docker compose exec fpm composer install`
followed by `docker compose exec fpm php artisan migrate`.

## Pages

- `/deliveries`
- `/webhooks`
