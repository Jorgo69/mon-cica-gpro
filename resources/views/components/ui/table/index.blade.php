@props([
    'headers' => []
])

<div class="overflow-x-auto -mx-6">
    <table class="w-full">
        <thead>
            <tr class="border-b border-slate-100 dark:border-slate-800">
                {{ $headers }}
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
            {{ $slot }}
        </tbody>
    </table>
</div>
