@extends('layouts.email')

@section('content')
<table role="presentation" class="em-wrap" width="100%" cellpadding="0" cellspacing="0">
<tr><td align="center">
  <table role="presentation" class="em-card" width="600" cellpadding="0" cellspacing="0">
    <!-- INSTITUTE HEADER -->
    <tr><td class="em-header-inst">
      @if(!empty($instituteLogoPath) && file_exists(public_path('storage/' . $instituteLogoPath)))
        <img class="em-inst-logo" src="{{ url('storage/' . $instituteLogoPath) }}" alt="{{ $instituteName }}" style="max-height: 56px;">
      @elseif(!empty($instituteLogoUrl))
        <img class="em-inst-logo" src="{{ $instituteLogoUrl }}" alt="{{ $instituteName }}" style="max-height: 56px;">
      @else
        <div class="em-inst-logo-fallback">{{ strtoupper(substr($instituteName, 0, 1)) }}</div>
      @endif
      <p class="em-inst-name">{{ $instituteName }}</p>
      <p class="em-inst-sub">Staff Account Registered</p>
    </td></tr>
    <tr><td class="em-banner"></td></tr>
    
    <!-- BODY -->
    <tr><td class="em-body">
      <div class="em-icon" style="background:#e0f2fe;color:#0284c7;">&#128108;</div>
      <h1 class="em-title">Welcome to the Team!</h1>
      <p class="em-sub">Your staff profile has been successfully created by {{ $instituteName }}.</p>
      
      <p>Hi <strong>{{ $staffName }}</strong>,</p>
      <p>We are excited to have you on board! Below are your registered staff profile details:</p>
      
      <table class="em-cred" role="presentation">
        <!-- <tr><td class="k">Employee ID</td><td><strong>{{ $employeeId }}</strong></td></tr> -->
        <tr><td class="k">Login Email</td><td><strong>{{ $staffEmail }}</strong></td></tr>
        @if(!empty($password))
          <tr><td class="k">Temporary Password</td><td><strong style="display:inline-block;background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:6px;font-family:monospace;letter-spacing:1.5px;font-size:14px;border:1px dashed #f59e0b;">{{ $password }}</strong></td></tr>
        @endif
        <tr><td class="k">Role</td><td>{{ $roleName }}</td></tr>
        <tr><td class="k">Department</td><td>{{ $departmentName }}</td></tr>
        <tr><td class="k">Institute</td><td>{{ $instituteName }}</td></tr>
      </table>
      
      @if(!empty($password))
        <p style="margin-top: 16px; padding: 10px 14px; background: #f0fdf4; border-left: 4px solid #22c55e; border-radius: 4px; font-size: 12px; color: #166534; line-height: 1.5;">
          <strong>Security Note:</strong> Please use the credentials above to log in to your staff portal. We strongly advise updating your password upon your first sign-in.
        </p>
      @endif

      @if(!empty($portalLoginUrl))
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top: 20px;">
          <tr><td align="center">
            <a href="{{ $portalLoginUrl }}" style="display:inline-block;background:#0284c7;color:#ffffff;text-decoration:none;padding:12px 28px;border-radius:8px;font-weight:600;font-size:14px;">Log in to Staff Portal</a>
          </td></tr>
        </table>
      @endif

      <p style="margin-top: 20px; color: #64748b; font-size: 13px; line-height: 1.6;">
        If you have any questions or require modifications to your profile details, please get in touch with the administration department.
      </p>
    </td></tr>
    
    <!-- FOOTER -->
    <tr><td class="em-footer-min">
      <p class="gen">Powered by <strong>Tuoora</strong></p>
    </td></tr>
  </table>
</td></tr>
</table>
@endsection
