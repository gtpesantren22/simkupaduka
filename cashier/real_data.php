<?php
require 'atas.php';

$l = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$kol' "));

$rab = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) as jml FROM rab WHERE lembaga = '$kol' "));
$rls = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as nom FROM realis WHERE lembaga = '$kol' "));
$sisa = $rab['jml'] - $rls['nom'];
$pesern = round(($rls['nom'] / $rab['jml']) * 100, 2);
if ($pesern >= 0 && $pesern <= 25) {
  $bg = 'progress-bar-success';
} elseif ($pesern >= 26 && $pesern <= 50) {
  $bg = 'progress-bar-primary';
} elseif ($pesern >= 51 && $pesern <= 75) {
  $bg = 'progress-bar-warning';
} elseif ($pesern >= 76 && $pesern <= 100) {
  $bg = 'progress-bar-danger';
}

$jns = mysqli_query($conn, "SELECT kode, jenis, bidang, IF(jenis = 'A', 'A. Belanja Barang', IF(jenis = 'B', 'B. Langganan Daya dan Jasa', IF(jenis = 'C', 'C. Belanja Kegiatan','D. Umum'))) as nm_jenis, COUNT(jenis) as jml, SUM(total) as tot FROM rab WHERE lembaga = '$kol' GROUP BY jenis ");

$no = 1;
?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Realiasasi RAB
      <small>Rencana Belanja</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">RAB</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">

      <div class="col-lg-12 col-xs-6">
        <div class="box box-default">
          <div class="box-header with-border">
            <i class="fa fa-bullhorn"></i>
            <h3 class="box-title">Informasi RAB</h3>
          </div><!-- /.box-header -->
          <div class="box-body">

            <div class="row">
              <div class="col-md-4">
                <!-- small box -->
                <div class="info-box">
                  <span class="info-box-icon bg-green"><i class="ion ion-cash"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">RAB Saya</span>
                    <span class="info-box-number"><?= rupiah($rab['jml']) ?></span>
                  </div><!-- /.info-box-content -->
                </div><!-- /.info-box -->
              </div>
              <div class="col-md-4">
                <!-- small box -->
                <div class="info-box">
                  <span class="info-box-icon bg-yellow"><i class="ion ion-bag"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Pemakaian</span>
                    <span class="info-box-number"><?= rupiah($rls['nom']) ?></span>
                  </div><!-- /.info-box-content -->
                </div><!-- /.info-box -->
              </div>
              <div class="col-md-4">
                <!-- small box -->
                <div class="info-box">
                  <span class="info-box-icon bg-red"><i class="ion ion-clipboard"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Sisa</span>
                    <span class="info-box-number"><?= rupiah($sisa) ?></span>
                  </div><!-- /.info-box-content -->
                </div><!-- /.info-box -->
              </div>
            </div>
            <hr>

            <div class="progress active">
              <div class="progress-bar <?= $bg ?> progress-bar-striped" role="progressbar" aria-valuenow="<?= $pesern ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $pesern ?>%"><?= $pesern ?>%
              </div>
            </div>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
      </div><!-- ./col -->

    </div><!-- /.row -->

    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Data Realiasasi RAB</h3>
          </div><!-- /.box-header -->
          <div class="box-body">
            <div class="table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                  <tr style="color: white; background-color: green; font-weight: bold;">
                    <th>No</th>
                    <th>Kode</th>
                    <th>Barang/Kegiatan</th>
                    <th>Bagian</th>
                    <th>Anggaran RAB</th>
                    <th>Realiasasi</th>
                    <th>Sisa</th>
                    <th>Pemakaian (%)</th>
                  </tr>
                </thead>
                <?php
                while ($ls_jns = mysqli_fetch_assoc($jns)) {
                  $jenis = $ls_jns['jenis'] ?>
                  <thead>
                    <tr style="color: white; background-color: goldenrod; font-weight: bold;">
                      <th colspan="8"><?= $ls_jns['nm_jenis'] ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $dt1 = mysqli_query($conn, "SELECT * FROM rab WHERE jenis = '$jenis' AND lembaga = '$kol' AND tahun = '2021/2022' ");
                    $dt2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) AS tt FROM rab WHERE jenis = '$jenis' AND lembaga = '$kol' AND tahun = '2021/2022' "));
                    $dt3 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS tt FROM realis WHERE jenis = '$jenis' AND lembaga = '$kol' AND tahun = '2021/2022' GROUP BY kode "));

                    while ($r1 = mysqli_fetch_assoc($dt1)) {
                      $kd = $r1['kode'];
                      $kdb = $r1['bidang'];
                      $rs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as nom FROM realis WHERE kode = '$kd' GROUP BY kode "));
                      $sisa = $r1['total'] - $rs['nom'];
                      $prc = round(($rs['nom'] / $r1['total']) * 100, 0);
                      if ($prc >= 0 && $prc <= 25) {
                        $bg = 'progress-bar-success';
                      } elseif ($prc >= 26 && $prc <= 50) {
                        $bg = 'progress-bar-primary';
                      } elseif ($prc >= 51 && $prc <= 75) {
                        $bg = 'progress-bar-warning';
                      } elseif ($prc >= 76 && $prc <= 100) {
                        $bg = 'progress-bar-danger';
                      }
                      $nmb = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM bidang WHERE kode = '$kdb' "));
                    ?>
                      <tr>
                        <td><?= $no++; ?></td>
                        <td><a href="<?= 'real_detail.php?kode=' . $r1['kode'] ?>"><?= $r1['kode'] ?></a></td>
                        <td><?= $r1['nama'] ?></td>
                        <td><?= $nmb['nama'] ?></td>
                        <td><?= rupiah($r1['total']) ?></td>
                        <td><?= rupiah($rs['nom']) ?></td>
                        <td><?= rupiah($sisa) ?></td>
                        <td>
                          <div class="progress active">
                            <div class="progress-bar progress-bar-striped <?= $bg ?>" role="progressbar" style="width: <?= $prc ?>%" aria-valuenow="<?= $prc ?>" aria-valuemin="0" aria-valuemax="100"><?= $prc ?>%</div>
                          </div>
                        </td>
                      </tr>
                    <?php } ?>
                    <tr>
                      <th colspan="4">TOTAL</th>
                      <th><?= rupiah($dt2['tt']) ?></th>
                      <th><?= rupiah($dt3['tt']) ?></th>
                      <th></th>
                      <th colspan="2">TOTAL</th>
                    </tr>
                  </tbody>
                <?php } ?>
              </table>
            </div>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
      </div>
    </div>
  </section><!-- /.content -->
