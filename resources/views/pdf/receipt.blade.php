<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fee Receipt - {{ $receipt->receipt_number }}</title>
    <style>
        @page {
            margin: 0;
            size: a4;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            -webkit-print-color-adjust: exact;
        }
        .page-container {
            padding: 40px 45px;
            position: relative;
        }
        /* Top orange accent strip */
        .top-accent-bar {
            height: 6px;
            background-color: #f97316;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }
        /* Table Layout utilities */
        .w-full {
            width: 100%;
        }
        table {
            border-collapse: collapse;
            border-spacing: 0;
        }
        td {
            padding: 0;
            vertical-align: top;
        }
        /* Header styles */
        .header-table {
            margin-top: 15px;
            margin-bottom: 25px;
        }
        .logo-box {
            width: 54px;
            height: 54px;
            background-color: #0f172a;
            border-radius: 12px;
            text-align: center;
            line-height: 54px;
            color: #ffffff;
            font-size: 26px;
            font-weight: bold;
        }
        .brand-name {
            font-size: 20px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 4px 0;
            line-height: 1.2;
        }
        .brand-info {
            font-size: 11px;
            color: #64748b;
            margin: 0 0 3px 0;
            line-height: 1.4;
        }
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 4px 0;
            letter-spacing: -0.5px;
        }
        .invoice-no {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
        }
        .invoice-no span {
            color: #0f172a;
        }
        .divider {
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 25px;
        }
        /* Metadata block */
        .meta-table {
            margin-bottom: 30px;
        }
        .meta-title {
            font-size: 10px;
            font-weight: bold;
            color: #94a3b8;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .meta-value-bold {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 4px 0;
        }
        .meta-value-text {
            font-size: 12px;
            color: #475569;
            margin: 0 0 3px 0;
            line-height: 1.4;
        }
        .meta-label-value-table td {
            font-size: 12px;
            padding: 2px 0;
            color: #475569;
        }
        /* Badges */
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 99px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-paid {
            background-color: #ecfdf5;
            color: #16a34a;
        }
        .badge-partial {
            background-color: #fffbeb;
            color: #d97706;
        }
        .badge-unpaid {
            background-color: #fef2f2;
            color: #dc2626;
        }
        /* Items Table */
        .items-table {
            margin-bottom: 20px;
        }
        .items-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 10px 12px;
            text-align: left;
        }
        .items-table th.text-right {
            text-align: right;
        }
        .items-table td {
            padding: 14px 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 12px;
            color: #334155;
        }
        .items-table td.center {
            text-align: center;
        }
        .items-table td.right {
            text-align: right;
        }
        .item-title {
            font-weight: bold;
            color: #0f172a;
            font-size: 13px;
            margin-bottom: 2px;
        }
        .item-subtitle {
            font-size: 11px;
            color: #64748b;
        }
        /* Totals Block */
        .totals-table {
            margin-bottom: 30px;
        }
        .totals-table td {
            font-size: 12px;
            padding: 6px 12px;
            color: #475569;
        }
        .totals-table tr.total-row td {
            border-top: 2px solid #0f172a;
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            padding-top: 10px;
        }
        .totals-table tr.total-row td.total-val {
            color: #f97316;
            font-size: 18px;
        }
        /* Payment info block */
        .payment-info-box {
            background-color: #f8fafc;
            border-left: 3px solid #f97316;
            border-radius: 6px;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        .payment-info-title {
            font-size: 10px;
            font-weight: bold;
            color: #f97316;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .payment-detail-label {
            font-size: 9px;
            color: #94a3b8;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .payment-detail-value {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
        }
        /* Notice / Warning box */
        .notice-box {
            background-color: #fffbeb;
            border-left: 3px solid #f59e0b;
            border-radius: 6px;
            padding: 12px 18px;
            margin-bottom: 30px;
        }
        .notice-text {
            font-size: 11px;
            color: #b45309;
            line-height: 1.5;
            margin: 0;
        }
        /* Footer */
        .footer-table {
            border-top: 1px solid #f1f5f9;
            padding-top: 15px;
        }
        .footer-text {
            font-size: 9px;
            color: #94a3b8;
            line-height: 1.4;
        }
        .footer-logo {
            font-size: 11px;
            color: #94a3b8;
            text-align: right;
            font-weight: 500;
        }
        .footer-logo span {
            color: #f97316;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="top-accent-bar"></div>
    <div class="page-container">
        
        <!-- Header -->
        <table class="w-full header-table">
            <tr>
                <td style="width: 65%;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 65px; padding-right: 15px;">
                                @if($institute->logo && file_exists(public_path('storage/' . $institute->logo)))
                                    <img src="{{ public_path('storage/' . $institute->logo) }}" style="width: 54px; height: 54px; object-fit: contain; border-radius: 12px;">
                                @else
                                    <div class="logo-box">{{ substr($institute->institute_name, 0, 1) }}</div>
                                @endif
                            </td>
                            <td>
                                <h1 class="brand-name">{{ $institute->institute_name }}</h1>
                                <p class="brand-info">{{ $institute->address ?? 'Main Campus, India' }}</p>
                                <p class="brand-info">
                                    {{ $institute->email ?? 'accounts@' . strtolower(str_replace(' ', '', $institute->institute_name)) . '.com' }} · {{ $institute->phone ?? 'Support Contact' }}
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width: 35%; text-align: right;">
                    <h2 class="invoice-title">RECEIPT</h2>
                    <div class="invoice-no">No: <span>{{ $receipt->receipt_number }}</span></div>
                </td>
            </tr>
        </table>

        <div class="divider"></div>

        <!-- Billed To & Details Grid -->
        <table class="w-full meta-table">
            <tr>
                <td style="width: 50%; padding-right: 20px;">
                    <div class="meta-title">Billed To</div>
                    <div class="meta-value-bold">{{ $student->name }}</div>
                    <div class="meta-value-text">Student ID: {{ $student->enrollment_id ?? 'N/A' }}</div>
                    <div class="meta-value-text">
                        Class: {{ $student->standard ?? 'N/A' }}
                        @if($student->batch)
                             — {{ $student->batch->batch_name }}
                        @endif
                    </div>
                    <div class="meta-value-text">{{ $student->email }}</div>
                </td>
                <td style="width: 50%; padding-left: 20px;">
                    <div class="meta-title">Receipt Details</div>
                    <table class="w-full meta-label-value-table">
                        <tr>
                            <td style="width: 40%; font-weight: 500;">Issue Date:</td>
                            <td style="width: 60%; text-align: right;">{{ \Carbon\Carbon::parse($fee->date)->format('d M Y') }}</td>
                        </tr>
                        
                        <tr>
                            <td style="font-weight: 500; padding-top: 5px;">Status:</td>
                            <td style="text-align: right; padding-top: 5px;">
                                @if($fee->status == 'Paid')
                                    <span class="status-badge badge-paid">Paid</span>
                                @elseif($fee->status == 'Partial')
                                    <span class="status-badge badge-partial">Partial</span>
                                @else
                                    <span class="status-badge badge-unpaid">Unpaid</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Items Table -->
        <table class="w-full items-table">
            <thead>
                <tr>
                    <th style="width: 8%; text-align: center;">#</th>
                    <th style="width: 52%;">Description</th>
                    <th style="width: 20%; text-align: center;">Period</th>
                    <th style="width: 20%; text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="center">1</td>
                    <td>
                        <div class="item-title">Tuition Fees</div>
                        <div class="item-subtitle">Academic monthly tuition and facilities fees</div>
                    </td>
                    <td class="center">{{ \Carbon\Carbon::parse($fee->date)->format('M-Y') }}</td>
                    <td class="right">₹ {{ number_format($fee->total_amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Totals Block -->
        <table class="w-full totals-table">
  <tr class="total-row">
                <td></td>
                <td style="text-align: right;">Amount Paid:</td>
                <td class="total-val" style="text-align: right;">₹ {{ number_format($fee->paid_amount, 2) }}</td>
            </tr>
        </table>

       

        <!-- Warning Note Box -->
        <div class="notice-box">
            <p class="notice-text">
                Please complete the payment on or before the due date to avoid late charges. For any queries regarding this receipt, contact {{ $institute->institute_name }} directly.
            </p>
        </div>

        <!-- Footer -->
        <table class="w-full footer-table">
            <tr>
                <td style="width: 70%;" class="footer-text">
                    This is a computer-generated receipt and does not require a physical signature.<br>
                    Thank you for your business.
                </td>
                <td style="width: 30%;" class="footer-logo">
                    Generated by <span>Tuoora</span>
                </td>
            </tr>
        </table>

    </div>
</body>
</html>
