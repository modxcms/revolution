FROM php:7.4-fpm

RUN apt-get update -y
RUN apt-get install -y \
            libbz2-dev \
            libxml2-dev \
            git \
            libzip-dev \
            libc-client-dev \
            libkrb5-dev \
            libpng-dev \
            libjpeg-dev \
            libwebp-dev \
            libfreetype6-dev \
            libkrb5-dev \
            libicu-dev \
            zlib1g-dev \
            zip \
            ffmpeg \
            libmemcached11 \
            libmemcachedutil2 \
            build-essential \
            libmemcached-dev \
            gnupg2 \
            libpq-dev \
            libpq5 \
            libz-dev \
            webp

RUN docker-php-ext-configure gd \
    --with-webp=/usr/include/ \
    --with-freetype=/usr/include/ \
    --with-jpeg=/usr/include/

RUN docker-php-ext-install \
    gd \
    bz2 \
    mysqli \
    opcache \
    pdo_mysql \
    soap \
    zip \
    exif \
    fileinfo

RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl

RUN pecl install xdebug
#RUN pecl install redis
#RUN pecl install memcached

#RUN mkdir /var/opcache
#COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini
#COPY opcache_blacklist.txt /usr/local/etc/php/opcache_blacklist.txt

#RUN echo extension=memcached.so >> /usr/local/etc/php/conf.d/memcached.ini
#RUN docker-php-ext-enable redis xdebug

RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#RUN version=$(php -r "echo PHP_MAJOR_VERSION.PHP_MINOR_VERSION;") \
#    && curl -A "Docker" -o /tmp/blackfire-probe.tar.gz -D - -L -s https://blackfire.io/api/v1/releases/probe/php/linux/amd64/$version \
#    && mkdir -p /tmp/blackfire \
#    && tar zxpf /tmp/blackfire-probe.tar.gz -C /tmp/blackfire \
#    && mv /tmp/blackfire/blackfire-*.so $(php -r "echo ini_get ('extension_dir');")/blackfire.so \
#    && printf "extension=blackfire.so\nblackfire.agent_socket=tcp://blackfire:8707\n" > $PHP_INI_DIR/conf.d/blackfire.ini \
#    && rm -rf /tmp/blackfire /tmp/blackfire-probe.tar.gz

USER 1000