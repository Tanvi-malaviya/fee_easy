@extends('layouts.email')

@section('content')
<table role="presentation" class="em-wrap" width="100%" cellpadding="0" cellspacing="0">
<tr><td align="center">
  <table role="presentation" class="em-card" width="600" cellpadding="0" cellspacing="0">
    <!-- HEADER -->
    <tr><td class="em-header">
      <img class="em-logo-img" src="{{ url('images/infinity logo transparent.png') }}" alt="Tuoora Logo">
      <p class="em-tagline">Learn · Grow · Achieve</p>
    </td></tr>
    <tr><td class="em-banner"></td></tr>

    <!-- BODY -->
    <tr><td class="em-body">
      <div class="em-icon" style="background:#fff2eb;color:#ff6600;">&#128200;</div>
      <h1 class="em-title">Your Week at {{ $instituteName }}</h1>
      <p class="em-sub">{{ $periodLabel }}</p>

      <p>Hi <strong>{{ $instituteName }}</strong>, here's what happened this week — no dashboard login required.</p>

      <table class="em-cred" role="presentation">
        <tr><td class="k">Fees Collected</td><td style="color:#1f9d55;font-weight:600;">₹{{ number_format($feesCollected, 2) }}</td></tr>
        <tr><td class="k">Fees Pending</td><td style="color:#d9534f;font-weight:600;">₹{{ number_format($feesPending, 2) }}</td></tr>
        <tr><td class="k">Attendance</td>
          <td>
            {{ $attendancePercentage }}%
            @if($attendanceTrendDelta > 0)
              <span style="color:#1f9d55;">(&#9650; {{ $attendanceTrendDelta }}% vs last week)</span>
            @elseif($attendanceTrendDelta < 0)
              <span style="color:#d9534f;">(&#9660; {{ abs($attendanceTrendDelta) }}% vs last week)</span>
            @else
              <span style="color:#94a3b8;">(no change vs last week)</span>
            @endif
          </td>
        </tr>
        <tr><td class="k">Upcoming Exams</td><td>{{ $upcomingExamCount }} in the next 14 days</td></tr>
        <tr><td class="k">New Leads</td><td>{{ $newLeadsCount }} this week</td></tr>
        @if($lowAttendanceCount > 0)
          <tr><td class="k">Low Attendance</td><td style="color:#d9534f;font-weight:600;">{{ $lowAttendanceCount }} student{{ $lowAttendanceCount == 1 ? '' : 's' }} below 75%</td></tr>
        @endif
      </table>

      @if(count($birthdaysThisWeek) > 0)
        <p style="font-weight:600;color:#0f172a;margin-bottom:8px;">&#127874; Birthdays This Week</p>
        <table class="em-inv" role="presentation">
          <tr><th>Student</th><th style="text-align:right;">Date</th></tr>
          @foreach($birthdaysThisWeek as $b)
            <tr><td>{{ $b['name'] }}</td><td style="text-align:right;">{{ $b['date'] }}</td></tr>
          @endforeach
        </table>
      @endif

      <div class="em-center"><a href="{{ $dashboardUrl }}" class="em-btn">Open Dashboard</a></div>
    </td></tr>

    <!-- FOOTER -->
    <tr><td class="em-footer">
      <p class="em-sign">
        Warm regards,<br>
        <strong>The Tuoora Team</strong><br>
        <span class="accent">support@tuoora.com</span>
      </p>
      <div class="em-divider"></div>
      <p class="copy">© {{ date('Y') }} Tuoora. All rights reserved.</p>
    </td></tr>
  </table>
</td></tr>
</table>
@endsection
