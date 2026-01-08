<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'warga') {
    $_SESSION['upload_error'] = 'Akses tidak valid';
    header("Location: data_pembayaran.php");
    exit();
}

if (!isset($_POST['upload_bukti']) || !isset($_FILES['bukti'])) {
    $_SESSION['upload_error'] = 'File tidak terdeteksi';
    header("Location: data_pembayaran.php");
    exit();
}

$nik           = $_SESSION['username'];
$id_pembayaran = $_POST['id_pembayaran'];
$nominal       = $_POST['nominal'];
$tanggal       = date('Y-m-d');

$file   = $_FILES['bukti'];
$nama   = $file['name'];
$tmp    = $file['tmp_name'];
$size   = $file['size'];
$error  = $file['error'];

if ($error !== UPLOAD_ERR_OK) {
    $_SESSION['upload_error'] = 'Terjadi kesalahan saat upload file';
    header("Location: data_pembayaran.php");
    exit();
}

$ext = strtolower(pathinfo($nama, PATHINFO_EXTENSION));
$allow_ext = ['jpg','jpeg','png','pdf'];

if (!in_array($ext, $allow_ext)) {
    $_SESSION['upload_error'] = 'Format file tidak didukung (JPG, PNG, PDF)';
    header("Location: data_pembayaran.php");
    exit();
}

$mime_map = [
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
    'pdf'  => 'application/pdf'
];

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime  = finfo_file($finfo, $tmp);
finfo_close($finfo);

if ($mime !== $mime_map[$ext]) {
    $_SESSION['upload_error'] = 'File tidak valid atau berbahaya';
    header("Location: data_pembayaran.php");
    exit();
}

if ($size > 2 * 1024 * 1024) {
    $_SESSION['upload_error'] = 'Ukuran file maksimal 2MB';
    header("Location: data_pembayaran.php");
    exit();
}

$folder = "uploads/bukti_bayar/";
if (!is_dir($folder)) {
    mkdir($folder, 0755, true);
}

$nama_file = $nik . "_" . time() . "." . $ext;
$path = $folder . $nama_file;

if (!move_uploaded_file($tmp, $path)) {
    $_SESSION['upload_error'] = 'Gagal menyimpan file';
    header("Location: data_pembayaran.php");
    exit();
}

mysqli_query($koneksi, "
    INSERT INTO riwayat_pembayaran
    (nik, id_pembayaran, tanggal_bayar, nominal_bayar, bukti_bayar, status_pembayaran)
    VALUES
    ('$nik', '$id_pembayaran', '$tanggal', '$nominal', '$nama_file', 'menunggu')
");

$_SESSION['upload_success'] = 'Bukti pembayaran berhasil diupload';
header("Location: data_pembayaran.php");
exit();
