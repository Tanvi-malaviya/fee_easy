@extends('layouts.email')

@section('content')
<table role="presentation" class="em-wrap" width="100%" cellpadding="0" cellspacing="0">
<tr><td align="center">
  <table role="presentation" class="em-card" width="600" cellpadding="0" cellspacing="0">
    <!-- HEADER -->
    <tr><td class="em-header">
      <p class="em-logo-fallback">TU<span>OO</span>RA</p>
      <p class="em-tagline">Learn · Grow · Achieve</p>
    </td></tr>
    <tr><td class="em-banner"></td></tr>
    <!-- BODY -->
    <tr><td class="em-body">
      <div class="em-icon" style="background:#e6f6ec;color:#1f9d55;">&#10004;</div>
      <h1 class="em-title">Account Activated!</h1>
      <p class="em-sub">Your Tuoora account is now active and ready to use.</p>
      <p>Hi <strong>{{ $userName }}</strong>,</p>
      <p>Congratulations! Your email has been successfully verified and your account is now fully activated. You can now access all your dashboard, resources, student registry, and financial management tools.</p>
      <div class="em-center"><a href="{{ $loginUrl }}" class="em-btn">Start Managing</a></div>
      <div class="em-note">Tip: Complete your profile setup to start adding batches, courses, and students.</div>
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
