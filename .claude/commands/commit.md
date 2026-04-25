Prépare un commit propre pour les changements en cours.

1. Lance `git status` et `git diff --stat` pour voir ce qui a changé.
2. Regroupe logiquement les changements s'il y en a plusieurs types (feat, fix, refactor, docs…).
3. Propose un message de commit au format Conventional Commits :
   ```
   type(scope): résumé court en impératif

   - détail 1
   - détail 2
   ```
   Types : feat, fix, refactor, docs, test, chore, style.

4. Si plusieurs sujets dans les changements, propose de SCINDER en plusieurs commits avec un message par commit.

NE LANCE PAS `git commit` toi-même. Affiche le(s) message(s) et attends mon OK.
