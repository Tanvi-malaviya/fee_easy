@extends('layouts.email')

@section('content')
<table role="presentation" class="em-wrap" width="100%" cellpadding="0" cellspacing="0">
<tr><td align="center">
  <table role="presentation" class="em-card" width="600" cellpadding="0" cellspacing="0">
    <!-- HEADER -->
    <tr><td class="em-header">
      <img class="em-logo-img" src="{{ url('images/2-remove.png') }}" alt="Tuoora Logo">
      <p class="em-tagline">Learn · Grow · Achieve</p>
    </td></tr>
    <tr><td class="em-banner"></td></tr>
    <!-- BODY -->
    <tr><td class="em-body">
      <div class="em-icon" style="background:#fff2eb;color:#ff6600;">&#127881;</div>
      
      @if($type == 'assigned')
        <h1 class="em-title">Your Plan is Active</h1>
        <p class="em-sub">A new subscription plan has been assigned to your account.</p>
      @elseif($type == 'extended')
        <h1 class="em-title">Subscription Extended!</h1>
        <p class="em-sub">Your plan validity has been successfully extended by the administrator.</p>
      @elseif($type == 'changed')
        <h1 class="em-title">Subscription Upgraded!</h1>
        <p class="em-sub">Your subscription plan has been successfully changed.</p>
      @elseif($type == 'approved')
        <h1 class="em-title">Renewal Approved!</h1>
        <p class="em-sub">Your offline plan renewal request has been approved by the administrator.</p>
      @else
        <h1 class="em-title">Subscription Updated</h1>
        <p class="em-sub">Your subscription plan details have been successfully updated.</p>
      @endif

      <p>Hi <strong>{{ $instituteName }}</strong>,</p>
      <p>Great news! The following subscription details are now active on your account:</p>
      
      <table class="em-cred" role="presentation">
        <tr><td class="k">Plan Name</td><td>{{ $planName }}</td></tr>
        @if($type == 'extended')
          <tr><td class="k">Days Added</td><td><strong>{{ $amount }} Days</strong></td></tr>
        @else
          <tr><td class="k">Price</td><td><strong>₹{{ number_format($amount, 2) }}</strong></td></tr>
        @endif
        <tr><td class="k">New Expiry Date</td><td>{{ \Carbon\Carbon::parse($endDate)->format('d M, Y') }}</td></tr>
        <tr><td class="k">Status</td><td style="color:#1f9d55;font-weight:600;">Active</td></tr>
      </table>

      @if($type == 'assigned')
        <p>Your new subscription plan is now active! You can continue using all the features of the Tuoora platform.</p>
      @elseif($type == 'extended')
        <p>Your subscription validity has been successfully extended. Thank you for continuing your journey with us!</p>
      @elseif($type == 'changed')
        <p>Your subscription has been successfully upgraded/changed to the new plan. Enjoy your new features!</p>
      @elseif($type == 'approved')
        <p>Your offline renewal request has been reviewed and approved by the administrator.</p>
      @endif

      <div class="em-center"><a href="{{ url('/institute/login') }}" class="em-btn">Open Admin App</a></div>
    </td></tr>
    <!-- FOOTER -->
    <tr><td class="em-footer">
      <p class="em-sign">
        Warm regards,<br>
        <strong>The Tuoora Team</strong><br>
        <span class="accent">support@tuoora.com</span>
      </p>
      <div class="em-divider"></div>
      <div class="links">
        <a href="#">Help Center</a>·<a href="#">Privacy</a>·<a href="#">Contact</a>
      </div>
      <p class="copy">© {{ date('Y') }} Tuoora. All rights reserved.</p>
    </td></tr>
  </table>
</td></tr>
</table>
@endsection
