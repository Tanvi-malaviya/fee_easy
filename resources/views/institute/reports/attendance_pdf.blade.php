<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Batch Attendance Report - {{ $batch->name }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; color: #334155; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; }
        .header h1 { color: #1e3a8a; margin: 0; font-size: 24px; text-transform: uppercase; }
        .header p { color: #64748b; margin: 5px 0 0; font-size: 14px; font-weight: bold; }
        
        .summary { margin-bottom: 30px; background: #f8fafc; padding: 15px; border-radius: 10px; }
        .summary table { width: 100%; border: none !important; }
        .summary td { font-size: 12px; font-weight: bold; color: #475569; border: none !important; }
        .summary .value { color: #1e3a8a; font-size: 16px; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #1e3a8a; color: white; text-align: left; padding: 12px 10px; font-size: 10px; text-transform: uppercase; }
        td { padding: 10px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
        tr:nth-child(even) { background: #f1f5f9; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #94a3b8; padding: 10px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $institute->name }}</h1>
        <p>Batch Attendance Report: {{ $batch->name }} ({{ $month_name }} {{ $year }})</p>
    </div>

    <div class="summary">
        <table>
            <tr>
                <td style="width: 25%;">Present: <br><span class="value" style="color: #059669;">{{ $attendance->where('status', 'Present')->count() }}</span></td>
                <td style="width: 25%; text-align: center;">Absent: <br><span class="value" style="color: #dc2626;">{{ $attendance->where('status', 'Absent')->count() }}</span></td>
                <td style="width: 25%; text-align: center;">Leave: <br><span class="value" style="color: #d97706;">{{ $attendance->where('status', 'Leave')->count() }}</span></td>
                <td style="width: 25%; text-align: right;">Total Days: <br><span class="value">{{ $attendance->count() }}</span></td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Status</th>
                <th>Marked By</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendance as $record)
            <tr>
                <td>{{ \Carbon\Carbon::parse($record->date)->format('d M, Y') }}</td>
                <td>STU-{{ str_pad($record->student_id, 4, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $record->student->name ?? 'N/A' }}</td>
                <td style="font-weight: bold; color: {{ $record->status === 'Present' ? '#059669' : ($record->status === 'Absent' ? '#dc2626' : '#d97706') }}">
                    {{ $record->status }}
                </td>
                <td>{{ $record->marked_by ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ now()->format('d M, Y h:i A') }} | Powered by FeeEasy
    </div>
</body>
</html>
