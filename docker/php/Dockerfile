FROM php:8.1.0-fpm

ARG USERNAME=site
ARG USER_UID
ARG USER_GID

RUN echo "USER_UID: ${USER_UID}"
RUN echo "USER_GID: ${USER_GID}"

# Packages
RUN apt-get update && \
  apt-get install -y \
    curl \
    wget \
    vim \
    openssl \
    libicu-dev \
    zlib1g-dev \
    libc-client-dev

# OPCache + APCu
RUN docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-install opcache

RUN pecl install apcu && docker-php-ext-enable apcu

# Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# User
RUN groupadd --gid $USER_GID $USERNAME \
    && useradd --uid $USER_UID --gid $USER_GID -m $USERNAME \
    # Add user as root
    && apt-get update \
    && apt-get install -y sudo \
    && echo $USERNAME ALL=\(root\) NOPASSWD:ALL > /etc/sudoers.d/$USERNAME \
    && chmod 0440 /etc/sudoers.d/$USERNAME

# Workdir
USER $USERNAME
WORKDIR /var/www
CMD composer install ; php-fpm
EXPOSE 9000