FROM node:latest AS npm
WORKDIR /app
COPY . .

RUN npm install && npm run build

FROM php:8.2-fpm
WORKDIR /var/www/html
ARG NODE_VERSION=18

RUN apt-get update \
  && apt-get install -y build-essential zlib1g-dev postgresql-client curl gnupg procps vim git unzip libzip-dev libpq-dev \
  && docker-php-ext-install zip pdo_pgsql && curl -sLS https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - \
    && apt-get install gcc g++ make \
    && apt-get install -y nodejs

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

COPY --from=npm /app .

RUN composer install

EXPOSE 8000
CMD ["/bin/bash", "start.sh"]
