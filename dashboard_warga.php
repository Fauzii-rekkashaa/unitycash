<?php
session_start();
include 'koneksi.php';

/* ===============================
   CEK LOGIN
================================ */
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Silakan login terlebih dahulu!');location='login_page.php';</script>";
    exit();
}

if ($_SESSION['role'] !== 'warga') {
    echo "<script>alert('Akses ditolak!');location='login_page.php';</script>";
    exit();
}

$nik = $_SESSION['username'];

/* ===============================
   DATA CARD
================================ */

/* TOTAL TAGIHAN AKTIF */
$q_tagihan = mysqli_query($koneksi, "
    SELECT IFNULL(SUM(nominal_default),0) AS total
    FROM pembayaran
    WHERE jenis_transaksi='Tagihan'
    AND status_aktif='aktif'
");
$total_tagihan = mysqli_fetch_assoc($q_tagihan)['total'] ?? 0;

/* TOTAL DIBAYAR */
$q_bayar = mysqli_query($koneksi, "
    SELECT IFNULL(SUM(nominal_bayar),0) AS total
    FROM riwayat_pembayaran
    WHERE nik='$nik'
    AND status_pembayaran='disetujui'
");
$total_bayar = mysqli_fetch_assoc($q_bayar)['total'] ?? 0;

/* TOTAL KAS KESELURUHAN (SEMUA WARGA) */
$q_kas = mysqli_query($koneksi, "
    SELECT IFNULL(SUM(nominal_bayar),0) AS total
    FROM riwayat_pembayaran
    WHERE status_pembayaran='disetujui'
");
$total_kas = mysqli_fetch_assoc($q_kas)['total'] ?? 0;

/* ===============================
   DATA GRAFIK
================================ */

$bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
$tagihan_bulanan = array_fill(0, 12, 0);
$bayar_bulanan   = array_fill(0, 12, 0);

/* TAGIHAN BULANAN */
$q_tagihan_bulan = mysqli_query($koneksi, "
    SELECT 
        MONTH(tanggal_mulai) AS bulan,
        SUM(nominal_default) AS total
    FROM pembayaran
    WHERE jenis_transaksi='Tagihan'
    AND status_aktif='aktif'
    GROUP BY MONTH(tanggal_mulai)
");

while ($r = mysqli_fetch_assoc($q_tagihan_bulan)) {
    $tagihan_bulanan[$r['bulan'] - 1] = (float)$r['total'];
}

/* PEMBAYARAN BULANAN */
$q_bayar_bulan = mysqli_query($koneksi, "
    SELECT 
        MONTH(tanggal_bayar) AS bulan,
        SUM(nominal_bayar) AS total
    FROM riwayat_pembayaran
    WHERE nik='$nik'
    AND status_pembayaran='disetujui'
    GROUP BY MONTH(tanggal_bayar)
");

while ($r = mysqli_fetch_assoc($q_bayar_bulan)) {
    $bayar_bulanan[$r['bulan'] - 1] = (float)$r['total'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UnityCash | Dashboard</title>

    <link rel="stylesheet" href="dashboard_warga.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<!-- ===== SIDEBAR ===== -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <i class="fa-solid fa-coins"></i>
        <span>UNITYCASH</span>
    </div>

    <a href="dashboard_warga.php" class="active"><i class="fa-solid fa-gauge"></i> Halaman Utama</a>
    <a href="data_pembayaran.php"><i class="fa-solid fa-users"></i> Data pembayaran</a>
    <a href="riwayat_pembayaran.php"><i class="fa-solid fa-file-invoice"></i> Riwayat Pembayaran</a>
    <a href="profile_warga.php"><i class="fa-solid fa-user"></i> Profil Saya</a>
</div>

<!-- ===== MAIN ===== -->
<div class="main" id="main">

    <!-- HEADER -->
    <div class="header dashboard-header">
        <button class="menu-btn" id="menuBtn"><i class="fa-solid fa-bars"></i></button>
        <h2>HALAMAN UTAMA</h2>
        <a class="navlink" href="logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?');">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
        </a>
    </div>

    <main class="content">

        <!-- CARD ATAS (TETAP) -->
<div class="card-container">
    <div class="card card-blue">
        <h1><?= number_format($total_tagihan,0,',','.') ?></h1>
        <p>Jumlah Tagihan</p>
    </div>

    <div class="card card-red">
        <h1><?= number_format($total_bayar,0,',','.') ?></h1>
        <p>Total Dibayar</p>
    </div>

    <div class="card card-green">
        <h1><?= number_format($total_kas,0,',','.') ?></h1>
        <p>Total Kas Keseluruhan</p>
    </div>
</div>


        <!-- GRAFIK -->
        <div class="chart-section">
            <h2 class="chart-title">Grafik Tagihan</h2>

            <div class="chart-legend">
                <span><span class="dot blue"></span> Jumlah Tagihan</span>
                <span><span class="dot red"></span> Total di bayar</span>
            </div>

            <canvas id="tagihanChart"></canvas>
        </div>

    </main>

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

</div>

<!-- MENU -->
<script>
const menuBtn = document.getElementById("menuBtn");
const sidebar = document.getElementById("sidebar");
const main = document.getElementById("main");

menuBtn.onclick = () => {
    sidebar.classList.toggle("close");
    main.classList.toggle("expand");
};
</script>

<!-- CHART -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('tagihanChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($bulan) ?>,
        datasets: [
            {
                label: 'Jumlah Tagihan',
                data: <?= json_encode($tagihan_bulanan) ?>,
                backgroundColor: '#1c67d1'
            },
            {
                label: 'Total di bayar',
                data: <?= json_encode($bayar_bulanan) ?>,
                backgroundColor: '#e72828'
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        },
        plugins: { legend: { display: false } }
    }
});
</script>

</body>
</html>
