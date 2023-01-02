<?php
include 'atas.php';

// AND kode_pengajuan NOT LIKE '%DISP.%'

$ss = mysqli_query($conn, "SELECT * FROM pak WHERE lembaga = '$kol' AND tahun = '$tahun_ajaran' AND status != 'selesai' ");
$ck = mysqli_fetch_assoc($ss);
$ck2 = mysqli_num_rows($ss);
$lm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$kol' AND tahun = '$tahun_ajaran' "));
$tgl = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM akses WHERE lembaga = 'umum' "));
$skr = date('Y-m-d');
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            PAK
            <small>Perubahan Anggaran Belanja</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">PAK</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <?php if ($skr >= $tgl['login'] && $skr <= $tgl['disposisi']) { ?>
                    <div class="box-header">
                        <h3 class="box-title label label-success">
                            <?= date('d F Y', strtotime($tgl['login'])) . ' - ' . date('d F Y', strtotime($tgl['disposisi'])); ?>
                        </h3>
                        <?php if ($ck2 < 1) { ?>
                        <button class="btn btn-success pull-right" data-toggle="modal" data-target="#staticBackdrop"><i
                                class="fa fa-plus-square"></i> Daftar PAK Baru</button>
                        <?php } ?>
                    </div><!-- /.box-header -->
                    <div class="box-body">

                        <div class="table-responsive">
                            <table id="example1_bst" class="table table-bordered table-striped">
                                <thead>
                                    <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                        <th>No</th>
                                        <th>Kode PAK</th>
                                        <th>Tanggal PAK</th>
                                        <th>Status</th>
                                        <th>Tahun</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $rls = mysqli_query($conn, "SELECT * FROM pak WHERE lembaga = '$kol' AND tahun = '$tahun_ajaran'  ");
                                        while ($ls_jns = mysqli_fetch_assoc($rls)) {
                                        ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $ls_jns['kode_pak']; ?></td>
                                        <td><?= $ls_jns['tgl_pak']; ?></td>
                                        <td><?= $ls_jns['status']; ?></td>
                                        <td><?= $ls_jns['tahun']; ?></td>
                                        <td><a href="<?= 'pak_detail.php?kode=' . $ls_jns['kode_pak'] ?>"><button
                                                    class="btn btn-info btn-xs"><i class="fa fa-search"></i>
                                                    Edit PAK</button></a></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div><!-- /.box-body -->
                    <?php } else { ?>
                    <div class="box-header">
                        <center>
                            <h3 class="box-title label label-danger">
                                Belum ada Jadwal PAK Aktif
                            </h3>
                        </center>
                    </div><!-- /.box-header -->
                    <?php } ?>
                </div><!-- /.box -->
            </div>
        </div>
    </section><!-- /.content -->
</div>

<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class=" modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Daftar PAK Baru</h4>
            </div>
            <form action="" method="post" class="form-horizontal">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Lembaga *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="<?= $lm['nama'] ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Tanggal PAK *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="tgl_pak"
                                value="<?= date('d F Y', strtotime($tgl['login'])) . ' s/d ' . date('d F Y', strtotime($tgl['disposisi'])); ?> "
                                readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Tahun *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="tahun" value="<?= $tahun_ajaran ?> " readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" name="save" class="btn btn-success">Daftar</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- DataTables -->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<!-- jQuery 2.1.4 -->
<script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
$(function() {
    $("#example1_bst").DataTable();
    $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });
});
</script>
<?php
include 'bawah.php';
?>

<?php
if (isset($_POST['save'])) {

    $kode = 'PAK.' . $kol . '.' . rand(0, 99999);
    $lembaga = $kol;
    $tgl_pak = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tgl_pak']));
    $tahun = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tahun']));

    $sql = mysqli_query($conn, "INSERT INTO pak VALUES('', '$kode', '$lembaga', '$tgl_pak', 'belum', '$tahun') ");
    if ($sql) {

?>
<script>
Swal.fire({
    position: 'top-end',
    icon: 'success',
    title: 'PAK berhasil didaftarkan',
    showConfirmButton: false
});
var millisecondsToWait = 1000;
setTimeout(function() {
    document.location.href = " <?= 'pak.php' ?>"
}, millisecondsToWait);
</script>
<?php

    }
}

?>