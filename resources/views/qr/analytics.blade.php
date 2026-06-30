<x-admin-layout title="QR Analytics">

    {{-- ── Stats Cards ─────────────────────────────────────────────────────── --}}
    <!-- <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2 mb-4">
        @php
        $cards = [
            ['label'=>'Total Scans',    'value'=>$totalScans,   'color'=>'orange', 'icon'=>'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01'],
            ['label'=>'Website QR',     'value'=>$webScans,     'color'=>'blue',   'icon'=>'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9'],
            ['label'=>'Android QR',     'value'=>$androidScans, 'color'=>'green',  'icon'=>'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z'],
            ['label'=>'iOS QR',         'value'=>$iosScans,     'color'=>'gray',   'icon'=>'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z'],
            ['label'=>"Today's Scans",  'value'=>$todayScans,   'color'=>'purple', 'icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ['label'=>'This Month',     'value'=>$monthScans,   'color'=>'rose',   'icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
        ];
        $colorMap = [
            'orange'=>['bg'=>'bg-orange-50','text'=>'text-orange-600','icon'=>'text-orange-500'],
            'blue'  =>['bg'=>'bg-blue-50',  'text'=>'text-blue-600',  'icon'=>'text-blue-500'],
            'green' =>['bg'=>'bg-green-50', 'text'=>'text-green-600', 'icon'=>'text-green-500'],
            'gray'  =>['bg'=>'bg-gray-50',  'text'=>'text-gray-700',  'icon'=>'text-gray-500'],
            'purple'=>['bg'=>'bg-purple-50','text'=>'text-purple-600','icon'=>'text-purple-500'],
            'rose'  =>['bg'=>'bg-rose-50',  'text'=>'text-rose-600',  'icon'=>'text-rose-500'],
        ];
        @endphp

        @foreach($cards as $card)
        @php $c = $colorMap[$card['color']]; @endphp
        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm flex flex-col gap-2 hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between">
                <p class="text-[9px] font-black uppercase tracking-widest text-gray-400">{{ $card['label'] }}</p>
                <div class="{{ $c['bg'] }} p-1.5 rounded-lg">
                    <svg class="w-3.5 h-3.5 {{ $c['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-black {{ $c['text'] }}">{{ number_format($card['value']) }}</p>
        </div>
        @endforeach
    </div> -->

    {{-- ── Trend Chart + Donut Charts Row ─────────────────────────────────── --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-2 mb-2">

        {{-- Scan Trend (30 days) --}}
        <div class="xl:col-span-2 bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Scan Trend</h3>
                    <p class="text-[10px] text-gray-400 font-medium mt-0.5 uppercase">Last 30 days by QR type</p>
                </div>
                <div class="flex items-center gap-3 text-[10px] font-bold">
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-blue-500 inline-block"></span>Web</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-green-500 inline-block"></span>Android</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-gray-400 inline-block"></span>iOS</span>
                </div>
            </div>
            <div id="trendChart" class="min-h-[260px]"></div>
        </div>

        {{-- Device Distribution --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-1">Device Types</h3>
            <p class="text-[10px] text-gray-400 font-medium uppercase mb-4">Mobile · Tablet · Desktop</p>
            <div id="deviceChart" class="min-h-[260px]"></div>
        </div>
    </div>

    {{-- ── Browser + OS Charts ──────────────────────────────────────────────── --}}
    <!-- <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-1">Browser Distribution</h3>
            <p class="text-[10px] text-gray-400 font-medium uppercase mb-4">Top browsers used by scanners</p>
            <div id="browserChart" class="min-h-[240px]"></div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-1">OS Distribution</h3>
            <p class="text-[10px] text-gray-400 font-medium uppercase mb-4">Operating systems detected</p>
            <div id="osChart" class="min-h-[240px]"></div>
        </div>
    </div> -->

    {{-- ── Country / City Stats ─────────────────────────────────────────────── --}}
    <!-- <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm mb-4">
        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Top Locations</h3>
        @if($countryStats->isEmpty())
        <p class="text-xs text-gray-400 text-center py-6">No location data yet. Geo-lookup requires a public IP.</p>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-[9px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-100">
                        <th class="pb-2 text-left">#</th>
                        <th class="pb-2 text-left">Country</th>
                        <th class="pb-2 text-left">City</th>
                        <th class="pb-2 text-right">Scans</th>
                        <th class="pb-2 text-right">Share</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($countryStats as $i => $row)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-2 text-gray-400 font-bold">{{ $i + 1 }}</td>
                        <td class="py-2 font-semibold text-gray-800">{{ $row->country ?? '—' }}</td>
                        <td class="py-2 text-gray-500">{{ $row->city ?? '—' }}</td>
                        <td class="py-2 text-right font-bold text-gray-900">{{ number_format($row->total) }}</td>
                        <td class="py-2 text-right text-gray-400">
                            {{ $totalScans > 0 ? number_format($row->total / $totalScans * 100, 1) : 0 }}%
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div> -->

    {{-- ── Recent Scans Table ───────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-2">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between flex-wrap gap-3">
            <div>
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Recent Scans</h3>
                <p class="text-[10px] text-gray-400 font-medium mt-0.5 uppercase">Live scan history</p>
            </div>
            <!-- <div class="flex items-center gap-2">
                <span class="text-[10px] font-bold text-gray-400">GPS Scans: <span class="text-purple-600">{{ number_format($gpsScans) }}</span></span>
                <a href="{{ route('qr.export') }}"
                   class="flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white text-[11px] font-bold uppercase tracking-wider rounded-xl hover:bg-emerald-700 transition-colors shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export CSV
                </a>
            </div> -->
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-gray-50/70">
                    <tr class="text-[9px] font-black uppercase tracking-widest text-gray-400">
                        <th class="px-4 py-3 text-left">Type</th>
                        <th class="px-4 py-3 text-left">Time</th>
                        <th class="px-4 py-3 text-left">IP</th>
                        <th class="px-4 py-3 text-left">Browser</th>
                        <th class="px-4 py-3 text-left">OS</th>
                        <th class="px-4 py-3 text-left">Device</th>
                        <th class="px-4 py-3 text-left">Country</th>
                        <th class="px-4 py-3 text-left">City</th>
                        <th class="px-4 py-3 text-left">GPS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentScans as $scan)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-4 py-2.5">
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider {{ $scan->qr_type_badge_color }}">
                                {{ $scan->qr_type_label }}
                            </span>
                        </td>
                        <td class="px-4 py-2.5 text-gray-500 whitespace-nowrap">{{ $scan->scanned_at?->format('d M y, H:i') }}</td>
                        <td class="px-4 py-2.5 text-gray-500 font-mono">{{ $scan->ip_address ?? '—' }}</td>
                        <td class="px-4 py-2.5 text-gray-700 font-semibold">{{ $scan->browser ?? '—' }}</td>
                        <td class="px-4 py-2.5 text-gray-500">{{ $scan->os ?? '—' }}</td>
                        <td class="px-4 py-2.5">
                            <span class="capitalize text-gray-600">{{ $scan->device_type }}</span>
                        </td>
                        <td class="px-4 py-2.5 text-gray-500">{{ $scan->country ?? '—' }}</td>
                        <td class="px-4 py-2.5 text-gray-500">{{ $scan->city ?? '—' }}</td>
                        <td class="px-4 py-2.5">
                            @if($scan->hasGps())
                                <span class="text-purple-600 font-bold text-[9px]">
                                    {{ number_format($scan->latitude, 4) }}, {{ number_format($scan->longitude, 4) }}
                                </span>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-12 text-center text-gray-400 text-xs">
                            No scans recorded yet. Scan a QR code to see data here.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($recentScans->hasPages())
        <div class="px-5 py-3 border-t border-gray-100">
            {{ $recentScans->links() }}
        </div>
        @endif
    </div>

    {{-- ── QR Code URLs Reference Card ─────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm mb-4">
        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Your QR Tracking URLs</h3>
        <!-- <p class="text-xs text-gray-400 mb-4">Point your QR codes to these URLs. Use the <code class="bg-gray-100 px-1 rounded">/location</code> variant to capture GPS coordinates.</p> -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            @foreach(['web'=>['Website','bg-blue-50 text-blue-700','border-blue-100'],'android'=>['Android','bg-green-50 text-green-700','border-green-100'],'ios'=>['iOS','bg-gray-50 text-gray-700','border-gray-200']] as $type => [$label,$badge,$border])
            <div class="border {{ $border }} rounded-xl p-4">
                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase {{ $badge }}">{{ $label }}</span>
                <p class="mt-2 text-[10px] font-bold text-gray-500 uppercase mb-1">Direct</p>
                <code class="text-[10px] text-gray-700 break-all bg-gray-50 px-2 py-1 rounded block">{{ url('/qr/'.$type) }}</code>
                <!-- <p class="mt-2 text-[10px] font-bold text-gray-500 uppercase mb-1">With GPS prompt</p>
                <code class="text-[10px] text-gray-700 break-all bg-gray-50 px-2 py-1 rounded block">{{ url('/qr/'.$type.'/location') }}</code> -->
            </div>
            @endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        // ── Trend Chart ──────────────────────────────────────────────────────
        new ApexCharts(document.querySelector('#trendChart'), {
            series: [
                { name: 'Website',  data: @json($trendWeb) },
                { name: 'Android',  data: @json($trendAndroid) },
                { name: 'iOS',      data: @json($trendIos) },
            ],
            chart: { type: 'area', height: 260, toolbar: { show: false }, zoom: { enabled: false }, fontFamily: 'inherit' },
            colors: ['#3b82f6', '#22c55e', '#9ca3af'],
            stroke: { curve: 'smooth', width: 2.5 },
            fill:   { type: 'gradient', gradient: { opacityFrom: 0.2, opacityTo: 0.02 } },
            dataLabels: { enabled: false },
            xaxis: {
                categories: @json($trendDates),
                axisBorder: { show: false }, axisTicks: { show: false },
                labels: { style: { colors: '#9ca3af', fontSize: '9px', fontWeight: 600 }, rotate: -45, rotateAlways: true }
            },
            yaxis: { labels: { style: { colors: '#9ca3af', fontSize: '10px' } }, min: 0 },
            tooltip: { theme: 'dark', shared: true, intersect: false },
            legend: { show: false },
            grid: { borderColor: '#f3f4f6', strokeDashArray: 4 },
        }).render();

        // ── Device Donut ──────────────────────────────────────────────────────
        const deviceData = @json($deviceStats);
        new ApexCharts(document.querySelector('#deviceChart'), {
            series: Object.values(deviceData).map(Number),
            labels: Object.keys(deviceData).map(l => l.charAt(0).toUpperCase() + l.slice(1)),
            chart: { type: 'donut', height: 260, fontFamily: 'inherit' },
            colors: ['#f97316', '#3b82f6', '#22c55e'],
            dataLabels: { enabled: true, style: { fontSize: '10px', fontWeight: 700 } },
            legend: { position: 'bottom', fontSize: '11px', fontWeight: 600 },
            plotOptions: { pie: { donut: { size: '60%' } } },
            tooltip: { theme: 'dark' },
        }).render();

        // ── Browser Bar ───────────────────────────────────────────────────────
        const browserData = @json($browserStats);
        new ApexCharts(document.querySelector('#browserChart'), {
            series: [{ name: 'Scans', data: Object.values(browserData).map(Number) }],
            chart: { type: 'bar', height: 240, toolbar: { show: false }, fontFamily: 'inherit' },
            colors: ['#f97316'],
            plotOptions: { bar: { horizontal: true, borderRadius: 6, barHeight: '60%' } },
            dataLabels: { enabled: false },
            xaxis: { categories: Object.keys(browserData), labels: { style: { colors: '#9ca3af', fontSize: '10px' } }, axisBorder: { show: false }, axisTicks: { show: false } },
            yaxis: { labels: { style: { colors: '#6b7280', fontSize: '11px', fontWeight: 600 } } },
            tooltip: { theme: 'dark' },
            grid: { borderColor: '#f3f4f6', strokeDashArray: 4, xaxis: { lines: { show: true } }, yaxis: { lines: { show: false } } },
        }).render();

        // ── OS Bar ────────────────────────────────────────────────────────────
        const osData = @json($osStats);
        new ApexCharts(document.querySelector('#osChart'), {
            series: [{ name: 'Scans', data: Object.values(osData).map(Number) }],
            chart: { type: 'bar', height: 240, toolbar: { show: false }, fontFamily: 'inherit' },
            colors: ['#6366f1'],
            plotOptions: { bar: { horizontal: true, borderRadius: 6, barHeight: '60%' } },
            dataLabels: { enabled: false },
            xaxis: { categories: Object.keys(osData), labels: { style: { colors: '#9ca3af', fontSize: '10px' } }, axisBorder: { show: false }, axisTicks: { show: false } },
            yaxis: { labels: { style: { colors: '#6b7280', fontSize: '11px', fontWeight: 600 } } },
            tooltip: { theme: 'dark' },
            grid: { borderColor: '#f3f4f6', strokeDashArray: 4, xaxis: { lines: { show: true } }, yaxis: { lines: { show: false } } },
        }).render();
    });
    </script>

</x-admin-layout>
