FROM php:7.3-fpm

RUN apt-get update -y && apt-get install -y libpng-dev libbz2-dev libxml2-dev imagemagick libzip-dev

RUN docker-php-ext-install gd bz2 mysqli pdo_mysql soap zip

USER 1000