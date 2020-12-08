FROM php:7.3-fpm

RUN apt-get install msmtp perl wget procps shadow libzip libpng libjpeg-turbo libwebp freetype icu

RUN apt-get install --virtual build-essentials \
    icu-dev icu-libs zlib-dev g++ make automake autoconf shadow libzip-dev curl \
    libpng-dev libwebp-dev libjpeg-turbo-dev freetype-dev && \
    docker-php-ext-configure gd \
    --with-gd \
    --with-freetype-dir=/usr/include/ \
    --with-png-dir=/usr/include/ \
    --with-jpeg-dir=/usr/include/ && \
    docker-php-ext-install gd && \
    docker-php-ext-install intl && \
    docker-php-ext-install opcache && \
    docker-php-ext-install exif && \
    docker-php-ext-install zip && \
    apk del build-essentials && rm -rf /usr/src/php*

RUN wget https://getcomposer.org/composer-stable.phar -O /usr/local/bin/composer && chmod +x /usr/local/bin/composer