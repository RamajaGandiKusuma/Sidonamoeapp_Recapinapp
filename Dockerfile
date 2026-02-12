FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git zip unzip libpng-dev libonig-dev libxml2-dev \
    libzip-dev libpq-dev curl

RUN docker-php-ext-install pdo pdo_pgsql mbstring zip exif pcntl

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf
