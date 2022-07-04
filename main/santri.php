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
                        <h2><i class="fa fa-bars"></i> Data Santri <small>aktif</small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <!-- <li><button class="btn btn-info btn-sm" data-toggle="modal" data-target="#tambah"><i class="fa fa-plus-square"></i> Tambah Data</button></li> -->
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <div class="tab-content" id="myTabContent">

                            <!-- Pemasukan BOS -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                                <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Alamat</th>
                                            <th>Kelas Formal</th>
                                            <th>Kelas Madin</th>
                                            <th>Stts</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $dt_bos = mysqli_query($conn, "SELECT * FROM tb_santri WHERE aktif = 'Y'");
                                        while ($a = mysqli_fetch_assoc($dt_bos)) { ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $a['nama'] ?></td>
                                                <td><?= $a['desa'] . '-' . $a['kec'] . '-' . $a['kab'] ?></td>
                                                <td><?= $a['k_formal'] . ' ' . $a['t_formal'] ?></td>
                                                <td><?= $a['k_madin'] . ' ' . $a['r_madin'] ?></td>
                                                <td><?php $st = $a["stts"];
                                                    $ps = explode("-", $st);
                                                    if ($ps[0] == 1) {
                                                        echo "<span class='badge badge-violet'>Ust/Usdz</span>";
                                                        echo " ";
                                                    }
                                                    if ($ps[1] == 2) {
                                                        echo "<span class='badge badge-primary'>Mhs/i</span>";
                                                        echo " ";
                                                    }
                                                    if ($ps[2] == 3) {
                                                        echo "<span class='badge badge-success'>Sdr/i</span>";
                                                        echo " ";
                                                    }
                                                    if ($ps[3] == 4) {
                                                        echo "<span class='badge badge-info'>Kls 6</span>";
                                                        echo " ";
                                                    }
                                                    if ($ps[4] == 5) {
                                                        echo "<span class='badge badge-warning'>Baru</span>";
                                                        echo " ";
                                                    }
                                                    if ($ps[5] == 6) {
                                                        echo "<span class='badge badge-danger'>Lama</span>";
                                                        echo " ";
                                                    }
                                                    if ($ps[6] == 7) {
                                                        echo "<span class='badge badge-primary'>Peng. Wilyah</span>";
                                                        echo " ";
                                                    }
                                                    if ($ps[7] == 8) {
                                                        echo "<span class='badge badge-dark'>Putra</span>";
                                                        echo " ";
                                                    }
                                                    if ($ps[8] == 9) {
                                                        echo "<span class='badge badge-info'>Putri</span>";
                                                    }
                                                    ?></td>
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