</div>

<?php

if (isset($_POST['save'])) {

  $id = $uuid;
  $lembaga = $l['kode'];
  $jenis = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['jenis']));
  $bidang = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['bidang']));
  $kode = $lembaga . '.' . $bidang .  '.' . $jenis . '.' . rand();
  $nama = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['nama']));
  $rencana = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['rencana']));
  $qty = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['qty']));
  $satuan = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['satuan']));
  $harga_satuan = htmlspecialchars(mysqli_real_escape_string($conn, preg_replace("/[^0-9]/", "", $_POST['harga_satuan'])));
  $total = $qty * preg_replace("/[^0-9]/", "", $harga_satuan);
  $tahun = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tahun']));

  $sql = mysqli_query($conn, "INSERT INTO rab VALUES ('$id', '$lembaga','$bidang','$jenis','$kode', '$nama','$rencana','$qty','$satuan','$harga_satuan','$total','$tahun')");
  if ($sql) { ?>
    <script>
      $(document).ready(function() {
        Swal.fire({
          position: 'top-end',
          icon: 'success',
          title: 'Kode berhasil tersimpan',
          showConfirmButton: false
        });
        var millisecondsToWait = 1000;
        setTimeout(function() {
          document.location.href = "<?= 'rab_detail.php?kode=' . $lembaga ?>"
        }, millisecondsToWait);

      });
    </script>

<?php    } else {
    echo "DATA TAK MAU MASUK";
  }
}

require 'bawah.php';

?>