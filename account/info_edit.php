<?php
include 'head.php';

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM info WHERE id_info = '$id' AND tahun = '$tahun_ajaran' "));
?>
<!-- Datatables -->
<link href="../main/../main/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="../main/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
<link href="../main/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
<link href="../main/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
<link href="../main/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

<!-- bootstrap-daterangepicker -->
<link href="../main/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<!-- bootstrap-datetimepicker -->
<link href="../main/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">

<!-- page content -->
<div class="right_col" role="main">
    <div class="">

        <div class="clearfix"></div>

        <div class="">
            <div class="col-md-12 col-sm-12  ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-bars"></i> Informasi Biro Keuangan</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a href="info.php"><button class="btn btn-info btn-sm"><i class="fa fa-arrow-left"></i> Kembali</button></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <div class="tab-content" id="myTabContent">

                            <!-- Pemasukan BOS -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <form class="form-horizontal form-label-left" action="" method="post">
                                    <input type="hidden" name="id_info" value="<?= $data['id_info'] ?>">
                                    <div class="form-group row ">
                                        <label class="control-label col-md-3 col-sm-3 ">Judul *</label>
                                        <div class="col-md-9 col-sm-9 ">
                                            <input type="text" value="<?= $data['judul'] ?>" class="form-control" placeholder="Judul informasi" name="judul" required>
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label class="control-label col-md-3 col-sm-3 ">Tanggal Informasi *</label>
                                        <div class="col-md-9 col-sm-9 ">
                                            <input type="text" value="<?= $data['tgl'] ?>" class="form-control" name="tgl" readonly value="<?= date('Y-m-d H:i') ?>">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-3 ">Tujuan *</label>
                                        <div class="col-md-9 col-sm-9 ">
                                            <select class="form-control" name="tujuan" required>
                                                <option value=""> -pilih- </option>
                                                <option value="umum" <?= $data['tujuan'] == 'umum' ? 'selected' : '' ?>>Umum</option>
                                                <option value="rab" <?= $data['tujuan'] == 'rab' ? 'selected' : '' ?>>Untuk RAB</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label class="control-label col-md-3 col-sm-3 ">Uploader *</label>
                                        <div class="col-md-9 col-sm-9 ">
                                            <input type="text" class="form-control" name="uploader" readonly value="<?= $data['uploader'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label class="control-label col-md-3 col-sm-3 ">Isi Informasi *</label>
                                    </div>
                                    <div class="form-group row ">
                                        <div class="col-md-12 col-sm-12 ">
                                            <textarea class="ckeditor" id="editor-full" name="isi" required><?= $data['isi'] ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <div class="col-md-12 col-sm-12 ">
                                            <button class="btn btn-success" type="submit" name="save"><i class="fa fa-save"></i> Simpan Informasi</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<!-- /page content -->


<?php include 'foot.php'; ?>
<!-- Datatables -->
<script src="../main/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../main/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="../main/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="../main/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="../main/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="../main/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="../main/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="../main/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="../main/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="../main/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="../main/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="../main/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
<!-- bootstrap-daterangepicker -->
<script src="../main/vendors/moment/min/moment.min.js"></script>
<script src="../main/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap-datetimepicker -->
<script src="../main/dist/sweetalert2.all.min.js"></script>
<script src="../main/vendors/ckeditor-full/ckeditor.js"></script>

<script>
    $(document).ready(function() {
        $('#datatable2').DataTable();
        $('#datatable3').DataTable();

        $('#datePick').datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $('#datePick2').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    });
</script>


<?php
if (isset($_POST['save'])) {

    $id =  $_POST['id_info'];
    $judul =  htmlspecialchars(mysqli_real_escape_string($conn, $_POST['judul']));
    $tgl =  $_POST['tgl'];
    $uploader =  $_POST['uploader'];
    $isi =  $_POST['isi'];
    $tujuan =  $_POST['tujuan'];


    $sql = mysqli_query($conn, "UPDATE info SET judul = '$judul', tgl = '$tgl', uploader = '$uploader', isi = '$isi', tujuan = '$tujuan' WHERE id_info = '$id' AND tahun = '$tahun_ajaran' ");
    if ($sql) { ?>
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Informasi sudah diperbarui',
                showConfirmButton: false
            });
            var millisecondsToWait = 1000;
            setTimeout(function() {
                document.location.href = "info.php"
            }, millisecondsToWait);
        </script>

<?php    }
}


?>