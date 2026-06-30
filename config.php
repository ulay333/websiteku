<?php
// =============================================
// KONFIGURASI DATABASE
// Universitas Nusantara PGRI Kediri
// =============================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_tugaskuliah');

// Koneksi ke database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek koneksi
if ($conn->connect_error) {
    die("<div style='padding:20px;background:#fee;color:#c00;border:1px solid #fcc;border-radius:8px;margin:20px;font-family:sans-serif;'>
        <strong>❌ Koneksi Database Gagal!</strong><br>
        Error: " . $conn->connect_error . "<br><br>
        <small>Pastikan XAMPP/WAMP berjalan dan database sudah diimport dari file <code>database.sql</code></small>
    </div>");
}

$conn->set_charset("utf8mb4");

// Mulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Informasi Universitas
define('UNIV_NAME', 'Universitas Nusantara PGRI Kediri');
define('UNIV_SHORT', 'UN PGRI Kediri');
define('UNIV_ADDRESS', 'Jl. KH. Ahmad Dahlan No.76, Mojoroto, Kediri, Jawa Timur 64112');
define('UNIV_EMAIL', 'info@unpkediri.ac.id');
define('UNIV_PHONE', '(0354) 771576');
define('UNIV_WEBSITE', 'www.unpkediri.ac.id');

// Fungsi Helper
function redirect($url) {
    header("Location: $url");
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['mahasiswa_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect('login.php');
    }
}

function sanitize($data) {
    global $conn;
    return htmlspecialchars(strip_tags(trim($conn->real_escape_string($data))));
}

function formatTanggal($tanggal) {
    $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
              'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $d = date('d', strtotime($tanggal));
    $m = $bulan[(int)date('n', strtotime($tanggal))];
    $y = date('Y', strtotime($tanggal));
    $t = date('H:i', strtotime($tanggal));
    return "$d $m $y, $t WIB";
}

// =============================================
// FUNGSI CAPTCHA (Math Captcha Sederhana)
// =============================================

// Membuat soal captcha baru dan simpan jawaban di session
function generateCaptcha($sessionKey = 'captcha_answer') {
    $angka1 = rand(1, 10);
    $angka2 = rand(1, 10);
    $operator = ['+', '-'][rand(0, 1)];

    if ($operator === '+') {
        $jawaban = $angka1 + $angka2;
    } else {
        // Pastikan hasil pengurangan tidak negatif
        if ($angka1 < $angka2) {
            [$angka1, $angka2] = [$angka2, $angka1];
        }
        $jawaban = $angka1 - $angka2;
    }

    $_SESSION[$sessionKey] = $jawaban;
    return "$angka1 $operator $angka2";
}

// Memverifikasi jawaban captcha yang diinput user
function verifyCaptcha($input, $sessionKey = 'captcha_answer') {
    if (!isset($_SESSION[$sessionKey])) {
        return false;
    }
    $valid = ((string)trim($input) === (string)$_SESSION[$sessionKey]);
    unset($_SESSION[$sessionKey]); // soal hanya bisa dipakai sekali
    return $valid;
}
?>
