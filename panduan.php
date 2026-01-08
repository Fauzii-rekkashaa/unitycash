<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panduan Penggunaan - UnityCash</title>
    <link rel="stylesheet" href="panduan.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

    <!-- Navbar -->
    <header class="navbar">
        <div class="container-fluid">
            <div class="logo">
                <img src="Accounting.jpg" alt="logo">
                <span>UNITYCASH</span>
            </div>
            <nav>
                <ul class="nav-links">
                    <li><a href="landing_page.php">Beranda</a></li>
                    <li><a href="about.php">Tentang</a></li>
                    <li><a href="panduan.php">Panduan</a></li>
                    <li><a href="faQ.php">Bantuan/FAQ</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- SECTION PANDUAN -->
    <section class="panduan-container">
        <h1>Panduan Penggunaan</h1>

        <div class="panduan-grid">

            <!-- Daftar Akun -->
            <div class="card">
                <h2>Daftar Akun</h2>
                <p>1. Klik tombol “Daftar”.</p>
                <p>2. Isi data lengkap: Nama, NIK, dan Alamat.</p>
                <p>3. Buat kata sandi dan klik Daftar.</p>
                <p>4. Tunggu verifikasi dari Ketua RT atau Bendahara.</p>
                <br>
                <p>Setelah disetujui, akun aktif dan bisa digunakan login.</p>
            </div>

            <!-- Masuk -->
            <div class="card">
                <h2>Masuk</h2>
                <p>Semua pengguna (Warga, Bendahara, Ketua RT/RW) login melalui halaman yang sama.</p>
                <p>1. Klik tombol “Masuk”.</p>
                <p>2. Masukkan username/NIK dan password.</p>
                <p>3. Setelah berhasil, sistem menyesuaikan tampilan dashboard.</p>
            </div>

            <!-- Pembayaran Iuran (SUDAH DIUBAH) -->
            <div class="card">
                <h2>Pembayaran Iuran</h2>
                <p>1. Buka menu "Data Pembayaran".</p>
                <p>2. Pilih tagihan yang ingin dibayar.</p>
                <p>3. Lalu, klik upload bukti bayar.</p>
                <p>4. Setelah berhasil di upload, bendahara akan memeriksa bukti pembayaran.</p>
                <p>5. Jika valid, status akan merubah menjadi "disetujui".</p>
            </div>

            <!-- Verifikasi Pembayaran -->
            <div class="card">
                <h2>Verifikasi Pembayaran</h2>
                <p>1. Masuk ke menu Verifikasi Pembayaran.</p>
                <p>2. Lihat daftar warga yang telah mengunggah bukti.</p>
                <p>3. Klik Setujui jika valid atau Tolak jika salah.</p>
                <p>4. Saldo otomatis bertambah jika diterima.</p>
            </div>

            <!-- Pencatatan Pengeluaran -->
            <div class="card">
                <h2>Pencatatan Pengeluaran</h2>
                <p>1. Pilih menu “Data Kelola Uang”.</p>
                <p>2. Isi riwayat, nominal, kategori, dan keterangan.</p>
                <p>3. Klik Simpan.</p>
                <p>4. Data otomatis masuk laporan keuangan.</p>
            </div>

            <!-- Laporan Keuangan -->
            <div class="card">
                <h2>Laporan Keuangan</h2>
                <p>1. Klik tombol “Laporan Keuangan”.</p>
                <p>2. Pilih jenis transaksi.</p>
                <p>3. Pilih periode.</p>
                <p>4. Klik Cetak Laporan.</p>
            </div>

            <!-- Bantuan & FAQ -->
            <div class="card">
                <h2>Bantuan & FaQ</h2>
                <p>Berisi panduan singkat seperti:</p>
                <p>a. Cara registrasi dan login</p>
                <p>b. Cara membayar iuran</p>
                <p>c. Cara melihat laporan</p>
                <p>d. Solusi akun belum aktif</p>
                <p>Jika butuh bantuan lebih lanjut,
                    pengguna dapat menghubungi pengurus 
                    RT/RW melalui kontak yang tersedia.
                </p>
            </div>

            <!-- Keluar -->
            <div class="card">
                <h2>Keluar</h2>
                <p>Klik tombol “Keluar” di pojok kanan atas untuk keluar dari sistem dengan aman.</p>
            </div>

        </div>
    </section>

<!-- ===== FOOTER ===== -->
<footer>
    <div class="footer-left">
        <h3>UnityCash</h3>
        <p>Sistem pencatatan keuangan digital untuk transparansi dan kemudahan pengelolaan kas di lingkungan RT/RW</p>
    </div>

    <div class="footer-center">
        <p>&copy; 2025 UnityCash RT/RW</p>
        <p>Dibuat oleh | Tim PBL-TRPL101-BM2</p>
    </div>

    <div class="footer-right">
        <h3>❓ Bantuan</h3>
        <a href="landing_page.php">Beranda</a>
        <a href="about.php">Tentang</a>
        <a href="panduan.php">Panduan</a>
        <a href="faQ.php">FAQ</a>
    </div>
</footer>


</body>
</html>
