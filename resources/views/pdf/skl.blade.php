<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Keterangan Lulus - {{ $user->name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: #fff;
        }
        
        /* Premium Background using absolute positioning since complex borders are buggy in DomPdf */
        .wrapper {
            margin: 0 auto;
            border: 4px double #10b981;
            padding: 2px;
            position: relative;
        }

        .inner-wrapper {
            border: 1px solid #10b981;
            padding: 40px;
            background: url('data:image/svg+xml;utf8,<svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="dots" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1" fill="#f0fdf4"/></pattern></defs><rect width="100%" height="100%" fill="url(%23dots)"/></svg>') repeat;
        }

        /* Header Style */
        .header {
            text-align: center;
            border-bottom: 3px solid #10b981;
            padding-bottom: 20px;
            margin-bottom: 30px;
            position: relative;
        }

        .header h1 {
            color: #047857;
            font-size: 28px;
            margin: 0 0 10px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .header p {
            margin: 0;
            font-size: 14px;
            color: #64748b;
        }

        /* Certificate Title */
        .title-box {
            text-align: center;
            margin: 40px 0;
        }

        .title-box h2 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 4px;
            color: #064e3b;
        }

        .title-box p {
            margin: 5px 0 0 0;
            font-size: 14px;
            font-weight: bold;
            color: #0d9488;
        }

        /* Student Information Profile */
        .student-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .student-info td {
            padding: 10px;
            font-size: 15px;
            border-bottom: 1px dashed #cbd5e1;
            vertical-align: top;
        }

        .student-info td.label {
            font-weight: bold;
            color: #475569;
            width: 30%;
        }

        .student-info td.separator {
            width: 5%;
            color: #cbd5e1;
            text-align: center;
        }

        .student-info td.value {
            font-weight: bold;
            color: #0f172a;
            width: 65%;
        }

        /* Content Declaration */
        .declaration {
            text-align: justify;
            line-height: 1.8;
            font-size: 15px;
            margin-bottom: 40px;
            color: #334155;
        }

        .highlight {
            color: #047857;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 18px;
            text-align: center;
            display: block;
            margin: 20px 0;
            letter-spacing: 5px;
        }

        /* Footer / Signatures */
        .footer {
            width: 100%;
            margin-top: 50px;
        }

        .signature-box {
            float: right;
            text-align: center;
            width: 250px;
        }

        .signature-box p {
            margin: 0;
            font-size: 14px;
        }

        .signature-space {
            height: 80px;
            /* Placeholder for stamp / signature */
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        /* Footer Note */
        .notes {
            clear: both;
            margin-top: 80px;
            font-size: 11px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            font-style: italic;
        }

        /* Utility */
        .text-center { text-align: center; }
        .mt-4 { margin-top: 20px; }
        .mb-2 { margin-bottom: 10px; }
    </style>
</head>
<body>

    <div class="wrapper">
        <div class="inner-wrapper">
            
            {{-- Header --}}
            <div class="header">
                <h1>PANITIA PENERIMAAN PESERTA DIDIK BARU</h1>
                <p>Sistem Informasi Akademik Terpadu (PPDB APP)</p>
                <p style="font-size: 12px; margin-top: 5px;">Tahun Ajaran {{ $registration->academicYear->name }}</p>
            </div>

            {{-- Title --}}
            <div class="title-box">
                <h2>Surat Keterangan Lulus</h2>
                <p>Nomor: SKL/{{ str_pad($registration->id, 4, '0', STR_PAD_LEFT) }}/{{ date('Y') }}</p>
            </div>

            {{-- Opening Declaration --}}
            <div class="declaration">
                Berdasarkan hasil seleksi dan evaluasi yang telah dilakukan oleh Panitia Penerimaan Peserta Didik Baru (PPDB), maka dengan ini kami menerangkan bahwa pendaftar di bawah ini:
            </div>

            {{-- Student Details --}}
            <table class="student-info">
                <tr>
                    <td class="label">Nomor Registrasi</td>
                    <td class="separator">:</td>
                    <td class="value">#REG-{{ str_pad($registration->id, 5, '0', STR_PAD_LEFT) }}</td>
                </tr>
                <tr>
                    <td class="label">Nama Lengkap</td>
                    <td class="separator">:</td>
                    <td class="value" style="text-transform: uppercase;">{{ $user->full_name ?? $user->name }}</td>
                </tr>
                <tr>
                    <td class="label">Tempat, Tanggal Lahir</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $registration->tempat_lahir }}, {{ date('d F Y', strtotime($registration->tanggal_lahir)) }}</td>
                </tr>
                <tr>
                    <td class="label">Asal Sekolah</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $user->asal_sekolah ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Unit Tujuan</td>
                    <td class="separator">:</td>
                    <td class="value">{{ $user->educationalLevel?->name }}</td>
                </tr>
            </table>

            {{-- Outcome Declaration --}}
            <div class="declaration">
                Dinyatakan <span class="highlight">LULUS</span>
                sebagai Peserta Didik Baru Unit <strong>{{ $user->educationalLevel?->name }}</strong> Tahun Ajaran {{ $registration->academicYear->name }}.<br><br>
                Selanjutnya, peserta didik wajib melakukan proses <strong>Daftar Ulang</strong> sesuai dengan batas waktu yang telah ditetapkan. Kelalaian dalam melakukan daftar ulang dapat berakibat pada pembatalan status kelulusan.
            </div>

            {{-- Signatures --}}
            <div class="footer">
                <div class="signature-box">
                    <p>Ditetapkan di: Kota Pendidikan</p>
                    <p>Tanggal: {{ date('d F Y') }}</p>
                    <p class="mt-4">Ketua Panitia PPDB,</p>
                    
                    <div class="signature-space">
                        <!-- Space for Stamp/Signature -->
                    </div>
                    
                    <p class="signature-name">Super Admin PPDB</p>
                    <p style="font-size: 12px; margin-top: 5px;">NIDN. 19890101 201404 1 001</p>
                </div>
            </div>

            {{-- Auto-generated notes --}}
            <div class="notes">
                * Dokumen ini dibuat dan divalidasi secara elektronik oleh Sistem PPDB.<br>
                * Dokumen ini sah dan dapat dipertanggungjawabkan tanpa tanda tangan basah.<br>
                * Batas Pelunasan Daftar Ulang: {{ $registration->reregistration_deadline ? \Carbon\Carbon::parse($registration->reregistration_deadline)->translatedFormat('d F Y') : 'Sesuai dengan ketentuan yang berlaku.' }}
            </div>

        </div>
    </div>

</body>
</html>
