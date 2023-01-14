<?php
require 'atas.php';
include '../func_wa.php';

$kode_pak = $_GET['kode'];
$l = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$kol' AND tahun = '$tahun_ajaran' "));

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

$pakde = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pak WHERE kode_pak = '$kode_pak' AND tahun = '$tahun_ajaran' "));

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
            </div><!-- ./col -->
            <div class="col-lg-6 col-xs-6">
                <div class="box box-default">

                    <div class="box-body">
                        <button class="btn btn-success"><i class="fa fa-download"></i> Download RAB</button>
                        <button class="btn btn-danger" data-toggle="modal" data-target="#ajukan"><i
                                class="fa fa-mail-forward"></i> Ajukan PAK ke
                            Bendahara</button>
                        <p>Pemakaian</p>
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
            <?php if ($pakde['status'] === 'belum' || $pakde['status'] === 'ditolak') { ?>
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
            <?php } ?>

            <div class="col-md-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Data RAB yang di PAK</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="callout callout-info">
                            <?php
                                $dt1 = mysqli_query($conn, "SELECT a.*, b.nama FROM pak_detail a JOIN rab b ON a.kode_rab=b.kode WHERE a.kode_pak = '$kode_pak' AND a.tahun = '$tahun_ajaran' ");
                                $dt_pak = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) AS tt FROM pak_detail WHERE kode_pak = '$kode_pak' AND tahun = '$tahun_ajaran' "));
                                ?>
                            <h4>TOTAL : <?= rupiah($dt_pak['tt']); ?></h4>
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

                                            // $sisa = $pakai['jml'] / $r1['total'] * 100;
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
                                            <?php if ($pakde['status'] === 'belum' || $pakde['status'] === 'ditolak') { ?>
                                            <a onclick="return confirm('Yakin akan dikembalikan ?')"
                                                href="<?= 'pak_set.php?kd=kembali&pak=' . $r1['kode_pak'] . '&id=' . $r1['kode_rab']; ?>"><button
                                                    class="btn btn-xs btn-danger"><i
                                                        class="fa fa-trash-o"></i></button></a>
                                            <?php } ?>
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
                                $dt1 = mysqli_query($conn, "SELECT * FROM rab_sm WHERE lembaga = '$kol' AND tahun = '$tahun_ajaran' ");
                                $dt_rab = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) AS tt FROM rab_sm WHERE lembaga = '$kol' AND tahun = '$tahun_ajaran' "));
                                ?>
                            <h4>TOTAL : <?= rupiah($dt_rab['tt']); ?>
                                <?php if ($pakde['status'] === 'belum' || $pakde['status'] === 'ditolak') { ?>
                                <button class="btn btn-warning pull-right btn-sm" data-toggle="modal"
                                    data-target="#tambah_bos"><i class="fa fa-plus"></i> Buat
                                    RAB</button>
                                <?php } ?>
                            </h4>
                        </div>
                        <div class="table-responsive">
                            <table id="example3_bst" class="table table-bordered table-striped">
                                <thead>
                                    <tr style="color: white; background-color: #00A65A; font-weight: bold;">
                                        <!-- <th>No</th> -->
                                        <th>Kode</th>
                                        <th>Barang/Kegiatan</th>
                                        <th>QTY</th>
                                        <!-- <th>Harga Satuan</th> -->
                                        <th>Total</th>
                                        <td>#</td>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php while ($r1 = mysqli_fetch_assoc($dt1)) { ?>
                                    <tr>
                                        <!-- <td><?= $no++; ?></td> -->
                                        <td><?= $r1['kode'] ?></td>
                                        <td><?= $r1['nama'] ?></td>
                                        <td><?= $r1['qty'] . ' x ' . number_format($r1['harga_satuan']) ?></td>
                                        <!-- <td><?= rupiah($r1['harga_satuan']) ?></td> -->
                                        <td><?= number_format($r1['total']) ?></td>
                                        <td>
                                            <?php if ($pakde['status'] === 'belum' || $pakde['status'] === 'ditolak') { ?>
                                            <a onclick="return confirm('Yakin akan dikembalikan ?')"
                                                href="<?= 'pak_set.php?kd=rab&pak=' . $kode_pak . '&id=' . $r1['kode']; ?>"><button
                                                    class="btn btn-xs btn-danger"><i
                                                        class="fa fa-trash-o"></i></button></a>
                                            <?php } ?>
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

