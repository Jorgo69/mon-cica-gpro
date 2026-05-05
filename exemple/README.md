# CICA-ARCHIVES

Système de gestion des courriers et archives — Application Laravel 10 dockerisée.

---

## Prérequis (à installer sur le serveur AVANT de commencer)

### 1. Docker Engine

Docker est le logiciel qui fait tourner l'application dans des conteneurs isolés.

```bash
# Mettre à jour les paquets
sudo apt update && sudo apt upgrade -y

# Installer Docker
sudo apt install -y docker.io

# Installer Docker Compose (plugin)
sudo apt install -y docker-compose-plugin

# Ajouter votre utilisateur au groupe docker (pour ne plus avoir besoin de sudo)
sudo usermod -aG docker $USER

# IMPORTANT : déconnectez-vous puis reconnectez-vous pour que ça prenne effet
# Ou tapez cette commande :
newgrp docker

# Vérifier que Docker fonctionne :
docker --version
docker compose version
```

### 2. Git

```bash
sudo apt install -y git

# Vérifier :
git --version
```

---

## Déploiement — Guide pas à pas

### Étape 1 : Récupérer le code

```bash
# Se placer dans le dossier où vous voulez installer l'application
cd /home/$USER

# Cloner le dépôt depuis GitHub
git clone https://github.com/VOTRE-ORGANISATION/cica-archives.git

# Entrer dans le dossier du projet
cd cica-archives
```

> **Note :** Si le code est sur la branche `docker`, faites :
> ```bash
> git checkout docker
> ```
> Ou si vous êtes sur `main` et que la branche `docker` a déjà été mergée, restez sur `main`.

### Étape 2 : Configurer les variables d'environnement

Le fichier `.env` contient les mots de passe et la configuration. Il n'est PAS dans le dépôt Git (pour des raisons de sécurité). Vous devez le créer :

```bash
# Copier le modèle
cp .env.example .env
```

Puis ouvrir le fichier `.env` avec nano (ou un autre éditeur) :

```bash
nano .env
```

**Modifier ces lignes :**

```env
# Nom de l'application (vous pouvez laisser tel quel)
APP_NAME=CICA-ARCHIVES

# IMPORTANT : passer en production pour le déploiement
APP_ENV=production
APP_DEBUG=false

# Mettre l'adresse du serveur (remplacer par l'IP ou le domaine réel)
APP_URL=http://VOTRE-IP-OU-DOMAINE:8080

# --- Base de données ---
# NE PAS MODIFIER ces 3 lignes, elles correspondent au conteneur MySQL Docker
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306

# Vous POUVEZ changer le nom de la base, l'utilisateur et le mot de passe
# MAIS il faut aussi les changer dans docker-compose.yml (voir plus bas)
DB_DATABASE=cica-archives
DB_USERNAME=cica_user
DB_PASSWORD=cica_secret

# --- Email (pour les notifications et réinitialisation de mot de passe) ---
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe-application
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="votre-email@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

> **Important :** Pour Gmail, il faut un "mot de passe d'application", pas votre mot de passe normal.
> Allez sur https://myaccount.google.com/apppasswords pour en générer un.

Sauvegarder avec `Ctrl+O` puis `Entrée`, quitter avec `Ctrl+X`.

### Étape 3 : (Optionnel) Changer les mots de passe MySQL

Si vous voulez des mots de passe différents, ouvrez `docker-compose.yml` :

```bash
nano docker-compose.yml
```

Trouvez la section `mysql` et modifiez :

```yaml
    environment:
      MYSQL_ROOT_PASSWORD: votre-mot-de-passe-root     # Mot de passe admin MySQL
      MYSQL_DATABASE: cica-archives                      # Nom de la base
      MYSQL_USER: cica_user                              # Utilisateur (doit correspondre au .env)
      MYSQL_PASSWORD: cica_secret                        # Mot de passe (doit correspondre au .env)
```

Et aussi la section `app` :

```yaml
    environment:
      DB_HOST: mysql
      DB_PORT: 3306
      DB_DATABASE: cica-archives                         # Même nom que ci-dessus
      DB_USERNAME: cica_user                             # Même utilisateur
      DB_PASSWORD: cica_secret                           # Même mot de passe
```

> **Règle simple :** Les valeurs `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` doivent être **identiques** dans le `.env`, dans la section `app` et dans la section `mysql` du `docker-compose.yml`.

### Étape 4 : Lancer l'application

```bash
# Cette commande va :
# 1. Construire l'image de l'application (ça prend quelques minutes la première fois)
# 2. Télécharger MySQL, Nginx et PhpMyAdmin
# 3. Démarrer tous les conteneurs
# 4. Exécuter automatiquement les migrations de base de données
docker compose up -d --build
```

**Attendre environ 1 à 2 minutes** que tout se mette en place (MySQL doit démarrer, puis les migrations s'exécutent).

Pour suivre la progression en temps réel :

```bash
docker compose logs -f app
```

Vous devriez voir à la fin :

```
========================================
  CICA-ARCHIVES - Prêt !
  Accessible sur http://localhost:8080
