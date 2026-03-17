@props([
    'type' => 'bar',
    'labels' => [],
    'datasets' => [],
    'options' => [],
    'id' => 'chart-' . str()->random(8),
    'height' => '300px'
])

<div x-data="{
    chart: null,
    init() {
        if (typeof Chart === 'undefined') {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
            script.onload = () => this.renderChart();
            document.head.appendChild(script);
        } else {
            this.renderChart();
        }
    },
    renderChart() {
        const ctx = document.getElementById('{{ $id }}').getContext('2d');
        
        // Configuration par défaut Premium
        const defaultOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: { size: 12, family: 'Inter' },
                        color: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#64748b'
                    }
                },
                tooltip: {
                    backgroundColor: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                    titleColor: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#0f172a',
                    bodyColor: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#64748b',
                    borderColor: document.documentElement.classList.contains('dark') ? '#334155' : '#e2e8f0',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: true,
                    usePointStyle: true,
                }
            },
            scales: '{{ $type }}' === 'pie' || '{{ $type }}' === 'doughnut' ? {} : {
                x: {
                    grid: { display: false },
                    ticks: { color: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#64748b' }
                },
                y: {
                    grid: { color: document.documentElement.classList.contains('dark') ? '#334155' : '#f1f5f9' },
                    ticks: { color: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#64748b' }
                }
            }
        };

        this.chart = new Chart(ctx, {
            type: '{{ $type }}',
            data: {
                labels: @js($labels),
                datasets: @js($datasets)
            },
            options: { ...defaultOptions, ...@js($options) }
        });

        // Gestion du wire:navigate (destruction du chart pour éviter les fuites mémoire)
        document.addEventListener('livewire:navigating', () => {
            if (this.chart) this.chart.destroy();
        }, { once: true });
    }
}" class="relative w-full" style="height: {{ $height }};">
    <canvas id="{{ $id }}"></canvas>
</div>
