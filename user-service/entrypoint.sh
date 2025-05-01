#!/usr/bin/env bash
echo "🔧 ISQL..."
echo "⏳ Attente de la base de données sur $DB_HOST:$DB_PORT..."
# Attente active que la base soit prête
until nc -z "$DB_HOST" "$DB_PORT"; do
  echo "⏳ Attente de la base de données sur $DB_HOST:$DB_PORT..."
  sleep 2
done

echo "✅ Base de données détectée. Lancement des migrations Laravel..."
# Lancer les migrations avec logs détaillés
php artisan migrate --force --verbose

# preremplir certaine parties de la BD
php artisan db:seed   

# Lancer le demarrage du microservice
exec php artisan serve --host=0.0.0.0 --port=80

echo "🚀 Démarrage de l'application Laravel avec la commande : $@"

# Démarrage du serveur Apache (ou autre)
exec "$@"