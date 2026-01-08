<?php
session_start();
include 'koneksi.php';

/* ===============================
   AUTH PENGURUS
================================ */
if (!isset($_SESSION['username_pengurus'])) {
    echo "<script>alert('Silakan login terlebih dahulu!');location='login_page.php';</script>";
    exit();
}

/* ===============================
   VALIDASI PARAMETER
================================ */
if (!isset($_GET['id']) || !isset($_GET['aksi'])) {
    header("Location: verif_pembayaran_adm.php");
    exit();
}

$id   = mysqli_real_escape_string($koneksi, $_GET['id']);
$aksi = $_GET['aksi'];
$nik_pengurus = $_SESSION['username_pengurus'];

/* ===============================
   TENTUKAN STATUS
================================ */
if ($aksi === 'setujui') {
    $status = 'disetujui';
} elseif ($aksi === 'tolak') {
    $status = 'ditolak';
} else {
    header("Location: verif_pembayaran_adm.php");
    exit();
}

/* ===============================
   UPDATE RIWAYAT PEMBAYARAN
================================ */
mysqli_query($koneksi, "
    UPDATE riwayat_pembayaran
    SET 
        status_pembayaran = '$status',
        nik_pengurus = '$nik_pengurus'
    WHERE id_riwayat = '$id'
");

/* ===============================
   REDIRECT KEMBALI
================================ */
header("Location: verif_pembayaran_adm.php");
exit();
