FROM php:7.3-fpm-alpine
ENV VERSION_PHALCON 3.4.5
WORKDIR /root
RUN apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS \
 && docker-php-source extract \
 && curl -L https://github.com/phalcon/cphalcon/archive/v${VERSION_PHALCON}.tar.gz | tar xzf - \
 && cd cphalcon-${VERSION_PHALCON}/build/php7/64bits \
 && phpize && ./configure CFLAGS="-O2 -g" && make -B && make install \
 && cd /root && rm -rf cphalcon-${VERSION_PHALCON} && echo "extension=phalcon.so">/usr/local/etc/php/conf.d/phalcon.ini \
 && docker-php-source delete \
 && apk del .phpize-deps
RUN docker-php-ext-install -j$(getconf _NPROCESSORS_ONLN) opcache pdo_mysql
ENV PHP_MEMORY_LIMIT 256M
ENV PHP_MAX_EXECUTION_TIME 10
ENV PHP_MAX_CHILDREN 20
ENV FPM_REQUEST_TERMINATE_TIMEOUT 10
COPY php-fpm/php-fpm.conf /usr/local/etc/
COPY php-fpm/php.ini /usr/local/etc/php/
WORKDIR "/app"
RUN apk add --no-cache git ffmpeg
COPY app/composer.* /app/
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
 && composer global require "hirak/prestissimo:^0.3" \
 && composer install --prefer-dist
