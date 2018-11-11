FROM php:7.0.8-fpm-alpine

RUN apk update && export LC_ALL=en_US.UTF-8 && export LANG=en_US.UTF-8 && export LANGUAGE=en_US.UTF-8 \
    && apk add autoconf automake make gcc g++ libtool pkgconfig libmcrypt-dev \
    && apk update && apk add imagemagick-dev mysql-client \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install mbstring zip xml mcrypt pdo_mysql

COPY . /usr/share/nginx/html

WORKDIR /usr/share/nginx/html

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === '93b54496392c062774670ac18b134c3b3a95e5a5e5c8f1a9f115f203b75bf9a129d5daa8ba6a13e2cc8a1da0806388a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && php composer.phar install --no-dev --no-scripts \
    && rm composer.phar

RUN cp .env.example .env
RUN php artisan storage:link && php artisan jwt:secret && php artisan optimize
RUN chown -R www-data:www-data /usr/share/nginx/html/storage && chown -R www-data:www-data /usr/share/nginx/html/bootstrap