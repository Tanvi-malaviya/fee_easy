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
        <tr><td class="k">Email</td><td>{{ $staffEmail }}</td></tr>
        <tr><td class="k">Role</td><td>{{ $roleName }}</td></tr>
        <tr><td class="k">Department</td><td>{{ $departmentName }}</td></tr>
        <tr><td class="k">Institute</td><td>{{ $instituteName }}</td></tr>
      </table>
      
      <p style="margin-top: 24px; color: #64748b; font-size: 13px; line-height: 1.6;">
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
