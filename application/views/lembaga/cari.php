<?php


if ($judul == "")
    echo "Masukkan judul arikel";
else {
    $query = "SELECT * FROM rab where kode = '$judul'";
    $result = $this->db->query($query);
    if ($result->num_rows() > 0) {
        foreach ($result->result() as $rows) {
            // extract($rows);
            $kd = $rows->kode;
            $pakai = $this->db->query("SELECT SUM(nominal) as nom, SUM(vol) as vol FROM realis WHERE kode = '$kd' AND tahun = '$tahun' ")->row();
            $pakai2 = $this->db->query("SELECT SUM(nominal) as nom, SUM(vol) as vol FROM real_sm WHERE kode = '$kd' AND tahun = '$tahun' ")->row();
            $sisa_jml = $rows->qty - $pakai->vol - $pakai2->vol;
            $sisa = $rows->total - $pakai->nom - $pakai2->nom;
            echo "
  <table class='table table-striped'>    
  <tr>
		<th>Volume</th>
		<th style='color: red;'>Sisa Volume : " . $sisa_jml . ' ' . $rows->satuan . "</th>
		<td><input style='text-align: center;' type='text' class='form-control' id='qty' name='qty' onkeyup='sum();' required autofucus='on'></td>
    </tr>
    <tr>
		<th>Harga Satuan</th>
		<th style='color: red;'>" . rupiah($rows->harga_satuan) . ' x ' . $sisa_jml . "</th>
		<td><input style='text-align: right;' type='text' class='form-control' value='" . rupiah($sisa) . "' readonly></td>
    <input type='hidden' name='harga_satuan' id='harga_satuan' value='" . $rows->harga_satuan . "' onkeyup='sum();'>
    <input type='hidden' name='sisa_jml' value='" . $sisa_jml . "' >
	</tr>
  </table>
  ";
        }
    } else {
        $hasil = " <h4 style='color:red'>Artikel tidak ditemukan!</h4>";
    }
}