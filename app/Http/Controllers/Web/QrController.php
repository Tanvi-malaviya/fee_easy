<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\QrScan;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class QrController extends Controller
{
    // Valid QR types
    private const VALID_TYPES = ['web', 'android', 'ios'];

    // ─── Public: Track & Redirect ─────────────────────────────────────────────

    /**
     * Record the QR scan analytics then redirect to the destination.
     * If GPS capture is enabled, first serve the bridge page on GET, then accept POST.
     */
    public function track(string $type, Request $request)
    {
        if (!in_array($type, self::VALID_TYPES)) {
            return redirect('/');
        }

        $captureGps = filter_var(config('qr.capture_gps', true), FILTER_VALIDATE_BOOLEAN);

        // If GPS capture is enabled, and this is the initial GET request, and not skipped:
        if ($captureGps && $request->isMethod('get') && !$request->has('skip') && !$request->has('lat')) {
            return view('qr.location_bridge', compact('type'));
        }

        $destination = config("qr.destinations.{$type}", '/');

        // Parse User-Agent
        $userAgent  = $request->header('User-Agent', '');
        $browser    = $this->parseBrowser($userAgent);
        $os         = $this->parseOs($userAgent);
        $deviceType = $this->parseDeviceType($userAgent);

        // Get real IP
        $ip = $this->getRealIp($request);

        // Geo-locate IP
        [$country, $city] = $this->geoLocate($ip);

        // GPS coordinates (can be passed via POST or GET fallback)
        $latitude  = $request->input('lat') ? (float) $request->input('lat') : null;
        $longitude = $request->input('lng') ? (float) $request->input('lng') : null;

        // Validate GPS range if provided
        if ($latitude !== null && ($latitude < -90 || $latitude > 90)) {
            $latitude = null;
        }
        if ($longitude !== null && ($longitude < -180 || $longitude > 180)) {
            $longitude = null;
        }

        // Record the scan
        QrScan::create([
            'qr_type'     => $type,
            'scanned_at'  => now(),
            'ip_address'  => $ip,
            'browser'     => $browser,
            'os'          => $os,
            'device_type' => $deviceType,
            'user_agent'  => substr($userAgent, 0, 1000),
            'referrer'    => $request->header('Referer') ?: null,
            'country'     => $country,
            'city'        => $city,
            'latitude'    => $latitude,
            'longitude'   => $longitude,
        ]);

        return redirect()->away($destination);
    }

    // ─── Admin: Analytics Dashboard ───────────────────────────────────────────

    /**
     * Admin analytics overview page.
     */
    public function adminIndex(Request $request): View
    {
        // ── Summary Counts ──────────────────────────────────────────
        $totalScans   = QrScan::count();
        $webScans     = QrScan::byType('web')->count();
        $androidScans = QrScan::byType('android')->count();
        $iosScans     = QrScan::byType('ios')->count();
        $todayScans   = QrScan::today()->count();
        $monthScans   = QrScan::thisMonth()->count();

        // ── Scan Trend (last 30 days, grouped by date) ──────────────
        $trendData = QrScan::selectRaw('DATE(scanned_at) as date, qr_type, COUNT(*) as total')
            ->where('scanned_at', '>=', now()->subDays(30))
            ->groupByRaw('DATE(scanned_at), qr_type')
            ->orderBy('date')
            ->get();

        // Build daily series for chart
        $trendDates   = [];
        $trendWeb     = [];
        $trendAndroid = [];
        $trendIos     = [];

        // Generate last-30 days date range
        $dateRange = collect();
        for ($i = 29; $i >= 0; $i--) {
            $dateRange->push(now()->subDays($i)->format('Y-m-d'));
        }

        foreach ($dateRange as $date) {
            $trendDates[]   = now()->parse($date)->format('M d');
            $trendWeb[]     = (int) $trendData->where('date', $date)->where('qr_type', 'web')->sum('total');
            $trendAndroid[] = (int) $trendData->where('date', $date)->where('qr_type', 'android')->sum('total');
            $trendIos[]     = (int) $trendData->where('date', $date)->where('qr_type', 'ios')->sum('total');
        }

        // ── Browser Distribution ─────────────────────────────────────
        $browserStats = QrScan::selectRaw('browser, COUNT(*) as total')
            ->groupBy('browser')
            ->orderByDesc('total')
            ->limit(8)
            ->pluck('total', 'browser')
            ->toArray();

        // ── OS Distribution ──────────────────────────────────────────
        $osStats = QrScan::selectRaw('os, COUNT(*) as total')
            ->groupBy('os')
            ->orderByDesc('total')
            ->limit(8)
            ->pluck('total', 'os')
            ->toArray();

        // ── Device Type Distribution ─────────────────────────────────
        $deviceStats = QrScan::selectRaw('device_type, COUNT(*) as total')
            ->groupBy('device_type')
            ->orderByDesc('total')
            ->pluck('total', 'device_type')
            ->toArray();

        // ── Country Stats ────────────────────────────────────────────
        $countryStats = QrScan::selectRaw('country, city, COUNT(*) as total')
            ->whereNotNull('country')
            ->groupByRaw('country, city')
            ->orderByDesc('total')
            ->limit(15)
            ->get();

        // ── Recent Scans (paginated) ─────────────────────────────────
        $recentScans = QrScan::latest('scanned_at')->paginate(25);

        // ── GPS Scans ────────────────────────────────────────────────
        $gpsScans = QrScan::whereNotNull('latitude')->whereNotNull('longitude')->count();

        return view('qr.analytics', compact(
            'totalScans', 'webScans', 'androidScans', 'iosScans',
            'todayScans', 'monthScans',
            'trendDates', 'trendWeb', 'trendAndroid', 'trendIos',
            'browserStats', 'osStats', 'deviceStats',
            'countryStats', 'recentScans', 'gpsScans'
        ));
    }

    // ─── Admin: CSV Export ────────────────────────────────────────────────────

    /**
     * Export all scan data as a CSV download.
     */
    public function export(Request $request)
    {
        $query = QrScan::query()->latest('scanned_at');

        // Optional filters
        if ($request->filled('type')) {
            $query->byType($request->type);
        }
        if ($request->filled('from')) {
            $query->whereDate('scanned_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('scanned_at', '<=', $request->to);
        }

        $scans = $query->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="qr_scans_' . now()->format('Y-m-d_His') . '.csv"',
        ];

        $callback = function () use ($scans) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header Row
            fputcsv($handle, [
                'ID', 'QR Type', 'Scanned At', 'IP Address',
                'Browser', 'OS', 'Device Type', 'User Agent',
                'Referrer', 'Country', 'City', 'Latitude', 'Longitude',
            ]);

            foreach ($scans as $scan) {
                fputcsv($handle, [
                    $scan->id,
                    $scan->qr_type_label,
                    $scan->scanned_at?->format('Y-m-d H:i:s'),
                    $scan->ip_address,
                    $scan->browser,
                    $scan->os,
                    $scan->device_type,
                    $scan->user_agent,
                    $scan->referrer,
                    $scan->country,
                    $scan->city,
                    $scan->latitude,
                    $scan->longitude,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ─── Private: Parsing Helpers ─────────────────────────────────────────────

    /**
     * Parse browser name from User-Agent string.
     */
    private function parseBrowser(string $ua): string
    {
        if (empty($ua)) return 'Unknown';

        $browsers = [
            'Edg'            => 'Edge',
            'OPR'            => 'Opera',
            'Opera'          => 'Opera',
            'SamsungBrowser' => 'Samsung Browser',
            'UCBrowser'      => 'UC Browser',
            'YaBrowser'      => 'Yandex',
            'Firefox'        => 'Firefox',
            'Chrome'         => 'Chrome',
            'Safari'         => 'Safari',
            'MSIE'           => 'Internet Explorer',
            'Trident'        => 'Internet Explorer',
        ];

        foreach ($browsers as $key => $name) {
            if (str_contains($ua, $key)) {
                return $name;
            }
        }

        return 'Other';
    }

    /**
     * Parse operating system from User-Agent string.
     */
    private function parseOs(string $ua): string
    {
        if (empty($ua)) return 'Unknown';

        $systems = [
            'Windows NT 10'  => 'Windows 10/11',
            'Windows NT 6.3' => 'Windows 8.1',
            'Windows NT 6.2' => 'Windows 8',
            'Windows NT 6.1' => 'Windows 7',
            'Windows'        => 'Windows',
            'Android'        => 'Android',
            'iPhone'         => 'iOS',
            'iPad'           => 'iPadOS',
            'Mac OS X'       => 'macOS',
            'Linux'          => 'Linux',
        ];

        foreach ($systems as $key => $name) {
            if (str_contains($ua, $key)) {
                // Append Android version if available
                if ($key === 'Android' && preg_match('/Android (\d+(\.\d+)?)/', $ua, $m)) {
                    return 'Android ' . $m[1];
                }
                // Append iOS version if available
                if (($key === 'iPhone' || $key === 'iPad') && preg_match('/OS (\d+_\d+)/', $ua, $m)) {
                    return ($key === 'iPhone' ? 'iOS' : 'iPadOS') . ' ' . str_replace('_', '.', $m[1]);
                }
                return $name;
            }
        }

        return 'Other';
    }

    /**
     * Determine device type from User-Agent string.
     */
    private function parseDeviceType(string $ua): string
    {
        if (empty($ua)) return 'desktop';

        $ua = strtolower($ua);

        // Tablets before mobile (iPad, Android tablets)
        if (
            str_contains($ua, 'ipad') ||
            (str_contains($ua, 'android') && !str_contains($ua, 'mobile')) ||
            str_contains($ua, 'tablet')
        ) {
            return 'tablet';
        }

        // Mobile
        if (
            str_contains($ua, 'mobile') ||
            str_contains($ua, 'iphone') ||
            str_contains($ua, 'ipod') ||
            str_contains($ua, 'blackberry') ||
            str_contains($ua, 'windows phone')
        ) {
            return 'mobile';
        }

        return 'desktop';
    }

    /**
     * Get the real client IP address, handling proxies.
     */
    private function getRealIp(Request $request): string
    {
        $ip = $request->header('X-Forwarded-For')
            ?? $request->header('X-Real-IP')
            ?? $request->ip();

        // X-Forwarded-For may be a comma-separated list; take the first
        if (str_contains((string) $ip, ',')) {
            $ip = trim(explode(',', $ip)[0]);
        }

        return (string) $ip;
    }

    /**
     * Geo-locate an IP address using ip-api.com.
     * Returns [country, city] or [null, null] on failure.
     */
    private function geoLocate(string $ip): array
    {
        // Skip private/local IPs
        if ($this->isPrivateIp($ip)) {
            return [null, null];
        }

        try {
            $url      = str_replace('{ip}', $ip, config('qr.geo_api_url'));
            $timeout  = (int) config('qr.geo_timeout', 2);

            $response = Http::timeout($timeout)->get($url);

            if ($response->successful()) {
                $data = $response->json();
                if (($data['status'] ?? '') === 'success') {
                    return [
                        $data['country'] ?? null,
                        $data['city']    ?? null,
                    ];
                }
            }
        } catch (\Throwable $e) {
            // Silently fail — geo is best-effort
        }

        return [null, null];
    }

    /**
     * Check if an IP is a private/reserved address (skip geo for these).
     */
    private function isPrivateIp(string $ip): bool
    {
        return in_array($ip, ['127.0.0.1', '::1', 'localhost'], true)
            || filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
    }
}
