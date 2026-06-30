<footer>
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="brand" style="margin-bottom:4px;">
                    <div class="logo-circle">UN</div>
                    <div class="brand-text">
                        <h1>SIKAD</h1>
                        <span>Sistem Kuliah & Tugas Digital</span>
                    </div>
                </div>
                <p>SIKAD adalah platform pengumpulan tugas kuliah resmi <?= UNIV_NAME ?>, dirancang untuk mempermudah mahasiswa mengumpulkan tugas mata kuliah Basis Data, Rancangan Perangkat Lunak, dan Pemrograman Web secara digital, tepat waktu, dan terdokumentasi rapi.</p>
            </div>
            <div class="footer-col">
                <h4>Menu Utama</h4>
                <ul>
                    <li><a href="index.php">→ Home</a></li>
                    <li><a href="gallery.php">→ Gallery</a></li>
                    <li><a href="profile.php">→ Profile</a></li>
                    <li><a href="form.php">→ Upload Tugas</a></li>
                    <li><a href="contact.php">→ Contact Us</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Hubungi Kami</h4>
                <ul>
                    <li><a href="#">📍 <?= UNIV_ADDRESS ?></a></li>
                    <li><a href="mailto:<?= UNIV_EMAIL ?>">✉️ <?= UNIV_EMAIL ?></a></li>
                    <li><a href="#">📞 <?= UNIV_PHONE ?></a></li>
                    <li><a href="#">🌐 <?= UNIV_WEBSITE ?></a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <span>&copy; <?= date('Y') ?> SIKAD - <?= UNIV_NAME ?>. Hak Cipta Dilindungi.</span>
            <span>Dibuat untuk Tugas Mata Kuliah Pemrograman Web</span>
        </div>
    </div>
</footer>

<script src="js/main.js"></script>
</body>
</html>
