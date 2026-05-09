<!DOCTYPE html>
<html>
<head>
    <title>Staff Salary Report</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2 f2 f2; }
        .header { text-align: center; margin-bottom: 20px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $institute->institute_name }}</h2>
        <h3>Staff Salary Report</h3>
        <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Period</th>
                <th>Staff Name</th>
                <th>Base</th>
                <th>Bonus</th>
                <th>Deductions</th>
                <th>Net Salary</th>
                <th>Payment Date</th>
                <th>Method</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salaries as $sal)
            <tr>
                <td>{{ $sal->month }}/{{ $sal->year }}</td>
                <td>{{ $sal->staff->full_name }}</td>
                <td>{{ number_format($sal->base_salary, 2) }}</td>
                <td>{{ number_format($sal->bonus, 2) }}</td>
                <td>{{ number_format($sal->deductions, 2) }}</td>
                <td><strong>{{ number_format($sal->net_salary, 2) }}</strong></td>
                <td>{{ $sal->payment_date ?? '-' }}</td>
                <td>{{ $sal->payment_method ?? '-' }}</td>
                <td>{{ $sal->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Tuoora ERP - Salary Management Module</p>
    </div>
</body>
</html>
