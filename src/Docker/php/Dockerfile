FROM php:7.4-fpm-stretch

RUN set -eux; \
    apt-get -y update; \
    apt-get -y install curl less vim libicu-dev zlib1g-dev libc-client-dev \
    wget nano libzip-dev pax-utils sudo gnupg unzip

ARG APCU_VERSION=5.1.12
RUN set -eux; \
    docker-php-ext-configure zip --with-libzip; \
    docker-php-ext-install -j$(nproc) intl mbstring pdo pdo_mysql zip; \
    pecl install apcu-${APCU_VERSION}; \
    pecl clear-cache; \
    docker-php-ext-enable \
        apcu \
        opcache \
    ; \
    \
    runDeps="$( \
        scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
            | tr ',' '\n' \
            | sort -u \
            | awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
    )";
#    apk add --no-cache --virtual .api-phpexts-rundeps $runDeps; \
#    \
#    apk del .build-deps

RUN cd /usr/local/bin; \
    wget https://getcomposer.org/composer.phar; \
    mv composer.phar composer; \
    chmod +x composer;

# to enable xdebug, uncomment this
# RUN (pecl install xdebug || pecl install xdebug-2.5.5) && docker-php-ext-enable xdebug

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]