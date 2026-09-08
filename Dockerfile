FROM richarvey/nginx-php-fpm:3.1.6

COPY . .
WORKDIR /var/www/html

RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs --no-scripts \
 && mkdir -p vendor/composer \
 && printf '%s\n' '<?php' > vendor/composer/platform_check.php

ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1
ENV COMPOSER_DISABLE_PLATFORM_CHECK 1
ENV APP_ENV production
ENV APP_DEBUG true
ENV LOG_CHANNEL stderr
ENV COMPOSER_ALLOW_SUPERUSER 1

CMD ["/start.sh"]
