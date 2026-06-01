<!DOCTYPE html>
<html>
<head>
    <title>Fee Collection Report - {{ $month }} {{ $year }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #334155;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 15px;
        }
        .header-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }
        .logo-container {
            text-align: left;
        }
        .logo-img {
            height: 50px;
            max-width: 180px;
            object-fit: contain;
        }
        .logo-placeholder {
            font-size: 22px;
            font-weight: 800;
            color: #ff6600;
            letter-spacing: -0.5px;
        }
        .report-title {
            font-size: 11px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 700;
            margin-top: 4px;
        }
        .contact-info {
            text-align: right;
            font-size: 11px;
            color: #64748b;
            line-height: 1.6;
        }
        .contact-info strong {
            color: #334155;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 8px;
        }
        .meta-table td {
            border: none;
            padding: 10px 15px;
            font-size: 11px;
            color: #64748b;
        }
        .meta-label {
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.data-table th {
            background-color: #ff6600;
            color: white;
            font-weight: 700;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: left;
            padding: 10px 12px;
            border: none;
        }
        table.data-table td {
            padding: 10px 12px;
            font-size: 11px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }
        table.data-table tr:nth-child(even) td {
            background-color: #fafaf9;
        }
        .footer {
            margin-top: 40px;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            letter-spacing: 0.5px;
        }
        .footer-line {
            font-weight: 600;
            color: #64748b;
        }
    </style>
</head>
<body>
    <!-- Header Block -->
    <table class="header-table">
        <tr>
            <td style="width: 55%;">
                <div class="logo-container">
                    @if($institute->logo && file_exists(public_path('storage/' . $institute->logo)))
                        <img class="logo-img" src="{{ public_path('storage/' . $institute->logo) }}" alt="Logo">
                    @else
                        <div class="logo-placeholder">{{ $institute->institute_name }}</div>
                    @endif
                    <div class="report-title">Fee Collection Report</div>
                </div>
            </td>
            <td style="width: 45%;">
                <div class="contact-info">
                    <strong>{{ $institute->institute_name }}</strong><br>
                    Email: {{ $institute->email }}<br>
                    Phone: +91 {{ $institute->phone }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Meta Details Block -->
    <table class="meta-table">
        <tr>
            <td>
                <span class="meta-label">Report Period:</span> {{ $month }} {{ $year }}
            </td>
            <td>
                <span class="meta-label">Total Records:</span> {{ count($fees) }}
            </td>
            <td style="text-align: right;">
                <span class="meta-label">Total Collection:</span> ₹{{ number_format($fees->sum('paid_amount'), 2) }}
            </td>
        </tr>
    </table>

    <!-- Fees Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 6%; text-align: center;">Sr.</th>
                <th style="width: 20%;">Date</th>
                <th style="width: 20%;">Student ID</th>
                <th style="width: 24%;">Student Name</th>
                <th style="width: 15%;">Total Fee</th>
                <th style="width: 15%;">Paid Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fees as $index => $fee)
                <tr>
                    <td style="text-align: center; color: #64748b;">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($fee->date)->format('d M, Y') }}</td>
                    <td>STU-{{ str_pad($fee->student_id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td><strong style="color: #0f172a;">{{ $fee->student->name ?? 'N/A' }}</strong></td>
                    <td>₹{{ number_format($fee->total_amount, 2) }}</td>
                    <td style="color: #16a34a; font-weight: bold;">₹{{ number_format($fee->paid_amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #ff6600 !important; color: white !important;">
                <td colspan="5" style="text-align: right; font-weight: 700; font-size: 11px; color: white !important; border: none !important; padding: 10px 12px;">GRAND TOTAL:</td>
                <td style="font-weight: 700; font-size: 11px; color: white !important; border: none !important; padding: 10px 12px;">₹{{ number_format($fees->sum('paid_amount'), 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Footer Block -->
    <div class="footer">
        <div class="footer-line">&copy; {{ date('Y') }} {{ $institute->institute_name }} | All Rights Reserved</div>
        <div style="margin-top: 4px; font-size: 9px; color: #cbd5e1;">Powered by Tuoora Education System</div>
    </div>
</body>
</html>
