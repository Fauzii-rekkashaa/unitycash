<?php
session_start();
include 'koneksi.php';

/* ===============================
   CEK LOGIN
================================ */
if (!isset($_SESSION['username_pengurus'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu!');
        window.location.href = 'login_page.php';
    </script>";
    exit();
}

/* ===============================
   CEK ROLE
================================ */
$role = $_SESSION['role'];
if ($role !== 'admin' && $role !== 'rt/rw' && $role !== 'bendahara') {
    echo "<script>
        alert('Akses ditolak! Halaman ini hanya dapat diakses oleh pengurus.');
        window.location.href = 'dashboard_admin.php';
    </script>";
    exit();
}   

/* ===============================
   DATA DASHBOARD (CARD)
================================ */
$q_warga = mysqli_query($koneksi,"SELECT COUNT(*) AS total FROM warga WHERE status='aktif'");
$jml_warga = mysqli_fetch_assoc($q_warga)['total'] ?? 0;

$q_pemasukan = mysqli_query($koneksi,"
    SELECT SUM(nominal_bayar) AS total
    FROM riwayat_pembayaran
    WHERE status_pembayaran='disetujui'
");
$total_pemasukan = mysqli_fetch_assoc($q_pemasukan)['total'] ?? 0;

$q_pengeluaran = mysqli_query($koneksi,"
    SELECT SUM(nominal_default) AS total
    FROM pembayaran
    WHERE jenis_transaksi='pengeluaran'
    AND status_aktif='aktif'
");
$total_pengeluaran = mysqli_fetch_assoc($q_pengeluaran)['total'] ?? 0;

$total_kas = $total_pemasukan - $total_pengeluaran;

/* ===============================
   DATA CHART
================================ */
$bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
$pemasukan_bulanan = array_fill(0,12,0);
$pengeluaran_bulanan = array_fill(0,12,0);

$q1 = mysqli_query($koneksi,"
    SELECT MONTH(tanggal_bayar) bulan, SUM(nominal_bayar) total
    FROM riwayat_pembayaran
    WHERE status_pembayaran='disetujui'
    GROUP BY bulan
");
while($r=mysqli_fetch_assoc($q1)){
    $pemasukan_bulanan[$r['bulan']-1]=(float)$r['total'];
}

$q2 = mysqli_query($koneksi,"
    SELECT MONTH(tanggal_mulai) bulan, SUM(nominal_default) total
    FROM pembayaran
    WHERE jenis_transaksi='pengeluaran'
    AND status_aktif='aktif'
    GROUP BY bulan
");
while($r=mysqli_fetch_assoc($q2)){
    $pengeluaran_bulanan[$r['bulan']-1]=(float)$r['total'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UnityCash | Dashboard</title>

<link rel="stylesheet" href="dashboard_admin.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<!-- ===== SIDEBAR (SAMA PERSIS PATOKAN) ===== -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <i class="fa-solid fa-coins"></i>
        <span>UNITYCASH</span>
    </div>

    <a href="dashboard_admin.php" class="active"><i class="fa-solid fa-gauge"></i> Halaman Utama</a>
    <a href="data_warga.php"><i class="fa-solid fa-users"></i> Data Warga</a>
    <a href="verif_pembayaran_adm.php"><i class="fa-solid fa-file-invoice"></i> Verifikasi Pembayaran</a>
    <a href="data_kelola_uang.php"><i class="fa-solid fa-money-bill-transfer"></i> Data Kelola Uang</a>
    <a href="laporan_keuangan.php"><i class="fa-solid fa-wallet"></i> Laporan Keuangan</a>
    <a href="profil.php"><i class="fa-solid fa-user"></i> Profil Saya</a>
</div>

<!-- ===== MAIN ===== -->
<div class="main" id="main">

<!-- ===== HEADER (SAMA PERSIS PATOKAN) ===== -->
<div class="dashboard-header">
    <button class="menu-btn" id="menuBtn">
        <i class="fa-solid fa-bars"></i>
    </button>

    <h2>HALAMAN UTAMA</h2>

    <a class="navlink" href="logout.php"
       onclick="return confirm('Apakah Anda yakin ingin keluar?');">
        <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
    </a>
</div>

<main class="content">

<!-- CARD (TIDAK DIUBAH) -->
<div class="card-container">
    <div class="card card-blue">
        <h1><?= $jml_warga ?></h1>
        <p>Jumlah Pengguna</p>
    </div>
    <div class="card card-green">
        <h1>Rp <?= number_format($total_kas,0,',','.') ?></h1>
        <p>Total Kas</p>
    </div>
    <div class="card card-yellow">
        <h1>Rp <?= number_format($total_pemasukan,0,',','.') ?></h1>
        <p>Total Pemasukan</p>
    </div>
    <div class="card card-red">
        <h1>Rp <?= number_format($total_pengeluaran,0,',','.') ?></h1>
        <p>Total Pengeluaran</p>
    </div>
</div>

<div class="chart-wrapper">
    <h2 class="chart-title">Grafik Keuangan</h2>
    <canvas id="myChart"></canvas>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(myChart,{
    type:'bar',
    data:{
        labels:<?=json_encode($bulan)?>,
        datasets:[
            {label:'Pemasukan',data:<?=json_encode($pemasukan_bulanan)?>,backgroundColor:'#f1c40f'},
            {label:'Pengeluaran',data:<?=json_encode($pengeluaran_bulanan)?>,backgroundColor:'#e74c3c'}
        ]
    },
    options:{responsive:true,scales:{y:{beginAtZero:true}}}
});
</script>

<script>
menuBtn.onclick = () => {
    sidebar.classList.toggle("close");
    main.classList.toggle("expand");
};
</script>

</body>
</html>