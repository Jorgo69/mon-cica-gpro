<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hors ligne — CICA-GPRO</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #0f172a; color: #94a3b8; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .container { text-align: center; padding: 2rem; }
        .icon { width: 80px; height: 80px; margin: 0 auto 1.5rem; background: rgba(99,102,241,0.1); border-radius: 1rem; display: flex; align-items: center; justify-content: center; }
        .icon svg { width: 40px; height: 40px; color: #6366f1; }
        h1 { font-size: 1.5rem; font-weight: 900; color: #f1f5f9; margin-bottom: 0.5rem; }
        p { font-size: 0.875rem; max-width: 400px; margin: 0 auto 1.5rem; line-height: 1.6; }
        button { background: #6366f1; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 0.75rem; font-weight: 700; font-size: 0.875rem; cursor: pointer; }
        button:hover { background: #4f46e5; }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
            </svg>
        </div>
        <h1>Vous etes hors ligne</h1>
        <p>Verifiez votre connexion internet et reessayez. CICA-GPRO necessite une connexion pour fonctionner.</p>
        <button onclick="window.location.reload()">Reessayer</button>
    </div>
</body>
</html>
