<?php

if ($kotaId !== "") {
    $query = $this->db->query("SELECT a.kode, a.nama, b.nama as nmb FROM rab a JOIN bidang b ON a.bidang=b.kode WHERE a.lembaga = '$kol' AND jenis = '$kotaId' AND a.tahun = '$tahun' AND b.tahun = '$tahun' ORDER BY a.nama ASC")->result();
    $output = '<option value="">--Pilih RAB--</option>';
    foreach ($query as $row) {
        $output .= '<option value="' . $row->kode . '">' . $row->nama . '</option>';
    }
} else {
    $output = '<option value="">--Tolong pilih data--</option>';
}
echo  $output;