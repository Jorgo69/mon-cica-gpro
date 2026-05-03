<?php

return [

    '400' => [
        'title' => 'Requête Invalide',
        'message' => 'La requête envoyée est invalide ou mal formée.',
    ],

    '401' => [
        'title' => 'Non Authentifié',
        'message' => 'Vous devez être connecté pour accéder à cette page.',
    ],

    '403' => [
        'title' => 'Accès Interdit',
        'message' => 'Vous n\'avez pas les permissions nécessaires pour accéder à cette ressource.',
    ],

    '404' => [
        'title' => 'Page Non Trouvée',
        'message' => 'Désolé, la page que vous recherchez n\'existe pas ou a été déplacée.',
    ],

    '419' => [
        'title' => 'Page Expirée',
        'message' => 'Votre session a expiré. Veuillez rafraîchir la page et réessayer.',
    ],

    '429' => [
        'title' => 'Trop de Requêtes',
        'message' => 'Vous avez effectué trop de requêtes. Veuillez patienter avant de réessayer.',
    ],

    '500' => [
        'title' => 'Erreur Interne du Serveur',
        'message' => 'Une erreur inattendue s\'est produite. Notre équipe a été notifiée.',
    ],

    '503' => [
        'title' => 'Service Indisponible',
        'message' => 'Le service est temporairement indisponible. Veuillez réessayer dans quelques instants.',
    ],

    'back_home' => 'Retour à l\'accueil',
    'login' => 'Se connecter',
    'refresh' => 'Rafraîchir',
    'retry' => 'Réessayer',

];
