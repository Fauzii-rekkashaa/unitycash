<?php
include 'koneksi.php';

if (!isset($_GET['nik'], $_GET['status'])) {
    header("Location: data_warga.php");
    exit();
}

$nik = mysqli_real_escape_string($koneksi, $_GET['nik']);
$status = $_GET['status'];

if ($status !== 'aktif' && $status !== 'tidak_aktif') {
    header("Location: data_warga.php");
    exit();
}

$query = mysqli_query(
    $koneksi,
    "UPDATE warga SET status='$status' WHERE nik='$nik'"
);

if ($query) {
    header("Location: data_warga.php");
} else {
    echo "Gagal update status: " . mysqli_error($koneksi);
}
