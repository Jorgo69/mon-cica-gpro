# Installation manuelle — CICA-GPRO (sans Docker)

Guide pour installer CICA-GPRO sur un serveur VPS, dedie, ou mutualise.
Compatible Ubuntu, Debian, CentOS, ou tout serveur avec PHP 8.2+.

---

## Comptes par defaut apres installation

> **IMPORTANT : Changez ces mots de passe immediatement apres l'installation !**

| Mode | Email | Mot de passe | Role |
|------|-------|-------------|------|
| **selfhosted** | `admin@projexia.org` | `password` | Admin (owner) |
| **selfhosted** | `manager@projexia.org` | `password` | Manager |
| **selfhosted** | `membre@projexia.org` | `password` | Membre |
| **saas** | `root@cica-gpro.com` | `password` | ROOT (super admin) |
| **saas** | `admin@projexia.org` | `password` | Admin org |

Ces comptes sont crees par `php artisan db:seed`. En production, supprimez-les et creez vos propres comptes via `/register`.

---

## Prerequis

| Logiciel | Version minimum | Verification |
|----------|----------------|-------------|
| PHP | 8.2+ | `php -v` |
| Composer | 2.x | `composer --version` |
| Node.js | 18+ | `node -v` |
| npm | 9+ | `npm -v` |
| Git | 2.x | `git --version` |
| Nginx ou Apache | - | `nginx -v` ou `apache2 -v` |

### Extensions PHP requises

```bash
# Verifier les extensions installees :
php -m

# Extensions necessaires :
php-cli php-fpm php-mbstring php-xml php-zip php-curl
php-gd php-intl php-bcmath php-tokenizer php-fileinfo
php-pdo php-json php-openssl
```

**Selon la base de donnees choisie :**

```bash
# SQLite (dev local, petites instances)
php-sqlite3

# MySQL / MariaDB
php-mysql

# PostgreSQL
php-pgsql
```

### Installer les prerequis (Ubuntu/Debian)

```bash
# PHP 8.3 + extensions
sudo apt update
sudo apt install -y php8.3-fpm php8.3-cli php8.3-mbstring php8.3-xml \
    php8.3-zip php8.3-curl php8.3-gd php8.3-intl php8.3-bcmath \
    php8.3-tokenizer php8.3-fileinfo php8.3-sqlite3 php8.3-mysql \
    php8.3-pgsql

# Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Node.js 20 (via NodeSource)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Nginx
sudo apt install -y nginx

# Supervisord (pour les queue workers)
sudo apt install -y supervisor

# Certbot (pour SSL)
sudo apt install -y certbot python3-certbot-nginx
```

---

## Installation

### 1. Cloner le projet

```bash
cd /var/www
git clone https://github.com/cave-tech/cica-gpro.git
cd cica-gpro
```

### 2. Configurer l'environnement

Copiez le fichier d'exemple qui correspond a votre base de donnees :

```bash
# SQLite (simple, pas de serveur DB)
cp .env.example.sqlite .env

# MySQL
cp .env.example.mysql .env

# PostgreSQL
cp .env.example.postgres .env
```

Editez `.env` et adaptez au minimum :

```bash
nano .env
```

```env
APP_URL=https://votre-domaine.com     # URL publique du site
MAIL_USERNAME=votre-email@gmail.com   # Pour envoyer les emails
MAIL_PASSWORD=votre-app-password      # Mot de passe application Gmail
```

### 3. Installer les dependances

```bash
# PHP
composer install --no-dev --optimize-autoloader

# JavaScript
npm ci
npm run build
```

### 4. Generer la cle d'application

```bash
php artisan key:generate
```

### 5. Creer la base de donnees

**SQLite :**

```bash
touch database/database.sqlite
```

**MySQL :**

```bash
mysql -u root -p
```

```sql
CREATE DATABASE cica_gpro CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'gpro'@'localhost' IDENTIFIED BY 'votre_mot_de_passe';
GRANT ALL PRIVILEGES ON cica_gpro.* TO 'gpro'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

**PostgreSQL :**

```bash
sudo -u postgres psql
```

```sql
CREATE DATABASE cica_gpro;
CREATE USER gpro WITH ENCRYPTED PASSWORD 'votre_mot_de_passe';
GRANT ALL PRIVILEGES ON DATABASE cica_gpro TO gpro;
ALTER DATABASE cica_gpro OWNER TO gpro;
\q
```

### 6. Lancer les migrations et le seed

```bash
php artisan migrate --seed --force
```

### 7. Lien storage + cache

```bash
php artisan storage:link
php artisan optimize
```

### 8. Permissions fichiers

```bash
sudo chown -R www-data:www-data /var/www/cica-gpro
sudo chmod -R 755 /var/www/cica-gpro
sudo chmod -R 775 storage bootstrap/cache
```

### 9. Premiere connexion

Ouvrez votre navigateur sur `https://votre-domaine.com/register`.
Le **premier utilisateur inscrit** devient automatiquement administrateur (mode selfhosted).

