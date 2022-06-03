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
            <div class="col-md-6 col-sm-12  ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-bars"></i> Daftar Kode Lembaga</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <!-- <li><button class="btn btn-info btn-sm" data-toggle="modal" data-target="#tambah"><i class="fa fa-plus-square"></i> Tambah Data</button></li> -->
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <div class="tab-content" id="myTabContent">

                            <!-- Pemasukan BOS -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <button class="btn btn-success btn-block" data-toggle="modal" data-target="#tambah_bos">Tambah Data Lembaga</button><br>
                                <div class="table-responsive">
                                    <table id="" class="table table-striped table-bordered table-sm" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode</th>
                                                <th>Nama Lembaga</th>
                                                <th>Act</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            $dt_bos = mysqli_query($conn, "SELECT * FROM lembaga ");
                                            while ($a = mysqli_fetch_assoc($dt_bos)) { ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $a['kode'] ?></td>
                                                    <td><?= $a['nama'] ?></td>
                                                    <td>
                                                        <a data-toggle="modal" data-target="#modal_del<?= $a['id_lembaga']; ?>" href="#"><i class="fa fa-trash-o"></i> Del</a> |
                                                        <a data-toggle="modal" data-target="#m<?= $a['id_lembaga']; ?>" href="#"><i class="fa fa-edit"></i> Edit</a>

                                                        <!-- Modal Hapus -->
                                                        <div class="modal fade bs-example-modal-sm" id="modal_del<?= $a['id_lembaga']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog modal-sm">
                                                                <div class="modal-content">

                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title" id="myModalLabel2">Hapus Daftar Kode</h4>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                                                                        </button>
                                                                    </div>
                                                                    <form action="" method="post">
                                                                        <input type="hidden" name="id_lembaga" value="<?= $a['id_lembaga']; ?>">
                                                                        <div class="modal-body">
                                                                            <h4>Yakin akan menghapu data ini ?</h4>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                                                            <button type="submit" name="delete" class="btn btn-danger">Ya.! Hapus Pon</button>
                                                                        </div>
                                                                    </form>

                                                                </div>
                                                            </div>
                                                        </div>

                                                    </td>

                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-sm-12  ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Keterangan Kode Bidang</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <button class="btn btn-success btn-block" data-toggle="modal" data-target="#tambah">Tambah Data Bidang</button><br>
                        <div class="table-responsive">
                            <table id="" class=" table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama Bagian</th>
                                        <th>Level</th>
                                        <th>Act</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $dt_bos = mysqli_query($conn, "SELECT * FROM bidang ");
                                    while ($a = mysqli_fetch_assoc($dt_bos)) { ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $a['kode'] ?></td>
                                            <td><?= $a['nama'] ?></td>
                                            <td><?= $a['lv'] ?></td>
                                            <td>
                                                <a data-toggle="modal" data-target="#modal_del2<?= $a['id_bidang']; ?>" href="#"><i class="fa fa-trash-o"></i> Del</a>

                                                <!-- Modal Hapus -->
                                                <div class="modal fade bs-example-modal-sm" id="modal_del2<?= $a['id_bidang']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-sm">
                                                        <div class="modal-content">

                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myModalLabel2">Hapus Daftar Kode Bagian</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <form action="" method="post">
                                                                <input type="hidden" name="id_bidang" value="<?= $a['id_bidang']; ?>">
                                                                <div class="modal-body">
                                                                    <h4>Yakin akan menghapu data ini ?</h4>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                                                    <button type="submit" name="delete2" class="btn btn-danger">Enggi.! Hapus Pon</button>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>

                                            </td>

                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
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

<!-- Modal Tambah Data BOS-->
<div class="modal fade" id="tambah_bos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Tambah Data Belanja</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left input_mask" action="" method="post">
                <div class="modal-body">
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Kode <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="number" id="first-name" name="kode" required="required" class="form-control ">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nama Lembaga <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="first-name" name="nama" required="required" class="form-control ">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Penanggungjawab <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 ">
                            <input id="middle-name" class="form-control" type="text" name="pj" required>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">No. HP <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 ">
                            <input id="middle-name" class="form-control" type="text" name="hp" required>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Waktu Pelaksanaan <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 ">
                            <input id="middle-name" class="form-control" type="text" name="waktu" required>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Level <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 ">
                            <select name="lv" id="" class="form-control" required>
                                <option value="ps">Pesantren</option>
                                <option value="lf">Lembaga Formal</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="save" class="btn btn-success">Simpan data</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Modal Tambah Data BOS-->
<div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Tambah Data Bidang Lembaga</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left input_mask" action="" method="post">
                <div class="modal-body">
                    <?php
                    $mx = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM bidang ORDER BY kode DESC LIMIT 1"));
                    $km = $mx['kode'] + 1;
                    ?>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Kode <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="number" id="first-name" name="kode" value="<?= $km ?>" required="required" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nama Bidang <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="first-name" name="nama" required="required" class="form-control ">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Level <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 ">
                            <select name="lv" id="" class="form-control" required>
                                <option value="ps">Pesantren</option>
                                <option value="lf">Lembaga Formal</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="save2" class="btn btn-success">Simpan data</button>
                </div>
            </form>

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
    $lv = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['lv']));

    $sql = mysqli_query($conn, "INSERT INTO lembaga VALUES ('', '$kode', '$nama','$pelaksana','$pj','$hp','$waktu','$lv')");
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
                    document.location.href = "kode.php"
                }, millisecondsToWait);

            });
        </script>

    <?php    }
}

if (isset($_POST['save2'])) {

    $kode = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['kode']));
    $nama = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['nama']));
    $lv = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['lv']));

    $sql = mysqli_query($conn, "INSERT INTO bidang VALUES ('', '$kode', '$nama','$lv')");
    if ($sql) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Bidang lembaga berhasil tersimpan',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "kode.php"
                }, millisecondsToWait);

            });
        </script>

    <?php    }
}

if (isset($_POST['delete'])) {

    $id_lembaga = $_POST['id_lembaga'];

    $sql = mysqli_query($conn, "DELETE FROM lembaga WHERE id_lembaga = $id_lembaga ");
    if ($sql) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Lembaga berhasil dihapus',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "kode.php"
                }, millisecondsToWait);

            });
        </script>

    <?php    }
}

if (isset($_POST['delete2'])) {

    $id_bidang = $_POST['id_bidang'];

    $sql = mysqli_query($conn, "DELETE FROM bidang WHERE id_bidang = $id_bidang ");
    if ($sql) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'bidang berhasil dihapus',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "kode.php"
                }, millisecondsToWait);

            });
        </script>

<?php    }
}
?>