<div class="modal fade" id="tambah_bos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Tambah Data Belanja</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left input_mask" action=""
                method="post">
                <div class="modal-body">
                    <div class="item form-group">
                        <label for="tahun" class="col-form-label col-md-3 col-sm-3 label-align">Lembaga <span
                                class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="first-name" name="lembaga" disabled class="form-control"
                                value="<?= $l['kode'] . '. ' . $l['nama'] ?>">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Bidang/bagian <span
                                class="required">*</span>
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
                        <label for="tahun" class="col-form-label col-md-3 col-sm-3 label-align">Pilih Jenis Belanja
                            <span class="required">*</span></label>
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
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nama
                            Barang/Kegiatan <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="first-name" name="nama" required="required" class="form-control ">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Rencana Waktu <span
                                class="required">*</span>
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
                        <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">QTY/Satuan <span
                                class="required">*</span></label>
                        <div class="col-md-3 col-sm-6 ">
                            <input id="middle-name" class="form-control" type="number" name="qty" required>
                        </div>
                        <div class="col-md-3 col-sm-6 ">
                            <input id="middle-name" class="form-control" type="text" name="satuan" required
                                placeholder="ex : rim,pack,pcs,dll">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Harga Satuan <span
                                class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" class="form-control" id="uang" name="harga_satuan" required>
                        </div>

                    </div>
                    <div class="item form-group">
                        <label for="tahun" class="col-form-label col-md-3 col-sm-3 label-align">Tahun Ajaran<span
                                class="required">*</span></label>
                        <div class="col-md-6 col-sm-5 ">
                            <select name="tahun" id="" required class="form-control">
                                <option value=""> -- pilih tahun -- </option>
                                <?php
                                $qr2 = mysqli_query($conn, "SELECT * FROM tahun ");
                                while ($a2 = mysqli_fetch_assoc($qr2)) { ?>
                                <option value="<?= $a2['nama_tahun'] ?>"><?= $a2['nama_tahun'] ?></option>
                                <?php } ?>
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

<div class="modal fade" id="ajukan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Pengajuan PAK</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left input_mask" action=""
                method="post">
                <div class="modal-body">
                    PAK akan diajukan kepada Bendahara. Setelah diajukan RAB ini sudah tidak bisa diganti lagi. <br>
                    Lanjutkan ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="ajukan" class="btn btn-success">Ya. Lanjutkan</button>
                </div>
            </form>
        </div>
    </div>
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
    $total = $qty * $harga_satuan;
    $tahun = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tahun']));

    $pak = $dt_pak['tt'];
    $rb = $dt_rab['tt'];
    $ttl = $rb + $total;
    if ($pak >= $ttl) {
        $ss = mysqli_query($conn, "INSERT INTO rab_sm VALUES ('$id', '$lembaga','$bidang','$jenis','$kode', '$nama','$rencana','$qty','$satuan','$harga_satuan','$total','$tahun_ajaran', NOW() )");
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
    document.location.href = "<?= 'pak_detail.php?kode=' . $kode_pak ?>"
}, millisecondsToWait);
</script>

<?php    } else {
            echo "DATA TAK MAU MASUK";
        }
    } else { ?>
<script>
Swal.fire({
    title: 'Error',
    text: 'Maaf Nominal PAK tidak mencukupi',
    icon: 'error',
    showConfirmButton: false
});
var millisecondsToWait = 2000;
setTimeout(function() {
    document.location.href = "<?= 'pak_detail.php?kode=' . $kode_pak ?>"
}, millisecondsToWait);
</script>

<?php }
}

if (isset($_POST['ajukan'])) {
    $sql = mysqli_query($conn, "UPDATE pak SET status = 'proses' WHERE kode_pak = '$kode_pak' ");

    $psn = '
*INFORMASI PENGAJUAN PAK*

Ada pengajuan baru dari :
    
Lembaga : ' . $l['nama'] . '
Kode PAK : ' . $kode . '

*_dimohon kepada SUB BAG ACCOUNTING untuk segera mengecek nya di https://simkupaduka.ppdwk.com/_*
Terimakasih';

    if ($sql) {
        kirim_group($api_key, '120363040973404347@g.us', $psn);
        kirim_group($api_key, '120363042148360147@g.us', $psn);
        kirim_person($api_key, '082302301003', $psn);

        echo "
        <script>
Swal.fire({
    title: 'Berhasil',
    text: 'PAK berhasil diajukan',
    icon: 'success',
    showConfirmButton: false
});
var millisecondsToWait = 1000;
setTimeout(function() {
    document.location.href = 'pak.php'
}, millisecondsToWait);
</script>
";
    }
}
?>