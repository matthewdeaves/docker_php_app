FROM php:fpm-alpine

RUN apk add --update --no-cache autoconf git gcc make build-base pkgconfig imagemagick-dev icu-dev libintl linux-headers

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

# Install and run Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . .
RUN composer install
