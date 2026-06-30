<?php
require_once 'config.php';
$pageTitle = 'Daftar Akun Mahasiswa';
$error = '';
$success = '';

if (isLoggedIn()) {
    redirect('profile.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim            = sanitize($_POST['nim']);
    $nama           = sanitize($_POST['nama']);
    $email          = sanitize($_POST['email']);
    $program_studi  = sanitize($_POST['program_studi']);
    $semester       = (int) $_POST['semester'];
    $password       = $_POST['password'];
    $konfirmasi     = $_POST['konfirmasi_password'];
    $captchaInput   = $_POST['captcha'] ?? '';

    if (empty($nim) || empty($nama) || empty($email) || empty($password)) {
        $error = 'Semua kolom wajib diisi!';
    } elseif (!preg_match('/^[0-9]{6,20}$/', $nim)) {
        $error = 'NIM harus berupa angka (6-20 digit).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } elseif ($password !== $konfirmasi) {
        $error = 'Konfirmasi password tidak sama.';
    } elseif (!verifyCaptcha($captchaInput)) {
        $error = 'Jawaban captcha salah! Silakan coba lagi.';
    } else {
        // Cek apakah NIM atau email sudah terdaftar
        $stmtCek = $conn->prepare("SELECT id FROM mahasiswa WHERE nim = ? OR email = ?");
        $stmtCek->bind_param("ss", $nim, $email);
        $stmtCek->execute();
        $cekResult = $stmtCek->get_result();

        if ($cekResult->num_rows > 0) {
            $error = 'NIM atau Email sudah terdaftar. Silakan login atau gunakan data lain.';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmtInsert = $conn->prepare("INSERT INTO mahasiswa (nim, nama, email, password, program_studi, semester) VALUES (?,?,?,?,?,?)");
            $stmtInsert->bind_param("sssssi", $nim, $nama, $email, $hashedPassword, $program_studi, $semester);

            if ($stmtInsert->execute()) {
                $success = 'Pendaftaran berhasil! Silakan login menggunakan NIM dan password Anda.';
            } else {
                $error = 'Gagal mendaftar: ' . $conn->error;
            }
        }
    }
}

$captchaSoal = generateCaptcha();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar Akun Mahasiswa - SIKAD | <?= UNIV_SHORT ?></title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="login-page">
    <div class="login-box" style="max-width:480px;">
        <div class="login-header">
            <div class="logo-lg">UN</div>
            <h2>Daftar Akun Mahasiswa</h2>
            <p>SIKAD - <?= UNIV_NAME ?></p>
        </div>
        <div class="login-body">

            <?php if ($error): ?>
                <div class="alert alert-danger">⚠️ <?= $error ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success">✅ <?= $success ?> <a href="login.php" class="fw-bold">Login sekarang →</a></div>
            <?php endif; ?>

            <?php if (!$success): ?>
            <form method="POST" action="daftar.php">
                <div class="form-grid">
                    <div class="form-group">
                        <label>NIM <span>*</span></label>
                        <input type="text" name="nim" class="form-control" placeholder="Contoh: 20211004" required pattern="[0-9]{6,20}" value="<?= isset($_POST['nim']) ? htmlspecialchars($_POST['nim']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label>Semester <span>*</span></label>
                        <select name="semester" class="form-control" required>
                            <?php for ($i = 1; $i <= 14; $i++): ?>
                                <option value="<?= $i ?>" <?= (isset($_POST['semester']) && $_POST['semester'] == $i) ? 'selected' : '' ?>>Semester <?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Nama Lengkap <span>*</span></label>
                    <input type="text" name="nama" class="form-control" placeholder="Nama lengkap Anda" required value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>">
                </div>
                <div class="form-group">
                    <label>Email <span>*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="email@student.unpkediri.ac.id" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                </div>
                <div class="form-group">
                    <label>Program Studi <span>*</span></label>
                    <select name="program_studi" class="form-control" required>
                        <option value="Teknik Informatika" <?= (isset($_POST['program_studi']) && $_POST['program_studi'] === 'Teknik Informatika') ? 'selected' : '' ?>>Teknik Informatika</option>
                        <option value="Sistem Informasi" <?= (isset($_POST['program_studi']) && $_POST['program_studi'] === 'Sistem Informasi') ? 'selected' : '' ?>>Sistem Informasi</option>
                        <option value="Teknik Elektro" <?= (isset($_POST['program_studi']) && $_POST['program_studi'] === 'Teknik Elektro') ? 'selected' : '' ?>>Teknik Elektro</option>
                    </select>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Password <span>*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password <span>*</span></label>
                        <input type="password" name="konfirmasi_password" class="form-control" placeholder="Ulangi password" required minlength="6">
                    </div>
                </div>

                <div class="form-group">
                    <label>Verifikasi Captcha <span>*</span></label>
                    <div class="captcha-box">
                        <div class="captcha-display"><?= $captchaSoal ?> = ?</div>
                        <div class="captcha-refresh" onclick="location.reload()" title="Ganti soal">🔄</div>
                    </div>
                    <input type="text" name="captcha" class="form-control" placeholder="Masukkan hasil jawaban" required autocomplete="off">
                </div>

                <button type="submit" class="btn btn-primary btn-full">📝 Daftar Sekarang</button>
            </form>
            <?php endif; ?>

            <hr class="form-divider">

            <p class="text-center" style="font-size:13px;">
                Sudah punya akun? <a href="login.php" class="text-navy fw-600">Login di sini →</a>
            </p>
            <p class="text-center mt-8" style="font-size:13px;">
                <a href="index.php" class="text-navy fw-600">← Kembali ke Beranda</a>
            </p>
        </div>
    </div>
</div>

<script src="js/main.js"></script>
</body>
</html>
