FROM php:8.1.2-apache

# Update the package list and install necessary packages
RUN apt-get update && apt-get install -y \
    wget zip unzip nano locales libreadline-dev libssl-dev pkg-config libcurl4-gnutls-dev libonig-dev \
    libfreetype6-dev libmcrypt-dev libjpeg-dev libpng-dev libzip-dev  libwebp-dev \
    libicu-dev libxml2-dev libmagickwand-dev libpq-dev imagemagick \
    && apt-get clean && rm -rf /var/lib/apt/lists/* \
    && echo "en_US.UTF-8 UTF-8" > /etc/locale.gen \
    && locale-gen

# Configure and install PHP extensions
RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl

RUN docker-php-ext-configure gd --with-jpeg=/usr/include/ --with-freetype=/usr/include/ \
    && docker-php-ext-install gd

# Install core PHP extensions (removed mysql as it's deprecated)
RUN docker-php-ext-install -j$(nproc) mysqli opcache pdo pdo_mysql zip

# Install PostgreSQL extensions 
RUN docker-php-ext-install pdo_pgsql pgsql

# Install Imagick extension
RUN pecl install imagick \
    && docker-php-ext-enable imagick

# Copy the production php.ini and set Apache document root
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Increase PHP memory limit in php.ini files
RUN sed -ri -e 's!128M!1024M!g' /usr/local/etc/php/php.ini-development
RUN sed -ri -e 's!128M!1024M!g' /usr/local/etc/php/php.ini-production

# Enable Apache mod_rewrite
RUN a2enmod rewrite