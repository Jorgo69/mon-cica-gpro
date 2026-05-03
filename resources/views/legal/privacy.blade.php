<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('legal.privacy_title') }} — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-surface text-body min-h-screen">
    <div class="max-w-3xl mx-auto px-6 py-12">
        <a href="{{ url('/') }}" class="text-accent text-sm font-bold hover:underline mb-6 inline-block">&larr; {{ config('app.name') }}</a>
        <h1 class="text-2xl font-black text-heading mb-2">{{ __('legal.privacy_title') }}</h1>
        <p class="text-xs text-muted mb-8">{{ __('legal.last_updated') }} : {{ now()->format('d/m/Y') }}</p>

        <div class="prose prose-sm dark:prose-invert max-w-none space-y-6 text-body leading-relaxed">
            @foreach(__('legal.privacy_sections') as $section)
                <section>
                    <h2 class="text-lg font-bold text-heading">{{ $section['title'] }}</h2>
                    <p>{{ $section['content'] }}</p>
                </section>
            @endforeach
        </div>
    </div>
</body>
</html>
