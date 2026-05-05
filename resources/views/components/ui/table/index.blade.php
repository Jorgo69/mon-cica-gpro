@props([])

<div class="overflow-x-auto -mx-6">
    <table class="w-full">
        @if(isset($headers) && !is_array($headers))
        <thead>
            <tr class="border-b border-border-light bg-surface-alt/50">
                {{ $headers }}
            </tr>
        </thead>
        @elseif(isset($head))
        <thead>
            <tr class="border-b border-border-light bg-surface-alt/50">
                {{ $head }}
            </tr>
        </thead>
        @endif
        <tbody class="divide-y divide-border-light">
            {{ $body ?? $slot }}
        </tbody>
    </table>
</div>
