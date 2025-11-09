FROM php:8.4-rc-apache-trixie
RUN apt-get update && apt-get upgrade -y
WORKDIR /var/www/html
COPY /home/frodriguez/MiPaginaWeb .
EXPOSE 80
