<script src="dist/sweetalert2.all.min.js"></script>
<?php
include '../koneksi.php';
require 'vendors/PHPExcel/Classes/PHPExcel.php';
require 'libs/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
//uuid1
//$uuid = Uuid::uuid4()->toString();

if (isset($_POST['upload'])) {
    $file = $_FILES['file']['name'];
    $ekstensi = explode('.', $file);
    $file_name = 'file-' . round(microtime(true)) . '.' . end($ekstensi);
    $sumber = $_FILES['file']['tmp_name'];
    $target_dir = 'file_rab/';
    $target_file = $target_dir . $file_name;
    move_uploaded_file($sumber, $target_file);

    // $obj = PHPExcel_IOFactory::load($target_file);
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($target_file);
    $all_data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    //echo $all_data[5]['H'];
    $sql = "INSERT INTO rab VALUES ";
    for ($i = 5; $i < count($all_data); $i++) {
        $id = Uuid::uuid4()->toString();
        $lembaga = $all_data[$i]['B'];
        $bidang = $all_data[$i]['C'];
        $jenis = $all_data[$i]['D'];
        $kode = $lembaga . '.' . $bidang . '.' . $jenis . '.' . rand();
        $nama = $all_data[$i]['E'];
        $rencana = $all_data[$i]['F'];
        $qty = $all_data[$i]['G'];
        $satuan = $all_data[$i]['H'];
        $harga_satuan = $all_data[$i]['I'];
        $total = $qty * $harga_satuan;
        $tahun = $all_data[$i]['J'];
        $upload = date('d-m-Y H:i:s');
        $sql .= "('$id', '$lembaga', '$bidang', '$jenis','$kode','$nama','$rencana','$qty','$satuan','$harga_satuan','$total','$tahun', '$upload'),";
    }
    $sql = substr($sql, 0, -1);
    // echo $sql;
    $qr = mysqli_query($conn, $sql);
    echo "
    <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'File RAB berhasil diupload',
                showConfirmButton: false
            });
            var millisecondsToWait = 1000;
            setTimeout(function() {
                document.location.href = 'rab.php'
            }, millisecondsToWait);
        </script>
    ";
?>


<?php
}
