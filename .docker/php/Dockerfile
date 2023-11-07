# DOCKER-VERSION        1.3.2

# Build UI assets
FROM node:20-alpine as assets
RUN apk add git
RUN npm install --location=global gulp-cli
RUN mkdir -p /work
COPY bootstrap /work/
WORKDIR /work
RUN npm install && gulp deploy
# Created assets need to be in an unmapped folder to persist
RUN mkdir -p /assets && mv /work/build/* /assets


# Build the PHP container
FROM php:8.2-fpm-alpine

# System dependencies
RUN mkdir -p /etc/apk && echo 'http://dl-cdn.alpinelinux.org/alpine/v3.16/community' >> /etc/apk/repositories
RUN apk update && \
  apk add --no-cache \
    bash \
    php8-bcmath \
    php8-bz2 \
    php8-dom \
#    php8-intl \
    php8-opcache \
    php8-pcntl \
    php8-sockets \
    php8-xsl \
    php8-zip \
    composer

# PHP configuration
COPY .docker/php/getlaminas.ini /usr/local/etc/php/conf.d/999-getlaminas.ini

# Overwrite entrypoint
COPY .docker/php/php-entrypoint /usr/local/bin/php-entrypoint

# Copy assets
COPY --from=assets /assets/assets.json /assets/assets.json
COPY --from=assets /assets/css /assets/css/
COPY --from=assets /assets/js /assets/js/

# Build project
WORKDIR /var/www

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# RUN composer update
# RUN composer install


ENTRYPOINT ["php-entrypoint"]
CMD ["php-fpm"]
