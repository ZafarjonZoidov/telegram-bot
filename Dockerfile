FROM php:8.2-apache

# MySQL ulanishi uchun kengaytma o‘rnatamiz
RUN docker-php-ext-install mysqli

# Loyihadagi fayllarni Apache server papkasiga nusxalash
COPY . /var/www/html/

# Apache 80-portda ishlaydi
EXPOSE 80

