# Stage 1: install Composer packages. Default is production packages only.
FROM composer:2 AS vendor
ARG COMPOSER_FLAGS="--no-dev --optimize-autoloader"
WORKDIR /app
COPY composer.json composer.lock* ./
COPY src/helpers.php src/helpers.php
RUN composer install --no-interaction --no-progress --no-scripts --ignore-platform-reqs $COMPOSER_FLAGS

# Stage 2: the web server. Apache serves only the public/ folder.
FROM php:8.2-apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN a2enmod rewrite \
    && sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
COPY . /var/www/html/
COPY --from=vendor /app/vendor /var/www/html/vendor
