<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nik        = mysqli_real_escape_string($koneksi, $_POST['nik']);
    $nama_warga = mysqli_real_escape_string($koneksi, $_POST['nama_warga']);
    $alamat     = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $tempat_lahir = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $username   = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password   = $_POST['password'];

    // status default sesuai DB
    $status = 'aktif';

    // cek NIK sudah terdaftar
    $cek = mysqli_query($koneksi, "SELECT nik FROM warga WHERE nik='$nik'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('NIK sudah terdaftar!');history.back();</script>";
        exit;
    }

    // hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // insert ke DB
    $query = "INSERT INTO warga 
        (nik, nama_warga, alamat, jenis_kelamin, tempat_lahir, tanggal_lahir, status, username, password) 
        VALUES 
        ('$nik', '$nama_warga', '$alamat', '$jenis_kelamin', '$tempat_lahir', '$tanggal_lahir', '$status', '$username', '$hashed_password')";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Registrasi berhasil!');window.location='login_page.php';</script>";
    } else {
        echo "<script>alert('Registrasi gagal: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNITYCASH - regis</title>
    <link rel="stylesheet" href="regis.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

    <!-- Navbar -->
    <header class="navbar">
        <div class="container-fluid">
            <div class="logo">
                <img src="Accounting.jpg" alt="logo"> 
                <span>UNITYCASH</span>
            </div>
            <nav>
                <ul class="nav-links">
                    <li><a href="landing_page.php">Beranda</a></li>
                    <li><a href="about.php">Tentang</a></li>
                    <li><a href="panduan.php">Panduan</a></li>
                    <li><a href="faQ.php">Bantuan/FAQ</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Form Registrasi -->
<div class="container mt-5">
     <div class="regis-formbox">
                <h3 class="text-center mb-4 fw-bold">BUAT AKUN ANDA</h3>

                <form method="POST" action="regis.php">

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_warga" class="form-control" placeholder="Masukkan Nama Lengkap" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NIK</label>
                        <input type="text" name="nik" class="form-control" placeholder="Masukkan NIK" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" placeholder="Masukkan Alamat Lengkap" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tempat Lahir</label>
                        <textarea name="tempat_lahir" class="form-control" placeholder="Masukkan Tempat Lahir" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">jenis kelamin</label>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="laki-laki">Laki-laki</option>
                            <option value="perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan Username" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Buat Kata Sandi" required>
                    </div>

                    <!-- Data default -->
                    <input type="hidden" name="status" value="aktif">

                    <button type="submit" class="btn btn-primary w-100 py-2">
                        Daftar
                    </button>

                </form>

            </div>
        </div>
    </div>
</div>

</body>
</html>
