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
            color: #FF6B00;
            font-weight: 700;
        }
        ul.features {
            padding-left: 20px;
            margin: 20px 0;
        }
        ul.features li {
            margin-bottom: 10px;
        }
        .button {
            display: inline-block;
            background-color: #FF6B00;
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
            <img src="{{ url('images/infinity logo transparent.png') }}" alt="Tuoora Logo" style="height: 32px; width: auto; display: inline-block;">
            <p style="color: #94a3b8; font-size: 14px; margin-top: 8px; font-weight: 500;">
                @if($stage == 1)
                    Ready When You Are
                @elseif($stage == 3)
                    Checking In
                @else
                    Popular With Institutes Like Yours
                @endif
            </p>
        </div>
        <div class="content">
            <p>Hello <span class="highlight">{{ $fullName }}</span>,</p>

            @if($stage == 1)
                <p>Thanks again for booking a walkthrough of Tuoora ERP for <strong>{{ $instituteName }}</strong>. Just a quick reminder in case our team hasn't connected with you yet — we'd love to show you around.</p>
                <p>In the meantime, here's what institutes usually set up in their first week on Tuoora:</p>
                <ul class="features">
                    <li>Student & fee management, fully digitized</li>
                    <li>Attendance tracking with parent notifications</li>
                    <li>Homework, exams, and report cards — automated</li>
                </ul>
            @elseif($stage == 3)
                <p>Did you get a chance to try Tuoora yet? We wanted to check in and see if you have any questions about how it could work for <strong>{{ $instituteName }}</strong>.</p>
                <p>Whether it's fee collection, attendance, or parent communication, our team is happy to walk you through any specific workflow.</p>
            @else
                <p>Here's a quick look at what institutes similar to <strong>{{ $instituteName }}</strong> use most on Tuoora once they get started:</p>
                <ul class="features">
                    <li><strong>Automated fee reminders</strong> — fewer manual follow-ups with parents</li>
                    <li><strong>Weekly owner summary</strong> — fees, attendance, and exams in one email</li>
                    <li><strong>Auto report cards</strong> — sent the moment exam results are published</li>
                </ul>
                <p>If you'd like a personalized walkthrough of any of these, just reply to this email.</p>
            @endif

            <div style="text-align: center;">
                <a href="https://tuoora.com" class="button">Explore Tuoora</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Tuoora Technologies. All rights reserved.
        </div>
    </div>
</body>
</html>
