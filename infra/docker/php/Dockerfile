FROM php:8-fpm

LABEL maintainer="ucan-lab <yes@u-can.pro>"
SHELL ["/bin/bash", "-oeux", "pipefail", "-c"]

# timezone environment
ENV TZ=UTC \
  # locale
  LANG=en_US.UTF-8 \
  LANGUAGE=en_US:en \
  LC_ALL=en_US.UTF-8 \
  # composer environment
  COMPOSER_ALLOW_SUPERUSER=1 \
  COMPOSER_HOME=/composer

COPY --from=composer:2.1 /usr/bin/composer /usr/bin/composer

RUN apt-get update && \
  apt-get -y install git libicu-dev libonig-dev libzip-dev libjpeg62-turbo-dev libpng-dev unzip locales nano && \
  apt-get clean && \
  rm -rf /var/lib/apt/lists/* && \
  locale-gen en_US.UTF-8 && \
  localedef -f UTF-8 -i en_US en_US.UTF-8 && \
  mkdir /var/run/php-fpm && \
  docker-php-ext-install intl pdo_mysql zip bcmath && \
  composer config -g process-timeout 3600 && \
  composer config -g repos.packagist composer https://packagist.org

RUN docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

COPY ./infra/docker/php/php.ini /usr/local/etc/php/php.ini

COPY ../../../src /var/www/html/

WORKDIR /var/www/html/

RUN composer install && \
    composer dump-autoload && \
    php artisan key:generate && \
    php artisan storage:link && \
    chmod -R 777 storage bootstrap/cache

