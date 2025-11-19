# Imagen de PHP con Apache
FROM php:8.2-apache

#Instala la extension PDO MySQL
RUN docker-php-ext-install pdo_mysql

#Copia todo dentro de src
COPY src/ /var/www/html/

#Expone el puerto 1524
EXPOSE 80