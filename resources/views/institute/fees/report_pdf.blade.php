<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fee Collection Report - {{ $month }} {{ $year }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; color: #334155; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; }
        .header h1 { color: #1e3a8a; margin: 0; font-size: 24px; text-transform: uppercase; letter-spacing: 1px; }
        .header p { color: #64748b; margin: 5px 0 0; font-size: 14px; font-weight: bold; }
        
        .summary { margin-bottom: 30px; background: #f8fafc; padding: 15px; border-radius: 10px; }
        .summary table { width: 100%; border: none !important; }
        .summary td { font-size: 12px; font-weight: bold; color: #475569; border: none !important; }
        .summary .value { color: #1e3a8a; font-size: 16px; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #1e3a8a; color: white; text-align: left; padding: 12px 10px; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; }
        td { padding: 10px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
        tr:nth-child(even) { background: #f1f5f9; }
        
        .total-row { background: #1e3a8a !important; color: white; font-weight: bold; }
        .total-row td { border: none !important; font-size: 13px; color: white !important; }

        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #94a3b8; padding: 10px 0; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $institute->name }}</h1>
        <p>Fee Collection Report: {{ $month }} {{ $year }}</p>
    </div>

    <div class="summary">
        <table>
            <tr>
                <td style="width: 50%;">Total Records: <br><span class="value">{{ count($fees) }}</span></td>
                <td style="text-align: right; width: 50%;">Total Collection: <br><span class="value">₹{{ number_format($fees->sum('paid_amount'), 2) }}</span></td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Total Fee</th>
                <th>Paid Amount</th>
                <th>Due Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fees as $fee)
            <tr>
                <td>{{ $fee->created_at->format('d M, Y') }}</td>
                <td>STU-{{ str_pad($fee->student_id, 4, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $fee->student->name ?? 'N/A' }}</td>
                <td>₹{{ number_format($fee->total_amount, 2) }}</td>
                <td style="color: #059669; font-weight: bold;">₹{{ number_format($fee->paid_amount, 2) }}</td>
                <td style="color: #dc2626;">₹{{ number_format($fee->due_amount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" style="text-align: right;">GRAND TOTAL:</td>
                <td>₹{{ number_format($fees->sum('paid_amount'), 2) }}</td>
                <td>₹{{ number_format($fees->sum('due_amount'), 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Generated on {{ now()->format('d M, Y h:i A') }} | Powered by FeeEasy
    </div>
</body>
</html>
