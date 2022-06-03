<?php
include 'atas.php';
$pes_data = mysqli_query($conn, "SELECT * FROM pesantren");
$pes_jml = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as jml FROM pesantren "));
$pes_lm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as jml FROM pesantren GROUP BY lembaga "));
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Pemasukan Pesantren
            <small>Dari Divisi</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">pes</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Data Pemasukan pes</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box bg-green">
                                    <span class="info-box-icon"><i class="ion ion-bug"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">TOTAL PEMASUKAN DARI pes</span>
                                        <span class="info-box-number"><?= rupiah($pes_jml['jml']) ?></span>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: 20%"></div>
                                        </div>
                                        <span class="progress-description">
                                            Total pemasukan pes per <?= date('d M Y') ?>, dari <?= $pes_lm['jml'] ?> lembaga
                                        </span>
                                    </div><!-- /.info-box-content -->
                                </div><!-- /.info-box -->
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop"><i class="fa fa-plus"></i> Input pes Baru</button>
                                <button class="btn btn-warning"><i class="fa fa-download"></i> Download data pes</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="example1_bst" class="table table-bordered table-sm table-striped">
                                <thead>
                                    <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                        <th>No</th>
                                        <th>Bidang</th>
                                        <th>Nominal</th>
                                        <th>Tgl Setor</th>
                                        <th>Uraian</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $rls = mysqli_query($conn, "SELECT a.*, b.nama FROM pesantren a JOIN bidang b ON a.bidang=b.kode  ");
                                    while ($ls_jns = mysqli_fetch_assoc($rls)) {

                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $ls_jns['nama']; ?></td>
                                            <td><?= rupiah($ls_jns['nominal']) ?></td>
                                            <td><?= $ls_jns['tgl_bayar'] ?></td>
                                            <td><?= $ls_jns['uraian'] ?></td>
                                            <td>
                                                <a href="<?= 'pes_edit.php?id=' . $ls_jns['id_pes'] ?>"><button class="btn btn-warning btn-xs"><i class="fa fa-edit"></i> Edit</button></a>
                                                <a onclick="return confirm('Yakin akan dihapus ?')" href="<?= 'hapus.php?kd=pes&id=' . $ls_jns['id_pes'] ?>"><button class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Del</button></a>
                                            </td>
                                        </tr>

                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
    </section><!-- /.content -->
</div>


<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class=" modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Tambah Pemasukan pes</h4>
            </div>
            <form action="" method="post" class="form-horizontal">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Lembaga *</label>
                        <div class="col-sm-10">
                            <select name="lembaga" class="form-control" required>
                                <option value=""> -- pilih lembaga -- </option>
                                <?php
                                $lm = mysqli_query($conn, "SELECT * FROM lembaga");
                                while ($row = mysqli_fetch_assoc($lm)) { ?>
                                    <option value="<?= $row['kode'] ?>"><?= $row['nama'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Bidang *</label>
                        <div class="col-sm-10">
                            <select name="bidang" class="form-control" required>
                                <option value=""> -- pilih bidang -- </option>
                                <?php
                                $lm = mysqli_query($conn, "SELECT * FROM bidang");
                                while ($row = mysqli_fetch_assoc($lm)) { ?>
                                    <option value="<?= $row['kode'] ?>"><?= $row['nama'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Periode *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="periode" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Nominal *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nominal" id="uang" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Tgl Setor *</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="tgl_setor" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Tahun</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="tahun" value="<?= date('Y') ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Uraian</label>
                        <div class="col-sm-10">
                            <textarea name="uraian" class="form-control" id=""></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" name="save" class="btn btn-success">Simpan pes</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- DataTables -->
<link rel="stylesheet" href="../institution/plugins/datatables/dataTables.bootstrap.css">
<!-- jQuery 2.1.4 -->
<script src="../institution/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="../institution/bootstrap/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="../institution/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../institution/plugins/datatables/dataTables.bootstrap.min.js"></script>
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

if (isset($_POST['save'])) {

    $id = $uuid;
    $lembaga = $_POST['lembaga'];
    $bidang = $_POST['bidang'];
    $kode = $lembaga . '.' . $bidang;
    $uraian = addslashes(htmlspecialchars(mysqli_real_escape_string($conn, $_POST['uraian'])));
    $periode = addslashes(htmlspecialchars(mysqli_real_escape_string($conn, $_POST['periode'])));
    $nominal = addslashes(htmlspecialchars(mysqli_real_escape_string($conn, preg_replace("/[^0-9]/", "", $_POST['nominal']))));
    $tgl_setor = $_POST['tgl_setor'];
    $tahun = $_POST['tahun'];
    $kasir = $nama_user;

    $sql = mysqli_query($conn, "INSERT INTO pesantren VALUES ('$id', '$lembaga', '$bidang', '$kode', '$uraian', '$periode', '$nominal', '$tahun', '$tgl_setor', NOW() ");

    if ($sql) { ?>
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Pemasukan sudah masuk',
                showConfirmButton: false
            });
            var millisecondsToWait = 1000;
            setTimeout(function() {
                document.location.href = "<?= 'pes.php' ?>"
            }, millisecondsToWait);
        </script>

<?php    }
}
?>