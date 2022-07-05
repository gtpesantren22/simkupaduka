<?php

include 'fungsi.php';
include 'bawah.php';

if (isset($_POST['delkos'])) {

    $kode = $_POST['lm'];
    $sql = mysqli_query($conn, "DELETE FROM rab WHERE lembaga = '$kode' AND tahun = '$tahun_ajaran' ");
    $sql2 = mysqli_query($conn, "DELETE FROM realis WHERE lembaga = '$kode' AND tahun = '$tahun_ajaran' ");

    if ($sql && $sql2) { ?>
        <script>
            Swal.fire({
                title: 'Berhasil',
                text: 'RAB dan Realisasinya sudah dikosongi',
                icon: 'success',
                showConfirmButton: false
            });
            var millisecondsToWait = 2000;
            setTimeout(function() {
                document.location.href = "rab.php"
            }, millisecondsToWait);
        </script>
<?php }
}
