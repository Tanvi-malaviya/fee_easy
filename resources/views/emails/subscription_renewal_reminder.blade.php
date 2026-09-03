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
      <div class="em-icon" style="background:#fff8e6;color:#f5a623;">&#9200;</div>
      @if($daysRemaining === 0)
        <h1 class="em-title">Your Plan Expires Today</h1>
        <p class="em-sub">Renew now to avoid any interruption to your services.</p>
      @else
        <h1 class="em-title">Your Plan Is Expiring Soon</h1>
        <p class="em-sub">{{ $daysRemaining }} day{{ $daysRemaining == 1 ? '' : 's' }} left on your current plan.</p>
      @endif

      <p>Hi <strong>{{ $instituteName }}</strong>,</p>
      <p>Your subscription is approaching its expiry date. Renew now to keep every feature — student records, fee tracking, attendance, exams, and reports — running without interruption.</p>

      <table class="em-cred" role="presentation">
        <tr><td class="k">Plan Name</td><td>{{ $planName }}</td></tr>
        <tr><td class="k">Expiry Date</td><td>{{ $endDate }}</td></tr>
      </table>

      <div class="em-center"><a href="{{ $renewUrl }}" class="em-btn">Renew Now</a></div>
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
