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

$role = $_SESSION['role'];
if ($role !== 'admin' && $role !== 'rt/rw' && $role !== 'bendahara') {
    echo "<script>
        alert('Akses ditolak! Halaman ini hanya dapat diakses oleh pengurus.');
        window.location.href = 'dashboard_admin.php';
    </script>";
    exit();
}

// AMBIL NIK
$username_pengurus = $_SESSION['username_pengurus'];

$query = mysqli_query($koneksi, "
    SELECT 
        nik_pengurus,
        nama_pengurus,
        jenis_kelamin,
        tempat_lahir,
        tanggal_lahir,
        alamat,
        jabatan,
        username_pengurus,
        role,
        foto_profil
    FROM pengurus
    WHERE nik_pengurus = '$username_pengurus'
") or die(mysqli_error($koneksi));


$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data pengurus tidak ditemukan');</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UnityCash | Profil Saya</title>
    <link rel="stylesheet" href="profil.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <a href="verif_pembayaran_adm.php"><i class="fa-solid fa-file-invoice"></i> Verifikasi Pembayaran</a>
    <a href="data_kelola_uang.php"><i class="fa-solid fa-money-bill-transfer"></i> Data Kelola Uang</a>
    <a href="laporan_keuangan.php"><i class="fa-solid fa-wallet"></i> Laporan Keuangan</a>
    <a href="profil.php"class="active"><i class="fa-solid fa-user"></i> Profil Saya</a>
</div>

   <!-- ===== MAIN ===== -->
<div class="main" id="main">

<!-- ===== HEADER (SAMA PERSIS PATOKAN) ===== -->
<div class="dashboard-header">
    <button class="menu-btn" id="menuBtn">
        <i class="fa-solid fa-bars"></i>
    </button>

    <h2>PROFIL SAYA</h2>

    <a class="navlink" href="logout.php"
       onclick="return confirm('Apakah Anda yakin ingin keluar?');">
        <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
    </a>
</div>
        <div class="content">

            <div class="profile-box">

            <div class="profile-header">
                <div class="photo"
                    style="background-image: url('uploads/<?= $data['foto_profil'] ?? 'default.jpg'; ?>');">
                </div>

                <div class="profile-name">
                    <h2><?= $data['nama_pengurus']; ?></h2>
                    <p><?= strtoupper($data['jabatan']); ?></p>
                </div>
            </div>

                <hr>

                <!-- DATA PRIBADI -->
                <div class="section">
                    <h3>Data Pribadi</h3>
                    <button class="edit-btn" data-bs-toggle="modal" data-bs-target="#modalPribadi"> Edit Data Pribadi</button>


                    <div class="row">
                        <div class="col">
                            <p>Nama</p>
                            <p>NIK</p>
                            <p>Jenis Kelamin</p>
                            <p>Tempat, Tanggal Lahir</p>
                            <p>Jabatan</p>
                            <p>Alamat</p>
                        </div>

                        <div class="col">
                            <p>: <?= $data['nama_pengurus']; ?></p>
                            <p>: <?= $data['nik_pengurus']; ?></p>
                            <p>: <?= $data['jenis_kelamin']; ?></p>
                            <p>: <?= $data['tempat_lahir'] ?? '-'; ?>, <?= $data['tanggal_lahir'] ?? '-'; ?></p>
                            <p>: <?= strtoupper($data['jabatan']); ?></p>
                            <p>: <?= $data['alamat'] ?? '-'; ?></p>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- DATA AKUN -->
                <div class="section">
                    <h3>Data Akun</h3>
                    <button class="edit-btn" data-bs-toggle="modal" data-bs-target="#modalAkun"> Edit Data Akun</button>


                    <div class="row">
                        <div class="col">
                            <p>Username</p>
                            <p>Kata Sandi</p>
                            <p>Status Akun</p>
                        </div>

                        <div class="col">
                            <p>: <?= $data['username_pengurus'] ?? '-'; ?></p>
                            <p>: ********</p>
                            <p>: Aktif</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

            <!-- MODAL EDIT DATA PRIBADI -->
            <div class="modal fade" id="modalPribadi" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">

                <form method="POST" action="update_data_pribadi.php">
                <div class="modal-content">

                    <div class="modal-header">
                    <h5 class="modal-title">Edit Data Pribadi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                    <input type="hidden" name="nik_pengurus" value="<?= $data['nik_pengurus']; ?>">

                    <div class="mb-2">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control"
                            name="nama_pengurus"
                            value="<?= $data['nama_pengurus']; ?>">
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Jenis Kelamin</label>
                        <select class="form-select" name="jenis_kelamin">
                        <option <?= $data['jenis_kelamin']=='Laki-laki'?'selected':''; ?>>Laki-laki</option>
                        <option <?= $data['jenis_kelamin']=='Perempuan'?'selected':''; ?>>Perempuan</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" class="form-control"
                            name="tempat_lahir"
                            value="<?= $data['tempat_lahir']; ?>">
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control"
                            name="tanggal_lahir"
                            value="<?= $data['tanggal_lahir']; ?>">
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control"
                                name="alamat"><?= $data['alamat']; ?></textarea>
                    </div>

                    </div>

                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>

                </div>
                </form>

            </div>
            </div>


                    <!-- MODAL EDIT DATA AKUN -->
            <div class="modal fade" id="modalAkun" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">

                <form method="POST" action="update_data_akun.php" enctype="multipart/form-data">

                <div class="modal-content">

                    <div class="modal-header">
                    <h5 class="modal-title">Edit Data Akun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                    <input type="hidden" name="nik_pengurus" value="<?= $data['nik_pengurus']; ?>">

                    <div class="mb-2">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control"
                            name="username_pengurus"
                            value="<?= $data['username_pengurus']; ?>">
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Password Baru</label>
                        <input type="password" class="form-control" name="password">
                    </div>

                    <div class="mb-2">
                         <label class="form-label">Foto Profil</label>
                        <input type="file" class="form-control" name="foto" accept="image/*">
                    </div>

                    </div>

                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>

                </div>
                </form>

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const menuBtn = document.getElementById("menuBtn");
        const sidebar = document.getElementById("sidebar");
        const main = document.getElementById("main");

        menuBtn.addEventListener("click", () => {
            sidebar.classList.toggle("close");
            main.classList.toggle("expand");
        });
    </script>

    </body>
    </html>
