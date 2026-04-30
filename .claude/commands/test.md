Délègue cette tâche au sub-agent **testeur** (si disponible), sinon traite-la directement.

Pour la fonction/fichier que je vais pointer :

1. Identifie le framework de test du projet (Jest, pytest, Vitest, etc.). Si aucun détecté, demande-moi lequel utiliser.
2. Consulte `PATTERNS.md` pour voir si un style de tests existe déjà.
3. Écris les tests qui couvrent :
   - Cas nominal.
   - Cas limites (empty, null, large, negative, unicode selon pertinence).
   - Cas d'erreur (inputs invalides, erreurs réseau, etc. selon contexte).
4. Pas de sur-test : 1 assertion claire par test, noms de tests explicites.
5. Propose d'ajouter un pattern de test récurrent à `PATTERNS.md` si pertinent.

Affiche les tests à valider avant de les écrire dans les fichiers.
