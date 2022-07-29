<?php
include 'head.php';
$kode = $_GET['kode'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pengajuan WHERE id_pn = '$kode' AND tahun = '$tahun_ajaran' "));
$kode_p = $data['kode_pengajuan'];

$kd_l = $data['lembaga'];
$l = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$kd_l' AND tahun = '$tahun_ajaran' "));
$kd_pj = $data['kode_pengajuan'];
$jml = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jml FROM real_sm WHERE kode_pengajuan = '$kd_pj' AND tahun = '$tahun_ajaran' "));
$jml2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jml FROM realis WHERE kode_pengajuan = '$kd_pj' AND tahun = '$tahun_ajaran' "));
$kfe = $jml['jml'] + $jml2['jml'];


$veral = mysqli_query($conn, "SELECT * FROM verifikasi WHERE kode_pengajuan = '$kd_pj' AND tahun = '$tahun_ajaran' ");
$apr = mysqli_query($conn, "SELECT * FROM approv WHERE kode_pengajuan = '$kd_pj' AND tahun = '$tahun_ajaran' ");
$cair = mysqli_query($conn, "SELECT * FROM pencairan WHERE kode_pengajuan = '$kd_pj' AND tahun = '$tahun_ajaran' ");

$spj = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM spj WHERE kode_pengajuan = '$kd_pj' "));

$a = mysqli_fetch_assoc(mysqli_query($conn, "SELECT a.*, b.nama FROM pengajuan a JOIN lembaga b ON a.lembaga=b.kode WHERE a.kode_pengajuan = '$kd_pj' AND a.tahun = '$tahun_ajaran' AND b.tahun = '$tahun_ajaran' "));
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
                        <h2><i class="fa fa-bars"></i> History Pengajuan <small>Realisasi</small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <a href="history_pengajuan.php">
                                <li><button class="btn btn-warning btn-sm"><i class="fa fa-plus-arrow-left"></i> Kembali</button></li>
                            </a>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <!-- info row -->
                        <div class="row invoice-info">
                            <div class="col-sm-5">
                                <table class="countries_list">
                                    <tbody>
                                        <tr>
                                            <th>Lembaga</th>
                                            <th>: <?= $l['nama'] ?></th>
                                        </tr>
                                        <tr>
                                            <th>Pelakasana</th>
                                            <th>: <?= $l['pelaksana'] ?></th>
                                        </tr>
                                        <tr>
                                            <th>PJ/HP</th>
                                            <th>: <?= $l['pj'] . ' / ' . $l['hp'] ?></th>
                                        </tr>
                                        <tr>
                                            <th>Pelaksanaan</th>
                                            <th>: <?= $l['waktu'] ?></th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.col -->

                            <!-- /.col -->
                            <div class="col-sm-4">
                                <center>
                                    <p style="font-size: 23px;">
                                        <b>NOMINAL PENGAJUAN</b><br>
                                        <b style="color: limegreen;"><?= rupiah($kfe); ?>
                                        </b>
                                    </p>
                                </center>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-3 invoice-col">
                                <center>
                                    <b>Status Pengajuan</b><br>
                                    Verval : <?= $a['verval'] == 1 ? "<span class='badge badge-success'><i class='fa fa-check'></i> sudah</span>" : "<span class='badge badge-danger'><i class='fa fa-times'></i> belum</span>"; ?><br>
                                    Approv : <?= $a['apr'] == 1 ? "<span class='badge badge-success'><i class='fa fa-check'></i> sudah</span>" : "<span class='badge badge-danger'><i class='fa fa-times'></i> belum</span>"; ?><br>
                                    Cair : <?= $a['cair'] == 1 ? "<span class='badge badge-success'><i class='fa fa-check'></i> sudah</span>" : "<span class='badge badge-danger'><i class='fa fa-times'></i> belum</span>"; ?><br>
                                    SPJ : <?php if ($a['spj'] == 0) { ?>
                                        <span class="badge badge-danger"><i class="fa fa-times"></i> belum upload</span>
                                    <?php } else if ($a['spj'] == 1) { ?>
                                        <span class="badge badge-warning btn-xs"><i class="fa fa-spinner fa-refresh-animate"></i>
                                            proses verifikasi</span>
                                    <?php } else { ?>

                                        <span class="badge badge-success"><i class="fa fa-check"></i> sudah selesai</span>
                                        <a href="../institution/spj_file/<?= $spj['file_spj']; ?>"> <span class="badge badge-warning"><i class="fa fa-download"> Unduh SPJ</i></span></a>

                                    <?php } ?>
                                </center>
                            </div>
                        </div>
                        <!-- /.row -->
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="list-unstyled timeline">
                                    <li>
                                        <div class="block">
                                            <div class="tags">
                                                <a href="" class="tag">
                                                    <span>Dibuat</span>
                                                </a>
                                            </div>

                                            <div class="block_content">
                                                <h2 class="title">
                                                    <b><i>Pengajuan dibuat oleh KPA <?= $l['nama']; ?></i></b>
                                                </h2>
                                                <div class="byline">
                                                    <span>Tanggal pengajuan</span>
                                                </div>
                                                <p>Pada : <?= $data['at']; ?></p>
                                            </div>

                                        </div>
                                    </li>
                                    <li>
                                        <div class="block">
                                            <div class="tags">
                                                <a href="" class="tag">
                                                    <span>Diverifikasi</span>
                                                </a>
                                            </div>
                                            <?php while ($vv = mysqli_fetch_assoc($veral)) { ?>
                                                <div class="block_content">
                                                    <h2 class="title">
                                                        <b><i>Diverifikasi oleh <?= $vv['user']; ?></i></b>
                                                    </h2>
                                                    <div class="byline">
                                                        <span>Waktu verifikasi</span>
                                                    </div>
                                                    <p class="excerpt">Pada : <span><?= $vv['tgl_verval']; ?></span>
                                                    </p>
                                                </div>
                                            <?php }; ?>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="block">
                                            <div class="tags">
                                                <a href="" class="tag">
                                                    <span>Disetujui</span>
                                                </a>
                                            </div>
                                            <?php while ($app = mysqli_fetch_assoc($apr)) { ?>
                                                <div class="block_content">
                                                    <h2 class="title">
                                                        <b><i>Disetujui oleh <?= $app['user']; ?></i></b>
                                                    </h2>
                                                    <div class="byline">
                                                        <span>Waktu persetujuan</span>
                                                    </div>
                                                    <p class="excerpt">Pada : <span><?= $app['tgl_apr']; ?></span>
                                                    </p>
                                                </div>
                                            <?php }; ?>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="block">
                                            <div class="tags">
                                                <a href="" class="tag">
                                                    <span>Dicairkan</span>
                                                </a>
                                            </div>
                                            <?php while ($acc = mysqli_fetch_assoc($cair)) { ?>
                                                <div class="block_content">
                                                    <h2 class="title">
                                                        <b><i>Dicairkan oleh <?= $acc['kasir']; ?></i></b>
                                                    </h2>
                                                    <div class="byline">
                                                        <span>Waktu pencairan</span>
                                                    </div>
                                                    <p class="excerpt">Pada : <span><?= $acc['tgl_cair']; ?></span><br>
                                                        Nominal : <span><?= rupiah($acc['nominal_cair']); ?></span></p>
                                                </div>
                                            <?php }; ?>
                                        </div>
                                    </li>
                                </ul>
                                <div class="table-responsive">
                                    <table id="datatable2" class="table table-striped table-bordered table-sm" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode RAB</th>
                                                <th>Periode</th>
                                                <th>PJ</th>
                                                <th>Nominal</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            if ($data['cair'] == 1) {
                                                $dt_bos = mysqli_query($conn, "SELECT * FROM realis WHERE kode_pengajuan = '$kode_p' AND tahun = '$tahun_ajaran' ");
                                                $tt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS tot FROM realis WHERE kode_pengajuan = '$kode_p' AND tahun = '$tahun_ajaran' "));
                                            } else {
                                                $dt_bos = mysqli_query($conn, "SELECT * FROM real_sm WHERE kode_pengajuan = '$kode_p' AND tahun = '$tahun_ajaran' ");
                                                $tt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS tot FROM real_sm WHERE kode_pengajuan = '$kode_p' AND tahun = '$tahun_ajaran' "));
                                            }
                                            while ($a = mysqli_fetch_assoc($dt_bos)) { ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $a['kode'] ?></td>
                                                    <td><?= $bulan[$a['bulan']] . ' ' . $a['tahun'] ?></td>
                                                    <td><?= $a['pj'] ?></td>
                                                    <td><?= rupiah($a['nominal']) ?></td>
                                                    <td><?= $a['ket'] ?></td>
                                                    <!-- <td>
                                            <a onclick="return confirm('Yakin akan dihapus ?. Menghapus data ini akan menghapus data realisasi juga')" href="<?= 'hapus.php?kd=rab&id=' . $a['id_realis']; ?>"><span class="fa fa-trash-o text-danger"> Hapus</span></a>
                                        </td> -->
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                                <th colspan="4">SUB JUMLAH</th>
                                                <th colspan="2"><?= rupiah($tt['tot']) ?></th>
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