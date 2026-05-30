<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Receipt - {{ $receipt->receipt_number }}</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1f7a6e;
            --primary-glow: rgba(31, 122, 110, 0.15);
            --accent: #155e63;
            --bg-dark: #0a0f12;
            --card-bg: rgba(20, 28, 33, 0.7);
            --border-color: rgba(255, 255, 255, 0.08);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --success: #10b981;
            --warning: #f5a623;
            --danger: #ef4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            background-image: 
                radial-gradient(at 0% 0%, rgba(31, 122, 110, 0.12) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(21, 94, 99, 0.1) 0px, transparent 50%);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .container {
            width: 100%;
            max-width: 800px;
            perspective: 1000px;
        }

        .receipt-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 3rem;
            backdrop-filter: blur(20px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
        }

        /* Ambient Glow Bar */
        .receipt-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 2rem;
            margin-bottom: 2rem;
        }

        .brand h1 {
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand p {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-top: 0.3rem;
        }

        .receipt-meta {
            text-align: right;
        }

        .receipt-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 99px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1rem;
        }

        .badge-paid {
            background: rgba(16, 185, 129, 0.15);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .badge-partial {
            background: rgba(245, 166, 35, 0.15);
            color: var(--warning);
            border: 1px solid rgba(245, 166, 35, 0.2);
        }

        .badge-unpaid {
            background: rgba(239, 68, 68, 0.15);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .receipt-no {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .receipt-date {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-top: 0.3rem;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            margin-bottom: 2.5rem;
        }

        .info-block h3 {
            font-size: 0.85rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.75rem;
        }

        .info-block p {
            font-size: 1rem;
            color: var(--text-main);
            font-weight: 500;
            line-height: 1.5;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2.5rem;
        }

        .items-table th {
            text-align: left;
            padding: 1rem;
            border-bottom: 2px solid var(--border-color);
            color: var(--text-muted);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .items-table td {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.95rem;
        }

        .items-table .text-right {
            text-align: right;
        }

        .summary-block {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.75rem;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            width: 300px;
            font-size: 0.95rem;
            color: var(--text-muted);
        }

        .summary-row.total {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text-main);
            margin-top: 0.5rem;
        }

        .summary-row.total span {
            color: var(--primary);
        }

        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-print {
            background: var(--primary);
            color: #fff;
            border: none;
            box-shadow: 0 4px 12px var(--primary-glow);
        }

        .btn-print:hover {
            background: var(--accent);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(31, 122, 110, 0.3);
        }

        .btn-secondary {
            background: transparent;
            color: var(--text-muted);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            color: var(--text-main);
            background: rgba(255, 255, 255, 0.03);
            border-color: rgba(255, 255, 255, 0.15);
        }

        .footer-note {
            text-align: center;
            color: var(--text-muted);
            font-size: 0.8rem;
            margin-top: 3rem;
            border-top: 1px dashed var(--border-color);
            padding-top: 1.5rem;
        }

        /* Print Override styles */
        @media print {
            body {
                background: #fff !important;
                color: #000 !important;
                padding: 0 !important;
            }

            .receipt-card {
                background: #fff !important;
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                backdrop-filter: none !important;
            }

            .receipt-card::before, .actions, .btn {
                display: none !important;
            }

            .brand h1 {
                background: none !important;
                -webkit-text-fill-color: #000 !important;
                color: #000 !important;
            }

            .receipt-badge {
                border: 1px solid #000 !important;
                color: #000 !important;
                background: none !important;
            }

            .summary-row.total span {
                color: #000 !important;
            }

            .items-table th {
                border-bottom: 2px solid #000 !important;
                color: #000 !important;
            }

            .items-table td {
                border-bottom: 1px solid #ddd !important;
                color: #000 !important;
            }

            .info-block h3 {
                color: #555 !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="receipt-card">
            <!-- Header -->
            <div class="header">
                <div class="brand">
                    <h1>{{ $institute->institute_name }}</h1>
                    <p>{{ $institute->address ?? 'Main Campus, India' }}</p>
                    <p>Phone: {{ $institute->phone ?? 'Support Contact' }}</p>
                </div>
                <div class="receipt-meta">
                    @if($fee->status == 'Paid')
                        <div class="receipt-badge badge-paid">Paid</div>
                    @elseif($fee->status == 'Partial')
                        <div class="receipt-badge badge-partial">Partial</div>
                    @else
                        <div class="receipt-badge badge-unpaid">Unpaid</div>
                    @endif
                    <div class="receipt-no">Receipt No: {{ $receipt->receipt_number }}</div>
                    <div class="receipt-date">Date: {{ \Carbon\Carbon::parse($fee->date)->format('d M, Y') }}</div>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="details-grid">
                <div class="info-block">
                    <h3>Billed To</h3>
                    <p><strong>{{ $student->name }}</strong></p>
                    <p>ID: #{{ $student->id }}</p>
                    <p>{{ $student->email }}</p>
                </div>
                <div class="info-block">
                    <h3>Payment Information</h3>
                    <p>Method: <strong>{{ $payment->payment_method }}</strong></p>
                    @if($payment->paid_at)
                        <p>Paid Date: {{ \Carbon\Carbon::parse($payment->paid_at)->format('d M, Y h:i A') }}</p>
                    @else
                        <p>Status: Pending</p>
                    @endif
                    <p>Transaction ID: {{ $payment->transaction_id ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Fee Item List -->
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>Monthly Academic Fees</strong>
                            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">
                                Billing Period: {{ \Carbon\Carbon::parse($fee->date)->format('F Y') }}
                            </div>
                        </td>
                        <td class="text-right">₹{{ number_format($fee->total_amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Summary Block -->
            <div class="summary-block">
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>₹{{ number_format($fee->total_amount, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>Tax & Service Charge</span>
                    <span>₹0.00</span>
                </div>
                <div class="summary-row total">
                    <span>Amount Paid</span>
                    <span>₹{{ number_format($fee->paid_amount, 2) }}</span>
                </div>
            </div>

            <!-- Actions Bar -->
            <div class="actions">
                <a href="#" onclick="window.history.back();" class="btn btn-secondary">← Back</a>
                <button onclick="window.print();" class="btn btn-print">🖨️ Print Receipt</button>
            </div>

            <!-- Footer Note -->
            <div class="footer-note">
                <p>Thank you for your payment to {{ $institute->institute_name }}.</p>
                <p>This is a computer-generated receipt, safe to save or print for your records.</p>
            </div>
        </div>
    </div>
</body>
</html>
