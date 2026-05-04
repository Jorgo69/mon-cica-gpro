{{-- USAID Performance Report Template --}}
<div class="usaid-report">
    <h1>USAID Performance Report</h1>
    <p>Project: {{ $project->title ?? 'N/A' }}</p>
    <p>Reporting Period: {{ $period ?? 'Q1 FY2026' }}</p>

    <table>
        <thead>
            <tr>
                <th>Performance Indicator</th>
                <th>Baseline</th>
                <th>Target</th>
                <th>Actual</th>
                <th>% Achievement</th>
            </tr>
        </thead>
        <tbody>
            {{-- Populated by the plugin logic --}}
        </tbody>
    </table>
</div>
