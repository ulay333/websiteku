<?php
// Menentukan halaman aktif untuk highlight menu
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($pageTitle) ? $pageTitle . ' - ' : '' ?>SIKAD | <?= UNIV_SHORT ?></title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="header-top">
        <div class="container">
            <span>📍 <?= UNIV_ADDRESS ?></span>
            <span>✉️ <a href="mailto:<?= UNIV_EMAIL ?>"><?= UNIV_EMAIL ?></a> &nbsp;|&nbsp; 📞 <?= UNIV_PHONE ?></span>
        </div>
    </div>
    <nav>
        <div class="nav-container">
            <a href="index.php" class="brand">
                <div class="logo-circle">UN</div>
                <div class="brand-text">
                    <h1>SIKAD</h1>
                    <span>Universitas Nusantara PGRI Kediri</span>
                </div>
            </a>

            <div class="hamburger" id="hamburger">
                <span></span><span></span><span></span>
            </div>

            <div class="nav-links" id="navLinks">
                <a href="index.php" class="<?= $current === 'index.php' ? 'active' : '' ?>">🏠 Home</a>
                <a href="gallery.php" class="<?= $current === 'gallery.php' ? 'active' : '' ?>">🖼️ Gallery</a>
                <?php if (isLoggedIn()): ?>
                    <a href="profile.php" class="<?= $current === 'profile.php' ? 'active' : '' ?>">👤 Profile</a>
                    <a href="form.php" class="<?= $current === 'form.php' ? 'active' : '' ?>">📤 Upload Tugas</a>
                    <a href="tabel.php" class="<?= $current === 'tabel.php' ? 'active' : '' ?>">📊 Rekap</a>
                <?php endif; ?>
                <a href="contact.php" class="<?= $current === 'contact.php' ? 'active' : '' ?>">✉️ Contact Us</a>
                <?php if (isLoggedIn()): ?>
                    <a href="logout.php" class="nav-cta">🚪 Logout</a>
                <?php else: ?>
                    <a href="daftar.php" class="<?= $current === 'daftar.php' ? 'active' : '' ?>">📝 Daftar</a>
                    <a href="login.php" class="nav-cta">🔐 Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>
