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

// AMBIL ROLE DARI SESSION
$role = $_SESSION['role'];

// HANYA ADMIN, RT/RW, DAN BENDAHARA YANG BOLEH MASUK
if ($role !== 'admin' && $role !== 'rt/rw' && $role !== 'bendahara') {
    echo "<script>
        alert('Akses ditolak! Halaman ini hanya dapat diakses oleh pengurus.');
        window.location.href = 'login_page.php';
    </script>";
    exit();
}

/* ===============================
   PROSES EDIT
=============================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nik    = mysqli_real_escape_string($koneksi, $_POST['nik']);
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    mysqli_query(
        $koneksi,
        "UPDATE warga 
         SET nama_warga='$nama',
             alamat='$alamat'
         WHERE nik='$nik'"
    );

    header("Location: data_warga.php");
    exit();
}
?>
