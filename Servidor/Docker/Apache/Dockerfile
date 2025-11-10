# Base de PHP con Apache
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

# Ajustar permisos: dueño www-data, grupo www-data
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \;

# Configurar login.php como página de inicio
RUN echo '<Directory /var/www/html>\n\
    DirectoryIndex login.php\n\
    Options -Indexes +FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/proyecto.conf \
    && a2enconf proyecto

# Habilitar mod_rewrite de Apache si lo necesitás
RUN a2enmod rewrite

# Exponer puerto 80
EXPOSE 80
