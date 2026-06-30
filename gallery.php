<?php
require_once 'config.php';
$pageTitle = 'Gallery';

$kategoriIcons = [
    'Akademik' => '🎓',
    'Workshop' => '💻',
    'Kompetisi' => '🏆',
    'Industri' => '🏢',
    'Budaya' => '🎭',
];

include 'header.php';
?>

<div class="page-hero">
    <div class="container">
        <h2>Gallery Kegiatan Kampus</h2>
        <p>Dokumentasi kegiatan akademik dan non-akademik di <?= UNIV_NAME ?></p>
        <div class="breadcrumb"><a href="index.php">Home</a> / Gallery</div>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="title-bar"><h2 class="section-title">Momen & Kegiatan</h2></div>
        <p class="section-subtitle">Berbagai dokumentasi seminar, workshop, kompetisi, dan kegiatan mahasiswa Teknik Informatika.</p>

        <div class="gallery-grid">
            <?php
            $result = $conn->query("SELECT * FROM gallery ORDER BY created_at DESC");
            while ($row = $result->fetch_assoc()):
                $icon = $kategoriIcons[$row['kategori']] ?? '🖼️';
            ?>
            <div class="gallery-item">
                <div class="gallery-placeholder">
                    <?= $icon ?>
                    <span><?= htmlspecialchars($row['gambar']) ?></span>
                </div>
                <span class="gallery-cat-badge"><?= htmlspecialchars($row['kategori']) ?></span>
                <div class="overlay">
                    <div class="gallery-overlay-text">
                        <h4><?= htmlspecialchars($row['judul']) ?></h4>
                        <p><?= htmlspecialchars($row['deskripsi']) ?></p>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
