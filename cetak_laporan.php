<?php
require_once('tcpdf/tcpdf.php');
include 'koneksi.php';

// ================= AMBIL FILTER ================= //
$jenis   = $_POST['jenis'] ?? 'Semua';
$tanggal = $_POST['tanggal'] ?? '';
$bulan   = $_POST['bulan'] ?? '';
$tahun   = $_POST['tahun'] ?? '';

// ================= QUERY ================= //
$query = "
    SELECT 
        rp.tanggal_bayar AS tanggal,
        p.jenis_transaksi,
        p.nama_transaksi,
        rp.nominal_bayar AS nominal
    FROM riwayat_pembayaran rp
    JOIN pembayaran p ON rp.id_pembayaran = p.id_pembayaran
    WHERE rp.status_pembayaran = 'disetujui'
";

// Filter jenis
if ($jenis === "Uang Masuk") {
    $query .= " AND p.jenis_transaksi = 'Tagihan'";
} elseif ($jenis === "Uang Keluar") {
    $query .= " AND p.jenis_transaksi = 'Pengeluaran'";
}

// Filter tanggal / bulan / tahun
if ($tanggal) {
    $query .= " AND DAY(rp.tanggal_bayar) = " . (int)$tanggal;
}
if ($bulan) {
    $query .= " AND MONTH(rp.tanggal_bayar) = " . (int)$bulan;
}
if ($tahun) {
    $query .= " AND YEAR(rp.tanggal_bayar) = " . (int)$tahun;
}

$query .= " ORDER BY rp.tanggal_bayar ASC";
$result = mysqli_query($koneksi, $query);

// ================= TCPDF ================= //
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('UnityCash');
$pdf->SetAuthor('UnityCash');
$pdf->SetTitle('Laporan Keuangan');
$pdf->SetMargins(15, 20, 15);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();

// ================= HEADER PDF ================= //
$periodeText = "Semua Periode";
if ($tanggal || $bulan || $tahun) {
    $periodeText = trim(
        ($tanggal ? "Tanggal $tanggal " : "") .
        ($bulan ? "Bulan $bulan " : "") .
        ($tahun ? "Tahun $tahun" : "")
    );
}

$html = "
<h2 style='text-align:center;'>LAPORAN KEUANGAN RT/RW</h2>
<hr>
<table cellpadding='4'>
    <tr><td><b>Jenis Transaksi:</b> $jenis</td></tr>
    <tr><td><b>Periode:</b> $periodeText</td></tr>
</table>
<br><br>
";

// ================= TABEL ================= //
$html .= "
<table border='1' cellpadding='5' cellspacing='0'>
    <tr style='background-color:#eeeeee;'>
        <th width='25%'><b>Tanggal</b></th>
        <th width='20%'><b>Jenis</b></th>
        <th width='35%'><b>Nama Transaksi</b></th>
        <th width='20%'><b>Nominal</b></th>
    </tr>
";

$total_masuk = 0;
$total_keluar = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $jenisText = ($row['jenis_transaksi'] == 'Tagihan') ? 'Uang Masuk' : 'Uang Keluar';

    if ($row['jenis_transaksi'] == 'Tagihan') {
        $total_masuk += $row['nominal'];
    } else {
        $total_keluar += $row['nominal'];
    }

    $html .= "
    <tr>
        <td>".date('d-m-Y', strtotime($row['tanggal']))."</td>
        <td>$jenisText</td>
        <td>{$row['nama_transaksi']}</td>
        <td>Rp ".number_format($row['nominal'],0,',','.')."</td>
    </tr>
    ";
}

$html .= "</table><br><br>";

// ================= RINGKASAN ================= //
$saldo = $total_masuk - $total_keluar;

$html .= "
<h3>Ringkasan</h3>
<table cellpadding='5'>
    <tr><td><b>Total Uang Masuk</b></td><td>Rp ".number_format($total_masuk,0,',','.')."</td></tr>
    <tr><td><b>Total Uang Keluar</b></td><td>Rp ".number_format($total_keluar,0,',','.')."</td></tr>
    <tr><td><b>Saldo Akhir</b></td><td><b>Rp ".number_format($saldo,0,',','.')."</b></td></tr>
</table>
";

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('laporan_keuangan.pdf', 'I');
?>
