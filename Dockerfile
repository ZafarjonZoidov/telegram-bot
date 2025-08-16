FROM php:8.2-apache

# Fayllarni Apache web root’ga nusxalash
COPY . /var/www/html/

# bot.php ni asosiy fayl sifatida ko‘rsatish
RUN mv /var/www/html/bot.php /var/www/html/index.php

