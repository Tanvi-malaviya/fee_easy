<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Student Report - {{ $student['name'] }}</title>
    <style>
        @page {
            margin: 20px 22px 20px 22px;
            size: A4 portrait;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1e293b;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            font-size: 10px;
            background-color: #ffffff;
        }

        /* Top Institute Banner */
        .inst-header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            background-color: #0f172a;
            border-radius: 6px;
            color: #ffffff;
        }
        .inst-header td {
            padding: 10px 14px;
            vertical-align: middle;
            border: none;
        }
        .inst-title {
            font-size: 16px;
            font-weight: bold;
            color: #ffffff;
            letter-spacing: -0.3px;
            margin: 0 0 2px 0;
        }
        .inst-subtitle {
            font-size: 9px;
            color: #94a3b8;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        .inst-contact {
            text-align: right;
            font-size: 9px;
            color: #cbd5e1;
            line-height: 1.4;
        }
        .inst-contact strong {
            color: #ffffff;
        }

        /* Highlighted Section Headings */
        .highlight-heading {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 8px;
        }
        .highlight-heading td {
            padding: 7px 12px;
            background-color: #1e293b;
            color: #ffffff;
            font-size: 10.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border-radius: 5px;
            border-left: 5px solid #ff6600;
        }
        .highlight-heading-sub {
            float: right;
            font-size: 9px;
            font-weight: normal;
            color: #94a3b8;
            text-transform: none;
            padding-top: 1px;
        }

        /* Profile Box */
        .profile-card {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
        }
        .profile-card td {
            padding: 6px 10px;
            font-size: 9.5px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: top;
        }
        .profile-card tr:last-child td {
            border-bottom: none;
        }
        .meta-lbl {
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            font-size: 8px;
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 2px;
        }
        .meta-val {
            font-size: 10.5px;
            font-weight: bold;
            color: #0f172a;
        }

        /* Key Metrics Grid */
        .metrics-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px 0;
            margin: 0 -6px 10px -6px;
        }
        .metric-box {
            background-color: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px 6px;
            text-align: center;
            vertical-align: middle;
            width: 25%;
        }
        .metric-lbl {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.6px;
            margin-bottom: 3px;
        }
        .metric-num {
            font-size: 15px;
            font-weight: bold;
            color: #0f172a;
            line-height: 1.1;
        }
        .metric-tag {
            font-size: 8px;
            color: #475569;
            font-weight: bold;
            margin-top: 3px;
        }

        /* Analytics Card */
        .analytics-card {
            background-color: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 6px;
            padding: 10px 12px;
            margin-bottom: 10px;
        }
        .analytics-header {
            font-size: 10px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            border-bottom: 1.5px solid #e2e8f0;
            padding-bottom: 4px;
        }
        .chart-table {
            width: 100%;
            border-collapse: collapse;
        }
        .chart-table td {
            padding: 5px 4px;
            vertical-align: middle;
            border: none;
        }
        .chart-lbl {
            font-size: 9px;
            font-weight: bold;
            color: #334155;
        }
        .chart-bg {
            background-color: #e2e8f0;
            height: 10px;
            border-radius: 5px;
            overflow: hidden;
            width: 100%;
        }
        .chart-fill {
            height: 10px;
            border-radius: 5px;
        }
        .chart-num {
            font-size: 9.5px;
            font-weight: bold;
            text-align: right;
        }

        /* Overall Evaluation Box */
        .eval-card {
            background-color: #faf5ff;
            border: 1.5px solid #e9d5ff;
            border-left: 5px solid #9333ea;
            border-radius: 6px;
            padding: 9px 12px;
            margin-bottom: 8px;
        }
        .eval-head {
            font-size: 9.5px;
            font-weight: bold;
            color: #6b21a8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }
        .eval-body {
            font-size: 9px;
            color: #4c1d95;
            line-height: 1.45;
        }

        /* Data Tables for Exams, Homework, Attendance */
        table.styled-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
        }
        table.styled-table thead th {
            background-color: #1e293b;
            color: #ffffff;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            text-align: left;
            padding: 7px 8px;
            border: 1px solid #0f172a;
        }
        table.styled-table tbody td {
            padding: 7px 8px;
            font-size: 9px;
            border: 1px solid #e2e8f0;
            color: #1e293b;
            vertical-align: middle;
        }
        table.styled-table tbody tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .badge-success { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .badge-danger { background-color: #ffe4e6; color: #9f1239; border: 1px solid #fecdd3; }
        .badge-warning { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .badge-info { background-color: #e0f2fe; color: #075985; border: 1px solid #bae6fd; }
        .badge-neutral { background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

        .footer {
            margin-top: 14px;
            border-top: 1px solid #cbd5e1;
            padding-top: 6px;
            text-align: center;
            font-size: 8px;
            color: #64748b;
            letter-spacing: 0.4px;
        }
        .footer-strong {
            font-weight: bold;
            color: #1e293b;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    <!-- ================= PAGE 1: STUDENT PROFILE, METRICS & EVALUATION ================= -->

    <!-- Institute Header Banner -->
    <table class="inst-header">
        <tr>
            <td style="width: 60%;">
                <div class="inst-title">{{ $institute->institute_name ?? $institute->name }}</div>
                <div class="inst-subtitle">Student Comprehensive Academic &amp; Performance Report</div>
            </td>
            <td style="width: 40%;">
                <div class="inst-contact">
                    <strong>{{ $institute->institute_name ?? $institute->name }}</strong><br>
                    Email: {{ $institute->email }} | Phone: +91 {{ $institute->phone }}<br>
                    Generated: <strong>{{ date('M d, Y h:i A') }}</strong>
                </div>
            </td>
        </tr>
    </table>

    <!-- Highlighted Section: Student Details -->
    <table class="highlight-heading">
        <tr>
            <td>
                Student Academic &amp; Personal Profile
                <span class="highlight-heading-sub">Official Student Record</span>
            </td>
        </tr>
    </table>

    <!-- Student Profile Card -->
    <table class="profile-card">
        <tr>
            <td style="width: 25%;">
                <span class="meta-lbl">Student Name</span>
                <span class="meta-val">{{ $student['name'] }}</span>
            </td>
            <td style="width: 25%;">
                <span class="meta-lbl">Enrollment ID</span>
                <span class="meta-val">{{ $student['enrollment_id'] ?? ('#ST-' . $student['id']) }}</span>
            </td>
            <td style="width: 25%;">
                <span class="meta-lbl">Batch Name</span>
                <span class="meta-val">{{ $student['batch_name'] }}</span>
            </td>
            <td style="width: 25%;">
                <span class="meta-lbl">Standard / Grade</span>
                <span class="meta-val">{{ $student['standard'] ?: 'N/A' }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="meta-lbl">Parent / Guardian</span>
                <span class="meta-val">{{ $student['guardian_name'] }}</span>
            </td>
            <td>
                <span class="meta-lbl">Phone Number</span>
                <span class="meta-val">{{ $student['phone'] ? '+91 ' . $student['phone'] : 'N/A' }}</span>
            </td>
            <td>
                <span class="meta-lbl">Email Address</span>
                <span class="meta-val">{{ $student['email'] ?: 'N/A' }}</span>
            </td>
            <td>
                <span class="meta-lbl">Date of Admission</span>
                <span class="meta-val">{{ $student['admission_date'] }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="4" style="background-color: #f1f5f9;">
                <span class="meta-lbl">Residential Address</span>
                <span class="meta-val" style="font-weight: normal; color: #334155;">{{ $student['address'] }}</span>
            </td>
        </tr>
    </table>

    <!-- Key Metrics Snapshot -->
    <table class="metrics-grid">
        <tr>
            <td class="metric-box">
                <div class="metric-lbl">Fee Balance</div>
                <div class="metric-num" style="color: {{ $financial['balance'] > 0 ? '#e11d48' : '#16a34a' }};">
                    Rs. {{ number_format($financial['balance']) }}
                </div>
                <div class="metric-tag">Status: <strong>{{ $financial['fee_status'] }}</strong></div>
            </td>
            <td class="metric-box">
                <div class="metric-lbl">Attendance Consistency</div>
                <div class="metric-num" style="color: #2563eb;">
                    {{ $attendance['percentage'] }}%
                </div>
                <div class="metric-tag">{{ $attendance['present_days'] }} Present / {{ $attendance['total_days'] }} Days</div>
            </td>
            <td class="metric-box">
                <div class="metric-lbl">Exams Average</div>
                <div class="metric-num" style="color: #7c3aed;">
                    {{ $exams['average_score'] }}
                </div>
                <div class="metric-tag">{{ $exams['passed_exams'] }} Passed / {{ $exams['total_exams'] }} Total</div>
            </td>
            <td class="metric-box">
                <div class="metric-lbl">Homework Quality</div>
                <div class="metric-num" style="color: #ea580c;">
                    {{ $homework['average_grade'] }}/10
                </div>
                <div class="metric-tag">{{ $homework['total_submissions'] }} Submissions</div>
            </td>
        </tr>
    </table>

    @php
        $attPct = min(100, max(0, (float)$attendance['percentage']));
        $examPct = $exams['total_exams'] > 0 ? min(100, max(0, round(($exams['passed_exams'] / $exams['total_exams']) * 100))) : 0;
        $hwPct = min(100, max(0, ((float)$homework['average_grade'] / 10) * 100));
        
        $totalFee = max(1, (float)($student['monthly_fee'] ?? 0));
        $paidFee = max(0, (float)($financial['total_paid'] ?? 0));
        $feePct = min(100, max(0, round(($paidFee / $totalFee) * 100)));
    @endphp

    <!-- Visual Performance Graph & Progress Bars -->
    <div class="analytics-card">
        <div class="analytics-header">Academic Performance &amp; Analytics Breakdown</div>
        <table class="chart-table">
            <tr>
                <td style="width: 28%;" class="chart-lbl">Attendance Consistency</td>
                <td style="width: 58%;">
                    <div class="chart-bg">
                        <div class="chart-fill" style="width: {{ $attPct }}%; background-color: #2563eb;"></div>
                    </div>
                </td>
                <td style="width: 14%;" class="chart-num" style="color: #2563eb;">{{ $attPct }}%</td>
            </tr>
            <tr>
                <td class="chart-lbl">Exam Pass Rate</td>
                <td>
                    <div class="chart-bg">
                        <div class="chart-fill" style="width: {{ $examPct }}%; background-color: #16a34a;"></div>
                    </div>
                </td>
                <td class="chart-num" style="color: #16a34a;">{{ $examPct }}%</td>
            </tr>
            <tr>
                <td class="chart-lbl">Homework Quality</td>
                <td>
                    <div class="chart-bg">
                        <div class="chart-fill" style="width: {{ $hwPct }}%; background-color: #ea580c;"></div>
                    </div>
                </td>
                <td class="chart-num" style="color: #ea580c;">{{ $homework['average_grade'] }}/10</td>
            </tr>
            <tr>
                <td class="chart-lbl">Fee Clearance</td>
                <td>
                    <div class="chart-bg">
                        <div class="chart-fill" style="width: {{ $feePct }}%; background-color: #0d9488;"></div>
                    </div>
                </td>
                <td class="chart-num" style="color: #0d9488;">{{ $feePct }}%</td>
            </tr>
        </table>
    </div>

    <!-- Overall Performance Evaluation Summary -->
    <div class="eval-card">
        <div class="eval-head">Overall Academic Evaluation</div>
        <div class="eval-body">
            @if($attPct >= 85 && $examPct >= 75)
                <strong>Outstanding Academic Record:</strong> {{ $student['name'] }} maintains strong regular attendance ({{ $attendance['percentage'] }}%) and consistently achieves commendable scores in examinations. Recommended for advanced study modules and continued excellence.
            @elseif($attPct >= 75)
                <strong>Consistent Performance:</strong> {{ $student['name'] }} shows good regular attendance with satisfactory engagement in coursework. Continuous revision for examination test series is recommended.
            @else
                <strong>Needs Attention:</strong> Regular classroom attendance and homework submission consistency should be monitored closely to ensure optimal performance in upcoming tests.
            @endif
        </div>
    </div>

    <!-- Footer for Page 1 -->
    <div class="footer">
        <div class="footer-strong">&copy; {{ date('Y') }} {{ $institute->institute_name ?? $institute->name }} | Page 1 of 4</div>
        <div style="margin-top: 2px; font-size: 7.5px; color: #94a3b8;">Confidential Student Performance Document - Powered by Tuoora Education System</div>
    </div>


    <!-- ================= PAGE 2: EXAMINATIONS & TEST PERFORMANCE ================= -->
    <div class="page-break"></div>

    <table class="inst-header" style="margin-bottom: 10px;">
        <tr>
            <td style="width: 65%;">
                <div class="inst-title" style="font-size: 14px;">{{ $institute->institute_name ?? $institute->name }}</div>
                <div class="inst-subtitle">{{ $student['name'] }} | Enrollment ID: {{ $student['enrollment_id'] ?? ('#ST-' . $student['id']) }}</div>
            </td>
            <td style="width: 35%;">
                <div class="inst-contact">Section 1: Examinations &amp; Tests<br>Generated: <strong>{{ date('M d, Y') }}</strong></div>
            </td>
        </tr>
    </table>

    <table class="highlight-heading">
        <tr>
            <td>
                1. Examinations &amp; Test Performance
                <span class="highlight-heading-sub">Total Exams: {{ $exams['total_exams'] }} | Passed: {{ $exams['passed_exams'] }}</span>
            </td>
        </tr>
    </table>

    <table class="styled-table">
        <thead>
            <tr>
                <th style="width: 26%;">Exam Title</th>
                <th style="width: 15%;">Subject</th>
                <th style="width: 14%;">Date</th>
                <th style="width: 15%;">Marks Scored</th>
                <th style="width: 10%;">Passing</th>
                <th style="width: 10%;">Result</th>
                <th style="width: 10%;">Remarks</th>
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
                            <strong style="color: #0f172a;">{{ $exm['marks_scored'] }}</strong> / {{ $exm['total_marks'] }} ({{ $exm['percentage'] }}%)
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
                    <td colspan="7" style="text-align: center; color: #94a3b8; padding: 18px;">No exam records found for this student.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="footer-strong">&copy; {{ date('Y') }} {{ $institute->institute_name ?? $institute->name }} | Page 2 of 4</div>
        <div style="margin-top: 2px; font-size: 7.5px; color: #94a3b8;">Confidential Student Performance Document - Powered by Tuoora Education System</div>
    </div>


    <!-- ================= PAGE 3: HOMEWORK & ASSIGNMENT RECORDS ================= -->
    <div class="page-break"></div>

    <table class="inst-header" style="margin-bottom: 10px;">
        <tr>
            <td style="width: 65%;">
                <div class="inst-title" style="font-size: 14px;">{{ $institute->institute_name ?? $institute->name }}</div>
                <div class="inst-subtitle">{{ $student['name'] }} | Enrollment ID: {{ $student['enrollment_id'] ?? ('#ST-' . $student['id']) }}</div>
            </td>
            <td style="width: 35%;">
                <div class="inst-contact">Section 2: Homework &amp; Assignments<br>Generated: <strong>{{ date('M d, Y') }}</strong></div>
            </td>
        </tr>
    </table>

    <table class="highlight-heading">
        <tr>
            <td>
                2. Homework &amp; Assignment Submissions
                <span class="highlight-heading-sub">Total: {{ $homework['total_submissions'] }} | Avg Grade: {{ $homework['average_grade'] }}/10</span>
            </td>
        </tr>
    </table>

    <table class="styled-table">
        <thead>
            <tr>
                <th style="width: 30%;">Assignment Title</th>
                <th style="width: 18%;">Subject</th>
                <th style="width: 14%;">Due Date</th>
                <th style="width: 12%;">Status</th>
                <th style="width: 10%;">Score</th>
                <th style="width: 16%;">Teacher Feedback</th>
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
                    <td>
                        @if($hw['score'] !== null)
                            <strong style="color: #16a34a;">{{ $hw['score'] }}</strong>/10
                        @else
                            <span style="color: #94a3b8;">Not Graded</span>
                        @endif
                    </td>
                    <td>{{ $hw['feedback'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #94a3b8; padding: 18px;">No homework submissions logged for this student.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="footer-strong">&copy; {{ date('Y') }} {{ $institute->institute_name ?? $institute->name }} | Page 3 of 4</div>
        <div style="margin-top: 2px; font-size: 7.5px; color: #94a3b8;">Confidential Student Performance Document - Powered by Tuoora Education System</div>
    </div>


    <!-- ================= PAGE 4: RECENT ATTENDANCE BREAKDOWN ================= -->
    <div class="page-break"></div>

    <table class="inst-header" style="margin-bottom: 10px;">
        <tr>
            <td style="width: 65%;">
                <div class="inst-title" style="font-size: 14px;">{{ $institute->institute_name ?? $institute->name }}</div>
                <div class="inst-subtitle">{{ $student['name'] }} | Enrollment ID: {{ $student['enrollment_id'] ?? ('#ST-' . $student['id']) }}</div>
            </td>
            <td style="width: 35%;">
                <div class="inst-contact">Section 3: Attendance Breakdown<br>Generated: <strong>{{ date('M d, Y') }}</strong></div>
            </td>
        </tr>
    </table>

    <table class="highlight-heading">
        <tr>
            <td>
                3. Recent Attendance Breakdown &amp; Logs
                <span class="highlight-heading-sub">Total Sessions: {{ $attendance['total_days'] }} | Rate: {{ $attendance['percentage'] }}%</span>
            </td>
        </tr>
    </table>

    <table class="styled-table">
        <thead>
            <tr>
                <th style="width: 25%;">Date</th>
                <th style="width: 25%;">Day</th>
                <th style="width: 30%;">Batch Name</th>
                <th style="width: 20%;">Attendance Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse(array_slice($attendance['records'] instanceof \Illuminate\Support\Collection ? $attendance['records']->toArray() : (array)$attendance['records'], 0, 25) as $att)
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
                    <td colspan="4" style="text-align: center; color: #94a3b8; padding: 18px;">No attendance records found for this student.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="footer-strong">&copy; {{ date('Y') }} {{ $institute->institute_name ?? $institute->name }} | Page 4 of 4</div>
        <div style="margin-top: 2px; font-size: 7.5px; color: #94a3b8;">Confidential Student Performance Document - Powered by Tuoora Education System</div>
    </div>

</body>
</html>
