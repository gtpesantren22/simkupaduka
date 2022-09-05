<?php
include 'atas.php';
$nis = $_GET['nis'];
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Tanggungan Santri Mutasi
            <small>Tanggungan</small>
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


                        // $tg = mysqli_query($conn, "SELECT * FROM tg_lembaga WHERE tahun = '$tahun_ajaran'");

                        $sn = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tb_santri WHERE nis = '$nis' "));
                        $mts = mysqli_fetch_assoc(mysqli_query($conn_sekretaris, "SELECT * FROM mutasi WHERE nis = '$nis' "));
                        $masuk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jml FROM pembayaran WHERE nis = '$nis' AND tahun = '$tahun_ajaran' GROUP BY nis "));
                        $bayar = mysqli_query($conn, "SELECT * FROM pembayaran WHERE nis = '$nis' AND tahun = '$tahun_ajaran' ");
                        $rc_byar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tangg WHERE nis = '$nis' AND tahun = '$tahun_ajaran' "));

                        if (date('m', strtotime($mts['tgl_mutasi'])) == 5 || date('m', strtotime($mts['tgl_mutasi'])) == 6) {
                            $tgbyr = $rc_byar['me_ju'];
                            $dekos = 0;
                        } else {
                            if ($rc_byar['me_ju'] == $rc_byar['ju_ap']) {
                                $dekos = 0;
                            } else {
                                $dekos = 300000;
                            }
                            $tgbyr = $rc_byar['ju_ap'] - $dekos;
                        }

                        $tglbr = date('d', strtotime($mts['tgl_mutasi']));

                        ?>
                        <div class="row">
                            <div class="col-md-5">
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

                            <div class="col-md-7">
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
                                                        <th>Bulan</th>
                                                        <th>Tahun</th>
                                                        <th>Penerima</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php while ($r = mysqli_fetch_assoc($bayar)) { ?>
                                                        <tr>
                                                            <td><?= $no++; ?></td>
                                                            <td><?= $r['tgl']; ?></td>
                                                            <td><?= rupiah($r['nominal']); ?></td>
                                                            <td><?= $bulan[$r['bulan']]; ?></td>
                                                            <td><?= $r['tahun']; ?></td>
                                                            <td><span class="label label-success"><?= $r['kasir']; ?></span></td>
                                                            <td>
                                                                <a href="hapus.php?kd=del_by&id=<?= $r['id']; ?>" onclick="return confirm('Yakin akan dihapus?. Ini akan menghapus data di dekosan juga')"><span class="label label-danger">Del</span></a>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div><!-- /.table-responsive -->
                                    </div><!-- /.box-body -->
                                </div><!-- /.box -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
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
                                                    <label for="exampleInputEmail1">Nominal Biaya Pendidikan</label>
                                                    <input type="text" name="nominal_bp" class="form-control" id="uang" placeholder="Masukan nominal" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Nominal Dekosan</label>
                                                    <input type="text" name="nominal_dks" class="form-control" id="uang2" placeholder="Masukan nominal" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputPassword1">Tanggal</label>
                                                    <input type="date" name="tgl" class="form-control" id="exampleInputPassword1" placeholder="Password" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputPassword1">Bulan Dekosan</label>
                                                    <select name="bulan" class="form-control">
                                                        <option value=""> -pilih bulan- </option>
                                                        <?php
                                                        for ($i = 1; $i <= 12; $i++) { ?>
                                                            <option value="<?= $i; ?>"><?= $bulan[$i]; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" name="add" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                                                </div>
                                            </div><!-- /.box-body -->

                                        </form>
                                    </div><!-- /.box-body -->
                                </div><!-- /.box -->
                            </div>

                            <div class="col-md-7">
                                <div class="box box-info">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Hitungan Tanggungan Santri Berdasarkan Tanggal Mutasi</h3>
                                        <div class="box-tools pull-right">
                                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                        </div>
                                    </div><!-- /.box-header -->
                                    <div class="box-body">
                                        <div class="table-responsive">
                                            <h4 class="box-title">Tanggal Mutasi Santri : <?= $mts['tgl_mutasi']; ?></h4>
                                            <table class="table no-margin">
                                                <thead>
                                                    <tr>
                                                        <th>Biaya Pendidikan</th>
                                                        <th>:</th>
                                                        <th><?= rupiah($tgbyr) . ' (' . date('M', strtotime($mts['tgl_mutasi'])) . ')'; ?> - <i>dikurangi dekosan</i></th>
                                                    </tr>
                                                    <tr>
                                                        <th></th>
                                                        <th>:</th>
                                                        <th><?= rupiah($tgbyr) . ' / 30 hari = ' . rupiah($tgbyr / 30); ?> (perhari)</th>
                                                    </tr>
                                                    <tr>
                                                        <th></th>
                                                        <th>:</th>
                                                        <th><?= rupiah($tgbyr / 30) . ' x ' . $tglbr . ' (Tgl Mutasi) = '; ?> <b style="color: green; text-decoration: underline;"><?= rupiah(($tgbyr / 30) * $tglbr); ?> (total bayar)</b></th>
                                                    </tr>
                                                    <tr>
                                                        <th>Biaya Dekos Makan</th>
                                                        <th>:</th>
                                                        <th><?= rupiah($dekos) . ' (sebulan)'; ?></th>
                                                    </tr>
                                                    <tr>
                                                        <th></th>
                                                        <th>:</th>
                                                        <th><?= rupiah($dekos) . ' / 30 hari = ' . rupiah($dekos / 30); ?> (perhari)</th>
                                                    </tr>
                                                    <tr>
                                                        <th></th>
                                                        <th>:</th>
                                                        <th><?= rupiah($dekos / 30) . ' x ' . $tglbr . ' (Tgl Mutasi) = '; ?> <b style="color: green; text-decoration: underline;"><?= rupiah(($dekos / 30) * $tglbr); ?> (total bayar)</b></th>
                                                    </tr>
                                                    <tr>
                                                        <th><button class="btn btn-success btn-sm" data-toggle="modal" data-target="#smallModal"><i class="fa fa-check"></i> </i> Verifikasi Tanggungan</button></th>
                                                        <div class="modal fade" id="smallModal" tabindex="-1" role="dialog" aria-labelledby="smallModal" aria-hidden="true">
                                                            <div class="modal-dialog modal-sm">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                        <h4 class="modal-title" id="myModalLabel">VerVal Bendahara</h4>
                                                                    </div>
                                                                    <form action="" method="post">
                                                                        <input type="hidden" name="id_mutasi" value="<?= $mts['id_mutasi']; ?>">
                                                                        <div class="modal-body">
                                                                            <h3>Sudah selesai ?</h3>
                                                                            <p>Data ini akan dilanjutkan ke sekretariat untuk selanjutnya diterbitkan surat mutasi</p>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                                                            <button type="submit" name="verval" class="btn btn-primary">Ya. Lanjut pon</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <th></th>
                                                        <th><a href="tg_syh2.php?nis=<?= $nis; ?>" class="btn btn-warning btn-sm"><i class="fa fa-search"></i> </i> Cek Perbulan</button></th>

                                                    </tr>
                                                </thead>
                                            </table>
                                        </div><!-- /.table-responsive -->
                                    </div><!-- /.box-body -->
                                </div><!-- /.box -->
                            </div>
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
<script src="../institution/dist/sw/sweetalert2.all.min.js"></script>

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

    $nominal_bp = preg_replace("/[^0-9]/", "", $_POST['nominal_bp']);
    $nominal_sk = preg_replace("/[^0-9]/", "", $_POST['nominal_dks']);
    $tgl = $_POST['tgl'];
    $kasir = htmlspecialchars(mysqli_real_escape_string($conn, $nama_user));
    $nama = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['nama']));
    $nis = $_POST['nis'];
    $tahun = $tahun_ajaran;
    $bulan_bayar = $_POST['bulan'];

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
                title: 'Maaf. Pembayaran Melebihi',
                showConfirmButton: false
            });
            var millisecondsToWait = 1000;
            setTimeout(function() {
                document.location.href = "<?= 'mutasi_dtl.php?nis=' . $nis ?>"
            }, millisecondsToWait);
        </script>
        <?php
        exit;
    } else {

        $cek = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pembayaran WHERE nis = '$nis' AND bulan = '$bulan_bayar' AND tahun = '$tahun' "));
        if ($cek < 1) {

            $qr = mysqli_query($conn, "INSERT INTO pembayaran VALUES ('', '$nis', '$nama', '$tgl', '$nominal_bp', '$bulan_bayar', '$tahun_ajaran', '$kasir') ");
            $qr2 = mysqli_query($conn_dekos, "INSERT INTO kos VALUES ('', '$nis', '$nominal_sk', '$bulan_bayar', '$tahun_ajaran', '$tgl', '$kasir', 1, NOW() ) ");

            if ($qr && $qr2) { ?>
                <script>
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Data Pembayaran telah ditambahkan',
                        showConfirmButton: false
                    });
                    var millisecondsToWait = 1000;
                    setTimeout(function() {
                        document.location.href = "<?= 'mutasi_dtl.php?nis=' . $nis ?>"
                    }, millisecondsToWait);
                </script>
            <?php }
        } else {
            ?>
            <script>
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Maaf Pembayaran sudah ada',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "<?= 'mutasi_dtl.php?nis=' . $nis ?>"
                }, millisecondsToWait);
            </script>
