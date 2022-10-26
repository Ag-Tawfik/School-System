FROM php:7.4-fpm-alpine

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# RUN apt-get update && apt-get install -y  \
#     libmagickwand-dev \
#     --no-install-recommends \
#     && pecl install imagick \
#     && docker-php-ext-enable imagick \
#     && docker-php-ext-install pdo_mysql

RUN apk add --update --no-cache libzip-dev icu-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    unzip \
    git

RUN apk add --no-cache --update --virtual buildDeps autoconf gcc make g++ zlib-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-configure gd \
    && docker-php-ext-install gd \
    && pecl install igbinary \
    && pecl install redis \
    && docker-php-ext-install -j$(nproc) zip \
    && docker-php-ext-install -j$(nproc) pdo_mysql \
    && docker-php-ext-install -j$(nproc) opcache \
    && docker-php-ext-enable igbinary \
    && docker-php-ext-enable redis \
    && apk del buildDeps

RUN echo memory_limit = -1 >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini;