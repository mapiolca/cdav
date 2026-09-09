# ChangeLog

## 3.3 — Non publiée

- Réglages répartis dans cinq onglets natifs : Réglages, CardDAV, CalDAV, Compatibilité et À propos ; sauvegarde limitée à l’onglet et à l’entité, avec validation des références.
- Catalogues anglais, français, allemand, espagnol et italien ; métadonnées affichées depuis le descripteur, identité CDav et crédits Befox conservés.
- Connexion DAV stateless fixée sur l’entité de l’URL avant chargement ; authentification native et contrôle séparé de l’admission Multicompany, découverte des affectations transverses et droits réels des liens ICS.
- Montage WebDAV administratif limité aux répertoires configurés des modules natifs, avec contrôles de droits et de chemins ; les répertoires d’autres entités et les liens symboliques ne sont plus parcourus.
- Mutations CardDAV et CalDAV réalisées par les objets natifs, avec transactions et conservation du propriétaire des objets partagés ; métadonnées URI/UID CardDAV séparées et installation idempotente. Décodage des liens ICS historiques et enveloppes iCalendar corrigés.
- Génération des tâches depuis le trigger natif `PROJECT_VALIDATE`, dans la transaction de validation : services et documents accessibles, rôles contrôlés, numérotation native, absence de doublons et annulation complète en cas d’échec. Valider les projets depuis leur entité propriétaire.
- Socle conservé : Dolibarr 16.0 et PHP 8.0 ; cible de vérification étendue à Dolibarr 24, sous réserve des exigences PHP du core.

Les validations sur instances Dolibarr et Multicompany, notamment en mode transverse, restent à exécuter. Les contrôles simulés sont identifiés comme tels.

L’historique antérieur reste disponible dans les commits du dépôt ; aucune date de publication passée n’est reconstituée.
