<?php
require_once 'config.php';
requireLogin();
$pageTitle = 'Rekap Tugas';

// Hitung total per mata kuliah
$rekap = [
    'Basis Data' => 0,
    'Rancangan Perangkat Lunak' => 0,
    'Pemrograman Web' => 0,
];
$resRekap = $conn->query("SELECT mata_kuliah, COUNT(*) as total FROM pengumpulan GROUP BY mata_kuliah");
while ($r = $resRekap->fetch_assoc()) {
    $rekap[$r['mata_kuliah']] = $r['total'];
}
$totalSemua = array_sum($rekap);

include 'header.php';
?>

<div class="page-hero">
    <div class="container">
        <h2>Rekap Pengumpulan Tugas</h2>
        <p>Tabel rekapitulasi seluruh tugas yang telah dikumpulkan mahasiswa</p>
        <div class="breadcrumb"><a href="index.php">Home</a> / Rekap Tugas</div>
    </div>
</div>

<section class="section">
    <div class="container">

        <div class="flex-between mb-24" style="flex-wrap:wrap; gap:16px;">
            <input type="text" id="tableSearch" class="form-control" placeholder="🔍 Cari nama, NIM, atau judul tugas..." style="max-width:320px;">
            <select id="filterMK" class="form-control" style="max-width:240px;">
                <option value="semua">Semua Mata Kuliah</option>
                <option value="Basis Data">Basis Data</option>
                <option value="Rancangan Perangkat Lunak">Rancangan Perangkat Lunak</option>
                <option value="Pemrograman Web">Pemrograman Web</option>
            </select>
        </div>

        <div class="table-wrapper">
            <table id="dataTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Mata Kuliah</th>
                        <th>Judul Tugas</th>
                        <th>Tgl Kumpul</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $result = $conn->query("SELECT * FROM pengumpulan ORDER BY tanggal_kumpul DESC");
                    if ($result->num_rows > 0):
                        while ($row = $result->fetch_assoc()):
                    ?>
                    <tr data-mk="<?= htmlspecialchars($row['mata_kuliah']) ?>">
                        <td><?= $no++ ?></td>
                        <td class="fw-600"><?= htmlspecialchars($row['nim']) ?></td>
                        <td><?= htmlspecialchars($row['nama_mahasiswa']) ?></td>
                        <td><span class="badge badge-info"><?= htmlspecialchars($row['mata_kuliah']) ?></span></td>
                        <td><?= htmlspecialchars($row['judul_tugas']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($row['tanggal_kumpul'])) ?></td>
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
                    <tr><td colspan="7" class="text-center text-gray" style="padding:32px;">Belum ada data pengumpulan tugas.</td></tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">Total Keseluruhan Pengumpulan</td>
                        <td colspan="4"><?= $totalSemua ?> tugas terkumpul</td>
                    </tr>
                    <tr>
                        <td colspan="3">🗄️ Basis Data</td>
                        <td colspan="4"><?= $rekap['Basis Data'] ?> tugas</td>
                    </tr>
                    <tr>
                        <td colspan="3">🧩 Rancangan Perangkat Lunak</td>
                        <td colspan="4"><?= $rekap['Rancangan Perangkat Lunak'] ?> tugas</td>
                    </tr>
                    <tr>
                        <td colspan="3">💻 Pemrograman Web</td>
                        <td colspan="4"><?= $rekap['Pemrograman Web'] ?> tugas</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
