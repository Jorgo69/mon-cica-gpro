{{-- resources/views/messages/index.blade.php --}}

@props(['context' => null]) <!-- contexte optionnel : 'project', 'activity', 'sub-activity' -->

@php
    $alertTypes = [
        'success', 
        'error', 
        'info', 
        'warning'
    ];

    $contexts = [
        'project', 
        'activity', 
        'sub-activity',
        'resource'
    ];

    // Si un contexte est spécifié, on filtre
    $targetContexts = $context ? [$context] : $contexts;
@endphp

@foreach($alertTypes as $type)
    @foreach($targetContexts as $ctx)
        @php
            $sessionKey = "{$type}-{$ctx}";
        @endphp

        @if(session($sessionKey))
            @php
                // Configuration par type
                $config = [
                    'success' => [
                        'title' => 'Succès',
                        'bgColor' => 'teal',
                        'borderColor' => 'teal',
                        'textColor' => 'teal',
                        'iconColor' => 'text-green-500',
                        'icon' => '<path d="M10 2a8 8 0 1 0 0 16 8 8 0 0 0 0-16zm0 2a6 6 0 1 1 0 12 6 6 0 0 1 0-12zm-1 8l-3-3 1.5-1.5L9 10l3.5-3.5L14 8l-5 5z"/>',
                    ],
                    'error' => [
                        'title' => 'Erreur',
                        'bgColor' => 'red',
                        'borderColor' => 'red',
                        'textColor' => 'red',
                        'iconColor' => 'text-red-500',
                        'icon' => '<path d="M10 2a8 8 0 1 0 0 16 8 8 0 0 0 0-16zm0 2a6 6 0 1 1 0 12 6 6 0 0 1 0-12zm-1 8l1 1 1-1 3-3-1-1-1 1-3 3z"/>',
                    ],
                    'info' => [
                        'title' => 'Information',
                        'bgColor' => 'blue',
                        'borderColor' => 'blue',
                        'textColor' => 'blue',
                        'iconColor' => 'text-blue-500',
                        'icon' => '<path d="M10 2a8 8 0 1 0 0 16 8 8 0 0 0 0-16zm0 2a6 6 0 1 1 0 12 6 6 0 0 1 0-12zm0 8a1 1 0 0 1-1-1v-4a1 1 0 0 1 2 0v4a1 1 0 0 1-1 1zm0-7a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>',
                    ],
                    'warning' => [
                        'title' => 'Attention',
                        'bgColor' => 'amber',
                        'borderColor' => 'amber',
                        'textColor' => 'amber',
                        'iconColor' => 'text-amber-500',
                        'icon' => '<path d="M10 2a8 8 0 1 1 0 16 8 8 0 0 1 0-16zm0 2a6 6 0 1 0 0 12 6 6 0 0 0 0-12zm0 8a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-6a1 1 0 0 1 1 1v4a1 1 0 1 1-2 0V7a1 1 0 0 1 1-1z"/>',
                    ],
                ][$type];
            @endphp

            <div class="alert-container" data-duration="5000">
                <div class="bg-{{ $config['bgColor'] }}-100 border-t-4 border-{{ $config['borderColor'] }}-500 rounded-b text-{{ $config['textColor'] }}-900 px-4 py-3 my-2 shadow-md relative" role="alert">
                    <div class="flex">
                        <div class="py-1">
                            <svg class="fill-current h-6 w-6 {{ $config['iconColor'] }} mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                {!! $config['icon'] !!}
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold">{{ $config['title'] }}</p>
                            <p class="text-sm">{{ session($sessionKey) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endforeach

@push('message-js')
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.alert-container').forEach(container => {
            const duration = container.dataset.duration;
            setTimeout(() => {
                container.style.transition = 'opacity 0.5s';
                container.style.opacity = '0';
                setTimeout(() => container.remove(), 500);
            }, parseInt(duration));
        });
    });
    </script>
@endpush