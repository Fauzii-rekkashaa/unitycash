<?php
include 'koneksi.php';
require_once('tcpdf/tcpdf.php');

$jenis   = $_GET['jenis'] ?? 'Semua';
$periode = $_GET['periode'] ?? 'Semua';

/* ===============================
   FILTER
================================ */
$where = "rp.status_pembayaran = 'disetujui'";

if ($jenis !== 'Semua') {
    if ($jenis === 'Uang Masuk') {
        $where .= " AND p.jenis_transaksi = 'Tagihan'";
    } else {
        $where .= " AND p.jenis_transaksi = 'Pengeluaran'";
    }
}

/* ===============================
   QUERY DATA
================================ */
$query = mysqli_query($conn, "
    SELECT 
        rp.tanggal_bayar,
        rp.nominal_bayar,
        p.jenis_transaksi,
        p.nama_transaksi,
        w.nama_warga
    FROM riwayat_pembayaran rp
    JOIN pembayaran p ON rp.id_pembayaran = p.id_pembayaran
    JOIN warga w ON rp.nik = w.nik
    WHERE $where
    ORDER BY rp.tanggal_bayar DESC
");

/* ===============================
   PDF SETUP
================================ */
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetTitle('Laporan Keuangan');
$pdf->SetMargins(15, 15, 15);
$pdf->AddPage();

/* ===============================
   HEADER
================================ */
$html = "
<h2 style='text-align:center;'>LAPORAN KEUANGAN RT</h2>
<p>
Jenis Transaksi : <b>$jenis</b><br>
Periode : <b>$periode</b>
</p>
<hr>

<table border='1' cellpadding='6'>
<thead>
<tr style='background-color:#f2f2f2;'>
<th width='8%'>No</th>
<th width='18%'>Tanggal</th>
<th width='22%'>Warga</th>
<th width='22%'>Transaksi</th>
<th width='15%'>Jenis</th>
<th width='15%'>Nominal</th>
</tr>
</thead>
<tbody>
";

$no = 1;
$total_masuk = 0;
$total_keluar = 0;

while ($row = mysqli_fetch_assoc($query)) {

    if ($row['jenis_transaksi'] === 'Tagihan') {
        $total_masuk += $row['nominal_bayar'];
    } else {
        $total_keluar += $row['nominal_bayar'];
    }

    $html .= "
    <tr>
        <td>$no</td>
        <td>{$row['tanggal_bayar']}</td>
        <td>{$row['nama_warga']}</td>
        <td>{$row['nama_transaksi']}</td>
        <td>{$row['jenis_transaksi']}</td>
        <td>Rp ".number_format($row['nominal_bayar'],0,',','.')."</td>
    </tr>
    ";
    $no++;
}

$saldo = $total_masuk - $total_keluar;

$html .= "
</tbody>
</table>

<br>
<table cellpadding='6'>
<tr>
<td width='40%'>Total Uang Masuk</td>
<td>: <b>Rp ".number_format($total_masuk,0,',','.')."</b></td>
</tr>
<tr>
<td>Total Uang Keluar</td>
<td>: <b>Rp ".number_format($total_keluar,0,',','.')."</b></td>
</tr>
<tr>
<td>Saldo Akhir</td>
<td>: <b>Rp ".number_format($saldo,0,',','.')."</b></td>
</tr>
</table>
";

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('laporan_keuangan.pdf', 'I');
