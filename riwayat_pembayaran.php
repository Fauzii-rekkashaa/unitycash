<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu!');
        window.location.href = 'login_page.php';
    </script>";
    exit();
}

if ($_SESSION['role'] !== 'warga') {
    echo "<script>
        alert('Akses ditolak! Halaman ini hanya dapat diakses oleh warga.');
        window.location.href = 'login_page.php';
    </script>";
    exit();
}

$nik = $_SESSION['username'];

/* ===============================
   AMBIL RIWAYAT PEMBAYARAN (BARU)
================================ */
$query = mysqli_query($koneksi, "
    SELECT 
        rp.tanggal_bayar,
        rp.nominal_bayar,
        rp.status_pembayaran,
        p.jenis_transaksi,
        p.nama_transaksi
    FROM riwayat_pembayaran rp
    JOIN pembayaran p ON rp.id_pembayaran = p.id_pembayaran
    WHERE rp.nik = '$nik'
    ORDER BY rp.tanggal_bayar DESC
") or die(mysqli_error($koneksi));


$no = 1;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>UnityCash | Riwayat Pembayaran</title>

    <!-- CSS TETAP -->
    <link rel="stylesheet" href="riwayat_pembayaran.css">

    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- ICON -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<!-- ===== SIDEBAR ===== -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <i class="fa-solid fa-coins"></i>
        <span>UNITYCASH</span>
    </div>

    <a href="dashboard_warga.php"><i class="fa-solid fa-gauge"></i> Halaman Utama</a>
    <a href="data_pembayaran.php"><i class="fa-solid fa-users"></i> Data pembayaran</a>
    <a href="riwayat_pembayaran.php"  class="active"><i class="fa-solid fa-file-invoice"></i> Riwayat Pembayaran</a>
    <a href="profile_warga.php"><i class="fa-solid fa-user"></i> Profil Saya</a>
</div>

<!-- ===== MAIN ===== -->
<div class="main" id="main">

    <!-- HEADER -->
    <div class="header dashboard-header">
        <button class="menu-btn" id="menuBtn"><i class="fa-solid fa-bars"></i></button>
        <h2>RIWAYAT PEMBAYARAN</h2>
        <a class="navlink" href="logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?');">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
        </a>
    </div>

<!-- ===== CONTENT ===== -->
<div class="content">
<div class="table-box">

<table class="table table-striped align-middle">
<thead>
<tr>
    <th>No</th>
    <th>Jenis Transaksi</th>
    <th>Nama Transaksi</th>
    <th>Tanggal Bayar</th>
    <th>Nominal</th>
    <th>Status</th>
</tr>
</thead>

<tbody>
<?php if (mysqli_num_rows($query) > 0): ?>
<?php while ($row = mysqli_fetch_assoc($query)): ?>

<tr>
    <td><?= $no++ ?></td>
    <td><?= htmlspecialchars($row['jenis_transaksi']) ?></td>
    <td><?= htmlspecialchars($row['nama_transaksi']) ?></td>
    <td><?= date('d-m-Y', strtotime($row['tanggal_bayar'])) ?></td>
    <td>Rp <?= number_format($row['nominal_bayar'], 0, ',', '.') ?></td>
    <td>
        <?php if ($row['status_pembayaran'] === 'disetujui'): ?>
            <span class="badge bg-success">
                <i class="fa-solid fa-circle-check"></i> Disetujui
            </span>
        <?php elseif ($row['status_pembayaran'] === 'menunggu'): ?>
            <span class="badge bg-warning text-dark">
                <i class="fa-solid fa-clock"></i> Menunggu
            </span>
        <?php else: ?>
            <span class="badge bg-danger">
                <i class="fa-solid fa-circle-xmark"></i> Ditolak
            </span>
        <?php endif; ?>
    </td>
</tr>

<?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="6" class="text-center">
        Belum ada riwayat pembayaran
    </td>
</tr>
<?php endif; ?>
</tbody>

</table>
</div>
</div>

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

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById("menuBtn").onclick = () => {
    document.getElementById("sidebar").classList.toggle("close");
    document.getElementById("main").classList.toggle("expand");
};
</script>

</body>
</html>
