<?php
include 'koneksi.php';

// pastikan request dari form
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: profile_warga.php");
    exit;
}

// ambil & amankan data
$nik           = mysqli_real_escape_string($koneksi, $_POST['nik']);
$nama_warga    = mysqli_real_escape_string($koneksi, $_POST['nama_warga']);
$jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
$tempat_lahir = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir']);
$tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
$alamat        = mysqli_real_escape_string($koneksi, $_POST['alamat']);

// update DATA PRIBADI
mysqli_query($koneksi, "
    UPDATE warga SET
        nama_warga    = '$nama_warga',
        jenis_kelamin = '$jenis_kelamin',
        tempat_lahir  = '$tempat_lahir',
        tanggal_lahir = '$tanggal_lahir',
        alamat        = '$alamat'
    WHERE nik = '$nik'
") or die(mysqli_error($koneksi));

// kembali ke profil
header("Location: profile_warga.php");
exit;
