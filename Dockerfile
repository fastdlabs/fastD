# syntax=docker/dockerfile:1
#
# FastD is a framework/library, not a runnable application.
# This image installs the framework with development dependencies and runs
# the test suite. Use dobee's Dockerfile for a deployable application image.

FROM php:8.4-cli-bookworm

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        curl \
        libcurl4-openssl-dev \
        libpcre3-dev \
        libsqlite3-dev \
        libssl-dev \
    && pecl install swoole \
    && docker-php-ext-enable swoole \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite sockets \
    && rm -rf /var/lib/apt/lists/* /tmp/pear

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /app

COPY composer.json ./

RUN composer update \
    --prefer-dist \
    --no-interaction \
    --no-progress

COPY . .

CMD ["vendor/bin/phpunit", "--no-coverage"]
