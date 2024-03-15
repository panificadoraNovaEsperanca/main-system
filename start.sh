#!/bin/bash
php artisan key:generate
php artisan serve --host 0.0.0.0 --port $APP_PORT
