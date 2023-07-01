<?php
$output = '<option value="">--pilih jenis belanja--</option>';
foreach ($query as $row) {
    if ($row->jenis == 'A') {
        $jn = 'A. Belanja Barang';
    } else if ($row->jenis == 'B') {
        $jn = 'B. Barang dan Jasa';
    } else if ($row->jenis == 'C') {
        $jn = 'C. Belanja Kegiatan';
    } else if ($row->jenis == 'D') {
        $jn = 'D. Umum';
    }

    $output .= '<option value="' . $row->jenis . '">' . $jn . '</option>';
}
echo $output;