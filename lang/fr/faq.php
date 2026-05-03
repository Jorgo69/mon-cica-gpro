<?php

return [

    'title' => 'Foire Aux Questions',
    'subtitle' => 'Trouvez des reponses aux questions les plus frequentes',
    'search_placeholder' => 'Rechercher une question...',
    'no_results' => 'Aucun resultat pour cette recherche.',

    'categories' => [
        'general' => 'General',
        'projects' => 'Projets',
        'collaboration' => 'Collaboration',
        'budget' => 'Budget & Finances',
        'export' => 'Exports & Rapports',
        'ai' => 'Intelligence Artificielle',
        'account' => 'Compte & Securite',
    ],

    'items' => [
        // General
        [
            'category' => 'general',
            'q' => 'Qu\'est-ce que CICA-GPRO ?',
            'a' => 'CICA-GPRO est un systeme intelligent de gestion de projets concu pour les ONG et organisations internationales. Il permet de gerer le cycle complet d\'un projet : cadre logique, activites, budgets, indicateurs, rapports.',
        ],
        [
            'category' => 'general',
            'q' => 'Quels sont les differents roles utilisateur ?',
            'a' => '<strong>ORG_ADMIN</strong> : Administrateur de l\'organisation (gestion complete). <strong>MANAGER</strong> : Gestionnaire de projets (creation, suivi). <strong>MEMBER</strong> : Membre d\'equipe (creation projets, suivi activites). <strong>INDEPENDENT</strong> : Utilisateur solo sans organisation.',
        ],
        [
            'category' => 'general',
            'q' => 'Comment changer la langue ?',
            'a' => 'Allez dans Parametres > Langue. Vous pouvez choisir entre Francais et Anglais. La langue s\'applique immediatement.',
        ],

        // Projects
        [
            'category' => 'projects',
            'q' => 'Comment creer un projet ?',
            'a' => 'Cliquez sur "Nouveau Projet" depuis le tableau de bord ou la liste des projets. Suivez les 6 etapes : informations cles, contexte, cadre logique, resultats, activites, finalisation.',
        ],
        [
            'category' => 'projects',
            'q' => 'Qu\'est-ce qu\'un cadre logique ?',
            'a' => 'Le cadre logique (logframe) est une matrice qui structure votre projet : Objectif General > Objectifs Specifiques > Resultats > Activites. Chaque niveau peut avoir des indicateurs, des sources de verification et des hypotheses.',
        ],
        [
            'category' => 'projects',
            'q' => 'Comment utiliser les templates ?',
            'a' => 'Allez dans Projets > Templates. Vous y trouverez des projets pre-configures. Cliquez "Utiliser ce template" pour creer une copie personnalisable. Vous pouvez aussi marquer vos propres projets comme templates.',
        ],
        [
            'category' => 'projects',
            'q' => 'Comment suivre la progression d\'un projet ?',
            'a' => 'Ouvrez un projet et allez dans l\'onglet "Suivi". Vous verrez la progression globale, les activites par statut, et un comparatif previsions/realisations. La progression est calculee automatiquement a partir des activites.',
        ],

        // Collaboration
        [
            'category' => 'collaboration',
            'q' => 'Comment inviter des membres ?',
            'a' => 'Allez dans Administration > Invitations. Saisissez l\'email de la personne et son role. Elle recevra un email avec un lien pour rejoindre votre organisation.',
        ],
        [
            'category' => 'collaboration',
            'q' => 'Comment partager un projet avec un bailleur ?',
            'a' => 'Ouvrez un projet, cliquez "Partager". Creez un lien de partage avec une date d\'expiration. Le bailleur pourra consulter un tableau de bord en lecture seule sans avoir besoin de compte.',
        ],
        [
            'category' => 'collaboration',
            'q' => 'Comment commenter une activite ?',
            'a' => 'Ouvrez une activite. En bas de la page, vous trouverez la section commentaires. Vous pouvez mentionner des collegues avec @nom.',
        ],

        // Budget
        [
            'category' => 'budget',
            'q' => 'Comment suivre les depenses reelles ?',
            'a' => 'Ouvrez un projet, allez dans les activites, puis utilisez la section "Depenses" pour enregistrer les depenses reelles. Le systeme calcule automatiquement l\'ecart avec le budget planifie et le taux de consommation.',
        ],
        [
            'category' => 'budget',
            'q' => 'Comment configurer les devises ?',
            'a' => 'Allez dans Administration > Taux de Change. Vous pouvez definir des taux de conversion manuels entre devises. Chaque projet peut avoir sa propre devise.',
        ],

        // Export
        [
            'category' => 'export',
            'q' => 'Quels formats d\'export sont disponibles ?',
            'a' => '<strong>PDF</strong> : Rapport professionnel (template moderne). <strong>Excel</strong> : 3 feuilles (Activites, Budget, Indicateurs). <strong>Word</strong> : Document DOCX editable.',
        ],
        [
            'category' => 'export',
            'q' => 'Comment exporter en Excel ?',
            'a' => 'Ouvrez un projet, cliquez "Export Excel" dans la barre d\'actions. Le fichier contient 3 onglets : Activites, Budget et Indicateurs.',
        ],

        // AI
        [
            'category' => 'ai',
            'q' => 'Comment fonctionne l\'IA dans CICA-GPRO ?',
            'a' => 'L\'IA est integree pour vous aider a rediger vos projets plus vite. Elle peut <strong>generer des descriptions</strong>, <strong>suggerer des cadres logiques</strong> complets, et <strong>analyser vos statistiques</strong>. Tout ce qu\'elle genere est une suggestion : vous restez maitre de vos contenus.',
        ],
        [
            'category' => 'ai',
            'q' => 'Ou trouver les boutons IA ?',
            'a' => 'Les boutons IA (violet avec une icone etincelle) apparaissent : <strong>1)</strong> A cote de chaque champ lors de la creation d\'un projet (etapes 2 a 5). <strong>2)</strong> Sur la page d\'un projet : bouton "Resume IA" pour un resume executif. <strong>3)</strong> Sur le tableau de bord : analyse automatique de vos statistiques.',
        ],
        [
            'category' => 'ai',
            'q' => 'L\'IA est-elle gratuite ?',
            'a' => 'Par defaut, la plateforme fournit une IA gratuite (Groq). Si vous souhaitez utiliser un autre provider (OpenAI, Anthropic, etc.), vous pouvez configurer votre propre cle API dans les <strong>Parametres > onglet IA</strong>. Certains providers comme Groq, Gemini et Cohere offrent des tiers gratuits.',
        ],
        [
            'category' => 'ai',
            'q' => 'Comment configurer ma propre cle IA ?',
            'a' => '<strong>Admins d\'organisation</strong> : Parametres > onglet IA > "Ma propre cle IA" > choisissez un provider, collez votre cle API. <strong>Independants</strong> : meme chemin. <strong>Membres</strong> : vous utilisez la configuration de votre organisation, contactez votre admin.',
        ],
        [
            'category' => 'ai',
            'q' => 'Ou obtenir une cle API gratuite ?',
            'a' => 'Nous recommandons <strong>Groq</strong> (gratuit, rapide, fonctionne partout) : creez un compte sur <a href="https://console.groq.com/keys" target="_blank" class="text-accent underline">console.groq.com</a>. Autres options gratuites : Google Gemini (bloque dans certains pays africains), Cohere (1000 requetes/mois).',
        ],
        [
            'category' => 'ai',
            'q' => 'Ma cle API est-elle en securite ?',
            'a' => 'Oui. Votre cle est <strong>chiffree</strong> en base de donnees avec l\'algorithme AES-256-CBC de Laravel. Personne — meme l\'administrateur systeme — ne peut voir votre cle en clair apres saisie. Seul le systeme la dechiffre au moment d\'appeler l\'IA.',
        ],
        [
            'category' => 'ai',
            'q' => 'L\'IA ne fonctionne pas, que faire ?',
            'a' => 'Verifiez : <strong>1)</strong> Qu\'une cle API est bien configuree (Parametres > IA ou contactez votre admin). <strong>2)</strong> Que l\'admin n\'a pas desactive l\'IA pour votre compte. <strong>3)</strong> Que le titre du projet est renseigne (l\'IA a besoin de contexte). <strong>4)</strong> Que votre quota n\'est pas epuise chez le provider. En cas de doute, cliquez "Tester la connexion" dans la configuration.',
        ],
        [
            'category' => 'ai',
            'q' => 'L\'admin peut-il desactiver l\'IA pour un membre ?',
            'a' => 'Oui. L\'admin d\'organisation peut, dans Parametres > onglet IA > section "IA par membre", activer ou desactiver l\'acces a l\'IA pour chaque membre individuellement.',
        ],
        [
            'category' => 'ai',
            'q' => 'Quels providers IA sont supportes ?',
            'a' => '9 providers sont supportes : <strong>Groq</strong> (Llama, gratuit), <strong>Google Gemini</strong> (gratuit), <strong>OpenAI</strong> (ChatGPT), <strong>Anthropic</strong> (Claude), <strong>Mistral</strong> (francais), <strong>DeepSeek</strong> (economique), <strong>Cohere</strong> (gratuit limite), <strong>Together AI</strong> (multi-modeles), et <strong>Custom</strong> (votre propre serveur IA local).',
        ],
        [
            'category' => 'ai',
            'q' => 'Puis-je utiliser un serveur IA local (Ollama, vLLM) ?',
            'a' => 'Oui ! Choisissez le provider "Personnalise" et renseignez l\'URL de votre serveur (ex: http://localhost:11434/v1 pour Ollama). Le serveur doit etre compatible avec le format API OpenAI (chat/completions).',
        ],

        // Account
        [
            'category' => 'account',
            'q' => 'Comment changer mon mot de passe ?',
            'a' => 'Allez dans votre Profil (cliquez sur votre avatar en haut a droite). Dans la section "Modifier le mot de passe", saisissez votre ancien et nouveau mot de passe.',
        ],
        [
            'category' => 'account',
            'q' => 'Comment changer mon avatar ?',
            'a' => 'Allez dans votre Profil. Cliquez sur votre avatar actuel pour ouvrir le selecteur. Choisissez parmi les 24 avatars predifinis.',
        ],
        [
            'category' => 'account',
            'q' => 'Je ne recois pas les notifications par email.',
            'a' => 'Verifiez dans Parametres > Notifications que les notifications email sont activees. Verifiez aussi vos spams. Si le probleme persiste, contactez votre administrateur.',
        ],
    ],

];
