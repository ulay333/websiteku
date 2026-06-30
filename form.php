<?php
require_once 'config.php';
requireLogin();
$pageTitle = 'Form Upload Tugas';

$success = '';
$error = '';

// Ambil data mahasiswa yang login
$stmt = $conn->prepare("SELECT * FROM mahasiswa WHERE id = ?");
$stmt->bind_param("i", $_SESSION['mahasiswa_id']);
$stmt->execute();
$mhs = $stmt->get_result()->fetch_assoc();

// ============ PROSES SUBMIT FORM (INPUT) ============
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_tugas'])) {

    $tugas_id    = (int) $_POST['tugas_id'];
    $keterangan  = sanitize($_POST['keterangan']);

    // Ambil detail tugas yang dipilih
    $stmtTugas = $conn->prepare("SELECT * FROM tugas WHERE id = ?");
    $stmtTugas->bind_param("i", $tugas_id);
    $stmtTugas->execute();
    $tugasDetail = $stmtTugas->get_result()->fetch_assoc();

    if (!$tugasDetail) {
        $error = 'Tugas yang dipilih tidak valid.';
    } elseif (empty($keterangan)) {
        $error = 'Keterangan tugas wajib diisi.';
    } else {

        $namaFile = null;
        $filePath = null;

        // Proses upload file jika ada
        if (isset($_FILES['file_tugas']) && $_FILES['file_tugas']['error'] === UPLOAD_ERR_OK) {
            $allowedExt = ['pdf', 'doc', 'docx', 'zip', 'rar', 'php', 'html', 'sql', 'png', 'jpg', 'jpeg'];
            $fileExt = strtolower(pathinfo($_FILES['file_tugas']['name'], PATHINFO_EXTENSION));

            if (!in_array($fileExt, $allowedExt)) {
                $error = 'Format file tidak diizinkan. Gunakan: ' . implode(', ', $allowedExt);
            } elseif ($_FILES['file_tugas']['size'] > 10 * 1024 * 1024) {
                $error = 'Ukuran file maksimal 10MB.';
            } else {
                $namaFile = 'TUGAS_' . $mhs['nim'] . '_' . time() . '.' . $fileExt;
                $uploadDir = __DIR__ . '/uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                $filePath = 'uploads/' . $namaFile;

                if (!move_uploaded_file($_FILES['file_tugas']['tmp_name'], $uploadDir . $namaFile)) {
                    $error = 'Gagal mengunggah file. Coba lagi.';
                    $namaFile = null;
                    $filePath = null;
                }
            }
        } else {
            $error = 'File tugas wajib diunggah.';
        }

        // Simpan ke database jika tidak ada error
        if (empty($error)) {
            $status = (time() > strtotime($tugasDetail['deadline'])) ? 'Terlambat' : 'Tepat Waktu';

            $stmtInsert = $conn->prepare("INSERT INTO pengumpulan (mahasiswa_id, tugas_id, nama_mahasiswa, nim, mata_kuliah, judul_tugas, keterangan, nama_file, file_path, status) VALUES (?,?,?,?,?,?,?,?,?,?)");
            $stmtInsert->bind_param(
                "iissssssss",
                $_SESSION['mahasiswa_id'],
                $tugas_id,
                $mhs['nama'],
                $mhs['nim'],
                $tugasDetail['mata_kuliah'],
                $tugasDetail['judul'],
                $keterangan,
                $namaFile,
                $filePath,
                $status
            );

            if ($stmtInsert->execute()) {
                $success = "Tugas \"{$tugasDetail['judul']}\" berhasil dikumpulkan! Status: $status";
            } else {
                $error = 'Gagal menyimpan data ke database: ' . $conn->error;
            }
        }
    }
}

// ============ HAPUS DATA (OPSIONAL, MILIK SENDIRI) ============
if (isset($_GET['hapus'])) {
    $idHapus = (int) $_GET['hapus'];
    $stmtCek = $conn->prepare("SELECT * FROM pengumpulan WHERE id = ? AND mahasiswa_id = ?");
    $stmtCek->bind_param("ii", $idHapus, $_SESSION['mahasiswa_id']);
    $stmtCek->execute();
    $dataCek = $stmtCek->get_result()->fetch_assoc();

    if ($dataCek) {
        if ($dataCek['file_path'] && file_exists(__DIR__ . '/' . $dataCek['file_path'])) {
            unlink(__DIR__ . '/' . $dataCek['file_path']);
        }
        $stmtDel = $conn->prepare("DELETE FROM pengumpulan WHERE id = ?");
        $stmtDel->bind_param("i", $idHapus);
        $stmtDel->execute();
        $success = 'Data pengumpulan tugas berhasil dihapus.';
    }
}

