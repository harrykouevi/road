#!/usr/bin/env bash

# Création du fichier .env si manquant
if [ ! -f .env ]; then
  echo "⚙️  Fichier .env manquant, création à partir de .env.example..."
  cp .env.example .env
fi

# Attente base de données
echo "⏳ Attente de la base de données sur $DB_HOST:$DB_PORT..."
until nc -z "$DB_HOST" "$DB_PORT"; do
  echo "⏳ Toujours en attente de la base de données sur $DB_HOST:$DB_PORT..."
  sleep 2
done

# Hash actuels
COMPOSER_HASH_FILE="/var/www/laravel/.composer.hash"
CURRENT_HASH=$(md5sum composer.lock composer.json | md5sum | awk '{ print $1 }')
# Fichier autoload à vérifier
AUTOLOAD_FILE="/var/www/laravel/vendor/autoload.php"

# Si hash différent OU fichier de hash absent OU vendor/autoload absent => composer install
if [ ! -f "$COMPOSER_HASH_FILE" ] || [ "$(cat $COMPOSER_HASH_FILE)" != "$CURRENT_HASH" ] || [ ! -f "$AUTOLOAD_FILE" ]; then
  echo "📦 Changements détectés ou vendor/autoload.php manquant — Installation des dépendances..."
  composer install --no-interaction --prefer-dist --optimize-autoloader

  # Mise à jour du fichier de hash
  echo "$CURRENT_HASH" > "$COMPOSER_HASH_FILE"
else
  echo "✅ Aucun changement dans composer.json — Pas d'installation"
fi

# Mise en cache de la configuration Laravel
echo "🧩 Mise en cache de la configuration Laravel..."
php artisan config:cache

echo "✅ Base de données détectée. Lancement des migrations Laravel..."
php artisan migrate --force

echo "🌱 Exécution des seeders..."
php artisan db:seed --force

# Création du lien symbolique vers storage, si absent
if [ ! -L public/storage ]; then
  echo "🌱 Lien symbolique vers le dossier storage manquant — Création en cours..."
  php artisan storage:link
else
  echo "✅ Lien symbolique vers storage déjà présent — Pas de création nécessaire"
fi

# Démarrage du serveur Apache
echo "🚀 Lancement d'Apache..."
# exec apache2-foreground
exec "$@"