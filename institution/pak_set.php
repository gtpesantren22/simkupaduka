<?php

require_once '../koneksi.php';
$kd = $_GET['kd'];
$id = $_GET['id'];
$pak = $_GET['pak'];

$dt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM rab WHERE id_rab = '$id' "));

if ($kd === 'del') {
    $kd_pak = $pak;
    $kd_rab = $dt['kode'];
    $qty = $dt['qty'];
    $satuan = $dt['satuan'];
    $harga_satuan = $dt['harga_satuan'];
    $total = $dt['harga_satuan'] * $dt['qty'];
    $ket = 'hapus';
    $tahun = $dt['tahun'];
    $cek = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pak_detail WHERE kode_rab = '$kd_rab' "));
    if ($cek > 0) {
        echo "
        <script>
            alert('Maaf. item RAB ini sudah dipakai PAK');
            window.location = 'pak_detail.php?kode=" . $pak . "';
        </script>
        ";
    } else {
        $sql = mysqli_query($conn, "INSERT INTO pak_detail VALUES ('', '$kd_pak', '$kd_rab', '$qty', '$satuan', '$harga_satuan', '$total', '$ket', '$tahun', 'belum')");
        if ($sql) {
            echo "
        <script>
            window.location = 'pak_detail.php?kode=" . $pak . "';
        </script>
        ";
        }
    }
}

if ($kd === 'kembali') {
    $sql = mysqli_query($conn, "DELETE FROM pak_detail WHERE kode_rab = '$id' ");
    if ($sql) {
        echo "
        <script>
            window.location = 'pak_detail.php?kode=" . $pak . "';
        </script>
        ";
    }
}

if ($kd === 'rab') {
    $sql = mysqli_query($conn, "DELETE FROM rab_sm WHERE kode = '$id' ");
    if ($sql) {
        echo "
        <script>
            window.location = 'pak_detail.php?kode=" . $pak . "';
        </script>
        ";
    }
}
