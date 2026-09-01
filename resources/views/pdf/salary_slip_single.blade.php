<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Salary Slip - {{ $staff->full_name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1e293b;
            margin: 0;
            padding: 20px;
            background: #ffffff;
            line-height: 1.4;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }
        .inst-name {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
        }
        .inst-sub {
            font-size: 10px;
            color: #64748b;
            margin-top: 3px;
        }
        .slip-title {
            text-align: right;
        }
        .slip-title h2 {
            margin: 0;
            font-size: 16px;
            color: #ea580c;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .slip-title p {
            margin: 3px 0 0;
            font-size: 10px;
            color: #64748b;
            font-weight: bold;
        }
        .info-table {
            width: 100%;
            margin-bottom: 18px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 5px 8px;
            font-size: 10px;
            vertical-align: top;
        }
        .info-label {
            color: #64748b;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.5px;
            width: 25%;
        }
        .info-val {
            color: #0f172a;
            font-weight: 600;
            width: 25%;
        }
        .salary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .salary-table th {
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
            padding: 8px 10px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
        }
        .salary-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 11px;
        }
        .salary-table .text-right {
            text-align: right;
        }
        .net-salary-row {
            background-color: #fff7ed;
            border-top: 2px solid #fdba74;
            border-bottom: 2px solid #fdba74;
        }
        .net-salary-row td {
            padding: 10px;
            font-size: 13px;
            font-weight: bold;
            color: #9a3412;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-paid {
            background-color: #dcfce7;
            color: #166534;
        }
        .badge-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .footer-note {
            margin-top: 25px;
            padding-top: 12px;
            border-top: 1px dashed #cbd5e1;
            font-size: 9px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="vertical-align: middle;">
                <h1 class="inst-name">{{ $institute->institute_name ?? 'FeeEasy Institute' }}</h1>
                <p class="inst-sub">{{ $institute->address ?? '' }} {{ $institute->phone ? ' | Phone: ' . $institute->phone : '' }}</p>
            </td>
            <td class="slip-title" style="vertical-align: middle;">
                <h2>Payslip</h2>
                <p>{{ \Carbon\Carbon::createFromDate($salary->year, $salary->month, 1)->format('F Y') }}</p>
            </td>
        </tr>
    </table>

    <!-- Staff & Payment Info -->
    <table class="info-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="info-label">Employee Name</td>
            <td class="info-val">{{ $staff->full_name }}</td>
            <td class="info-label">Payslip ID</td>
            <td class="info-val">#PAY-{{ str_pad($salary->id, 5, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <td class="info-label">Employee ID</td>
            <td class="info-val">{{ !empty($staff->employee_id) ? $staff->employee_id : $staff->formatted_employee_id }}</td>
            <td class="info-label">Payment Date</td>
            <td class="info-val">{{ $salary->payment_date ? \Carbon\Carbon::parse($salary->payment_date)->format('d M, Y') : 'N/A' }}</td>
        </tr>
        <tr>
            <td class="info-label">Role / Department</td>
            <td class="info-val">
                {{ $staff->role->name ?? 'Staff' }} 
                @if($staff->department)
                    ({{ $staff->department->name }})
                @endif
            </td>
            <td class="info-label">Payment Method</td>
            <td class="info-val">{{ $salary->payment_method ?? 'Cash' }}</td>
        </tr>
        <tr>
            <td class="info-label">Email</td>
            <td class="info-val">{{ $staff->email }}</td>
            <td class="info-label">Status</td>
            <td class="info-val">
                <span class="badge {{ strtolower($salary->status) === 'paid' ? 'badge-paid' : 'badge-pending' }}">
                    {{ $salary->status }}
                </span>
            </td>
        </tr>
    </table>

    <!-- Salary Breakdown Table -->
    <table class="salary-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 70%;">Salary Components</th>
                <th class="text-right" style="width: 30%;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Base Salary</strong></td>
                <td class="text-right">₹ {{ number_format($salary->base_salary, 2) }}</td>
            </tr>
            @if(($salary->bonus ?? 0) > 0)
            <tr>
                <td style="color: #16a34a;"><strong>+ Bonus / Allowances</strong></td>
                <td class="text-right" style="color: #16a34a;">₹ {{ number_format($salary->bonus, 2) }}</td>
            </tr>
            @endif
            @if(($salary->deductions ?? 0) > 0)
            <tr>
                <td style="color: #dc2626;"><strong>- Deductions</strong></td>
                <td class="text-right" style="color: #dc2626;">₹ {{ number_format($salary->deductions, 2) }}</td>
            </tr>
            @endif
            <tr class="net-salary-row">
                <td><strong>NET PAYABLE SALARY</strong></td>
                <td class="text-right">₹ {{ number_format($salary->net_salary, 2) }}</td>
            </tr>
        </tbody>
    </table>

    @if(!empty($salary->notes))
    <div style="margin-bottom: 18px; background: #f8fafc; padding: 8px 10px; border-radius: 4px; border: 1px solid #e2e8f0; font-size: 10px;">
        <strong>Notes:</strong> {{ $salary->notes }}
    </div>
    @endif

    <!-- Footer Note -->
    <div class="footer-note">
        This is a computer-generated salary slip and does not require a physical signature.<br>
        Powered by <strong>Tuoora</strong>
    </div>
</body>
</html>
