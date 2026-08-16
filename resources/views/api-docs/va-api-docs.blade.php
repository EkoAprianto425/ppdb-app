<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dokumentasi API Virtual Account — PPDB Al-Hasra</title>
<style>
/* ================================================================
   RESET & BASE
================================================================ */
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
  font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
  font-size: 12.5px;
  line-height: 1.7;
  color: #1e293b;
  background: #ffffff;
  -webkit-print-color-adjust: exact;
  print-color-adjust: exact;
}

/* ================================================================
   MONO FONT
================================================================ */
.mono, code, pre, .code-block {
  font-family: DejaVu Sans Mono, Courier New, monospace;
}

/* ================================================================
   PAGE STRUCTURE
================================================================ */
.page-break { page-break-before: always; }
.no-break   { page-break-inside: avoid; }

/* ================================================================
   COVER PAGE
================================================================ */
.cover {
  min-height: 297mm;
  background: #0f172a;
  position: relative;
  display: block;
  overflow: hidden;
}
.cover-accent-bar {
  height: 5px;
  background: linear-gradient(90deg, #3b82f6 0%, #8b5cf6 50%, #06b6d4 100%);
}
.cover-inner {
  padding: 44px 42px 36px 42px;
}
.cover-tag {
  display: inline-block;
  font-size: 9px;
  font-weight: bold;
  letter-spacing: 2.5px;
  text-transform: uppercase;
  color: #7dd3fc;
  background: rgba(59,130,246,0.15);
  border: 1px solid rgba(59,130,246,0.35);
  padding: 4px 12px;
  border-radius: 20px;
  margin-bottom: 24px;
}
.cover-title {
  font-size: 34px;
  font-weight: bold;
  color: #ffffff;
  line-height: 1.15;
  margin-bottom: 8px;
  letter-spacing: -0.5px;
}
.cover-title span { color: #60a5fa; }
.cover-subtitle {
  font-size: 14px;
  color: #94a3b8;
  margin-bottom: 32px;
  font-weight: normal;
}
.cover-divider {
  width: 48px;
  height: 3px;
  background: #3b82f6;
  border-radius: 2px;
  margin-bottom: 28px;
}
.cover-desc {
  font-size: 12px;
  color: #94a3b8;
  line-height: 1.75;
  max-width: 500px;
  margin-bottom: 36px;
}
.cover-ep-grid {
  display: block;
  margin-bottom: 36px;
}
.cover-ep-row {
  display: block;
  margin-bottom: 10px;
}
.cover-ep-cell {
  display: inline-block;
  width: 48%;
  vertical-align: top;
  background: rgba(255,255,255,0.05);
  border: 1px solid rgba(255,255,255,0.1);
  border-radius: 8px;
  padding: 10px 14px;
  margin-right: 2%;
}
.cover-ep-cell:last-child { margin-right: 0; }
.cover-ep-path {
  font-family: DejaVu Sans Mono, Courier New, monospace;
  font-size: 10.5px;
  color: #e2e8f0;
  margin: 5px 0 2px 0;
  display: block;
}
.cover-ep-name {
  font-size: 10px;
  color: #64748b;
}
.cover-meta-bar {
  border-top: 1px solid rgba(255,255,255,0.1);
  padding-top: 20px;
  display: block;
}
.cover-meta-left {
  display: inline-block;
  vertical-align: top;
  width: 60%;
}
.cover-meta-right {
  display: inline-block;
  vertical-align: top;
  width: 38%;
  text-align: right;
}
.cover-meta-label {
  font-size: 9px;
  color: #475569;
  text-transform: uppercase;
  letter-spacing: 1px;
  display: block;
  margin-bottom: 2px;
}
.cover-meta-value {
  font-size: 11px;
  color: #94a3b8;
}
.cover-meta-value strong { color: #cbd5e1; }

/* ================================================================
   METHOD BADGES
================================================================ */
.badge-method {
  display: inline-block;
  font-size: 9px;
  font-weight: bold;
  letter-spacing: 0.5px;
  padding: 3px 9px;
  border-radius: 4px;
  vertical-align: middle;
  font-family: DejaVu Sans Mono, Courier New, monospace;
}
.method-get  { background: #dbeafe; color: #1d4ed8; }
.method-post { background: #dcfce7; color: #15803d; }
.method-both { background: #ede9fe; color: #6d28d9; }

/* ================================================================
   CONTENT PAGES
================================================================ */
.content-page {
  padding: 22px 34px 30px 34px;
}

/* ================================================================
   PAGE HEADER
================================================================ */
.page-header {
  border-bottom: 2px solid #e2e8f0;
  padding-bottom: 8px;
  margin-bottom: 20px;
}
.page-header-label {
  font-size: 9px;
  text-transform: uppercase;
  letter-spacing: 2px;
  color: #94a3b8;
  margin-bottom: 3px;
}
.page-header-title {
  font-size: 18px;
  font-weight: bold;
  color: #0f172a;
}

/* ================================================================
   SECTIONS
================================================================ */
.section { margin-bottom: 22px; }
.section-title {
  font-size: 14.5px;
  font-weight: bold;
  color: #0f172a;
  padding-bottom: 5px;
  border-bottom: 2px solid #e2e8f0;
  margin-bottom: 12px;
}
.section-title.blue   { border-bottom-color: #3b82f6; }
.section-title.green  { border-bottom-color: #22c55e; }
.section-title.orange { border-bottom-color: #f97316; }
.section-title.red    { border-bottom-color: #ef4444; }
.section-title.purple { border-bottom-color: #8b5cf6; }
.section-title.cyan   { border-bottom-color: #06b6d4; }

.sub-section { margin-bottom: 14px; }
.sub-title {
  font-size: 12.5px;
  font-weight: bold;
  color: #334155;
  margin-bottom: 6px;
}

/* ================================================================
   TYPOGRAPHY
================================================================ */
p  { margin-bottom: 8px; color: #475569; font-size: 12px; }
ul, ol { padding-left: 18px; margin-bottom: 10px; }
li { font-size: 12px; color: #475569; margin-bottom: 3px; }
strong { color: #1e293b; }

/* ================================================================
   TABLES
================================================================ */
table {
  width: 100%;
  border-collapse: collapse;
  font-size: 11.5px;
  margin-bottom: 12px;
}
thead { background: #1e293b; }
thead th {
  color: #e2e8f0;
  font-weight: bold;
  font-size: 10.5px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  padding: 8px 12px;
  text-align: left;
}
tbody td {
  padding: 8px 12px;
  border-bottom: 1px solid #e2e8f0;
  color: #334155;
  vertical-align: top;
  font-size: 11.5px;
}
tr:last-child td { border-bottom: none; }
tr:nth-child(even) td { background: #f8fafc; }
table { border: 1px solid #e2e8f0; }

.td-mono {
  font-family: DejaVu Sans Mono, Courier New, monospace;
  font-size: 10.5px;
  color: #1d4ed8;
  font-weight: bold;
}

/* ================================================================
   PARAMETER BADGES
================================================================ */
.badge {
  display: inline-block;
  padding: 2px 7px;
  border-radius: 4px;
  font-size: 9px;
  font-weight: bold;
}
.badge-required { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
.badge-optional { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
.badge-string   { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.badge-numeric  { background: #faf5ff; color: #7c3aed; border: 1px solid #ddd6fe; }
.badge-datetime { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }

/* ================================================================
   RESPONSE STATUS BADGES
================================================================ */
.badge-200 { background: #f0fdf4; color: #15803d; border: 1px solid #86efac; font-family: DejaVu Sans Mono, Courier New, monospace; font-size: 10px; font-weight: bold; padding: 2px 7px; border-radius: 4px; display: inline-block; }
.badge-400 { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; font-family: DejaVu Sans Mono, Courier New, monospace; font-size: 10px; font-weight: bold; padding: 2px 7px; border-radius: 4px; display: inline-block; }
.badge-401 { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; font-family: DejaVu Sans Mono, Courier New, monospace; font-size: 10px; font-weight: bold; padding: 2px 7px; border-radius: 4px; display: inline-block; }
.badge-404 { background: #fdf4ff; color: #7e22ce; border: 1px solid #e9d5ff; font-family: DejaVu Sans Mono, Courier New, monospace; font-size: 10px; font-weight: bold; padding: 2px 7px; border-radius: 4px; display: inline-block; }
.badge-422 { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; font-family: DejaVu Sans Mono, Courier New, monospace; font-size: 10px; font-weight: bold; padding: 2px 7px; border-radius: 4px; display: inline-block; }
.badge-429 { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; font-family: DejaVu Sans Mono, Courier New, monospace; font-size: 10px; font-weight: bold; padding: 2px 7px; border-radius: 4px; display: inline-block; }

/* ================================================================
   ENDPOINT CARDS
================================================================ */
.endpoint-card {
  border: 1.5px solid #e2e8f0;
  border-radius: 8px;
  margin-bottom: 18px;
  overflow: hidden;
}
.endpoint-top {
  padding: 11px 16px;
  background: #1e293b;
}
.endpoint-top.get-bg  { background: #14532d; }
.endpoint-top.post-bg { background: #1e3a5f; }
.endpoint-top.both-bg { background: #2d1b69; }
.endpoint-path {
  font-family: DejaVu Sans Mono, Courier New, monospace;
  font-size: 13px;
  font-weight: bold;
  color: #ffffff;
  vertical-align: middle;
  margin-left: 8px;
}
.endpoint-subdesc {
  font-size: 10px;
  color: #94a3b8;
  margin-top: 4px;
}
.endpoint-body {
  padding: 14px 16px;
  background: #ffffff;
}
.endpoint-desc-text {
  font-size: 12px;
  color: #475569;
  margin-bottom: 12px;
  line-height: 1.7;
}

/* ================================================================
   CODE BLOCKS (print-friendly light theme)
================================================================ */
.code-block {
  background: #f8fafc;
  border: 1.5px solid #e2e8f0;
  border-radius: 6px;
  padding: 11px 14px;
  margin: 8px 0 12px 0;
  font-family: DejaVu Sans Mono, Courier New, monospace;
  font-size: 10px;
  color: #1e293b;
  line-height: 1.75;
  white-space: pre-wrap;
  word-wrap: break-word;
}
.code-block .cm  { color: #64748b; font-style: italic; }  /* comment */
.code-block .ky  { color: #7c3aed; }                       /* keyword / key */
.code-block .st  { color: #15803d; }                       /* string */
.code-block .nm  { color: #c2410c; }                       /* number */
.code-block .fn  { color: #1d4ed8; }                       /* function */
.code-block .tr  { color: #15803d; font-weight: bold; }    /* true */
.code-block .fa  { color: #b91c1c; font-weight: bold; }    /* false */
.code-block .nu  { color: #64748b; }                       /* null */
.code-block .url { color: #b45309; font-weight: bold; }    /* url */
.code-block .hd  { color: #0369a1; }                       /* header name */

.code-label {
  font-size: 9px;
  font-weight: bold;
  text-transform: uppercase;
  letter-spacing: 1.2px;
  color: #64748b;
  margin-bottom: 3px;
}

/* ================================================================
   INLINE CODE
================================================================ */
code {
  font-family: DejaVu Sans Mono, Courier New, monospace;
  font-size: 10.5px;
  background: #f1f5f9;
  color: #be123c;
  padding: 1px 5px;
  border-radius: 3px;
  border: 1px solid #e2e8f0;
}

/* ================================================================
   ALERT / CALLOUT BOXES
================================================================ */
.alert {
  padding: 10px 14px;
  border-radius: 6px;
  margin-bottom: 12px;
  font-size: 11.5px;
  border: 1px solid transparent;
  border-left: 4px solid transparent;
  line-height: 1.65;
}
.alert-title { font-weight: bold; margin-bottom: 3px; font-size: 12px; }
.alert-info    { background: #eff6ff; border-color: #bfdbfe; border-left-color: #3b82f6; color: #1e40af; }
.alert-warning { background: #fffbeb; border-color: #fde68a; border-left-color: #f59e0b; color: #92400e; }
.alert-danger  { background: #fef2f2; border-color: #fecaca; border-left-color: #ef4444; color: #991b1b; }
.alert-success { background: #f0fdf4; border-color: #bbf7d0; border-left-color: #22c55e; color: #166534; }

/* ================================================================
   TABLE OF CONTENTS
================================================================ */
.toc-box {
  background: #f8fafc;
  border: 1.5px solid #e2e8f0;
  border-radius: 8px;
  padding: 18px 22px;
  margin-bottom: 20px;
}
.toc-heading {
  font-size: 10px;
  font-weight: bold;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  color: #94a3b8;
  margin-bottom: 14px;
}
.toc-item {
  display: block;
  padding: 5px 0;
  border-bottom: 1px dotted #e2e8f0;
  font-size: 12px;
  color: #334155;
}
.toc-item:last-child { border-bottom: none; }
.toc-num {
  font-weight: bold;
  color: #3b82f6;
  display: inline-block;
  width: 26px;
  font-size: 12px;
}
.toc-sub {
  padding: 4px 0 4px 26px;
  font-size: 11px;
  color: #64748b;
  border-bottom: 1px dotted #f1f5f9;
}
.toc-sub:last-child { border-bottom: none; }

/* ================================================================
   NUMBERED STEPS
================================================================ */
.steps-list { margin-bottom: 12px; }
.step-row {
  display: block;
  margin-bottom: 7px;
  padding: 9px 13px;
  background: #f8fafc;
  border-left: 3px solid #3b82f6;
  border-radius: 0 6px 6px 0;
}
.step-num {
  display: inline-block;
  background: #3b82f6;
  color: #ffffff;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  line-height: 20px;
  text-align: center;
  font-size: 9.5px;
  font-weight: bold;
  margin-right: 9px;
  vertical-align: middle;
}
.step-text {
  display: inline-block;
  vertical-align: middle;
  font-size: 11.5px;
  color: #334155;
  line-height: 1.6;
}

/* ================================================================
   FLOW STEPS
================================================================ */
.flow-row {
  display: block;
  margin: 10px 0;
  background: #f8fafc;
  border: 1.5px solid #e2e8f0;
  border-radius: 6px;
  padding: 8px 12px;
  font-size: 11.5px;
  color: #334155;
}
.flow-arrow {
  font-size: 14px;
  color: #94a3b8;
  margin: 0 6px;
  vertical-align: middle;
}
.flow-step-box {
  display: inline-block;
  background: #1e293b;
  color: #e2e8f0;
  border-radius: 5px;
  padding: 4px 10px;
  font-size: 10.5px;
  font-weight: bold;
  vertical-align: middle;
}

/* ================================================================
   INFO CARDS (overview grid)
================================================================ */
.info-grid { display: block; margin-bottom: 14px; }
.info-row  { display: block; margin-bottom: 8px; }
.info-card {
  display: inline-block;
  vertical-align: top;
  width: 31%;
  margin-right: 2.3%;
  border: 1.5px solid #e2e8f0;
  border-radius: 8px;
  padding: 10px 12px;
  background: #fff;
}
.info-card:last-child { margin-right: 0; }
.info-card-label {
  font-size: 9px;
  font-weight: bold;
  text-transform: uppercase;
  letter-spacing: 0.8px;
  color: #94a3b8;
  margin-bottom: 4px;
}
.info-card-value {
  font-size: 12px;
  font-weight: bold;
  color: #1e293b;
}
.info-card-value.mono {
  font-family: DejaVu Sans Mono, Courier New, monospace;
  font-size: 11px;
}

/* ================================================================
   RESPONSE CODE TABLE
================================================================ */
.rc-thead { background: #1e293b; }
.rc-thead th { color: #e2e8f0; }

/* ================================================================
   PAGE FOOTER
================================================================ */
.doc-footer {
  margin-top: 26px;
  padding-top: 10px;
  border-top: 1px solid #e2e8f0;
  font-size: 9px;
  color: #94a3b8;
  text-align: center;
}

/* ================================================================
   SECURITY TAG
================================================================ */
.sec-badge {
  display: inline-block;
  font-size: 9px;
  font-weight: bold;
  padding: 2px 8px;
  border-radius: 4px;
  vertical-align: middle;
  margin-left: 6px;
}
.sec-hmac { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
.sec-pub  { background: #f0fdf4; color: #166534; border: 1px solid #86efac; }
</style>
</head>
<body>

<!-- ============================================================
     HALAMAN 1: COVER
============================================================ -->
<div class="cover">
  <div class="cover-accent-bar"></div>
  <div class="cover-inner">

    <div class="cover-tag">API Technical Documentation</div>

    <div class="cover-title">PPDB Al-Hasra<br><span>Virtual Account API</span></div>
    <div class="cover-subtitle">Standar BI SNAP &mdash; Autentikasi HMAC-SHA256</div>

    <div class="cover-divider"></div>

    <p class="cover-desc">
      Dokumentasi teknis lengkap untuk integrasi API Virtual Account sistem PPDB Al-Hasra.
      Mencakup spesifikasi semua endpoint, mekanisme autentikasi, format request &amp; response,
      kode error, dan contoh implementasi dalam berbagai bahasa pemrograman.
    </p>

    <div class="cover-ep-grid">
      <div class="cover-ep-row">
        <div class="cover-ep-cell">
          <span class="badge-method method-get">GET</span>
          <span class="cover-ep-path">/api/va/{va_number}</span>
          <span class="cover-ep-name">VA Inquiry by Path Parameter</span>
        </div><!--
     --><div class="cover-ep-cell">
          <span class="badge-method method-both">GET/POST</span>
          <span class="cover-ep-path">/api/va/inquiry</span>
          <span class="cover-ep-name">VA Inquiry by Request Body</span>
        </div>
      </div>
      <div class="cover-ep-row">
        <div class="cover-ep-cell">
          <span class="badge-method method-post">POST</span>
          <span class="cover-ep-path">/api/va/payment-notify</span>
          <span class="cover-ep-name">VA Payment Notification</span>
        </div><!--
     --><div class="cover-ep-cell">
          <span class="badge-method method-post">POST</span>
          <span class="cover-ep-path">/api/btn/callback</span>
          <span class="cover-ep-name">BTN Legacy Callback Webhook</span>
        </div>
      </div>
    </div>

    <div class="cover-meta-bar">
      <div class="cover-meta-left">
        <span class="cover-meta-label">Diterbitkan oleh</span>
        <span class="cover-meta-value"><strong>PPDB Al-Hasra</strong> &mdash; Tim Teknis Integrasi</span>
      </div>
      <div class="cover-meta-right">
        <span class="cover-meta-label">Versi &amp; Tanggal</span>
        <span class="cover-meta-value"><strong>v1.0.0</strong> &mdash; {{ date('d F Y') }}</span>
      </div>
    </div>

  </div>
</div>

<!-- ============================================================
     HALAMAN 2: DAFTAR ISI
============================================================ -->
<div class="page-break"></div>
<div class="content-page">

  <div class="page-header">
    <div class="page-header-label">Virtual Account API &bull; PPDB Al-Hasra</div>
    <div class="page-header-title">Daftar Isi</div>
  </div>

  <div class="toc-box">
    <div class="toc-heading">Isi Dokumen</div>

    <span class="toc-item"><span class="toc-num">1</span> Gambaran Umum API</span>
    <span class="toc-sub">1.1 &nbsp;Deskripsi Sistem &amp; Tujuan</span>
    <span class="toc-sub">1.2 &nbsp;Daftar Endpoint</span>
    <span class="toc-sub">1.3 &nbsp;Alur Sistem Virtual Account PPDB</span>

    <span class="toc-item"><span class="toc-num">2</span> Autentikasi &amp; Keamanan (HMAC-SHA256)</span>
    <span class="toc-sub">2.1 &nbsp;Header Autentikasi yang Wajib</span>
    <span class="toc-sub">2.2 &nbsp;Cara Membuat Signature (Langkah Demi Langkah)</span>
    <span class="toc-sub">2.3 &nbsp;Fitur Keamanan Tambahan</span>
    <span class="toc-sub">2.4 &nbsp;Konfigurasi Environment Server</span>

    <span class="toc-item"><span class="toc-num">3</span> Rate Limiting &amp; Environment</span>

    <span class="toc-item"><span class="toc-num">4</span> Endpoint: VA Inquiry (GET by Path)</span>
    <span class="toc-sub">4.1 &nbsp;Path Parameter &amp; Headers</span>
    <span class="toc-sub">4.2 &nbsp;Contoh Request &amp; Response</span>

    <span class="toc-item"><span class="toc-num">5</span> Endpoint: VA Inquiry (GET/POST by Body)</span>
    <span class="toc-sub">5.1 &nbsp;Request Body / Query String</span>
    <span class="toc-sub">5.2 &nbsp;Contoh Request &amp; Response</span>

    <span class="toc-item"><span class="toc-num">6</span> Endpoint: VA Payment Notification</span>
    <span class="toc-sub">6.1 &nbsp;Request Body &amp; Validasi</span>
    <span class="toc-sub">6.2 &nbsp;Logika Pemrosesan</span>
    <span class="toc-sub">6.3 &nbsp;Contoh Request &amp; Semua Skenario Response</span>

    <span class="toc-item"><span class="toc-num">7</span> Endpoint: BTN Legacy Callback</span>
    <span class="toc-sub">7.1 &nbsp;Request Body &amp; Response Format</span>

    <span class="toc-item"><span class="toc-num">8</span> Kode Respons &amp; Error Handling</span>
    <span class="toc-sub">8.1 &nbsp;Tabel Kode Respons Lengkap</span>
    <span class="toc-sub">8.2 &nbsp;Format Respons Error Standar</span>

    <span class="toc-item"><span class="toc-num">9</span> Contoh Implementasi Lengkap</span>
    <span class="toc-sub">9.1 &nbsp;PHP (cURL) — Inquiry &amp; Payment Notify</span>
    <span class="toc-sub">9.2 &nbsp;JavaScript (Node.js) — Inquiry &amp; Payment Notify</span>
    <span class="toc-sub">9.3 &nbsp;Python (requests) — Inquiry &amp; Payment Notify</span>

    <span class="toc-item"><span class="toc-num">10</span> Integrasi Postman</span>
    <span class="toc-sub">10.1 &nbsp;Collection Variables &amp; Pre-request Script</span>

    <span class="toc-item"><span class="toc-num">11</span> FAQ &amp; Troubleshooting</span>
  </div>

  <div class="doc-footer">
    PPDB Al-Hasra &bull; Virtual Account API Documentation &bull; v1.0.0 &bull; {{ date('Y') }}
  </div>
</div>

<!-- ============================================================
     HALAMAN 3: GAMBARAN UMUM
============================================================ -->
<div class="page-break"></div>
<div class="content-page">

  <div class="page-header">
    <div class="page-header-label">Virtual Account API &bull; PPDB Al-Hasra</div>
    <div class="page-header-title">1. Gambaran Umum API</div>
  </div>

  <!-- 1.1 Deskripsi -->
  <div class="section no-break">
    <div class="section-title blue">1.1 Deskripsi Sistem &amp; Tujuan</div>
    <p>
      PPDB VA API menyediakan antarmuka standar untuk mengintegrasikan sistem Virtual Account (VA) Bank BTN
      ke dalam platform PPDB Al-Hasra. API ini mengikuti spesifikasi
      <strong>Standar Nasional Open API Pembayaran Bank Indonesia (BI SNAP)</strong>
      dengan autentikasi HMAC-SHA256.
    </p>
    <p>
      Melalui API ini, sistem bank atau payment gateway dapat melakukan inquiry data tagihan VA siswa
      dan mengirimkan notifikasi pembayaran secara otomatis dan aman.
    </p>

    <div class="info-grid">
      <div class="info-row">
        <div class="info-card">
          <div class="info-card-label">Base URL (Dev)</div>
          <div class="info-card-value mono">http://localhost:8000</div>
        </div><!--
     --><div class="info-card">
          <div class="info-card-label">Format Data</div>
          <div class="info-card-value">JSON (application/json)</div>
        </div><!--
     --><div class="info-card">
          <div class="info-card-label">Autentikasi</div>
          <div class="info-card-value">HMAC-SHA256 Signature</div>
        </div>
      </div>
      <div class="info-row">
        <div class="info-card">
          <div class="info-card-label">Standar</div>
          <div class="info-card-value">BI SNAP</div>
        </div><!--
     --><div class="info-card">
          <div class="info-card-label">Rate Limit</div>
          <div class="info-card-value">60 request / menit</div>
        </div><!--
     --><div class="info-card">
          <div class="info-card-label">Versi Dokumen</div>
          <div class="info-card-value">v1.0.0 &mdash; {{ date('Y') }}</div>
        </div>
      </div>
    </div>
  </div>

  <!-- 1.2 Daftar Endpoint -->
  <div class="section no-break">
    <div class="section-title green">1.2 Daftar Endpoint</div>
    <table>
      <thead>
        <tr>
          <th>Method</th>
          <th>Endpoint</th>
          <th>Fungsi</th>
          <th>Auth</th>
          <th>Rate Limit</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><span class="badge-method method-get">GET</span></td>
          <td class="td-mono">/api/va/{va_number}</td>
          <td>VA Inquiry by Path Parameter</td>
          <td>HMAC-SHA256</td>
          <td>60/menit</td>
        </tr>
        <tr>
          <td><span class="badge-method method-both">GET/POST</span></td>
          <td class="td-mono">/api/va/inquiry</td>
          <td>VA Inquiry by Request Body</td>
          <td>HMAC-SHA256</td>
          <td>60/menit</td>
        </tr>
        <tr>
          <td><span class="badge-method method-post">POST</span></td>
          <td class="td-mono">/api/va/payment-notify</td>
          <td>Notifikasi Pembayaran VA</td>
          <td>HMAC-SHA256</td>
          <td>60/menit</td>
        </tr>
        <tr>
          <td><span class="badge-method method-post">POST</span></td>
          <td class="td-mono">/api/btn/callback</td>
          <td>BTN Legacy Callback Webhook</td>
          <td>Tidak diperlukan</td>
          <td>&mdash;</td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- 1.3 Alur Sistem -->
  <div class="section no-break">
    <div class="section-title orange">1.3 Alur Sistem Virtual Account PPDB</div>
    <div class="flow-row">
      <span class="flow-step-box">1. Siswa Daftar</span>
      <span class="flow-arrow">&rarr;</span>
      <span class="flow-step-box">2. Generate VA BTN</span>
      <span class="flow-arrow">&rarr;</span>
      <span class="flow-step-box">3. Siswa Transfer</span>
      <span class="flow-arrow">&rarr;</span>
      <span class="flow-step-box">4. Inquiry / Callback</span>
      <span class="flow-arrow">&rarr;</span>
      <span class="flow-step-box">5. Status &rarr; Sukses</span>
      <span class="flow-arrow">&rarr;</span>
      <span class="flow-step-box">6. Pilih Jadwal Ujian</span>
    </div>
    <ol>
      <li><strong>Siswa Daftar</strong> &mdash; Membuat akun dan memilih unit tujuan (SMP/SMA/SMK)</li>
      <li><strong>Generate VA</strong> &mdash; Menekan tombol &ldquo;Bayar via VA BTN&rdquo; untuk mendapatkan nomor VA 17 digit</li>
      <li><strong>Transfer</strong> &mdash; Siswa mentransfer biaya pendaftaran ke nomor VA tersebut</li>
      <li><strong>Verifikasi</strong> &mdash; Bank mengirim notifikasi ke <code>/api/va/payment-notify</code> atau <code>/api/btn/callback</code>, atau admin menekan tombol &ldquo;Cek Status VA&rdquo; (Inquiry)</li>
      <li><strong>Status Update</strong> &mdash; Sistem mengupdate status pembayaran siswa menjadi <code>success</code></li>
      <li><strong>Lanjut Proses</strong> &mdash; Siswa dapat memilih jadwal ujian dan mengunduh kartu ujian</li>
    </ol>
  </div>

  <div class="doc-footer">
    PPDB Al-Hasra &bull; Virtual Account API Documentation &bull; v1.0.0 &bull; {{ date('Y') }}
  </div>
</div>

<!-- ============================================================
     HALAMAN 4: AUTENTIKASI
============================================================ -->
<div class="page-break"></div>
<div class="content-page">

  <div class="page-header">
    <div class="page-header-label">Virtual Account API &bull; PPDB Al-Hasra</div>
    <div class="page-header-title">2. Autentikasi &amp; Keamanan</div>
  </div>

  <!-- 2.1 Header Wajib -->
  <div class="section no-break">
    <div class="section-title blue">2.1 Header Autentikasi yang Wajib</div>
    <p>
      Semua endpoint (kecuali <code>/api/btn/callback</code>) dilindungi middleware
      <strong>HMAC-SHA256 Signature Authentication</strong> standar BI SNAP.
      Setiap request <strong>wajib</strong> menyertakan 3 header berikut:
    </p>
    <table>
      <thead>
        <tr><th>Header</th><th>Tipe</th><th>Keterangan</th><th>Contoh Nilai</th></tr>
      </thead>
      <tbody>
        <tr>
          <td class="td-mono">X-CLIENT-KEY</td>
          <td><span class="badge badge-string">string</span></td>
          <td>Identifier klien yang telah disetujui sistem</td>
          <td class="td-mono">PPDBALHASRA</td>
        </tr>
        <tr>
          <td class="td-mono">X-TIMESTAMP</td>
          <td><span class="badge badge-datetime">datetime</span></td>
          <td>Waktu saat ini dalam format ISO 8601. Toleransi &plusmn;5 menit dari server.</td>
          <td class="td-mono">2026-08-16T10:30:00+07:00</td>
        </tr>
        <tr>
          <td class="td-mono">X-SIGNATURE</td>
          <td><span class="badge badge-string">string</span></td>
          <td>HMAC-SHA256(<code>"{CLIENT_KEY}|{TIMESTAMP}"</code>, CLIENT_SECRET) &rarr; hex lowercase</td>
          <td class="td-mono">a3f9d2c1b8e7...</td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- 2.2 Cara Membuat Signature -->
  <div class="section">
    <div class="section-title green">2.2 Cara Membuat Signature (Langkah Demi Langkah)</div>
    <div class="steps-list">
      <div class="step-row">
        <span class="step-num">1</span>
        <span class="step-text"><strong>Siapkan CLIENT_KEY dan CLIENT_SECRET</strong> &mdash;
        Dikonfigurasi via environment variable <code>VA_INQUIRY_CLIENT_KEY</code> dan <code>VA_INQUIRY_CLIENT_SECRET</code> di file <code>.env</code>.</span>
      </div>
      <div class="step-row">
        <span class="step-num">2</span>
        <span class="step-text"><strong>Buat timestamp ISO 8601</strong> &mdash;
        Format: <code>YYYY-MM-DDTHH:mm:ss+07:00</code>. Pastikan waktu server sudah disinkronkan dengan NTP server (toleransi &plusmn;5 menit).</span>
      </div>
      <div class="step-row">
        <span class="step-num">3</span>
        <span class="step-text"><strong>Buat string yang akan di-sign</strong> &mdash;
        Gabungkan CLIENT_KEY dan timestamp dengan separator pipe (<code>|</code>):
        <code>stringToSign = "{CLIENT_KEY}|{TIMESTAMP}"</code></span>
      </div>
      <div class="step-row">
        <span class="step-num">4</span>
        <span class="step-text"><strong>Hitung HMAC-SHA256</strong> &mdash;
        <code>signature = HMAC-SHA256(stringToSign, CLIENT_SECRET)</code>, kemudian encode hasilnya sebagai <strong>hex lowercase</strong>.</span>
      </div>
      <div class="step-row">
        <span class="step-num">5</span>
        <span class="step-text"><strong>Sertakan 3 header dalam setiap request</strong> &mdash;
        <code>X-CLIENT-KEY</code>, <code>X-TIMESTAMP</code>, dan <code>X-SIGNATURE</code> wajib ada di setiap request.</span>
      </div>
    </div>

    <div class="code-label">JavaScript &mdash; Membuat Signature (menggunakan CryptoJS)</div>
    <div class="code-block"><span class="cm">// npm install crypto-js (atau gunakan built-in 'crypto' di Node.js)</span>
<span class="ky">const</span> CryptoJS = <span class="fn">require</span>(<span class="st">'crypto-js'</span>);

<span class="ky">const</span> clientKey    = <span class="st">'PPDBALHASRA'</span>;
<span class="ky">const</span> clientSecret = <span class="st">'AEkvExg92F'</span>;

<span class="cm">// Langkah 2: Buat timestamp ISO 8601</span>
<span class="ky">const</span> timestamp = <span class="ky">new</span> <span class="fn">Date</span>().<span class="fn">toISOString</span>(); <span class="cm">// "2026-08-16T10:30:00.000Z"</span>

<span class="cm">// Langkah 3: Buat string-to-sign</span>
<span class="ky">const</span> stringToSign = <span class="st">`${clientKey}|${timestamp}`</span>;

<span class="cm">// Langkah 4: Hitung HMAC-SHA256</span>
<span class="ky">const</span> signature = CryptoJS.<span class="fn">HmacSHA256</span>(stringToSign, clientSecret)
  .<span class="fn">toString</span>(CryptoJS.enc.Hex); <span class="cm">// hasil hex lowercase</span>

<span class="cm">// Langkah 5: Sertakan di headers</span>
<span class="ky">const</span> headers = {
  <span class="st">'X-CLIENT-KEY'</span> : clientKey,
  <span class="st">'X-TIMESTAMP'</span>  : timestamp,
  <span class="st">'X-SIGNATURE'</span>  : signature,
  <span class="st">'Accept'</span>       : <span class="st">'application/json'</span>,
};</div>

    <div class="code-label">PHP &mdash; Membuat Signature (menggunakan hash_hmac bawaan PHP)</div>
    <div class="code-block"><span class="ky">&lt;?php</span>

<span class="ky">$clientKey</span>    = <span class="st">'PPDBALHASRA'</span>;
<span class="ky">$clientSecret</span> = <span class="st">'AEkvExg92F'</span>;

<span class="cm">// Langkah 2: Buat timestamp ISO 8601</span>
<span class="ky">$timestamp</span>    = (<span class="ky">new</span> <span class="fn">DateTime</span>())-><span class="fn">format</span>(<span class="fn">DateTime</span>::ATOM);
<span class="cm">// Contoh: "2026-08-16T10:30:00+07:00"</span>

<span class="cm">// Langkah 3: Buat string-to-sign</span>
<span class="ky">$stringToSign</span> = <span class="ky">$clientKey</span> . <span class="st">'|'</span> . <span class="ky">$timestamp</span>;

<span class="cm">// Langkah 4: Hitung HMAC-SHA256 (hasil otomatis lowercase hex)</span>
<span class="ky">$signature</span>    = <span class="fn">hash_hmac</span>(<span class="st">'sha256'</span>, <span class="ky">$stringToSign</span>, <span class="ky">$clientSecret</span>);

<span class="cm">// Langkah 5: Sertakan di headers</span>
<span class="ky">$headers</span> = [
  <span class="st">"X-CLIENT-KEY: {$clientKey}"</span>,
  <span class="st">"X-TIMESTAMP: {$timestamp}"</span>,
  <span class="st">"X-SIGNATURE: {$signature}"</span>,
  <span class="st">"Accept: application/json"</span>,
];</div>
  </div>

  <!-- 2.3 Fitur Keamanan -->
  <div class="section no-break">
    <div class="section-title purple">2.3 Fitur Keamanan Tambahan</div>
    <table>
      <thead>
        <tr><th>Fitur</th><th>Implementasi</th><th>Tujuan</th></tr>
      </thead>
      <tbody>
        <tr>
          <td><strong>Anti Replay Attack</strong></td>
          <td>Timestamp window &plusmn;5 menit (konfigurasi)</td>
          <td>Mencegah penggunaan kembali request yang sudah lama</td>
        </tr>
        <tr>
          <td><strong>Anti Timing Attack</strong></td>
          <td>Constant-time comparison (<code>hash_equals()</code>)</td>
          <td>Mencegah analisis waktu respons untuk menebak signature</td>
        </tr>
        <tr>
          <td><strong>Client Key Validation</strong></td>
          <td><code>hash_equals()</code> untuk membandingkan client key</td>
          <td>Memastikan hanya klien terdaftar yang bisa mengakses</td>
        </tr>
        <tr>
          <td><strong>Audit Trail</strong></td>
          <td>Logging setiap request (sukses &amp; gagal) ke <code>laravel.log</code></td>
          <td>Memudahkan investigasi dan pemantauan keamanan</td>
        </tr>
      </tbody>
    </table>

    <div class="alert alert-warning">
      <div class="alert-title">&#9888; Penting: Sinkronisasi Waktu Server</div>
      Pastikan waktu server pengirim request sudah disinkronkan dengan NTP server.
      Perbedaan waktu lebih dari 5 menit akan menyebabkan semua request ditolak dengan kode error
      <strong>4010002</strong> (Timestamp Expired). Gunakan <code>ntpdate pool.ntp.org</code> untuk sinkronisasi.
    </div>
  </div>

  <!-- 2.4 Konfigurasi -->
  <div class="section no-break">
    <div class="section-title cyan">2.4 Konfigurasi Environment Server</div>
    <div class="code-label">.env &mdash; Laravel Server Configuration</div>
    <div class="code-block"><span class="cm"># Konfigurasi VA Inquiry API Authentication</span>
VA_INQUIRY_CLIENT_KEY=PPDBALHASRA
VA_INQUIRY_CLIENT_SECRET=AEkvExg92F
VA_INQUIRY_TIMESTAMP_TOLERANCE=5    <span class="cm"># toleransi dalam menit (default: 5)</span>

<span class="cm"># Nilai ini dibaca di config/services.php:</span>
<span class="cm"># 'va_inquiry' => [</span>
<span class="cm">#   'client_key'          => env('VA_INQUIRY_CLIENT_KEY'),</span>
<span class="cm">#   'client_secret'       => env('VA_INQUIRY_CLIENT_SECRET'),</span>
<span class="cm">#   'timestamp_tolerance' => env('VA_INQUIRY_TIMESTAMP_TOLERANCE', 5),</span>
<span class="cm"># ],</span></div>
  </div>

  <div class="doc-footer">
    PPDB Al-Hasra &bull; Virtual Account API Documentation &bull; v1.0.0 &bull; {{ date('Y') }}
  </div>
</div>

<!-- ============================================================
     HALAMAN 5: RATE LIMITING + ENDPOINT #4
============================================================ -->
<div class="page-break"></div>
<div class="content-page">

  <div class="page-header">
    <div class="page-header-label">Virtual Account API &bull; PPDB Al-Hasra</div>
    <div class="page-header-title">3. Rate Limiting &amp; Environment</div>
  </div>

  <div class="section no-break">
    <div class="section-title blue">3.1 Rate Limiting</div>
    <p>
      Seluruh endpoint yang dilindungi HMAC dibatasi dengan middleware <code>throttle:60,1</code>,
      yaitu <strong>maksimum 60 request per menit per IP address</strong>.
      Jika terlampaui, server akan merespons dengan HTTP <strong>429 Too Many Requests</strong>.
    </p>
    <table>
      <thead><tr><th>Environment</th><th>Base URL</th><th>Keterangan</th></tr></thead>
      <tbody>
        <tr>
          <td>&#128187; Local Dev</td>
          <td class="td-mono">http://localhost:8000</td>
          <td>Jalankan dengan <code>php artisan serve</code> atau <code>composer dev</code></td>
        </tr>
        <tr>
          <td>&#129514; Staging</td>
          <td class="td-mono">https://staging.ppdb.alhasra.sch.id</td>
          <td>Environment pengujian sebelum production</td>
        </tr>
        <tr>
          <td>&#128640; Production</td>
          <td class="td-mono">https://ppdb.alhasra.sch.id</td>
          <td>Environment live &mdash; <strong>wajib HTTPS</strong></td>
        </tr>
      </tbody>
    </table>
    <div class="alert alert-danger">
      <div class="alert-title">&#128274; Wajib HTTPS di Production</div>
      Gunakan HTTPS di production untuk melindungi CLIENT_KEY, CLIENT_SECRET, dan SIGNATURE
      dari serangan man-in-the-middle. Jangan pernah mengirim kredensial melalui koneksi HTTP biasa.
    </div>
  </div>

  <!-- ============================================================
       SECTION 4: ENDPOINT #1 — VA Inquiry GET by Path
  ============================================================ -->
  <div class="page-header" style="margin-top: 10px;">
    <div class="page-header-label">Virtual Account API &bull; PPDB Al-Hasra</div>
    <div class="page-header-title">4. Endpoint: VA Inquiry (GET by Path)</div>
  </div>

  <div class="section">
    <div class="endpoint-card no-break">
      <div class="endpoint-top get-bg">
        <span class="badge-method method-get">GET</span>
        <span class="endpoint-path">/api/va/{va_number}</span>
        <span class="sec-badge sec-hmac">&#128274; HMAC-SHA256 Required</span>
        <div class="endpoint-subdesc">Mengambil data Virtual Account berdasarkan nomor VA di path URL. Cocok untuk integrasi REST murni.</div>
      </div>
      <div class="endpoint-body">
        <div class="endpoint-desc-text">
          Endpoint ini membaca nomor VA langsung dari URL path parameter. Nomor VA adalah string
          numerik hingga 30 karakter. Server akan mencari data payment terbaru dengan status apapun
          yang cocok dengan nomor VA tersebut.
        </div>

        <div class="sub-section">
          <div class="sub-title">Path Parameter</div>
          <table>
            <thead><tr><th>Parameter</th><th>Tipe</th><th>Status</th><th>Keterangan</th><th>Contoh</th></tr></thead>
            <tbody>
              <tr>
                <td class="td-mono">va_number</td>
                <td><span class="badge badge-string">string</span></td>
                <td><span class="badge badge-required">WAJIB</span></td>
                <td>Nomor Virtual Account (maks 30 karakter)</td>
                <td class="td-mono">1234567890</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="sub-section">
          <div class="sub-title">Headers Wajib</div>
          <table>
            <thead><tr><th>Header</th><th>Contoh Nilai</th><th>Keterangan</th></tr></thead>
            <tbody>
              <tr><td class="td-mono">X-CLIENT-KEY</td><td class="td-mono">PPDBALHASRA</td><td>Client identifier</td></tr>
              <tr><td class="td-mono">X-TIMESTAMP</td><td class="td-mono">2026-08-16T10:30:00+07:00</td><td>Timestamp ISO 8601</td></tr>
              <tr><td class="td-mono">X-SIGNATURE</td><td class="td-mono">a3f9d2c1...</td><td>HMAC-SHA256 hex lowercase</td></tr>
              <tr><td class="td-mono">Accept</td><td class="td-mono">application/json</td><td>Format response</td></tr>
            </tbody>
          </table>
        </div>

        <div class="code-label">Contoh Request &mdash; cURL</div>
        <div class="code-block"><span class="url">curl</span> -X GET \
  <span class="url">"http://localhost:8000/api/va/1234567890"</span> \
  -H <span class="st">"X-CLIENT-KEY: PPDBALHASRA"</span> \
  -H <span class="st">"X-TIMESTAMP: 2026-08-16T10:30:00+07:00"</span> \
  -H <span class="st">"X-SIGNATURE: a3f9d2c1b8e74f6a9d2e1c4b7f8a3d5e6c9b2f1a"</span> \
  -H <span class="st">"Accept: application/json"</span></div>

        <div class="code-label">Response Sukses &mdash; <span class="badge-200">200 OK</span></div>
        <div class="code-block">{
  <span class="ky">"status"</span>          : <span class="tr">true</span>,
  <span class="ky">"responseCode"</span>    : <span class="st">"2002500"</span>,
  <span class="ky">"responseMessage"</span> : <span class="st">"Successful"</span>,
  <span class="ky">"message"</span>         : <span class="st">"Data Virtual Account berhasil ditemukan."</span>,
  <span class="ky">"data"</span>            : {
    <span class="ky">"nama_siswa"</span>  : <span class="st">"Ahmad Fauzi Rahman"</span>,
    <span class="ky">"fee_type"</span>    : <span class="st">"Formulir"</span>,   <span class="cm">// Formulir | Daftar Ulang</span>
    <span class="ky">"va"</span>          : <span class="st">"1234567890"</span>,
    <span class="ky">"nominal"</span>     : <span class="nm">500000</span>,         <span class="cm">// Dalam Rupiah (float)</span>
    <span class="ky">"status"</span>      : <span class="st">"pending"</span>         <span class="cm">// pending | success</span>
  }
}</div>

        <div class="code-label">Response Error &mdash; <span class="badge-404">404 Not Found</span> (VA tidak ditemukan)</div>
        <div class="code-block">{
  <span class="ky">"status"</span>          : <span class="fa">false</span>,
  <span class="ky">"responseCode"</span>    : <span class="st">"4042512"</span>,
  <span class="ky">"responseMessage"</span> : <span class="st">"Bill not found"</span>,
  <span class="ky">"message"</span>         : <span class="st">"Data Virtual Account tidak ditemukan."</span>,
  <span class="ky">"data"</span>            : <span class="nu">null</span>
}</div>

        <div class="code-label">Response Error &mdash; <span class="badge-401">401 Unauthorized</span> (signature salah)</div>
        <div class="code-block">{
  <span class="ky">"status"</span>          : <span class="fa">false</span>,
  <span class="ky">"responseCode"</span>    : <span class="st">"4010003"</span>,
  <span class="ky">"responseMessage"</span> : <span class="st">"Unauthorized: X-SIGNATURE tidak valid. Pastikan string-to-sign dan secret key benar."</span>,
  <span class="ky">"data"</span>            : <span class="nu">null</span>
}</div>
      </div>
    </div>
  </div>

  <div class="doc-footer">
    PPDB Al-Hasra &bull; Virtual Account API Documentation &bull; v1.0.0 &bull; {{ date('Y') }}
  </div>
</div>

<!-- ============================================================
     HALAMAN 6: ENDPOINT #2 — VA Inquiry POST/GET by Body
============================================================ -->
<div class="page-break"></div>
<div class="content-page">

  <div class="page-header">
    <div class="page-header-label">Virtual Account API &bull; PPDB Al-Hasra</div>
    <div class="page-header-title">5. Endpoint: VA Inquiry (GET/POST by Body)</div>
  </div>

  <div class="section">
    <div class="endpoint-card no-break">
      <div class="endpoint-top both-bg">
        <span class="badge-method method-both">GET / POST</span>
        <span class="endpoint-path">/api/va/inquiry</span>
        <span class="sec-badge sec-hmac">&#128274; HMAC-SHA256 Required</span>
        <div class="endpoint-subdesc">Mengambil data VA melalui body JSON (POST) atau query string (GET). Mendukung 3 nama parameter untuk kompatibilitas.</div>
      </div>
      <div class="endpoint-body">
        <div class="endpoint-desc-text">
          Endpoint ini menerima nomor VA melalui request body (POST) atau query string (GET).
          Mendukung 3 nama parameter alternatif untuk kompatibilitas dengan berbagai sistem perbankan.
          Parameter pertama yang ditemukan (tidak kosong) akan digunakan.
        </div>

        <div class="alert alert-info">
          <div class="alert-title">&#8505; Prioritas Parameter</div>
          Server membaca parameter dalam urutan: <strong>va</strong> &rarr; <strong>va_number</strong> &rarr; <strong>virtualAccountNumber</strong>.
          Parameter pertama yang tidak kosong akan digunakan sebagai nomor VA.
        </div>

        <div class="sub-section">
          <div class="sub-title">Request Body (POST) atau Query String (GET)</div>
          <table>
            <thead><tr><th>Parameter</th><th>Tipe</th><th>Status</th><th>Keterangan</th></tr></thead>
            <tbody>
              <tr>
                <td class="td-mono">va</td>
                <td><span class="badge badge-string">string</span></td>
                <td><span class="badge badge-required">WAJIB*</span></td>
                <td>Nomor VA (parameter utama)</td>
              </tr>
              <tr>
                <td class="td-mono">va_number</td>
                <td><span class="badge badge-string">string</span></td>
                <td><span class="badge badge-optional">Alternatif</span></td>
                <td>Nama alternatif untuk kompatibilitas gateway lain</td>
              </tr>
              <tr>
                <td class="td-mono">virtualAccountNumber</td>
                <td><span class="badge badge-string">string</span></td>
                <td><span class="badge badge-optional">Alternatif</span></td>
                <td>Format standar BI SNAP untuk bank</td>
              </tr>
            </tbody>
          </table>
          <p style="font-size:11px; color:#64748b;">* Salah satu dari ketiga parameter di atas wajib disertakan.</p>
        </div>

        <div class="code-label">Contoh Request &mdash; POST dengan JSON Body (cURL)</div>
        <div class="code-block"><span class="url">curl</span> -X POST \
  <span class="url">"http://localhost:8000/api/va/inquiry"</span> \
  -H <span class="st">"X-CLIENT-KEY: PPDBALHASRA"</span> \
  -H <span class="st">"X-TIMESTAMP: 2026-08-16T10:30:00+07:00"</span> \
  -H <span class="st">"X-SIGNATURE: a3f9d2c1b8e74f6a9d2e1c4b7f8a3d5e6c9b2f1a"</span> \
  -H <span class="st">"Accept: application/json"</span> \
  -H <span class="st">"Content-Type: application/json"</span> \
  -d <span class="st">'{"va": "1234567890"}'</span></div>

        <div class="code-label">Contoh Request &mdash; GET dengan Query String (cURL)</div>
        <div class="code-block"><span class="url">curl</span> -X GET \
  <span class="url">"http://localhost:8000/api/va/inquiry?va=1234567890"</span> \
  -H <span class="st">"X-CLIENT-KEY: PPDBALHASRA"</span> \
  -H <span class="st">"X-TIMESTAMP: 2026-08-16T10:30:00+07:00"</span> \
  -H <span class="st">"X-SIGNATURE: a3f9d2c1b8e74f6a9d2e1c4b7f8a3d5e6c9b2f1a"</span> \
  -H <span class="st">"Accept: application/json"</span></div>

        <div class="code-label">Contoh Request &mdash; Format BI SNAP (virtualAccountNumber)</div>
        <div class="code-block"><span class="url">curl</span> -X POST \
  <span class="url">"http://localhost:8000/api/va/inquiry"</span> \
  -H <span class="st">"X-CLIENT-KEY: PPDBALHASRA"</span> \
  -H <span class="st">"X-TIMESTAMP: 2026-08-16T10:30:00+07:00"</span> \
  -H <span class="st">"X-SIGNATURE: a3f9d2c1b8e74f6a9d2e1c4b7f8a3d5e6c9b2f1a"</span> \
  -H <span class="st">"Content-Type: application/json"</span> \
  -d <span class="st">'{"virtualAccountNumber": "1234567890"}'</span></div>

        <div class="code-label">Response Sukses &mdash; <span class="badge-200">200 OK</span></div>
        <div class="code-block">{
  <span class="ky">"status"</span>          : <span class="tr">true</span>,
  <span class="ky">"responseCode"</span>    : <span class="st">"2002500"</span>,
  <span class="ky">"responseMessage"</span> : <span class="st">"Successful"</span>,
  <span class="ky">"message"</span>         : <span class="st">"Data Virtual Account berhasil ditemukan."</span>,
  <span class="ky">"data"</span>            : {
    <span class="ky">"nama_siswa"</span>  : <span class="st">"Ahmad Fauzi Rahman"</span>,
    <span class="ky">"fee_type"</span>    : <span class="st">"Formulir"</span>,
    <span class="ky">"va"</span>          : <span class="st">"1234567890"</span>,
    <span class="ky">"nominal"</span>     : <span class="nm">500000</span>,
    <span class="ky">"status"</span>      : <span class="st">"pending"</span>
  }
}</div>

        <div class="code-label">Response Error &mdash; <span class="badge-400">400 Bad Request</span> (parameter tidak diberikan)</div>
        <div class="code-block">{
  <span class="ky">"status"</span>          : <span class="fa">false</span>,
  <span class="ky">"responseCode"</span>    : <span class="st">"4002500"</span>,
  <span class="ky">"responseMessage"</span> : <span class="st">"Bad Request: Nomor Virtual Account (va) tidak diberikan."</span>,
  <span class="ky">"message"</span>         : <span class="st">"Parameter va / va_number / virtualAccountNumber wajib diisi."</span>,
  <span class="ky">"data"</span>            : <span class="nu">null</span>
}</div>
      </div>
    </div>
  </div>

  <div class="doc-footer">
    PPDB Al-Hasra &bull; Virtual Account API Documentation &bull; v1.0.0 &bull; {{ date('Y') }}
  </div>
</div>

<!-- ============================================================
     HALAMAN 7: ENDPOINT #3 — VA Payment Notify
============================================================ -->
<div class="page-break"></div>
<div class="content-page">

  <div class="page-header">
    <div class="page-header-label">Virtual Account API &bull; PPDB Al-Hasra</div>
    <div class="page-header-title">6. Endpoint: VA Payment Notification</div>
  </div>

  <div class="section">
    <div class="endpoint-card">
      <div class="endpoint-top post-bg">
        <span class="badge-method method-post">POST</span>
        <span class="endpoint-path">/api/va/payment-notify</span>
        <span class="sec-badge sec-hmac">&#128274; HMAC-SHA256 Required</span>
        <div class="endpoint-subdesc">Menerima notifikasi pembayaran dari bank/gateway dan mengupdate status VA secara otomatis.</div>
      </div>
      <div class="endpoint-body">
        <div class="endpoint-desc-text">
          Endpoint ini menerima notifikasi pembayaran Virtual Account dari bank atau payment gateway.
          Server memvalidasi data, memverifikasi kesesuaian nominal (toleransi &plusmn;1 rupiah untuk pembulatan),
          lalu mengupdate status payment dan registration siswa secara otomatis.
        </div>

        <div class="sub-section">
          <div class="sub-title">6.1 Request Body (JSON) &mdash; Wajib: Content-Type: application/json</div>
          <table>
            <thead><tr><th>Parameter</th><th>Tipe</th><th>Status</th><th>Validasi</th><th>Keterangan</th></tr></thead>
            <tbody>
              <tr>
                <td class="td-mono">va_number</td>
                <td><span class="badge badge-string">string</span></td>
                <td><span class="badge badge-required">WAJIB</span></td>
                <td>max:30 karakter</td>
                <td>Nomor Virtual Account</td>
              </tr>
              <tr>
                <td class="td-mono">amount</td>
                <td><span class="badge badge-numeric">numeric</span></td>
                <td><span class="badge badge-required">WAJIB</span></td>
                <td>angka positif, min:1</td>
                <td>Nominal yang dibayarkan (Rupiah)</td>
              </tr>
              <tr>
                <td class="td-mono">paid_at</td>
                <td><span class="badge badge-datetime">datetime</span></td>
                <td><span class="badge badge-required">WAJIB</span></td>
                <td>format: date/datetime</td>
                <td>Waktu pembayaran (ISO 8601 atau Y-m-d H:i:s)</td>
              </tr>
              <tr>
                <td class="td-mono">va_ref</td>
                <td><span class="badge badge-string">string</span></td>
                <td><span class="badge badge-optional">Opsional</span></td>
                <td>max:100 karakter</td>
                <td>Nomor referensi transaksi dari bank</td>
              </tr>
              <tr>
                <td class="td-mono">notes</td>
                <td><span class="badge badge-string">string</span></td>
                <td><span class="badge badge-optional">Opsional</span></td>
                <td>max:500 karakter</td>
                <td>Catatan tambahan dari bank</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="sub-section no-break">
          <div class="sub-title">6.2 Logika Pemrosesan</div>
          <div class="flow-row">
            <span class="flow-step-box" style="background:#7c3aed;">1. Validasi Input</span>
            <span class="flow-arrow">&rarr;</span>
            <span class="flow-step-box" style="background:#1d4ed8;">2. Cari VA Pending</span>
            <span class="flow-arrow">&rarr;</span>
            <span class="flow-step-box" style="background:#0369a1;">3. Cek Nominal &plusmn;1</span>
            <span class="flow-arrow">&rarr;</span>
            <span class="flow-step-box" style="background:#047857;">4. Update Status</span>
            <span class="flow-arrow">&rarr;</span>
            <span class="flow-step-box" style="background:#065f46;">5. Return Sukses</span>
          </div>
          <ul>
            <li>Validasi input &mdash; semua field wajib harus ada dan valid</li>
            <li>Mencari record payment dengan <code>va_number</code> yang cocok dan status <code>pending</code></li>
            <li>Jika sudah pernah dibayar (status <code>success</code>), kembalikan respons idempoten 200 tanpa mengubah data</li>
            <li>Validasi kesesuaian nominal dengan toleransi &plusmn;1 rupiah untuk menangani pembulatan</li>
            <li>Update status payment ke <code>success</code> dan isi <code>verified_at</code>, <code>va_ref</code>, <code>admin_note</code></li>
            <li>Jika biaya ini adalah <code>sort_order == 1</code> (Formulir), update juga <code>payment_status</code> di tabel registration</li>
          </ul>
          <div class="alert alert-info">
            <div class="alert-title">&#128161; Toleransi Nominal &plusmn;1 Rupiah</div>
            Server memvalidasi: <code>abs(amount - expectedAmount) &lt;= 1</code>.
            Toleransi ini mengakomodasi perbedaan pembulatan kecil antar sistem bank.
            Jika selisih lebih dari 1 rupiah, request akan ditolak dengan kode <strong>4002501</strong>.
          </div>
        </div>

        <div class="code-label">Contoh Request &mdash; cURL (lengkap)</div>
        <div class="code-block"><span class="url">curl</span> -X POST \
  <span class="url">"http://localhost:8000/api/va/payment-notify"</span> \
  -H <span class="st">"X-CLIENT-KEY: PPDBALHASRA"</span> \
  -H <span class="st">"X-TIMESTAMP: 2026-08-16T10:30:00+07:00"</span> \
  -H <span class="st">"X-SIGNATURE: a3f9d2c1b8e74f6a9d2e1c4b7f8a3d5e6c9b2f1a"</span> \
  -H <span class="st">"Accept: application/json"</span> \
  -H <span class="st">"Content-Type: application/json"</span> \
  -d '{
    <span class="ky">"va_number"</span> : <span class="st">"1234567890"</span>,
    <span class="ky">"amount"</span>    : <span class="nm">500000</span>,
    <span class="ky">"paid_at"</span>   : <span class="st">"2026-08-16T10:25:00+07:00"</span>,
    <span class="ky">"va_ref"</span>    : <span class="st">"TXN-BTN-20260816-001"</span>,
    <span class="ky">"notes"</span>     : <span class="st">"Pembayaran Formulir PPDB 2026"</span>
  }'</div>

        <div class="code-label">Response &mdash; <span class="badge-200">200 OK</span> Pembayaran Berhasil Diproses</div>
        <div class="code-block">{
  <span class="ky">"status"</span>          : <span class="tr">true</span>,
  <span class="ky">"responseCode"</span>    : <span class="st">"2002500"</span>,
  <span class="ky">"responseMessage"</span> : <span class="st">"Successful"</span>,
  <span class="ky">"message"</span>         : <span class="st">"Pembayaran berhasil diproses."</span>,
  <span class="ky">"data"</span>            : {
    <span class="ky">"payment_id"</span>   : <span class="nm">42</span>,
    <span class="ky">"va_number"</span>    : <span class="st">"1234567890"</span>,
    <span class="ky">"nama_siswa"</span>   : <span class="st">"Ahmad Fauzi Rahman"</span>,
    <span class="ky">"fee_type"</span>     : <span class="st">"Formulir"</span>,
    <span class="ky">"amount"</span>       : <span class="nm">500000</span>,        <span class="cm">// Tagihan awal</span>
    <span class="ky">"paid_amount"</span>  : <span class="nm">500000</span>,        <span class="cm">// Yang dibayarkan</span>
    <span class="ky">"paid_at"</span>      : <span class="st">"2026-08-16T10:25:00+07:00"</span>,
    <span class="ky">"status"</span>       : <span class="st">"success"</span>
  }
}</div>

        <div class="code-label">Response &mdash; <span class="badge-200">200 OK</span> Sudah Dibayar Sebelumnya (Idempoten)</div>
        <div class="code-block">{
  <span class="ky">"status"</span>          : <span class="tr">true</span>,
  <span class="ky">"responseCode"</span>    : <span class="st">"2002501"</span>,
  <span class="ky">"responseMessage"</span> : <span class="st">"Payment Already Processed"</span>,
  <span class="ky">"message"</span>         : <span class="st">"Pembayaran untuk VA ini sudah diproses sebelumnya."</span>,
  <span class="ky">"data"</span>            : <span class="nu">null</span>
}</div>

        <div class="code-label">Response &mdash; <span class="badge-422">422</span> Nominal Tidak Sesuai</div>
        <div class="code-block">{
  <span class="ky">"status"</span>          : <span class="fa">false</span>,
  <span class="ky">"responseCode"</span>    : <span class="st">"4002501"</span>,
  <span class="ky">"responseMessage"</span> : <span class="st">"Bad Request: Nominal tidak sesuai."</span>,
  <span class="ky">"message"</span>         : <span class="st">"Nominal yang dibayarkan (Rp 450.000) tidak sesuai dengan tagihan (Rp 500.000)."</span>,
  <span class="ky">"data"</span>            : <span class="nu">null</span>
}</div>

        <div class="code-label">Response &mdash; <span class="badge-422">422</span> Validasi Input Gagal</div>
        <div class="code-block">{
  <span class="ky">"status"</span>          : <span class="fa">false</span>,
  <span class="ky">"responseCode"</span>    : <span class="st">"4002500"</span>,
  <span class="ky">"responseMessage"</span> : <span class="st">"Bad Request: Data tidak valid."</span>,
  <span class="ky">"errors"</span>          : {
    <span class="ky">"va_number"</span>  : [<span class="st">"Nomor Virtual Account (va_number) wajib diisi."</span>],
    <span class="ky">"amount"</span>     : [<span class="st">"Nominal pembayaran (amount) wajib diisi."</span>],
    <span class="ky">"paid_at"</span>    : [<span class="st">"Waktu pembayaran (paid_at) wajib diisi."</span>]
  },
  <span class="ky">"data"</span>            : <span class="nu">null</span>
}</div>
      </div>
    </div>
  </div>

  <div class="doc-footer">
    PPDB Al-Hasra &bull; Virtual Account API Documentation &bull; v1.0.0 &bull; {{ date('Y') }}
  </div>
</div>

<!-- ============================================================
     HALAMAN 8: ENDPOINT #4 — BTN Callback + Error Codes
============================================================ -->
<div class="page-break"></div>
<div class="content-page">

  <div class="page-header">
    <div class="page-header-label">Virtual Account API &bull; PPDB Al-Hasra</div>
    <div class="page-header-title">7. Endpoint: BTN Legacy Callback</div>
  </div>

  <div class="section">
    <div class="endpoint-card no-break">
      <div class="endpoint-top post-bg" style="background:#1e3320;">
        <span class="badge-method method-post">POST</span>
        <span class="endpoint-path">/api/btn/callback</span>
        <span class="sec-badge sec-pub">&#127759; Public &mdash; Tanpa Auth</span>
        <div class="endpoint-subdesc">Webhook yang menerima notifikasi pembayaran langsung dari sistem Legacy Bank BTN. Tidak memerlukan HMAC.</div>
      </div>
      <div class="endpoint-body">
        <div class="endpoint-desc-text">
          Endpoint ini dipanggil langsung oleh sistem backend Bank BTN saat terjadi pembayaran.
          Karena endpoint ini bersifat publik (tidak ada autentikasi HMAC), keamanan dilakukan di level
          jaringan (firewall / IP whitelist server BTN).
        </div>

        <div class="alert alert-warning">
          <div class="alert-title">&#9888; Keamanan Jaringan untuk Endpoint Publik</div>
          Karena tidak ada autentikasi HMAC, pastikan hanya IP server BTN yang diizinkan mengakses
          endpoint ini melalui konfigurasi firewall, Nginx <code>allow</code> directive,
          atau Laravel Trusted Proxies.
        </div>

        <div class="sub-section">
          <div class="sub-title">Request Body (JSON atau Form)</div>
          <table>
            <thead><tr><th>Parameter</th><th>Tipe</th><th>Status</th><th>Keterangan</th></tr></thead>
            <tbody>
              <tr>
                <td class="td-mono">va</td>
                <td><span class="badge badge-string">string</span></td>
                <td><span class="badge badge-required">WAJIB</span></td>
                <td>Nomor Virtual Account</td>
              </tr>
              <tr>
                <td class="td-mono">ref</td>
                <td><span class="badge badge-string">string</span></td>
                <td><span class="badge badge-optional">Opsional</span></td>
                <td>Nomor referensi transaksi dari BTN</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="sub-section">
          <div class="sub-title">Format Response (Format Khusus BTN Legacy)</div>
          <table>
            <thead><tr><th>Field</th><th>Tipe</th><th>Keterangan</th></tr></thead>
            <tbody>
              <tr><td class="td-mono">rsp</td><td><span class="badge badge-string">string</span></td><td><code>"000"</code> = sukses, <code>"001"</code> = gagal</td></tr>
              <tr><td class="td-mono">rspdesc</td><td><span class="badge badge-string">string</span></td><td>Deskripsi hasil transaksi</td></tr>
            </tbody>
          </table>
        </div>

        <div class="code-label">Contoh Request &mdash; Simulasi BTN Callback (cURL)</div>
        <div class="code-block"><span class="url">curl</span> -X POST \
  <span class="url">"http://localhost:8000/api/btn/callback"</span> \
  -H <span class="st">"Content-Type: application/json"</span> \
  -d <span class="st">'{"va": "1234567890", "ref": "BTN-REF-20260816"}'</span></div>

        <div class="code-label">Response &mdash; rsp:000 Transaksi Sukses</div>
        <div class="code-block">{ <span class="ky">"rsp"</span>: <span class="st">"000"</span>, <span class="ky">"rspdesc"</span>: <span class="st">"Transaction Success"</span> }

<span class="cm">// Jika sudah pernah diproses:</span>
{ <span class="ky">"rsp"</span>: <span class="st">"000"</span>, <span class="ky">"rspdesc"</span>: <span class="st">"Transaction Already Processed"</span> }</div>

        <div class="code-label">Response &mdash; rsp:001 Gagal</div>
        <div class="code-block">{ <span class="ky">"rsp"</span>: <span class="st">"001"</span>, <span class="ky">"rspdesc"</span>: <span class="st">"Payment Record Not Found"</span> }

<span class="cm">// Kemungkinan nilai rspdesc lainnya:</span>
<span class="cm">// "Transaction Failed"  — Payload kosong atau tidak valid</span>
<span class="cm">// "VA Number Missing"   — Field 'va' tidak ada</span></div>
      </div>
    </div>
  </div>

  <!-- ============================================================
       SECTION 8: KODE RESPONS
  ============================================================ -->
  <div class="page-header" style="margin-top:10px;">
    <div class="page-header-label">Virtual Account API &bull; PPDB Al-Hasra</div>
    <div class="page-header-title">8. Kode Respons &amp; Error Handling</div>
  </div>

  <div class="section">
    <div class="section-title blue">8.1 Tabel Kode Respons Lengkap</div>
    <table>
      <thead class="rc-thead">
        <tr><th>responseCode</th><th>HTTP Status</th><th>Keterangan</th><th>Endpoint</th></tr>
      </thead>
      <tbody>
        <tr><td><span class="badge-200">2002500</span></td><td>200</td><td>Sukses &mdash; Data ditemukan atau pembayaran diproses</td><td>Inquiry, Payment Notify</td></tr>
        <tr><td><span class="badge-200">2002501</span></td><td>200</td><td>Pembayaran sudah diproses sebelumnya (idempoten)</td><td>Payment Notify</td></tr>
        <tr><td><span class="badge-400">4000001</span></td><td>400</td><td>Format X-TIMESTAMP tidak valid (bukan ISO 8601)</td><td>Auth Middleware</td></tr>
        <tr><td><span class="badge-400">4002500</span></td><td>400/422</td><td>Parameter wajib tidak diberikan atau validasi gagal</td><td>Inquiry, Payment Notify</td></tr>
        <tr><td><span class="badge-400">4002501</span></td><td>422</td><td>Nominal yang dibayarkan tidak sesuai tagihan (selisih &gt;1 rupiah)</td><td>Payment Notify</td></tr>
        <tr><td><span class="badge-401">4010000</span></td><td>401</td><td>Header X-CLIENT-KEY, X-TIMESTAMP, atau X-SIGNATURE tidak ada</td><td>Auth Middleware</td></tr>
        <tr><td><span class="badge-401">4010001</span></td><td>401</td><td>X-CLIENT-KEY tidak valid atau tidak dikenali sistem</td><td>Auth Middleware</td></tr>
        <tr><td><span class="badge-401">4010002</span></td><td>401</td><td>X-TIMESTAMP kadaluarsa &mdash; selisih lebih dari 5 menit</td><td>Auth Middleware</td></tr>
        <tr><td><span class="badge-401">4010003</span></td><td>401</td><td>X-SIGNATURE tidak valid &mdash; tidak cocok dengan yang diharapkan</td><td>Auth Middleware</td></tr>
        <tr><td><span class="badge-404">4042512</span></td><td>404</td><td>Nomor VA tidak ditemukan di database</td><td>Inquiry, Payment Notify</td></tr>
        <tr><td><span class="badge-429">429</span></td><td>429</td><td>Rate limit terlampaui &mdash; maks 60 request/menit per IP</td><td>Semua endpoint HMAC</td></tr>
      </tbody>
    </table>

    <div class="sub-section no-break">
      <div class="sub-title">8.2 Format Respons Error Standar</div>
      <div class="code-block">{
  <span class="ky">"status"</span>          : <span class="fa">false</span>,       <span class="cm">// selalu false untuk semua error</span>
  <span class="ky">"responseCode"</span>    : <span class="st">"4010003"</span>,  <span class="cm">// kode error spesifik</span>
  <span class="ky">"responseMessage"</span> : <span class="st">"Unauthorized: X-SIGNATURE tidak valid. Pastikan string-to-sign dan secret key benar."</span>,
  <span class="ky">"data"</span>            : <span class="nu">null</span>          <span class="cm">// selalu null untuk error</span>
}</div>
    </div>
  </div>

  <div class="doc-footer">
    PPDB Al-Hasra &bull; Virtual Account API Documentation &bull; v1.0.0 &bull; {{ date('Y') }}
  </div>
</div>

<!-- ============================================================
     HALAMAN 9: CONTOH IMPLEMENTASI — PHP
============================================================ -->
<div class="page-break"></div>
<div class="content-page">

  <div class="page-header">
    <div class="page-header-label">Virtual Account API &bull; PPDB Al-Hasra</div>
    <div class="page-header-title">9. Contoh Implementasi Lengkap</div>
  </div>

  <!-- 9.1 PHP -->
  <div class="section">
    <div class="section-title blue">9.1 PHP (cURL) &mdash; Inquiry &amp; Payment Notify</div>

    <div class="code-label">PHP &mdash; VA Inquiry (GET by Path)</div>
    <div class="code-block"><span class="ky">&lt;?php</span>

<span class="ky">function</span> <span class="fn">vaInquiry</span>(<span class="ky">string</span> <span class="ky">$vaNumber</span>): array {
    <span class="ky">$baseUrl</span>      = <span class="st">'http://localhost:8000'</span>;
    <span class="ky">$clientKey</span>    = <span class="st">'PPDBALHASRA'</span>;
    <span class="ky">$clientSecret</span> = <span class="st">'AEkvExg92F'</span>;

    <span class="cm">// 1. Generate timestamp dan signature</span>
    <span class="ky">$timestamp</span>    = (<span class="ky">new</span> <span class="fn">DateTime</span>())-&gt;<span class="fn">format</span>(<span class="fn">DateTime</span>::ATOM);
    <span class="ky">$signature</span>    = <span class="fn">hash_hmac</span>(<span class="st">'sha256'</span>, <span class="ky">$clientKey</span>.<span class="st">'|'</span>.<span class="ky">$timestamp</span>, <span class="ky">$clientSecret</span>);

    <span class="cm">// 2. Kirim request</span>
    <span class="ky">$ch</span> = <span class="fn">curl_init</span>();
    <span class="fn">curl_setopt_array</span>(<span class="ky">$ch</span>, [
        CURLOPT_URL            =&gt; <span class="ky">$baseUrl</span>.<span class="st">"/api/va/{$vaNumber}"</span>,
        CURLOPT_RETURNTRANSFER =&gt; <span class="tr">true</span>,
        CURLOPT_TIMEOUT        =&gt; <span class="nm">15</span>,
        CURLOPT_HTTPHEADER     =&gt; [
            <span class="st">"X-CLIENT-KEY: {$clientKey}"</span>,
            <span class="st">"X-TIMESTAMP: {$timestamp}"</span>,
            <span class="st">"X-SIGNATURE: {$signature}"</span>,
            <span class="st">"Accept: application/json"</span>,
        ],
    ]);
    <span class="ky">$body</span>     = <span class="fn">curl_exec</span>(<span class="ky">$ch</span>);
    <span class="ky">$httpCode</span> = <span class="fn">curl_getinfo</span>(<span class="ky">$ch</span>, CURLINFO_HTTP_CODE);
    <span class="fn">curl_close</span>(<span class="ky">$ch</span>);

    <span class="ky">return</span> [<span class="st">'http_code'</span> =&gt; <span class="ky">$httpCode</span>, <span class="st">'body'</span> =&gt; <span class="fn">json_decode</span>(<span class="ky">$body</span>, <span class="tr">true</span>)];
}

<span class="cm">// --- Penggunaan ---</span>
<span class="ky">$result</span> = <span class="fn">vaInquiry</span>(<span class="st">'1234567890'</span>);
<span class="ky">if</span> (<span class="ky">$result</span>[<span class="st">'http_code'</span>] === <span class="nm">200</span> &amp;&amp; <span class="ky">$result</span>[<span class="st">'body'</span>][<span class="st">'status'</span>]) {
    <span class="ky">$data</span> = <span class="ky">$result</span>[<span class="st">'body'</span>][<span class="st">'data'</span>];
    <span class="fn">echo</span> <span class="st">"Nama  : "</span> . <span class="ky">$data</span>[<span class="st">'nama_siswa'</span>]  . PHP_EOL;
    <span class="fn">echo</span> <span class="st">"Biaya : Rp "</span> . <span class="fn">number_format</span>(<span class="ky">$data</span>[<span class="st">'nominal'</span>], <span class="nm">0</span>, <span class="st">','</span>, <span class="st">'.'</span>) . PHP_EOL;
    <span class="fn">echo</span> <span class="st">"Status: "</span> . <span class="ky">$data</span>[<span class="st">'status'</span>] . PHP_EOL;
} <span class="ky">else</span> {
    <span class="fn">echo</span> <span class="st">"Error: "</span> . <span class="ky">$result</span>[<span class="st">'body'</span>][<span class="st">'responseMessage'</span>];
}</div>

    <div class="code-label">PHP &mdash; VA Payment Notification</div>
    <div class="code-block"><span class="ky">function</span> <span class="fn">sendPaymentNotify</span>(<span class="ky">string</span> <span class="ky">$vaNumber</span>, <span class="ky">float</span> <span class="ky">$amount</span>, <span class="ky">string</span> <span class="ky">$paidAt</span>, <span class="ky">string</span> <span class="ky">$vaRef</span> = <span class="st">''</span>): array {
    <span class="ky">$baseUrl</span>      = <span class="st">'http://localhost:8000'</span>;
    <span class="ky">$clientKey</span>    = <span class="st">'PPDBALHASRA'</span>;
    <span class="ky">$clientSecret</span> = <span class="st">'AEkvExg92F'</span>;

    <span class="ky">$timestamp</span> = (<span class="ky">new</span> <span class="fn">DateTime</span>())-&gt;<span class="fn">format</span>(<span class="fn">DateTime</span>::ATOM);
    <span class="ky">$signature</span> = <span class="fn">hash_hmac</span>(<span class="st">'sha256'</span>, <span class="ky">$clientKey</span>.<span class="st">'|'</span>.<span class="ky">$timestamp</span>, <span class="ky">$clientSecret</span>);

    <span class="ky">$payload</span> = <span class="fn">json_encode</span>([
        <span class="st">'va_number'</span> =&gt; <span class="ky">$vaNumber</span>,
        <span class="st">'amount'</span>    =&gt; <span class="ky">$amount</span>,
        <span class="st">'paid_at'</span>   =&gt; <span class="ky">$paidAt</span>,
        <span class="st">'va_ref'</span>    =&gt; <span class="ky">$vaRef</span>,
        <span class="st">'notes'</span>     =&gt; <span class="st">'Notifikasi otomatis dari sistem'</span>,
    ]);

    <span class="ky">$ch</span> = <span class="fn">curl_init</span>();
    <span class="fn">curl_setopt_array</span>(<span class="ky">$ch</span>, [
        CURLOPT_URL            =&gt; <span class="ky">$baseUrl</span> . <span class="st">'/api/va/payment-notify'</span>,
        CURLOPT_POST           =&gt; <span class="tr">true</span>,
        CURLOPT_POSTFIELDS     =&gt; <span class="ky">$payload</span>,
        CURLOPT_RETURNTRANSFER =&gt; <span class="tr">true</span>,
        CURLOPT_HTTPHEADER     =&gt; [
            <span class="st">"X-CLIENT-KEY: {$clientKey}"</span>,
            <span class="st">"X-TIMESTAMP: {$timestamp}"</span>,
            <span class="st">"X-SIGNATURE: {$signature}"</span>,
            <span class="st">"Content-Type: application/json"</span>,
            <span class="st">"Accept: application/json"</span>,
        ],
    ]);
    <span class="ky">$body</span>     = <span class="fn">curl_exec</span>(<span class="ky">$ch</span>);
    <span class="ky">$httpCode</span> = <span class="fn">curl_getinfo</span>(<span class="ky">$ch</span>, CURLINFO_HTTP_CODE);
    <span class="fn">curl_close</span>(<span class="ky">$ch</span>);
    <span class="ky">return</span> [<span class="st">'http_code'</span> =&gt; <span class="ky">$httpCode</span>, <span class="st">'body'</span> =&gt; <span class="fn">json_decode</span>(<span class="ky">$body</span>, <span class="tr">true</span>)];
}

<span class="cm">// --- Penggunaan ---</span>
<span class="ky">$r</span> = <span class="fn">sendPaymentNotify</span>(<span class="st">'1234567890'</span>, <span class="nm">500000</span>, <span class="st">'2026-08-16T10:25:00+07:00'</span>, <span class="st">'TXN-001'</span>);
<span class="fn">echo</span> <span class="ky">$r</span>[<span class="st">'body'</span>][<span class="st">'message'</span>]; <span class="cm">// "Pembayaran berhasil diproses."</span></div>
  </div>

  <div class="doc-footer">
    PPDB Al-Hasra &bull; Virtual Account API Documentation &bull; v1.0.0 &bull; {{ date('Y') }}
  </div>
</div>

<!-- ============================================================
     HALAMAN 10: IMPLEMENTASI — JavaScript & Python
============================================================ -->
<div class="page-break"></div>
<div class="content-page">

  <div class="page-header">
    <div class="page-header-label">Virtual Account API &bull; PPDB Al-Hasra</div>
    <div class="page-header-title">9. Contoh Implementasi (lanjutan)</div>
  </div>

  <!-- 9.2 JavaScript -->
  <div class="section">
    <div class="section-title green">9.2 JavaScript (Node.js) &mdash; Inquiry &amp; Payment Notify</div>

    <div class="code-label">JavaScript &mdash; VA Inquiry &amp; Payment Notify (Node.js v18+, built-in crypto)</div>
    <div class="code-block"><span class="ky">const</span> crypto = <span class="fn">require</span>(<span class="st">'crypto'</span>);

<span class="ky">const</span> BASE_URL      = <span class="st">'http://localhost:8000'</span>;
<span class="ky">const</span> CLIENT_KEY    = <span class="st">'PPDBALHASRA'</span>;
<span class="ky">const</span> CLIENT_SECRET = <span class="st">'AEkvExg92F'</span>;

<span class="cm">/** Buat headers HMAC-SHA256 untuk setiap request */</span>
<span class="ky">function</span> <span class="fn">makeHeaders</span>(extraHeaders = {}) {
    <span class="ky">const</span> timestamp    = <span class="ky">new</span> <span class="fn">Date</span>().<span class="fn">toISOString</span>();
    <span class="ky">const</span> stringToSign = <span class="st">`${CLIENT_KEY}|${timestamp}`</span>;
    <span class="ky">const</span> signature    = crypto
        .<span class="fn">createHmac</span>(<span class="st">'sha256'</span>, CLIENT_SECRET)
        .<span class="fn">update</span>(stringToSign)
        .<span class="fn">digest</span>(<span class="st">'hex'</span>);
    <span class="ky">return</span> {
        <span class="st">'X-CLIENT-KEY'</span> : CLIENT_KEY,
        <span class="st">'X-TIMESTAMP'</span>  : timestamp,
        <span class="st">'X-SIGNATURE'</span>  : signature,
        <span class="st">'Accept'</span>       : <span class="st">'application/json'</span>,
        ...extraHeaders,
    };
}

<span class="cm">/** VA Inquiry by path parameter */</span>
<span class="ky">async function</span> <span class="fn">vaInquiry</span>(vaNumber) {
    <span class="ky">const</span> response = <span class="ky">await</span> <span class="fn">fetch</span>(<span class="st">`${BASE_URL}/api/va/${vaNumber}`</span>, {
        headers: <span class="fn">makeHeaders</span>(),
    });
    <span class="ky">const</span> data = <span class="ky">await</span> response.<span class="fn">json</span>();
    <span class="ky">if</span> (data.status) {
        console.<span class="fn">log</span>(<span class="st">`Nama  : ${data.data.nama_siswa}`</span>);
        console.<span class="fn">log</span>(<span class="st">`Biaya : Rp ${data.data.nominal.toLocaleString('id-ID')}`</span>);
        console.<span class="fn">log</span>(<span class="st">`Status: ${data.data.status}`</span>);
    } <span class="ky">else</span> {
        console.<span class="fn">error</span>(<span class="st">`Error: ${data.responseMessage}`</span>);
    }
    <span class="ky">return</span> data;
}

<span class="cm">/** VA Payment Notification */</span>
<span class="ky">async function</span> <span class="fn">sendPaymentNotify</span>(vaNumber, amount, paidAt, vaRef = <span class="nu">null</span>) {
    <span class="ky">const</span> payload = { va_number: vaNumber, amount, paid_at: paidAt };
    <span class="ky">if</span> (vaRef) payload.va_ref = vaRef;

    <span class="ky">const</span> response = <span class="ky">await</span> <span class="fn">fetch</span>(<span class="st">`${BASE_URL}/api/va/payment-notify`</span>, {
        method  : <span class="st">'POST'</span>,
        headers : <span class="fn">makeHeaders</span>({ <span class="st">'Content-Type'</span>: <span class="st">'application/json'</span> }),
        body    : <span class="fn">JSON.stringify</span>(payload),
    });
    <span class="ky">const</span> data = <span class="ky">await</span> response.<span class="fn">json</span>();
    console.<span class="fn">log</span>(<span class="st">`[${data.responseCode}] ${data.message}`</span>);
    <span class="ky">return</span> data;
}

<span class="cm">// ---- Contoh penggunaan ----</span>
(async () => {
    <span class="cm">// Inquiry VA</span>
    <span class="ky">await</span> <span class="fn">vaInquiry</span>(<span class="st">'1234567890'</span>);

    <span class="cm">// Kirim notifikasi pembayaran</span>
    <span class="ky">await</span> <span class="fn">sendPaymentNotify</span>(
        <span class="st">'1234567890'</span>,                    <span class="cm">// va_number</span>
        <span class="nm">500000</span>,                          <span class="cm">// amount (Rupiah)</span>
        <span class="st">'2026-08-16T10:25:00+07:00'</span>,    <span class="cm">// paid_at</span>
        <span class="st">'TXN-BTN-20260816-001'</span>           <span class="cm">// va_ref (opsional)</span>
    );
})();</div>
  </div>

  <!-- 9.3 Python -->
  <div class="section">
    <div class="section-title purple">9.3 Python (requests) &mdash; Inquiry &amp; Payment Notify</div>

    <div class="code-label">Python 3 &mdash; pip install requests</div>
    <div class="code-block"><span class="ky">import</span> hmac, hashlib, requests
<span class="ky">from</span> datetime <span class="ky">import</span> datetime, timezone, timedelta

BASE_URL      = <span class="st">'http://localhost:8000'</span>
CLIENT_KEY    = <span class="st">'PPDBALHASRA'</span>
CLIENT_SECRET = <span class="st">'AEkvExg92F'</span>

<span class="ky">def</span> <span class="fn">make_headers</span>() -> dict:
    <span class="st">"""Generate auth headers dengan HMAC-SHA256 signature."""</span>
    tz  = timezone(timedelta(hours=<span class="nm">7</span>))
    ts  = datetime.<span class="fn">now</span>(tz).<span class="fn">isoformat</span>(timespec=<span class="st">'seconds'</span>)
    sig = hmac.<span class="fn">new</span>(
        CLIENT_SECRET.<span class="fn">encode</span>(<span class="st">'utf-8'</span>),
        f<span class="st">"{CLIENT_KEY}|{ts}"</span>.<span class="fn">encode</span>(<span class="st">'utf-8'</span>),
        hashlib.sha256
    ).<span class="fn">hexdigest</span>()
    <span class="ky">return</span> {
        <span class="st">'X-CLIENT-KEY'</span> : CLIENT_KEY,
        <span class="st">'X-TIMESTAMP'</span>  : ts,
        <span class="st">'X-SIGNATURE'</span>  : sig,
        <span class="st">'Accept'</span>       : <span class="st">'application/json'</span>,
        <span class="st">'Content-Type'</span> : <span class="st">'application/json'</span>,
    }

<span class="ky">def</span> <span class="fn">va_inquiry</span>(va_number: str) -> dict:
    <span class="st">"""Inquiry data Virtual Account by path parameter."""</span>
    r = requests.<span class="fn">get</span>(
        f<span class="st">"{BASE_URL}/api/va/{va_number}"</span>,
        headers=<span class="fn">make_headers</span>(),
        timeout=<span class="nm">15</span>
    )
    r.<span class="fn">raise_for_status</span>()
    result = r.<span class="fn">json</span>()
    <span class="ky">if</span> result[<span class="st">'status'</span>]:
        d = result[<span class="st">'data'</span>]
        <span class="fn">print</span>(f<span class="st">"Nama  : {d['nama_siswa']}"</span>)
        <span class="fn">print</span>(f<span class="st">"Biaya : Rp {d['nominal']:,.0f}"</span>)
        <span class="fn">print</span>(f<span class="st">"Status: {d['status']}"</span>)
    <span class="ky">return</span> result

<span class="ky">def</span> <span class="fn">payment_notify</span>(va_number: str, amount: float, paid_at: str, va_ref: str = <span class="nu">None</span>) -> dict:
    <span class="st">"""Kirim notifikasi pembayaran VA ke server PPDB."""</span>
    payload = {<span class="st">'va_number'</span>: va_number, <span class="st">'amount'</span>: amount, <span class="st">'paid_at'</span>: paid_at}
    <span class="ky">if</span> va_ref:
        payload[<span class="st">'va_ref'</span>] = va_ref
    r = requests.<span class="fn">post</span>(
        f<span class="st">"{BASE_URL}/api/va/payment-notify"</span>,
        json    = payload,
        headers = <span class="fn">make_headers</span>(),
        timeout = <span class="nm">15</span>
    )
    r.<span class="fn">raise_for_status</span>()
    result = r.<span class="fn">json</span>()
    <span class="fn">print</span>(f<span class="st">"[{result['responseCode']}] {result['message']}"</span>)
    <span class="ky">return</span> result

<span class="cm"># ---- Contoh penggunaan ----</span>
<span class="ky">if</span> __name__ == <span class="st">'__main__'</span>:
    <span class="cm"># 1. Cek data VA</span>
    <span class="fn">va_inquiry</span>(<span class="st">'1234567890'</span>)

    <span class="cm"># 2. Kirim notifikasi pembayaran</span>
    <span class="fn">payment_notify</span>(
        va_number = <span class="st">'1234567890'</span>,
        amount    = <span class="nm">500000</span>,
        paid_at   = <span class="st">'2026-08-16T10:25:00+07:00'</span>,
        va_ref    = <span class="st">'TXN-BTN-20260816-001'</span>
    )</div>
  </div>

  <div class="doc-footer">
    PPDB Al-Hasra &bull; Virtual Account API Documentation &bull; v1.0.0 &bull; {{ date('Y') }}
  </div>
</div>

<!-- ============================================================
     HALAMAN 11: POSTMAN + FAQ
============================================================ -->
<div class="page-break"></div>
<div class="content-page">

  <div class="page-header">
    <div class="page-header-label">Virtual Account API &bull; PPDB Al-Hasra</div>
    <div class="page-header-title">10. Integrasi Postman</div>
  </div>

  <div class="section">
    <div class="section-title blue">10.1 Collection Variables &amp; Pre-request Script</div>
    <p>
      File koleksi Postman tersedia di root proyek: <code>PPDB-VA-API.postman_collection.json</code>.
      Import file ini ke Postman untuk langsung mencoba semua endpoint. Signature digenerate otomatis
      oleh Pre-request Script setiap kali request dikirim.
    </p>

    <div class="sub-section">
      <div class="sub-title">Collection Variables</div>
      <table>
        <thead><tr><th>Variable</th><th>Default Value</th><th>Keterangan</th></tr></thead>
        <tbody>
          <tr><td class="td-mono">BASE_URL</td><td class="td-mono">http://localhost:8000</td><td>Base URL server &mdash; ganti sesuai environment</td></tr>
          <tr><td class="td-mono">CLIENT_KEY</td><td class="td-mono">PPDBALHASRA</td><td>Client key untuk autentikasi HMAC</td></tr>
          <tr><td class="td-mono">CLIENT_SECRET</td><td class="td-mono">AEkvExg92F</td><td>Secret key untuk generate signature</td></tr>
          <tr><td class="td-mono">VA_NUMBER</td><td class="td-mono">1234567890</td><td>Nomor VA untuk keperluan testing</td></tr>
        </tbody>
      </table>
    </div>

    <div class="code-label">Pre-request Script (Postman JavaScript)</div>
    <div class="code-block"><span class="cm">// Script ini berjalan otomatis sebelum setiap request</span>
<span class="ky">const</span> clientKey    = pm.collectionVariables.<span class="fn">get</span>(<span class="st">"CLIENT_KEY"</span>);
<span class="ky">const</span> clientSecret = pm.collectionVariables.<span class="fn">get</span>(<span class="st">"CLIENT_SECRET"</span>);
<span class="ky">const</span> moment       = <span class="fn">require</span>(<span class="st">'moment'</span>);

<span class="cm">// Generate timestamp ISO 8601</span>
<span class="ky">const</span> timestamp = moment().<span class="fn">format</span>(); <span class="cm">// "2026-08-16T10:30:00+07:00"</span>

<span class="cm">// Generate HMAC-SHA256 signature (menggunakan CryptoJS yang sudah built-in di Postman)</span>
<span class="ky">const</span> stringToSign = clientKey + <span class="st">"|"</span> + timestamp;
<span class="ky">const</span> signature    = CryptoJS.<span class="fn">HmacSHA256</span>(stringToSign, clientSecret)
    .<span class="fn">toString</span>(CryptoJS.enc.Hex);

<span class="cm">// Set ke variabel sementara (digunakan di headers)</span>
pm.variables.<span class="fn">set</span>(<span class="st">"X-CLIENT-KEY"</span>, clientKey);
pm.variables.<span class="fn">set</span>(<span class="st">"X-TIMESTAMP"</span>,  timestamp);
pm.variables.<span class="fn">set</span>(<span class="st">"X-SIGNATURE"</span>,  signature);</div>

    <div class="sub-section">
      <div class="sub-title">Cara Import dan Penggunaan</div>
      <div class="steps-list">
        <div class="step-row"><span class="step-num">1</span><span class="step-text">Buka Postman &rarr; klik <strong>Import</strong> &rarr; pilih file <code>PPDB-VA-API.postman_collection.json</code></span></div>
        <div class="step-row"><span class="step-num">2</span><span class="step-text">Klik ikon titik tiga pada koleksi &rarr; pilih <strong>Edit</strong> &rarr; tab <strong>Variables</strong></span></div>
        <div class="step-row"><span class="step-num">3</span><span class="step-text">Update nilai <code>BASE_URL</code> sesuai environment Anda (localhost / staging / production)</span></div>
        <div class="step-row"><span class="step-num">4</span><span class="step-text">Update <code>VA_NUMBER</code> dengan nomor VA yang valid dari database PPDB</span></div>
        <div class="step-row"><span class="step-num">5</span><span class="step-text">Klik <strong>Save</strong> &rarr; jalankan request &rarr; signature otomatis digenerate!</span></div>
      </div>
    </div>

    <div class="alert alert-success">
      <div class="alert-title">&#10003; Signature Digenerate Otomatis</div>
      Anda tidak perlu menghitung signature secara manual saat menggunakan Postman.
      Pre-request Script secara otomatis mengisi header <code>X-CLIENT-KEY</code>,
      <code>X-TIMESTAMP</code>, dan <code>X-SIGNATURE</code> setiap kali request dikirim.
    </div>
  </div>

  <!-- SECTION 11: FAQ -->
  <div class="page-header" style="margin-top:10px;">
    <div class="page-header-label">Virtual Account API &bull; PPDB Al-Hasra</div>
    <div class="page-header-title">11. FAQ &amp; Troubleshooting</div>
  </div>

  <div class="section">
    <div class="section-title red">11.1 Masalah Umum &amp; Solusi</div>

    <div class="sub-section no-break">
      <div class="sub-title">&#10060; Error 4010002: X-TIMESTAMP sudah kadaluarsa</div>
      <p><strong>Penyebab:</strong> Perbedaan waktu antara server pengirim dan server PPDB lebih dari 5 menit.</p>
      <p><strong>Solusi:</strong> Sinkronkan jam server dengan NTP: <code>ntpdate pool.ntp.org</code>.
      Pastikan timezone sudah benar (WIB = UTC+7). Toleransi default adalah &plusmn;5 menit.</p>
    </div>

    <div class="sub-section no-break">
      <div class="sub-title">&#10060; Error 4010003: X-SIGNATURE tidak valid</div>
      <p><strong>Penyebab:</strong> Salah membuat string-to-sign, salah secret key, atau encoding tidak tepat.</p>
      <p><strong>Solusi:</strong> Pastikan format string-to-sign adalah <code>"CLIENT_KEY|TIMESTAMP"</code>
      (dipisahkan pipe). Signature harus dalam format hex <strong>lowercase</strong>.
      Gunakan CLIENT_SECRET yang sama persis dengan yang ada di server <code>.env</code>.</p>
    </div>

    <div class="sub-section no-break">
      <div class="sub-title">&#10060; Error 4002501: Nominal tidak sesuai</div>
      <p><strong>Penyebab:</strong> Amount berbeda lebih dari Rp 1 dari tagihan di database.</p>
      <p><strong>Solusi:</strong> Lakukan VA Inquiry terlebih dahulu untuk mendapatkan nominal yang tepat,
      kemudian gunakan nilai tersebut di Payment Notification.</p>
    </div>

    <div class="sub-section no-break">
      <div class="sub-title">&#9888; Error 429: Rate Limit Terlampaui</div>
      <p><strong>Penyebab:</strong> Lebih dari 60 request dalam 1 menit dari IP yang sama.</p>
      <p><strong>Solusi:</strong> Implementasikan retry logic dengan exponential backoff.
      Tunggu minimal 60 detik sebelum mencoba kembali.</p>
    </div>

    <div class="sub-section no-break">
      <div class="sub-title">&#128161; Tips: Cara Melihat Log Audit</div>
      <p>Setiap request (berhasil maupun gagal) dicatat ke Laravel log. Cek di:</p>
      <div class="code-block"><span class="cm"># Lihat log terbaru (real-time)</span>
tail -f storage/logs/laravel.log | grep "VA Inquiry"

<span class="cm"># Log sukses: "VA Inquiry Auth OK"</span>
<span class="cm"># Log gagal : "VA Inquiry Auth FAILED"</span>
<span class="cm"># Log payment: "VA Payment Notify: SUCCESS"</span>

<span class="cm"># Cek konfigurasi via Tinker:</span>
php artisan tinker
config(<span class="st">'services.va_inquiry'</span>);</div>
    </div>
  </div>

  <div class="doc-footer">
    PPDB Al-Hasra &bull; Virtual Account API Documentation &bull; v1.0.0 &bull; {{ date('Y') }}
    &nbsp;&bull;&nbsp; Dokumen Rahasia &mdash; Hanya untuk keperluan integrasi teknis
  </div>
</div>

</body>
</html>
