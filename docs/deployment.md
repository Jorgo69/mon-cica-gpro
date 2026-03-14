# Guide de Déploiement — LWS (FTP + Subdomain)

Ce guide explique comment configurer le déploiement automatique depuis la branche `development` vers votre hébergement LWS.

## 1. Préparation sur le Panel LWS

### Création du Sous-Domaine
1. Connectez-vous à votre espace **LWS Panel**.
2. Cliquez sur le bouton "Gérer" à côté de votre domaine.
3. Allez dans la section **"Gestion du Domaine"** -> **"Sous-domaines"**.
4. Saisissez le nom de votre sous-domaine (ex: `dev`).
5. Dans le champ **"Destination"**, indiquez un dossier spécifique (ex: `/public_html/cpro-dev`).
6. Validez.

> [!NOTE]
> Le fichier `.htaccess` à la racine s'occupera de rediriger les requêtes vers le dossier `public` de Laravel.

---

## 2. Configuration sur GitHub (Secrets)

Vous devez ajouter les secrets suivants dans **Settings > Secrets and variables > Actions** :

| Nom du Secret | Description | Valeur / Exemple |
|---|---|---|
| `FTP_SERVER` | Hôte FTP (LWS) | `ftp.campuschine.org` |
| `FTP_USERNAME` | Identifiant FTP | `campu2743848` |
| `FTP_PASSWORD` | Mot de passe FTP | `5X2u8XGJErwXysR` |
| `FTP_SERVER_DIR` | Dossier du sous-domaine (doit finir par `/`) | `cica-pro.campuschine.org/` |
| `APP_URL` | URL du sous-domaine | `https://cica-pro.campuschine.org` |
| `WEBHOOK_SECRET` | Token de sécurité | `VOTRE_TOKEN_ALEATOIRE` |

---

## 3. Configuration de l'environnement (.env sur le serveur)

Une fois les fichiers transférés par FTP, vous devrez créer ou modifier le fichier `.env` sur votre serveur LWS :

1. Assurez-vous que `WEBHOOK_SECRET` correspond exactement à celui mis dans les secrets GitHub.
2. Configurez vos accès base de données.
3. `APP_ENV=production` et `APP_DEBUG=false`.

---

## 4. Fonctionnement du workflow

- Chaque **push** sur la branche `development` déclenche :
    1. Le build des assets JS/CSS (`npm run build`).
    2. L'installation des dépendances PHP (`composer install`).
    3. Le transfert de tous les fichiers (incluant `vendor`) vers LWS.
    4. L'appel à `webhook.php` qui lance automatiquement `deploy.sh` pour les migrations et le cache.

---

## 5. Dépannage

- **Logs Webhook** : Si le déploiement semble échouer à la fin, vérifiez le fichier `storage/logs/webhook.log` sur votre serveur.
- **Dossier Vendor** : Nous envoyons le dossier `vendor` car LWS mutualisé ne permet pas d'exécuter Composer en ligne de commande facilement.
