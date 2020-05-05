FROM php:7.2-apache
COPY ./php.ini /usr/local/etc/php/
COPY src/*	/var/www/html/
COPY ./apache.conf	/etc/apache2/
RUN docker-php-ext-install mysqli
