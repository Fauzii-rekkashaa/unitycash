<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['role'])) {
    header("Location: login_page.php");
    exit();
}

/* ===== TAMBAH ===== */
if (isset($_POST['aksi']) && $_POST['aksi'] === 'tambah') {
    mysqli_query($koneksi, "
        INSERT INTO pembayaran (nama_pembayaran, nominal_default, keterangan)
        VALUES (
            '$_POST[nama]',
            '$_POST[nominal]',
            '$_POST[keterangan]'
        )
    ");
    header("Location: data_kelola_uang.php");
    exit();
}

/* ===== EDIT ===== */
if (isset($_POST['aksi']) && $_POST['aksi'] === 'edit') {
    mysqli_query($koneksi, "
        UPDATE pembayaran SET
            nama_pembayaran='$_POST[nama]',
            nominal_default='$_POST[nominal]',
            keterangan='$_POST[keterangan]'
        WHERE id_pembayaran='$_POST[id]'
    ");
    header("Location: data_kelola_uang.php");
    exit();
}

/* ===== HAPUS ===== */
if (isset($_GET['hapus'])) {
    mysqli_query($koneksi, "
        DELETE FROM pembayaran
        WHERE id_pembayaran='$_GET[hapus]'
    ");
    header("Location: data_kelola_uang.php");
    exit();
}
