FROM php:8.2-fpm-alpine

RUN apk add --no-cache libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd fileinfo \
    && apk del libpng-dev libjpeg-turbo-dev freetype-dev

COPY php.ini.docker /usr/local/etc/php/conf.d/custom.ini
COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html
