<p align="center">
  <img src="public/images/banner.png" alt="PPDB Online Banner" width="100%">
</p>

<h1 align="center">PPDB Online — BTN VA Integrated</h1>

<p align="center">
  <strong>Sistem Penerimaan Peserta Didik Baru — Modern, Cepat & Terintegrasi Bank BTN</strong>
</p>

<p align="center">
  <a href="#"><img src="https://img.shields.io/badge/Laravel-13-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 13"></a>
  <a href="#"><img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+"></a>
  <a href="#"><img src="https://img.shields.io/badge/TailwindCSS-4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind CSS"></a>
  <a href="#"><img src="https://img.shields.io/badge/Alpine.js-3-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white" alt="Alpine.js"></a>
  <a href="#"><img src="https://img.shields.io/badge/BTN_VA-Integrated-00529C?style=for-the-badge" alt="BTN VA"></a>
</p>

<p align="center">
  Platform manajemen pendaftaran siswa baru multi-jenjang (SMP, SMA, SMK) dengan integrasi <strong>Virtual Account Bank BTN</strong>. Mendukung otomatisasi verifikasi pembayaran melalui sistem <em>Inquiry</em> dan <em>Callback (Webhook)</em>.
</p>

---

## ✨ Fitur Unggulan Terkini

| | Fitur | Keterangan |
|---|---|---|
| 💳 | **Integrasi VA BTN** | Pembuatan Virtual Account otomatis (17 digit) untuk biaya pendaftaran & daftar ulang |
| 🔄 | **Auto-Verify** | Verifikasi pembayaran realtime melalui tombol "Cek Status" (Inquiry API) |
| 🌐 | **Webhook Callback** | Pembaruan status pembayaran otomatis dari bank ke sistem tanpa campur tangan admin |
| 📊 | **1000+ Dummy Data** | Seeder pintar untuk simulasi data pendaftar dalam jumlah besar yang saling terintegrasi |
| 🏫 | **Multi-Unit Scope** | Isolasi data ketat antara unit SMP, SMA, dan SMK dalam satu dashboard |
| 🎨 | **Premium UI** | Antarmuka berbasis Glassmorphism dengan tema dinamis & micro-animations |

---

## 🖥️ Demo & Akun Testing

Setelah melakukan instalasi dan seeding, gunakan akun berikut:

| Role | Email | Password |
|---|---|---|
| **Super Admin** | `admin@ppdb.com` | `password` |
| **Admin Unit (SMP)** | `admin.smp@ppdb.com` | `password` |
| **Admin Keuangan** | `admin.adm@ppdb.com` | `password` |
| **Siswa (Dummy)** | *(Cek tabel `users`)* | `password123` |

---

## 🏗️ Modul Utama

### 📋 Portal Siswa (Student Portal)
- **Registrasi & Login** — Alur pembuatan akun yang simpel.
- **Formulir Pendaftaran** — Validasi biodata, data orang tua, dan asal sekolah.
- **Finansial VA** — Generate VA BTN untuk biaya formulir & biaya masuk. Siswa bisa cek status lunas secara mandiri.
- **Download Center** — Cetak Kartu Ujian dan SKL (Surat Keterangan Lulus) dalam format PDF premium.

### 💰 Modul Keuangan & VA BTN
- **Inquiry Status** — Admin Keuangan dapat memverifikasi pembayaran VA secara massal atau per individu langsung ke API BTN.
- **Master Biaya** — Pengaturan nominal biaya pendaftaran dan daftar ulang per jenjang (SMP/SMA/SMK).
- **Callback Listener** — Endpoint API `/api/btn/callback` yang siap menerima notifikasi pelunasan dari Bank.

### 🛡️ Modul Admin Unit & Super Admin
- **Verifikasi Peserta** — Manajemen status kelulusan dan seleksi berkas.
- **Jadwal Ujian** — Pengaturan sesi ujian yang terintegrasi dengan kartu peserta.
- **Konfigurasi Sistem** — Ubah Logo, Nama Aplikasi, Kontak WA, dan Background Tema tanpa sentuh kode.

---

## 🚀 Instalasi & Setup

### 1. Persyaratan Sistem
- PHP ≥ 8.2
- MySQL / MariaDB
- Node.js & NPM
- Composer

### 2. Langkah Cepat (Fresh Install)

```bash
# Clone & Install
git clone https://github.com/your-repo/ppdb-app.git
cd ppdb-app
composer install && npm install

# Setup Env
cp .env.example .env
php artisan key:generate

# Database & Seeding
# Pastikan DB_DATABASE sudah dibuat di MySQL
php artisan migrate
php artisan db:seed --class=DatabaseSeeder      # Data Master (Role, Jenjang, Biaya)
php artisan db:seed --class=DummyDataSeeder    # Opsional: 1000 data siswa dummy
```

### 3. Konfigurasi BTN ( .env )
Tambahkan kredensial BTN Anda untuk mengaktifkan fitur Virtual Account:
```env
BTN_ID=your_id
BTN_KEY=your_key
BTN_SECRET=your_secret
BTN_BASE_URL=https://dev.btn.co.id/api
BTN_KODE_INSTITUSI=4842
```

---

## 📝 Alur Pembayaran Virtual Account

1.  **Generate**: Siswa menekan tombol "Bayar via VA BTN". Sistem mengirim request ke API BTN dan menyimpan nomor VA 17-digit.
2.  **Payment**: Siswa melakukan pembayaran melalui ATM/Mobile Banking BTN atau Bank lain.
3.  **Verification**: 
    *   **Manual**: Siswa/Admin menekan tombol "Cek Status" (Inquiry).
    *   **Otomatis**: Bank BTN mengirimkan data pelunasan ke endpoint Callback aplikasi.
4.  **Update**: Status pendaftaran siswa otomatis berubah menjadi "Success" dan fitur download Kartu Ujian terbuka.

---

## 🧱 Tech Stack & Library
- **Framework**: Laravel 13
- **UI**: Tailwind CSS 4, Alpine.js
- **Integrasi Bank**: BTN Legacy API (Signature SHA-256)
- **PDF**: Barryvdh DomPDF
- **Icons**: Lucide Icons & FontAwesome

---

<p align="center">
  Dibuat dengan ❤️ oleh <strong>Tim IT Yayasan</strong>
</p>
