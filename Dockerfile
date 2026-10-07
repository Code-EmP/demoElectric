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

RUN mkdir -p writable/cache writable/logs writable/session
RUN chmod -R 777 writable
 
RUN a2enmod rewrite
 
EXPOSE 80