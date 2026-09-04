<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use ZipArchive;
use App\Models\AcademicYear;
use App\Models\Payment;
use App\Models\Registration;
use Exception;

class BackupController extends Controller
{
    public function index()
    {
        return view('admin.backup.index');
    }

    public function downloadDatabase()
    {
        $databaseName = env('DB_DATABASE');
        
        $fileName = 'backup_db_' . date('Y-m-d_H-i-s') . '.sql';
        $backupPath = storage_path('app/temp/' . $fileName);
        
        if (!File::isDirectory(storage_path('app/temp'))) {
            File::makeDirectory(storage_path('app/temp'), 0755, true, true);
        }

        try {
            $tables = DB::select('SHOW TABLES');
            $sql = "-- PPDB Online Database Backup\n";
            $sql .= "-- Generated at: " . date('Y-m-d H:i:s') . "\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tables as $table) {
                $tableName = json_decode(json_encode($table), true);
                $tableName = array_values($tableName)[0];
                
                // Get table structure
                $createTableQuery = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $createTableSql = json_decode(json_encode($createTableQuery[0]), true)['Create Table'];
                
                $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $sql .= "{$createTableSql};\n\n";
                
                // Get table data
                $rows = DB::table($tableName)->get();
                if ($rows->count() > 0) {
                    foreach ($rows as $row) {
                        $rowArray = json_decode(json_encode($row), true);
                        $keys = array_keys($rowArray);
                        $values = array_map(function ($value) {
                            if (is_null($value)) return 'NULL';
                            $value = addslashes($value);
                            $value = str_replace("\n", "\\n", $value);
                            return "'" . $value . "'";
                        }, array_values($rowArray));
                        
                        $sql .= "INSERT INTO `{$tableName}` (`" . implode("`, `", $keys) . "`) VALUES (" . implode(", ", $values) . ");\n";
                    }
                    $sql .= "\n";
                }
            }
            
            $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
            
            File::put($backupPath, $sql);
            
            return response()->download($backupPath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membackup database: ' . $e->getMessage());
        }
    }

    public function downloadProofs()
    {
        // Cari tahun ajaran aktif
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return back()->with('error', 'Tidak ada Tahun Ajaran aktif.');
        }

        // Ambil semua registrasi tahun ajaran aktif yang memiliki file bukti bayar
        $registrations = Registration::where('academic_year_id', $activeYear->id)
            ->whereNotNull('payment_proof')->get();

        if ($registrations->isEmpty()) {
            return back()->with('error', 'Tidak ada bukti pembayaran untuk didownload pada tahun ajaran aktif ini.');
        }

        $zipFileName = 'backup_proofs_' . date('Y-m-d_H-i-s') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        if (!File::isDirectory(storage_path('app/temp'))) {
            File::makeDirectory(storage_path('app/temp'), 0755, true, true);
        }

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach ($registrations as $registration) {
                // Asumsi field payment_proof menyimpan 'payments/namafile.jpg'
                // Di RegistrationController disimpan di storage_path('app/public/payments') -> tidak lewat path public storage di controller?
                // Tunggu, kalau nama filenya cuma nama filenya saja, mari kita asumsikan 'payments/'.$registration->payment_proof
                $proofPath = storage_path('app/public/' . $registration->payment_proof);
                
                // Jika file exist
                if (file_exists($proofPath)) {
                    $relativeNameInZip = basename($proofPath);
                    $zip->addFile($proofPath, $relativeNameInZip);
                } else {
                    // Cek jika field hanya menyimpan filename atau folder/filename
                    $altProofPath = storage_path('app/public/payments/' . basename($registration->payment_proof));
                    if (file_exists($altProofPath)) {
                        $zip->addFile($altProofPath, basename($altProofPath));
                    }
                }
            }
            $zip->close();
        } else {
            return back()->with('error', 'Gagal membuat file ZIP.');
        }

        if (!file_exists($zipPath)) {
            return back()->with('error', 'ZIP kosong karena file fisik tidak ditemukan di server.');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function restoreDatabase(Request $request)
    {
        $request->validate([
            'sql_file' => 'required|file|mimetypes:text/plain,application/sql,application/octet-stream|max:102400', // 100MB Max
        ]);

        try {
            $file = $request->file('sql_file');
            $realPath = $file->getRealPath();
            if ($realPath === false && isset($_FILES['sql_file']) && $_FILES['sql_file']['error'] === UPLOAD_ERR_OK) {
                $realPath = $_FILES['sql_file']['tmp_name'];
            }
            $sqlContent = file_get_contents($realPath);

            // Nonaktifkan pemeriksaan kunci asing sementara
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            DB::unprepared($sqlContent);
            
            // Kembalikan pemeriksaan kunci asing
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            return back()->with('success', 'Database berhasil di-restore dari file SQL.');
        } catch (Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return back()->with('error', 'Gagal me-restore database: ' . $e->getMessage());
        }
    }

    public function restoreProofs(Request $request)
    {
        $request->validate([
            'zip_file' => 'required|file|mimes:zip|max:204800', // 200MB Max
        ]);

        try {
            $file = $request->file('zip_file');
            $realPath = $file->getRealPath();
            if ($realPath === false && isset($_FILES['zip_file']) && $_FILES['zip_file']['error'] === UPLOAD_ERR_OK) {
                $realPath = $_FILES['zip_file']['tmp_name'];
            }
            
            $zip = new ZipArchive;
            if ($zip->open($realPath) === TRUE) {
                // Tentukan lokasi target ekstrak
                $targetPath = storage_path('app/public/payments');
                if (!File::isDirectory($targetPath)) {
                    File::makeDirectory($targetPath, 0755, true, true);
                }
                
                $zip->extractTo($targetPath);
                $zip->close();
                
                return back()->with('success', 'Bukti pembayaran berhasil di-restore.');
            } else {
                return back()->with('error', 'Gagal membuka file ZIP.');
            }
        } catch (Exception $e) {
            return back()->with('error', 'Gagal me-restore bukti pembayaran: ' . $e->getMessage());
        }
    }
}
