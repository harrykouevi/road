#!/usr/bin/env bash

# Création du fichier .env si manquant
if [ ! -f .env ]; then
  echo "⚙️  Fichier .env manquant, création à partir de .env.example..."
  cp .env.example .env
fi

# Installation des dépendances PHP
if [ -f composer.json ] || [ ! -f vendor/autoload.php ];  then
  echo "📦 Installation des dépendances PHP..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Mise en cache de la configuration Laravel
echo "🧩 Mise en cache de la configuration Laravel..."
php artisan config:cache



# Démarrage du serveur Laravel
echo "🚀 Lancement de Laravel sur le port 80..."
exec php artisan serve --host=0.0.0.0 --port=80

# Démarrage du serveur Apache (ou autre)
# exec "$@"