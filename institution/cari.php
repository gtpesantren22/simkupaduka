<?php
include 'fungsi.php';

$judul = strip_tags($_GET['judul']);
if ($judul == "")
  echo "Masukkan judul arikel";
else {
  $query = "SELECT * FROM rab where kode = '$judul'";
  $result = $conn->query($query) or die($conn->error . __LINE__);
  if ($result->num_rows > 0) {
    while ($rows = $result->fetch_assoc()) {
      extract($rows);
      $kd = $rows['kode'];
      $pakai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as nom, SUM(vol) as vol FROM realis WHERE kode = '$kd' "));
      $sisa_jml = $rows['qty'] - $pakai['vol'];
      $sisa = $rows['total'] - $pakai['nom'];
      echo "
  <table class='table table-striped'>    
  <tr>
		<th>Volume</th>
		<th style='color: red;'>Sisa Volume : " . $sisa_jml . ' ' . $rows['satuan'] . "</th>
		<td><input style='text-align: center;' type='text' class='form-control' id='qty' name='qty' onkeyup='sum();' required autofucus='on'></td>
    </tr>
    <tr>
		<th>Harga Satuan</th>
		<th style='color: red;'>" . rupiah($rows['harga_satuan']) . ' x ' . $rows['qty'] . "</th>
		<td><input style='text-align: right;' type='text' class='form-control' value='" . rupiah($sisa) . "' readonly></td>
    <input type='hidden' name='harga_satuan' id='harga_satuan' value='" . $rows['harga_satuan'] . "' onkeyup='sum();'>
    <input type='hidden' name='sisa_jml' value='" . $sisa_jml . "' >
	</tr>
	<tr>
		<th>Jumlah Belanja </th>
		<td colspan='2'><input style='text-align: right;' type='text' class='form-control' id='total' name='nominal' readonly></td>
	</tr>
  </table>
  ";
    }
  } else {
    $hasil = " <h4 style='color:red'>Artikel tidak ditemukan!</h4>";
  }
}
?>
<!-- jQuery 2.1.4 -->
<script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script>
  function sum() {
    var txtFirstNumberValue = document.getElementById('qty').value;
    var txtSecondNumberValue = document.getElementById('harga_satuan').value;
    var result = parseInt(txtFirstNumberValue) * parseInt(txtSecondNumberValue);

    var number_string = result.toString(),
      sisa = number_string.length % 3,
      rupiah = number_string.substr(0, sisa),
      ribuan = number_string.substr(sisa).match(/\d{3}/g);

    if (ribuan) {
      rp = 'Rp. '
      separator = sisa ? '.' : '';
      rupiah += separator + ribuan.join('.');
    }

    if (!isNaN(result)) {
      document.getElementById('total').value = rupiah;
    }
  }
</script>