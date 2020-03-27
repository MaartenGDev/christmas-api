FROM php:7.3-apache-stretch
LABEL php="7.3"

# Set Apache work directory
ENV APACHE_DOCUMENT_ROOT /var/www/html
ENV PROJECT_PUBLIC_ROOT /var/www/html/public
WORKDIR $APACHE_DOCUMENT_ROOT

RUN sed -ri -e "s!/var/www/html!${PROJECT_PUBLIC_ROOT}!g" /etc/apache2/sites-available/*.conf
RUN sed -ri -e "s!/var/www/!${PROJECT_PUBLIC_ROOT}!g" /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN sed -ri -e 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Install Laravel PHP requirements
RUN BUILD_DEPENDENCIES="autoconf" \
    DEV_DEPENDENCIES="libcurl4-gnutls-dev \
            libzip-dev \
    	    openssh-client \
    	    iputils-ping \
          libjpeg-dev \
           libjpeg-dev \
            libpng-dev \
    	    git \
    	    zip \
	        gnupg \
	        apt-transport-https \
    	    libmagickwand-dev \
	    unixodbc-dev \
    	    unzip" \
    && docker-php-source extract \
    && apt-get update && apt-get install -y \
        $BUILD_DEPENDENCIES \
        $DEV_DEPENDENCIES \
    && curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/debian/9/prod.list > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update && ACCEPT_EULA=Y apt-get install msodbcsql17 -y \
    && docker-php-ext-install mbstring pdo_mysql curl gd zip opcache\
    && docker-php-ext-configure gd \
        --with-gd \
        --with-png-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/ \
    && pecl install xdebug-2.7.0beta1 imagick sqlsrv pdo_sqlsrv \
    && docker-php-ext-enable xdebug imagick sqlsrv pdo_sqlsrv \
    && php -v \
    && a2enmod rewrite

# Install composer
RUN cd ~ \
    && EXPECTED_SIGNATURE=$(curl -q -sS https://composer.github.io/installer.sig) \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && ACTUAL_SIGNATURE=$(php -r "echo hash_file('SHA384', 'composer-setup.php');") \
    && if [ "$EXPECTED_SIGNATURE" != "$ACTUAL_SIGNATURE" ]; then >&2 echo 'ERROR: Invalid installer signature' && rm composer-setup.php && exit 1; fi \
    && php composer-setup.php --quiet --install-dir=/usr/local/bin --filename=composer \
    && RESULT=$? \
    && composer --version \
    && rm composer-setup.php \
    && echo "" >> composer.lock \
    && mkdir vendor \
    && docker-php-source delete \
    && exit $RESULT

COPY . ${APACHE_DOCUMENT_ROOT}

RUN composer install

RUN cp .env.example .env \
        && php artisan jwt:secret -f \
        && php artisan storage:link && php artisan optimize \
        && chown -R www-data:www-data  ./storage && chmod -R 755 ./storage && chown -R www-data:www-data ./bootstrap \
        && mkdir secrets && touch secrets/.env
