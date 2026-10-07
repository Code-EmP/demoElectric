FROM php:8.2-apache
 
RUN apt-get update && apt-get install -y \
git \
zip \
unzip \
libicu-dev
 
RUN docker-php-ext-install intl
 
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
 
WORKDIR /var/www/html
 
COPY . .
 
RUN composer install --no-dev --optimize-autoloader
 
RUN a2enmod rewrite
 
EXPOSE 80