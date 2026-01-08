<?php
session_start();
include 'koneksi.php';

/* ===============================
   AUTH PENGURUS
================================ */
if (!isset($_SESSION['username_pengurus'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu!');
        window.location.href = 'login_page.php';
    </script>";
    exit();
}

$role = strtolower(trim($_SESSION['role']));

if ($role !== 'admin' && $role !== 'bendahara') {
    echo "<script>
        alert('Akses ditolak! Halaman ini hanya untuk admin dan bendahara.');
        window.location.href='dashboard_admin.php';
    </script>";
    exit();
}


/* ===============================
   DATA PEMBAYARAN MENUNGGU
================================ */
$query = mysqli_query($koneksi, "
    SELECT 
        rp.id_riwayat,
        rp.tanggal_bayar,
        rp.nominal_bayar,
        rp.bukti_bayar,
        w.nama_warga,
        p.jenis_transaksi,
        p.nama_transaksi
    FROM riwayat_pembayaran rp
    JOIN warga w ON rp.nik = w.nik
    JOIN pembayaran p ON rp.id_pembayaran = p.id_pembayaran
    WHERE rp.status_pembayaran = 'menunggu'
    ORDER BY rp.tanggal_bayar DESC
");

$no = 1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>UnityCash | Verifikasi Pembayaran</title>

    <!-- CSS CUSTOM -->
    <link rel="stylesheet" href="verif_pembayaran_adm.css">

    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- ICON -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<!-- ===== SIDEBAR (SAMA PERSIS PATOKAN) ===== -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <i class="fa-solid fa-coins"></i>
        <span>UNITYCASH</span>
    </div>

    <a href="dashboard_admin.php" ><i class="fa-solid fa-gauge"></i> Halaman Utama</a>
    <a href="data_warga.php"><i class="fa-solid fa-users"></i> Data Warga</a>
    <a href="verif_pembayaran_adm.php"class="active"><i class="fa-solid fa-file-invoice"></i> Verifikasi Pembayaran</a>
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

    <h2>VERIFIAKSI PEMBAYARAN</h2>

    <a class="navlink" href="logout.php"
       onclick="return confirm('Apakah Anda yakin ingin keluar?');">
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
                        <th>Nama Warga</th>
                        <th>Jenis Transaksi</th>
                        <th>Nama Transaksi</th>
                        <th>Tanggal Bayar</th>
                        <th>Nominal</th>
                        <th>Bukti</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (mysqli_num_rows($query) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama_warga']) ?></td>
                            <td><?= htmlspecialchars($row['jenis_transaksi']) ?></td>
                            <td><?= htmlspecialchars($row['nama_transaksi']) ?></td>
                            <td><?= date('d-m-Y', strtotime($row['tanggal_bayar'])) ?></td>
                            <td>Rp <?= number_format($row['nominal_bayar'], 0, ',', '.') ?></td>

                            <td class="text-center">
                                <a href="uploads/bukti_bayar/<?= htmlspecialchars($row['bukti_bayar']) ?>"
                                   target="_blank"
                                   class="btn btn-sm btn-secondary">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </a>
                            </td>

                            <td class="text-center">
                                <a href="proses_verifikasi.php?id=<?= $row['id_riwayat'] ?>&aksi=setujui"
                                   onclick="return confirm('Setujui pembayaran ini?')"
                                   class="btn btn-sm btn-success">
                                    <i class="fa-solid fa-check"></i>
                                </a>

                                <a href="proses_verifikasi.php?id=<?= $row['id_riwayat'] ?>&aksi=tolak"
                                   onclick="return confirm('Tolak pembayaran ini?')"
                                   class="btn btn-sm btn-danger">
                                    <i class="fa-solid fa-xmark"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">
                            Tidak ada pembayaran menunggu verifikasi
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>

<!-- ===== FOOTER (SAMA PERSIS PATOKAN) ===== -->
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
    const menuBtn = document.getElementById("menuBtn");
    menuBtn.onclick = () => {
        document.getElementById("sidebar").classList.toggle("close");
        document.getElementById("main").classList.toggle("expand");
    };
</script>

</body>
</html>