<?php
        }
    }
}

if (isset($_POST['verval'])) {
    $id_mutasi = $_POST['id_mutasi'];

    $sql = mysqli_query($conn_santri, "UPDATE mutasi SET status = 1 WHERE id_mutasi = '$id_mutasi' ");
    $sql2 = mysqli_query($conn_sekretaris, "UPDATE mutasi SET status = 1 WHERE id_mutasi = '$id_mutasi' ");

    $dts = mysqli_fetch_assoc(mysqli_query($conn_santri, "SELECT a.tgl_mutasi, b.* FROM mutasi a JOIN tb_santri b ON a.nis=b.nis WHERE a.id_mutasi = $id_mutasi "));
    $psn = '*INFORMASI MUTASI*

*PENERBITAN SURAT BERHENTI*
    
Nama : ' . $dts['nama'] . '
Alamat : ' . $dts['desa'] . '-' . $dts['kec'] . '-' . $dts['kab'] . '
Sekolah : ' . $dts['t_formal'] . '
Tgl Mutasi : ' .  $dts['tgl_mutasi'] . '

*_telah diverifikasi oleh BENDAHARA PESANTREN. Untuk selanjutnya surat mutasi sudah bisa diterbitkan oleh SEKRETARIAT_*
Terimakasih';

    if ($sql2 && $sql) {

        kirim_group($api_key, 'CnbjJ9vz2Dh7KkNzI3769h', $psn);

        echo "
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: 'Data mutasi sudah diverifikasi',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = 'mutasi.php' 
                }, millisecondsToWait);
            </script>
        ";
    }
}
