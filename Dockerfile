ARG PHP_VERSION=8.2

FROM php:${PHP_VERSION}

# Easy installation of php extensions with all their APK/APT dependencies
# https://github.com/mlocati/docker-php-extension-installer#with-curl
RUN curl -sSL https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions -o - | sh -s \
    json \
    zip \
    curl \
    pcntl

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

RUN apt update -yqq && \
    apt install bash-completion && \
    echo "source /etc/profile.d/bash_completion.sh" >> ~/.bashrc
