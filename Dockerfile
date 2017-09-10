FROM php:7.1-apache

MAINTAINER TAKAHASHI Kunihiko <kunihiko.takahashi@gmail.com>

COPY ./docker/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY ./docker/php.ini /usr/local/etc/php/php.ini

RUN apt-get update \
  && apt-get install -y git g++ libmcrypt-dev libicu-dev libmcrypt4 libicu52 zlib1g-dev sqlite3 libsqlite3-dev \
  && docker-php-ext-install mbstring mcrypt pdo pdo_sqlite intl zip \
  && pecl install xdebug \
  && docker-php-ext-enable xdebug \
  && apt-get purge --auto-remove -y g++ libmcrypt-dev libicu-dev zlib1g-dev libsqlite3-dev \
  && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
  && curl -sSL https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer \
  && a2enmod rewrite \
  && usermod -u 1000 www-data

WORKDIR /var/www
