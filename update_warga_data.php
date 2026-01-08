<?php
include 'koneksi.php';

$nik = $_POST['nik'];
$username = $_POST['username'];
$password = $_POST['password'] ?? '';

// username
mysqli_query($koneksi, "UPDATE warga SET username='$username' WHERE nik='$nik'");

// password
if (!empty($password)) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    mysqli_query($koneksi, "UPDATE warga SET password='$hash' WHERE nik='$nik'");
}

// foto
if (!empty($_FILES['foto']['name'])) {
    $namaFile = time() . '_' . $_FILES['foto']['name'];
    move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $namaFile);
    mysqli_query($koneksi, "UPDATE warga SET foto='$namaFile' WHERE nik='$nik'");
}

header("Location: profile_warga.php");
