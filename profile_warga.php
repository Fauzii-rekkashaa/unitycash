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

include 'koneksi.php';

$nik = $_SESSION['username'];

$query = mysqli_query($koneksi, "SELECT * FROM warga WHERE nik='$nik'");
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>UnityCash | Profil Saya</title>
    <link rel="stylesheet" href="profile_warga.css">
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

    <a href="dashboard_warga.php" ><i class="fa-solid fa-gauge"></i> Halaman Utama</a>
    <a href="data_pembayaran.php"><i class="fa-solid fa-users"></i> Data pembayaran</a>
    <a href="riwayat_pembayaran.php"><i class="fa-solid fa-file-invoice"></i> Riwayat Pembayaran</a>
    <a href="profile_warga.php"class="active"><i class="fa-solid fa-user"></i> Profil Saya</a>
</div>

<!-- MAIN -->
<div class="main" id="main">

    <!-- HEADER -->
    <div class="header dashboard-header">
        <button class="menu-btn" id="menuBtn"><i class="fa-solid fa-bars"></i></button>
        <h2>PROFIL SAYA</h2>
        <a class="navlink" href="logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar?');">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
        </a>
    </div>

    <!-- CONTENT -->
    <div class="content">

        <div class="profile-box">

            <!-- Header Profile -->
            <div class="profile-header">
                <div class="photo"
     style="background-image: url('uploads/<?= $data['foto'] ?? 'default.jpg'; ?>');">
</div>


                <div class="profile-name">
                    <h2><?= $data['nama_warga']; ?></h2>
                    <p>Warga</p>
                </div>
            </div>

            <hr>

            <!-- Data Pribadi -->
            <div class="section">
                <h3>Data Pribadi</h3>
               <button class="edit-btn" data-bs-toggle="modal" data-bs-target="#modalPribadi">Edit Data Pribadi</button>



                <div class="row">
                    <div class="col">
                        <p>Nama</p>
                        <p>NIK</p>
                        <p>Jenis Kelamin</p>
                        <p>Tempat, Tanggal Lahir</p>
                        <p>Alamat</p>
                    </div>

                    <div class="col">
                        <p>: <?= $data['nama_warga']; ?></p>
                        <p>: <?= $data['nik']; ?></p>
                        <p>: <?= ucfirst($data['jenis_kelamin']); ?></p>
                        <p>: <?= $data['tempat_lahir']; ?>, <?= date('d F Y', strtotime($data['tanggal_lahir'])); ?></p>
                        <p>: <?= $data['alamat']; ?></p>
                    </div>
                </div>
            </div>

            <hr>

            <!-- DATA AKUN -->
            <div class="section">
                <h3>Data Akun</h3>
                <button class="edit-btn" data-bs-toggle="modal" data-bs-target="#modalAkun">Edit Data Akun</button>


                <div class="row">
                    <div class="col">
                        <p>Username</p>
                        <p>Kata Sandi</p>
                        <p>Status Akun</p>
                    </div>

                    <div class="col">
                        <p>: <?= $data['username']; ?></p>
                        <p>: ********</p>
                        <p>: <?= ucfirst($data['status']); ?></p>
                    </div>
                </div>
            </div>
            </div>
</div>

    <!-- MODAL EDIT DATA PRIBADI -->
<div class="modal fade" id="modalPribadi" tabindex="-1">
<div class="modal-dialog modal-dialog-centered modal-lg">
        <form method="POST" action="update_warga_profile.php">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Pribadi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="nik" value="<?= $data['nik']; ?>">

                    <div class="mb-2">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control"
                               name="nama_warga"
                               value="<?= $data['nama_warga']; ?>" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Jenis Kelamin</label>
                        <select class="form-select" name="jenis_kelamin">
                            <option <?= $data['jenis_kelamin']=='laki-laki'?'selected':''; ?>>
                                Laki-laki
                            </option>
                            <option <?= $data['jenis_kelamin']=='perempuan'?'selected':''; ?>>
                                Perempuan
                            </option>
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
        <form method="POST" action="update_warga_data.php" enctype="multipart/form-data">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Akun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="nik" value="<?= $data['nik']; ?>">

                    <div class="mb-2">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control"
                               name="username"
                               value="<?= $data['username']; ?>">
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Password Baru</label>
                        <input type="password" class="form-control"
                               name="password"
                               placeholder="Kosongkan jika tidak diganti">
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Foto Profil</label>
                        <input type="file" class="form-control"
                               name="foto" accept="image/*">
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


<!-- SCRIPT -->
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
