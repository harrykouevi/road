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

# Étape 4 : Copie des fichiers Laravel dans le conteneur
COPY . /var/www/html

# Étape 5 : Positionnement du répertoire de travail
WORKDIR /var/www/html

# Étape 6 : Installation de Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Étape 7 : Permissions pour Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Exposer le port 80
EXPOSE 80

# Étape 8 : Copie du script d’entrée (migration + démarrage)
COPY entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

# Étape 9 : Point d’entrée et commande par défaut
ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]
