<!DOCTYPE html>
<html>
<head>
    <title>Staff Attendance Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2 f2 f2; }
        .header { text-align: center; margin-bottom: 20px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $institute->institute_name }}</h2>
        <h3>Staff Attendance Report</h3>
        <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Employee ID</th>
                <th>Staff Name</th>
                <th>Status</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $att)
            <tr>
                <td>{{ $att->date }}</td>
                <td>{{ $att->staff->employee_id }}</td>
                <td>{{ $att->staff->full_name }}</td>
                <td>{{ $att->status }}</td>
                <td>{{ $att->note ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Tuoora ERP - Staff Management Module</p>
    </div>
</body>
</html>
