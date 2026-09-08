FROM webdevops/php-nginx:8.4

ENV WEB_DOCUMENT_ROOT=/app/public
ENV WEB_DOCUMENT_INDEX=index.php
ENV PHP_DISPLAY_ERRORS=1
ENV COMPOSER_ALLOW_SUPERUSER=1

COPY . /app
WORKDIR /app

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts \
 && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
 && chmod -R 777 storage bootstrap/cache

CMD ["supervisord"]
