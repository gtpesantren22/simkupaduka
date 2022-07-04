<?php
include 'atas.php';
$nis = $_GET['nis'];
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Tanggungan Santri
            <small>Tanggunagn</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Tanggungan</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Rincain Tanggungan Santri</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?php
                        $no = 1;

                        $tg = mysqli_query($conn, "SELECT * FROM tg_lembaga WHERE tahun = '$tahun_ajaran'");
                        $sn = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tb_santri WHERE nis = '$nis' "));
                        $masuk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jml FROM pembayaran WHERE nis = '$nis' AND tahun = '$tahun_ajaran' GROUP BY nis "));
                        $bayar = mysqli_query($conn, "SELECT * FROM pembayaran WHERE nis = '$nis' AND tahun = '$tahun_ajaran' ");

                        ?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="box box-info">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Identitas santri</h3>
                                        <div class="box-tools pull-right">
                                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div><!-- /.box-header -->
                                    <div class="box-body">
                                        <div class="table-responsive">
                                            <table class="table no-margin">
                                                <tr>
                                                    <th>NIS</th>
                                                    <th>: <?= $sn['nis']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Nama</th>
                                                    <th>: <?= $sn['nama']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Alamat</th>
                                                    <th>: <?= $sn['desa'] . ' - ' . $sn['kec'] . ' - ' . $sn['kab']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Formal</th>
                                                    <th>: <?= $sn['k_formal'] . ' - ' . $sn['t_formal'] ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Madin</th>
                                                    <th>: <?= $sn['k_madin'] . ' ' . $sn['r_madin'] ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Kamar</th>
                                                    <th>: <?= $sn['kamar'] ?></th>
                                                </tr>
                                                <tr>
                                                    <th>Komplek</th>
                                                    <th>: <?= $sn['komplek'] ?></th>
                                                </tr>
                                            </table>
                                        </div><!-- /.table-responsive -->
                                    </div><!-- /.box-body -->

                                </div><!-- /.box -->
                            </div>
                            <div class="col-md-4">
                                <div class="box box-default">
                                    <div class="box-header with-border">
                                        <i class="fa fa-warning"></i>
                                        <h3 class="box-title">Rincian Tanggungan santri</h3>
                                    </div><!-- /.box-header -->
                                    <div class="box-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">

                                                <?php
                                                $tgn = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tangg WHERE nis = '$nis' AND tahun = '$tahun_ajaran' "));

                                                for ($i = 1; $i <= 12; $i++) {
                                                    $tnn = $tgn['ju_ap'];
                                                    if ($i == 5 || $i == 6) {
                                                        $tnnOk = $tgn['me_ju'];
                                                    } else {
                                                        $tnnOk = $tnn;
                                                    }
                                                ?>
                                                    <tr>
                                                        <th><?= $bulan[$i]; ?></th>
                                                        <th><?= rupiah($tnnOk); ?></th>
                                                    </tr>
                                                <?php } ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="small-box bg-aqua">
                                    <div class="inner">
                                        <h3><?= rupiah($tgn['total']); ?></h3>
                                        <p>Total Tanggungan Santri</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-cash"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                                <div class="small-box bg-green">
                                    <div class="inner">
                                        <h3><?= rupiah($masuk['jml']); ?></h3>
                                        <p>Sudah dibayarkan</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                                <div class="small-box bg-red">
                                    <div class="inner">
                                        <h3><?= rupiah($tgn['total'] - $masuk['jml']); ?></h3>
                                        <p>Kekurangan</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-pie-graph"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="box box-info">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Hisytrory bayar santri</h3>
                                        <div class="box-tools pull-right">
                                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div><!-- /.box-header -->
                                    <div class="box-body">
                                        <div class="table-responsive">
                                            <table class="table no-margin">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Tgl Bayar</th>
                                                        <th>Nominal</th>
                                                        <th>Untuk tahun</th>
                                                        <th>Penerima</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php while ($r = mysqli_fetch_assoc($bayar)) { ?>
                                                        <tr>
                                                            <td><?= $no++; ?></td>
                                                            <td><?= $r['tgl']; ?></td>
                                                            <td><?= rupiah($r['nominal']); ?></td>
                                                            <td><?= $r['tahun']; ?></td>
                                                            <td><span class="label label-success"><?= $r['kasir']; ?></span></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div><!-- /.table-responsive -->
                                    </div><!-- /.box-body -->
                                </div><!-- /.box -->
                            </div>
                            <div class="col-md-4">
                                <div class="box box-danger">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Input Pembayaran Baru</h3>
                                        <div class="box-tools pull-right">
                                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        </div>
                                    </div><!-- /.box-header -->
                                    <div class="box-body">
                                        <form role="form" action="" method="POST">
                                            <input type="hidden" name="nis" value="<?= $nis; ?>">
                                            <input type="hidden" name="nama" value="<?= $sn['nama']; ?>">
                                            <input type="hidden" name="ttl" value="<?= $tgn['total']; ?>">
                                            <input type="hidden" name="masuk" value="<?= $masuk['jml']; ?>">
                                            <div class="box-body">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Nominal Pembyaran</label>
                                                    <input type="text" name="nominal" class="form-control" id="uang" placeholder="Masukan nominal" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputPassword1">Tanggal</label>
                                                    <input type="date" name="tgl" class="form-control" id="exampleInputPassword1" placeholder="Password" required>
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" name="add" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                                                </div>
                                            </div><!-- /.box-body -->

                                        </form>
                                    </div><!-- /.box-body -->
                                </div><!-- /.box -->
                            </div>
                        </div>

                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
    </section><!-- /.content -->
</div>

<!-- DataTables -->
<link rel="stylesheet" href="../institution/plugins/datatables/dataTables.bootstrap.css">
<!-- DataTables -->
<link rel="stylesheet" href="../institution/plugins/datatables/dataTables.bootstrap.css">
<!-- Select2 -->
<link rel="stylesheet" href="../institution/plugins/select2/select2.min.css">
<!-- jQuery 2.1.4 -->
<script src="../institution/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="../institution/bootstrap/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="../institution/plugins/select2/select2.full.min.js"></script>
<!-- DataTables -->
<script src="../institution/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../institution/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
    function masuk(txt, data) {
        document.getElementById('nis').value = data; // ini berfungsi mengisi value yang ber id textbox
        //$("#cek").modal('hide'); // ini berfungsi untuk menyembunyikan modal
    }
    $(function() {
        //$(".select2").select2();
        $("#example1_bst").DataTable();
        $("#example2_bst").DataTable();
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
    });
</script>
<script>
    $(document).ready(function() {
        $(".cari1").select2();
    });
</script>
<?php
include 'bawah.php';

if (isset($_POST['add'])) {

    $nominal = preg_replace("/[^0-9]/", "", $_POST['nominal']);
    $tgl = $_POST['tgl'];
    $kasir = htmlspecialchars(mysqli_real_escape_string($conn, $nama_user));
    $nama = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['nama']));
    $nis = $_POST['nis'];
    $tahun = $tahun_ajaran;

    $dp = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tb_santri WHERE nis = '$nis' "));

    $by = $nominal + $_POST['masuk'];
    $ttl = $_POST['ttl'];
    $alm = $dp['desa'] . '-' . $dp['kec'] . '-' . $dp['kab'];

    $pesan = '_(Ini adalah pesan otomatis dari sistem)_
