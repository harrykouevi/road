#!/usr/bin/env bash

# Création du fichier .env si manquant
if [ ! -f .env ]; then
  echo "⚙️  Fichier .env manquant, création à partir de .env.example..."
  cp .env.example .env
fi

# Installation des dépendances PHP
if [ -f composer.json ]; then
  echo "📦 Installation des dépendances PHP..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Mise en cache de la configuration Laravel
echo "🧩 Mise en cache de la configuration Laravel..."
php artisan config:cache

# Attente active que la base soit prête
echo "🔧 ISQL..."
echo "⏳ Attente de la base de données sur $DB_HOST:$DB_PORT..."
until nc -z "$DB_HOST" "$DB_PORT"; do
  echo "⏳ Toujours en attente de la base de données sur $DB_HOST:$DB_PORT..."
  sleep 2
done

echo "✅ Base de données détectée. Lancement des migrations Laravel..."
php artisan migrate --force --verbose

# Préremplissage de la base de données
echo "🌱 Exécution des seeders..."
php artisan db:seed



# Démarrage du serveur Laravel
echo "🚀 Lancement de Laravel sur le port 80..."
exec php artisan serve --host=0.0.0.0 --port=80

# Démarrage du serveur Apache (ou autre)
# exec "$@"