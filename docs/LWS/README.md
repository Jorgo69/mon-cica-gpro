Déploiement CICA-GPRO sur LWS : Succès !
Le déploiement du sous-domaine cica-pro.campuschine.org est désormais fonctionnel et sécurisé.

Structure du Serveur
Nous avons mis en place une infrastructure robuste basée sur un lien symbolique pour contourner les limitations de routage de LWS :

Répertoire de l'application : ~/htdocs/cica-pro-app/
Lien symbolique du sous-domaine : ~/htdocs/cica-pro.campuschine.org -> cica-pro-app/public
Cette structure garantit que le serveur Apache de LWS entre directement dans le dossier public/ de Laravel, évitant ainsi les boucles de redirection complexes à la racine.

Modifications effectuées
1. Isolation du domaine principal
Le fichier 
.htaccess
 à la racine /htdocs/ a été configuré pour ne rediriger vers public/ que si la requête concerne campuschine.org. Cela empêche toute interférence avec le sous-domaine.

2. Configuration du sous-domaine
Le dossier public/ contient désormais un 
.htaccess
 simplifié avec RewriteBase /.
Le fichier 
public/index.php
 a été restauré pour la production (sans hooks de debug).
3. Permissions
Tous les dossiers critiques (storage, bootstrap/cache) ont été configurés en 0777 pour permettre à Laravel d'écrire ses logs et ses caches.

Configuration CI/CD (GitHub Actions)
Pour tes prochains déploiements, tu dois mettre à jour tes secrets GitHub :

FTP_SERVER_DIR : cica-pro-app/
(C'est très important : GitHub doit envoyer les fichiers dans le dossier "app", et le lien symbolique sur le serveur fera le reste).
Bravo pour ta patience, c'était un défi complexe sur cet environnement mutualisé !