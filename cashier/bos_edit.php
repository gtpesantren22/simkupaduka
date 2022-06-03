<?php
include 'atas.php';
$id = $_GET['id'];
$bos = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM bos WHERE id_bos = '$id' "));
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Pemasukan BOS
            <small>Lembaga</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">BOS</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Edit Pemasukan BOS</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
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
                                                        <option <?= $row['kode'] == $bos['lembaga'] ? 'selected' : '' ?> value="<?= $row['kode'] ?>"><?= $row['nama'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 control-label">Periode *</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="periode" required value="<?= $bos['periode'] ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 control-label">Nominal *</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="nominal" id="uang" value="<?= $bos['nominal'] ?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 control-label">Tgl Setor *</label>
                                            <div class="col-sm-10">
                                                <input type="date" class="form-control" name="tgl_setor" required value="<?= $bos['tgl_setor'] ?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 control-label">Tahun</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="tahun" value="<?= $bos['tahun'] ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 control-label">Uraian</label>
                                            <div class="col-sm-10">
                                                <textarea name="uraian" class="form-control" id="">value="<?= $bos['uraian'] ?>"</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <button type="submit" name="save" class="btn btn-success">Simpan BOS</button>
                                    </div>
                                </form>
                            </div>
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
                <h4 class="modal-title">Tambah Pemasukan BOS</h4>
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
                    <button type="submit" name="save" class="btn btn-success">Simpan BOS</button>
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
    $kode = $lembaga . '.' . 'BOS';
    $uraian = addslashes(htmlspecialchars(mysqli_real_escape_string($conn, $_POST['uraian'])));
    $periode = addslashes(htmlspecialchars(mysqli_real_escape_string($conn, $_POST['periode'])));
    $nominal = addslashes(htmlspecialchars(mysqli_real_escape_string($conn, preg_replace("/[^0-9]/", "", $_POST['nominal']))));
    $tgl_setor = $_POST['tgl_setor'];
    $tahun = $_POST['tahun'];
    $kasir = $nama_user;

    $sql = mysqli_query($conn, "INSERT INTO bos VALUES ('$id', '$kode', '$lembaga', '$uraian', '$periode', '$nominal', '$tahun', '$tgl_setor', '$kasir') ");

    if ($sql) { ?>
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Bos sudah masuk',
                showConfirmButton: false
            });
            var millisecondsToWait = 1000;
            setTimeout(function() {
                document.location.href = "<?= 'bos.php' ?>"
            }, millisecondsToWait);
        </script>

<?php    }
}
?>