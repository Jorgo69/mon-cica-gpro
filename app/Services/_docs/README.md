# Services Métier (Business Logic)

Ce dossier contient des classes de service chargées de la logique métier lourde, des calculs complexes et des interactions avec des APIs externes.

## Règles d'Or
*   **Stateless** : Les services ne doivent pas stocker d'état. Ils reçoivent des données, les traitent et retournent un résultat.
*   **Injection de Dépendances** : Toujours injecter les services via le constructeur pour faciliter les tests.
*   **Single Responsibility** : Un service ne doit pas tout faire. (ex: Ne pas mélanger calcul de budget et envoi d'emails).

## Services Disponibles

### [StatisticsService.php] (Prévu)
Centralisera tous les calculs de progression, taux de réussite et agrégations financières pour le Dashboard.

### [ExportService.php] (Prévu)
Gestion de la génération des documents PDF et Word (PHPWord).

---
*Note : Si une action modifie la base de données de manière unitaire, utilisez plutôt le dossier [Actions](file:///app/Actions/README.md).*
