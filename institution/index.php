<?php
require 'atas.php';
$rab = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) as jml FROM rab WHERE lembaga = '$kol' AND tahun = '$tahun_ajaran' "));
$real = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as jml FROM realis WHERE lembaga = '$kol' AND tahun = '$tahun_ajaran' "));
$pj = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pengajuan WHERE lembaga = '$kol' AND tahun = '$tahun_ajaran' ORDER BY no_urut DESC LIMIT 1 "));
$spj = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM spj WHERE lembaga = '$kol' AND tahun = '$tahun_ajaran' ORDER BY no_urut DESC LIMIT 1 "));

$A = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as jml FROM realis WHERE lembaga = '$kol' AND jenis = 'A' AND tahun = '$tahun_ajaran' "));
$B = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as jml FROM realis WHERE lembaga = '$kol' AND jenis = 'B' AND tahun = '$tahun_ajaran' "));
$C = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as jml FROM realis WHERE lembaga = '$kol' AND jenis = 'C' AND tahun = '$tahun_ajaran' "));
$D = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as jml FROM realis WHERE lembaga = '$kol' AND jenis = 'D' AND tahun = '$tahun_ajaran' "));

$hasilA = $A['jml'] == null ? '0' : $A['jml'];
$hasilB = $B['jml'] == null ? '0' : $B['jml'];
$hasilC = $C['jml'] == null ? '0' : $C['jml'];
$hasilD = $D['jml'] == null ? '0' : $D['jml'];
?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Dashboard
      <small>Control panel</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Dashboard</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
            <h2><?= rupiah($rab['jml']) ?></h2>
            <p>RAB Saya</p>
          </div>
          <div class="icon">
            <i class="ion ion-bag"></i>
          </div>
          <a href="rab.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div><!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <h2><?= rupiah($real['jml']) ?></h2>
            <p>Realisasi</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
          <a href="real_data.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div><!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-purple">
          <div class="inner">
            <!-- <h3>Pengajuan</h3> -->
            <p>
              Verifikasi : <?= $pj['verval'] == 1 ? "<span class='label label-success'><i class='fa fa-check'></i> sudah</span>" : "<span class='label label-danger'><i class='fa fa-times'></i> belum</span>"; ?>
            </p>
            <p>
              Persetujuan : <?= $pj['apr'] == 1 ? "<span class='label label-success'><i class='fa fa-check'></i> sudah</span>" : "<span class='label label-danger'><i class='fa fa-times'></i> belum</span>"; ?>
            </p>
            <p>
              Pencairan : <?= $pj['cair'] == 1 ? "<span class='label label-success'><i class='fa fa-check'></i> sudah</span>" : "<span class='label label-danger'><i class='fa fa-times'></i> belum</span>"; ?>
            </p>
          </div>
          <div class="icon">
            <i class="ion ion-clipboard"></i>
          </div>
          <a href="pengajuan.php" class="small-box-footer">Pengajuan terakhir <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div><!-- ./col -->
      <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
          <div class="inner">
            <h2>SPJ Terakhir</h2>
            <p>Status :
              <?php if ($spj['stts'] == 0) { ?>
                <span class="label label-danger"><i class="fa fa-times"></i> belum upload</span>
              <?php } else if ($spj['stts'] == 1) { ?>
                <button class="btn btn-warning btn-xs"><i class="fa fa-spinner fa-refresh-animate"></i>
                  proses verifikasi</button>
              <?php } else { ?>
                <span class="label label-success"><i class="fa fa-check"></i> sudah selesai</span>
              <?php } ?>
            </p>
          </div>
          <div class="icon">
            <i class="ion ion-email"></i>
          </div>
          <a href="spj.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div><!-- ./col -->
    </div><!-- /.row -->
    <div class="row">
      <div class="col-md-7">
        <!-- BAR CHART -->
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Bar Chart</h3>
            <div class="box-tools pull-right">
              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>
          <div class="box-body chart-responsive">
            <div class="chart" id="bar-chart2" style="height: 300px;"></div>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
      </div>
      <div class="col-md-5">
        <ul class="timeline">
          <?php
          $dt = mysqli_query($conn, "SELECT * FROM info WHERE tahun = '$tahun_ajaran' ORDER BY tgl DESC LIMIT 1");
          while ($row = mysqli_fetch_assoc($dt)) {
            $tg = $row['tgl']; ?>
            <li>
              <i class="fa fa-envelope bg-blue"></i>
              <div class="timeline-item">
                <span class="time"><i class="fa fa-clock-o"></i> <?= date('H:i', strtotime($tg)) ?></span>
                <h3 class="timeline-header"><a href="#"><?= $row['judul'] ?></a></h3>
                <div class="timeline-body">
                  <?= $row['isi'] ?>
                </div>
                <div class="timeline-footer">
                  <span class="label label-success">by : <?= $row['uploader'] ?></span>
                  <span class="label label-warning"><?= date('d F Y', strtotime($tg)) ?></span>
                </div>
              </div>
            </li>
          <?php } ?>
          <!-- END timeline item -->
        </ul>
      </div>
    </div>
  </section><!-- /.content -->
</div>

<!-- jQuery 2.1.4 -->
<script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="plugins/morris/morris.min.js"></script>

<script>
  $(function() {
    // "use strict";
    //BAR CHART
    var bar = new Morris.Bar({
      element: 'bar-chart2',
      resize: true,
      data: [{
          y: 'A. Belanja Barang',
          a: <?= $hasilA; ?>
        },
        {
          y: 'B. Langganan&Jasa',
          a: <?= $hasilB; ?>
        },
        {
          y: 'C. Belanja Kegiatan',
          a: <?= $hasilC; ?>
        },
        {
          y: 'D. Umum',
          a: <?= $hasilD; ?>
        }
      ],
      barColors: ['#00a65a'],
      xkey: 'y',
      ykeys: ['a'],
      labels: ['Total belanja'],
      hideHover: 'auto'
    });
  });
</script>
<?php
require 'bawah.php'
?>