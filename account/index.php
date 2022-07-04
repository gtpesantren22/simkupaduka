<?php
include 'head.php';

$tot = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jm FROM bos WHERE tahun = '$tahun_ajaran' "));
$tot2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jm FROM pesantren WHERE tahun = '$tahun_ajaran' "));
$tot3 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(sisa) AS jm FROM real_sisa WHERE tahun = '$tahun_ajaran' "));
$tot4 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jm FROM pembayaran WHERE tahun = '$tahun_ajaran' "));

$kas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(IF( ket = 'masuk', nominal, 0)) AS masuk, SUM(IF( ket = 'keluar', nominal, 0)) AS keluar FROM kas WHERE tahun = '$tahun_ajaran' "));

$msk = $tot['jm'] + $tot2['jm'] + $tot3['jm'] + $tot4['jm'];
$sld = $kas['masuk'] - $kas['keluar'];

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
        <div class="top_tiles">
          <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 ">
            <div class="tile-stats">
              <div class="count"><?= rupiah($msk); ?></div>
              <h3>Pemasukan</h3>
              <p><a href="masuk.php">Lihat rincian data <i class="fa fa-arrow-right"></i></a></p>
            </div>
          </div>
          <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 ">
            <div class="tile-stats">
              <div class="count"><?= rupiah($msk - $sld); ?></div>
              <h3>Pengeluaran</h3>
              <p><a href="#">Lihat rincian data <i class="fa fa-arrow-right"></i></a></p>
            </div>
          </div>
          <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 ">
            <div class="tile-stats">
              <div class="count"><?= rupiah($sld); ?></div>
              <h3>Saldo</h3>
              <p><a href="masuk_saldo.php">Lihat rincian data <i class="fa fa-arrow-right"></i></a></p>
            </div>
          </div>
        </div>

        <!-- <center>
          <h1>BIRO KEUANGAN</h1>
          <h3>PONPES DARUL LUGHAH WAL KAROMAH</h3>
        </center> -->
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