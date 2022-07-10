<?php
include 'fungsi.php';
include 'bawah.php';

$kd = $_GET['kd'];
$id = $_GET['id'];

if ($kd == 'bos') {
    $sql = mysqli_query($conn, "DELETE FROM bos WHERE id_bos = '$id' AND tahun = '$tahun_ajaran' ");
    if ($sql) { ?>
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Data BOS telah dihapus',
                showConfirmButton: false
            });
            var millisecondsToWait = 1000;
            setTimeout(function() {
                document.location.href = "bos.php"
            }, millisecondsToWait);
        </script>
    <?php }
}

if ($kd == 'pes') {
    $sql = mysqli_query($conn, "DELETE FROM pesantren WHERE id_pes = '$id' AND tahun = '$tahun_ajaran' ");
    if ($sql) { ?>
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Data pemasukan pesantren telah dihapus',
                showConfirmButton: false
            });
            var millisecondsToWait = 1000;
            setTimeout(function() {
                document.location.href = "masuk.php"
            }, millisecondsToWait);
        </script>
    <?php }
}

if ($kd == 'rab') {
    $kode = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM rab WHERE id_rab = '$id' AND tahun = '$tahun_ajaran' "));
    $kd = $kode['kode'];
    $sql = mysqli_query($conn, "DELETE FROM rab WHERE id_rab = '$id' AND tahun = '$tahun_ajaran' ");
    $sql2 = mysqli_query($conn, "DELETE FROM realis WHERE kode = '$kd' AND tahun = '$tahun_ajaran' ");
    if ($sql && $sql2) { ?>
        <script>
            Swal.fire({
                title: 'Berhasil',
                text: 'RAB dan Realisasinya berhasil dihaous',
                icon: 'success',
                showConfirmButton: false
            });
            var millisecondsToWait = 1000;
            setTimeout(function() {
                document.location.href = "rab.php"
            }, millisecondsToWait);
        </script>
    <?php }
}

if ($kd == 'del_real') {
    $kode = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM realis WHERE id_realis = '$id' AND tahun = '$tahun_ajaran' "));
    $sql = mysqli_query($conn, "DELETE FROM realis WHERE id_realis = '$id' AND tahun = '$tahun_ajaran' ");
    if ($sql) { ?>
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Data Belanja telah dihapus',
                showConfirmButton: false
            });
            var millisecondsToWait = 1000;
            setTimeout(function() {
                document.location.href = "<?= 'real_add.php?kode=' . $kode['kode'] ?>"
            }, millisecondsToWait);
        </script>
    <?php }
}

if ($kd == 'del_real_sm') {
    $sql = mysqli_query($conn, "DELETE FROM real_sm WHERE id_realis = '$id' AND tahun = '$tahun_ajaran' ");
    if ($sql) { ?>
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Data Belanja telah dihapus',
                showConfirmButton: false
            });
            var millisecondsToWait = 1000;
            setTimeout(function() {
                document.location.href = "<?= 'pengajuan_add.php' ?>"
            }, millisecondsToWait);
        </script>
    <?php }
}

if ($kd == 'del_tg') {
    $sql = mysqli_query($conn, "DELETE FROM tanggungan WHERE id_tanggungan = '$id' AND tahun = '$tahun_ajaran' ");
    if ($sql) { ?>
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Data Tanggungan telah dihapus',
                showConfirmButton: false
            });
            var millisecondsToWait = 1000;
            setTimeout(function() {
                document.location.href = "<?= 'tanggungan.php' ?>"
            }, millisecondsToWait);
        </script>
    <?php }
}

if ($kd == 'del_by') {
    $dtby = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pembayaran WHERE id = '$id' "));
    $nis = $dtby['nis'];
    $buln = $dtby['bulan'];
    $tahun = $dtby['tahun'];

    $sql = mysqli_query($conn, "DELETE FROM pembayaran WHERE id = '$id' AND tahun = '$tahun_ajaran' ");
    $sql2 = mysqli_query($conn_dekos, "DELETE FROM kos WHERE nis = '$nis' AND bulan = '$buln' AND tahun = '$tahun' ");
    if ($sql && $sql2) { ?>
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Data Pembayaran telah dihapus',
                showConfirmButton: false
            });
            var millisecondsToWait = 1000;
            setTimeout(function() {
                document.location.href = "<?= 'tg_syh2.php?nis=' . $nis ?>"
            }, millisecondsToWait);
        </script>
<?php }
}
