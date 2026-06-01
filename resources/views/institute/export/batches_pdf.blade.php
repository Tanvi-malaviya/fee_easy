<!DOCTYPE html>
<html>
<head>
    <title>Batch Report - {{ $institute->institute_name }}</title>
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
        .status {
            font-weight: 700;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 2px 6px;
            border-radius: 4px;
            display: inline-block;
        }
        .active {
            color: #16a34a;
        }
        .closed {
            color: #dc2626;
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
                    <div class="report-title">Batch Management Report</div>
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
                <span class="meta-label">Exported On:</span> {{ $date }}
            </td>
            <td style="text-align: right;">
                <span class="meta-label">Total Batches:</span> {{ count($batches) }}
            </td>
        </tr>
    </table>

    <!-- Batches Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">Sr.</th>
                <th style="width: 20%;">Batch Name</th>
                <th style="width: 14%;">Subject</th>
                <th style="width: 12%;">Fees</th>
                <th style="width: 10%; text-align: center;">Students</th>
                <th style="width: 15%;">Staff</th>
                <th style="width: 16%;">Schedule</th>
                <th style="width: 8%; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($batches as $index => $batch)
                <tr>
                    <td style="text-align: center; color: #64748b;">{{ $index + 1 }}</td>
                    <td><strong style="color: #0f172a;">{{ $batch->name }}</strong></td>
                    <td>{{ $batch->subject }}</td>
                    <td>₹{{ number_format($batch->fees, 2) }}</td>
                    <td style="text-align: center;">{{ $batch->students_count }}</td>
                    <td>{{ $batch->staff ? $batch->staff->full_name : 'N/A' }}</td>
                    <td style="font-size: 10px;">{{ $batch->start_time }} - {{ $batch->end_time }}</td>
                    <td style="text-align: center;">
                        <span class="status {{ $batch->status === 'closed' ? 'closed' : 'active' }}">
                            {{ $batch->status === 'closed' ? 'Closed' : 'Active' }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer Block -->
    <div class="footer">
        <div class="footer-line">&copy; {{ date('Y') }} {{ $institute->institute_name }} | All Rights Reserved</div>
        <div style="margin-top: 4px; font-size: 9px; color: #cbd5e1;">Powered by Tuoora Education System</div>
    </div>
</body>
</html>
