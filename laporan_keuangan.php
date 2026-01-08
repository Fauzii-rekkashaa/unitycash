<?php
session_start();
include 'koneksi.php';

/* ===============================
   CEK LOGIN PENGURUS
================================ */
if (!isset($_SESSION['username_pengurus'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu!');
        window.location.href = 'login_page.php';
    </script>";
    exit();
}

/* ===============================
   CEK ROLE PENGURUS
================================ */
$role = strtolower(trim($_SESSION['role']));

if ($role !== 'admin' && $role !== 'bendahara') {
    echo "<script>
        alert('Akses ditolak! Halaman ini hanya untuk admin dan bendahara.');
        window.location.href='dashboard_admin.php';
    </script>";
    exit();
}


$nik = $_SESSION['username_pengurus'];

/* ===============================
   FILTER
================================ */
$jenis   = $_GET['jenis']   ?? 'Semua';
$tanggal = $_GET['tanggal'] ?? '';
$bulan   = $_GET['bulan']   ?? '';
$tahun   = $_GET['tahun']   ?? '';

$query = "
    SELECT 
        rp.tanggal_bayar AS tanggal,
        p.jenis_transaksi,
        p.nama_transaksi,
        rp.nominal_bayar AS nominal
    FROM riwayat_pembayaran rp
    JOIN pembayaran p ON rp.id_pembayaran = p.id_pembayaran
    WHERE rp.status_pembayaran='disetujui'
    AND rp.nik='$nik'
";

if ($jenis === "Uang Masuk") {
    $query .= " AND p.jenis_transaksi='Tagihan'";
} elseif ($jenis === "Uang Keluar") {
    $query .= " AND p.jenis_transaksi='Pengeluaran'";
}

if ($tanggal) $query .= " AND DAY(rp.tanggal_bayar)=".(int)$tanggal;
if ($bulan)   $query .= " AND MONTH(rp.tanggal_bayar)=".(int)$bulan;
if ($tahun)   $query .= " AND YEAR(rp.tanggal_bayar)=".(int)$tahun;

$query .= " ORDER BY rp.tanggal_bayar DESC";
$result = mysqli_query($koneksi,$query);

$bulanList = [
    1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
    5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
    9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UnityCash | Laporan Keuangan</title>

<link rel="stylesheet" href="laporan_keuangan.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<!-- ===== SIDEBAR (DISAMAKAN) ===== -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <i class="fa-solid fa-coins"></i>
        <span>UNITYCASH</span>
    </div>

    <a href="dashboard_admin.php">
        <i class="fa-solid fa-gauge"></i> Halaman Utama
    </a>
    <a href="data_warga.php">
        <i class="fa-solid fa-users"></i> Data Warga
    </a>
    <a href="verif_pembayaran_adm.php">
        <i class="fa-solid fa-file-invoice"></i> Verifikasi Pembayaran
    </a>
    <a href="data_kelola_uang.php">
        <i class="fa-solid fa-money-bill-transfer"></i> Data Kelola Uang
    </a>
    <a href="laporan_keuangan.php" class="active">
        <i class="fa-solid fa-wallet"></i> Laporan Keuangan
    </a>
    <a href="profil.php">
        <i class="fa-solid fa-user"></i> Profil Saya
    </a>
</div>

<!-- ===== MAIN ===== -->
<div class="main" id="main">

<!-- ===== HEADER (DISAMAKAN) ===== -->
<div class="dashboard-header">
    <button class="menu-btn" id="menuBtn">
        <i class="fa-solid fa-bars"></i>
    </button>

    <h2>LAPORAN KEUANGAN</h2>

    <a class="navlink" href="logout.php"
       onclick="return confirm('Apakah Anda yakin ingin keluar?');">
        <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
    </a>
</div>

<main class="content">

<!-- FILTER -->
<div class="row justify-content-center mb-4">
<div class="col-lg-10">
<div class="filter-box shadow-sm">
<h4 class="mb-1">Periode & Jenis Transaksi</h4>
<p class="text-muted mb-4">Atur filter laporan sebelum mencetak</p>

<form method="GET" class="row g-3">
<div class="col-md-3">
<select name="jenis" class="form-select">
    <option value="Semua">Semua</option>
    <option value="Uang Masuk" <?= $jenis=='Uang Masuk'?'selected':'' ?>>Uang Masuk</option>
    <option value="Uang Keluar" <?= $jenis=='Uang Keluar'?'selected':'' ?>>Uang Keluar</option>
</select>
</div>

<div class="col-md-2">
<select name="tanggal" class="form-select">
<option value="">Tanggal</option>
<?php for($i=1;$i<=31;$i++): ?>
<option value="<?= $i ?>" <?= $tanggal==$i?'selected':'' ?>><?= $i ?></option>
<?php endfor; ?>
</select>
</div>

<div class="col-md-3">
<select name="bulan" class="form-select">
<option value="">Bulan</option>
<?php foreach($bulanList as $n=>$b): ?>
<option value="<?= $n ?>" <?= $bulan==$n?'selected':'' ?>><?= $b ?></option>
<?php endforeach; ?>
</select>
</div>

<div class="col-md-2">
<select name="tahun" class="form-select">
<option value="">Tahun</option>
<?php for($t=2020;$t<=date('Y');$t++): ?>
<option value="<?= $t ?>" <?= $tahun==$t?'selected':'' ?>><?= $t ?></option>
<?php endfor; ?>
</select>
</div>

<div class="col-md-2 d-grid">
<button class="btn btn-primary">
<i class="fa-solid fa-filter"></i> Terapkan
</button>
</div>
</form>
</div>
</div>
</div>

<!-- TABEL -->
<div class="card shadow-sm">
<div class="card-body">
<table class="table table-bordered table-striped text-center">
<thead class="table-primary">
<tr>
<th>Tanggal</th>
<th>Jenis</th>
<th>Nama Transaksi</th>
<th>Nominal</th>
</tr>
</thead>
<tbody>
<?php if(mysqli_num_rows($result)>0): while($r=mysqli_fetch_assoc($result)): ?>
<tr>
<td><?= date('d-m-Y',strtotime($r['tanggal'])) ?></td>
<td><?= $r['jenis_transaksi']=='Tagihan'?'Uang Masuk':'Uang Keluar' ?></td>
<td><?= $r['nama_transaksi'] ?></td>
<td>Rp <?= number_format($r['nominal'],0,',','.') ?></td>
</tr>
<?php endwhile; else: ?>
<tr><td colspan="4">Data tidak ditemukan</td></tr>
<?php endif; ?>
</tbody>
</table>

<div class="text-end">
<form action="cetak_laporan.php" method="POST" target="_blank">
<input type="hidden" name="jenis" value="<?= $jenis ?>">
<input type="hidden" name="tanggal" value="<?= $tanggal ?>">
<input type="hidden" name="bulan" value="<?= $bulan ?>">
<input type="hidden" name="tahun" value="<?= $tahun ?>">
<button class="btn btn-success">
<i class="fa-solid fa-print"></i> Cetak Laporan
</button>
</form>
</div>

</div>
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

<script>
menuBtn.onclick = () => {
    sidebar.classList.toggle("close");
    main.classList.toggle("expand");
};
</script>

</body>
</html>