<x-layouts.legal :title="__('plans.pricing_title') . ' — ' . config('app.name')" width="max-w-5xl">
    <div class="text-center mb-12">
        <h1 class="text-3xl font-black text-heading">{{ __('plans.pricing_title') }}</h1>
        <p class="text-sm text-muted mt-2">{{ __('plans.pricing_subtitle') }}</p>
    </div>

    @php $plans = \App\Models\Plan::active()->ordered()->get(); @endphp

    <div class="grid grid-cols-1 md:grid-cols-{{ min($plans->count(), 3) }} gap-6">
        @foreach($plans as $plan)
            @php $isPopular = $plan->slug === 'pro'; @endphp
            <div class="relative bg-card rounded-2xl border {{ $isPopular ? 'border-accent shadow-xl shadow-accent/10' : 'border-border' }} p-6 flex flex-col">
                @if($isPopular)
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 bg-accent text-white text-[9px] font-black uppercase tracking-widest rounded-full">
                        {{ __('plans.popular') }}
                    </div>
                @endif

                <div class="mb-6">
                    <h2 class="text-lg font-black {{ $plan->color() }}">{{ $plan->label() }}</h2>
                    <div class="mt-3">
                        <span class="text-3xl font-black text-heading">{{ $plan->formattedPrice() }}</span>
                        <span class="text-xs text-muted">/{{ $plan->billing_period === 'month' ? __('plans.month') : __('plans.year') }}</span>
                    </div>
                </div>

                <div class="flex-1 space-y-3 mb-6">
                    <div class="flex items-center gap-2 text-xs">
                        <x-lucide-folder class="w-4 h-4 text-accent" />
                        <span>{{ $plan->maxProjects() === -1 ? __('plans.unlimited') : $plan->maxProjects() }} {{ __('plans.projects') }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs">
                        <x-lucide-users class="w-4 h-4 text-accent" />
                        <span>{{ $plan->maxMembers() === -1 ? __('plans.unlimited') : $plan->maxMembers() }} {{ __('plans.members') }}</span>
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
                        <div class="flex items-center gap-2 text-xs {{ $plan->hasFeature($key) ? 'text-body' : 'text-muted/40 line-through' }}">
                            @if($plan->hasFeature($key))
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
</x-layouts.legal>
