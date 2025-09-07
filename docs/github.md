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