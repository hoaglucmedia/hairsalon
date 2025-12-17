FROM php:5.6-apache-stretch

RUN apt-get update && apt-get install -y \
    libmysqlclient-dev \
    && docker-php-ext-install mysql \
    && docker-php-ext-enable mysql

COPY . /var/www/html/

EXPOSE 80
