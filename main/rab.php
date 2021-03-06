<?php
include 'head.php';
?>
<!-- Datatables -->
<link href="vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
<link href="vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
<link href="vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
<link href="vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

<!-- bootstrap-daterangepicker -->
<link href="vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<!-- bootstrap-datetimepicker -->
<link href="vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">

<!-- page content -->
<div class="right_col" role="main">
    <div class="">

        <div class="clearfix"></div>

        <div class="">
            <div class="col-md-12 col-sm-12  ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-bars"></i> Daftar Rencana Belanja <small>lembaga</small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <!-- <li><button class="btn btn-info btn-sm" data-toggle="modal" data-target="#tambah"><i class="fa fa-plus-square"></i> Tambah Data</button></li> -->
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="tab-content" id="myTabContent">

                            <!-- Pemasukan BOS -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <?php
                                $df = mysqli_query($conn, "SELECT * FROM lembaga WHERE tahun = '$tahun_ajaran'");
                                while ($r = mysqli_fetch_assoc($df)) { ?>
                                    <a href="<?= 'rab_detail.php?kode=' . $r['kode'] ?>"><button class="btn btn-primary"><?= $r['kode'] . '. ' . $r['nama'] ?></button></a>
                                <?php } ?>

                                <div class="row">
                                    <div class="col-md-9">
                                        <h2 class="line_30">Daftar kode belanja</h2>
                                        <table class="countries_list">
                                            <tbody>
                                                <tr>
                                                    <td class="fs15 fw700 text-left"><b>A.</b></td>
                                                    <td>Belanja Barang</td>
                                                </tr>
                                                <tr>
                                                    <td class="fs15 fw700 text-left"><b>B.</b></td>
                                                    <td>Langganan Daya dan Jasa</td>
                                                </tr>
                                                <tr>
                                                    <td class="fs15 fw700 text-left"><b>C.</b></td>
                                                    <td>Belanja Kegiatan</td>
                                                </tr>
                                                <tr>
                                                    <td class="fs15 fw700 text-left"><b>D.</b></td>
                                                    <td>Umum</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-3">
                                        <h2 class="line_30">Upload File RAB (.xls)</h2>
                                        <button class="btn btn-success btn-block btn-xl" data-toggle="modal" data-target="#ex_upload"><i class="fa fa-upload"></i> Upload Excel</button><br>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End to do list -->


    </div>
    <div class="clearfix"></div>
</div>
<!-- /page content -->

<!-- Modal Upload-->
<div class="modal fade" id="ex_upload" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Upload File RAB (.xls)</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">??</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="item form-group">
                    <label for="tahun" class="col-form-label col-md-3 col-sm-3 label-align">Download Template <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 ">
                        <a href="file_rab/File example/Templetes RAB_realisasi_upload.xls">
                            <button class="btn btn-sm btn-success"><i class="fa fa-file-excel"></i> Download Template RAB (.xls)</button>
                        </a>
                    </div>
                </div>
                <hr>
                <form id="" data-parsley-validate class="form-horizontal form-label-left input_mask" action="upload_ex2.php" method="post" enctype="multipart/form-data">
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nama Barang/Kegiatan <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="file" id="" name="file" required="required" class="form-control ">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name"><span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <button type="submit" name="upload" class="btn btn-success">Upload File</button>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

<?php include 'foot.php'; ?>
<!-- Datatables -->
<script src="vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
<!-- bootstrap-daterangepicker -->
<script src="vendors/moment/min/moment.min.js"></script>
<script src="vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap-datetimepicker -->
<script src="vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
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

    $kode = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['kode']));
    $nama = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['nama']));
    $pelaksana = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['nama']));
    $pj = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['pj']));
    $hp = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['hp']));
    $waktu = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['waktu']));
    $link = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['link']));

    $sql = mysqli_query($conn, "INSERT INTO lembaga VALUES ('', '$kode', '$nama','$pelaksana','$pj','$hp','$waktu','$link', '$tahun_ajaran')");
    if ($sql) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Lembaga berhasil tersimpan',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "rab.php"
                }, millisecondsToWait);

            });
        </script>

    <?php    }
}


if (isset($_POST['delete'])) {

    $id_kode = $_POST['id_kode'];

    $sql = mysqli_query($conn, "DELETE FROM df_kode WHERE id_kode = $id_kode AND tahun = '$tahun_ajaran' ");
    if ($sql) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Tahapan berhasil dihapus',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "kdpes.php"
                }, millisecondsToWait);

            });
        </script>

<?php    }
}
?>