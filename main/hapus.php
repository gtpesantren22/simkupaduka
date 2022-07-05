<?php
include '../koneksi.php';
include 'foot.php';

$kd = $_GET['kd'];
$id = $_GET['id'];

if ($kd == 'bos') {
    $sql = mysqli_query($conn, "DELETE FROM bos WHERE id_bos = '$id' AND tahun = '$tahun_ajaran' ");
    if ($sql) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Data BOS telah dihapus',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "masuk.php"
                }, millisecondsToWait);

            });
        </script>
    <?php }
}

if ($kd == 'pes') {
    $sql = mysqli_query($conn, "DELETE FROM pesantren WHERE id_pes = '$id' AND tahun = '$tahun_ajaran' ");
    if ($sql) { ?>
        <script>
            $(document).ready(function() {
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

            });
        </script>
    <?php }
}

if ($kd == 'rab') {
    $kode = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM rab WHERE id_rab = '$id' AND tahun = '$tahun_ajaran' "));
    $kd = $kode['kode'];
    $lm = $kode['lembaga'];
    $sql = mysqli_query($conn, "DELETE FROM rab WHERE id_rab = '$id' AND tahun = '$tahun_ajaran' ");
    $sql2 = mysqli_query($conn, "DELETE FROM realis WHERE kode = '$kd' AND tahun = '$tahun_ajaran' ");
    if ($sql && $sql2) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Data RAB pesantren telah dihapus',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "<?= 'rab_detail.php?kode=' . $lm ?>"
                }, millisecondsToWait);

            });
        </script>
    <?php }
}

if ($kd == 'del_real') {
    $kode = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM realis WHERE id_realis = '$id' AND tahun = '$tahun_ajaran' "));
    $sql = mysqli_query($conn, "DELETE FROM realis WHERE id_realis = '$id' AND tahun = '$tahun_ajaran' ");
    if ($sql) { ?>
        <script>
            $(document).ready(function() {
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

            });
        </script>
    <?php }
}

if ($kd == 'sisa') {
    $sql = mysqli_query($conn, "DELETE FROM real_sisa WHERE id_sisa = '$id' AND tahun = '$tahun_ajaran' ");
    if ($sql) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Data Sisa Belanja telah dihapus',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "<?= 'masuk.php' ?>"
                }, millisecondsToWait);

            });
        </script>
    <?php }
}

if ($kd == 'aju') {
    $sql = mysqli_query($conn, "DELETE FROM pengajuan WHERE kode_pengajuan = '$id' AND tahun = '$tahun_ajaran' ");
    $sql2 = mysqli_query($conn, "DELETE FROM real_sm WHERE kode_pengajuan = '$id' AND tahun = '$tahun_ajaran' ");

    if ($sql && $sql2) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Pengajuan sudah dihapus',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "<?= 'pengajuan.php' ?>"
                }, millisecondsToWait);

            });
        </script>
    <?php }
}

if ($kd == 'dsp') {
    $sql = mysqli_query($conn, "DELETE FROM disposisi WHERE id_disp = '$id' AND tahun = '$tahun_ajaran' ");

    if ($sql) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Disposisi sudah dihapus',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "<?= 'dispo.php' ?>"
                }, millisecondsToWait);

            });
        </script>
    <?php }
}

if ($kd == 'dspsp') {
    $sql = mysqli_query($conn, "DELETE FROM disposisi_sisa WHERE id_disp_sisa = '$id' AND tahun = '$tahun_ajaran' ");

    if ($sql) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Disposisi sudah dihapus',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "<?= 'dispo_sisa.php' ?>"
                }, millisecondsToWait);

            });
        </script>
    <?php }
}

if ($kd == 'kbj') {
    $sql = mysqli_query($conn, "DELETE FROM kebijakan WHERE id_kebijakan = '$id' ");

    if ($sql) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Disposisi sudah dihapus',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "<?= 'rab_kbj.php' ?>"
                }, millisecondsToWait);

            });
        </script>
<?php }
}
