<?php
session_start();
include 'koneksi.php';

/* ===============================
   CEK LOGIN (PATOKAN PROFIL)
================================ */
if (!isset($_SESSION['username_pengurus'])) {
    header("Location: login_page.php");
    exit();
}

/* ===============================
   AMBIL NIK DARI FORM (FIX)
================================ */
$nik      = $_POST['nik_pengurus'];
$username = $_POST['username_pengurus'];
$password = $_POST['password'];

/* ===============================
   AMBIL FOTO LAMA
================================ */
$q = mysqli_query($koneksi, "
    SELECT foto_profil 
    FROM pengurus 
    WHERE nik_pengurus = '$nik'
");

if (mysqli_num_rows($q) == 0) {
    echo "<script>alert('Data pengurus tidak ditemukan');history.back();</script>";
    exit();
}

$data = mysqli_fetch_assoc($q);
$foto_lama = $data['foto_profil'] ?: 'profil/default.png';
$foto_baru = $foto_lama;

/* ===============================
   UPLOAD FOTO BARU
================================ */
if (!empty($_FILES['foto']['name'])) {

    $ext_valid = ['jpg','jpeg','png','webp'];
    $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $ext_valid)) {
        echo "<script>alert('Format foto tidak valid');history.back();</script>";
        exit();
    }

    if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
        echo "<script>alert('Ukuran foto maksimal 2MB');history.back();</script>";
        exit();
    }

    $nama_file = uniqid().'_'.$nik.'.'.$ext;
    $folder = 'uploads/profil/';

    move_uploaded_file($_FILES['foto']['tmp_name'], $folder.$nama_file);

    $foto_baru = 'profil/'.$nama_file;

    if ($foto_lama != 'profil/default.png' && file_exists('uploads/'.$foto_lama)) {
        unlink('uploads/'.$foto_lama);
    }
}

/* ===============================
   UPDATE DATA AKUN (FIX PASSWORD)
================================ */
if (!empty($password)) {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    mysqli_query($koneksi, "
        UPDATE pengurus SET
            username_pengurus = '$username',
            password_pengurus = '$password_hash',
            foto_profil = '$foto_baru'
        WHERE nik_pengurus = '$nik'
    ");
} else {
    mysqli_query($koneksi, "
        UPDATE pengurus SET
            username_pengurus = '$username',
            foto_profil = '$foto_baru'
        WHERE nik_pengurus = '$nik'
    ");
}

/* ===============================
   UPDATE SESSION
================================ */


echo "<script>
    alert('Data akun berhasil diperbarui');
    window.location='profil.php';
</script>";
