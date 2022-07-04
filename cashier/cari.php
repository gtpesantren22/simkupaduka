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
      $pakai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as nom FROM real_sm WHERE kode = '$kd' AND tahun = '$tahun_ajaran' "));
      $sisa = $rows['total'] - $pakai['nom'];
      echo "
  <table class='table table-striped'>    
  <tr>
		<th>Nominal</th>
		<td><input type='text' class='form-control' value=' " . rupiah($rows['total']) . "' disabled></td>
	</tr>
	<tr>
		<th>Pemakaian Sementara</th>
		<td><input type='text' class='form-control' value=' " . rupiah($pakai['nom']) . "' disabled></td>
	</tr>
	<tr>
		<th>Sisa Sementara</th>
		<td><input type='text' class='form-control' value=' " . rupiah($sisa) . "' disabled></td>
	</tr>
  </table>
  ";
    }
  } else {
    $hasil = " <h4 style='color:red'>Artikel tidak ditemukan!</h4>";
  }
}