Ou utilisez la commande interactive :

```bash
php artisan gpro:install
```

---

## Configuration Nginx

Creez le fichier de configuration :

```bash
sudo nano /etc/nginx/sites-available/cica-gpro
```

```nginx
server {
    listen 80;
    server_name votre-domaine.com;
    root /var/www/cica-gpro/public;

    index index.php;

    # Taille max upload (fichiers Excel, images)
    client_max_body_size 20M;

    # Assets statiques
    location /build/ {
        expires 1y;
        access_log off;
        add_header Cache-Control "public, immutable";
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Activez et testez :

```bash
sudo ln -s /etc/nginx/sites-available/cica-gpro /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## Configuration Apache

Si vous utilisez Apache au lieu de Nginx :

```bash
sudo nano /etc/apache2/sites-available/cica-gpro.conf
```

```apache
<VirtualHost *:80>
    ServerName votre-domaine.com
    DocumentRoot /var/www/cica-gpro/public

    <Directory /var/www/cica-gpro/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/cica-gpro-error.log
    CustomLog ${APACHE_LOG_DIR}/cica-gpro-access.log combined
</VirtualHost>
```

```bash
sudo a2ensite cica-gpro.conf
sudo a2enmod rewrite
sudo systemctl reload apache2
```

---

## SSL avec Let's Encrypt

```bash
sudo certbot --nginx -d votre-domaine.com
```

Certbot modifie automatiquement la config Nginx. Mettez a jour `.env` :

```env
APP_URL=https://votre-domaine.com
```

Puis :

```bash
php artisan optimize
```

Le renouvellement est automatique (cron installe par Certbot).

---

## Queue Worker (Supervisor)

Les emails, notifications et taches en arriere-plan utilisent les queues.

```bash
sudo nano /etc/supervisor/conf.d/cica-gpro-worker.conf
```

```ini
[program:cica-gpro-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/cica-gpro/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/cica-gpro/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start cica-gpro-worker:*
```

Verifier :

```bash
sudo supervisorctl status
```

---

## Tache planifiee (Cron)

```bash
sudo crontab -e -u www-data
```

Ajoutez :

```
* * * * * cd /var/www/cica-gpro && php artisan schedule:run >> /dev/null 2>&1
```

Cela active les rappels automatiques, la purge des orgs expirees, et les rapports planifies.

---

## Choix de la base de donnees

### SQLite

Le plus simple. Un seul fichier, zero configuration serveur.
Ideal pour : dev local, petite organisation (< 50 utilisateurs).

```env
DB_CONNECTION=sqlite
```

### MySQL

Le plus courant. Performant, bien supporte partout.
Ideal pour : production, hebergement mutualise.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cica_gpro
DB_USERNAME=gpro
DB_PASSWORD=votre_mot_de_passe
```

Installer MySQL :

```bash
sudo apt install -y mysql-server
sudo mysql_secure_installation
```

Installer phpMyAdmin (optionnel) :

```bash
sudo apt install -y phpmyadmin
```

### PostgreSQL

Le plus robuste. Meilleur pour les grosses instances.
Ideal pour : production, gros volumes, fonctionnalites avancees.

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=cica_gpro
DB_USERNAME=gpro
DB_PASSWORD=votre_mot_de_passe
```

Installer PostgreSQL :

```bash
sudo apt install -y postgresql postgresql-contrib
```

Installer pgAdmin (optionnel) :

```bash
# Via le depot officiel
curl -fsS https://www.pgadmin.org/static/packages_pgadmin_org.pub | sudo gpg --dearmor -o /usr/share/keyrings/pgadmin.gpg
echo "deb [signed-by=/usr/share/keyrings/pgadmin.gpg] https://ftp.postgresql.org/pub/pgadmin/pgadmin4/apt/$(lsb_release -cs) pgadmin4 main" | sudo tee /etc/apt/sources.list.d/pgadmin4.list
sudo apt update
sudo apt install -y pgadmin4-web
sudo /usr/pgadmin4/bin/setup-web.sh
```

### Changer de base de donnees

Le code est **100% DB-agnostic** (zero SQL brut). Pour migrer :

1. Changez `DB_CONNECTION` et les parametres dans `.env`
2. Creez la nouvelle base (voir sections ci-dessus)
3. Relancez les migrations : `php artisan migrate --seed --force`

Les donnees existantes ne sont PAS migrees automatiquement.
Pour migrer les donnees, utilisez un outil comme `pgloader` (MySQL → PostgreSQL).

---

## WebSockets (optionnel)

