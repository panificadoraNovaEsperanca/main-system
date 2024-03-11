#!/bin/bash
cat .env
php artisan key:generate
php artisan storage:link
php artisan migrate:fresh --seed --force
php artisan serve --host 0.0.0.0 --port $APP_PORT