========================================
```

Appuyez sur `Ctrl+C` pour quitter les logs.

### Étape 5 : Vérifier que tout fonctionne

```bash
# Vérifier que les 4 conteneurs tournent
docker compose ps
```

Vous devriez voir 4 lignes avec le statut "Up" :

| Conteneur | Rôle |
|-----------|------|
| cica-archives-app | L'application PHP |
| cica-archives-nginx | Le serveur web |
| cica-archives-mysql | La base de données |
| cica-archives-phpmyadmin | Interface de gestion de la base (optionnel) |

### Étape 6 : Accéder à l'application

Ouvrez votre navigateur :

| Service | URL | Identifiants |
|---------|-----|-------------|
| **Application** | `http://VOTRE-IP:8080` | Créez un compte sur la page d'inscription |
| **PhpMyAdmin** | `http://VOTRE-IP:8081` | Utilisateur : `cica_user` / Mot de passe : `cica_secret` |

---

## Commandes utiles au quotidien

### Démarrer l'application
```bash
cd /chemin/vers/cica-archives
docker compose up -d
```

### Arrêter l'application
```bash
docker compose down
```

### Voir les logs (en cas de problème)
```bash
# Tous les logs
docker compose logs -f

# Logs de l'application seulement
docker compose logs -f app

# Logs de MySQL
docker compose logs -f mysql
```

### Redémarrer l'application
```bash
docker compose restart
```

### Reconstruire après une mise à jour du code
```bash
git pull
docker compose up -d --build
```

### Exécuter une commande Laravel (artisan)
```bash
docker compose exec app php artisan migrate        # Lancer les migrations
docker compose exec app php artisan db:seed        # Remplir la base avec des données de test
docker compose exec app php artisan cache:clear    # Vider le cache
docker compose exec app php artisan config:clear   # Vider le cache de configuration
```

### Accéder au terminal du conteneur
```bash
docker compose exec app bash
```

---

## Dépannage

### L'application ne démarre pas
```bash
# Vérifier l'état des conteneurs
docker compose ps

# Si un conteneur est "Exited" ou "Restarting", regarder ses logs :
docker compose logs app
docker compose logs mysql
```

### Erreur "port already in use" (port déjà utilisé)
Un autre service utilise déjà le port 8080 ou 8081 sur le serveur.

Solution : Changer les ports dans `docker-compose.yml` :
```yaml
# Changer 8080 par un autre port (ex: 8888)
ports:
  - "8888:80"
```

### Erreur de connexion à la base de données
Vérifiez que les identifiants sont **identiques** dans :
1. Le fichier `.env` (DB_USERNAME, DB_PASSWORD, DB_DATABASE)
2. Le `docker-compose.yml` section `app` (environment)
3. Le `docker-compose.yml` section `mysql` (MYSQL_USER, MYSQL_PASSWORD, MYSQL_DATABASE)

### Repartir de zéro (supprime TOUTES les données)
```bash
docker compose down -v
docker compose up -d --build
```

> **Attention :** `-v` supprime les volumes, donc toutes les données de la base de données seront perdues.

---

## Architecture Docker

```
┌─────────────────────────────────────────────────────────┐
│                    Serveur                               │
│                                                         │
│   ┌──────────┐     ┌──────────┐     ┌──────────┐       │
│   │  Nginx   │────▶│ PHP-FPM  │────▶│  MySQL   │       │
│   │ (port    │     │ (App     │     │ (Base de │       │
│   │  8080)   │     │  Laravel)│     │  données)│       │
│   └──────────┘     └──────────┘     └──────────┘       │
│                                                         │
│   ┌──────────────┐                                      │
│   │ PhpMyAdmin   │──────────────────────────┘           │
│   │ (port 8081)  │                                      │
│   └──────────────┘                                      │
│                                                         │
│   Utilisateur ──▶ http://IP:8080 ──▶ Nginx ──▶ App     │
└─────────────────────────────────────────────────────────┘
```

