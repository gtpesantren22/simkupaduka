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
                        <h2><i class="fa fa-bars"></i> Data SPJ <small>Realisasi</small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <!-- <li><button class="btn btn-info btn-sm" data-toggle="modal" data-target="#tambah"><i class="fa fa-plus-square"></i> Tambah Data</button></li> -->
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <div class="tab-content" id="myTabContent">

                            <!-- Pemasukan BOS -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-badgeledby="home-tab">

                                <table id="datatable" class="table table-striped table-bordered table-sm" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Lembaga</th>
                                            <th>Periode</th>
                                            <th>Status</th>
                                            <th>Nominal</th>
                                            <th>Berkas</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $dt_bos = mysqli_query($conn, "SELECT a.*, b.nama FROM spj a JOIN lembaga b ON a.lembaga=b.kode WHERE a.stts != 2 AND a.tahun = '$tahun_ajaran' AND b.tahun = '$tahun_ajaran' ");
                                        while ($a = mysqli_fetch_assoc($dt_bos)) {
                                            $kd_pj = $a['kode_pengajuan'];
                                            $jml = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jml FROM real_sm WHERE kode_pengajuan = '$kd_pj' AND tahun = '$tahun_ajaran' "));
                                            $jml2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jml FROM realis WHERE kode_pengajuan = '$kd_pj' AND tahun = '$tahun_ajaran' "));
                                            $pjan = $jml['jml'] + $jml2['jml'];
                                        ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $a['nama'] ?></td>
                                                <td><?= $bulan[$a['bulan']] . ' ' . $a['tahun'] ?></td>
                                                <td>
                                                    <?php if ($a['stts'] == 0) { ?>
                                                        <span class="badge badge-danger"><i class="fa fa-times"></i> belum upload</span>
                                                    <?php } else if ($a['stts'] == 1) { ?>
                                                        <span class="badge badge-warning btn-xs"><i class="fa fa-spinner fa-refresh-animate"></i>
                                                            proses verifikasi</span>
                                                    <?php } else { ?>
                                                        <span class="badge badge-success"><i class="fa fa-check"></i> sudah selesai</span>
                                                    <?php } ?>
                                                </td>
                                                <td><?= rupiah($pjan) ?></td>
                                                <td><a href="<?= '../institution/spj_file/' . $a['file_spj'] ?>"><i class="fa fa-download"></i> Unduh Berkas</a></td>
                                                <td><button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#tambah_bos<?= $a['id_spj'] ?>"><i class="fa fa-check"></i> Setuji</button></td>
                                            </tr>
                                            <!-- Modal Tambah Data BOS-->
                                            <div class="modal fade" id="tambah_bos<?= $a['id_spj'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">

                                                        <div class="modal-header">
                                                            <h4 class="modal-title" id="myModalLabel">Form persetujuan SPJ</h4>
                                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">??</span>
                                                            </button>
                                                        </div>
                                                        <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left input_mask" action="" method="post">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="id" value="<?= $a['id_spj'] ?>">
                                                                <input type="hidden" name="kode" value="<?= $a['kode_pengajuan'] ?>">
                                                                <div class="item form-group">
                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Lembaga <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-md-6 col-sm-6 ">
                                                                        <input type="text" id="first-name" required="required" disabled value="<?= $a['nama'] ?>" class="form-control ">
                                                                    </div>
                                                                </div>
                                                                <div class="item form-group">
                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Periode <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-md-6 col-sm-6 ">
                                                                        <input type="text" id="first-name" required="required" disabled value="<?= $bulan[$a['bulan']] . ' ' . $a['tahun'] ?>" class="form-control ">
                                                                    </div>
                                                                </div>
                                                                <div class="item form-group">
                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nominal <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-md-6 col-sm-6 ">
                                                                        <input type="text" id="first-name" required="required" disabled value="<?= rupiah($pjan) ?>" class="form-control ">
                                                                    </div>
                                                                </div>
                                                                <div class="item form-group">
                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Tanggal disetujui <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-md-6 col-sm-6 ">
                                                                        <input type="date" id="" required="required" name="tgl" class="form-control ">
                                                                    </div>
                                                                </div>
                                                                <div class="item form-group">
                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Menyetujui <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-md-6 col-sm-6 ">
                                                                        <input type="text" required="required" name="user" value="<?= $nama_user ?>" class="form-control" readonly>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="save_bos" class="btn btn-success"><i class="fa fa-check"></i> Setujui</button>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </tbody>
                                </table>
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

    $id = $_POST['id'];
    $kode = $_POST['kode'];

    $sql = mysqli_query($conn, "UPDATE spj SET stts = 2 WHERE id_spj = '$id' AND tahun = '$tahun_ajaran' ");
    $sql2 = mysqli_query($conn, "UPDATE pengajuan SET spj = 2 WHERE kode_pengajuan = '$kode' AND tahun = '$tahun_ajaran' ");
    if ($sql) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'SPJ sudah di verifikasi',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "spj.php"
                }, millisecondsToWait);

            });
        </script>

<?php    }
}

?>