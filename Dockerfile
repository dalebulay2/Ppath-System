FROM php:8.2-cli-alpine

COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN composer self-update

WORKDIR /usr/src/app

COPY . .

RUN mkdir -p bootstrap/cache storage/framework/cache storage/framework/sessions storage/framework/views \
    && chmod -R 775 bootstrap storage

RUN composer install --prefer-dist

CMD php artisan serve --host=0.0.0.0 --port=10000
