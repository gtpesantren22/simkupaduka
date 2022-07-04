<?php
require 'atas.php';


$l = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$kol' AND tahun = '$tahun_ajaran' "));

$data = mysqli_query($conn, "SELECT jenis, nama, kode, total, IF(jenis = 'A', 'A. Belanja Barang', IF(jenis = 'B', 'B. Langganan Daya dan Jasa', IF(jenis = 'C', 'C. Belanja Kegiatan','D. Umum'))) as nm_jenis, COUNT(jenis) as jml, SUM(total) as tot FROM rab WHERE lembaga = '$kol' AND tahun = '$tahun_ajaran'
GROUP BY jenis");

$rab = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) as jml FROM rab WHERE lembaga = '$kol' AND tahun = '$tahun_ajaran' "));
$real = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as nom FROM realis WHERE lembaga = '$kol' AND tahun = '$tahun_ajaran' "));
$sisa = $rab['jml'] - $real['nom'];
$pesern = round(($real['nom'] / $rab['jml']) * 100, 0);
if ($pesern >= 0 && $pesern <= 25) {
  $bg = 'progress-bar-success';
} elseif ($pesern >= 26 && $pesern <= 50) {
  $bg = 'progress-bar-primary';
} elseif ($pesern >= 51 && $pesern <= 75) {
  $bg = 'progress-bar-warning';
} elseif ($pesern >= 76 && $pesern <= 100) {
  $bg = 'progress-bar-danger';
}

$jns = mysqli_query($conn, "SELECT kode, jenis, bidang, IF(jenis = 'A', 'A. Belanja Barang', IF(jenis = 'B', 'B. Langganan Daya dan Jasa', IF(jenis = 'C', 'C. Belanja Kegiatan','D. Umum'))) as nm_jenis, COUNT(jenis) as jml, SUM(total) as tot FROM rab WHERE lembaga = '$kol' AND tahun = '$tahun_ajaran' GROUP BY jenis ");

$no = 1;
?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      RAB
      <small>Rencana Belanja</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">RAB</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-lg-6 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <h3><?= rupiah($rab['jml']) ?></h3>
            <p>RAB Saya</p>
          </div>
          <div class="icon">
            <i class="ion ion-bag"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
        <div class="table-responsive">
          <table class="table table-striped">
            <?php while ($dt = mysqli_fetch_assoc($data)) { ?>
              <tr>
                <th><?= $dt['nm_jenis'] ?></th>
                <th><?= $dt['jml'] ?> item</th>
                <th><?= rupiah($dt['tot']) ?></th>
              </tr>
            <?php } ?>
          </table>
        </div><!-- /.col -->
      </div><!-- ./col -->
      <div class="col-lg-6 col-xs-6">
        <div class="box box-default">
          <div class="box-header with-border">
            <i class="fa fa-bullhorn"></i>
            <h3 class="box-title">Informasi RAB</h3>
          </div><!-- /.box-header -->
          <div class="box-body">

            <div class="callout callout-warning">
              <h4>PENTING !</h4>
              <p>This is a yellow callout.</p>
            </div>


            <!-- <button class="btn btn-primary" data-toggle="modal" data-target="#tambah_bos"><i class="fa fa-plus-circle"></i> Input RAB</button>
            <button class="btn btn-danger pull-right" data-toggle="modal" data-target="#komodo"><i class="fa fa-trash-o"></i> Kosongkan RAB</button> -->
            <button class="btn btn-success btn-block"><i class="fa fa-download"></i> Export to Excel</button><br>
            <b>Pemakaian</b>
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
            <h3 class="box-title">Data Table With Full Features</h3>
          </div><!-- /.box-header -->
          <div class="box-body">
            <div class="table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                  <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                    <th>No</th>
                    <th>Kode</th>
                    <th>Barang/Kegiatan</th>
                    <th>Rencana</th>
                    <th>Bagian</th>
                    <th>Anggaran RAB</th>
                  </tr>
                </thead>
                <?php
                while ($ls_jns = mysqli_fetch_assoc($jns)) {
                  $jenis = $ls_jns['jenis'] ?>
                  <thead>
                    <tr style="color: white; background-color: darkkhaki; font-weight: bold;">
                      <th colspan="8"><?= $ls_jns['nm_jenis'] ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $dt1 = mysqli_query($conn, "SELECT * FROM rab WHERE jenis = '$jenis' AND lembaga = '$kol' AND tahun = '$tahun_ajaran' ");
                    $dt2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) AS tt FROM rab WHERE jenis = '$jenis' AND lembaga = '$kol' AND tahun = '$tahun_ajaran' "));

                    while ($r1 = mysqli_fetch_assoc($dt1)) {
                      $kd = $r1['kode'];
                      $kdb = $r1['bidang'];
                      $nmb = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM bidang WHERE kode = '$kdb' AND tahun = '$tahun_ajaran' "));
                    ?>
                      <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $r1['kode'] ?></td>
                        <td><?= $r1['nama'] ?></td>
                        <td><?= $r1['rencana'] ?></td>
                        <td><?= $nmb['nama'] ?></td>
                        <td><?= rupiah($r1['total']) ?></td>
                        <!-- <td><a onclick="return confirm('Yakin akan dihapus ?. Menghapus data ini akan menghapus data realisasi juga')" href="<?= 'hapus.php?kd=rab&id=' . $r1['id_rab']; ?>"><button class="btn btn-xs btn-danger"><i class="fa fa-trash-o"></i> Hapus</button></a></td> -->

                      </tr>
                    <?php } ?>
                    <tr>
                      <th colspan="5">TOTAL</th>
                      <th><?= rupiah($dt2['tt']) ?></th>
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

