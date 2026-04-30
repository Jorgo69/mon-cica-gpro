Délègue cette tâche au sub-agent **reviewer** (si disponible), sinon traite-la directement.

Relis les fichiers modifiés dans la session (pas tout le projet).

Vérifie :
1. Respect des règles listées dans `CLAUDE.md`.
2. Cohérence avec les patterns de `PATTERNS.md`.
3. Problèmes classiques : imports inutiles, console.log oubliés, `any` TypeScript, fonctions mortes, TODO laissés.
4. Gestion d'erreur (pas de catch vide, pas de promesse non gérée).

Format de sortie :
- Liste de problèmes, sévérité (🔴 bloquant / 🟡 à corriger / 🟢 suggestion).
- PAS de réécriture automatique. Attends mon feu vert pour corriger.

Si tout est propre, une seule phrase : "Tout est clean."
