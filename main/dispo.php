<?php
include 'head.php';

$tot = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nom_cair) AS jml FROM real_sm WHERE kode_pengajuan LIKE 'DISP.%' AND tahun = '$tahun_ajaran'"));
$tot2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nom_cair) AS jml FROM realis WHERE kode_pengajuan LIKE 'DISP.%' AND tahun = '$tahun_ajaran'"));
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
                        <h2><i class="fa fa-bars"></i> Data Pencairan Disposisi <small>Pencairan</small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <div class="tab-content" id="myTabContent">

                            <!-- Pemasukan BOS -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-badgeledby="home-tab">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="alert alert-danger" role="alert">
                                            <strong>LIMIT : <?= rupiah(50000000); ?></strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="alert alert-success" role="alert">
                                            <strong>Terpakai : <?= rupiah($tot['jml'] + $tot2['jml']); ?></strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="alert alert-info" role="alert">
                                            <strong>Sisa Dana : <?= rupiah(50000000 - ($tot['jml'] + $tot2['jml'])); ?></strong>
                                        </div>
                                    </div>
                                </div>
                                <table id="datatable" class="table table-striped table-bordered table-sm" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Lembaga</th>
                                            <th>Tanggal</th>
                                            <th>Nominal Cair</th>
                                            <th>Status</th>
                                            <!-- <th>#</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $dt_bos = mysqli_query($conn, "SELECT a.*, b.nama FROM pengajuan a JOIN lembaga b ON a.lembaga=b.kode WHERE a.tahun = '$tahun_ajaran' AND a.kode_pengajuan LIKE 'DISP.%' AND b.tahun = '$tahun_ajaran' ");
                                        while ($a = mysqli_fetch_assoc($dt_bos)) {
                                            $kd_pj = $a['kode_pengajuan'];
                                            $jml = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nom_cair) AS jml FROM real_sm WHERE kode_pengajuan = '$kd_pj' AND tahun = '$tahun_ajaran' "));
                                            $jml2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nom_cair) AS jml FROM realis WHERE kode_pengajuan = '$kd_pj' AND tahun = '$tahun_ajaran' "));
                                            $kfe = $jml['jml'] + $jml2['jml'];
                                        ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $a['kode_pengajuan'] ?></td>
                                                <td><?= $a['nama'] ?></td>
                                                <td><?= $a['at'] ?></td>
                                                <td><?= rupiah($kfe) ?></td>
                                                <td>
                                                    <?= $a['verval'] == 1 ? "<span class='badge badge-success'><i class='fa fa-check'></i> sudah</span>" : "<span class='badge badge-danger'><i class='fa fa-times'></i> belum</span>"; ?>
                                                    <?= $a['apr'] == 1 ? "<span class='badge badge-success'><i class='fa fa-check'></i> sudah</span>" : "<span class='badge badge-danger'><i class='fa fa-times'></i> belum</span>"; ?>
                                                    <?= $a['cair'] == 1 ? "<span class='badge badge-success'><i class='fa fa-check'></i> sudah</span>" : "<span class='badge badge-danger'><i class='fa fa-times'></i> belum</span>"; ?>
                                                    <?php if ($a['spj'] == 0) { ?>
                                                        <span class="badge badge-danger"><i class="fa fa-times"></i> belum upload</span>
                                                    <?php } else if ($a['spj'] == 1) { ?>
                                                        <span class="badge badge-warning btn-xs"><i class="fa fa-spinner fa-refresh-animate"></i>
                                                            proses verifikasi</span>
                                                    <?php } else { ?>
                                                        <span class="badge badge-success"><i class="fa fa-check"></i> sudah selesai</span>
                                                    <?php } ?>
                                                </td>
                                                <!-- <td>
                                                    <a href="<?= 'edit_disp.php?id=' . $a['id_disp'] ?>"><button class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</button></a>
                                                    <a onclick="return confirm('Yakin akan dihapus ?')" href="<?= 'hapus.php?kd=dsp&id=' . $a['id_disp'] ?>"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Del</button></a>
                                                </td> -->
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4"></th>
                                            <th colspan="2"><?= rupiah($tot['jml'] + $tot2['jml']); ?></th>
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