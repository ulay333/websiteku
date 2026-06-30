<?php
require_once 'config.php';
$pageTitle = 'Home';

// Hitung statistik dari database
$totalMhs = $conn->query("SELECT COUNT(*) as total FROM mahasiswa")->fetch_assoc()['total'];
$totalTugas = $conn->query("SELECT COUNT(*) as total FROM tugas")->fetch_assoc()['total'];
$totalKumpul = $conn->query("SELECT COUNT(*) as total FROM pengumpulan")->fetch_assoc()['total'];
$totalMK = 3; // Basis Data, RPL, Pemrograman Web

include 'header.php';
?>

<!-- HERO -->
<section class="hero">
    <div class="hero-pattern"></div>
    <div class="hero-grid"></div>
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge">🎓 Sistem Akademik Digital Resmi</div>
            <h1>Kumpulkan Tugas Kuliahmu <span>Tanpa Drama Deadline</span></h1>
            <p>SIKAD adalah platform resmi <?= UNIV_NAME ?> untuk pengumpulan tugas mata kuliah Basis Data, Rancangan Perangkat Lunak, dan Pemrograman Web — cepat, tercatat rapi, dan bisa diakses kapan saja.</p>
            <div class="btn-group">
                <?php if (isLoggedIn()): ?>
                    <a href="form.php" class="btn btn-primary">📤 Upload Tugas Sekarang</a>
                    <a href="profile.php" class="btn btn-outline">👤 Lihat Profil Saya</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary">🔐 Login Mahasiswa</a>
                    <a href="gallery.php" class="btn btn-outline">🖼️ Lihat Galeri Kampus</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- STATS -->
<section class="stats-strip">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number"><span><?= $totalMhs ?></span></div>
                <div class="stat-label">Mahasiswa Terdaftar</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><span><?= $totalMK ?></span></div>
                <div class="stat-label">Mata Kuliah Aktif</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><span><?= $totalTugas ?></span></div>
                <div class="stat-label">Tugas Diberikan</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><span><?= $totalKumpul ?></span></div>
                <div class="stat-label">Tugas Terkumpul</div>
            </div>
        </div>
    </div>
</section>

<!-- MATA KULIAH -->
<section class="section">
    <div class="container">
        <div class="title-bar"><h2 class="section-title">Mata Kuliah yang Tersedia</h2></div>
        <p class="section-subtitle">Berikut tiga mata kuliah utama program studi Teknik Informatika yang menggunakan SIKAD untuk pengumpulan tugas semester ini.</p>

        <div class="mk-grid">
            <div class="mk-card">
                <div class="mk-header bd">
                    <div class="mk-icon">🗄️</div>
                    <h3>Basis Data</h3>
                </div>
                <div class="mk-body">
                    <div class="mk-info">
                        <div class="mk-row"><span class="label">Kode MK</span><span class="value">IF-301</span></div>
                        <div class="mk-row"><span class="label">SKS</span><span class="value">3 SKS</span></div>
                        <div class="mk-row"><span class="label">Dosen Pengampu</span><span class="value">Dr. Eka Putra, M.Kom</span></div>
                        <div class="mk-row"><span class="label">Tugas Aktif</span><span class="value">2 Tugas</span></div>
                    </div>
                </div>
            </div>

            <div class="mk-card">
                <div class="mk-header rpl">
                    <div class="mk-icon">🧩</div>
                    <h3>Rancangan Perangkat Lunak</h3>
                </div>
                <div class="mk-body">
                    <div class="mk-info">
                        <div class="mk-row"><span class="label">Kode MK</span><span class="value">IF-302</span></div>
                        <div class="mk-row"><span class="label">SKS</span><span class="value">3 SKS</span></div>
                        <div class="mk-row"><span class="label">Dosen Pengampu</span><span class="value">Ririd Triwindiati, M.Kom</span></div>
                        <div class="mk-row"><span class="label">Tugas Aktif</span><span class="value">2 Tugas</span></div>
                    </div>
                </div>
            </div>

            <div class="mk-card">
                <div class="mk-header pw">
                    <div class="mk-icon">💻</div>
                    <h3>Pemrograman Web</h3>
                </div>
                <div class="mk-body">
                    <div class="mk-info">
                        <div class="mk-row"><span class="label">Kode MK</span><span class="value">IF-303</span></div>
                        <div class="mk-row"><span class="label">SKS</span><span class="value">4 SKS</span></div>
                        <div class="mk-row"><span class="label">Dosen Pengampu</span><span class="value">Arie Nugroho, M.Kom</span></div>
                        <div class="mk-row"><span class="label">Tugas Aktif</span><span class="value">1 Tugas</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- TABEL TUGAS TERBARU -->
<section class="section section-alt">
    <div class="container">
        <div class="title-bar"><h2 class="section-title">Daftar Tugas Terbaru</h2></div>
        <p class="section-subtitle">Pantau tugas yang sedang berjalan beserta tenggat waktu pengumpulannya.</p>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Tugas</th>
                        <th>Mata Kuliah</th>
                        <th>Deadline</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $result = $conn->query("SELECT * FROM tugas ORDER BY deadline ASC LIMIT 5");
                    while ($row = $result->fetch_assoc()):
                        $isLewat = strtotime($row['deadline']) < time();
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="fw-600 text-navy"><?= htmlspecialchars($row['judul']) ?></td>
                        <td><?= htmlspecialchars($row['mata_kuliah']) ?></td>
                        <td><?= formatTanggal($row['deadline']) ?></td>
                        <td>
                            <?php if ($isLewat): ?>
                                <span class="badge badge-danger">Sudah Lewat</span>
                            <?php else: ?>
                                <span class="badge badge-success">Masih Dibuka</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-32">
            <?php if (isLoggedIn()): ?>
                <a href="form.php" class="btn btn-navy">📤 Kumpulkan Tugas Sekarang</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-navy">🔐 Login untuk Mengumpulkan Tugas</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
