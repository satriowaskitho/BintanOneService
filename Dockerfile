# Stage 1: Node.js build for frontend assets
FROM node:20-alpine AS node-builder

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build

# Stage 2: Composer dependencies
FROM serversideup/php:8.3-cli AS composer-builder

USER root
WORKDIR /app

# Install required PHP extensions for Composer/platform checks
RUN install-php-extensions gd

COPY composer.json composer.lock ./
RUN composer install --no-interaction --optimize-autoloader --no-dev --prefer-dist

COPY . .
RUN composer dump-autoload --optimize --no-dev

# Stage 3: Production image
FROM serversideup/php:8.3-fpm-nginx

ENV PHP_OPCACHE_ENABLE=1

USER root
WORKDIR /var/www/html

# Install runtime extension too if the app uses PhpSpreadsheet/Excel in production
RUN install-php-extensions gd

COPY --chown=www-data:www-data --from=composer-builder /app /var/www/html
COPY --chown=www-data:www-data --from=node-builder /app/public/build /var/www/html/public/build

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

USER www-data
EXPOSE 8080
