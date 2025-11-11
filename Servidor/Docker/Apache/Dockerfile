# Imagen base de PHP con Apache
FROM php:8.4-rc-apache-trixie

# Instalar extensiones necesarias para MySQL
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install mysqli pdo_mysql \
    && docker-php-ext-enable mysqli pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copiar todo el proyecto al contenedor
COPY . /var/www/html/

# Ajustar permisos de archivos y carpetas
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \;

# Evitar el warning de ServerName
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Configurar Apache para redirigir la raíz al login.php dentro de la subcarpeta
RUN echo '<VirtualHost *:80>\n\
    ServerName localhost\n\
    DocumentRoot /var/www/html\n\
\n\
    # Redirigir raíz al login.php dentro de /backend/login.php\n\
    RedirectMatch ^/$ /backend/login.php\n\
\n\
    <Directory /var/www/html>\n\
        Options +Indexes +FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Habilitar mod_rewrite (por si lo necesitás para .htaccess)
RUN a2enmod rewrite

# Exponer el puerto 80
EXPOSE 80

# Iniciar Apache en primer plano
CMD ["apache2-foreground"]
