## Snapp Box Fake

To allow testing Snapp Box interaction in local and staging environments.

## Install

just run `docker compose up -d` and if it's the first time using it,
run `docker compose exec fpm composer install`
followed by `docker compose exec fpm php artisan migrate`.

## Pages

- `/deliveries`
- `/webhooks`

## Limitations

- Assumes one pickup and one drop terminal and although it will accept more it won't be able to send status updates for 
them or show them in the delivery view.
- Does not do batching thus might not replicate some more complex response structures.

## Disclaimer

This software is provided as is and does not come with any warranty or support, neither does it claim complete compliance
with SnappBox API as it changes rapidly and is generally not well documented. Nevertheless, I will try to keep it updated
using sample requests and responses from using SnappBox production API.
