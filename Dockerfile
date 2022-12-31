FROM composer:latest AS vendor

COPY database/ database/

COPY tests/ tests/

COPY composer.json composer.json

COPY composer.lock composer.lock

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

FROM php:8.2.0-apache

RUN apt-get update
RUN a2enmod rewrite

RUN apt-get update && docker-php-ext-install pdo pdo_mysql

# Install zip
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libzip-dev git zip \
    && docker-php-ext-install zip

COPY . /var/www/html
COPY ./.docker/vhost.conf /etc/apache2/sites-available/000-default.conf

COPY --from=vendor /app/vendor/ /var/www/html/vendor/

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && ln -s /var/www/html/storage/app/public public/storage

WORKDIR /var/www/html
