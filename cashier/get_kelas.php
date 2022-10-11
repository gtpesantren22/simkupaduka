<?php
include '../koneksi.php';
$t_formal = $_POST['t_formal'];

echo "<option value=''>Pilih Kelas</option>";

$query = "SELECT nm_kelas FROM kl_formal WHERE lembaga=? ";
$dewan1 = $conn_santri->prepare($query);
$dewan1->bind_param("s", $t_formal);
$dewan1->execute();
$res1 = $dewan1->get_result();
while ($row = $res1->fetch_assoc()) {
	echo "<option value='" . $row['nm_kelas'] . "'>" . $row['nm_kelas'] . "</option>";
}
?>
<!-- OK -->