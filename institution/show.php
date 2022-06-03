<?php
include 'fungsi.php';
$query = mysqli_query($conn, "SELECT * FROM rab WHERE lembaga = '$kol' GROUP BY jenis");
$output = '<option value="">--pilih jenis belanja--</option>';
while ($row = mysqli_fetch_array($query)) {
    if ($row['jenis'] == 'A') {
        $jn = 'A. Belanja Barang';
    } else if ($row['jenis'] == 'B') {
        $jn = 'B. Barang dan Jasa';
    } else if ($row['jenis'] == 'C') {
        $jn = 'C. Belanja Kegiatan';
    } else if ($row['jenis'] == 'D') {
        $jn = 'D. Umum';
    }

    $output .= '<option value="' . $row["jenis"] . '">' . $jn . '</option>';
}
echo $output;
