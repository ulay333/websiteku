-- =============================================
-- DATABASE: WEBSITE PENGUMPULAN TUGAS KULIAH
-- Universitas Nusantara PGRI Kediri
-- =============================================

CREATE DATABASE IF NOT EXISTS db_tugaskuliah CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_tugaskuliah;

-- Tabel Mahasiswa
CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    program_studi VARCHAR(100) DEFAULT 'Teknik Informatika',
    semester INT DEFAULT 1,
    foto VARCHAR(255) DEFAULT 'default.png',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Tugas
CREATE TABLE IF NOT EXISTS tugas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    deskripsi TEXT,
    mata_kuliah ENUM('Basis Data', 'Rancangan Perangkat Lunak', 'Pemrograman Web') NOT NULL,
    deadline DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Pengumpulan Tugas
CREATE TABLE IF NOT EXISTS pengumpulan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mahasiswa_id INT NOT NULL,
    tugas_id INT NOT NULL,
    nama_mahasiswa VARCHAR(100) NOT NULL,
    nim VARCHAR(20) NOT NULL,
    mata_kuliah ENUM('Basis Data', 'Rancangan Perangkat Lunak', 'Pemrograman Web') NOT NULL,
    judul_tugas VARCHAR(200) NOT NULL,
    keterangan TEXT,
    nama_file VARCHAR(255),
    file_path VARCHAR(255),
    tanggal_kumpul TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Tepat Waktu', 'Terlambat') DEFAULT 'Tepat Waktu',
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id),
    FOREIGN KEY (tugas_id) REFERENCES tugas(id)
);

-- Tabel Kontak
CREATE TABLE IF NOT EXISTS kontak (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subjek VARCHAR(200),
    pesan TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Gallery
CREATE TABLE IF NOT EXISTS gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    deskripsi TEXT,
    gambar VARCHAR(255) NOT NULL,
    kategori VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =============================================
-- DATA AWAL (SEED DATA)
-- =============================================

-- Data Mahasiswa (password: mahasiswa123)
INSERT INTO mahasiswa (nim, nama, email, password, program_studi, semester) VALUES
('20211001', 'Budi Santoso', 'budi@student.unpkediri.ac.id', '$2y$10$AIAX15sjEC7OvP1Hw7hnhemn2zqKADWlo4Mrg14k3y6OUqVgVfbMq', 'Teknik Informatika', 5),
('20211002', 'Siti Rahayu', 'siti@student.unpkediri.ac.id', '$2y$10$AIAX15sjEC7OvP1Hw7hnhemn2zqKADWlo4Mrg14k3y6OUqVgVfbMq', 'Teknik Informatika', 5),
('20211003', 'Ahmad Fajar', 'ahmad@student.unpkediri.ac.id', '$2y$10$AIAX15sjEC7OvP1Hw7hnhemn2zqKADWlo4Mrg14k3y6OUqVgVfbMq', 'Teknik Informatika', 5);

-- Data Tugas
INSERT INTO tugas (judul, deskripsi, mata_kuliah, deadline) VALUES
('ER Diagram Sistem Perpustakaan', 'Buat ER Diagram untuk sistem perpustakaan digital dengan minimal 5 entitas', 'Basis Data', DATE_ADD(CURDATE(), INTERVAL 14 DAY)),
('Dokumen SRS Aplikasi Mobile', 'Buat dokumen Software Requirements Specification untuk aplikasi mobile', 'Rancangan Perangkat Lunak', DATE_ADD(CURDATE(), INTERVAL 10 DAY)),
('Website Portfolio Pribadi', 'Buat website portfolio menggunakan HTML, CSS, dan JavaScript', 'Pemrograman Web', DATE_ADD(CURDATE(), INTERVAL 7 DAY)),
('Normalisasi Database 3NF', 'Lakukan normalisasi tabel hingga bentuk normal ketiga (3NF)', 'Basis Data', DATE_ADD(CURDATE(), INTERVAL 21 DAY)),
('Use Case Diagram & Activity Diagram', 'Buat Use Case dan Activity Diagram untuk sistem e-commerce', 'Rancangan Perangkat Lunak', DATE_ADD(CURDATE(), INTERVAL 18 DAY));

-- Data Gallery
INSERT INTO gallery (judul, deskripsi, gambar, kategori) VALUES
('Seminar Nasional IT 2024', 'Kegiatan seminar nasional teknologi informasi yang dihadiri 500+ peserta', 'seminar.jpg', 'Akademik'),
('Workshop Web Development', 'Pelatihan pembuatan website modern untuk mahasiswa semester 3-6', 'workshop.jpg', 'Workshop'),
('Wisuda Sarjana 2024', 'Prosesi wisuda sarjana Universitas Nusantara PGRI Kediri angkatan 2024', 'wisuda.jpg', 'Akademik'),
('Hackathon 24 Jam', 'Kompetisi pemrograman maraton selama 24 jam oleh HMTI', 'hackathon.jpg', 'Kompetisi'),
('PKL Industry Visit', 'Kunjungan industri mahasiswa Teknik Informatika ke perusahaan IT terkemuka', 'pkl.jpg', 'Industri'),
('Festival Seni Mahasiswa', 'Pagelaran seni budaya tahunan yang menampilkan bakat mahasiswa', 'festival.jpg', 'Budaya');