Necessite un VPS ou serveur dedie (pas de mutualise).

1. Dans `.env` :

```env
BROADCAST_DRIVER=reverb
REVERB_APP_ID=gpro
REVERB_APP_KEY=gpro-key
REVERB_APP_SECRET=gpro-secret
REVERB_HOST=127.0.0.1
REVERB_PORT=6001
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST=votre-domaine.com
VITE_REVERB_PORT=6001
VITE_REVERB_SCHEME=https
```

2. Ajoutez un worker Supervisor :

```ini
[program:cica-gpro-reverb]
command=php /var/www/cica-gpro/artisan reverb:start --host=0.0.0.0 --port=6001
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/www/cica-gpro/storage/logs/reverb.log
```

3. Rebuild les assets (pour injecter les vars Vite) :

```bash
npm run build
```

4. Proxy Nginx pour WebSocket :

```nginx
location /app/ {
    proxy_pass http://127.0.0.1:6001;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "upgrade";
    proxy_set_header Host $host;
}
```

---

## Email

### Gmail SMTP

1. Activez "App Passwords" dans votre compte Google (Security > 2-Step Verification > App passwords)
2. Dans `.env` :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=xxxx-xxxx-xxxx-xxxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@votre-domaine.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Mailgun / SendGrid / autre

Meme principe, adaptez HOST, PORT, USERNAME, PASSWORD.

### Tester l'envoi

```bash
php artisan tinker
>>> Mail::raw('Test', fn($m) => $m->to('vous@email.com')->subject('Test'));
```

---

## Mise a jour

```bash
cd /var/www/cica-gpro

# Sauvegarder
php artisan down
mysqldump -u gpro -p cica_gpro > backup_$(date +%Y%m%d).sql  # ou pg_dump

# Mettre a jour
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan optimize

# Relancer
php artisan up
sudo supervisorctl restart cica-gpro-worker:*
```

---

## Sauvegarde automatique

```bash
sudo nano /etc/cron.d/cica-gpro-backup
```

**MySQL :**

```
0 3 * * * www-data mysqldump -u gpro -pVOTRE_MDP cica_gpro | gzip > /var/backups/cica-gpro/backup_$(date +\%Y\%m\%d).sql.gz
```

**PostgreSQL :**

```
0 3 * * * www-data pg_dump -U gpro cica_gpro | gzip > /var/backups/cica-gpro/backup_$(date +\%Y\%m\%d).sql.gz
```

```bash
sudo mkdir -p /var/backups/cica-gpro
sudo chown www-data:www-data /var/backups/cica-gpro
```

---

## Hebergement mutualise (LWS, o2switch, etc.)

Sur un mutualise, vous n'avez pas acces a Nginx/Supervisor.

1. Uploadez le projet via FTP ou Git
2. Le `.htaccess` dans `public/` gere le routing Apache
3. Pointez le domaine vers le dossier `public/`
4. Utilisez le panel pour creer la base MySQL
5. Configurez `.env` avec les infos du panel
6. Lancez via SSH (si disponible) : `php artisan migrate --seed --force`
7. **Queue** : utilisez `QUEUE_CONNECTION=sync` (pas de Supervisor sur mutualise)
8. **Cron** : ajoutez via le panel : `* * * * * cd /chemin/vers/cica-gpro && php artisan schedule:run`
9. **WebSockets** : non disponible sur mutualise

---

## Troubleshooting

### "Permission denied" sur storage/

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### "Class not found" apres une mise a jour

```bash
composer dump-autoload
php artisan optimize:clear
php artisan optimize
```

### "SQLSTATE Connection refused"

Verifiez que le serveur de base de donnees est demarre :

```bash
# MySQL
sudo systemctl status mysql

# PostgreSQL
sudo systemctl status postgresql
```

Verifiez les parametres dans `.env` (host, port, user, password).

### "413 Request Entity Too Large"

Augmentez la limite dans Nginx :

```nginx
client_max_body_size 20M;
```

Et dans `php.ini` :

```ini
upload_max_filesize = 20M
post_max_size = 20M
```

### Migration echoue

```bash
# Voir le statut
php artisan migrate:status

# Reset complet (ATTENTION: perd les donnees)
php artisan migrate:fresh --seed --force
```

### Les emails ne partent pas

```bash
# Tester la connexion SMTP
php artisan tinker
>>> Mail::raw('Test', fn($m) => $m->to('test@test.com')->subject('Test'));

# Si QUEUE_CONNECTION=database, verifier le worker
sudo supervisorctl status
```

### L'IA ne fonctionne pas

Verifiez qu'au moins une cle API est configuree dans `.env` :

```env
GROQ_API_KEY=gsk_...     # Recommande (gratuit, pas de restriction geo)
```

Ou configurez via l'interface admin (Settings > IA).
