@extends('layouts.email')

@section('content')
<table role="presentation" class="em-wrap" width="100%" cellpadding="0" cellspacing="0">
<tr><td align="center">
  <table role="presentation" class="em-card" width="600" cellpadding="0" cellspacing="0">
    <!-- HEADER -->
    <tr><td class="em-header">
      <img class="em-logo-img" src="{{ $message->embed(public_path('images/2-remove.png')) }}" alt="Tuoora Logo">
      <p class="em-tagline">Learn · Grow · Achieve</p>
    </td></tr>
    <tr><td class="em-banner"></td></tr>
    <!-- BODY -->
    <tr><td class="em-body">
      <div class="em-icon" style="background:#fff2eb;color:#ff6600;">&#128273;</div>
      <h1 class="em-title">Reset Your Password</h1>
      <p class="em-sub">Use the verification code below to reset your password.</p>
      <p>Hi <strong>{{ $userName }}</strong>,</p>
      <p>We received a request to reset the password for your Tuoora account. Enter the following code to proceed:</p>
      <div class="em-otp"><span class="code">{{ $otp }}</span></div>
      <div class="em-note">This code expires in <strong>15 minutes</strong>. If you didn't request a password reset, please ignore this email — your account is still secure.</div>
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
