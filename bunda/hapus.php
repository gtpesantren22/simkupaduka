<?php
include 'fungsi.php';
include 'foot.php';

$kd = $_GET['kd'];
$id = $_GET['id'];

if ($kd = 'in') {
    $link = "masuk.php";
} else {
    $link = "keluar.php";
}


$sql = mysqli_query($conn, "DELETE FROM kas WHERE id_kas = '$id' ");
if ($sql) { ?>
    <script>
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'Data telah dihapus',
            showConfirmButton: false
        });
        var millisecondsToWait = 1000;
        setTimeout(function() {
            document.location.href = "<?= $link; ?>"
        }, millisecondsToWait);
    </script>
<?php }
