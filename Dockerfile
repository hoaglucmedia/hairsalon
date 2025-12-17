FROM php:8.2-apache

# Cài extension MySQL cho PHP
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Copy toàn bộ source code vào Apache
COPY . /var/www/html/

# Phân quyền
RUN chown -R www-data:www-data /var/www/html

# Mở cổng 80
EXPOSE 80
