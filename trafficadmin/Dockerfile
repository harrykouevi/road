# Étape 1 : Image de base avec PHP et Apache
FROM php:8.2-apache

# Étape 2 : Installation des extensions PHP requises par Laravel
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    netcat-openbsd \
    libpq-dev libmcrypt-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl bcmath

# Étape 3 : Activation du mod_rewrite d'Apache
RUN a2enmod rewrite

# Changer DocumentRoot vers /var/www/laravel/public
RUN sed -i 's|/var/www/html|/var/www/laravel/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Étape 4 : Copier le projet Laravel
COPY . /var/www/laravel

# Étape 6 : Installation de Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


# Étape 5 : Positionnement du répertoire de travail
WORKDIR /var/www/laravel

# Droits d'accès
# Étape 8 : Permissions pour Laravel
RUN chown -R www-data:www-data /var/www/laravel \
    && chmod -R 755 /var/www/laravel

# Étape 9 : Copie du script d’entrée
COPY entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

# Exposer le port 80
EXPOSE 80

# Étape 10 : Point d’entrée et commande par défaut
ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]
