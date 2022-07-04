<?php
include 'atas.php';

$no = 1;
$kode = $_GET['kode'];
$l = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM rab WHERE kode = '$kode' AND tahun = '$tahun_ajaran' "));
$kalem = $l['lembaga'];
$lem = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$kalem' AND tahun = '$tahun_ajaran' "));
$rel = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as nn FROM realis WHERE kode = '$kode' AND tahun = '$tahun_ajaran' "));

$jns = mysqli_fetch_assoc(mysqli_query($conn, "SELECT jenis, nama, kode, total, rencana, IF(jenis = 'A', 'A. Belanja Barang', IF(jenis = 'B', 'B. Langganan Daya dan Jasa', IF(jenis = 'C', 'C. Belanja Kegiatan','D. Umum'))) as nm_jenis, COUNT(jenis) as jml, SUM(total) as tot FROM rab WHERE kode = '$kode' AND tahun = '$tahun_ajaran' "));

$sisa = $jns['total'] - $rel['nn'];
$prc = round(($rel['nn'] / $jns['total']) * 100, 0);
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
                        <a href="real_data.php"><button class="btn btn-warning pull-right btn-xs"><i class="fa fa-arrow-left"></i> Kembali</button></a>
                    </div><!-- /.box-header -->
                    <div class="box-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <th>Nama Lembaga</th>
                                                <th> : <?= $lem['nama'] ?></th>
                                            </tr>
                                            <tr>
                                                <th>Jenis Belanja</th>
                                                <th> : <?= $jns['nm_jenis'] ?></th>
                                            </tr>
                                            <tr>
                                                <th>Rencana Belanja</th>
                                                <th> : <?= $jns['rencana'] ?></th>
                                            </tr>
                                            <tr>
                                                <th>Kode Item</th>
                                                <th> : <?= $jns['kode'] ?></th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div><!-- /.col -->
                            </div>
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <th>Nama Item</th>
                                                <th> : <?= $jns['nama'] ?></th>
                                            </tr>
                                            <tr>
                                                <th>Anggaran RAB</th>
                                                <th> : <?= rupiah($jns['total']) ?></th>
                                            </tr>
                                            <tr>
                                                <th>Realisasi</th>
                                                <th> : <?= rupiah($rel['nn']) ?></th>
                                            </tr>
                                            <tr>
                                                <th>Sisa</th>
                                                <th> : <?= rupiah($sisa) ?></th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div><!-- /.col -->
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
                                    <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                        <th>No</th>
                                        <th>Kode RAB</th>
                                        <th>Tanggal</th>
                                        <th>PJ</th>
                                        <th>Nominal</th>
                                        <th>Ket</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $rls = mysqli_query($conn, "SELECT * FROM realis WHERE kode = '$kode' AND tahun = '$tahun_ajaran' ");
                                    while ($ls_jns = mysqli_fetch_assoc($rls)) {
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $ls_jns['kode'] ?></a></td>
                                            <td><?= $ls_jns['tgl'] ?></a></td>
                                            <td><?= $ls_jns['pj'] ?></a></td>
                                            <td><?= rupiah($ls_jns['nominal']) ?></td>
                                            <td><?= $ls_jns['ket'] ?></a></td>
                                            <!-- <td><a onclick="return confirm('Yakin akan dihapus ?')" href="<?= 'hapus.php?kd=del_real&id=' . $ls_jns['id_realis'] ?>"><span class="fa fa-trash"></span> Hapus</a></td> -->
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <th colspan="4">TOTAL</th>
                                        <th><?= rupiah($rel['nn']) ?></th>
                                        <th></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
    </section><!-- /.content -->
</div>

<?php
include 'bawah.php';
?>