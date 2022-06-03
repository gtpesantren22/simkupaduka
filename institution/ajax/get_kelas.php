<?php
include '../fungsi.php';
$id_rab = $_POST['id_rab'];

echo "<option value=''>Pilih Kelas</option>";

$query = "SELECT total FROM rab WHERE kode=? ";
$dewan1 = $conn->prepare($query);
$dewan1->bind_param("s", $id_rab);
$dewan1->execute();
$res1 = $dewan1->get_result();
while ($row = $res1->fetch_assoc()) {
	echo "
	<tr>
		<th>Nominal</th>
		<td><input type='text' class='form-control' value=' " . $row['total'] . "' disabled></td>
	</tr>
	<tr>
		<th>Pemakaian</th>
		<td><input type='text' class='form-control' disabled></td>
	</tr>
	<tr>
		<th>Sisa</th>
		<td><input type='text' class='form-control' disabled></td>
	</tr>
	";
} ?>
<!-- OK -->