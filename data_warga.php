<?php
session_start();
include 'koneksi.php';

/* ===============================
   AUTH PENGURUS
================================ */
if (!isset($_SESSION['role'])) {
    header("Location: login_page.php");
    exit();
}

$role = strtolower(trim($_SESSION['role']));
if ($role !== 'admin' && $role !== 'rt/rw' && $role !== 'bendahara') {
    echo "<script>
        alert('Akses ditolak! Halaman ini hanya untuk pengurus.');
        window.location.href='dashboard_admin.php';
    </script>";
    exit();
}

/* ===============================
   AMBIL DATA WARGA
================================ */
$query = mysqli_query($koneksi, "SELECT * FROM warga ORDER BY nama_warga ASC");
$no = 1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>UnityCash | Data Warga</title>

    <!-- CSS CUSTOM -->
    <link rel="stylesheet" href="data_warga.css">

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

    <a href="dashboard_admin.php"><i class="fa-solid fa-gauge"></i> Halaman Utama</a>
    <a href="data_warga.php" class="active"><i class="fa-solid fa-users"></i> Data Warga</a>
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

    <h2>DATA WARGA</h2>

    <a class="navlink" href="logout.php"
       onclick="return confirm('Apakah Anda yakin ingin keluar?');">
        <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
    </a>
</div>

    <!-- CONTENT -->
    <div class="content">
        <div class="table-box">

            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>Alamat</th>
                        <th>Jenis Kelamin</th>
                        <th>Tempat Lahir</th>
                        <th>Tanggal Lahir</th>
                        <th>Status</th>
                        <th class="text-center">Verifikasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (mysqli_num_rows($query) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama_warga']) ?></td>
                            <td><?= $row['nik'] ?></td>
                            <td><?= htmlspecialchars($row['alamat']) ?></td>
                            <td><?= htmlspecialchars($row['jenis_kelamin']) ?></td>
                            <td><?= htmlspecialchars($row['tempat_lahir']) ?></td>
                            <td><?= htmlspecialchars($row['tanggal_lahir']) ?></td>

                            <!-- STATUS -->
                            <td>
                                <?php if ($row['status'] === 'aktif'): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Tidak Aktif</span>
                                <?php endif; ?>
                            </td>

                            <!-- VERIFIKASI -->
                            <td class="text-center">
                                <a href="verifikasi_warga.php?nik=<?= $row['nik'] ?>&status=aktif"
                                   onclick="return confirm('Aktifkan warga ini?')"
                                   class="btn btn-sm btn-success">
                                    <i class="fa-solid fa-check"></i>
                                </a>

                                <a href="verifikasi_warga.php?nik=<?= $row['nik'] ?>&status=tidak_aktif"
                                   onclick="return confirm('Nonaktifkan warga ini?')"
                                   class="btn btn-sm btn-danger">
                                    <i class="fa-solid fa-xmark"></i>
                                </a>
                            </td>

                            <!-- AKSI -->
                            <td>
                                <!-- DETAIL -->
                                <button class="btn btn-sm btn-info"
                                        data-bs-toggle="modal"
                                        data-bs-target="#detail<?= $row['nik'] ?>">
                                    <i class="fa-solid fa-eye"></i>
                                </button>

                                <!-- EDIT -->
                                <button class="btn btn-sm btn-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#edit<?= $row['nik'] ?>">
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                <!-- DELETE -->
                                <a href="hapus_warga.php?nik=<?= $row['nik'] ?>"
                                   onclick="return confirm('Yakin hapus data ini?')"
                                   class="btn btn-sm btn-danger">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- ================= MODAL DETAIL ================= -->
                        <div class="modal fade" id="detail<?= $row['nik'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Warga</h5>
                                        <button class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <p><b>Nama:</b> <?= $row['nama_warga'] ?></p>
                                        <p><b>NIK:</b> <?= $row['nik'] ?></p>
                                        <p><b>Alamat:</b> <?= $row['alamat'] ?></p>
                                        <p><b>Status:</b> <?= $row['status'] ?></p>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- ================= MODAL EDIT ================= -->
                        <div class="modal fade" id="edit<?= $row['nik'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST" action="edit_warga.php">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Warga</h5>
                                            <button class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <input type="hidden" name="nik" value="<?= $row['nik'] ?>">

                                            <div class="mb-3">
                                                <label>Nama</label>
                                                <input type="text" name="nama"
                                                       class="form-control"
                                                       value="<?= $row['nama_warga'] ?>" required>
                                            </div>

                                            <div class="mb-3">
                                                <label>Alamat</label>
                                                <textarea name="alamat"
                                                          class="form-control"
                                                          required><?= $row['alamat'] ?></textarea>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-warning">
                                                <i class="fa-solid fa-floppy-disk"></i> Simpan
                                            </button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>

                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Data warga belum tersedia</td>
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
    const menuBtn = document.getElementById("menuBtn");
    menuBtn.onclick = () => {
        document.getElementById("sidebar").classList.toggle("close");
        document.getElementById("main").classList.toggle("expand");
    };
</script>

</body>
</html>
