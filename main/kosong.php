<?php
include '../koneksi.php';
$tahun_ajaran = $_SESSION['tahun'];

$lembaga = $_GET['kode'];
$sql = mysqli_query($conn, "DELETE * FROM rab WHERE lembaga = '$lembaga' AND tahun = '$tahun_ajaran' ");
if ($sql) { ?>
    <script>
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'RAB sudah di kosongi',
            showConfirmButton: false
        });
        var millisecondsToWait = 1000;
        setTimeout(function() {
            document.location.href = "<?= 'rab_detail.php?kode=' . $lembaga ?>"
        }, millisecondsToWait);
    </script>
<?php }
?>