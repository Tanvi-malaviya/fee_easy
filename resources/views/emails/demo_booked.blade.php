<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #1e293b;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .header {
            background-color: #0f172a;
            padding: 40px;
            text-align: center;
        }
        .content {
            padding: 40px;
        }
        .footer {
            background-color: #f1f5f9;
            padding: 24px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.025em;
        }
        .highlight {
            color: #6366f1;
            font-weight: 700;
        }
        .details-box {
            background-color: #f8fafc;
            border-radius: 16px;
            padding: 24px;
            margin: 24px 0;
            border: 1px solid #e2e8f0;
        }
        .detail-item {
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
        }
        .detail-label {
            font-weight: 600;
            color: #64748b;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .detail-value {
            font-weight: 700;
            color: #0f172a;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background-color: #6366f1;
            color: #ffffff !important;
            padding: 14px 32px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-top: 24px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>TUOORA <span style="color: #6366f1;">ERP</span></h1>
            <p style="color: #94a3b8; font-size: 14px; margin-top: 8px; font-weight: 500;">Institutional Walkthrough Confirmed</p>
        </div>
        <div class="content">
            <p>Hello <span class="highlight">{{ $details['full_name'] }}</span>,</p>
            <p>Thank you for requesting a personalized walkthrough of Tuoora ERP. We are excited to show you how our ecosystem can transform <strong>{{ $details['institute_name'] }}</strong>.</p>
            
            <div class="details-box">
                <div class="detail-item">
                    <span class="detail-label">Institute</span>
                    <span class="detail-value">{{ $details['institute_name'] }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Designation</span>
                    <span class="detail-value">{{ $details['designation'] }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Contact</span>
                    <span class="detail-value">{{ $details['phone'] }}</span>
                </div>
            </div>

            <p>Our team will contact you within the next 2 hours to schedule a convenient time for the session.</p>
            
            <div style="text-align: center;">
                <a href="https://tuoora.com" class="button">Visit Our Website</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Tuoora Technologies. All rights reserved.
        </div>
    </div>
</body>
</html>
