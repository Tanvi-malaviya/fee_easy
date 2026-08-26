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
      <p class="em-inst-sub">Student Performance & Academic Report</p>
    </td></tr>
    <tr><td class="em-banner"></td></tr>
    
    <!-- BODY -->
    <tr><td class="em-body">
      <div class="em-icon" style="background:#f0fdf4;color:#16a34a;">&#128202;</div>
      <h1 class="em-title">Academic & Performance Report</h1>
      <p class="em-sub">Your official student academic report is attached below.</p>
      
      <p>Dear <strong>{{ $studentName }}</strong> (or Parent / Guardian),</p>
      <p>Please find attached your official student comprehensive academic report issued by <strong>{{ $instituteName }}</strong>. This document includes:</p>
      
      <table class="em-cred" role="presentation" style="margin: 16px 0;">
        <tr><td class="k">Student Name</td><td><strong>{{ $studentName }}</strong></td></tr>
        <tr><td class="k">Enrollment ID</td><td><code>{{ $enrollmentId }}</code></td></tr>
        <tr><td class="k">Report Generated</td><td>{{ $generatedAt }}</td></tr>
        <tr><td class="k">Attachment</td><td><strong>PDF Report Attached &#128206;</strong></td></tr>
      </table>
      
      <p style="font-size:13px;color:#475569;line-height:1.6;">
        The attached PDF contains your complete records including <strong>Attendance Summary</strong>, <strong>Examination Marks & Results</strong>, <strong>Homework Submissions</strong>, and <strong>Fee Balance Status</strong>.
      </p>

      <div class="em-note" style="margin-top:18px;">
        If you have any questions regarding this academic report or attendance details, please contact your institute coordinator directly.
      </div>
    </td></tr>
    
    <!-- FOOTER -->
    <tr><td class="em-footer-min">
      <p class="gen">&copy; {{ date('Y') }} <strong>{{ $instituteName }}</strong> &bull; Powered by Tuoora Education System</p>
    </td></tr>
  </table>
</td></tr>
</table>
@endsection
