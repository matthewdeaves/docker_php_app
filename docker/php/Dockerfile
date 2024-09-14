FROM php:fpm-alpine

RUN apk add --update --no-cache autoconf git gcc make build-base pkgconfig imagemagick-dev composer icu-dev libintl linux-headers

RUN rm -rf /tmp/imagick

RUN git clone https://github.com/Imagick/imagick.git --depth 1 /tmp/imagick && \
    cd /tmp/imagick && \
    git fetch origin master && \
    git switch master && \
    cd /tmp/imagick && \
    phpize && \
    ./configure && \
    make && \
    make install && \
    apk del git

RUN docker-php-ext-install mysqli pdo pdo_mysql bcmath sockets intl

RUN docker-php-ext-enable imagick pdo_mysql bcmath sockets intl

RUN cd /var/www/html && \
    composer require php-amqplib/php-amqplib && \
    composer update --no-interaction --prefer-dist && \
    composer install --no-interaction --prefer-dist
