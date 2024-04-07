FROM composer:2.7.2 as composer

COPY database/ database/

COPY composer.json composer.json
COPY composer.lock composer.lock

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

FROM node:20.11.1 as node

RUN mkdir -p /app/public

COPY package.json vite.config.js tailwind.config.js postcss.config.js /app/
COPY resources/ /app/resources/

WORKDIR /app

RUN npm install && npm run build

FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
    pdo \
    pdo_pgsql \
    zip \
    pdo_mysql \
    intl

COPY --from=composer /app/vendor /var/www/vendor
COPY --from=node /app/public/build /var/www/public/build

WORKDIR /var/www

# Install nginx
RUN apt-get update && apt-get install -y nginx

# Copy nginx configuration
COPY docker/nginx.conf /etc/nginx/sites-available/default

# Copy start script
COPY docker/startup.sh /usr/local/bin/start
RUN chmod +x /usr/local/bin/start

COPY --chown=www-data:www-data . /var/www

VOLUME /var/www/env

RUN ln -s /var/www/env/.env /var/www/.env

# Expose port 80
EXPOSE 80

CMD ["start"]
