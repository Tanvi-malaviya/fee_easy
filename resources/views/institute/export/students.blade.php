<!DOCTYPE html>
<html>
<head>
    <title>Student Registry - {{ $institute->institute_name }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #334155;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
        }
        .institute-name {
            font-size: 24px;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 16px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .meta {
            margin-bottom: 20px;
            font-size: 12px;
            color: #94a3b8;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            text-align: left;
            padding: 10px 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        td {
            padding: 10px 8px;
            font-size: 12px;
            border-bottom: 1px solid #f1f5f9;
        }
        .status {
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        .active { color: #10b981; }
        .inactive { color: #ef4444; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #cbd5e1;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="institute-name">{{ $institute->institute_name }}</div>
        <div class="report-title">Student Registry Report</div>
    </div>

    <div class="meta">
        <table style="border: none;">
            <tr style="border: none;">
                <td style="border: none; padding: 0;"><strong>Date:</strong> {{ $date }}</td>
                <td style="border: none; padding: 0; text-align: right;">
                    @if($batch)
                        <strong>Batch:</strong> {{ $batch->name }}
                    @else
                        <strong>Cohort:</strong> All Students
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>Sr.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Batch</th>
                <th>Std.</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $student)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $student->name }}</strong></td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->phone ?? 'N/A' }}</td>
                    <td>{{ $student->batch ? $student->batch->name : 'N/A' }}</td>
                    <td>{{ $student->standard ?? '--' }}</td>
                    <td class="status {{ $student->status == 1 ? 'active' : 'inactive' }}">
                        {{ $student->status == 1 ? 'Active' : 'Inactive' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated via FeeEasy Management System &copy; {{ date('Y') }}
    </div>
</body>
</html>
