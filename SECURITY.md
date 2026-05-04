# Politique de Securite

## Versions supportees

| Version | Supportee |
|---------|-----------|
| 2.0.x   | Oui       |
| < 2.0   | Non       |

## Signaler une vulnerabilite

**Ne signalez PAS les vulnerabilites de securite via les issues publiques GitHub.**

Envoyez un email a **security@cave-tech.com** avec :

1. Description de la vulnerabilite
2. Etapes pour la reproduire
3. Impact potentiel
4. Suggestion de correction (si vous en avez une)

## Delai de reponse

- **Accusé de reception** : sous 48h
- **Evaluation initiale** : sous 7 jours
- **Correction** : selon la severite (critique: 48h, haute: 7j, moyenne: 30j)

## Perimetre

Les vulnerabilites suivantes sont dans le perimetre :

- Injection SQL, XSS, CSRF
- Contournement d'authentification ou d'autorisation
- Fuite de donnees entre organisations (multi-tenancy)
- Elevation de privileges
- Exposition de cles API ou secrets

## Recompense

Nous n'avons pas de programme de bug bounty pour le moment, mais nous crediterons publiquement les chercheurs en securite qui signalent des vulnerabilites de maniere responsable (sauf si vous preferez rester anonyme).

## Bonnes pratiques pour les deployements

- Toujours utiliser HTTPS en production
- Changer `APP_KEY` apres l'installation
- Ne jamais exposer `.env` publiquement
- Mettre a jour regulierement les dependances (`composer update`, `npm update`)
- Configurer les sauvegardes automatiques de la base de donnees
- Activer le rate limiting sur les routes d'authentification
- Verifier les plugins avant de les activer (E9 Marketplace)
