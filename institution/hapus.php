<?php
include 'fungsi.php';
include 'bawah.php';

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
    $kode = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM realis WHERE id_realis = '$id' AND tahun = '$tahun_ajaran'"));
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

if ($kd == 'del_real_sm') {
    $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM real_sm WHERE id_realis = '$id' AND tahun = '$tahun_ajaran' "));
    $kode = $data['kode_pengajuan'];
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
                document.location.href = "<?= 'pengajuan_add.php?kode=' . $kode ?>"
            }, millisecondsToWait);
        </script>
    <?php }
}

if ($kd == 'del_real_sm_disp') {
    $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM real_sm WHERE id_realis = '$id' AND tahun = '$tahun_ajaran' "));
    $kode = $data['kode_pengajuan'];
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
                document.location.href = "<?= 'pengajuan_add_disp.php?kode=' . $kode ?>"
            }, millisecondsToWait);
        </script>
<?php }
}
