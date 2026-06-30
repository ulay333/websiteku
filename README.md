# SIKAD - Sistem Pengumpulan Tugas Kuliah
### Universitas Nusantara PGRI Kediri

Website untuk pengumpulan tugas kuliah mata kuliah **Basis Data**, **Rancangan Perangkat Lunak**, dan **Pemrograman Web**, dibangun menggunakan **PHP native** (tanpa framework/CMS) + **MySQL** + **CSS murni**.

---

## 📁 Struktur File

```
tugaskuliah/
├── config.php          # Konfigurasi koneksi database + fungsi captcha
├── header.php          # Header & navbar (di-include semua halaman)
├── footer.php          # Footer (di-include semua halaman)
├── index.php           # MENU 1: Home
├── gallery.php         # MENU 2: Gallery kegiatan kampus
├── profile.php         # MENU 3: Profile mahasiswa (perlu login)
├── form.php            # MENU 4: Form upload tugas (input & output PHP)
├── contact.php         # MENU 5: Contact Us (form pesan + captcha)
├── tabel.php           # Halaman tambahan: Rekap tabel tugas (tabel dimodifikasi)
├── login.php           # Login mahasiswa + captcha
├── daftar.php           # Daftar / Registrasi akun mahasiswa baru + captcha
├── logout.php          # Logout
├── database.sql        # Script SQL (struktur tabel + data awal)
├── css/style.css        # Semua styling (CSS murni, tanpa Bootstrap/framework)
├── js/main.js           # Interaksi kecil (menu mobile, search tabel, dll.)
└── uploads/             # Folder penyimpanan file tugas yang diupload
```

---

## ⚙️ Cara Instalasi (XAMPP / Laragon)

1. **Install XAMPP** (jika belum ada): https://www.apachefriends.org/
2. Copy folder `tugaskuliah` ke dalam folder `htdocs` (XAMPP) atau `www` (Laragon).
3. Jalankan **Apache** dan **MySQL** dari XAMPP Control Panel.
4. Buka **phpMyAdmin** di `http://localhost/phpmyadmin`.
5. Klik tab **Import**, pilih file `database.sql`, lalu klik **Go**.
   - Database `db_tugaskuliah` beserta seluruh tabel dan data awal akan otomatis dibuat.
6. Buka browser dan akses: `http://localhost/tugaskuliah/index.php`

---

## 🔐 Akun Login Demo (Mahasiswa)

| NIM | Password | Nama |
|---|---|---|
| 20211001 | mahasiswa123 | Budi Santoso |
| 20211002 | mahasiswa123 | Siti Rahayu |
| 20211003 | mahasiswa123 | Ahmad Fajar |

---

## 🗄️ Struktur Database

5 tabel utama:
- **mahasiswa** — data akun & profil mahasiswa
- **tugas** — daftar tugas dari 3 mata kuliah
- **pengumpulan** — data tugas yang dikumpulkan mahasiswa (input dari form.php)
- **kontak** — pesan masuk dari halaman Contact Us
- **gallery** — data dokumentasi kegiatan kampus

---

## ✅ Checklist Sesuai Ketentuan Tugas

- [x] Minimal 5 menu utama: **Home, Gallery, Profile, Form, Contact Us** (+ bonus menu Rekap, Daftar)
- [x] Menggunakan **PHP** untuk form (input tersimpan ke MySQL, output ditampilkan kembali dalam tabel)
- [x] Menggunakan **CSS murni** (tanpa Bootstrap/Tailwind/framework apapun) dan **tanpa CMS**
- [x] Terdapat **tabel yang dimodifikasi** (filter, search, `<tfoot>` rekap total, kolom gabungan colspan) di `tabel.php`
- [x] 3 Mata kuliah: **Basis Data, Rancangan Perangkat Lunak, Pemrograman Web**
- [x] Nama universitas: **Universitas Nusantara PGRI Kediri**
- [x] **Login mahasiswa** dengan password ter-enkripsi (bcrypt)
- [x] **Database mahasiswa** (tabel `mahasiswa` di MySQL)
- [x] **CAPTCHA** (math captcha, di halaman Login, Daftar, dan Contact Us)
- [x] **Fitur Daftar/Registrasi** akun mahasiswa baru (`daftar.php`), dengan validasi NIM/email duplikat

---

## 🛠️ Catatan Teknis

- Password disimpan menggunakan `password_hash()` (bcrypt), bukan plain text.
- Semua input form disanitasi (`htmlspecialchars`, `real_escape_string`, `prepared statement`) untuk mencegah SQL Injection & XSS.
- Upload file dibatasi: ekstensi (pdf, doc, docx, zip, rar, php, html, sql, png, jpg) dan ukuran maksimal 10MB.
- Status pengumpulan ("Tepat Waktu"/"Terlambat") dihitung otomatis berdasarkan perbandingan waktu submit dengan deadline tugas.
- CAPTCHA dibuat secara native PHP (soal matematika acak + jawaban disimpan di session), tidak butuh API key eksternal apapun. Soal otomatis berganti tiap kali halaman dimuat ulang atau tombol 🔄 diklik.
- Sudah ditest end-to-end (login, upload file, simpan ke DB, tampil di tabel, hapus data) — semua berfungsi tanpa error.

---

**Universitas Nusantara PGRI Kediri**
Jl. KH. Ahmad Dahlan No.76, Mojoroto, Kediri, Jawa Timur
