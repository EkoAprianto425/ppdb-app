<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kartu Ujian - {{ $user->name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            background-color: #f9fafb;
        }
        .card {
            background: #ffffff;
            border-radius: 15px;
            padding: 30px;
            max-width: 600px;
            margin: 0 auto;
            border: 2px solid #e5e7eb;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05); /* dompdf might ignore shadow but it's safe to add */
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #1e3a8a;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header p {
            margin: 5px 0 0;
            color: #6b7280;
            font-size: 14px;
        }
        .content {
            width: 100%;
        }
        .photo-container {
            width: 120px;
            height: 160px;
            background: #f3f4f6;
            border: 2px dashed #cbd5e1;
            text-align: center;
            line-height: 160px;
            color: #94a3b8;
            font-size: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .details-table td {
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .details-table td.label {
            width: 35%;
            font-weight: bold;
            color: #64748b;
            font-size: 14px;
            text-transform: uppercase;
        }
        .details-table td.value {
            font-weight: bold;
            color: #0f172a;
            font-size: 16px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
        }
        .badge {
            background: #eff6ff;
            color: #2563eb;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>KARTU UJIAN PPDB</h1>
            <p>Tahun Ajaran {{ $schedule->academicYear?->year ?? '2024/2025' }}</p>
        </div>

        <table style="width: 100%;">
            <tr>
                <td style="width: 140px; vertical-align: top;">
                    <div class="photo-container">
                        Pas Foto 3x4
                    </div>
                </td>
                <td style="vertical-align: top;">
                    <table class="details-table">
                        <tr>
                            <td class="label">No. Registrasi</td>
                            <td class="value">REG-{{ str_pad($registration->id, 5, '0', STR_PAD_LEFT) }}</td>
                        </tr>
                        <tr>
                            <td class="label">Nama Lengkap</td>
                            <td class="value">{{ strtoupper($user->full_name) }}</td>
                        </tr>
                        <tr>
                            <td class="label">Unit Pilihan</td>
                            <td class="value">
                                <span class="badge">{{ strtoupper($user->getUnit()) }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        
        <div style="margin-top: 20px; background: #f8fafc; padding: 20px; border-radius: 10px; border-left: 4px solid #3b82f6;">
            <h3 style="margin-top: 0; color: #1e40af; font-size: 16px; text-transform: uppercase;">Jadwal Seleksi</h3>
            <table class="details-table" style="margin-top: 0;">
                <tr>
                    <td class="label" style="width: 30%;">Gelombang</td>
                    <td class="value">{{ $schedule->name }}</td>
                </tr>
                <tr>
                    <td class="label">Tanggal</td>
                    <td class="value">{{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('l, d F Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Waktu</td>
                    <td class="value">{{ \Carbon\Carbon::parse($schedule->time_start)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->time_end)->format('H:i') }} WIB</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Kartu ini wajib dibawa saat pelaksanaan ujian seleksi.</p>
            <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
