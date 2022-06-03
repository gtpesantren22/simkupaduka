<?php
include '../koneksi.php';
$kl = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM spj WHERE kode_pengajuan = '01.0505.1.2022' "));

//memanggil file example.pdf yang berada di folder bernama file
$filePath = '../institution/spj_file/' . $kl['file_spj'];
//Membuat kondisi jika file tidak ada
if (!file_exists($filePath)) {
    echo "The file $filePath does not exist";
    die();
}
//nama file untuk tampilan
$filename = $kl['file_spj'];
header('Content-type:application/pdf');
header('Content-disposition: inline; filename="' . $filename . '"');
header('content-Transfer-Encoding:binary');
header('Accept-Ranges:bytes');
//membaca dan menampilkan file
readfile($filePath);