**Comment ça marche :**
1. L'utilisateur tape l'adresse dans son navigateur
2. **Nginx** reçoit la requête et sert les fichiers statiques (CSS, JS, images)
3. Pour les pages PHP, Nginx transmet la requête à **PHP-FPM** (l'application Laravel)
4. L'application interroge **MySQL** pour lire/écrire les données
5. La réponse remonte dans l'autre sens jusqu'au navigateur

---

## Structure des fichiers Docker

```
cica-archives/
├── Dockerfile                    # Recette pour construire l'image de l'app
├── docker-compose.yml            # Définit et relie tous les services
├── .dockerignore                 # Fichiers exclus de l'image Docker
├── .env                          # Configuration (à créer, non versionné)
├── .env.example                  # Modèle pour le .env
└── docker/
    ├── entrypoint.sh             # Script qui s'exécute au démarrage du conteneur
    └── nginx/
        └── default.conf          # Configuration du serveur web Nginx
```


### Commande Utils
```
docker compose up -d --build   # Démarrer (avec rebuild)
docker compose down            # Arrêter
docker compose logs -f app     # Voir les logs
docker compose exec app php artisan migrate  # Lancer une migration
docker compose down -v         # Tout supprimer (y compris les données)
```

---

## Comprendre les ports et les accès

### C'est quoi un port ?

Un port, c'est comme un **numéro de porte** sur un immeuble (le serveur).
Le serveur a une seule adresse IP, mais plusieurs services tournent dessus.
Chaque service écoute sur un port différent pour ne pas se mélanger.

Exemple concret : votre serveur a l'adresse `192.168.1.50` :
- Porte **8080** → l'application CICA-ARCHIVES
- Porte **8081** → PhpMyAdmin (gestion de la base de données)

### Comment accéder à l'application ?

#### Si vous êtes directement sur le serveur (écran + clavier branchés dessus) :
```
http://127.0.0.1:8080        → L'application
http://127.0.0.1:8081        → PhpMyAdmin
```
> `127.0.0.1` veut dire "moi-même", c'est l'adresse locale de la machine.

#### Si vous accédez depuis un autre ordinateur du réseau :
Il faut d'abord connaître l'IP du serveur :
```bash
# Sur le serveur, tapez :
ip addr show | grep "inet " | grep -v 127.0.0.1
```
Vous verrez quelque chose comme `inet 192.168.1.50/24`. L'IP est `192.168.1.50`.

Ensuite, depuis un autre PC du même réseau :
```
http://192.168.1.50:8080     → L'application
http://192.168.1.50:8081     → PhpMyAdmin
```

#### Si le serveur a un nom de domaine (ex: archives.monentreprise.com) :
```
http://archives.monentreprise.com:8080    → L'application
http://archives.monentreprise.com:8081    → PhpMyAdmin
```

### Tableau récapitulatif des accès

| Service | Port | URL (local) | URL (réseau) | Identifiants |
|---------|------|-------------|--------------|-------------|
| **Application** | 8080 | `http://127.0.0.1:8080` | `http://IP-SERVEUR:8080` | Créer un compte sur la page d'inscription |
| **PhpMyAdmin** | 8081 | `http://127.0.0.1:8081` | `http://IP-SERVEUR:8081` | Utilisateur : `cica_user` / Mot de passe : `cica_secret` |
| **MySQL** | 3306 | Non accessible depuis l'extérieur | — | Utilisé uniquement par l'application en interne |

> **Note :** MySQL (port 3306) n'est **pas** accessible depuis l'extérieur du serveur.
> C'est normal et c'est fait exprès pour la sécurité. Seuls les conteneurs Docker
> (l'application et PhpMyAdmin) peuvent y accéder. Pour gérer la base de données,
> utilisez PhpMyAdmin sur le port 8081.

### Comment changer les ports ?

Si le port 8080 ou 8081 est déjà utilisé par un autre programme sur le serveur,
vous pouvez les changer.

Ouvrez `docker-compose.yml` :
```bash
nano docker-compose.yml
```

Trouvez les lignes `ports:` et changez le **premier** chiffre (celui avant les `:`) :

```yaml
# Pour l'application (section nginx) :
ports:
  - "8080:80"    # ← Changer 8080 par le port que vous voulez (ex: 9090)

# Pour PhpMyAdmin (section phpmyadmin) :
ports:
  - "8081:80"    # ← Changer 8081 par le port que vous voulez (ex: 9091)
```

> **Important :** Ne changez JAMAIS le chiffre **après** les `:` (le `80`).
> C'est le port interne du conteneur, il doit rester à 80.
>
> Le format c'est : `PORT-EXTERNE:PORT-INTERNE`
> - PORT-EXTERNE = le port sur lequel VOUS accédez (celui que vous tapez dans le navigateur)
> - PORT-INTERNE = le port à l'intérieur du conteneur (ne pas toucher)

Après avoir changé, relancez :
```bash
docker compose down
docker compose up -d
```

---

## Pourquoi Docker est différent d'un hébergement classique ?

### Hébergement mutualisé / VPS classique (SANS Docker)
Quand on déploie une application PHP sur un serveur classique ou un hébergement mutualisé :
1. On installe manuellement PHP, MySQL, Nginx/Apache, Composer sur le serveur
2. On va dans phpMyAdmin (ou en ligne de commande) pour **créer la base de données à la main**
3. On crée un utilisateur MySQL avec un mot de passe
4. On note ces identifiants et on les met dans le fichier `.env`
5. Si on oublie une étape ou si les versions ne correspondent pas → ça ne marche pas
6. Chaque serveur est différent → le déploiement n'est jamais le même

### Avec Docker (ce projet)
Docker embarque **tout** dans des conteneurs :
- Le conteneur `app` contient PHP + Composer + le code Laravel → pas besoin d'installer PHP
- Le conteneur `mysql` contient MySQL → pas besoin d'installer MySQL
- Le conteneur `nginx` contient le serveur web → pas besoin d'installer Nginx
- Le conteneur `phpmyadmin` contient PhpMyAdmin → pas besoin de l'installer non plus

**La base de données se crée TOUTE SEULE** au premier lancement.
Quand le conteneur MySQL démarre pour la première fois, il lit les variables
dans `docker-compose.yml` (`MYSQL_DATABASE`, `MYSQL_USER`, `MYSQL_PASSWORD`)
et crée automatiquement :
- La base de données `cica-archives`
- L'utilisateur `cica_user` avec le mot de passe `cica_secret`
- Les permissions pour que cet utilisateur accède à cette base

Ensuite, le conteneur `app` exécute automatiquement les migrations Laravel
(les fichiers dans `database/migrations/`) qui créent toutes les tables.

**Résultat :** vous n'avez rien à faire manuellement. Un seul `docker compose up -d --build`
et tout est prêt.

### En résumé

| | Hébergement classique | Docker |
|---|---|---|
| Installer PHP | Oui, manuellement | Non, c'est dans le conteneur |
| Installer MySQL | Oui, manuellement | Non, c'est dans le conteneur |
| Installer Nginx | Oui, manuellement | Non, c'est dans le conteneur |
| Créer la base de données | Oui, manuellement | Non, automatique |
| Créer l'utilisateur MySQL | Oui, manuellement | Non, automatique |
| Configurer les permissions | Oui, manuellement | Non, automatique |
| Lancer les migrations | Oui, manuellement | Non, automatique au démarrage |
| Commande pour tout lancer | Plusieurs étapes | `docker compose up -d --build` |
| Reproductible ? | Non (chaque serveur est différent) | Oui (même résultat partout) |

---

## FAQ — Questions fréquentes

### Est-ce que je dois installer PHP sur le serveur ?
**Non.** PHP est dans le conteneur Docker. Il faut uniquement installer Docker et Git.

### Est-ce que je dois créer la base de données moi-même ?
**Non.** Docker crée la base de données automatiquement au premier lancement grâce aux
variables dans `docker-compose.yml`.

### Est-ce que je dois changer les mots de passe ?
**Non, pas obligatoirement.** Les mots de passe par défaut (`cica_user` / `cica_secret`)
fonctionnent directement. MySQL tourne dans un conteneur isolé, il n'est pas accessible
depuis l'extérieur du serveur. Mais si vous voulez les changer (recommandé en production),
voir l'Étape 3 plus haut.

### Les données sont stockées où ?
Les données de MySQL sont stockées dans un **volume Docker** (`mysql_data`).
Ça veut dire que même si vous arrêtez les conteneurs (`docker compose down`),
les données restent. Elles ne sont supprimées QUE si vous faites `docker compose down -v`
(avec le `-v` qui veut dire "supprimer les volumes").

### Comment faire une sauvegarde de la base de données ?
```bash
# Exporter toute la base dans un fichier SQL
docker compose exec mysql mysqldump -u cica_user -pcica_secret cica-archives > sauvegarde.sql

# Pour restaurer la sauvegarde plus tard :
docker compose exec -T mysql mysql -u cica_user -pcica_secret cica-archives < sauvegarde.sql
```

### Comment mettre à jour l'application ?
```bash
# 1. Récupérer les dernières modifications
git pull

# 2. Reconstruire et relancer
docker compose up -d --build
```
Les migrations sont exécutées automatiquement à chaque démarrage, donc les nouvelles
tables ou colonnes seront créées automatiquement.

### L'application plante, comment voir ce qui ne va pas ?
```bash
# Voir les logs en temps réel
docker compose logs -f app

# Voir les 100 dernières lignes des logs
docker compose logs --tail=100 app
```

### Comment accéder au terminal de l'application (pour debug) ?
```bash
docker compose exec app bash
# Vous êtes maintenant "à l'intérieur" du conteneur
# Vous pouvez taper des commandes Laravel :
php artisan tinker
php artisan route:list
exit   # Pour sortir du conteneur
```