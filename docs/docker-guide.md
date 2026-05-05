# Guide Docker — CICA-GPRO

Guide complet pour deployer CICA-GPRO avec Docker sur un serveur client.

## Prerequis

- Docker Engine 24+
- Docker Compose v2+
- Git
- 2 Go de RAM minimum (4 Go recommande)
- 10 Go d'espace disque

> **Note** : Node.js 20 est installe dans le container Docker automatiquement.
> Vous n'avez PAS besoin de Node.js sur la machine host.

## Installation rapide

### 1. Cloner le projet

```bash
git clone https://github.com/cave-tech/cica-gpro.git
cd cica-gpro
```

### 2. Configurer l'environnement

Les fichiers Docker `.env.docker.mysql` et `.env.docker.postgres` sont deja preconfigures.
Vous n'avez **PAS besoin de toucher au `.env` principal** — Docker utilise ses propres fichiers.

Si vous voulez personnaliser (mot de passe, port, domaine), editez :

```bash
# Pour MySQL :
nano .env.docker.mysql

# Pour PostgreSQL :
nano .env.docker.postgres
```

### 3. Lancer

```bash
# Avec MySQL (par defaut)
make up

# OU avec PostgreSQL
make up-pg
```

### 4. Acceder a l'application

| Service | URL |
|---------|-----|
| Application | http://localhost:8080 |
| phpMyAdmin | http://localhost:8081 |
| pgAdmin | http://localhost:8082 |

Le premier utilisateur inscrit devient automatiquement administrateur (mode selfhosted).

---

## Choix de la base de donnees

### MySQL (par defaut)

Rien a changer. C'est la configuration par defaut.

```bash
make up
```

### PostgreSQL

1. Dans `.env`, modifiez :

```env
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
```

2. Lancez avec l'override :

```bash
make up-pg
# ou manuellement :
docker compose -f docker-compose.yml -f docker-compose.postgres.yml up -d --build
```

### pgAdmin — connexion

- Email : `admin@cica-gpro.com` (configurable via `PGADMIN_EMAIL`)
- Mot de passe : `pgadmin_2026` (configurable via `PGADMIN_PASSWORD`)
- Pour ajouter le serveur : Host = `postgres`, Port = `5432`, User = `gpro`

---

## Ports

### Ports par defaut

| Service | Port | Variable |
|---------|------|----------|
| Application (Nginx) | 8080 | `APP_PORT` |
| MySQL | 3306 | `DB_PORT` |
| PostgreSQL | 5432 | `DB_PORT` |
| phpMyAdmin | 8081 | `PHPMYADMIN_PORT` |
| pgAdmin | 8082 | `PGADMIN_PORT` |
| Redis | 6379 | `REDIS_PORT` |
| Reverb (WebSockets) | 6001 | `REVERB_PORT` |

### Port occupe ?

Si un port est deja utilise sur le serveur :

```env
# Exemple : le port 8080 est pris, on utilise 9000
APP_PORT=9000

# Le port 3306 est pris (autre MySQL), on utilise 3307
DB_PORT=3307
```

Puis relancez :

```bash
make restart
```

### Verifier les ports utilises

```bash
# Linux
sudo ss -tlnp | grep -E '8080|3306|8081|6379'

# macOS
lsof -i -P | grep -E '8080|3306|8081|6379'
```

---

## Commandes utiles

```bash
make help        # Voir toutes les commandes
make up          # Demarrer (MySQL)
make up-pg       # Demarrer (PostgreSQL)
make down        # Arreter
make restart     # Redemarrer
make logs        # Voir les logs
make logs-app    # Logs de l'app seulement
make shell       # Ouvrir un terminal dans le container
make mysql       # Client MySQL
make psql        # Client PostgreSQL
make migrate     # Lancer les migrations
make seed        # Lancer les seeders
make fresh       # Reset complet DB
make test        # Lancer les tests
make cache       # Reconstruire les caches
make clear       # Vider les caches
make backup-mysql # Sauvegarder MySQL
make backup-pg    # Sauvegarder PostgreSQL
make update      # Mise a jour (git pull + rebuild)
make destroy     # Tout supprimer (ATTENTION)
```

---

## SSL / HTTPS

### Option 1 : Reverse proxy externe (recommande)

Si vous avez deja un reverse proxy (Nginx, Traefik, Caddy) sur le serveur :

1. Pointez le proxy vers `localhost:8080`
2. Configurez SSL sur le proxy
3. Dans `.env` : `APP_URL=https://votre-domaine.com`

### Option 2 : Certbot / Let's Encrypt

1. Installez Certbot sur le serveur host
2. Generez le certificat : `certbot certonly --standalone -d votre-domaine.com`
3. Montez les certificats dans le container Nginx :

```yaml
# Dans docker-compose.yml, section nginx > volumes :
- /etc/letsencrypt/live/votre-domaine.com/fullchain.pem:/etc/nginx/ssl/fullchain.pem:ro
- /etc/letsencrypt/live/votre-domaine.com/privkey.pem:/etc/nginx/ssl/privkey.pem:ro
```

4. Decommentez la section SSL dans `docker/nginx/default.conf`

---

## WebSockets (optionnel)

