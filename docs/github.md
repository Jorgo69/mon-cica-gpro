## 1. **Récupérer les dernières informations du dépôt distant**

```bash
git fetch origin
```

Cette commande va récupérer toutes les branches et tags du dépôt distant sans les fusionner.

## 2. **Voir toutes les branches disponibles (locales et distantes)**

```bash
git branch -a
```

Maintenant tu devrais voir les branches distantes de ton collègue, par exemple :
```
* main
  remotes/origin/main
  remotes/origin/nom-de-la-branche-de-ton-collegue
```

## 3. **Créer une branche locale pour suivre la branche distante**

```bash
git checkout -b nom-de-la-branche-de-ton-collegue origin/nom-de-la-branche-de-ton-collegue
```

Exemple :
```bash
git checkout -b feature/nouvelle-fonctionnalite origin/feature/nouvelle-fonctionnalite
```

## 4. **Si tu veux juste voir la branche sans la suivre**

```bash
git checkout --track origin/nom-de-la-branche-de-ton-collegue
```

## 5. **Pour mettre à jour ta branche locale avec les derniers changements**

Une fois que tu as checkout la branche, pour récupérer les derniers changements :

```bash
git pull origin nom-de-la-branche-de-ton-collegue
```

## 6. **Retourner sur ta branche principale**

```bash
git checkout main
```

## Résumé des commandes :

```bash
# Étape 1 : Récupérer les infos du dépôt distant
git fetch origin

# Étape 2 : Voir toutes les branches
git branch -a

# Étape 3 : Créer et basculer sur la branche de ton collègue
git checkout -b nom-branche-collegue origin/nom-branche-collegue

# Étape 4 : Récupérer les derniers changements (optionnel)
git pull origin nom-branche-collegue
```

`git push -u origin feature/summernote-editor`

## 💡 **Astuce importante :**

Si tu ne connais pas le nom exact de la branche de ton collègue, tu peux d'abord lister toutes les branches distantes :

```bash
git ls-remote --heads origin
```

Ou pour voir juste les noms :
```bash
git branch -r
```

Cela te montrera toutes les branches disponibles sur le dépôt distant. Une fois que tu identifies la branche de ton collègue, tu peux suivre les étapes ci-dessus.

___________________________________________________________

# D'abord, récupère la branche de ton collègue en local
git fetch origin
git checkout -b branche-collegue origin/branche-collegue

# Note les hash des commits que tu veux récupérer
git log --oneline

# Retourne sur ta branche principale
git checkout main

# Récupère un commit spécifique
git cherry-pick <hash-du-commit>

___________________________________________________________

## 7. **Workflow Multi-Dépôts (Double Remote)**

Si tu travailles pour un client mais que tu veux aussi sauvegarder ton travail sur ton propre compte GitHub :

### Configuration Initiale
Tu as par défaut le dépôt du client appelé `origin`. Tu dois ajouter ton propre dépôt appelé `perso` :
```bash
git remote add perso https://github.com/TonPseudo/mon-depot-perso.git
```

### Le Flux de Travail Quotidien (Workflow)

**1. Quand tu codes (sauvegarde chez TOI) :**
Travaille toujours sur ta branche `development` ou une sous-branche (`feature/...`).
```bash
# Envoyer ton travail en cours sur ton propre GitHub
git push perso development
```

**2. Quand tu livres au client (envoi chez LUI) :**
Quand tu estimes que le code sur `development` est parfait et prêt, tu le verses dans la branche dédiée au client (ex: `Projexia`) et tu l'envoies sur son dépôt (`origin`).
```bash
# Va sur la branche du client
git checkout Projexia

# Importe tout ton travail finalisé depuis development
git merge development

# Envoie le code au client
git push origin Projexia

# Retourne sur ta branche de développement pour la suite
git checkout development
```

### Problème Courant : "La branche par défaut n'est pas la bonne"
Si tu as poussé une branche par erreur en premier sur ton dépôt vierge et qu'elle est devenue la branche par défaut, tu peux forcer `development` comme branche principale ainsi :
```bash
git remote set-head perso development
```
*(Optionnel : Il faudra aussi le changer dans les "Settings -> Default branch" sur le site de GitHub).*