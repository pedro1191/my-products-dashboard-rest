FROM php:8.2.0-apache

RUN apt-get update
RUN a2enmod rewrite

RUN apt-get update && docker-php-ext-install pdo pdo_mysql

# Install zip
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libzip-dev \
    && docker-php-ext-install zip

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git zip

COPY . /var/www/html
COPY ./.docker/vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
