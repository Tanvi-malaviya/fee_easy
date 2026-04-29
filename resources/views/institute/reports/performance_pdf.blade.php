<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Performance Report - {{ $batch->name ?? 'All Batches' }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; color: #334155; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; }
        .header h1 { color: #1e3a8a; margin: 0; font-size: 24px; text-transform: uppercase; }
        .header p { color: #64748b; margin: 5px 0 0; font-size: 14px; font-weight: bold; }
        
        .summary { margin-bottom: 30px; background: #f8fafc; padding: 15px; border-radius: 10px; border: 1px solid #e2e8f0; }
        .summary table { width: 100%; border: none !important; }
        .summary td { font-size: 12px; font-weight: bold; color: #475569; border: none !important; }
        .summary .value { color: #1e3a8a; font-size: 16px; }

        .details-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .details-table th { background: #1e3a8a; color: white; text-align: left; padding: 12px 10px; font-size: 12px; text-transform: uppercase; }
        .details-table td { padding: 12px 10px; border-bottom: 1px solid #e2e8f0; font-size: 13px; font-weight: bold; color: #334155; }
        .details-table tr:nth-child(even) { background: #f8fafc; }

        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #94a3b8; padding: 10px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $institute->name }}</h1>
        <p>Performance Report: {{ $batch->name ?? 'All Batches' }}</p>
    </div>

    @php
        $globalPerformance = collect($batchesData)->avg(function($b) {
            return (float) str_replace('%', '', $b->avg_score);
        });
    @endphp

    <div class="summary">
        <table>
            <tr>
                <td style="width: 100%; text-align: center;">Average Performance: <br><span class="value">{{ round($globalPerformance, 2) }}%</span></td>
            </tr>
        </table>
    </div>

    <table class="details-table">
        <thead>
            <tr>
                <th>Batch Name</th>
                <th>Average Score</th>
                <th>Students Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($batchesData as $b)
            <tr>
                <td style="color: #1e3a8a;">{{ $b->name }}</td>
                <td style="color: #059669; font-weight: bold;">{{ $b->avg_score }}</td>
                <td>{{ $b->students_count }} Students</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ now()->format('d M, Y h:i A') }} | Powered by FeeEasy
    </div>
</body>
</html>
