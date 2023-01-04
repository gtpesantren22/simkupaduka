<?php
include 'atas.php';

$no = 1;
$kode = $_GET['kd'];
$pak = $_GET['pak'];

$l = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM rab WHERE id_rab = '$kode' AND tahun = '$tahun_ajaran' "));
$kalem = $l['lembaga'];
$kd_rab = $l['kode'];
$lem = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$kalem' AND tahun = '$tahun_ajaran' "));
$rel = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as nn, SUM(vol) as jml FROM realis WHERE kode = '$kd_rab' AND tahun = '$tahun_ajaran' "));

$jns = mysqli_fetch_assoc(mysqli_query($conn, "SELECT *, IF(jenis = 'A', 'A. Belanja Barang', IF(jenis = 'B', 'B. Langganan Daya dan Jasa', IF(jenis = 'C', 'C. Belanja Kegiatan','D. Umum'))) as nm_jenis, COUNT(jenis) as jml, SUM(total) as tot FROM rab WHERE id_rab = '$kode' AND tahun = '$tahun_ajaran' "));

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
                        <!-- <a href="real_data.php"><button class="btn btn-warning pull-right btn-xs"><i
                                    class="fa fa-arrow-left"></i> Kembali</button></a> -->
                    </div><!-- /.box-header -->
                    <div class="box-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tbody>
                                            <!-- <tr>
                                                <th>Jenis Belanja</th>
                                                <th> : <?= $jns['nm_jenis'] ?></th>
                                            </tr> -->
                                            <tr>
                                                <th>Kode Item</th>
                                                <th> : <?= $jns['kode'] ?></th>
                                            </tr>
                                            <tr>
                                                <th>Nama Item</th>
                                                <th> : <?= $jns['nama'] ?></th>
                                            </tr>
                                            <tr>
                                                <th>QTY</th>
                                                <th> : <?= $jns['qty'] . ' ' . $jns['satuan'] ?></th>
                                            </tr>
                                            <tr>
                                                <th>Harga Satuan</th>
                                                <th> : <?= rupiah($jns['harga_satuan']) ?></th>
                                            </tr>
                                            <tr>
                                                <th>Tatal</th>
                                                <th> : <?= rupiah($jns['total']) ?></th>
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
                                                <th>Realisasi</th>
                                                <th> :
                                                    <?= $rel['jml'] . ' x ' . number_format($jns['harga_satuan']) . ' = ' . number_format($rel['nn']) ?>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>Sisa</th>
                                                <th> :
                                                    <?= $jns['qty'] - $rel['jml'] . ' ' . $jns['satuan'] . ' (' . rupiah($sisa) . ')' ?>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>QTY yang akan diajukan</th>
                                                <form action="" method="post">
                                                    <th>
                                                        <input type="number" class="form-control" name="jml" required>
                                                        <input type="hidden" class="form-control" name="sisa"
                                                            value="<?= $jns['qty'] - $rel['jml']; ?>">
                                                        <input type="hidden" class="form-control" name="kd_rab"
                                                            value="<?= $jns['kode'] ?>">
                                                        <small class="text-danger">* QTY ini tidak boleh melebihi dari
                                                            QTY yang
                                                            tersisa</small><br><br>
                                                        <button type="submit" name="edit"
                                                            class="btn btn-sm btn-success pull-right"><i
                                                                class="fa fa-check"></i> Simpan</button>
                                                    </th>
                                                </form>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div><!-- /.col -->
                            </div>
                        </div>
                        <hr>

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
    </section><!-- /.content -->
</div>

<?php
include 'bawah.php';

if (isset($_POST['edit'])) {
    $jml = $_POST['jml'];
    $sisa = $_POST['sisa'];
    $kd_rab = $_POST['kd_rab'];

    $cek = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pak_detail WHERE kode_rab = '$kd_rab' "));
    if ($jml > $sisa) {
        echo "
        <script>
            alert('Maaf. Sisa QTY tidak mencukupi');
            window.location = 'pak_edit.php?kd=" . $kode . "&pak=" . $pak . "';
        </script>
        ";
    } elseif ($cek > 0) {
        echo "
        <script>
            alert('Maaf. item RAB ini sudah dipakai PAK');
            window.location = 'pak_detail.php?kode=" . $pak . "';
        </script>
        ";
    } else {
        $kd_pak = $pak;
        $kd_rab = $jns['kode'];
        $qty = $jml;
        $satuan = $jns['satuan'];
        $harga_satuan = $jns['harga_satuan'];
        $total = $qty * $harga_satuan;
        $ket = 'edit';
        $tahun = $jns['tahun'];

        $sql = mysqli_query($conn, "INSERT INTO pak_detail VALUES ('', '$kd_pak', '$kd_rab', '$qty', '$satuan', '$harga_satuan', '$total', '$ket', '$tahun')");
        if ($sql) {
            echo "
            <script>
                window.location = 'pak_detail.php?kode=" . $pak . "';
            </script>
            ";
        }
    }
}
?>