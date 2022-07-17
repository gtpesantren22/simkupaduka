<?php
include '../koneksi.php';
include 'foot.php';
//require 'vendors/PHPExcel/Classes/PHPExcel.php';
require 'libs/vendor/autoload.php';
require_once 'excel_reader2.php';

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

// $tahun_ajaran = $_SESSION['tahun'];

$target = basename($_FILES['file']['name']);
move_uploaded_file($_FILES['file']['tmp_name'], $target);

chmod($_FILES['file']['name'], 07777);

$data = new Spreadsheet_Excel_Reader($_FILES['file']['name'], false);

$jumbar = $data->rowcount($sheet_index = 0);

$success = 0;

for ($i = 5; $i <= $jumbar; $i++) {

    $id = Uuid::uuid4()->toString();
    $lembaga = $data->val($i, 2);
    $bidang = $data->val($i, 3);
    $jenis = $data->val($i, 4);
    $kode = $lembaga . '.' . $bidang . '.' . $jenis . '.' . rand();
    $nama = mysqli_real_escape_string($conn, $data->val($i, 5));
    $rencana = $data->val($i, 6);
    $qty = $data->val($i, 7);
    $satuan = $data->val($i, 8);
    $harga_satuan = $data->val($i, 9);
    $total = (int)$qty * (int)$harga_satuan;
    $tahun = $data->val($i, 10);
    $upload = date('d-m-Y H:i:s');

    mysqli_query($conn, "INSERT INTO rab VALUES ('$id', '$lembaga', '$bidang', '$jenis','$kode','$nama','$rencana','$qty','$satuan','$harga_satuan','$total','$tahun', '$upload')");

    $success++;
}

unlink($_FILES['file']['name']);

if ($success > 0) {
?>
    <script>
        $(document).ready(function() {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'File RAB berhasil diupload',
                showConfirmButton: false
            });
            var millisecondsToWait = 1000;
            setTimeout(function() {
                document.location.href = "rab.php"
            }, millisecondsToWait);

        });
    </script>
<?php
}
