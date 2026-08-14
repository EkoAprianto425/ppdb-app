<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dokumentasi API Virtual Account — PPDB Al-Hasra</title>
<style>
  /* ================================================================
     BASE & TYPOGRAPHY
     Standard A4 document — clean, readable, professional
  ================================================================ */
  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: DejaVu Sans, Arial, sans-serif;
    font-size: 11px;
    line-height: 1.65;
    color: #222222;
    background: #ffffff;
  }

  /* ================================================================
     PAGE STRUCTURE
  ================================================================ */
  .page-break { page-break-before: always; }
  .no-break   { page-break-inside: avoid; }

  /* ================================================================
     COVER PAGE
     Clean, minimal — white base with a single dark header band
  ================================================================ */
  .cover {
    height: 297mm;
    display: block;
    position: relative;
    background: #ffffff;
    color: #222222;
  }
  .cover-header-band {
    background: #1a1a2e;
    padding: 44px 52px 36px 52px;
    border-bottom: 4px solid #e76f51;
  }
  .cover-logo-row {
    font-size: 10px;
    font-weight: bold;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #aaaaaa;
    margin-bottom: 28px;
  }
  .cover-title {
    font-size: 26px;
    font-weight: bold;
    color: #ffffff;
    line-height: 1.25;
    margin-bottom: 6px;
  }
  .cover-subtitle {
    font-size: 13px;
    color: #bbbbcc;
    margin-bottom: 0;
  }
  .cover-body {
    padding: 40px 52px;
  }
  .cover-meta-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 32px;
  }
  .cover-meta-table td {
    padding: 9px 0;
    border-bottom: 1px solid #eeeeee;
    font-size: 11px;
    vertical-align: top;
  }
  .cover-meta-table .label {
    color: #888888;
    width: 38%;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.8px;
  }
  .cover-meta-table .value {
    color: #222222;
    font-weight: bold;
  }
  .cover-desc-box {
    background: #f8f8f8;
    border: 1px solid #e0e0e0;
    border-left: 4px solid #e76f51;
    padding: 16px 20px;
    margin-bottom: 32px;
  }
  .cover-desc-box p {
    font-size: 11px;
    color: #444444;
    line-height: 1.7;
    margin: 0;
  }
  .cover-footer {
    position: absolute;
    bottom: 28px;
    left: 52px;
    right: 52px;
    border-top: 1px solid #e0e0e0;
    padding-top: 12px;
  }
  .cover-footer-text {
    font-size: 9px;
    color: #999999;
  }
  .cover-footer-right {
    font-size: 9px;
    color: #999999;
    text-align: right;
  }

  /* ================================================================
     CONTENT PAGES
     Standard A4 margins: top 24mm, sides 22mm, bottom 24mm
  ================================================================ */
  .content-page {
    padding: 28px 44px 40px 44px;
  }

  /* ================================================================
     PAGE HEADER (section title bar)
  ================================================================ */
  .page-header {
    border-bottom: 1px solid #cccccc;
    padding-bottom: 8px;
    margin-bottom: 24px;
  }
  .page-header-label {
    font-size: 8px;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: #888888;
    margin-bottom: 2px;
  }
  .page-header-title {
    font-size: 17px;
    font-weight: bold;
    color: #1a1a2e;
  }

  /* ================================================================
     SECTION & SUBSECTION
  ================================================================ */
  .section { margin-bottom: 24px; }

  .section-title {
    font-size: 13px;
    font-weight: bold;
    color: #1a1a2e;
    padding-bottom: 5px;
    border-bottom: 2px solid #e0e0e0;
    margin-bottom: 12px;
  }
  /* Color accents — subtle left rule instead of vivid borders */
  .section-title.purple { border-bottom-color: #7c5cbf; }
  .section-title.green  { border-bottom-color: #2e7d5e; }
  .section-title.orange { border-bottom-color: #e76f51; }
  .section-title.red    { border-bottom-color: #c0392b; }
  .section-title.cyan   { border-bottom-color: #1a7fa1; }

  .sub-section { margin-bottom: 16px; }
  .sub-title {
    font-size: 11px;
    font-weight: bold;
    color: #333333;
    margin-bottom: 6px;
  }

  /* ================================================================
     TYPOGRAPHY
  ================================================================ */
  p { margin-bottom: 8px; color: #444444; font-size: 11px; }

  ul, ol {
    font-size: 11px;
    color: #444444;
  }

  /* ================================================================
     ENDPOINT CARD
     Postman-style: method badge + path on colored band
  ================================================================ */
  .endpoint-card {
    border: 1px solid #d0d0d0;
    border-radius: 4px;
    margin-bottom: 20px;
    overflow: hidden;
  }
  .endpoint-header {
    padding: 10px 14px;
    background: #2d3748;
  }
  .endpoint-header.get  { background: #1a4a3a; }
  .endpoint-header.post { background: #1a3a4a; }
  .endpoint-method {
    display: inline-block;
    font-size: 9px;
    font-weight: bold;
    letter-spacing: 1px;
    padding: 3px 9px;
    border-radius: 3px;
    margin-right: 10px;
    vertical-align: middle;
  }
  .method-get  { background: #27ae60; color: #ffffff; }
  .method-post { background: #e67e22; color: #ffffff; }
  .method-both { background: #8e44ad; color: #ffffff; }
  .endpoint-path {
    font-family: DejaVu Sans Mono, Courier New, monospace;
    font-size: 12px;
    font-weight: bold;
    color: #ffffff;
    vertical-align: middle;
  }
  .endpoint-desc {
    font-size: 9px;
    color: #aaaaaa;
    margin-top: 4px;
  }
  .endpoint-body {
    padding: 14px 16px;
    background: #ffffff;
  }

  /* ================================================================
     TABLES
     Clean borders, readable cell padding
  ================================================================ */
  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 10px;
    margin-bottom: 12px;
    border: 1px solid #e0e0e0;
  }
  th {
    background: #f5f5f5;
    color: #555555;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 8.5px;
    padding: 8px 12px;
    text-align: left;
    border-bottom: 1px solid #d0d0d0;
  }
  td {
    padding: 8px 12px;
    border-bottom: 1px solid #eeeeee;
    color: #333333;
    vertical-align: top;
    font-size: 10px;
  }
  tr:last-child td { border-bottom: none; }
  tr:nth-child(even) td { background: #fafafa; }

  /* ================================================================
     BADGES
  ================================================================ */
  .badge {
    display: inline-block;
    padding: 2px 7px;
    border-radius: 3px;
    font-size: 8px;
    font-weight: bold;
    letter-spacing: 0.2px;
  }
  .badge-required { background: #fde8e8; color: #c0392b; border: 1px solid #f5c6c6; }
  .badge-optional { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
  .badge-string   { background: #e3f0fb; color: #1565c0; border: 1px solid #bbdefb; }
  .badge-numeric  { background: #f3e8fb; color: #6a1b9a; border: 1px solid #e1bee7; }
  .badge-datetime { background: #fff3e0; color: #e65100; border: 1px solid #ffe0b2; }
  .badge-200 { background: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; }
  .badge-400 { background: #fff8e1; color: #f57f17; border: 1px solid #ffe082; }
  .badge-401 { background: #fde8e8; color: #c0392b; border: 1px solid #f5c6c6; }
  .badge-404 { background: #fce4ec; color: #880e4f; border: 1px solid #f48fb1; }
  .badge-422 { background: #fce4ec; color: #880e4f; border: 1px solid #f48fb1; }
  .badge-429 { background: #ede7f6; color: #4527a0; border: 1px solid #d1c4e9; }

  /* ================================================================
     CODE BLOCKS
     Light background — easier to read in print
  ================================================================ */
  .code-block {
    background: #f6f8fa;
    border: 1px solid #d0d0d0;
    border-radius: 4px;
    padding: 11px 14px;
    margin: 8px 0;
    font-family: DejaVu Sans Mono, Courier New, monospace;
    font-size: 9px;
    color: #24292e;
    line-height: 1.7;
    white-space: pre-wrap;
    word-wrap: break-word;
  }
  /* Syntax highlighting — muted, print-friendly */
  .code-block .comment { color: #6a737d; font-style: italic; }
  .code-block .key     { color: #005cc5; }
  .code-block .string  { color: #22863a; }
  .code-block .number  { color: #e36209; }
  .code-block .keyword { color: #d73a49; }

  .code-label {
    font-size: 8.5px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #888888;
    margin-bottom: 3px;
  }

  /* ================================================================
     INLINE CODE
  ================================================================ */
  code {
    font-family: DejaVu Sans Mono, Courier New, monospace;
    font-size: 9.5px;
    background: #f0f0f0;
    color: #d73a49;
    padding: 1px 5px;
    border-radius: 3px;
    border: 1px solid #e0e0e0;
  }

  /* ================================================================
     ALERT / CALLOUT BOXES
  ================================================================ */
  .alert {
    padding: 10px 14px;
    border-radius: 4px;
    margin-bottom: 12px;
    font-size: 10px;
    border: 1px solid transparent;
  }
  .alert-info {
    background: #f0f7ff;
    border-color: #b3d4f0;
    border-left: 4px solid #1565c0;
    color: #1a3a6c;
  }
  .alert-warning {
    background: #fffbf0;
    border-color: #ffd980;
    border-left: 4px solid #f57f17;
    color: #6b3800;
  }
  .alert-danger {
    background: #fff5f5;
    border-color: #f5c6c6;
    border-left: 4px solid #c0392b;
    color: #7b1111;
  }
  .alert-success {
    background: #f0faf5;
    border-color: #a5d6a7;
    border-left: 4px solid #2e7d32;
    color: #1b4d1f;
  }
  .alert-title { font-weight: bold; margin-bottom: 4px; font-size: 10px; }

  /* ================================================================
     TABLE OF CONTENTS
  ================================================================ */
  .toc-item {
    display: block;
    padding: 6px 0;
    border-bottom: 1px solid #eeeeee;
    font-size: 11px;
    color: #333333;
  }
  .toc-item .toc-num {
    font-weight: bold;
    color: #e76f51;
    margin-right: 8px;
    display: inline-block;
    width: 26px;
  }
  .toc-item .toc-page {
    float: right;
    color: #999999;
    font-size: 10px;
  }
  .toc-sub {
    padding-left: 34px;
    font-size: 10px;
    color: #777777;
    border-bottom: 1px solid #f5f5f5;
  }

  /* ================================================================
     NUMBERED STEPS
  ================================================================ */
  .step-row {
    display: block;
    margin-bottom: 8px;
    padding: 8px 12px;
    background: #f8f8f8;
    border-left: 3px solid #e76f51;
    border-radius: 0 4px 4px 0;
  }
  .step-num {
    display: inline-block;
    background: #e76f51;
    color: #ffffff;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    line-height: 18px;
    text-align: center;
    font-size: 8.5px;
    font-weight: bold;
    margin-right: 8px;
    vertical-align: middle;
  }
  .step-text {
    display: inline-block;
    vertical-align: middle;
    font-size: 10px;
    color: #333333;
  }

  /* ================================================================
     RESPONSE CODE TABLE
  ================================================================ */
  .rc-table th {
    background: #2d3748;
    color: #dddddd;
    border-bottom: 1px solid #444444;
  }

  /* ================================================================
     GRID HELPERS
  ================================================================ */
  .grid-2 { display: block; }
  .col     { display: inline-block; vertical-align: top; width: 48%; margin-right: 2%; }

  /* ================================================================
     PAGE FOOTER
  ================================================================ */
  .doc-footer {
    margin-top: 28px;
    padding-top: 10px;
    border-top: 1px solid #e0e0e0;
    font-size: 8.5px;
    color: #aaaaaa;
    text-align: center;
  }

  /* ================================================================
     MISC
  ================================================================ */
  .confidential {
    font-size: 8px;
    color: #cccccc;
    text-transform: uppercase;
    letter-spacing: 2px;
    text-align: center;
    margin-bottom: 10px;
  }
</style>

  /* ===== FOOTER ===== */
  .doc-footer {
    margin-top: 32px;
    padding-top: 10px;
    border-top: 1px solid #e2e8f0;
    font-size: 7.5px;
    color: #94a3b8;
    text-align: center;
  }

  /* ===== GRID ===== */
  .grid-2 { display: block; }
  .col     { display: inline-block; vertical-align: top; width: 48%; margin-right: 2%; }

  /* ===== RESPONSE EXAMPLE ===== */
  .response-tabs {
    display: block;
    margin-bottom: 6px;
  }
  .resp-tab {
    display: inline-block;
    padding: 4px 12px;
    font-size: 8px;
    font-weight: bold;
    border-radius: 4px 4px 0 0;
    margin-right: 2px;
    cursor: default;
  }
  .resp-tab-success { background: #22c55e; color: #fff; }
  .resp-tab-error   { background: #ef4444; color: #fff; }

  /* ===== SECURITY STEP ===== */
  .step-row {
    display: block;
    margin-bottom: 8px;
    padding: 8px 12px;
    background: #f8fafc;
    border-radius: 5px;
    border-left: 3px solid #3b82f6;
  }
  .step-num {
    display: inline-block;
    background: #3b82f6;
    color: #fff;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    line-height: 18px;
    text-align: center;
    font-size: 8px;
    font-weight: bold;
    margin-right: 8px;
    vertical-align: middle;
  }
  .step-text {
    display: inline-block;
    vertical-align: middle;
    font-size: 9px;
    color: #1e293b;
  }

  /* ===== RESPONSE CODE TABLE ===== */
  .rc-table th { background: #1e293b; color: #94a3b8; }

  /* ===== WATERMARK ===== */
  .confidential {
    font-size: 7px;
    color: #cbd5e1;
    text-transform: uppercase;
    letter-spacing: 2px;
    text-align: center;
    margin-bottom: 16px;
  }
</style>
</head>
<body>

<!-- ============================================================ -->
<!--  HALAMAN COVER                                               -->
<!-- ============================================================ -->
<div class="cover">
  <div class="cover-header-band">
    <div class="cover-logo-row">PPDB Al-Hasra &nbsp;&bull;&nbsp; Dokumentasi Teknis</div>
    <div class="cover-title">Virtual Account API</div>
    <div class="cover-subtitle">Spesifikasi Integrasi Sistem Pembayaran VA</div>
  </div>

  <div class="cover-body">

    <table class="cover-meta-table">
      <tr>
        <td class="label">Institusi</td>
        <td class="value">PPDB Al-Hasra</td>
      </tr>
      <tr>
        <td class="label">Base URL</td>
        <td class="value">https://ppdb.alhasra.sch.id/api</td>
      </tr>
      <tr>
        <td class="label">Versi Dokumen</td>
        <td class="value">v1.0.0</td>
      </tr>
      <tr>
        <td class="label">Tanggal Terbit</td>
        <td class="value">{{ date('d F Y') }}</td>
      </tr>
      <tr>
        <td class="label">Standar Keamanan</td>
        <td class="value">HMAC-SHA256 &bull; BI SNAP Compliant</td>
      </tr>
      <tr>
        <td class="label">Bank Mitra</td>
        <td class="value">BTN (Bank Tabungan Negara)</td>
      </tr>
      <tr>
        <td class="label">Klasifikasi</td>
        <td class="value">Rahasia &mdash; Hanya untuk keperluan integrasi teknis</td>
      </tr>
    </table>

    <div class="cover-desc-box">
      <p><strong>Deskripsi Dokumen</strong><br>
      Dokumen ini berisi spesifikasi teknis lengkap untuk integrasi API Virtual Account (VA)
      sistem PPDB Al-Hasra. API ini memungkinkan sistem bank atau gateway pembayaran untuk
      melakukan inquiry data tagihan VA dan mengirimkan notifikasi pembayaran secara aman,
      menggunakan mekanisme autentikasi HMAC-SHA256 yang sesuai standar Bank Indonesia (SNAP).
      </p>
    </div>

  </div>

  <div class="cover-footer">
    <div class="cover-footer-text">PPDB Al-Hasra &bull; Virtual Account API &bull; v1.0.0 &bull; {{ date('Y') }}</div>
    <div class="cover-footer-right">Dokumen Rahasia &mdash; Jangan disebarkan</div>
  </div>
</div>

<!-- ============================================================ -->
<!--  HALAMAN 2: DAFTAR ISI                                       -->
<!-- ============================================================ -->
<div class="page-break"></div>
<div class="content-page">

  <div class="page-header">
    <div class="page-header-label">Virtual Account API &bull; PPDB Al-Hasra</div>
    <div class="page-header-title">Daftar Isi</div>
  </div>

  <div class="toc-item">
    <span class="toc-num">01</span> Pendahuluan &amp; Ikhtisar Sistem
    <span class="toc-page">3</span>
  </div>
  <div class="toc-item toc-sub">
    &bull; Tujuan Dokumen &bull; Lingkup API &bull; Teknologi
  </div>
  <div class="toc-item">
    <span class="toc-num">02</span> Autentikasi &amp; Keamanan
    <span class="toc-page">4</span>
  </div>
  <div class="toc-item toc-sub">
    &bull; Mekanisme HMAC-SHA256 &bull; Cara Generate Signature &bull; Contoh Kode
  </div>
  <div class="toc-item">
    <span class="toc-num">03</span> Endpoint: GET Inquiry VA (by Path)
    <span class="toc-page">5</span>
  </div>
  <div class="toc-item toc-sub">
    &bull; Request &bull; Response Sukses &bull; Response Error
  </div>
  <div class="toc-item">
    <span class="toc-num">04</span> Endpoint: GET/POST Inquiry VA (by Parameter)
    <span class="toc-page">6</span>
  </div>
  <div class="toc-item toc-sub">
    &bull; Request &bull; Response Sukses &bull; Response Error
  </div>
  <div class="toc-item">
    <span class="toc-num">05</span> Endpoint: POST Payment Notification
    <span class="toc-page">7</span>
  </div>
  <div class="toc-item toc-sub">
    &bull; Request Body &bull; Validasi Nominal &bull; Response Sukses &bull; Response Error
  </div>
  <div class="toc-item">
    <span class="toc-num">06</span> Kode Respons &amp; Error Handling
    <span class="toc-page">9</span>
  </div>
  <div class="toc-item">
    <span class="toc-num">07</span> Rate Limiting
    <span class="toc-page">9</span>
  </div>
  <div class="toc-item">
    <span class="toc-num">08</span> Contoh Implementasi Lengkap
    <span class="toc-page">10</span>
  </div>
  <div class="toc-item toc-sub">
    &bull; PHP &bull; Python &bull; cURL
  </div>
  <div class="toc-item">
    <span class="toc-num">09</span> Alur Proses Pembayaran
    <span class="toc-page">11</span>
  </div>
  <div class="toc-item">
    <span class="toc-num">10</span> Kontak &amp; Dukungan Teknis
    <span class="toc-page">11</span>
  </div>

  <div style="margin-top: 32px;">
    <div class="alert alert-info">
      <div class="alert-title">&#x2139; Tentang Dokumen Ini</div>
      Dokumen ini bersifat <strong>rahasia</strong> dan hanya ditujukan untuk keperluan integrasi teknis
      antara sistem PPDB Al-Hasra dengan sistem bank/gateway pembayaran. Jangan sebarkan kredensial
      yang tercantum dalam dokumen ini kepada pihak yang tidak berwenang.
    </div>
  </div>

</div>

<!-- ============================================================ -->
<!--  HALAMAN 3: PENDAHULUAN                                      -->
<!-- ============================================================ -->
<div class="page-break"></div>
<div class="content-page">

  <div class="page-header">
    <div class="page-header-label">01 &bull; Pendahuluan</div>
    <div class="page-header-title">Ikhtisar Sistem</div>
  </div>

  <div class="section">
    <div class="section-title">Tujuan Dokumen</div>
    <p>Dokumen ini menjelaskan spesifikasi teknis API Virtual Account (VA) yang disediakan oleh
    sistem PPDB Al-Hasra. API ini dirancang untuk memfasilitasi dua fungsi utama:</p>
    <ul style="margin: 6px 0 6px 18px; font-size: 9px; color:#334155; line-height: 1.9;">
      <li><strong>Inquiry VA</strong> — Memungkinkan sistem bank untuk mengambil data tagihan berdasarkan nomor VA sebelum transaksi dilakukan.</li>
      <li><strong>Payment Notification</strong> — Menerima notifikasi dari bank setelah nasabah melakukan pembayaran, untuk memperbarui status pembayaran di sistem PPDB.</li>
    </ul>
  </div>

  <div class="section">
    <div class="section-title cyan">Informasi Teknis Umum</div>
    <table>
      <thead>
        <tr><th>Parameter</th><th>Nilai</th></tr>
      </thead>
      <tbody>
        <tr><td><strong>Base URL (Production)</strong></td><td><code>https://ppdb.alhasra.sch.id/api</code></td></tr>
        <tr><td><strong>Base URL (Development)</strong></td><td><code>http://localhost/api</code></td></tr>
        <tr><td><strong>Protocol</strong></td><td>HTTPS (wajib di production)</td></tr>
        <tr><td><strong>Format Data</strong></td><td>JSON (<code>Content-Type: application/json</code>)</td></tr>
        <tr><td><strong>Encoding Karakter</strong></td><td>UTF-8</td></tr>
        <tr><td><strong>Metode Autentikasi</strong></td><td>HMAC-SHA256 Signature (BI SNAP)</td></tr>
        <tr><td><strong>Rate Limit</strong></td><td>60 request per menit per IP</td></tr>
        <tr><td><strong>Timeout yang Disarankan</strong></td><td>30 detik</td></tr>
      </tbody>
    </table>
  </div>

  <div class="section">
    <div class="section-title purple">Daftar Endpoint</div>
    <table>
      <thead>
        <tr><th>Method</th><th>Endpoint</th><th>Fungsi</th></tr>
      </thead>
      <tbody>
        <tr>
          <td><span class="badge" style="background:#0ea5e9;color:#fff;">GET</span></td>
          <td><code>/va/{va_number}</code></td>
          <td>Inquiry data VA berdasarkan nomor VA di URL</td>
        </tr>
        <tr>
          <td><span class="badge" style="background:#8b5cf6;color:#fff;">GET/POST</span></td>
          <td><code>/va/inquiry</code></td>
          <td>Inquiry data VA berdasarkan parameter request</td>
        </tr>
        <tr>
          <td><span class="badge" style="background:#22c55e;color:#fff;">POST</span></td>
          <td><code>/va/payment-notify</code></td>
          <td>Notifikasi pembayaran VA dari bank/gateway</td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="alert alert-warning">
    <div class="alert-title">&#x26A0; Penting: Urutan Definisi Route</div>
    Endpoint <code>/va/inquiry</code> harus selalu dipanggil menggunakan path yang tepat.
    Jangan gunakan <code>/va/inquiry</code> sebagai nilai <code>va_number</code> di path parameter.
  </div>

</div>

<!-- ============================================================ -->
<!--  HALAMAN 4: AUTENTIKASI                                      -->
<!-- ============================================================ -->
<div class="page-break"></div>
<div class="content-page">

  <div class="page-header">
    <div class="page-header-label">02 &bull; Keamanan</div>
    <div class="page-header-title">Autentikasi HMAC-SHA256</div>
  </div>

  <div class="section">
    <div class="section-title">Mekanisme Autentikasi</div>
    <p>Seluruh endpoint VA dilindungi menggunakan <strong>HMAC-SHA256 Signature Authentication</strong>
    yang sesuai dengan standar <strong>Bank Indonesia SNAP (Standar Nasional Open API Pembayaran)</strong>.
    Mekanisme ini memastikan bahwa:</p>
    <ul style="margin: 6px 0 6px 18px; font-size: 9px; color:#334155; line-height: 2.0;">
      <li>Hanya client yang memiliki <strong>secret key</strong> yang dapat mengakses API.</li>
      <li>Request lama tidak dapat digunakan ulang (<strong>anti replay attack</strong>).</li>
      <li>Signature tidak dapat dipalsukan tanpa mengetahui secret key.</li>
    </ul>
  </div>

  <div class="section">
    <div class="section-title purple">Header Wajib</div>
    <table>
      <thead>
        <tr><th>Header</th><th>Contoh Nilai</th><th>Keterangan</th></tr>
      </thead>
      <tbody>
        <tr>
          <td><code>X-CLIENT-KEY</code></td>
          <td><code>PPDBALHASRA</code></td>
          <td>Client identifier yang diberikan oleh PPDB Al-Hasra</td>
        </tr>
        <tr>
          <td><code>X-TIMESTAMP</code></td>
          <td><code>2026-08-14T20:28:09+07:00</code></td>
          <td>Waktu request dalam format ISO 8601. Toleransi ±5 menit dari waktu server.</td>
        </tr>
        <tr>
          <td><code>X-SIGNATURE</code></td>
          <td><code>a3f8c1d2e5...</code></td>
          <td>HMAC-SHA256 dari <em>string-to-sign</em> menggunakan <em>client secret</em></td>
        </tr>
        <tr>
          <td><code>Content-Type</code></td>
          <td><code>application/json</code></td>
          <td>Wajib untuk request POST dengan body JSON</td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="section">
    <div class="section-title green">Cara Menghitung Signature</div>

    <div class="step-row">
      <span class="step-num">1</span>
      <span class="step-text">Tentukan <strong>String-to-Sign</strong>: gabungkan CLIENT_KEY dan TIMESTAMP dengan karakter <code>|</code></span>
    </div>

    <div class="code-label">Format String-to-Sign</div>
    <div class="code-block">{CLIENT_KEY}|{TIMESTAMP}

<span class="comment">Contoh:</span>
PPDBALHASRA|2026-08-14T20:28:09+07:00</div>

    <div class="step-row">
      <span class="step-num">2</span>
      <span class="step-text">Hitung <strong>HMAC-SHA256</strong> dari String-to-Sign menggunakan CLIENT_SECRET sebagai kunci</span>
    </div>

    <div class="code-label">Formula Signature</div>
    <div class="code-block">SIGNATURE = HMAC-SHA256(StringToSign, CLIENT_SECRET)
<span class="comment">// Hasil berupa hex string lowercase, contoh:</span>
<span class="comment">// a3f8c1d2e57b9034f1c6a8d5e2b7f490c3d1e8a5b2c9f6d3...</span></div>

    <div class="step-row">
      <span class="step-num">3</span>
      <span class="step-text">Masukkan hasil signature ke header <strong>X-SIGNATURE</strong></span>
    </div>
  </div>

  <div class="section">
    <div class="section-title orange">Contoh Kode — Generate Signature</div>

    <div class="code-label">PHP</div>
    <div class="code-block"><span class="keyword">$clientKey</span>  = <span class="string">'PPDBALHASRA'</span>;
<span class="keyword">$secret</span>     = <span class="string">'YOUR_CLIENT_SECRET'</span>;
<span class="keyword">$timestamp</span>  = (new DateTime())->format(DateTime::ATOM); <span class="comment">// ISO 8601</span>

<span class="keyword">$stringToSign</span> = <span class="keyword">$clientKey</span> . <span class="string">'|'</span> . <span class="keyword">$timestamp</span>;
<span class="keyword">$signature</span>    = hash_hmac(<span class="string">'sha256'</span>, <span class="keyword">$stringToSign</span>, <span class="keyword">$secret</span>);</div>

    <div class="code-label">Python</div>
    <div class="code-block"><span class="keyword">import</span> hmac, hashlib
<span class="keyword">from</span> datetime <span class="keyword">import</span> datetime, timezone, timedelta

client_key = <span class="string">'PPDBALHASRA'</span>
secret     = <span class="string">'YOUR_CLIENT_SECRET'</span>
timestamp  = datetime.now(timezone(timedelta(hours=7))).isoformat()

string_to_sign = <span class="string">f"</span>{client_key}<span class="string">|</span>{timestamp}<span class="string">"</span>
signature = hmac.new(secret.encode(), string_to_sign.encode(), hashlib.sha256).hexdigest()</div>

    <div class="code-label">cURL (Bash / Linux)</div>
    <div class="code-block">TIMESTAMP=$(date -Iseconds)
CLIENT_KEY="PPDBALHASRA"
SECRET="YOUR_CLIENT_SECRET"
STRING_TO_SIGN="${CLIENT_KEY}|${TIMESTAMP}"
SIGNATURE=$(echo -n "$STRING_TO_SIGN" | openssl dgst -sha256 -hmac "$SECRET" | awk '{print $2}')</div>
  </div>

  <div class="alert alert-danger">
    <div class="alert-title">&#x1F512; Keamanan Kredensial</div>
    <strong>CLIENT_SECRET</strong> bersifat rahasia dan tidak boleh disimpan di sisi client/browser,
    di-hardcode dalam source code, atau dibagikan kepada pihak ketiga. Gunakan environment variable
    atau secrets manager di production.
  </div>

</div>

<!-- ============================================================ -->
<!--  HALAMAN 5: ENDPOINT 1 — GET /va/{va_number}                 -->
<!-- ============================================================ -->
<div class="page-break"></div>
<div class="content-page">

  <div class="page-header">
    <div class="page-header-label">03 &bull; Endpoint</div>
    <div class="page-header-title">Inquiry VA by Path Parameter</div>
  </div>

  <div class="endpoint-card no-break">
    <div class="endpoint-header get">
      <span class="endpoint-method method-get">GET</span>
      <span class="endpoint-path">/api/va/{va_number}</span>
      <div class="endpoint-desc">Mendapatkan data tagihan Virtual Account berdasarkan nomor VA yang dikirimkan sebagai path parameter</div>
    </div>
    <div class="endpoint-body">

      <div class="sub-section">
        <div class="sub-title">Path Parameter</div>
        <table>
          <thead><tr><th>Parameter</th><th>Tipe</th><th>Wajib</th><th>Keterangan</th></tr></thead>
          <tbody>
            <tr>
              <td><code>va_number</code></td>
              <td><span class="badge badge-string">string</span></td>
              <td><span class="badge badge-required">Wajib</span></td>
              <td>Nomor Virtual Account yang akan di-inquiry (panjang sesuai konfigurasi bank)</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="sub-section">
        <div class="sub-title">Contoh Request</div>
        <div class="code-label">cURL</div>
        <div class="code-block">curl -X GET <span class="string">"https://ppdb.alhasra.sch.id/api/va/88170484200000001"</span> \
  -H <span class="string">"X-CLIENT-KEY: PPDBALHASRA"</span> \
  -H <span class="string">"X-TIMESTAMP: 2026-08-14T20:28:09+07:00"</span> \
  -H <span class="string">"X-SIGNATURE: a3f8c1d2e57b90..."</span></div>
      </div>

      <div class="sub-section">
        <div class="sub-title">Response Sukses <span class="badge badge-200">200 OK</span></div>
        <div class="code-block">{
  <span class="key">"status"</span>:          <span class="keyword">true</span>,
  <span class="key">"responseCode"</span>:    <span class="string">"2002500"</span>,
  <span class="key">"responseMessage"</span>: <span class="string">"Successful"</span>,
  <span class="key">"message"</span>:         <span class="string">"Data Virtual Account berhasil ditemukan."</span>,
  <span class="key">"data"</span>: {
    <span class="key">"nama_siswa"</span>: <span class="string">"Ahmad Fauzan Hidayat"</span>,
    <span class="key">"fee_type"</span>:   <span class="string">"Formulir"</span>,
    <span class="key">"va"</span>:         <span class="string">"88170484200000001"</span>,
    <span class="key">"nominal"</span>:    <span class="number">250000</span>,
    <span class="key">"status"</span>:     <span class="string">"pending"</span>
  }
}</div>
      </div>

      <div class="sub-section">
        <div class="sub-title">Keterangan Field Response <code>data</code></div>
        <table>
          <thead><tr><th>Field</th><th>Tipe</th><th>Keterangan</th></tr></thead>
          <tbody>
            <tr><td><code>nama_siswa</code></td><td>string</td><td>Nama lengkap calon siswa pemilik VA</td></tr>
            <tr><td><code>fee_type</code></td><td>string</td><td>Jenis biaya (contoh: <em>Formulir</em>, <em>Uang Masuk</em>)</td></tr>
            <tr><td><code>va</code></td><td>string</td><td>Nomor Virtual Account</td></tr>
            <tr><td><code>nominal</code></td><td>float</td><td>Jumlah tagihan dalam Rupiah</td></tr>
            <tr><td><code>status</code></td><td>string</td><td>Status pembayaran: <code>pending</code> / <code>success</code> / <code>failed</code></td></tr>
          </tbody>
        </table>
      </div>

      <div class="sub-section">
        <div class="sub-title">Response Error</div>
        <table>
          <thead><tr><th>HTTP</th><th>responseCode</th><th>Penyebab</th></tr></thead>
          <tbody>
            <tr><td><span class="badge badge-401">401</span></td><td><code>4010000</code></td><td>Header autentikasi tidak lengkap</td></tr>
            <tr><td><span class="badge badge-401">401</span></td><td><code>4010001</code></td><td>X-CLIENT-KEY tidak valid</td></tr>
            <tr><td><span class="badge badge-401">401</span></td><td><code>4010002</code></td><td>X-TIMESTAMP kadaluarsa (selisih &gt;5 menit)</td></tr>
            <tr><td><span class="badge badge-401">401</span></td><td><code>4010003</code></td><td>X-SIGNATURE tidak valid</td></tr>
            <tr><td><span class="badge badge-404">404</span></td><td><code>4042512</code></td><td>Nomor VA tidak ditemukan di sistem</td></tr>
          </tbody>
        </table>
      </div>

    </div>
  </div>

</div>

<!-- ============================================================ -->
<!--  HALAMAN 6: ENDPOINT 2 — GET|POST /va/inquiry               -->
<!-- ============================================================ -->
<div class="page-break"></div>
<div class="content-page">

  <div class="page-header">
    <div class="page-header-label">04 &bull; Endpoint</div>
    <div class="page-header-title">Inquiry VA by Request Parameter</div>
  </div>

  <div class="endpoint-card no-break">
    <div class="endpoint-header" style="background:#1e3a5f;">
      <span class="endpoint-method method-both">GET / POST</span>
      <span class="endpoint-path">/api/va/inquiry</span>
      <div class="endpoint-desc">Mendapatkan data tagihan VA dengan nomor VA dikirimkan melalui query parameter (GET) atau request body (POST)</div>
    </div>
    <div class="endpoint-body">

      <div class="sub-section">
        <div class="sub-title">Parameter Request</div>
        <table>
          <thead><tr><th>Parameter</th><th>Lokasi</th><th>Tipe</th><th>Wajib</th><th>Keterangan</th></tr></thead>
          <tbody>
            <tr>
              <td><code>va</code></td>
              <td>Query / Body</td>
              <td><span class="badge badge-string">string</span></td>
              <td><span class="badge badge-required">Wajib*</span></td>
              <td>Nomor Virtual Account</td>
            </tr>
            <tr>
              <td><code>va_number</code></td>
              <td>Query / Body</td>
              <td><span class="badge badge-string">string</span></td>
              <td><span class="badge badge-optional">Alternatif</span></td>
              <td>Alias untuk <code>va</code></td>
            </tr>
            <tr>
              <td><code>virtualAccountNumber</code></td>
              <td>Query / Body</td>
              <td><span class="badge badge-string">string</span></td>
              <td><span class="badge badge-optional">Alternatif</span></td>
              <td>Alias untuk <code>va</code> (format SNAP BTN)</td>
            </tr>
          </tbody>
        </table>
        <p style="font-size:8px; color:#64748b;">* Salah satu dari tiga parameter di atas wajib dikirimkan.</p>
      </div>

      <div class="sub-section">
        <div class="sub-title">Contoh Request — GET</div>
        <div class="code-block">curl -X GET \
  <span class="string">"https://ppdb.alhasra.sch.id/api/va/inquiry?va=88170484200000001"</span> \
  -H <span class="string">"X-CLIENT-KEY: PPDBALHASRA"</span> \
  -H <span class="string">"X-TIMESTAMP: 2026-08-14T20:28:09+07:00"</span> \
  -H <span class="string">"X-SIGNATURE: a3f8c1d2e57b90..."</span></div>
      </div>

      <div class="sub-section">
        <div class="sub-title">Contoh Request — POST</div>
        <div class="code-block">curl -X POST \
  <span class="string">"https://ppdb.alhasra.sch.id/api/va/inquiry"</span> \
  -H <span class="string">"Content-Type: application/json"</span> \
  -H <span class="string">"X-CLIENT-KEY: PPDBALHASRA"</span> \
  -H <span class="string">"X-TIMESTAMP: 2026-08-14T20:28:09+07:00"</span> \
  -H <span class="string">"X-SIGNATURE: a3f8c1d2e57b90..."</span> \
  -d <span class="string">'{"virtualAccountNumber": "88170484200000001"}'</span></div>
      </div>

      <div class="sub-section">
        <div class="sub-title">Response Sukses <span class="badge badge-200">200 OK</span></div>
        <p style="font-size:8.5px; color:#64748b; margin-bottom:6px;">
          Format response identik dengan endpoint <code>GET /api/va/{'{'}va_number{'}'}</code>.
        </p>
        <div class="code-block">{
  <span class="key">"status"</span>:          <span class="keyword">true</span>,
  <span class="key">"responseCode"</span>:    <span class="string">"2002500"</span>,
  <span class="key">"responseMessage"</span>: <span class="string">"Successful"</span>,
  <span class="key">"message"</span>:         <span class="string">"Data Virtual Account berhasil ditemukan."</span>,
  <span class="key">"data"</span>: {
    <span class="key">"nama_siswa"</span>: <span class="string">"Siti Nurhaliza"</span>,
    <span class="key">"fee_type"</span>:   <span class="string">"Uang Masuk"</span>,
    <span class="key">"va"</span>:         <span class="string">"88170484200000001"</span>,
    <span class="key">"nominal"</span>:    <span class="number">3500000</span>,
    <span class="key">"status"</span>:     <span class="string">"pending"</span>
  }
}</div>
      </div>

      <div class="sub-section">
        <div class="sub-title">Response Error — VA tidak ditemukan <span class="badge badge-404">404</span></div>
        <div class="code-block">{
  <span class="key">"status"</span>:          <span class="keyword">false</span>,
  <span class="key">"responseCode"</span>:    <span class="string">"4042512"</span>,
  <span class="key">"responseMessage"</span>: <span class="string">"Bill not found"</span>,
  <span class="key">"message"</span>:         <span class="string">"Data Virtual Account tidak ditemukan."</span>,
  <span class="key">"data"</span>:            <span class="keyword">null</span>
}</div>
      </div>

      <div class="sub-section">
        <div class="sub-title">Response Error — Parameter tidak diberikan <span class="badge badge-400">400</span></div>
        <div class="code-block">{
  <span class="key">"status"</span>:          <span class="keyword">false</span>,
  <span class="key">"responseCode"</span>:    <span class="string">"4002500"</span>,
  <span class="key">"responseMessage"</span>: <span class="string">"Bad Request: Nomor Virtual Account (va) tidak diberikan."</span>,
  <span class="key">"message"</span>:         <span class="string">"Parameter va / va_number / virtualAccountNumber wajib diisi."</span>,
  <span class="key">"data"</span>:            <span class="keyword">null</span>
}</div>
      </div>

    </div>
  </div>

</div>

<!-- ============================================================ -->
<!--  HALAMAN 7-8: ENDPOINT 3 — POST /va/payment-notify          -->
<!-- ============================================================ -->
<div class="page-break"></div>
<div class="content-page">

  <div class="page-header">
    <div class="page-header-label">05 &bull; Endpoint</div>
    <div class="page-header-title">Payment Notification</div>
  </div>

  <div class="endpoint-card">
    <div class="endpoint-header post">
      <span class="endpoint-method method-post">POST</span>
      <span class="endpoint-path">/api/va/payment-notify</span>
      <div class="endpoint-desc">Mengirimkan notifikasi pembayaran VA ke sistem PPDB setelah transaksi berhasil dilakukan oleh nasabah</div>
    </div>
    <div class="endpoint-body">

      <div class="sub-section">
        <div class="sub-title">Request Body (JSON)</div>
        <table>
          <thead><tr><th>Field</th><th>Tipe</th><th>Status</th><th>Keterangan</th></tr></thead>
          <tbody>
            <tr>
              <td><code>va_number</code></td>
              <td><span class="badge badge-string">string</span></td>
              <td><span class="badge badge-required">Wajib</span></td>
              <td>Nomor Virtual Account yang dibayar (maks. 30 karakter)</td>
            </tr>
            <tr>
              <td><code>amount</code></td>
              <td><span class="badge badge-numeric">numeric</span></td>
              <td><span class="badge badge-required">Wajib</span></td>
              <td>Nominal yang dibayarkan dalam Rupiah (harus &gt; 0). Toleransi pembulatan ±1 Rupiah.</td>
            </tr>
            <tr>
              <td><code>paid_at</code></td>
              <td><span class="badge badge-datetime">datetime</span></td>
              <td><span class="badge badge-required">Wajib</span></td>
              <td>Waktu pembayaran. Format: ISO 8601 (<code>2026-08-14T20:30:00+07:00</code>) atau <code>Y-m-d H:i:s</code></td>
            </tr>
            <tr>
              <td><code>va_ref</code></td>
              <td><span class="badge badge-string">string</span></td>
              <td><span class="badge badge-optional">Opsional</span></td>
              <td>Nomor referensi transaksi dari bank/gateway (maks. 100 karakter)</td>
            </tr>
            <tr>
              <td><code>notes</code></td>
              <td><span class="badge badge-string">string</span></td>
              <td><span class="badge badge-optional">Opsional</span></td>
              <td>Catatan tambahan dari bank (maks. 500 karakter)</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="sub-section">
        <div class="sub-title">Contoh Request</div>
        <div class="code-block">curl -X POST \
  <span class="string">"https://ppdb.alhasra.sch.id/api/va/payment-notify"</span> \
  -H <span class="string">"Content-Type: application/json"</span> \
  -H <span class="string">"X-CLIENT-KEY: PPDBALHASRA"</span> \
  -H <span class="string">"X-TIMESTAMP: 2026-08-14T20:28:09+07:00"</span> \
  -H <span class="string">"X-SIGNATURE: a3f8c1d2e57b90..."</span> \
  -d <span class="string">'{
    "va_number": "88170484200000001",
    "amount":    250000,
    "paid_at":   "2026-08-14T20:30:00+07:00",
    "va_ref":    "TRX20260814BTN001",
    "notes":     "Pembayaran formulir PPDB 2026/2027"
  }'</span></div>
      </div>

    </div>
  </div>

  <div class="section">
    <div class="section-title green">Validasi Nominal Pembayaran</div>
    <p>Sistem akan membandingkan nominal yang dikirimkan (<code>amount</code>) dengan nominal tagihan
    yang tersimpan di database. Jika selisih lebih dari <strong>Rp 1</strong> (toleransi pembulatan),
    request akan ditolak dengan kode <code>4002501</code>.</p>
    <div class="code-block"><span class="comment">// Logika validasi nominal:</span>
abs(amount_dikirim - amount_tagihan) &lt;= 1  <span class="comment">// LOLOS</span>
abs(amount_dikirim - amount_tagihan) &gt;  1  <span class="comment">// DITOLAK — 422</span></div>
  </div>

  <div class="section">
    <div class="section-title purple">Logika Pemrosesan</div>
    <p>Ketika notifikasi diterima dan lolos validasi, sistem akan:</p>
    <ol style="margin: 6px 0 6px 18px; font-size: 9px; color:#334155; line-height: 2.0;">
      <li>Memperbarui <code>status</code> pembayaran menjadi <code>success</code></li>
      <li>Menyimpan <code>va_ref</code> (nomor referensi bank) jika diberikan</li>
      <li>Menyimpan <code>verified_at</code> dengan nilai dari field <code>paid_at</code></li>
      <li>Jika ini adalah pembayaran <strong>Formulir</strong> (fee pertama), status pendaftaran siswa akan diperbarui menjadi <code>success</code></li>
    </ol>
  </div>

</div>

<!-- ============================================================ -->
<!--  HALAMAN 8: RESPONSE PAYMENT NOTIFY                          -->
<!-- ============================================================ -->
<div class="page-break"></div>
<div class="content-page">

  <div class="page-header">
    <div class="page-header-label">05 &bull; Endpoint (Lanjutan)</div>
    <div class="page-header-title">Payment Notification — Response</div>
  </div>

  <div class="section">
    <div class="section-title green">Response Sukses — Pembayaran Berhasil Diproses</div>
    <div style="margin-bottom:4px;"><span class="badge badge-200">200 OK</span></div>
    <div class="code-block">{
  <span class="key">"status"</span>:          <span class="keyword">true</span>,
  <span class="key">"responseCode"</span>:    <span class="string">"2002500"</span>,
  <span class="key">"responseMessage"</span>: <span class="string">"Successful"</span>,
  <span class="key">"message"</span>:         <span class="string">"Pembayaran berhasil diproses."</span>,
  <span class="key">"data"</span>: {
    <span class="key">"payment_id"</span>:  <span class="number">42</span>,
    <span class="key">"va_number"</span>:   <span class="string">"88170484200000001"</span>,
    <span class="key">"nama_siswa"</span>:  <span class="string">"Ahmad Fauzan Hidayat"</span>,
    <span class="key">"fee_type"</span>:    <span class="string">"Formulir"</span>,
    <span class="key">"amount"</span>:      <span class="number">250000</span>,
    <span class="key">"paid_amount"</span>: <span class="number">250000</span>,
    <span class="key">"paid_at"</span>:     <span class="string">"2026-08-14T20:30:00+07:00"</span>,
    <span class="key">"status"</span>:      <span class="string">"success"</span>
  }
}</div>

    <div class="sub-section" style="margin-top:10px;">
      <div class="sub-title">Keterangan Field Response <code>data</code></div>
      <table>
        <thead><tr><th>Field</th><th>Tipe</th><th>Keterangan</th></tr></thead>
        <tbody>
          <tr><td><code>payment_id</code></td><td>integer</td><td>ID internal record pembayaran</td></tr>
          <tr><td><code>va_number</code></td><td>string</td><td>Nomor VA yang diproses</td></tr>
          <tr><td><code>nama_siswa</code></td><td>string</td><td>Nama calon siswa pemilik VA</td></tr>
          <tr><td><code>fee_type</code></td><td>string</td><td>Jenis biaya yang dibayar</td></tr>
          <tr><td><code>amount</code></td><td>float</td><td>Nominal tagihan di sistem</td></tr>
          <tr><td><code>paid_amount</code></td><td>float</td><td>Nominal yang dikirimkan bank</td></tr>
          <tr><td><code>paid_at</code></td><td>string</td><td>Waktu bayar yang dicatat</td></tr>
          <tr><td><code>status</code></td><td>string</td><td>Status setelah diproses (selalu <code>success</code>)</td></tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="section">
    <div class="section-title cyan">Response — Pembayaran Sudah Diproses Sebelumnya</div>
    <div style="margin-bottom:4px;"><span class="badge badge-200">200 OK</span></div>
    <div class="code-block">{
  <span class="key">"status"</span>:          <span class="keyword">true</span>,
  <span class="key">"responseCode"</span>:    <span class="string">"2002501"</span>,
  <span class="key">"responseMessage"</span>: <span class="string">"Payment Already Processed"</span>,
  <span class="key">"message"</span>:         <span class="string">"Pembayaran untuk VA ini sudah diproses sebelumnya."</span>,
  <span class="key">"data"</span>:            <span class="keyword">null</span>
}</div>
  </div>

  <div class="section">
    <div class="section-title red">Response Error</div>
    <table>
      <thead><tr><th>HTTP</th><th>responseCode</th><th>Kondisi</th></tr></thead>
      <tbody>
        <tr><td><span class="badge badge-401">401</span></td><td><code>4010000</code></td><td>Header autentikasi tidak lengkap</td></tr>
        <tr><td><span class="badge badge-401">401</span></td><td><code>4010001</code></td><td>X-CLIENT-KEY tidak dikenali</td></tr>
        <tr><td><span class="badge badge-401">401</span></td><td><code>4010002</code></td><td>Timestamp kadaluarsa (selisih &gt;5 menit)</td></tr>
        <tr><td><span class="badge badge-401">401</span></td><td><code>4010003</code></td><td>Signature tidak cocok</td></tr>
        <tr><td><span class="badge badge-422">422</span></td><td><code>4002500</code></td><td>Validasi input gagal (field wajib tidak ada / format salah)</td></tr>
        <tr><td><span class="badge badge-422">422</span></td><td><code>4002501</code></td><td>Nominal tidak sesuai tagihan (selisih &gt; Rp 1)</td></tr>
        <tr><td><span class="badge badge-404">404</span></td><td><code>4042512</code></td><td>VA tidak ditemukan atau tidak dalam status pending</td></tr>
      </tbody>
    </table>

    <div class="sub-section" style="margin-top:10px;">
      <div class="sub-title">Contoh Response Error — Validasi Gagal <span class="badge badge-422">422</span></div>
      <div class="code-block">{
  <span class="key">"status"</span>:          <span class="keyword">false</span>,
  <span class="key">"responseCode"</span>:    <span class="string">"4002500"</span>,
  <span class="key">"responseMessage"</span>: <span class="string">"Bad Request: Data tidak valid."</span>,
  <span class="key">"errors"</span>: {
    <span class="key">"paid_at"</span>: [<span class="string">"Format waktu pembayaran (paid_at) tidak valid."</span>]
  },
  <span class="key">"data"</span>: <span class="keyword">null</span>
}</div>
    </div>
  </div>

</div>

<!-- ============================================================ -->
<!--  HALAMAN 9: KODE RESPONS & RATE LIMITING                     -->
<!-- ============================================================ -->
<div class="page-break"></div>
<div class="content-page">

  <div class="page-header">
    <div class="page-header-label">06 &bull; Referensi</div>
    <div class="page-header-title">Kode Respons &amp; Rate Limiting</div>
  </div>

  <div class="section">
    <div class="section-title">Tabel Lengkap Kode Respons</div>
    <table class="rc-table">
      <thead>
        <tr><th>responseCode</th><th>HTTP Status</th><th>responseMessage</th><th>Keterangan</th></tr>
      </thead>
      <tbody>
        <tr><td><code>2002500</code></td><td><span class="badge badge-200">200</span></td><td>Successful</td><td>Request berhasil diproses</td></tr>
        <tr><td><code>2002501</code></td><td><span class="badge badge-200">200</span></td><td>Payment Already Processed</td><td>Pembayaran duplikat — sudah pernah diproses</td></tr>
        <tr><td><code>4000001</code></td><td><span class="badge badge-400">400</span></td><td>Bad Request</td><td>Format X-TIMESTAMP tidak valid</td></tr>
        <tr><td><code>4002500</code></td><td><span class="badge badge-422">422</span></td><td>Bad Request</td><td>Validasi input gagal / parameter wajib tidak ada</td></tr>
        <tr><td><code>4002501</code></td><td><span class="badge badge-422">422</span></td><td>Bad Request</td><td>Nominal pembayaran tidak sesuai tagihan</td></tr>
        <tr><td><code>4010000</code></td><td><span class="badge badge-401">401</span></td><td>Unauthorized</td><td>Header wajib tidak lengkap</td></tr>
        <tr><td><code>4010001</code></td><td><span class="badge badge-401">401</span></td><td>Unauthorized</td><td>X-CLIENT-KEY tidak valid</td></tr>
        <tr><td><code>4010002</code></td><td><span class="badge badge-401">401</span></td><td>Unauthorized</td><td>X-TIMESTAMP kadaluarsa</td></tr>
        <tr><td><code>4010003</code></td><td><span class="badge badge-401">401</span></td><td>Unauthorized</td><td>X-SIGNATURE tidak valid</td></tr>
        <tr><td><code>4042512</code></td><td><span class="badge badge-404">404</span></td><td>Bill not found</td><td>Nomor VA tidak ditemukan</td></tr>
        <tr><td><em>(429)</em></td><td><span class="badge badge-429">429</span></td><td>Too Many Requests</td><td>Rate limit terlampaui (60 req/menit)</td></tr>
      </tbody>
    </table>
  </div>

  <div class="section">
    <div class="section-title orange">Rate Limiting (07)</div>
    <p>Semua endpoint VA dibatasi maksimal <strong>60 request per menit per IP address</strong>.
    Jika batas terlampaui, server akan mengembalikan HTTP <span class="badge badge-429">429 Too Many Requests</span>.</p>

    <table style="margin-top: 10px;">
      <thead><tr><th>Header Response</th><th>Keterangan</th></tr></thead>
      <tbody>
        <tr><td><code>X-RateLimit-Limit</code></td><td>Batas maksimum request per window (60)</td></tr>
        <tr><td><code>X-RateLimit-Remaining</code></td><td>Sisa request yang tersedia di window saat ini</td></tr>
        <tr><td><code>Retry-After</code></td><td>Detik yang harus ditunggu sebelum request kembali diterima</td></tr>
      </tbody>
    </table>

    <div class="alert alert-warning" style="margin-top: 12px;">
      <div class="alert-title">&#x26A0; Rekomendasi Implementasi</div>
      Pastikan sistem Anda menangani response <strong>429</strong> dengan melakukan retry menggunakan
      <em>exponential backoff</em>. Jangan langsung melakukan retry terus-menerus karena dapat
      memperpanjang periode pemblokiran.
    </div>
  </div>

  <div class="section">
    <div class="section-title">Format Response Umum</div>
    <p>Semua response mengikuti struktur JSON standar berikut:</p>
    <div class="code-block">{
  <span class="key">"status"</span>:          <span class="keyword">boolean</span>,  <span class="comment">// true = sukses, false = gagal</span>
  <span class="key">"responseCode"</span>:    <span class="string">"string"</span>,  <span class="comment">// 7 digit kode respons</span>
  <span class="key">"responseMessage"</span>: <span class="string">"string"</span>,  <span class="comment">// pesan singkat (bahasa Inggris)</span>
  <span class="key">"message"</span>:         <span class="string">"string"</span>,  <span class="comment">// pesan detail (bahasa Indonesia)</span>
  <span class="key">"data"</span>:            <span class="keyword">object</span>|<span class="keyword">null</span>  <span class="comment">// payload data (null jika error)</span>
}</div>
  </div>

</div>

<!-- ============================================================ -->
<!--  HALAMAN 10: CONTOH IMPLEMENTASI LENGKAP                     -->
<!-- ============================================================ -->
<div class="page-break"></div>
<div class="content-page">

  <div class="page-header">
    <div class="page-header-label">08 &bull; Implementasi</div>
    <div class="page-header-title">Contoh Kode Lengkap</div>
  </div>

  <div class="section">
    <div class="section-title">PHP — Inquiry + Payment Notify</div>
    <div class="code-block"><span class="keyword">class</span> VaApiClient
{
    <span class="keyword">private</span> <span class="keyword">string</span> $baseUrl    = <span class="string">'https://ppdb.alhasra.sch.id/api'</span>;
    <span class="keyword">private</span> <span class="keyword">string</span> $clientKey = <span class="string">'PPDBALHASRA'</span>;
    <span class="keyword">private</span> <span class="keyword">string</span> $secret    = <span class="string">'YOUR_CLIENT_SECRET'</span>;

    <span class="keyword">private function</span> buildHeaders(): array
    {
        $timestamp = (new DateTime())->format(DateTime::ATOM);
        $signature = hash_hmac(<span class="string">'sha256'</span>, $this->clientKey.<span class="string">'|'</span>.$timestamp, $this->secret);
        return [
            <span class="string">'Content-Type: application/json'</span>,
            <span class="string">'X-CLIENT-KEY: '</span> . $this->clientKey,
            <span class="string">'X-TIMESTAMP: '</span>  . $timestamp,
            <span class="string">'X-SIGNATURE: '</span>  . $signature,
        ];
    }

    <span class="keyword">public function</span> inquiryVa(string $vaNumber): array
    {
        $ch = curl_init($this->baseUrl.<span class="string">'/va/'</span>.$vaNumber);
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER     => $this->buildHeaders(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
        ]);
        return json_decode(curl_exec($ch), true);
    }

    <span class="keyword">public function</span> notifyPayment(array $data): array
    {
        $ch = curl_init($this->baseUrl.<span class="string">'/va/payment-notify'</span>);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($data),
            CURLOPT_HTTPHEADER     => $this->buildHeaders(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
        ]);
        return json_decode(curl_exec($ch), true);
    }
}

<span class="comment">// Penggunaan:</span>
$client = new VaApiClient();

<span class="comment">// 1. Inquiry</span>
$inquiry = $client->inquiryVa(<span class="string">'88170484200000001'</span>);

<span class="comment">// 2. Notifikasi bayar</span>
$result = $client->notifyPayment([
    <span class="string">'va_number'</span> => <span class="string">'88170484200000001'</span>,
    <span class="string">'amount'</span>    => <span class="number">250000</span>,
    <span class="string">'paid_at'</span>   => (new DateTime())->format(DateTime::ATOM),
    <span class="string">'va_ref'</span>    => <span class="string">'TRX20260814BTN001'</span>,
]);</div>
  </div>

  <div class="section">
    <div class="section-title purple">Python — Payment Notification</div>
    <div class="code-block"><span class="keyword">import</span> hmac, hashlib, json, requests
<span class="keyword">from</span> datetime <span class="keyword">import</span> datetime, timezone, timedelta

BASE_URL   = <span class="string">"https://ppdb.alhasra.sch.id/api"</span>
CLIENT_KEY = <span class="string">"PPDBALHASRA"</span>
SECRET     = <span class="string">"YOUR_CLIENT_SECRET"</span>

<span class="keyword">def</span> build_headers():
    ts  = datetime.now(timezone(timedelta(hours=7))).isoformat()
    sig = hmac.new(SECRET.encode(), <span class="string">f"</span>{CLIENT_KEY}<span class="string">|</span>{ts}<span class="string">"</span>.encode(), hashlib.sha256).hexdigest()
    <span class="keyword">return</span> {<span class="string">"Content-Type"</span>:<span class="string">"application/json"</span>, <span class="string">"X-CLIENT-KEY"</span>:CLIENT_KEY,
            <span class="string">"X-TIMESTAMP"</span>:ts, <span class="string">"X-SIGNATURE"</span>:sig}

payload = {<span class="string">"va_number"</span>:<span class="string">"88170484200000001"</span>,<span class="string">"amount"</span>:<span class="number">250000</span>,
           <span class="string">"paid_at"</span>:<span class="string">"2026-08-14T20:30:00+07:00"</span>,<span class="string">"va_ref"</span>:<span class="string">"TRX2026BTN001"</span>}

resp = requests.post(<span class="string">f"</span>{BASE_URL}<span class="string">/va/payment-notify"</span>,
                     headers=build_headers(), json=payload, timeout=30)
print(resp.json())</div>
  </div>

</div>

<!-- ============================================================ -->
<!--  HALAMAN 11: ALUR PROSES & KONTAK                            -->
<!-- ============================================================ -->
<div class="page-break"></div>
<div class="content-page">

  <div class="page-header">
    <div class="page-header-label">09 &bull; Alur Sistem</div>
    <div class="page-header-title">Alur Proses Pembayaran Virtual Account</div>
  </div>

  <div class="section">
    <div class="section-title">Sequence: Inquiry &rarr; Bayar &rarr; Notifikasi</div>

    <table style="margin-top: 8px;">
      <thead>
        <tr>
          <th style="width:5%">#</th>
          <th style="width:20%">Pihak</th>
          <th style="width:75%">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="background:#eff6ff;color:#1d4ed8;font-weight:bold;text-align:center;">1</td>
          <td>Nasabah</td>
          <td>Memasukkan nomor VA di ATM / Mobile Banking / Internet Banking</td>
        </tr>
        <tr>
          <td style="background:#f0f9ff;color:#0369a1;font-weight:bold;text-align:center;">2</td>
          <td><strong>Bank → PPDB API</strong></td>
          <td><code>GET /api/va/{va_number}</code> — Bank mengambil data tagihan (nama, nominal)</td>
        </tr>
        <tr>
          <td style="background:#f0fdf4;color:#166534;font-weight:bold;text-align:center;">3</td>
          <td>PPDB API → Bank</td>
          <td>Mengembalikan data: nama siswa, fee type, nominal tagihan, status</td>
        </tr>
        <tr>
          <td style="background:#eff6ff;color:#1d4ed8;font-weight:bold;text-align:center;">4</td>
          <td>Nasabah</td>
          <td>Mengkonfirmasi pembayaran dan transaksi diproses bank</td>
        </tr>
        <tr>
          <td style="background:#fff7ed;color:#c2410c;font-weight:bold;text-align:center;">5</td>
          <td><strong>Bank → PPDB API</strong></td>
          <td><code>POST /api/va/payment-notify</code> — Bank mengirimkan notifikasi pembayaran berhasil</td>
        </tr>
        <tr>
          <td style="background:#f0fdf4;color:#166534;font-weight:bold;text-align:center;">6</td>
          <td>PPDB API</td>
          <td>Memperbarui status pembayaran ke <code>success</code>, mencatat waktu &amp; referensi transaksi</td>
        </tr>
        <tr>
          <td style="background:#faf5ff;color:#6b21a8;font-weight:bold;text-align:center;">7</td>
          <td>PPDB API → Bank</td>
          <td>Mengembalikan konfirmasi sukses beserta detail pembayaran yang telah direkam</td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="section">
    <div class="section-title orange">Catatan Penting Implementasi</div>
    <div class="alert alert-info">
      <div class="alert-title">&#x2139; Idempotency</div>
      Endpoint payment-notify bersifat <strong>idempotent</strong>: jika VA yang sama dikirimkan dua kali,
      request kedua akan mengembalikan <code>2002501 — Payment Already Processed</code> tanpa mengubah data.
      Ini mencegah pembayaran ganda (double payment).
    </div>
    <div class="alert alert-warning" style="margin-top: 8px;">
      <div class="alert-title">&#x26A0; Validasi Waktu Server</div>
      Pastikan waktu server Anda tersinkronisasi dengan NTP (Network Time Protocol). Header
      <code>X-TIMESTAMP</code> harus berada dalam toleransi <strong>±5 menit</strong> dari waktu server PPDB.
      Perbedaan zona waktu harus dihandle dengan format ISO 8601 yang menyertakan offset timezone
      (contoh: <code>+07:00</code>).
    </div>
  </div>

  <div class="page-break"></div>
  <div style="margin-top: 0;">
    <div class="page-header">
      <div class="page-header-label">10 &bull; Dukungan Teknis</div>
      <div class="page-header-title">Kontak &amp; Informasi Tambahan</div>
    </div>

    <div class="section">
      <div class="section-title cyan">Tim Teknis PPDB Al-Hasra</div>
      <table>
        <thead><tr><th>Informasi</th><th>Detail</th></tr></thead>
        <tbody>
          <tr><td><strong>Institusi</strong></td><td>PPDB Al-Hasra</td></tr>
          <tr><td><strong>URL Sistem</strong></td><td><code>https://ppdb.alhasra.sch.id</code></td></tr>
          <tr><td><strong>Bank Mitra</strong></td><td>BTN (Bank Tabungan Negara)</td></tr>
          <tr><td><strong>Standar API</strong></td><td>SNAP BI (Standar Nasional Open API Pembayaran)</td></tr>
          <tr><td><strong>Framework</strong></td><td>Laravel 13 (PHP 8.3+)</td></tr>
        </tbody>
      </table>
    </div>

    <div class="section">
      <div class="section-title">Informasi Dokumen</div>
      <table>
        <thead><tr><th>Atribut</th><th>Nilai</th></tr></thead>
        <tbody>
          <tr><td>Versi Dokumen</td><td>v1.0.0</td></tr>
          <tr><td>Tanggal Terbit</td><td>{{ date('d F Y') }}</td></tr>
          <tr><td>Status</td><td>Final — Siap Integrasi</td></tr>
          <tr><td>Confidentiality</td><td>Rahasia — Hanya untuk tim teknis yang berwenang</td></tr>
        </tbody>
      </table>
    </div>

    <div class="alert alert-danger" style="margin-top: 16px;">
      <div class="alert-title">&#x1F512; Keamanan Kredensial API</div>
      Jika Anda mencurigai <strong>CLIENT_SECRET</strong> telah bocor atau diketahui pihak tidak berwenang,
      segera hubungi tim teknis PPDB Al-Hasra untuk melakukan rotasi kredensial. API yang dikompromikan
      dapat menyebabkan manipulasi data pembayaran siswa.
    </div>

    <div class="doc-footer">
      <div class="confidential">&#x25CF; DOKUMEN RAHASIA &#x25CF; HANYA UNTUK KEPERLUAN INTEGRASI TEKNIS &#x25CF;</div>
      Virtual Account API Documentation v1.0.0 &bull; PPDB Al-Hasra &bull; {{ date('Y') }} &bull; Dibuat: {{ date('d M Y H:i') }} WIB
    </div>
  </div>

</div>

</body>
</html>
