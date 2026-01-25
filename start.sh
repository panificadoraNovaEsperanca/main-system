#!/bin/bash

# Inicializar o Laravel, se necessário
php artisan inicializar:sistema

# Iniciar o PHP-FPM em foreground para manter o container vivo
# O -F faz o PHP-FPM rodar em foreground (não como daemon)
php-fpm -F
 