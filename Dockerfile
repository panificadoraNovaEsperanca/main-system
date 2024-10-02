FROM php:8.2-fpm AS composer
WORKDIR /build

RUN apt-get update \
  && apt-get install -y build-essential zlib1g-dev postgresql-client curl gnupg procps vim git unzip libzip-dev libpq-dev gcc g++ make \
  && docker-php-ext-install zip pdo_pgsql 

RUN apt-get install -y libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

RUN pecl install pcov && docker-php-ext-enable pcov

# Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
ENV PATH $PATH:/composer/vendor/bin
RUN composer config --global process-timeout 3600
RUN composer global require "laravel/installer"

COPY . .

RUN composer install

FROM node:18-alpine AS npm
WORKDIR /app
COPY --from=composer /build .

RUN apk add git
RUN yarn install
RUN yarn build

FROM composer AS run
RUN rm -rf /build

WORKDIR /var/www/html

COPY --from=npm /app .
EXPOSE 80
EXPOSE 8080
EXPOSE 443
CMD ["/bin/bash", "start.sh"]
