<?php

return [

    'title' => 'Frequently Asked Questions',
    'subtitle' => 'Find answers to the most common questions',
    'search_placeholder' => 'Search a question...',
    'no_results' => 'No results for this search.',

    'categories' => [
        'general' => 'General',
        'projects' => 'Projects',
        'collaboration' => 'Collaboration',
        'budget' => 'Budget & Finance',
        'export' => 'Exports & Reports',
        'ai' => 'Artificial Intelligence',
        'account' => 'Account & Security',
    ],

    'items' => [
        [
            'category' => 'general',
            'q' => 'What is CICA-GPRO?',
            'a' => 'CICA-GPRO is an intelligent project management system designed for NGOs and international organizations. It manages the complete project cycle: logical framework, activities, budgets, indicators, reports.',
        ],
        [
            'category' => 'general',
            'q' => 'What are the different user roles?',
            'a' => '<strong>ORG_ADMIN</strong>: Organization administrator (full management). <strong>MANAGER</strong>: Project manager (creation, tracking). <strong>MEMBER</strong>: Team member (create projects, track activities). <strong>INDEPENDENT</strong>: Solo user without organization.',
        ],
        [
            'category' => 'general',
            'q' => 'How to change the language?',
            'a' => 'Go to Settings > Language. You can choose between French and English. The language applies immediately.',
        ],
        [
            'category' => 'projects',
            'q' => 'How to create a project?',
            'a' => 'Click "New Project" from the dashboard or project list. Follow the 6 steps: key information, context, logical framework, results, activities, finalization.',
        ],
        [
            'category' => 'projects',
            'q' => 'What is a logical framework?',
            'a' => 'The logical framework (logframe) is a matrix that structures your project: General Objective > Specific Objectives > Results > Activities. Each level can have indicators, verification sources and assumptions.',
        ],
        [
            'category' => 'projects',
            'q' => 'How to use templates?',
            'a' => 'Go to Projects > Templates. You will find pre-configured projects. Click "Use this template" to create a customizable copy. You can also mark your own projects as templates.',
        ],
        [
            'category' => 'projects',
            'q' => 'How to track project progress?',
            'a' => 'Open a project and go to the "Tracking" tab. You will see overall progress, activities by status, and a forecast vs. actual comparison.',
        ],
        [
            'category' => 'collaboration',
            'q' => 'How to invite members?',
            'a' => 'Go to Administration > Invitations. Enter the person\'s email and role. They will receive an email with a link to join your organization.',
        ],
        [
            'category' => 'collaboration',
            'q' => 'How to share a project with a donor?',
            'a' => 'Open a project, click "Share". Create a share link with an expiration date. The donor can view a read-only dashboard without needing an account.',
        ],
        [
            'category' => 'collaboration',
            'q' => 'How to comment on an activity?',
            'a' => 'Open an activity. At the bottom of the page, you will find the comments section. You can mention colleagues with @name.',
        ],
        [
            'category' => 'budget',
            'q' => 'How to track actual expenses?',
            'a' => 'Open a project, go to activities, then use the "Expenses" section to record actual expenses. The system automatically calculates the variance with planned budget and burn rate.',
        ],
        [
            'category' => 'budget',
            'q' => 'How to configure currencies?',
            'a' => 'Go to Administration > Exchange Rates. You can set manual conversion rates between currencies. Each project can have its own currency.',
        ],
        [
            'category' => 'export',
            'q' => 'What export formats are available?',
            'a' => '<strong>PDF</strong>: Professional report (modern template). <strong>Excel</strong>: 3 sheets (Activities, Budget, Indicators). <strong>Word</strong>: Editable DOCX document.',
        ],
        [
            'category' => 'export',
            'q' => 'How to export to Excel?',
            'a' => 'Open a project, click "Excel Export" in the action bar. The file contains 3 tabs: Activities, Budget and Indicators.',
        ],
        // AI
        [
            'category' => 'ai',
            'q' => 'How does AI work in CICA-GPRO?',
            'a' => 'AI is integrated to help you write your projects faster. It can <strong>generate descriptions</strong>, <strong>suggest logical frameworks</strong>, and <strong>analyze your statistics</strong>. Everything it generates is a suggestion: you stay in full control of your content.',
        ],
        [
            'category' => 'ai',
            'q' => 'Where to find AI buttons?',
            'a' => 'AI buttons (purple with a sparkle icon) appear: <strong>1)</strong> Next to each field when creating a project (steps 2-5). <strong>2)</strong> On a project page: "AI Summary" button for an executive summary. <strong>3)</strong> On the dashboard: automatic analysis of your statistics.',
        ],
        [
            'category' => 'ai',
            'q' => 'Is AI free?',
            'a' => 'By default, the platform provides free AI (Groq). If you want to use another provider (OpenAI, Anthropic, etc.), configure your own API key in <strong>Settings > AI tab</strong>. Some providers like Groq, Gemini and Cohere offer free tiers.',
        ],
        [
            'category' => 'ai',
            'q' => 'How to configure my own AI key?',
            'a' => '<strong>Organization admins</strong>: Settings > AI tab > "My own AI key" > choose a provider, paste your API key. <strong>Independents</strong>: same path. <strong>Members</strong>: you use your organization\'s configuration, contact your admin.',
        ],
        [
            'category' => 'ai',
            'q' => 'Where to get a free API key?',
            'a' => 'We recommend <strong>Groq</strong> (free, fast, works everywhere): create an account at <a href="https://console.groq.com/keys" target="_blank" class="text-accent underline">console.groq.com</a>. Other free options: Google Gemini (blocked in some African countries), Cohere (1000 requests/month).',
        ],
        [
            'category' => 'ai',
            'q' => 'Is my API key secure?',
            'a' => 'Yes. Your key is <strong>encrypted</strong> in the database using Laravel\'s AES-256-CBC algorithm. No one — not even the system administrator — can see your key in plain text after entry. Only the system decrypts it when calling the AI.',
        ],
        [
            'category' => 'ai',
            'q' => 'AI is not working, what to do?',
            'a' => 'Check: <strong>1)</strong> That an API key is configured (Settings > AI or contact your admin). <strong>2)</strong> That the admin hasn\'t disabled AI for your account. <strong>3)</strong> That the project title is filled (AI needs context). <strong>4)</strong> That your quota isn\'t exhausted at the provider. When in doubt, click "Test connection" in the configuration.',
        ],
        [
            'category' => 'ai',
            'q' => 'Can the admin disable AI for a member?',
            'a' => 'Yes. The organization admin can, in Settings > AI tab > "AI per member" section, enable or disable AI access for each member individually.',
        ],
        [
            'category' => 'ai',
            'q' => 'Which AI providers are supported?',
            'a' => '9 providers are supported: <strong>Groq</strong> (Llama, free), <strong>Google Gemini</strong> (free), <strong>OpenAI</strong> (ChatGPT), <strong>Anthropic</strong> (Claude), <strong>Mistral</strong> (French), <strong>DeepSeek</strong> (affordable), <strong>Cohere</strong> (free limited), <strong>Together AI</strong> (multi-model), and <strong>Custom</strong> (your own local AI server).',
        ],
        [
            'category' => 'ai',
            'q' => 'Can I use a local AI server (Ollama, vLLM)?',
            'a' => 'Yes! Choose the "Custom" provider and enter your server URL (e.g., http://localhost:11434/v1 for Ollama). The server must be compatible with the OpenAI API format (chat/completions).',
        ],

        [
            'category' => 'account',
            'q' => 'How to change my password?',
            'a' => 'Go to your Profile (click your avatar at the top right). In the "Change password" section, enter your old and new password.',
        ],
        [
            'category' => 'account',
            'q' => 'How to change my avatar?',
            'a' => 'Go to your Profile. Click on your current avatar to open the selector. Choose from 24 predefined avatars.',
        ],
        [
            'category' => 'account',
            'q' => 'I am not receiving email notifications.',
            'a' => 'Check in Settings > Notifications that email notifications are enabled. Also check your spam folder. If the problem persists, contact your administrator.',
        ],
    ],

];