<!-- Modal Tambah Data -->
<div class="modal fade" id="tambah_bos" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Tambah Data Belanja</h4>
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
      </div>
      <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left input_mask" action="" method="post">
        <div class="modal-body">
          <div class="item form-group">
            <label for="tahun" class="col-form-label col-md-3 col-sm-3 label-align">Pilih Lembaga <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 ">
              <input type="text" id="first-name" name="lembaga" disabled class="form-control" value="<?= $l['kode'] . '. ' . $l['nama'] ?>">
            </div>
          </div>
          <div class="item form-group">
            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Bidang/bagian <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 ">
              <select name="bidang" class="form-control" id="" required>
                <option value=""> -pilih bidang- </option>
                <?php
                $qr2 = mysqli_query($conn, "SELECT * FROM bidang WHERE tahun = '$tahun_ajaran'");
                while ($a2 = mysqli_fetch_assoc($qr2)) { ?>
                  <option value="<?= $a2['kode'] ?>"><?= $a2['kode'] ?>. <?= $a2['nama'] ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="item form-group">
            <label for="tahun" class="col-form-label col-md-3 col-sm-3 label-align">Pilih Jenis Belanja <span class="required">*</span></label>
            <div class="col-md-6 col-sm-6 ">
              <select name="jenis" id="" required class="form-control">
                <option value=""> -- pilih jenis -- </option>
                <option value="A"> A. Belanja Barang </option>
                <option value="B"> B. Langganan Daya dan Jasa </option>
                <option value="C"> C. Belanja Kegiatan </option>
                <option value="D"> D. Umum </option>
              </select>
            </div>
          </div>
          <div class="item form-group">
            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nama Barang/Kegiatan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 ">
              <input type="text" id="first-name" name="nama" required="required" class="form-control ">
            </div>
          </div>
          <div class="item form-group">
            <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Rencana Waktu <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 ">
              <select name="rencana" id="" required class="form-control">
                <option value=""> -- pilih waktu -- </option>
                <option value="Semester 1"> Semester 1 </option>
                <option value="Semester 2"> Semester 2 </option>
                <option value="Semester 1&2"> Semester 1&2 </option>
              </select>
            </div>
          </div>
          <div class="item form-group">
            <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">QTY/Satuan <span class="required">*</span></label>
            <div class="col-md-3 col-sm-6 ">
              <input id="middle-name" class="form-control" type="number" name="qty" required>
            </div>
            <div class="col-md-3 col-sm-6 ">
              <input id="middle-name" class="form-control" type="text" name="satuan" required placeholder="ex : rim,pack,pcs,dll">
            </div>
          </div>
          <div class="item form-group">
            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Harga Satuan <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 ">
              <input type="text" class="form-control" id="uang" name="harga_satuan" required>
            </div>

          </div>
          <div class="item form-group">
            <label for="tahun" class="col-form-label col-md-3 col-sm-3 label-align">Tahun Ajaran<span class="required">*</span></label>
            <div class="col-md-6 col-sm-5 ">
              <select name="tahun" id="" required class="form-control">
                <option value=""> -- pilih tahun -- </option>
                <option value="2021/2022">2021/2022</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" name="save" class="btn btn-success">Simpan data</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modalksoosong -->
<!-- Modal -->
<div class="modal fade" id="komodo" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Kosongkan RAB Saya</h5>
      </div>
      <form action="kosong.php" method="post">
        <input type="hidden" name="lm" value="<?= $kol ?>">
        <div class="modal-body">
          <h4 style="color: red; font-weight: bold;">PENTING !</h4>
          <span style="color: red; font-weight: bold;">Tombol ini akan mengahpus seluruh data RAB serta Realisasi anda. Mohon diperiksa kembali sebelum mengkosonginya. !</span>
          <h4>Yakin akan dikosongi ?</h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" name="delkos" class="btn btn-danger">Ya! Kosongkan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
include 'bawah.php';

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

  $ss = mysqli_query($conn, "INSERT INTO rab VALUES ('$id', '$lembaga','$bidang','$jenis','$kode', '$nama','$rencana','$qty','$satuan','$harga_satuan','$total','$tahun_ajaran')");
  if ($ss) { ?>
    <script>
      Swal.fire({
        title: 'Berhasil',
        text: 'RAB berhasil ditambahkan',
        icon: 'success',
        showConfirmButton: false
      });
      var millisecondsToWait = 1000;
      setTimeout(function() {
        document.location.href = "rab.php"
      }, millisecondsToWait);
    </script>

<?php    } else {
    echo "DATA TAK MAU MASUK";
  }
}


?>