<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      margin: 0;
      padding: 0;
      background: #eef2f6;
      font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
      -webkit-font-smoothing: antialiased;
    }

    .em-wrap {
      width: 100%;
      background: #eef2f6;
      padding: 28px 0 48px;
    }

    .em-card {
      width: 100%;
      max-width: 600px;
      margin: 0 auto;
      background: #ffffff;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 8px 30px rgba(15, 42, 57, 0.08);
    }

    .em-header {
      background: linear-gradient(135deg, #1f7a6e 0%, #155e63 55%, #0f3d4f 100%);
      padding: 30px 40px;
      text-align: center;
    }

    .em-logo-fallback {
      font-size: 26px;
      font-weight: 700;
      letter-spacing: 2px;
      color: #ffffff;
      margin: 0;
    }

    .em-logo-fallback span {
      color: #ffd66b;
    }

    .em-tagline {
      margin: 6px 0 0;
      font-size: 11px;
      letter-spacing: 3px;
      text-transform: uppercase;
      color: rgba(255, 255, 255, 0.7);
    }

    .em-banner {
      height: 6px;
      background: linear-gradient(90deg, #ffd66b 0%, #f5a623 100%);
    }

    .em-body {
      padding: 40px 44px 8px;
      color: #334155;
      font-size: 15px;
      line-height: 1.7;
    }

    .em-icon {
      width: 72px;
      height: 72px;
      margin: 0 auto 18px;
      border-radius: 50%;
      display: block;
      font-size: 32px;
      line-height: 72px;
      text-align: center;
    }

    .em-title {
      text-align: center;
      font-size: 23px;
      font-weight: 700;
      color: #0f3d4f;
      margin: 0 0 6px;
    }

    .em-sub {
      text-align: center;
      color: #64748b;
      font-size: 14px;
      margin: 0 0 26px;
    }

    .em-otp {
      text-align: center;
      margin: 8px auto 26px;
    }

    .em-otp .code {
      display: inline-block;
      background: #f1f7f6;
      border: 1px dashed #1f7a6e;
      border-radius: 12px;
      padding: 16px 30px;
      font-size: 34px;
      font-weight: 700;
      letter-spacing: 12px;
      color: #155e63;
    }

    .em-btn {
      display: inline-block;
      background: linear-gradient(135deg, #1f7a6e, #155e63);
      color: #ffffff !important;
      text-decoration: none;
      padding: 14px 34px;
      border-radius: 10px;
      font-size: 15px;
      font-weight: 600;
    }

    .em-center {
      text-align: center;
      margin: 8px 0 28px;
    }

    .em-note {
      background: #fff8e6;
      border-left: 4px solid #f5a623;
      border-radius: 8px;
      padding: 12px 16px;
      font-size: 13px;
      color: #7a5c10;
      margin: 4px 0 24px;
    }

    .em-cred {
      width: 100%;
      border-collapse: collapse;
      margin: 6px 0 24px;
      font-size: 14px;
    }

    .em-cred td {
      padding: 11px 16px;
      border: 1px solid #e2e8f0;
    }

    .em-cred td.k {
      background: #f1f7f6;
      font-weight: 600;
      color: #0f3d4f;
      width: 38%;
    }

    .em-inv {
      width: 100%;
      border-collapse: collapse;
      margin: 6px 0 16px;
      font-size: 14px;
    }

    .em-inv th {
      background: #0f3d4f;
      color: #fff;
      text-align: left;
      padding: 11px 14px;
      font-size: 13px;
    }

    .em-inv td {
      padding: 11px 14px;
      border-bottom: 1px solid #e2e8f0;
      color: #334155;
    }

    .em-inv tr.total td {
      font-weight: 700;
      color: #0f3d4f;
      font-size: 16px;
      border-bottom: none;
      border-top: 2px solid #0f3d4f;
    }

    .em-meta {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 18px;
      font-size: 13px;
      color: #64748b;
    }

    .em-meta td {
      padding: 3px 0;
    }

    .em-meta td.r {
      text-align: right;
    }

    .em-footer {
      background: #0f3d4f;
      padding: 28px 40px;
      text-align: center;
    }

    .em-sign {
      color: #cbd5e1;
      font-size: 14px;
      line-height: 1.6;
      margin: 0 0 18px;
      text-align: left;
    }

    .em-sign strong {
      color: #ffffff;
    }

    .em-sign .accent {
      color: #ffd66b;
    }

    .em-footer .links a {
      color: #9fc4c0;
      text-decoration: none;
      font-size: 12px;
      margin: 0 8px;
    }

    .em-footer .copy {
      color: #64829a;
      font-size: 11px;
      margin: 14px 0 0;
    }

    .em-divider {
      height: 1px;
      background: #1c5366;
      margin: 0 0 18px;
    }

    /* ---- institute-branded header (templates 5 & 6) ---- */
    .em-header-inst {
      background: #ffffff;
      border-bottom: 1px solid #e2e8f0;
      padding: 26px 40px;
      text-align: center;
    }

    .em-inst-logo-fallback {
      width: 56px;
      height: 56px;
      line-height: 56px;
      margin: 0 auto 8px;
      border-radius: 50%;
      background: #0f3d4f;
      color: #ffffff;
      font-size: 24px;
      font-weight: 700;
      text-align: center;
    }

    .em-inst-name {
      margin: 6px 0 0;
      font-size: 20px;
      font-weight: 700;
      color: #0f3d4f;
    }

    .em-inst-sub {
      margin: 2px 0 0;
      font-size: 12px;
      color: #94a3b8;
    }

    /* ---- minimal "generated by" footer ---- */
    .em-footer-min {
      background: #f1f5f9;
      padding: 20px 40px;
      text-align: center;
      border-top: 1px solid #e2e8f0;
    }

    .em-footer-min .gen {
      font-size: 12px;
      color: #94a3b8;
      margin: 0;
    }

    .em-footer-min .gen strong {
      color: #1f7a6e;
    }

    @media (max-width:620px) {
      .em-body {
        padding: 28px 24px 6px;
      }

      .em-header {
        padding: 24px;
      }

      .em-otp .code {
        font-size: 26px;
        letter-spacing: 8px;
        padding: 14px 18px;
      }
    }
  </style>
</head>

<body>
  @yield('content')
</body>

</html>