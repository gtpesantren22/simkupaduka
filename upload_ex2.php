<?php
include 'koneksi.php';
include 'main/foot.php';
// require 'main/vendors/PHPExcel/Classes/PHPExcel.php';
require 'main/libs/vendor/autoload.php';
require_once 'main/excel_reader2.php';

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

$target = basename($_FILES['file']['name']);
move_uploaded_file($_FILES['file']['tmp_name'], $target);

chmod($_FILES['file']['name'], 07777);

$data = new Spreadsheet_Excel_Reader($_FILES['file']['name'], false);

$jumbar = $data->rowcount($sheet_index = 0);

$success = 0;

mysqli_query($conn, "TRUNCATE TABLE cost");

for ($i = 2; $i <= $jumbar; $i++) {

    // $id = Uuid::uuid4()->toString();
    $cost_id = $data->val($i, 1);
    $cost_name = mysqli_real_escape_string($conn, $data->val($i, 2));
    $group_id = $data->val($i, 3);
    $group_name = mysqli_real_escape_string($conn, $data->val($i, 4));
    $bill_id = $data->val($i, 5);
    $bill_name = $data->val($i, 6);
    $nominal = $data->val($i, 7);

    mysqli_query($conn, "INSERT INTO cost VALUES ('', '$cost_id', '$cost_name', '$group_id','$group_name','$bill_id','$bill_name','$nominal')");

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <a href="cost.php">
        <<- kembali</a>
</body>

</html>