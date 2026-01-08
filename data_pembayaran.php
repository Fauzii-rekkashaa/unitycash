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

/* ===============================
   NOTIFIKASI UPLOAD
================================ */
if (isset($_SESSION['upload_error'])) {
    echo "<script>alert('{$_SESSION['upload_error']}');</script>";
    unset($_SESSION['upload_error']);
}

if (isset($_SESSION['upload_success'])) {
    echo "<script>alert('{$_SESSION['upload_success']}');</script>";
    unset($_SESSION['upload_success']);
}

/* ===============================
   AMBIL DATA TAGIHAN
================================ */
$query = mysqli_query($koneksi, "
    SELECT *
    FROM pembayaran
    WHERE jenis_transaksi = 'Tagihan'
      AND status_aktif = 'aktif'
    ORDER BY tanggal_mulai DESC
");

$no = 1;
$tanggal_hari_ini = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UnityCash | Data Pembayaran</title>

    <link rel="stylesheet" href="data_pembayaran.css">
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

    <a href="dashboard_warga.php" ><i class="fa-solid fa-gauge"></i> Halaman Utama</a>
    <a href="data_pembayaran.php"class="active"><i class="fa-solid fa-users"></i> Data pembayaran</a>
    <a href="riwayat_pembayaran.php"><i class="fa-solid fa-file-invoice"></i> Riwayat Pembayaran</a>
    <a href="profile_warga.php"><i class="fa-solid fa-user"></i> Profil Saya</a>
</div>

<!-- ===== MAIN ===== -->
<div class="main" id="main">

    <!-- HEADER -->
    <div class="header dashboard-header">
        <button class="menu-btn" id="menuBtn"><i class="fa-solid fa-bars"></i></button>
        <h2>DATA PEMBAYARAN</h2>
        <a class="navlink" href="logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?');">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
        </a>
    </div>

<div class="content">
<div class="table-box">

<table class="table table-striped align-middle">
<thead>
<tr>
    <th>No</th>
    <th>Jenis Pembayaran</th>
    <th>Dari Tanggal</th>
    <th>Sampai Tanggal</th>
    <th>Nominal</th>
    <th class="text-center">Bukti Pembayaran</th>
</tr>
</thead>

<tbody>
<?php if (mysqli_num_rows($query) > 0): ?>
<?php while ($row = mysqli_fetch_assoc($query)): ?>

<?php
    $expired = ($row['tanggal_selesai'] < $tanggal_hari_ini);
?>

<tr>
    <td><?= $no++ ?></td>

    <td><?= htmlspecialchars($row['nama_transaksi']) ?></td>

    <td><?= date('d-m-Y', strtotime($row['tanggal_mulai'])) ?></td>
    <td><?= date('d-m-Y', strtotime($row['tanggal_selesai'])) ?></td>
    <td>Rp <?= number_format($row['nominal_default'],0,',','.') ?></td>

    <td class="text-center">

    <?php if ($expired): ?>
        <span class="badge bg-secondary">Periode Berakhir</span>
    <?php else: ?>
        <form method="POST" action="proses_upload_bukti.php" enctype="multipart/form-data">
            <input type="hidden" name="id_pembayaran" value="<?= $row['id_pembayaran'] ?>">
            <input type="hidden" name="nominal" value="<?= $row['nominal_default'] ?>">

            <input type="file" name="bukti" required class="form-control form-control-sm mb-2">

            <button type="submit" name="upload_bukti" class="btn btn-sm btn-primary">
                <i class="fa-solid fa-upload"></i> Upload
            </button>
        </form>
    <?php endif; ?>

    </td>
</tr>

<?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="6" class="text-center">Belum ada tagihan pembayaran</td>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
menuBtn.onclick = () => {
    sidebar.classList.toggle("close");
    main.classList.toggle("expand");
};
</script>

</body>
</html>
