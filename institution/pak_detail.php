<?php
require 'atas.php';

$kode_pak = $_GET['kode'];
$l = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$kol' AND tahun = '$tahun_ajaran' "));

$data = mysqli_query($conn, "SELECT jenis, nama, kode, total, IF(jenis = 'A', 'A. Belanja Barang', IF(jenis = 'B', 'B. Langganan Daya dan Jasa', IF(jenis = 'C', 'C. Belanja Kegiatan','D. Umum'))) as nm_jenis, COUNT(jenis) as jml, SUM(total) as tot FROM rab WHERE lembaga = '$kol'  AND tahun = '$tahun_ajaran'
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

$no = 1;

$tgl = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM akses WHERE lembaga = 'umum' "));
$skr = date('Y-m-d');
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
        <?php if ($skr >= $tgl['login'] && $skr <= $tgl['disposisi']) { ?>
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
                <!-- <div class="table-responsive">
                    <table class="table table-striped">
                        <?php while ($dt = mysqli_fetch_assoc($data)) { ?>
                        <tr>
                            <th><?= $dt['nm_jenis'] ?></th>
                            <th><?= $dt['jml'] ?> item</th>
                            <th><?= rupiah($dt['tot']) ?></th>
                        </tr>
                        <?php } ?>
                    </table>
                </div> -->
            </div><!-- ./col -->
            <div class="col-lg-6 col-xs-6">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <i class="fa fa-bullhorn"></i>
                        <h3 class="box-title">Informasi RAB</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <button class="btn btn-success btn-block"><i class="fa fa-download"></i> Download
                            RAB</button><br>
                        <b>Pemakaian</b>
                        <div class="progress active">
                            <div class="progress-bar <?= $bg ?> progress-bar-striped" role="progressbar"
                                aria-valuenow="<?= $pesern ?>" aria-valuemin="0" aria-valuemax="100"
                                style="width: <?= $pesern ?>%"><?= $pesern ?>%
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
                        <h3 class="box-title">Data RAB Saya</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="example1_bst" class="table table-bordered table-striped">
                                <thead>
                                    <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Barang/Kegiatan</th>
                                        <th>QTY</th>
                                        <th>Harga Satuan</th>
                                        <th>Total</th>
                                        <th>Terpakai</th>
                                        <td>#</td>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                        $dt1 = mysqli_query($conn, "SELECT * FROM rab WHERE  lembaga = '$kol' AND tahun = '$tahun_ajaran' ");
                                        $dt2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) AS tt FROM rab WHERE  lembaga = '$kol' AND tahun = '$tahun_ajaran' "));

                                        while ($r1 = mysqli_fetch_assoc($dt1)) {
                                            $kd = $r1['kode'];
                                            $pakai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT IFNULL (SUM(nominal),0) AS jml, IFNULL (SUM(vol),0) AS qty FROM realis WHERE kode = '$kd' AND tahun = '$tahun_ajaran' "));
                                            $pakaiSm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT IFNULL (SUM(nominal),0) AS jml, IFNULL (SUM(vol),0) AS qty FROM real_sm WHERE kode = '$kd' AND tahun = '$tahun_ajaran' "));

                                            $sisa = $pakai['jml'] / $r1['total'] * 100;
                                        ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $r1['kode'] ?></td>
                                        <td><?= $r1['nama'] ?></td>
                                        <td><?= $r1['qty'] ?></td>
                                        <td><?= rupiah($r1['harga_satuan']) ?></td>
                                        <td><?= rupiah($r1['total']) ?></td>
                                        <td class="text-success">
                                            <?= $pakaiSm['qty'] > 0 ? "<span class='label label-warning'>dalam pengajuan</span>" : round($sisa, 1) . '%' ?>
                                        </td>
                                        <td>
                                            <?php if ($pakaiSm['qty'] > 0 || $sisa == 100) {
                                                    } elseif ($pakaiSm['qty'] < 1 && $pakai['qty'] < 1) { ?>
                                            <a onclick="return confirm('Yakin akan dihapus ?. Menghapus data ini akan menghapus data realisasi juga')"
                                                href="<?= 'pak_set.php?kd=del&pak=' . $kode_pak . '&id=' . $r1['id_rab']; ?>"><button
                                                    class="btn btn-xs btn-danger"><i class="fa fa-trash-o"></i>
                                                    Hapus</button></a>
                                            <a href="<?= 'pak_edit.php?kd=' . $r1['id_rab'] . '&pak=' . $kode_pak; ?>"><button
                                                    class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i>
                                                    Edit</button></a>
                                            <?php } elseif ($pakaiSm['qty'] < 1 && $pakai['qty'] > 0) { ?>
                                            <a href="<?= 'pak_edit.php?kd=' . $r1['id_rab'] . '&pak=' . $kode_pak; ?>"><button
                                                    class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i>
                                                    Edit</button></a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <?php } ?>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5">TOTAL</th>
                                        <th><?= rupiah($dt2['tt']) ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>

            <div class="col-md-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Data RAB yang di PAK</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="callout callout-info">
                            <?php
                                $dt1 = mysqli_query($conn, "SELECT a.*, b.nama FROM pak_detail a JOIN rab b ON a.kode_rab=b.kode WHERE a.kode_pak = '$kode_pak' AND a.tahun = '$tahun_ajaran' ");
                                $dt2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) AS tt FROM pak_detail WHERE kode_pak = '$kode_pak' AND tahun = '$tahun_ajaran' "));
                                ?>
                            <h4>TOTAL : <?= rupiah($dt2['tt']); ?></h4>
                        </div>
                        <div class="table-responsive">
                            <table id="example2_bst" class="table table-bordered table-striped">
                                <thead>
                                    <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                        <!-- <th>No</th> -->
                                        <th>Kode</th>
                                        <th>Barang/Kegiatan</th>
                                        <th>QTY</th>
                                        <!-- <th>Harga Satuan</th> -->
                                        <th>Total</th>
                                        <th>Ket</th>
                                        <td>#</td>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                        while ($r1 = mysqli_fetch_assoc($dt1)) {
                                            // $kd = $r1['kode'];
                                            // $pakai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT IFNULL (SUM(nominal),0) AS jml, IFNULL (SUM(vol),0) AS qty FROM realis WHERE kode = '$kd' AND tahun = '$tahun_ajaran' "));
                                            // $pakaiSm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT IFNULL (SUM(nominal),0) AS jml, IFNULL (SUM(vol),0) AS qty FROM real_sm WHERE kode = '$kd' AND tahun = '$tahun_ajaran' "));

                                            $sisa = $pakai['jml'] / $r1['total'] * 100;
                                        ?>
                                    <tr>
                                        <!-- <td><?= $no++; ?></td> -->
                                        <td><?= $r1['kode_rab'] ?></td>
                                        <td><?= $r1['nama'] ?></td>
                                        <td><?= $r1['qty'] . ' x ' . number_format($r1['harga_satuan']) ?></td>
                                        <!-- <td><?= rupiah($r1['harga_satuan']) ?></td> -->
                                        <td><?= number_format($r1['total']) ?></td>
                                        <td class="text-success">
                                            <?= $r1['ket'] == 'hapus' ? "<span class='label label-danger btn-rounded'>hapus</span>" : "<span class='label label-success btn-rounded'>edit</span>" ?>
                                        </td>
                                        <td>
                                            <a onclick="return confirm('Yakin akan dikembalikan ?')"
                                                href="<?= 'pak_set.php?kd=kembali&pak=' . $r1['kode_pak'] . '&id=' . $r1['kode_rab']; ?>"><button
                                                    class="btn btn-xs btn-danger"><i
                                                        class="fa fa-trash-o"></i></button></a>
                                        </td>
                                    </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>

            <div class="col-md-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">RAB Baru yang akan diajukan</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="callout callout-success">
                            <?php
                                $dt1 = mysqli_query($conn, "SELECT a.*, b.nama FROM pak_detail a JOIN rab b ON a.kode_rab=b.kode WHERE a.kode_pak = '$kode_pak' AND a.tahun = '$tahun_ajaran' ");
                                $dt2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) AS tt FROM pak_detail WHERE kode_pak = '$kode_pak' AND tahun = '$tahun_ajaran' "));
                                ?>
                            <h4>TOTAL : <?= rupiah($dt2['tt']); ?></h4>
                        </div>
                        <div class="table-responsive">
                            <table id="example3_bst" class="table table-bordered table-striped">
                                <thead>
                                    <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                        <!-- <th>No</th> -->
                                        <th>Kode</th>
                                        <th>Barang/Kegiatan</th>
                                        <th>QTY</th>
                                        <!-- <th>Harga Satuan</th> -->
                                        <th>Total</th>
                                        <th>Ket</th>
                                        <td>#</td>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                        while ($r1 = mysqli_fetch_assoc($dt1)) {
                                            // $kd = $r1['kode'];
                                            // $pakai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT IFNULL (SUM(nominal),0) AS jml, IFNULL (SUM(vol),0) AS qty FROM realis WHERE kode = '$kd' AND tahun = '$tahun_ajaran' "));
                                            // $pakaiSm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT IFNULL (SUM(nominal),0) AS jml, IFNULL (SUM(vol),0) AS qty FROM real_sm WHERE kode = '$kd' AND tahun = '$tahun_ajaran' "));

                                            $sisa = $pakai['jml'] / $r1['total'] * 100;
                                        ?>
                                    <tr>
                                        <!-- <td><?= $no++; ?></td> -->
                                        <td><?= $r1['kode_rab'] ?></td>
                                        <td><?= $r1['nama'] ?></td>
                                        <td><?= $r1['qty'] . ' x ' . number_format($r1['harga_satuan']) ?></td>
                                        <!-- <td><?= rupiah($r1['harga_satuan']) ?></td> -->
                                        <td><?= number_format($r1['total']) ?></td>
                                        <td class="text-success">
                                            <?= $r1['ket'] == 'hapus' ? "<span class='label label-danger btn-rounded'>hapus</span>" : "<span class='label label-success btn-rounded'>edit</span>" ?>
                                        </td>
                                        <td>
                                            <a onclick="return confirm('Yakin akan dikembalikan ?')"
                                                href="<?= 'pak_set.php?kd=kembali&pak=' . $r1['kode_pak'] . '&id=' . $r1['kode_rab']; ?>"><button
                                                    class="btn btn-xs btn-danger"><i
                                                        class="fa fa-trash-o"></i></button></a>
                                        </td>
                                    </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
        <?php } ?>
    </section><!-- /.content -->
</div>


<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<!-- jQuery 2.1.4 -->
<script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
$(function() {
    $("#example1_bst").DataTable();
    $("#example2_bst").DataTable();
    $("#example3_bst").DataTable();
    $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });
});
</script>
<?php
include 'bawah.php';

?>