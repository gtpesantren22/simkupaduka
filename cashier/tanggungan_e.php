<?php
include 'atas.php';
$nis = $_GET['nis'];
$sn = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tb_santri WHERE nis = '$nis' "));
$dt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tanggungan WHERE nis = '$nis' "));
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Edit Data Tanggungan Santri
            <small>Tanggungan Perbulan</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Pengajuan</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Data Tanggungan Santri</h3>
                        <a href="tanggungan.php">
                            <button class="btn btn-warning pull-right"> Kembali</button>
                        </a>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <div class="row">
                        <div class="col-md-12">
                            <form role="form" method="post">
                                <div class="box-body">
                                    <div class="form-group">
                                        <table class="table">
                                            <tr>
                                                <th>NIS</th>
                                                <th>: <?= $sn['nis']; ?></th>
                                            </tr>
                                            <tr>
                                                <th>Nama</th>
                                                <th>: <?= $sn['nama']; ?></th>
                                            </tr>
                                            <tr>
                                                <th>Alamat</th>
                                                <th>: <?= $sn['desa'] . '-' . $sn['kec'] . '-' . $sn['kab']; ?></th>
                                            </tr>
                                            <tr>
                                                <th>Kelas</th>
                                                <th>: <?= $sn['k_formal'] . ' ' . $sn['t_formal']; ?></th>
                                            </tr>
                                        </table>
                                    </div>
                                </div><!-- /.box-body -->
                            </form>
                        </div>
                        <div class="col-md-12">
                            <form role="form" method="post">
                                <input type="hidden" name="nis" value="<?= $nis; ?>">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Sayhriah Pesantrn</label>
                                                <input type="text" name="syahriah" class="form-control" id="uang" value="<?= $dt['syahriah']; ?>">
                                            </div>
                                        </div>
                                        <?php
                                        $sql = mysqli_query($conn, "SELECT * FROM tg_lembaga");
                                        while ($r = mysqli_fetch_assoc($sql)) { ?>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1"><?= $r['nama']; ?></label>
                                                    <input type="text" name="<?= $r['kode']; ?>" class="form-control" id="uang" value="<?= $dt[$r['kode']]; ?>">
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div><!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="submit" name="save" class="btn btn-block btn-success">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!-- /.box -->
            </div>
        </div>
    </section><!-- /.content -->
</div>



<!-- DataTables -->
<link rel="stylesheet" href="../institution/plugins/datatables/dataTables.bootstrap.css">
<!-- DataTables -->
<link rel="stylesheet" href="../institution/plugins/datatables/dataTables.bootstrap.css">
<!-- Select2 -->
<link rel="stylesheet" href="../institution/plugins/select2/select2.min.css">
<!-- jQuery 2.1.4 -->
<script src="../institution/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="../institution/bootstrap/js/bootstrap.min.js"></script>

<!-- DataTables -->
<script src="../institution/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../institution/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
    function masuk(txt, data) {
        document.getElementById('nis').value = data; // ini berfungsi mengisi value yang ber id textbox
        $("#tambah").modal('hide'); // ini berfungsi untuk menyembunyikan modal
    }
    $(function() {
        $(".select2").select2();
        $("#example1_bst").DataTable();
        $("#example2_bst").DataTable();
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

if (isset($_POST['save'])) {

    $nis = $_POST['nis'];
    $syahriah = $_POST['syahriah'];

    $sql = "UPDATE tanggungan SET syahriah = '$syahriah' ";
    $tg = mysqli_query($conn, "SELECT * FROM tg_lembaga ");
    while ($ak = mysqli_fetch_assoc($tg)) {
        $vl = $ak['kode'];
        $nm = $_POST[$ak['kode']];
        $sql .= ",$vl = '$nm'";
    }
    //$sql = substr($sql, 0, -4);
    $sql1 =   $sql . " WHERE nis = '$nis' ";
    // echo $sql1;
    $qr = mysqli_query($conn, $sql1);
    if ($qr) {
?>
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Tanggungan berhasil dupdate',
                showConfirmButton: false
            });
            var millisecondsToWait = 1000;
            setTimeout(function() {
                document.location.href = "<?= 'tanggungan.php' ?>"
            }, millisecondsToWait);
        </script>

<?php
    }
}
