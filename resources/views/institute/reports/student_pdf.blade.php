<!DOCTYPE html>
<html>
<head>
    <title>Student Report - {{ $student['name'] }} ({{ $student['enrollment_id'] ?? '#ST-' . $student['id'] }})</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #334155;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            font-size: 10px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 12px;
        }
        .header-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }
        .logo-container {
            text-align: left;
        }
        .logo-img {
            height: 45px;
            max-width: 170px;
            object-fit: contain;
        }
        .logo-placeholder {
            font-size: 20px;
            font-weight: 800;
            color: #ff6600;
            letter-spacing: -0.5px;
        }
        .report-title {
            font-size: 11px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            margin-top: 3px;
        }
        .contact-info {
            text-align: right;
            font-size: 10px;
            color: #64748b;
            line-height: 1.5;
        }
        .contact-info strong {
            color: #334155;
        }
        
        .profile-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
        }
        .profile-table td {
            padding: 7px 10px;
            font-size: 10px;
            border: none;
            vertical-align: top;
        }
        .meta-label {
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            font-size: 8.5px;
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 2px;
        }
        .meta-value {
            font-size: 10.5px;
            font-weight: 600;
            color: #1e293b;
        }

        .metrics-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .metrics-table td {
            padding: 8px 10px;
            background-color: #fff;
            border: 1px solid #e2e8f0;
            text-align: center;
            width: 25%;
        }
        .metric-title {
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            color: #94a3b8;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }
        .metric-value {
            font-size: 13px;
            font-weight: 800;
            color: #0f172a;
        }
        .metric-sub {
            font-size: 8.5px;
            color: #64748b;
            font-weight: 500;
            margin-top: 2px;
        }

        .section-header {
            font-size: 11px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 15px;
            margin-bottom: 6px;
            border-bottom: 1.5px solid #ff6600;
            padding-bottom: 3px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.data-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 700;
            font-size: 8.5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: left;
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
        }
        table.data-table td {
            padding: 6px 8px;
            font-size: 9.5px;
            border: 1px solid #f1f5f9;
            color: #334155;
        }
        table.data-table tr:nth-child(even) td {
            background-color: #fafafa;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-success { background-color: #dcfce7; color: #15803d; }
        .badge-danger { background-color: #ffe4e6; color: #be123c; }
        .badge-warning { background-color: #fef3c7; color: #b45309; }
        .badge-info { background-color: #e0f2fe; color: #0369a1; }
        .badge-neutral { background-color: #f1f5f9; color: #475569; }

        .footer {
            margin-top: 25px;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            text-align: center;
            font-size: 8.5px;
            color: #94a3b8;
            letter-spacing: 0.5px;
        }
        .footer-line {
            font-weight: 600;
            color: #64748b;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header Block -->
    <table class="header-table">
        <tr>
            <td style="width: 55%;">
                <div class="logo-container">
                    @if(extension_loaded('gd') && !empty($institute->logo) && file_exists(public_path('storage/' . $institute->logo)))
                        <img class="logo-img" src="{{ public_path('storage/' . $institute->logo) }}" alt="Logo">
                    @else
                        <div class="logo-placeholder">{{ $institute->institute_name ?? $institute->name }}</div>
                    @endif
                    <div class="report-title">Student Comprehensive Profile & Academic Report</div>
                </div>
            </td>
            <td style="width: 45%;">
                <div class="contact-info">
                    <strong>{{ $institute->institute_name ?? $institute->name }}</strong><br>
                    Email: {{ $institute->email }}<br>
                    Phone: +91 {{ $institute->phone }}<br>
                    Generated: {{ date('M d, Y h:i A') }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Student Profile Information -->
    <table class="profile-table">
        <tr>
            <td style="width: 25%;">
                <span class="meta-label">Student Name</span>
                <span class="meta-value">{{ $student['name'] }}</span>
            </td>
            <td style="width: 25%;">
                <span class="meta-label">Enrollment ID</span>
                <span class="meta-value">{{ $student['enrollment_id'] ?? ('#ST-' . $student['id']) }}</span>
            </td>
            <td style="width: 25%;">
                <span class="meta-label">Batch</span>
                <span class="meta-value">{{ $student['batch_name'] }}</span>
            </td>
            <td style="width: 25%;">
                <span class="meta-label">Standard / Grade</span>
                <span class="meta-value">{{ $student['standard'] ?: 'N/A' }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="meta-label">Parent / Guardian</span>
                <span class="meta-value">{{ $student['guardian_name'] }}</span>
            </td>
            <td>
                <span class="meta-label">Phone Number</span>
                <span class="meta-value">{{ $student['phone'] ? '+91 ' . $student['phone'] : '—' }}</span>
            </td>
            <td>
                <span class="meta-label">Email Address</span>
                <span class="meta-value">{{ $student['email'] ?: '—' }}</span>
            </td>
            <td>
                <span class="meta-label">Admission Date</span>
                <span class="meta-value">{{ $student['admission_date'] }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="4" style="border-top: 1px solid #e2e8f0; padding-top: 6px;">
                <span class="meta-label">Residential Address</span>
                <span class="meta-value" style="font-weight: 500;">{{ $student['address'] }}</span>
            </td>
        </tr>
    </table>

    <!-- Key Metrics Snapshot -->
    <table class="metrics-table">
        <tr>
            <td>
                <div class="metric-title">Fee Balance</div>
                <div class="metric-value" style="color: {{ $financial['balance'] > 0 ? '#e11d48' : '#16a34a' }};">
                    ₹{{ number_format($financial['balance']) }}
                </div>
                <div class="metric-sub">
                    Status: <strong>{{ $financial['fee_status'] }}</strong>
                </div>
            </td>
            <td>
                <div class="metric-title">Attendance Rate</div>
                <div class="metric-value" style="color: #2563eb;">
                    {{ $attendance['percentage'] }}%
                </div>
                <div class="metric-sub">
                    {{ $attendance['present_days'] }} Present / {{ $attendance['total_days'] }} Days
                </div>
            </td>
            <td>
                <div class="metric-title">Exams Performance</div>
                <div class="metric-value" style="color: #7c3aed;">
                    {{ $exams['average_score'] }}
                </div>
                <div class="metric-sub">
                    {{ $exams['passed_exams'] }} Passed / {{ $exams['total_exams'] }} Total
                </div>
            </td>
            <td>
                <div class="metric-title">Homework Submissions</div>
                <div class="metric-value" style="color: #ea580c;">
                    {{ $homework['average_grade'] }}/10
                </div>
                <div class="metric-sub">
                    {{ $homework['total_submissions'] }} Submissions
                </div>
            </td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- Section 1: Examinations & Test Performance -->
    <div class="section-header">1. Examinations & Test Performance ({{ $exams['total_exams'] }} Exams)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25%;">Exam Title</th>
                <th style="width: 15%;">Subject</th>
                <th style="width: 15%;">Date</th>
                <th style="width: 15%;">Score</th>
                <th style="width: 12%;">Passing</th>
                <th style="width: 10%;">Result</th>
                <th style="width: 8%;">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse($exams['list'] as $exm)
                <tr>
                    <td><strong>{{ $exm['title'] }}</strong></td>
                    <td>{{ $exm['subject'] }}</td>
                    <td>{{ $exm['date'] }}</td>
                    <td>
                        @if($exm['is_absent'])
                            <span class="badge badge-warning">Absent</span>
                        @else
                            <strong>{{ $exm['marks_scored'] }}</strong> / {{ $exm['total_marks'] }} ({{ $exm['percentage'] }}%)
                        @endif
                    </td>
                    <td>{{ $exm['passing_marks'] }}</td>
                    <td>
                        @if($exm['is_absent'])
                            <span class="badge badge-warning">Absent</span>
                        @elseif($exm['is_passed'])
                            <span class="badge badge-success">Passed</span>
                        @else
                            <span class="badge badge-danger">Failed</span>
                        @endif
                    </td>
                    <td>{{ $exm['remarks'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #94a3b8; padding: 12px;">No exam records found for this student.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- Section 2: Homework & Assignment Submissions -->
    <div class="section-header">2. Homework & Assignment Records ({{ $homework['total_submissions'] }} Submissions)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30%;">Assignment Title</th>
                <th style="width: 18%;">Subject</th>
                <th style="width: 15%;">Due Date</th>
                <th style="width: 12%;">Status</th>
                <th style="width: 10%;">Grade</th>
                <th style="width: 15%;">Feedback</th>
            </tr>
        </thead>
        <tbody>
            @forelse($homework['list'] as $hw)
                <tr>
                    <td><strong>{{ $hw['title'] }}</strong></td>
                    <td>{{ $hw['subject'] }}</td>
                    <td>{{ $hw['due_date'] }}</td>
                    <td>
                        @if($hw['status'] === 'Reviewed')
                            <span class="badge badge-success">{{ $hw['status'] }}</span>
                        @elseif($hw['status'] === 'Submitted')
                            <span class="badge badge-info">{{ $hw['status'] }}</span>
                        @else
                            <span class="badge badge-neutral">{{ $hw['status'] }}</span>
                        @endif
                    </td>
                    <td>{{ $hw['score'] !== null ? $hw['score'] . '/10' : '—' }}</td>
                    <td>{{ $hw['feedback'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #94a3b8; padding: 12px;">No homework submissions logged for this student.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Section 3: Fee Payment History -->
    <!-- <div class="section-header">3. Fee Payment History</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25%;">Month / Cycle</th>
                <th style="width: 20%;">Total Amount</th>
                <th style="width: 20%;">Paid Amount</th>
                <th style="width: 20%;">Remaining Balance</th>
                <th style="width: 15%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($financial['fees_history'] as $fee)
                <tr>
                    <td><strong>{{ $fee['month_year'] }}</strong></td>
                    <td>₹{{ number_format($fee['total_amount']) }}</td>
                    <td style="color: #16a34a; font-weight: bold;">₹{{ number_format($fee['paid_amount']) }}</td>
                    <td style="color: {{ $fee['remaining'] > 0 ? '#e11d48' : '#64748b' }}; font-weight: bold;">
                        ₹{{ number_format($fee['remaining']) }}
                    </td>
                    <td>
                        @if($fee['status'] === 'Paid' || $fee['remaining'] == 0)
                            <span class="badge badge-success">Paid</span>
                        @elseif($fee['paid_amount'] > 0)
                            <span class="badge badge-warning">Partial</span>
                        @else
                            <span class="badge badge-danger">Unpaid</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #94a3b8; padding: 12px;">No fee cycles recorded for this student.</td>
                </tr>
            @endforelse
        </tbody>
    </table> -->

    <!-- Section 4: Recent Attendance Logs -->
    <div class="section-header">3. Recent Attendance Breakdown (Total Days: {{ $attendance['total_days'] }})</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25%;">Date</th>
                <th style="width: 25%;">Day</th>
                <th style="width: 30%;">Batch</th>
                <th style="width: 20%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse(array_slice($attendance['records'] instanceof \Illuminate\Support\Collection ? $attendance['records']->toArray() : (array)$attendance['records'], 0, 15) as $att)
                <tr>
                    <td><strong>{{ $att['formatted_date'] }}</strong></td>
                    <td>{{ $att['day'] }}</td>
                    <td>{{ $att['batch_name'] }}</td>
                    <td>
                        @if($att['status'] === 'Present')
                            <span class="badge badge-success">Present</span>
                        @elseif($att['status'] === 'Absent')
                            <span class="badge badge-danger">Absent</span>
                        @elseif($att['status'] === 'Late')
                            <span class="badge badge-warning">Late</span>
                        @else
                            <span class="badge badge-neutral">{{ $att['status'] }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #94a3b8; padding: 12px;">No attendance records found for this student.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer Block -->
    <div class="footer">
        <div class="footer-line">&copy; {{ date('Y') }} {{ $institute->institute_name ?? $institute->name }} | All Rights Reserved</div>
        <div style="margin-top: 3px; font-size: 8px; color: #cbd5e1;">Confidential Student Academic Record &bull; Powered by Tuoora Education System</div>
    </div>
</body>
</html>
