#!/bin/bash
php artisan inicializar:sistema
php artisan serve --host 0.0.0.0 --port $APP_PORT
