<?php
session_start();
include 'koneksi.php';

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
   TAMBAH
================================ */
if (isset($_POST['tambah'])) {

    $jenis  = $_POST['kategori'];
    $nama   = $_POST['nama'];

    mysqli_query($koneksi, "
        INSERT INTO pembayaran 
        (jenis_transaksi, nama_transaksi, tanggal_mulai, tanggal_selesai, nominal_default, keterangan, status_aktif)
        VALUES (
            '$jenis',
            '$nama',
            '$_POST[tgl_mulai]',
            '$_POST[tgl_selesai]',
            '$_POST[nominal]',
            '$_POST[keterangan]',
            'aktif'
        )
    ");

    echo "<script>alert('Data berhasil ditambahkan');location='data_kelola_uang.php';</script>";
}

/* ===============================
   EDIT
================================ */
if (isset($_POST['edit'])) {

    $jenis  = $_POST['kategori'];
    $nama   = $_POST['nama'];

    mysqli_query($koneksi, "
        UPDATE pembayaran SET
            jenis_transaksi='$jenis',
            nama_transaksi='$nama',
            tanggal_mulai='$_POST[tgl_mulai]',
            tanggal_selesai='$_POST[tgl_selesai]',
            nominal_default='$_POST[nominal]',
            keterangan='$_POST[keterangan]'
        WHERE id_pembayaran='$_POST[id]'
    ");

    echo "<script>alert('Data berhasil diubah');location='data_kelola_uang.php';</script>";
}

/* ===============================
   NONAKTIF
================================ */
if (isset($_GET['hapus'])) {

    mysqli_query($koneksi, "
        UPDATE pembayaran 
        SET status_aktif='nonaktif' 
        WHERE id_pembayaran='$_GET[hapus]'
    ");

    echo "<script>alert('Data berhasil dinonaktifkan');location='data_kelola_uang.php';</script>";
}

/* ===============================
   GET DATA
================================ */
$data = mysqli_query($koneksi, "
    SELECT * FROM pembayaran
    WHERE status_aktif='aktif'
    ORDER BY tanggal_mulai DESC
");
$no = 1;

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>UnityCash | Data Kelola Uang</title>

<link rel="stylesheet" href="data_kelola_uang.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <i class="fa-solid fa-coins"></i>
        <span>UNITYCASH</span>
    </div>

    <a href="dashboard_admin.php"><i class="fa-solid fa-gauge"></i> Halaman Utama</a>
    <a href="data_warga.php"><i class="fa-solid fa-users"></i> Data Warga</a>
    <a href="verif_pembayaran_adm.php"><i class="fa-solid fa-file-invoice"></i> Verifikasi Pembayaran</a>
    <a href="data_kelola_uang.php" class="active">
        <i class="fa-solid fa-money-bill-transfer"></i> Data Kelola Uang
    </a>
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

    <h2>DATA KELOLA UANG</h2>

    <a class="navlink" href="logout.php"
       onclick="return confirm('Apakah Anda yakin ingin keluar?');">
        <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
    </a>
</div>


<div class="content">
<div class="table-box">

<div class="d-flex justify-content-end mb-3">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambah">
        <i class="fa-solid fa-plus"></i> Tambah
    </button>
</div>

<table class="table table-striped align-middle">
<thead>
<tr>
    <th>No</th>
    <th>Periode</th>
    <th>Nominal</th>
    <th>Kategori</th>
    <th>Nama Transaksi</th>
    <th>Keterangan</th>
    <th class="text-center">Aksi</th>
</tr>
</thead>

<tbody>
<?php if (mysqli_num_rows($data) > 0): ?>
<?php while ($row = mysqli_fetch_assoc($data)) : ?>
<tr>
    <td><?= $no++ ?></td>
    <td>
        <?= date('d-m-Y', strtotime($row['tanggal_mulai'])) ?> s/d
        <?= date('d-m-Y', strtotime($row['tanggal_selesai'])) ?>
    </td>
    <td>Rp <?= number_format($row['nominal_default'],0,',','.') ?></td>
    <td>
        <span class="badge-kategori"><?= $row['jenis_transaksi'] ?></span>
    </td>
    <td><?= htmlspecialchars($row['nama_transaksi']) ?></td>
    <td><?= htmlspecialchars($row['keterangan']) ?></td>
    <td class="text-center aksi">

        <!-- DETAIL -->
        <button class="btn btn-sm btn-info"
                data-bs-toggle="modal"
                data-bs-target="#detail<?= $row['id_pembayaran'] ?>">
            <i class="fa-solid fa-eye"></i>
        </button>

        <!-- EDIT -->
        <button class="btn btn-sm btn-warning"
                data-bs-toggle="modal"
                data-bs-target="#edit<?= $row['id_pembayaran'] ?>">
            <i class="fa-solid fa-pen"></i>
        </button>

        <!-- DELETE -->
        <a href="?hapus=<?= $row['id_pembayaran'] ?>"
           onclick="return confirm('Hapus data ini?')"
           class="btn btn-sm btn-danger">
            <i class="fa-solid fa-trash"></i>
        </a>
    </td>
</tr>

<!-- ================= MODAL DETAIL ================= -->
<div class="modal fade" id="detail<?= $row['id_pembayaran'] ?>" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
    <h5 class="modal-title">Detail Transaksi</h5>
    <button class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <p><b>Kategori:</b> <?= $row['jenis_transaksi'] ?></p>
    <p><b>Nama Transaksi:</b> <?= $row['nama_transaksi'] ?></p>
    <p><b>Periode:</b>
        <?= date('d-m-Y', strtotime($row['tanggal_mulai'])) ?> s/d
        <?= date('d-m-Y', strtotime($row['tanggal_selesai'])) ?>
    </p>
    <p><b>Nominal:</b> Rp <?= number_format($row['nominal_default'],0,',','.') ?></p>
    <p><b>Keterangan:</b> <?= $row['keterangan'] ?></p>
</div>
</div>
</div>
</div>

<!-- ================= MODAL EDIT ================= -->
<div class="modal fade" id="edit<?= $row['id_pembayaran'] ?>" tabindex="-1">
<div class="modal-dialog">
<form method="POST">
<input type="hidden" name="id" value="<?= $row['id_pembayaran'] ?>">
<div class="modal-content">

<div class="modal-header">
    <h5 class="modal-title">Edit Data</h5>
    <button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
    <select name="kategori" class="form-control mb-2" required>
        <option value="Tagihan" <?= $row['jenis_transaksi']=='Tagihan'?'selected':'' ?>>Tagihan</option>
        <option value="Pengeluaran" <?= $row['jenis_transaksi']=='Pengeluaran'?'selected':'' ?>>Pengeluaran</option>
    </select>

    <input type="text" name="nama" class="form-control mb-2"
           value="<?= $row['nama_transaksi'] ?>" required>

    <label>Tanggal Mulai</label>
    <input type="date" name="tgl_mulai" class="form-control mb-2"
           value="<?= $row['tanggal_mulai'] ?>" required>

    <label>Tanggal Selesai</label>
    <input type="date" name="tgl_selesai" class="form-control mb-2"
           value="<?= $row['tanggal_selesai'] ?>" required>

    <input type="number" name="nominal" class="form-control mb-2"
           value="<?= $row['nominal_default'] ?>" required>

    <textarea name="keterangan" class="form-control"><?= $row['keterangan'] ?></textarea>
</div>

<div class="modal-footer">
    <button name="edit" class="btn btn-warning">Simpan</button>
</div>

</div>
</form>
</div>
</div>

<?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="7" class="text-center">Data belum tersedia</td>
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

<!-- MODAL TAMBAH -->
<div class="modal fade" id="tambah" tabindex="-1">
<div class="modal-dialog">
<form method="POST">
<div class="modal-content">
<div class="modal-header">
    <h5 class="modal-title">Tambah Data</h5>
    <button class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">

<select name="kategori" class="form-control mb-2" required>
    <option value="">-- Pilih Kategori --</option>
    <option value="Tagihan">Tagihan</option>
    <option value="Pengeluaran">Pengeluaran</option>
</select>

<input type="text" name="nama" class="form-control mb-2" placeholder="Nama Transaksi" required>

<label>Tanggal Mulai</label>
<input type="date" name="tgl_mulai" class="form-control mb-2" required>

<label>Tanggal Selesai (Deadline)</label>
<input type="date" name="tgl_selesai" class="form-control mb-2" required>

<input type="number" name="nominal" class="form-control mb-2" placeholder="Nominal" required>

<textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>

</div>
<div class="modal-footer">
    <button name="tambah" class="btn btn-primary">Simpan</button>
</div>
</div>
</form>
</div>
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
