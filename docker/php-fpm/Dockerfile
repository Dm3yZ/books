FROM php:7.4-fpm

ARG APP_USER_ID=1000
ARG APP_USERGROUP_ID=1000

RUN apt-get update \
    && apt-get -y install \
    curl \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install zip pgsql pdo_pgsql \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apt-get -y --purge autoremove \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/{man,doc}

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN usermod -u $APP_USER_ID www-data \
    && groupmod -g $APP_USERGROUP_ID www-data \
    && usermod -d /home/www-data www-data \
    && mkdir -p /home/www-data/.composer \
    && chown -R www-data:www-data /home/www-data/.composer

WORKDIR /var/www

USER www-data

EXPOSE 9000

CMD ["php-fpm"]
