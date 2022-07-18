<?php
include 'fungsi.php';
$kotaId = $_POST["kotaId"];
if ($kotaId !== "") {
    $query = mysqli_query($conn, "SELECT a.kode, a.nama, b.nama as nmb FROM rab a JOIN bidang b ON a.bidang=b.kode WHERE a.lembaga = '$kol' AND jenis = '$kotaId' AND a.tahun = '$tahun_ajaran' AND b.tahun = '$tahun_ajaran' ORDER BY a.nama ASC");
    $output = '<option value="">--Pilih RAB--</option>';
    while ($row = mysqli_fetch_array($query)) {
        $output .= '<option value="' . $row["kode"] . '">' . $row["nama"] . '</option>';
    }
} else {
    $output = '<option value="">--Tolong pilih data--</option>';
}
echo  $output;