// Daftar tugas untuk dropdown
$daftarTugas = $conn->query("SELECT * FROM tugas ORDER BY deadline ASC");

include 'header.php';
?>

<div class="page-hero">
    <div class="container">
        <h2>Form Upload Tugas</h2>
        <div class="breadcrumb"><a href="index.php">Home</a> / Form Upload Tugas</div>
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

        <div class="grid-2" style="grid-template-columns: 1fr 1.3fr; align-items:start;">

            <!-- FORM INPUT -->
            <div class="form-card">
                <h3 class="text-navy mb-24" style="font-family:var(--font-head); font-size:19px;">📤 Kumpulkan Tugas</h3>

                <form method="POST" action="form.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Nama Mahasiswa</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($mhs['nama']) ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>NIM</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($mhs['nim']) ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Pilih Tugas <span>*</span></label>
                        <select name="tugas_id" class="form-control" required>
                            <option value="">-- Pilih Tugas --</option>
                            <?php while ($t = $daftarTugas->fetch_assoc()): ?>
                                <option value="<?= $t['id'] ?>">
                                    [<?= htmlspecialchars($t['mata_kuliah']) ?>] <?= htmlspecialchars($t['judul']) ?> (Deadline: <?= date('d/m/Y', strtotime($t['deadline'])) ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Keterangan / Catatan Tugas <span>*</span></label>
                        <textarea name="keterangan" class="form-control" placeholder="Contoh: Tugas dikerjakan secara individu, mencakup 5 entitas utama..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Upload File Tugas <span>*</span></label>
                        <input type="file" name="file_tugas" id="file_tugas" class="form-control" style="display:none;" onchange="document.getElementById('fileLabel').textContent = this.files.length ? '📎 ' + this.files[0].name : 'Belum ada file dipilih';">
                        <label for="file_tugas" style="display:block; border:2px dashed var(--border); border-radius:8px; padding:18px; text-align:center; cursor:pointer; color:var(--gray); font-size:13.5px;" id="fileLabel">
                            📁 Klik untuk memilih file
                        </label>
                        <div class="form-hint">Format: PDF, DOC, DOCX, ZIP, RAR, PHP, HTML, SQL, PNG, JPG (maks. 10MB)</div>
                    </div>

                    <button type="submit" name="submit_tugas" class="btn btn-primary btn-full">📤 Kumpulkan Tugas</button>
                </form>
            </div>

            <!-- OUTPUT: TABEL TUGAS YANG SUDAH DIKUMPULKAN -->
            <div>
                <h3 class="text-navy mb-24" style="font-family:var(--font-head); font-size:19px;">📋 Tugas yang Sudah Anda Kumpulkan</h3>

                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tugas</th>
                                <th>MK</th>
                                <th>File</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmtMy = $conn->prepare("SELECT * FROM pengumpulan WHERE mahasiswa_id = ? ORDER BY tanggal_kumpul DESC");
                            $stmtMy->bind_param("i", $_SESSION['mahasiswa_id']);
                            $stmtMy->execute();
                            $myData = $stmtMy->get_result();
                            $no = 1;
                            if ($myData->num_rows > 0):
                                while ($row = $myData->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td class="fw-600" style="max-width:160px;"><?= htmlspecialchars($row['judul_tugas']) ?></td>
                                <td style="font-size:12px;"><?= htmlspecialchars($row['mata_kuliah']) ?></td>
                                <td>
                                    <?php if ($row['file_path']): ?>
                                        <a href="<?= htmlspecialchars($row['file_path']) ?>" target="_blank">📎</a>
                                    <?php else: ?>-<?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($row['status'] === 'Tepat Waktu'): ?>
                                        <span class="badge badge-success">OK</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Telat</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="form.php?hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus pengumpulan tugas ini?')" style="color:var(--danger); font-size:12px;">🗑️ Hapus</a>
                                </td>
                            </tr>
                            <?php
                                endwhile;
                            else:
                            ?>
                            <tr><td colspan="6" class="text-center text-gray" style="padding:24px;">Belum ada tugas dikumpulkan.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
