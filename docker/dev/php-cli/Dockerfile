FROM composer AS composer

WORKDIR /var/www

COPY composer.json composer.lock ./
RUN set -eux; \
    COMPOSER_ALLOW_SUPERUSER=1 COMPOSER_MEMORY_LIMIT=-1 composer install --no-scripts --no-cache --no-progress --no-interaction;


FROM debian:buster-slim

RUN apt-get update -y \
    && apt-get install -y --no-install-recommends \
        apt-transport-https apt-utils ca-certificates curl gnupg \
    && echo "deb https://packages.sury.org/php/ buster main" >> /etc/apt/sources.list.d/sury.org.list \
    && curl -sS https://packages.sury.org/php/apt.gpg | apt-key add - \
    && apt-get update -y \
    && apt-get install -y --no-install-recommends \
            libfcgi-bin unzip \
            php8.0-cli php8.0-mbstring php8.0-xdebug php8.0-xml \
    && apt-get purge --autoremove -y gnupg \
    && apt-get clean -y \
    && rm -rf /var/lib/apt/lists/* /usr/share/man/* /usr/share/doc/* /var/cache/* /var/log/* /tmp/*

COPY docker/dev/php-cli/xdebug.ini /etc/php/8.0/cli/conf.d/99-xdebug.ini

WORKDIR /var/www

COPY --from=composer /var/www/vendor /var/www/vendor

VOLUME /var/www/var

CMD ["php", "-a"]