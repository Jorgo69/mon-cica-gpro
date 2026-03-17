<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Rapport CICA-GPRO')</title>
    {{-- On utilise Tailwind via CDN pour que Browsershot puisse le charger sans dépendre de npm dev pendant la génération si besoin --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: white;
            color: #1e293b;
        }

        @page {
            size: A4;
            margin: 0;
        }

        .page-break {
            page-break-after: always;
        }

        .header-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }

        .text-accent { color: #f59e0b; }
        .bg-accent { background-color: #f59e0b; }
        
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body class="p-0 m-0 print:m-0">
    <div class="relative min-h-screen">
        @yield('content')
        
        {{-- Footer --}}
        <div class="absolute bottom-0 w-full p-8 border-t border-slate-100 flex justify-between items-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
            <div>Généré par CICA-GPRO Intelligent Engine</div>
            <div>Page <span class="pageNumber"></span></div>
        </div>
    </div>
</body>
</html>
