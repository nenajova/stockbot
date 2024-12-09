FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    && docker-php-ext-install \
    intl \
    pdo_mysql \
    zip

RUN a2enmod rewrite

WORKDIR /var/www

COPY . /var/www/

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-scripts --no-autoloader

EXPOSE 80

RUN sed -i 's!/var/www/html!/var/www/html/public!g' \
/etc/apache2/sites-available/000-default.conf

# RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN chmod +x ./entrypoint.sh

ENTRYPOINT ["./entrypoint.sh"]

# CMD [ "apache2-foreground" ]

