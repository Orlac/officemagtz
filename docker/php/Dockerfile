FROM php:fpm

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_NO_INTERACTION=1 \
    COMPOSER_PROCESS_TIMEOUT=300 \
    COMPOSER_TIMEOUT=300

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libzip-dev curl \
    && docker-php-ext-install pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/* \
    && echo "memory_limit=256M" > /usr/local/etc/php/conf.d/memory.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Каркас Laravel (последняя версия)
RUN set -e; \
    composer create-project --no-scripts --no-interaction --prefer-dist --no-install laravel/laravel /tmp/skeleton \
    && rm -rf /var/www/html \
    && cp -a /tmp/skeleton/. /var/www/ \
    && rm -rf /tmp/skeleton \
    && for i in 1 2 3 4 5; do \
        composer install --no-interaction --prefer-dist --no-scripts && break; \
        echo "Composer install attempt $i failed, retrying..."; \
        sleep 8; \
    done

COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
