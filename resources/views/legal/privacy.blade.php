<x-layouts.legal :title="__('legal.privacy_title') . ' — ' . config('app.name')">
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
</x-layouts.legal>
