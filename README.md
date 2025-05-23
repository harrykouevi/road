# 🚀 Trafine Project - Microservices with Laravel & Docker

Ce projet repose sur une architecture microservices basée sur Laravel, Docker et MySQL. Chaque service dispose de sa propre base de données et est orchestré via Docker Compose.

## 🧱 Services Inclus

- **user-service** : Gestion des utilisateurs (Laravel + MySQL)
- **traffic-service** : Gestion des données de trafic (Laravel + MySQL)

Chaque service :
- Dispose de son propre conteneur Laravel
- A sa propre base de données MySQL
- Est isolé via un réseau Docker commun `trafine-net`

## 📦 Prérequis

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

## 📁 Structure du projet

```
.
├── docker-compose.yml
├── user-service/
│   ├── Dockerfile
│   ├── entrypoint.sh
│   ├── .env
├── traffic-service/
│   ├── Dockerfile
│   ├── entrypoint.sh
│   ├── .env
└── README.md
```

## 🚀 Démarrage

1. Construisez et démarrez les conteneurs :

```bash
docker compose up -d --build
```

2. Vérifiez que les conteneurs tournent :

```bash
docker compose ps
```

3. Suivez les logs si nécessaire :

```bash
docker compose logs -f
```

## 🔗 Accès aux services

- `http://localhost:8001` → user-service
- `http://localhost:8002` → traffic-service

## 🛠 Fonctionnement des services

- Chaque conteneur Laravel :
  - Attend que sa base de données soit prête via netcat (`nc`)
  - Exécute les migrations  (`php artisan migrate`)
  - Lance le serveur  (via `php artisan serve` ou Apache)
- Un healthcheck basé sur `curl` vérifie que chaque application répond correctement

## 🔧 Variables d'environnement

Chaque service Laravel lit ses variables depuis son fichier `.env`. Exemple pour `traffic-service` :

```dotenv
DB_HOST=db2
DB_DATABASE=traffic_service_db
DB_USERNAME=root
DB_PASSWORD=
MICRO_SERVICE_AUTH_URL=http://user-service:80
```

## 🧪 Tester les API

```bash
curl http://localhost:8001/api
curl http://localhost:8002/api
```

## 🧹 Nettoyage

Pour arrêter et supprimer tous les conteneurs :

```bash
docker compose down
```


## 📂 Volumes persistants

- `user_service-db-data`
- `traffic_service-db-data`

Ces volumes assurent la persistance des bases de données entre les redémarrages.

---

Pour toute erreur ou anomalie :

- Utilisez `docker inspect` et `docker logs` pour analyser les problèmes
- Vérifiez le healthcheck et les logs Laravel


Bonne utilisation du projet Trafine 🚗 !
