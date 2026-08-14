<?php
/**
 * Script untuk import data dari daftar-sekolah.sql ke tabel sekolah
 * Kolom `id` dari SQL file -> kolom `sekolah_id` di tabel (varchar UUID)
 * Kolom `id` di tabel adalah auto-increment bigint, dibiarkan auto
 */

$sqlFile = __DIR__ . '/daftar-sekolah.sql';
$host    = '127.0.0.1';
$port    = '3306';
$dbname  = 'ppdb_app';
$user    = 'root';
$pass    = '';

echo "=== Import daftar-sekolah.sql ke tabel sekolah ===" . PHP_EOL;
echo "Koneksi ke database $dbname..." . PHP_EOL;

$pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

// Kosongkan tabel sekolah dulu
echo "Mengosongkan tabel sekolah (TRUNCATE)..." . PHP_EOL;
$pdo->exec("SET FOREIGN_KEY_CHECKS=0");
$pdo->exec("TRUNCATE TABLE sekolah");
$pdo->exec("SET FOREIGN_KEY_CHECKS=1");

echo "Membaca file SQL (~51MB)..." . PHP_EOL;

$handle = fopen($sqlFile, 'r');
if (!$handle) {
    die("ERROR: File tidak ditemukan: $sqlFile\n");
}

$inserted    = 0;
$errors      = 0;
$commitEvery = 5000; // commit tiap N baris

$pdo->beginTransaction();

// Kolom di SQL file (urutan): kode_prop, propinsi, kode_kab_kota, kabupaten_kota,
//   kode_kec, kecamatan, id, npsn, sekolah, bentuk, status, alamat_jalan, lintang, bujur
// Mapping ke tabel: id -> sekolah_id

$stmt = $pdo->prepare(
    "INSERT INTO sekolah 
        (sekolah_id, kode_prop, propinsi, kode_kab_kota, kabupaten_kota, kode_kec, kecamatan, npsn, sekolah, bentuk, status, alamat_jalan, lintang, bujur)
     VALUES 
        (:sekolah_id, :kode_prop, :propinsi, :kode_kab_kota, :kabupaten_kota, :kode_kec, :kecamatan, :npsn, :sekolah, :bentuk, :status, :alamat_jalan, :lintang, :bujur)"
);

$buffer = '';
$inInsert = false;

while (!feof($handle)) {
    $line = fgets($handle);
    $trimmed = ltrim($line);

    // Deteksi baris INSERT INTO
    if (stripos($trimmed, 'INSERT INTO `sekolah`') === 0) {
        $buffer = $trimmed;
        $inInsert = true;
    } elseif ($inInsert) {
        $buffer .= $trimmed;
    }

    // Eksekusi jika statement selesai
    if ($inInsert && substr(rtrim($buffer), -1) === ';') {
        $inInsert = false;

        // Ekstrak semua VALUE tuple dari statement INSERT
        // Format: INSERT INTO `sekolah` (...) VALUES (...),(...),...;
        // Ambil bagian VALUES
        $valuesStart = stripos($buffer, ') VALUES');
        if ($valuesStart === false) {
            $buffer = '';
            continue;
        }

        // Ambil kolom dari SQL file (baris INSERT pertama)
        // Kita parse manual tiap tuple VALUES
        $valuesPart = trim(substr($buffer, $valuesStart + 8)); // skip ') VALUES'
        // Hapus semicolon di akhir
        $valuesPart = rtrim($valuesPart, ";\n\r");

        // Parse tiap tuple - kita ekstrak dengan regex sederhana per baris tuple
        // Karena setiap nilai tuple dipisah ),\n(
        // Split dengan pattern "),\n(" atau "),\r\n("
        // Tapi lebih aman: parse char by char untuk menemukan tuple boundaries

        $tuples = parseTuples($valuesPart);

        foreach ($tuples as $tuple) {
            // Kolom urutan di SQL: kode_prop, propinsi, kode_kab_kota, kabupaten_kota,
            //                      kode_kec, kecamatan, id, npsn, sekolah, bentuk, status,
            //                      alamat_jalan, lintang, bujur
            if (count($tuple) < 14) continue;

            try {
                $stmt->execute([
                    ':kode_prop'      => trim($tuple[0]),
                    ':propinsi'       => trim($tuple[1]),
                    ':kode_kab_kota'  => trim($tuple[2]),
                    ':kabupaten_kota' => trim($tuple[3]),
                    ':kode_kec'       => trim($tuple[4]),
                    ':kecamatan'      => trim($tuple[5]),
                    ':sekolah_id'     => trim($tuple[6]),  // id UUID -> sekolah_id
                    ':npsn'           => trim($tuple[7]),
                    ':sekolah'        => trim($tuple[8]),
                    ':bentuk'         => trim($tuple[9]),
                    ':status'         => trim($tuple[10]),
                    ':alamat_jalan'   => trim($tuple[11]),
                    ':lintang'        => trim($tuple[12]),
                    ':bujur'          => trim($tuple[13]),
                ]);
                $inserted++;

                if ($inserted % $commitEvery === 0) {
                    $pdo->commit();
                    $pdo->beginTransaction();
                    echo "  Progress: $inserted baris diinsert..." . PHP_EOL;
                }
            } catch (PDOException $e) {
                $errors++;
                if ($errors <= 5) {
                    echo "WARN [$errors]: " . $e->getMessage() . PHP_EOL;
                }
            }
        }

        $buffer = '';
    }
}

fclose($handle);

if ($pdo->inTransaction()) {
    $pdo->commit();
}

echo PHP_EOL . "=== SELESAI ===" . PHP_EOL;
echo "Total baris diinsert : $inserted" . PHP_EOL;
echo "Total error          : $errors" . PHP_EOL;

$count = $pdo->query("SELECT COUNT(*) FROM sekolah")->fetchColumn();
echo "Total record di DB   : $count" . PHP_EOL;

// ============================================================
// Helper: parse tuple values dari string VALUES bagian SQL
// ============================================================
function parseTuples(string $valuesPart): array
{
    $tuples = [];
    $len    = strlen($valuesPart);
    $i      = 0;

    while ($i < $len) {
        // Skip whitespace dan koma antar tuple
        while ($i < $len && in_array($valuesPart[$i], [' ', "\t", "\r", "\n", ','])) {
            $i++;
        }
        if ($i >= $len) break;

        if ($valuesPart[$i] !== '(') {
            $i++;
            continue;
        }
        $i++; // skip '('

        $fields = [];
        $field  = '';
        $inStr  = false;
        $strChar = '';

        while ($i < $len) {
            $ch = $valuesPart[$i];

            if ($inStr) {
                if ($ch === '\\' && isset($valuesPart[$i + 1])) {
                    $field .= $ch . $valuesPart[$i + 1];
                    $i += 2;
                    continue;
                }
                if ($ch === $strChar) {
                    $inStr = false;
                    $i++;
                    continue;
                }
                $field .= $ch;
            } else {
                if ($ch === "'" || $ch === '"') {
                    $inStr   = true;
                    $strChar = $ch;
                } elseif ($ch === ',') {
                    $fields[] = $field;
                    $field    = '';
                } elseif ($ch === ')') {
                    $fields[] = $field;
                    $i++;
                    break;
                } else {
                    $field .= $ch;
                }
            }
            $i++;
        }

        if (!empty($fields)) {
            $tuples[] = $fields;
        }
    }

    return $tuples;
}
