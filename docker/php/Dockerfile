FROM php:8.2-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
    libicu-dev \
    libzip-dev \
    libonig-dev \
    unzip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
        intl \
        mysqli \
        pdo_mysql \
        zip \
        mbstring \
        opcache \
    && a2enmod rewrite \
    && sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
EXPOSE 80