Pour activer les notifications temps reel :

1. Dans `.env` :

```env
BROADCAST_DRIVER=reverb
```

2. Dans `docker-compose.yml`, decommentez le service `reverb`

3. Relancez :

```bash
docker compose up -d
```

---

## Email

### Gmail SMTP

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@votre-domaine.com
```

Pour le mot de passe Gmail : allez dans Google Account > Security > App Passwords.

### Mailgun / SendGrid / autre

Meme principe, changez `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`.

---

## Sauvegarde et restauration

### Sauvegarder

```bash
# MySQL
make backup-mysql
# Cree un fichier backup_YYYYMMDD_HHMMSS.sql

# PostgreSQL
make backup-pg
```

### Restaurer

```bash
# MySQL
docker compose exec -T mysql mysql -u gpro -pgpro_secret_2026 cica_gpro < backup_20260504_120000.sql

# PostgreSQL
docker compose exec -T postgres psql -U gpro -d cica_gpro < backup_20260504_120000.sql
```

### Sauvegarde automatique (cron)

```bash
# Ajoutez au crontab du serveur (tous les jours a 3h)
0 3 * * * cd /chemin/vers/cica-gpro && make backup-mysql >> /var/log/gpro-backup.log 2>&1
```

---

## Mise a jour

```bash
make update
```

Cela fait : `git pull` → rebuild image → restart → migrate → cache.

Si vous avez des modifications locales :

```bash
git stash
make update
git stash pop
```

---

## Troubleshooting

### L'app ne demarre pas

```bash
# Verifier les logs
make logs-app

# Verifier que la DB est prete
docker compose exec app php artisan db:monitor
```

### Erreur "Permission denied" sur storage

```bash
docker compose exec app chown -R www-data:www-data /var/www/html/storage
docker compose exec app chmod -R 775 /var/www/html/storage
```

### Port deja utilise

```
Error: bind: address already in use
```

1. Identifiez le processus : `sudo ss -tlnp | grep 8080`
2. Changez le port dans `.env` : `APP_PORT=9000`
3. `make restart`

### MySQL "Access denied"

Verifiez que `DB_PASSWORD` dans `.env` correspond a `MYSQL_PASSWORD` dans le compose.
Si vous avez change le mot de passe apres la premiere creation, supprimez le volume :

```bash
docker compose down -v  # ATTENTION: supprime les donnees!
make up
```

### "Class not found" ou "View not found"

```bash
make cache    # Reconstruire les caches
make clear    # Ou vider puis reconstruire
make cache
```

### Migration echoue

```bash
# Voir le statut des migrations
docker compose exec app php artisan migrate:status

# Forcer une migration specifique
docker compose exec app php artisan migrate --path=database/migrations/2026_xx_xx_fichier.php --force
```

### Espace disque plein

```bash
# Nettoyer les images Docker inutilisees
docker system prune -a --volumes

# Verifier l'espace des volumes
docker system df
```

### Performances lentes

1. Verifiez Redis : `make redis` puis `INFO`
2. Verifiez OPcache : `docker compose exec app php -i | grep opcache`
3. Reconstruire les caches : `make cache`

---

## Architecture des containers

```
                    Internet
                       |
                   [Nginx:8080]
                       |
                   [PHP-FPM:9000]
                    /    |    \
           [MySQL]  [Redis]  [Queue Worker]
              |                    |
        [phpMyAdmin]          [Scheduler]
```

---

## Variables d'environnement — Reference complete

| Variable | Defaut | Description |
|----------|--------|-------------|
| `APP_PORT` | 8080 | Port de l'application |
| `APP_URL` | http://localhost:8080 | URL publique |
| `APP_ENV` | production | Environnement |
| `APP_DEBUG` | false | Mode debug |
| `GPRO_MODE` | selfhosted | Mode GPRO |
| `DB_CONNECTION` | mysql | mysql ou pgsql |
| `DB_HOST` | mysql | Hostname DB (mysql ou postgres) |
| `DB_PORT` | 3306 | Port DB (3306 ou 5432) |
| `DB_DATABASE` | cica_gpro | Nom de la base |
| `DB_USERNAME` | gpro | User DB |
| `DB_PASSWORD` | gpro_secret_2026 | Mot de passe DB |
| `DB_ROOT_PASSWORD` | root_secret_2026 | Root password MySQL |
| `REDIS_HOST` | redis | Hostname Redis |
| `REDIS_PASSWORD` | gpro_redis_2026 | Mot de passe Redis |
| `REDIS_PORT` | 6379 | Port Redis |
| `PHPMYADMIN_PORT` | 8081 | Port phpMyAdmin |
| `PGADMIN_PORT` | 8082 | Port pgAdmin |
| `PGADMIN_EMAIL` | admin@cica-gpro.com | Email pgAdmin |
| `PGADMIN_PASSWORD` | pgadmin_2026 | Password pgAdmin |
| `MAIL_MAILER` | log | Driver mail |
| `BROADCAST_DRIVER` | log | Driver broadcast |
| `REVERB_PORT` | 6001 | Port WebSocket |
| `GPRO_PLUGINS_ENABLED` | true | Activer le systeme de plugins |
