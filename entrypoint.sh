#!/bin/bash

# Asignar permisos correctos
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Continuar con el servicio PHP
exec php-fpm
