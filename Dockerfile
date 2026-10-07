FROM php:8.2-apache
 
RUN apt-get update && apt-get install -y \
zip unzip git
 
COPY . /var/www/html
 
WORKDIR /var/www/html
 
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
 
RUN composer install --no-dev --optimize-autoloader
 
RUN a2enmod rewrite
 
EXPOSE 80