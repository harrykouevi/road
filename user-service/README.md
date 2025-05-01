# 👤 Micro-service API Uuser-service

🔐 **Service d’authentification & gestion des utilisateurs**

📦 **Version 1.0.0**  
🗓️ **Sortie : 25 avril 2025**

---

## 🧾 Présentation

Ce micro-service gère l’**authentification**, la **création de comptes**, ainsi que les **profils utilisateur** dans l’écosystème de l’application.  
Développé avec **Laravel 10**, il utilise **Sanctum** pour la gestion sécurisée des tokens API.

---

## 🚀 Technologies Utilisées

| Techno           | Rôle                                                                 |
|------------------|----------------------------------------------------------------------|
| 🧬 Laravel 10     | Framework PHP pour la création d’API REST                           |
| 🛡️ Sanctum       | Authentification API basée sur les tokens                           |
| 💃 MySQL         | Base de données relationnelle                                        |
| 🥪 PHPUnit        | Tests automatisés                                                    |
| 🛠️ Laravel Artisan| Outils de développement et gestion de commandes Laravel             |

---

## 📁 Structure du Projet

```
└───app
    ├───Http
    │   ├───Controllers
    │   ├───Middleware
    │   └───Requests
    ├───Models
└───routes
    └───api.php
└───database
    ├───factories
    ├───migrations
    └───seeders
```

---

## 🔐 Routes API

| Méthode | URI                  | Contrôleur             | Description                                     |
|---------|----------------------|-------------------------|-------------------------------------------------|
| POST    | `/register`          | `AuthController@register` | Création d’un nouvel utilisateur                |
| POST    | `/login`             | `AuthController@login`    | Connexion et récupération du token              |
| GET     | `/profile`           | `UserController@profile`  | Récupération du profil utilisateur (auth requise) |
| PUT     | `/profile/update`    | `UserController@updateProfile` | Mise à jour des informations du profil       |
| POST    | `/logout`            | `AuthController@logout`   | Déconnexion de l'utilisateur                    |

---

## ⚙️ Pré-requis

✔️ **PHP 8.2+**  
✔️ **Composer**  
✔️ **Base de données MySQL**  
✔️ **Outils Laravel : Artisan, Migrations, etc.**

---

## ▶️ Installation

```bash
# 1. Cloner le dépôt
git clone ...

# 2. Aller dans le dossier
cd UserServiceAPI

# 3. Installer les dépendances PHP
composer install

# 4. Copier le fichier .env et configurer
cp .env.example .env

# 5. Générer la clé d’application
php artisan key:generate

# 6. Lancer les migrations
php artisan migrate

# 7. Lancer le serveur de dev
php artisan serve
```

---

## 📝 Notes Importantes

⚠️ Les routes `/profile`, `/update` et `/logout` sont protégées via **Sanctum** – le token d’auth doit être passé dans les headers.  
⚠️ L’**inscription Firebase** en parallèle est gérée côté Flutter via `firebase_auth`.

---

## 👨‍💼 Développeur

Développé avec 🧠 et 💻 au Togo  
**...........** – 2025

