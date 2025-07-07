FROM php:7.4-fpm

# Instalar dependencias de sistema
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Crear directorio de la app
WORKDIR /var/www

# Copiar archivos del proyecto (si los hay)
COPY ./ /var/www

#--no-interaction --optimize-autoloader --no-dev

# Permisos
RUN chown -R www-data:www-data /var/www
RUN chown -R www-data:www-data storage bootstrap/cache

# Copiar el script de permisos
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]


EXPOSE 9000

CMD ["php-fpm"]
