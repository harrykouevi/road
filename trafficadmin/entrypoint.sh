#!/usr/bin/env bash

# Création du fichier .env si manquant
if [ ! -f .env ]; then
  echo "⚙️  Fichier .env manquant, création à partir de .env.example..."
  cp .env.example .env
fi

# Hash actuels
COMPOSER_HASH_FILE="/var/www/laravel/.composer.hash"
CURRENT_HASH=$(md5sum composer.lock composer.json | md5sum | awk '{ print $1 }')

# Si fichier de hash absent ou hash différent => composer install
if [ ! -f "$COMPOSER_HASH_FILE" ] || [ "$(cat $COMPOSER_HASH_FILE)" != "$CURRENT_HASH" ]; then
  echo "📦 Changements détectés dans composer.json ou composer.lock — Installation des dépendances..."
  composer install --no-interaction --prefer-dist --optimize-autoloader

  # Mise à jour du fichier de hash
  echo "$CURRENT_HASH" > "$COMPOSER_HASH_FILE"
else
  echo "✅ Aucun changement dans composer.json — Pas d'installation"
fi

# Mise en cache de la configuration Laravel
echo "🧩 Mise en cache de la configuration Laravel..."
php artisan config:cache



# Démarrage du serveur Apache
echo "🚀 Lancement d'Apache..."
# exec apache2-foreground
exec "$@"