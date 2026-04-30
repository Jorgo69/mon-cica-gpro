@props([
    'headers' => []
])

<div class="overflow-x-auto -mx-6">
    <table class="w-full">
        @if(isset($head))
        <thead>
            <tr class="border-b border-border-light">
                {{ $head }}
            </tr>
        </thead>
        @elseif(!empty($headers))
        <thead>
            <tr class="border-b border-border-light">
                @foreach($headers as $header)
                    <x-ui.table.th>{{ $header }}</x-ui.table.th>
                @endforeach
            </tr>
        </thead>
        @endif
        <tbody class="divide-y divide-border-light">
            {{ $body ?? $slot }}
        </tbody>
    </table>
</div>
