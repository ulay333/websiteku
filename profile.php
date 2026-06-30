<?php
require_once 'config.php';
requireLogin();
$pageTitle = 'Profile Mahasiswa';

// Ambil data mahasiswa yang login
$stmt = $conn->prepare("SELECT * FROM mahasiswa WHERE id = ?");
$stmt->bind_param("i", $_SESSION['mahasiswa_id']);
$stmt->execute();
$mhs = $stmt->get_result()->fetch_assoc();

// Statistik pengumpulan tugas mahasiswa ini
$stmt2 = $conn->prepare("SELECT COUNT(*) as total FROM pengumpulan WHERE mahasiswa_id = ?");
$stmt2->bind_param("i", $_SESSION['mahasiswa_id']);
$stmt2->execute();
$totalKumpul = $stmt2->get_result()->fetch_assoc()['total'];

$stmt3 = $conn->prepare("SELECT COUNT(*) as total FROM pengumpulan WHERE mahasiswa_id = ? AND status = 'Tepat Waktu'");
$stmt3->bind_param("i", $_SESSION['mahasiswa_id']);
$stmt3->execute();
$tepatWaktu = $stmt3->get_result()->fetch_assoc()['total'];

$stmt4 = $conn->prepare("SELECT COUNT(*) as total FROM pengumpulan WHERE mahasiswa_id = ? AND status = 'Terlambat'");
$stmt4->bind_param("i", $_SESSION['mahasiswa_id']);
$stmt4->execute();
$terlambat = $stmt4->get_result()->fetch_assoc()['total'];

$totalTugas = $conn->query("SELECT COUNT(*) as total FROM tugas")->fetch_assoc()['total'];
$belumKumpul = $totalTugas - $totalKumpul;

// Inisial untuk avatar
$initial = strtoupper(substr($mhs['nama'], 0, 1));

include 'header.php';
?>

<div class="page-hero">
    <div class="container">
        <h2>Profile Mahasiswa</h2>
        <div class="breadcrumb"><a href="index.php">Home</a> / Profile</div>
    </div>
</div>

<section class="section">
    <div class="container">

        <div class="profile-card mb-32">
            <div class="avatar"><?= $initial ?></div>
            <div class="profile-info">
                <h3><?= htmlspecialchars($mhs['nama']) ?></h3>
                <p>NIM: <?= htmlspecialchars($mhs['nim']) ?> &nbsp;•&nbsp; <?= htmlspecialchars($mhs['email']) ?></p>
                <div class="profile-tags">
                    <span class="profile-tag">🎓 <?= htmlspecialchars($mhs['program_studi']) ?></span>
                    <span class="profile-tag">📚 Semester <?= htmlspecialchars($mhs['semester']) ?></span>
                    <span class="profile-tag">🗓️ Bergabung <?= date('Y', strtotime($mhs['created_at'])) ?></span>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="dash-card">
                <div class="dash-icon blue">📤</div>
                <div class="dash-text"><h4><?= $totalKumpul ?></h4><p>Total Tugas Dikumpulkan</p></div>
            </div>
            <div class="dash-card">
                <div class="dash-icon green">✅</div>
                <div class="dash-text"><h4><?= $tepatWaktu ?></h4><p>Tepat Waktu</p></div>
            </div>
            <div class="dash-card">
                <div class="dash-icon red">⏰</div>
                <div class="dash-text"><h4><?= $terlambat ?></h4><p>Terlambat</p></div>
            </div>
        </div>

        <div class="title-bar"><h2 class="section-title">Riwayat Pengumpulan Tugas Saya</h2></div>
        <p class="section-subtitle">Daftar tugas yang sudah Anda kumpulkan melalui SIKAD.</p>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Tugas</th>
                        <th>Mata Kuliah</th>
                        <th>File</th>
                        <th>Tanggal Kumpul</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt5 = $conn->prepare("SELECT * FROM pengumpulan WHERE mahasiswa_id = ? ORDER BY tanggal_kumpul DESC");
                    $stmt5->bind_param("i", $_SESSION['mahasiswa_id']);
                    $stmt5->execute();
                    $riwayat = $stmt5->get_result();
                    $no = 1;
                    if ($riwayat->num_rows > 0):
                        while ($row = $riwayat->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="fw-600 text-navy"><?= htmlspecialchars($row['judul_tugas']) ?></td>
                        <td><?= htmlspecialchars($row['mata_kuliah']) ?></td>
                        <td>
                            <?php if ($row['file_path']): ?>
                                <a href="<?= htmlspecialchars($row['file_path']) ?>" target="_blank" class="text-navy fw-600">📎 Unduh</a>
                            <?php else: ?>
                                <span class="text-gray">-</span>
                            <?php endif; ?>
                        </td>
                        <td><?= formatTanggal($row['tanggal_kumpul']) ?></td>
                        <td>
                            <?php if ($row['status'] === 'Tepat Waktu'): ?>
                                <span class="badge badge-success">Tepat Waktu</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Terlambat</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php
                        endwhile;
                    else:
                    ?>
                    <tr><td colspan="6" class="text-center text-gray" style="padding:32px;">Anda belum mengumpulkan tugas apapun. <a href="form.php" class="text-navy fw-600">Upload tugas sekarang →</a></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-32">
            <a href="form.php" class="btn btn-navy">📤 Upload Tugas Baru</a>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
