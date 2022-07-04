<?php
include 'head.php';
?>
<!-- Datatables -->
<link href="cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
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
                        <h2><i class="fa fa-bars"></i> Rekapan RAB berdasarkan KPA <small>Rekapan</small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <!-- <li><button class="btn btn-info btn-sm" data-toggle="modal" data-target="#tambah"><i class="fa fa-plus-square"></i> Tambah Data</button></li> -->
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <div class="tab-content" id="myTabContent">

                            <!-- Pemasukan BOS -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-badgeledby="home-tab">
                                <form action="" method="post">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <select name="lembaga" id="" class="form-control">
                                                    <option value=""> -- pilih lembaga -- </option>
                                                    <?php
                                                    $sql = mysqli_query($conn, "SELECT * FROM lembaga WHERE tahun = '$tahun_ajaran'");
                                                    while ($rw = mysqli_fetch_assoc($sql)) { ?>
                                                        <option value="<?= $rw['kode']; ?>"><?= $rw['kode'] . '. ' . $rw['nama']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <button class="btn btn-primary" type="submit" name="views"><i class="fa fa-search"></i> Tampilkan</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <?php
                                if (isset($_POST['views'])) {
                                    $lmb = $_POST['lembaga'];
                                    $no = 1;
                                    $dt_bos = mysqli_query($conn, "SELECT a.*, b.nama AS nbd, c.nama AS nlmb FROM rab a JOIN bidang b ON a.bidang=b.kode JOIN lembaga c ON a.lembaga=c.kode WHERE a.lembaga = '$lmb' AND a.tahun = '$tahun_ajaran' ORDER BY jenis ASC");
                                ?>
                                    <div class="table-responsive">
                                        <table id="datatable-buttons" class="table table-striped table-bordered table-sm">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode</th>
                                                    <th>Lembaga</th>
                                                    <th>Bidang</th>
                                                    <th>Jenis</th>
                                                    <th>Nama</th>
                                                    <th>Rencana</th>
                                                    <th>QTY</th>
                                                    <th>Satuan</th>
                                                    <th>Hrg Satuan</th>
                                                    <th>Total</th>
                                                    <th>Tahun</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                while ($a = mysqli_fetch_assoc($dt_bos)) { ?>
                                                    <tr>
                                                        <td><?= $no++ ?></td>
                                                        <td><?= $a['kode'] ?></td>
                                                        <td><?= $a['nlmb'] ?></td>
                                                        <td><?= $a['nbd'] ?></td>
                                                        <td><?= $a['jenis'] ?></td>
                                                        <td><?= $a['nama'] ?></td>
                                                        <td><?= $a['rencana'] ?></td>
                                                        <td><?= $a['qty'] ?></td>
                                                        <td><?= $a['satuan'] ?></td>
                                                        <td><?= rupiah($a['harga_satuan']) ?></td>
                                                        <td><?= rupiah($a['total']) ?></td>
                                                        <td><?= $a['tahun'] ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php } ?>
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
<script src="vendors/jszip/dist/jszip.min.js"></script>
<script src="vendors/pdfmake/build/pdfmake.min.js"></script>
<script src="vendors/pdfmake/build/vfs_fonts.js"></script>
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