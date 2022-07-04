<?php
include 'head.php';
$jml = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(IF( ket = 'masuk', nominal, 0)) AS masuk, SUM(IF( ket = 'keluar', nominal, 0)) AS keluar FROM kas WHERE tahun = '$tahun_ajaran' "));

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
                        <h2><i class="fa fa-bars"></i> Data Pengeluaran Dari Bendahara Utama </h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <!-- <li><button class="btn btn-info btn-sm" data-toggle="modal" data-target="#tambah"><i class="fa fa-plus-square"></i> Tambah Data</button></li> -->
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <div class="tab-content" id="myTabContent">

                            <!-- Pemasukan BOS -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-badgeledby="home-tab">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="alert alert-success " role="alert">
                                            <strong>Masuk : <?= rupiah($jml['masuk']); ?></strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="alert alert-danger " role="alert">
                                            <strong>Keluar : <?= rupiah($jml['keluar']); ?></strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="alert alert-info " role="alert">
                                            <strong>Saldo : <?= rupiah($jml['masuk'] - $jml['keluar']); ?></strong>
                                        </div>
                                    </div>
                                </div>
                                <table id="datatable" class="table table-striped table-bordered table-sm" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tgl Setor</th>
                                            <th>Uraian</th>
                                            <th>Penerima</th>
                                            <th>Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $dt_bos = mysqli_query($conn, "SELECT * FROM kas WHERE ket = 'keluar' AND tahun = '$tahun_ajaran' ");
                                        while ($a = mysqli_fetch_assoc($dt_bos)) {
                                        ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $a['tgl'] ?></td>
                                                <td><?= $a['uraian'] ?></td>
                                                <td><?= $a['penyetor'] ?></td>
                                                <td><?= rupiah($a['nominal']) ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4">TOTAL</th>
                                            <th><?= rupiah($jml['keluar']); ?></th>
                                        </tr>
                                    </tfoot>
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