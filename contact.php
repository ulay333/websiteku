<?php
require_once 'config.php';
$pageTitle = 'Contact Us';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = sanitize($_POST['nama']);
    $email  = sanitize($_POST['email']);
    $subjek = sanitize($_POST['subjek']);
    $pesan  = sanitize($_POST['pesan']);
    $captchaInput = $_POST['captcha'] ?? '';

    if (empty($nama) || empty($email) || empty($pesan)) {
        $error = 'Nama, email, dan pesan wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif (!verifyCaptcha($captchaInput)) {
        $error = 'Jawaban captcha salah! Silakan coba lagi.';
    } else {
        $stmt = $conn->prepare("INSERT INTO kontak (nama, email, subjek, pesan) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $nama, $email, $subjek, $pesan);
        if ($stmt->execute()) {
            $success = 'Pesan Anda berhasil dikirim! Tim kami akan segera merespon.';
        } else {
            $error = 'Gagal mengirim pesan: ' . $conn->error;
        }
    }
}

$captchaSoal = generateCaptcha();

include 'header.php';
?>

<div class="page-hero">
    <div class="container">
        <h2>Contact Us</h2>
        <p>Ada pertanyaan seputar SIKAD atau perkuliahan? Hubungi kami.</p>
        <div class="breadcrumb"><a href="index.php">Home</a> / Contact Us</div>
    </div>
</div>

<section class="section">
    <div class="container">

        <?php if ($success): ?>
            <div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger">⚠️ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="contact-grid">

            <div class="contact-info-card">
                <h3>Informasi Kontak</h3>
                <p>Hubungi <?= UNIV_NAME ?> melalui kanal berikut, atau kirim pesan langsung lewat formulir di samping.</p>

                <div class="contact-detail">
                    <div class="contact-detail-icon">📍</div>
                    <div class="contact-detail-text">
                        <h4>Alamat Kampus</h4>
                        <p><?= UNIV_ADDRESS ?></p>
                    </div>
                </div>
                <div class="contact-detail">
                    <div class="contact-detail-icon">✉️</div>
                    <div class="contact-detail-text">
                        <h4>Email</h4>
                        <p><?= UNIV_EMAIL ?></p>
                    </div>
                </div>
                <div class="contact-detail">
                    <div class="contact-detail-icon">📞</div>
                    <div class="contact-detail-text">
                        <h4>Telepon</h4>
                        <p><?= UNIV_PHONE ?></p>
                    </div>
                </div>
                <div class="contact-detail">
                    <div class="contact-detail-icon">🌐</div>
                    <div class="contact-detail-text">
                        <h4>Website</h4>
                        <p><?= UNIV_WEBSITE ?></p>
                    </div>
                </div>
                <div class="contact-detail">
                    <div class="contact-detail-icon">🕒</div>
                    <div class="contact-detail-text">
                        <h4>Jam Layanan</h4>
                        <p>Senin - Jumat, 08.00 - 16.00 WIB</p>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <h3 class="text-navy mb-24" style="font-family:var(--font-head); font-size:19px;">📨 Kirim Pesan</h3>
                <form method="POST" action="contact.php">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nama Lengkap <span>*</span></label>
                            <input type="text" name="nama" class="form-control" placeholder="Nama Anda" required value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>">
                        </div>
                        <div class="form-group">
                            <label>Email <span>*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="email@contoh.com" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Subjek</label>
                        <input type="text" name="subjek" class="form-control" placeholder="Topik pesan Anda" value="<?= isset($_POST['subjek']) ? htmlspecialchars($_POST['subjek']) : '' ?>">
                    </div>
                    <div class="form-group">
                        <label>Pesan <span>*</span></label>
                        <textarea name="pesan" class="form-control" placeholder="Tuliskan pesan Anda di sini..." required style="min-height:140px;"><?= isset($_POST['pesan']) ? htmlspecialchars($_POST['pesan']) : '' ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Verifikasi Captcha <span>*</span></label>
                        <div class="captcha-box">
                            <div class="captcha-display"><?= $captchaSoal ?> = ?</div>
                            <div class="captcha-refresh" onclick="location.reload()" title="Ganti soal">🔄</div>
                        </div>
                        <input type="text" name="captcha" class="form-control" placeholder="Masukkan hasil jawaban" required autocomplete="off">
                    </div>
                    <button type="submit" class="btn btn-primary btn-full">📨 Kirim Pesan</button>
                </form>
            </div>
        </div>

        <!-- TABEL RIWAYAT PESAN MASUK (OUTPUT) -->
        <div class="mt-32">
            <div class="title-bar"><h2 class="section-title">Pesan Terbaru Masuk</h2></div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr><th>No</th><th>Nama</th><th>Email</th><th>Subjek</th><th>Tanggal</th></tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $resKontak = $conn->query("SELECT * FROM kontak ORDER BY created_at DESC LIMIT 5");
                        if ($resKontak->num_rows > 0):
                            while ($row = $resKontak->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td class="fw-600"><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['subjek'] ?: '-') ?></td>
                            <td><?= formatTanggal($row['created_at']) ?></td>
                        </tr>
                        <?php
                            endwhile;
                        else:
                        ?>
                        <tr><td colspan="5" class="text-center text-gray" style="padding:24px;">Belum ada pesan masuk.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
