<?php
session_start();
include 'koneksi.php';

/* ===============================
   CEK LOGIN
================================ */
if (!isset($_SESSION['username_pengurus'])) {
    header("Location: login_page.php");
    exit();
}

/* ===============================
   AMBIL NIK DARI FORM (BENAR)
================================ */
$nik = $_POST['nik_pengurus'];

/* ===============================
   UPDATE DATA PRIBADI
================================ */
$query = mysqli_query($koneksi, "
    UPDATE pengurus SET
        nama_pengurus   = '$_POST[nama_pengurus]',
        jenis_kelamin   = '$_POST[jenis_kelamin]',
        tempat_lahir    = '$_POST[tempat_lahir]',
        tanggal_lahir   = '$_POST[tanggal_lahir]',
        alamat          = '$_POST[alamat]'
    WHERE nik_pengurus = '$nik'
");

if (!$query) {
    echo "<script>alert('Gagal memperbarui data');history.back();</script>";
    exit();
}

echo "<script>
    alert('Data pribadi berhasil diperbarui');
    window.location='profil.php';
</script>";
