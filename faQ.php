<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantuan / FAQ - UnityCash</title>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="faQ.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

    <!-- Navbar -->
    <header class="navbar">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <div class="logo d-flex align-items-center">
                <img src="Accounting.jpg" alt="logo" class="logo-img">
                <span class="brand">UNITYCASH</span>
            </div>
            <nav>
                <ul class="nav-links d-flex">
                    <li><a href="landing_page.php">Beranda</a></li>
                    <li><a href="about.php">Tentang</a></li>
                    <li><a href="panduan.php">Panduan</a></li>
                    <li><a class="active" href="faQ.php">Bantuan/FAQ</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- SECTION FAQ -->
    <section class="faq-section">
        <div class="container faq-inner">
            <h1 class="faq-title">Bantuan / FaQ</h1>

            <div class="faq-list">

                <article class="faq-box">
                    <h3 class="faq-q">1. Bagaimana cara membuat akun di UnityCash?</h3>
                    <p class="faq-a">Buka menu “Daftar”. Lalu, isi data diri seperti nama, NIK, alamat, dan kata sandi lalu klik Daftar.</p>
                </article>

                <article class="faq-box">
                    <h3 class="faq-q">2. Bagaimana cara login ke sistem?</h3>
                    <p class="faq-a">Masukkan NIK/Username dan kata sandi di halaman login, lalu klik Masuk.</p>
                </article>

                <article class="faq-box">
                    <h3 class="faq-q">3. Bagaimana cara membayar iuran?</h3>
                    <p class="faq-a">Masuk ke menu "Data Pembayaran". Lalu, upload bukti pembayaran cukup unggah foto/struk bukti pembayaran.</p>
                </article>

                <article class="faq-box">
                    <h3 class="faq-q">4. Apa yang terjadi setelah mengunggah bukti pembayaran?</h3>
                    <p class="faq-a">
                        Bendahara akan memeriksa bukti pembayaran.<br>
                        Jika valid → Status berubah menjadi Disetujui.<br>
                        Jika ada kesalahan → Status Ditolak dan Anda dapat mengunggah ulang.
                    </p>
                </article>

                <article class="faq-box">
                    <h3 class="faq-q">5. Bagaimana cara melihat status pembayaran saya?</h3>
                    <p class="faq-a">
                        Buka menu Riwayat Pembayaran.<br>
                        Anda dapat melihat status: Menunggu, Disetujui, atau Ditolak, beserta detail nominal dan tanggal.
                    </p>
                </article>

                <article class="faq-box">
                    <h3 class="faq-q">6. Bagaimana jika data profil saya salah?</h3>
                    <p class="faq-a">
                        Anda dapat mengubah data melalui Profil Saya → Edit Data Pribadi.
                    </p>
                </article>

            </div>
        </div>
    </section>

    <!-- FOOTER -->
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
