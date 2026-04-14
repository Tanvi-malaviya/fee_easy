<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fee Receipt</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; }
        .receipt-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #2c3e50; }
        .details { margin-bottom: 30px; }
        .details table { width: 100%; }
        .details td { padding: 5px 0; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .items-table th { background: #f8f9fa; border: 1px solid #dee2e6; padding: 10px; text-align: left; }
        .items-table td { border: 1px solid #dee2e6; padding: 10px; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #777; }
        .amount-big { font-size: 24px; font-weight: bold; color: #27ae60; }
    </style>
</head>
<body>
    <div class="receipt-box">
        <div class="header">
            <h1>{{ $institute->institute_name }}</h1>
            <p>{{ $institute->address }} | {{ $institute->phone }}</p>
            <hr>
            <h3>FEE RECEIPT</h3>
        </div>

        <div class="details">
            <table>
                <tr>
                    <td><strong>Receipt No:</strong> {{ $receipt->receipt_number }}</td>
                    <td style="text-align: right;"><strong>Date:</strong> {{ date('d-m-Y', strtotime($payment->paid_at)) }}</td>
                </tr>
                <tr>
                    <td><strong>Student Name:</strong> {{ $student->name }}</td>
                    <td style="text-align: right;"><strong>Student ID:</strong> {{ $student->id }}</td>
                </tr>
            </table>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Monthly Fee ({{ $fee->month }} {{ $fee->year }})</td>
                    <td>{{ number_format($payment->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 20px; text-align: right;">
            <p><strong>Total Paid:</strong> <span class="amount-big">₹{{ number_format($payment->amount, 2) }}</span></p>
            <p><strong>Payment Method:</strong> {{ $payment->payment_method }}</p>
            <p><strong>Transaction ID:</strong> {{ $payment->transaction_id ?? 'N/A' }}</p>
        </div>

        <div class="footer">
            <p>Thank you for your payment!</p>
            <p>This is a computer-generated receipt and does not require a signature.</p>
        </div>
    </div>
</body>
</html>
