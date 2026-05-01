<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('plans.pricing_title') }} — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-surface text-body min-h-screen">

    <div class="max-w-5xl mx-auto px-6 py-16">
        <a href="{{ url('/') }}" class="text-accent text-sm font-bold hover:underline mb-8 inline-block">&larr; {{ config('app.name') }}</a>

        <div class="text-center mb-12">
            <h1 class="text-3xl font-black text-heading">{{ __('plans.pricing_title') }}</h1>
            <p class="text-sm text-muted mt-2">{{ __('plans.pricing_subtitle') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach(\App\Enums\Plan::cases() as $plan)
                @php
                    $config = config("gpro.plans.{$plan->value}");
                    $limits = $config['limits'];
                    $isPopular = $plan === \App\Enums\Plan::PRO;
                @endphp
                <div class="relative bg-card rounded-2xl border {{ $isPopular ? 'border-accent shadow-xl shadow-accent/10' : 'border-border-light' }} p-6 flex flex-col">
                    @if($isPopular)
                        <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 bg-accent text-white text-[9px] font-black uppercase tracking-widest rounded-full">
                            {{ __('plans.popular') }}
                        </div>
                    @endif

                    <div class="mb-6">
                        <h2 class="text-lg font-black {{ $plan->color() }}">{{ $plan->label() }}</h2>
                        <div class="mt-3">
                            <span class="text-3xl font-black text-heading">{{ $config['price'] }}</span>
                            <span class="text-xs text-muted">/{{ $config['price_period'] }}</span>
                        </div>
                    </div>

                    <div class="flex-1 space-y-3 mb-6">
                        <div class="flex items-center gap-2 text-xs">
                            <x-lucide-folder class="w-4 h-4 text-accent" />
                            <span>{{ $limits['max_projects'] === -1 ? __('plans.unlimited') : $limits['max_projects'] }} {{ __('plans.projects') }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs">
                            <x-lucide-users class="w-4 h-4 text-accent" />
                            <span>{{ $limits['max_members'] === -1 ? __('plans.unlimited') : $limits['max_members'] }} {{ __('plans.members') }}</span>
                        </div>

                        @php
                            $allFeatures = [
                                'logframe' => __('plans.feature_logframe'),
                                'basic_export' => __('plans.feature_basic_export'),
                                'pdf_export' => __('plans.feature_pdf'),
                                'excel_export' => __('plans.feature_excel'),
                                'share_link' => __('plans.feature_share'),
                                'templates' => __('plans.feature_templates'),
                                'indicators' => __('plans.feature_indicators'),
                                'budget_tracking' => __('plans.feature_budget'),
                                'multi_currency' => __('plans.feature_multi_currency'),
                                'api_access' => __('plans.feature_api'),
                                'priority_support' => __('plans.feature_support'),
                            ];
                        @endphp

                        @foreach($allFeatures as $key => $label)
                            <div class="flex items-center gap-2 text-xs {{ in_array($key, $limits['features']) ? 'text-body' : 'text-muted/40 line-through' }}">
                                @if(in_array($key, $limits['features']))
                                    <x-lucide-check class="w-4 h-4 text-success" />
                                @else
                                    <x-lucide-x class="w-4 h-4 text-muted/30" />
                                @endif
                                <span>{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>

                    <a href="{{ route('register') }}" class="block text-center px-4 py-2.5 rounded-xl text-sm font-bold transition-colors {{ $isPopular ? 'bg-accent text-white hover:bg-accent/90' : 'bg-surface text-heading hover:bg-surface-alt' }}">
                        {{ __('plans.get_started') }}
                    </a>
                </div>
            @endforeach
        </div>

        <p class="text-center text-xs text-muted mt-8">
            {{ __('plans.payment_note') }}
        </p>
    </div>

</body>
</html>
