<?php
include 'head.php';

$kode = $_GET['lm'];
$l = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$kode' AND tahun = '$tahun_ajaran' "));
$no = 1;
$jns = mysqli_query($conn, "SELECT jenis, IF(jenis = 'A', 'A. Belanja Barang', IF(jenis = 'B', 'B. Langganan Daya dan Jasa', IF(jenis = 'C', 'C. Belanja Kegiatan','D. Umum'))) as nm_jenis, COUNT(jenis) as jml, SUM(total) as tot FROM rab WHERE lembaga = '$kode' AND tahun = '$tahun_ajaran' GROUP BY jenis ");
?>
<!-- Datatables -->
<link href="../main/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
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
                        <h2><i class="fa fa-bars"></i> Realisasi RAB <small>lembaga</small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <center>
                            <h3>
                                <small class=""><u>REALISASI RAB <?= strtoupper($l['nama']) ?></u></small>
                            </h3>
                        </center>
                        <br>
                        <!-- info row -->
                        <div class="row invoice-info">
                            <div class="col-sm-1 invoice-col">
                                <address>
                                    <strong>Lembaga</strong><br>
                                    <strong>Pelakasana</strong><br>
                                    <strong>PJ/HP</strong><br>
                                    <strong>Pelaksanaan</strong>
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-3 invoice-col">
                                <address>
                                    <strong>: <?= $l['nama'] ?></strong><br>
                                    <strong>: <?= $l['pelaksana'] ?></strong><br>
                                    <strong>: <?= $l['pj'] . ' / ' . $l['hp'] ?></strong><br>
                                    <strong>: <?= $l['waktu'] ?></strong>
                                </address>
                                <hr>
                                <address>
                                    <button class="btn btn-primary btn-sm"><i class="fa fa-download"></i> Download
                                        Excel</button><br>
                                    <a href="real.php"><button class="btn btn-warning btn-sm"><i
                                                class="fa fa-chevron-circle-left"></i> Kembali</button></a>
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-8 invoice-col">
                                <address>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <?php
                                            $trb = mysqli_fetch_assoc(mysqli_query($conn, "SELECT 
                                        SUM(IF( jenis = 'A', total, '0')) AS A, 
                                        SUM(IF( jenis = 'B', total, '0')) AS B, 
                                        SUM(IF( jenis = 'C', total, '0')) AS C, 
                                        SUM(IF( jenis = 'D', total, '0')) AS D, 
                                        SUM(total) AS T 
                                        FROM rab WHERE lembaga = '$kode' AND tahun = '$tahun_ajaran' "));

                                            $rl = mysqli_fetch_assoc(mysqli_query($conn, "SELECT 
                                        SUM(IF( jenis = 'A', nominal, '0')) AS A, 
                                        SUM(IF( jenis = 'B', nominal, '0')) AS B, 
                                        SUM(IF( jenis = 'C', nominal, '0')) AS C, 
                                        SUM(IF( jenis = 'D', nominal, '0')) AS D, 
                                        SUM(nominal) AS T 
                                        FROM realis WHERE lembaga = '$kode' AND tahun = '$tahun_ajaran' "));
                                            ?>
                                            <tr style="color: white; background-color: purple; font-weight: bold;">
                                                <th>Jenis Belanja</th>
                                                <th>Jml RAB</th>
                                                <th>Terpakai</th>
                                                <th colspan="2">Sisa</th>
                                            </tr>
                                            <tr>
                                                <th>A. Belanja Barang</th>
                                                <th>: <?= rupiah($trb['A']) ?></th>
                                                <th>: <?= rupiah($rl['A']) ?></th>
                                                <th>: <?= rupiah($trb['A'] - $rl['A']) ?></th>
                                                <th>(<?= round((($trb['A'] - $rl['A']) / $trb['A']) * 100, 0) ?> %)</th>
                                            </tr>
                                            <tr>
                                                <th>B. Tagihan & Jasa</th>
                                                <th>: <?= rupiah($trb['B']) ?></th>
                                                <th>: <?= rupiah($rl['B']) ?></th>
                                                <th>: <?= rupiah($trb['B'] - $rl['B']) ?></th>
                                                <th>(<?= round((($trb['B'] - $rl['B']) / $trb['B']) * 100, 0) ?> %)</th>
                                            </tr>
                                            <tr>
                                                <th>C. Belanja Kegiatan</th>
                                                <th>: <?= rupiah($trb['C']) ?></th>
                                                <th>: <?= rupiah($rl['C']) ?></th>
                                                <th>: <?= rupiah($trb['C'] - $rl['C']) ?></th>
                                                <th>(<?= round((($trb['C'] - $rl['C']) / $trb['C']) * 100, 0) ?> %)</th>
                                            </tr>
                                            <tr>
                                                <th>D. Umum</th>
                                                <th>: <?= rupiah($trb['D']) ?></th>
                                                <th>: <?= rupiah($rl['D']) ?></th>
                                                <th>: <?= rupiah($trb['D'] - $rl['D']) ?></th>
                                                <th>(<?= round((($trb['D'] - $rl['D']) / $trb['D']) * 100, 0) ?> %)</th>
                                            </tr>
                                            <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                                <th>Total RAB</th>
                                                <th>: <?= rupiah($trb['T']) ?></th>
                                                <th>: <?= rupiah($rl['T']) ?></th>
                                                <th>: <?= rupiah($trb['T'] - $rl['T']) ?></th>
                                                <th>(<?= round((($trb['T'] - $rl['T']) / $trb['T']) * 100, 0) ?> %)</th>
                                            </tr>
                                        </table>
                                    </div>
                                </address>
                            </div>
                        </div>
                        <!-- /.row -->
                        <hr>
                        <div class="table-responsive">
                            <table id="datatable" class="table countries_list" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Barang/Kegiatan</th>
                                        <th>Anggaran RAB</th>
                                        <th>Realiasasi</th>
                                        <th>Sisa</th>
                                        <th>Pemakaian (%)</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $dt1 = mysqli_query($conn, "SELECT * FROM rab WHERE lembaga = '$kode' AND tahun = '$tahun_ajaran' ");
                                    $dt2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) AS tt FROM rab WHERE  lembaga = '$kode' AND tahun = '$tahun_ajaran' "));
                                    $dt3 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS tt FROM realis WHERE  lembaga = '$kode' AND tahun = '$tahun_ajaran' GROUP BY lembaga "));

                                    while ($r1 = mysqli_fetch_assoc($dt1)) {
                                        $kd = $r1['kode'];
                                        $rs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as nom FROM realis WHERE kode = '$kd' AND tahun = '$tahun_ajaran' GROUP BY kode "));
                                        $sisa = $r1['total'] - $rs['nom'];
                                        $prc = round(($rs['nom'] / $r1['total']) * 100, 0);
                                        if ($prc >= 0 && $prc <= 25) {
                                            $bg = 'bg-success';
                                        } elseif ($prc >= 26 && $prc <= 50) {
                                            $bg = 'bg-info';
                                        } elseif ($prc >= 51 && $prc <= 75) {
                                            $bg = 'bg-warning';
                                        } elseif ($prc >= 76 && $prc <= 100) {
                                            $bg = 'bg-danger';
                                        }
                                    ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><a href="<?= 'real_add.php?kode=' . $r1['kode'] ?>"><?= $r1['nama'] ?></a>
                                        </td>
                                        <td><?= rupiah($r1['total']) ?></td>
                                        <td><?= rupiah($rs['nom']) ?></td>
                                        <td><?= rupiah($sisa) ?></td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated <?= $bg ?>"
                                                    role="progressbar" style="width: <?= $prc ?>%"
                                                    aria-valuenow="<?= $prc ?>" aria-valuemin="0" aria-valuemax="100">
                                                    <?= $prc ?>%</div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    <!-- <tr>
                                        <th colspan="2">TOTAL</th>
                                        <th><?= rupiah($dt2['tt']) ?></th>
                                        <th><?= rupiah($dt3['tt']) ?></th>
                                        <th></th>
                                        <th colspan="2">TOTAL</th>
                                    </tr> -->
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
<script src="../main/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
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