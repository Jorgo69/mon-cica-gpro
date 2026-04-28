<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Rapport CICA-GPRO')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.5;
            background: white;
        }

        @page {
            size: A4;
            margin: 15mm 12mm;
        }

        .page-break { page-break-after: always; }

        /* Typography */
        h1 { font-size: 22px; font-weight: bold; color: #0f172a; margin-bottom: 8px; }
        h2 { font-size: 16px; font-weight: bold; color: #1e293b; margin-bottom: 6px; border-bottom: 2px solid #e74c6f; padding-bottom: 4px; }
        h3 { font-size: 13px; font-weight: bold; color: #334155; margin-bottom: 4px; }
        h4 { font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }

        p { margin-bottom: 6px; }

        /* Colors */
        .text-primary { color: #e74c6f; }
        .text-accent { color: #0ea5e9; }
        .text-muted { color: #94a3b8; }
        .text-success { color: #22c55e; }
        .text-warning { color: #f59e0b; }

        .bg-primary { background-color: #e74c6f; }
        .bg-light { background-color: #f8fafc; }
        .bg-accent-light { background-color: #f0f9ff; }

        /* Layout */
        .flex { display: flex; }
        .grid-2 { display: table; width: 100%; }
        .grid-2 .col { display: table-cell; width: 50%; vertical-align: top; padding-right: 12px; }
        .grid-2 .col:last-child { padding-right: 0; padding-left: 12px; }

        /* Cards / Sections */
        .section {
            margin-bottom: 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }
        .section-header {
            background: #f8fafc;
            padding: 8px 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 12px;
            font-weight: bold;
            color: #334155;
        }
        .section-body { padding: 12px; }

        /* Table */
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th, td { padding: 6px 10px; text-align: left; border-bottom: 1px solid #e2e8f0; font-size: 10px; }
        th { background: #f1f5f9; font-weight: bold; color: #475569; text-transform: uppercase; font-size: 9px; letter-spacing: 0.5px; }
        tr:last-child td { border-bottom: none; }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-primary { background: #fce7f3; color: #be185d; }
        .badge-accent { background: #e0f2fe; color: #0369a1; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-slate { background: #f1f5f9; color: #475569; }

        /* Cover page */
        .cover {
            text-align: center;
            padding-top: 120px;
        }
        .cover h1 {
            font-size: 28px;
            color: #0f172a;
            margin-bottom: 16px;
        }
        .cover .subtitle {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 8px;
        }
        .cover .divider {
            width: 60px;
            height: 3px;
            background: #e74c6f;
            margin: 20px auto;
            border-radius: 2px;
        }
        .cover .meta {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 40px;
        }

        /* Info row */
        .info-row { margin-bottom: 6px; }
        .info-label { font-size: 9px; font-weight: bold; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }
        .info-value { font-size: 11px; color: #1e293b; font-weight: 600; }

        /* Indicator card */
        .indicator {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px 10px;
            margin-bottom: 6px;
        }

        /* Footer */
        .footer {
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            margin-top: 20px;
            padding-top: 8px;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    @yield('content')

    <div class="footer">
        Genere par CICA-GPRO &bull; {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
