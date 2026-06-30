<?php
require_once 'config.php';
$pageTitle = 'Login Mahasiswa';
$error = '';

if (isLoggedIn()) {
    redirect('profile.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = sanitize($_POST['nim']);
    $password = $_POST['password'];
    $captchaInput = $_POST['captcha'] ?? '';

    if (empty($nim) || empty($password)) {
        $error = 'NIM dan Password wajib diisi!';
    } elseif (!verifyCaptcha($captchaInput)) {
        $error = 'Jawaban captcha salah! Silakan coba lagi.';
    } else {
        $stmt = $conn->prepare("SELECT * FROM mahasiswa WHERE nim = ?");
        $stmt->bind_param("s", $nim);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $mhs = $result->fetch_assoc();
            if (password_verify($password, $mhs['password'])) {
                $_SESSION['mahasiswa_id'] = $mhs['id'];
                $_SESSION['mahasiswa_nim'] = $mhs['nim'];
                $_SESSION['mahasiswa_nama'] = $mhs['nama'];
                redirect('profile.php');
            } else {
                $error = 'Password salah! Silakan coba lagi.';
            }
        } else {
            $error = 'NIM tidak ditemukan dalam database.';
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
<title>Login Mahasiswa - SIKAD | <?= UNIV_SHORT ?></title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="login-page">
    <div class="login-box">
        <div class="login-header">
            <div class="logo-lg">UN</div>
            <h2>Login Mahasiswa</h2>
            <p>SIKAD - <?= UNIV_NAME ?></p>
        </div>
        <div class="login-body">

            <?php if ($error): ?>
                <div class="alert alert-danger">⚠️ <?= $error ?></div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="form-group">
                    <label>NIM <span>*</span></label>
                    <input type="text" name="nim" class="form-control" placeholder="Contoh: 20211001" required value="<?= isset($_POST['nim']) ? htmlspecialchars($_POST['nim']) : '' ?>">
                </div>
                <div class="form-group">
                    <label>Password <span>*</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
                <div class="form-group">
                    <label>Verifikasi Captcha <span>*</span></label>
                    <div class="captcha-box">
                        <div class="captcha-display" id="captchaDisplay"><?= $captchaSoal ?> = ?</div>
                        <div class="captcha-refresh" onclick="location.reload()" title="Ganti soal">🔄</div>
                    </div>
                    <input type="text" name="captcha" class="form-control" placeholder="Masukkan hasil jawaban" required autocomplete="off">
                </div>
                <button type="submit" class="btn btn-primary btn-full">🔐 Masuk</button>
            </form>

            <hr class="form-divider">

            <div class="alert alert-info" style="font-size:13px;">
                <div>
                    💡 <strong>Akun Demo:</strong><br>
                    NIM: <code>20211001</code> / <code>20211002</code> / <code>20211003</code><br>
                    Password: <code>mahasiswa123</code>
                </div>
            </div>

            <p class="text-center mt-16" style="font-size:13px;">
                Belum punya akun? <a href="daftar.php" class="text-navy fw-600">Daftar di sini →</a>
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
