<x-ui.page-layout>
    <x-ui.page-header :title="__('map.title')" :subtitle="count($markers) . ' ' . __('map.projects_on_map')">
    </x-ui.page-header>

    {{-- Filters --}}
    <x-ui.card class="mb-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <x-ui.select wire:model.live="statusFilter" icon="filter">
                    <option value="">{{ __('map.all_statuses') }}</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </x-ui.select>
            </div>
        </div>
    </x-ui.card>

    {{-- Map container --}}
    <x-ui.card>
        <div id="gpro-map" class="w-full h-[500px] rounded-xl overflow-hidden z-0"
             wire:ignore
             x-data="gproMap(@js($markers))"
             x-init="initMap()">
        </div>

        {{-- Legend --}}
        <div class="mt-4 flex flex-wrap gap-3">
            @foreach($statuses as $status)
                <div class="flex items-center gap-1.5 text-[10px]">
                    <span class="w-3 h-3 rounded-full" style="background: {{ $status->hex() }}"></span>
                    <span class="text-body">{{ $status->label() }}</span>
                </div>
            @endforeach
        </div>
    </x-ui.card>
</x-ui.page-layout>

@push('alpine-js')
{{-- Leaflet CSS & JS via CDN --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
function gproMap(markers) {
    return {
        map: null,
        markerLayer: null,

        initMap() {
            this.map = L.map('gpro-map').setView([5, 15], 3);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap',
                maxZoom: 18,
            }).addTo(this.map);

            this.addMarkers(markers);
        },

        addMarkers(data) {
            if (this.markerLayer) {
                this.map.removeLayer(this.markerLayer);
            }

            this.markerLayer = L.layerGroup();

            data.forEach(m => {
                const icon = L.divIcon({
                    className: 'gpro-marker',
                    html: `<div style="
                        width: 28px; height: 28px;
                        border-radius: 50%;
                        background: ${m.color};
                        border: 3px solid white;
                        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
                        display: flex; align-items: center; justify-content: center;
                        color: white; font-size: 10px; font-weight: 900;
                    ">${m.progress}%</div>`,
                    iconSize: [28, 28],
                    iconAnchor: [14, 14],
                });

                const popup = `
                    <div style="min-width: 180px; font-family: system-ui;">
                        <p style="font-weight: 900; font-size: 13px; margin: 0 0 4px 0;">${m.title}</p>
                        <p style="font-size: 11px; color: #666; margin: 0 0 6px 0;">
                            <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: ${m.color}; margin-right: 4px;"></span>
                            ${m.status} · ${m.progress}%
                        </p>
                        ${m.creator ? `<p style="font-size: 10px; color: #999; margin: 0 0 6px 0;">${m.creator}</p>` : ''}
                        <a href="${m.url}" style="font-size: 11px; color: #6366f1; text-decoration: none; font-weight: 700;">Voir le projet →</a>
                    </div>
                `;

                L.marker([m.lat, m.lng], { icon })
                    .bindPopup(popup)
                    .addTo(this.markerLayer);
            });

            this.markerLayer.addTo(this.map);

            if (data.length > 0) {
                const bounds = L.latLngBounds(data.map(m => [m.lat, m.lng]));
                this.map.fitBounds(bounds, { padding: [40, 40], maxZoom: 6 });
            }
        }
    };
}
</script>
@endpush