*Assalamualaikum Wr. Wb*
Kami dari *Pengurus Syahriyah* Pesantren Darul Lughah Wal Karomah
menginfokan bahwa data dibawah ini
    
Nama : *' . $nama . '*
Alamat : *' . $alm . '* 
Nominal Pembayaran: *' . rupiah($nominal) . '*
Tanggal Bayar : *' . $tgl . '*
Pembayaran Untuk: *Syahriyah tahun ' . $tahun . '*
Penerima: *' . $kasir . '*

_*- Pesan ini bisa disimpan sebagai bukti pembayaran*_
*Terimakasih*';

    if ($by > $ttl) { ?>
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Maaf Pembayaran anda melebihi',
                showConfirmBuutton: false
            });
            var millisecondsToWait = 2500;
            setTimeout(function() {
                document.location.href = "<?= 'tg_syh.php' ?>"
            }, millisecondsToWait);
        </script>
<?php
        exit;
    } else {
        $qr = mysqli_query($conn, "INSERT INTO pembayaran VALUES ('', '$nis', '$nama', '$tgl', '$nominal', '$tahun', '$kasir') ");
        if ($qr) {
            echo "
                    <script>
                            Swal.fire({
                                title: 'Berhasil',
                                text: 'Pembayaran Berhasil',
                                icon: 'success',
                                showConfirmButton: false
                            });
                            var millisecondsToWait = 1000;
                            setTimeout(function() {
                                document.location.href = 'tg_syh2.php?nis='" . $nis . "
                            }, millisecondsToWait);
                        </script>
                    ";

            $url = 'https://app.whacenter.com/api/send';
            $ch = curl_init($url);
            // $pesan = $pesan;
            $data = array(
                'device_id' => 'ba05119ba4157d8214272d38ceeef5a0',
                'number' => $dp['hp'],
                // 'number' => '085236924510',
                'message' => $pesan,

            );
            $payload = $data;
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
        }
    }
}
