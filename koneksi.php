<?php
$conn = mysqli_connect("localhost", "root", "", "db_sentral");
$conn_dekos = mysqli_connect("localhost", "root", "", "db_dekos");
$conn_sekretaris = mysqli_connect("localhost", "root", "", "db_sekretaris");
$conn_santri = mysqli_connect("localhost", "root", "", "db_santri");

// $conn = mysqli_connect("localhost", "u9048253_dwk", "PesantrenDWKIT2021", "u9048253_sentral");
// $conn_dekos = mysqli_connect("localhost", "u9048253_dwk", "PesantrenDWKIT2021", "u9048253_dekos");
// $conn_sekretaris  = mysqli_connect("localhost", "u9048253_dwk", "PesantrenDWKIT2021", "u9048253_sekretaris ");
// $conn_santri  = mysqli_connect("localhost", "u9048253_dwk", "PesantrenDWKIT2021", "u9048253_santri ");

$key = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM api WHERE nama = 'Bendahara' "));
$api_key = $key['nama_key'];

function rupiah($angka)
{
    $hasil_rupiah = "Rp. " . number_format($angka, 0, ',', '.');
    return $hasil_rupiah;
}
function rupiah2($angka)
{
    $hasil_rupiah = number_format($angka, 0, ',', '.');
    return $hasil_rupiah;
}

$bulan = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "July", "Agustus", "September", "Oktober", "November", "Desember");

$tahun_ajaran = '2022/2023';
