<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nik      = mysqli_real_escape_string($koneksi, $_POST['nik']);
    $password = $_POST['pass'];

    /* ===============================
       LOGIN WARGA
    =============================== */
    $query  = "SELECT * FROM warga WHERE nik = '$nik' AND status='aktif'";
    $qWarga = mysqli_query($koneksi, $query);
    $warga = mysqli_fetch_assoc($qWarga);

    if ($warga && password_verify($password, $warga['password'])) {

        $_SESSION['username'] = $warga['nik'];
        $_SESSION['nik']      = $warga['nik'];
        $_SESSION['role']     = 'warga';
        $_SESSION['nama']     = $warga['nama_warga'];

        header("Location: dashboard_warga.php");
        exit();
    }

    /* ===============================
       LOGIN PENGURUS
    =============================== */
    $query = "SELECT * FROM pengurus WHERE nik_pengurus = '$nik'";
    $qPengurus = mysqli_query($koneksi, $query);
    $pengurus = mysqli_fetch_assoc($qPengurus);

    if ($pengurus && password_verify($password, $pengurus['password_pengurus'])) {

        $_SESSION['username_pengurus'] = $pengurus['nik_pengurus'];
        $_SESSION['role']     = $pengurus['role']; // rt/rw / bendahara
        $_SESSION['nama']     = $pengurus['nama_pengurus'];

        header("Location: dashboard_admin.php");
        exit();
    }

    $error = "NIK atau password salah!";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNITYCASH - Login</title>
    <link rel="stylesheet" href="page.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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

    <!-- Main Content -->
    <div class="container">
        <div class="login-box">
            <h2>Masuk</h2>

            <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
            <div class="input-group">
                <input type="text" name="nik" placeholder="Masukkan NIK"required>
                <i class="fa-solid fa-user-plus"></i>
            </div>

            <div class="input-group">
                <input type="password" name="pass" id="password" placeholder="Kata Sandi"required>
                <i id="toggleIcon" class="fa-solid fa-eye-slash" onclick="togglePassword()"></i>
            </div>

            <button type="submit" class="btn login">Masuk</button>
            <p class="signup-text">Belum punya akun? <a href="regis.php">Daftar</a></p>
        </div>
    </div>

    <script>
    function togglePassword() {
        const pwd = document.getElementById("password");
        const icon = document.getElementById("toggleIcon");

        if (pwd.type === "password") {
            pwd.type = "text";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        } else {
            pwd.type = "password";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        }
    }
    </script>

</body>
</html>