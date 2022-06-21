<?php
include 'head.php';
require '../main/vendors/PHPExcel/Classes/PHPExcel.php';

$kode_p = $_GET['kode'];
$kl = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pengajuan WHERE kode_pengajuan = '$kode_p' "));
$kode = $kl['lembaga'];
$l = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$kode' "));
$tt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS tot FROM real_sm WHERE kode_pengajuan = '$kode_p' "));

?>
<!-- Datatables -->
<link href="../main/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="../main/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
<link href="../main/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
<link href="../main/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
<link href="../main/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

<!-- bootstrap-daterangepicker -->
<link href="../main/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<!-- bootstrap-datetimepicker -->
<link href="../main/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">

<!-- page content -->
<div class="right_col" role="main">
    <div class="">

        <div class="clearfix"></div>

        <div class="">
            <div class="col-md-12 col-sm-12  ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-bars"></i> Pengajuan Realisasi RAB <small>lembaga</small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <center>
                            <h3>
                                <small class=""><u>Rincian Pengajuan RAB <?= strtoupper($l['nama']) ?></u></small>
                            </h3>
                        </center>
                        <br>
                        <!-- info row -->
                        <div class="row invoice-info">
                            <!-- /.col -->
                            <div class="col-sm-8 invoice-col">
                                <address>
                                    <table class="table table-sm">
                                        <?php
                                        $trb = mysqli_fetch_assoc(mysqli_query($conn, "SELECT 
                                        SUM(IF( jenis = 'A', total, 0)) AS A, 
                                        SUM(IF( jenis = 'B', total, 0)) AS B, 
                                        SUM(IF( jenis = 'C', total, 0)) AS C, 
                                        SUM(IF( jenis = 'D', total, 0)) AS D, 
                                        SUM(total) AS T 
                                        FROM rab WHERE lembaga = '$kode' AND tahun = '2022' "));

                                        $trb_pakai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT 
                                        SUM(IF( jenis = 'A', nominal, 0)) AS A, 
                                        SUM(IF( jenis = 'B', nominal, 0)) AS B, 
                                        SUM(IF( jenis = 'C', nominal, 0)) AS C, 
                                        SUM(IF( jenis = 'D', nominal, 0)) AS D, 
                                        SUM(nominal) AS T 
                                        FROM realis WHERE lembaga = '$kode' AND tahun = '2022' "));

                                        $pengajuan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT 
                                        SUM(IF( jenis = 'A', nominal, 0)) AS A, 
                                        SUM(IF( jenis = 'B', nominal, 0)) AS B, 
                                        SUM(IF( jenis = 'C', nominal, 0)) AS C, 
                                        SUM(IF( jenis = 'D', nominal, 0)) AS D, 
                                        SUM(nominal) AS T 
                                        FROM real_sm WHERE lembaga = '$kode' AND tahun = '2022' "));

                                        ?>
                                        <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                            <th>Jenis Belanja</th>
                                            <th>Sisa RAB</th>
                                            <th>Dana Pengajuan</th>
                                            <th>Ket</th>
                                        </tr>
                                        <tr>
                                            <th>Belanja Barang</th>
                                            <th><?= rupiah($trb['A'] - $trb_pakai['A']) ?></th>
                                            <th><?= rupiah($pengajuan['A']) ?></th>
                                            <th>
                                                <?php if (($trb['A'] - $trb_pakai['A']) >= $pengajuan['A']) { ?>
                                                    <span class="badge badge-success"><i class="fa fa-check"></i> RAB Mencukupi</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-danger"><i class="fa fa-check"></i> RAB Tidak Mencukupi</span>
                                                <?php } ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Langganan & Jasa</th>
                                            <th><?= rupiah($trb['B'] - $trb_pakai['B']) ?></th>
                                            <th><?= rupiah($pengajuan['B']) ?></th>
                                            <th>
                                                <?php if (($trb['B'] - $trb_pakai['B']) >= $pengajuan['B']) { ?>
                                                    <span class="badge badge-success"><i class="fa fa-check"></i> RAB Mencukupi</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-danger"><i class="fa fa-check"></i> RAB Tidak Mencukupi</span>
                                                <?php } ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Belanja Kegiatan</th>
                                            <th><?= rupiah($trb['C'] - $trb_pakai['C']) ?></th>
                                            <th><?= rupiah($pengajuan['C']) ?></th>
                                            <th>
                                                <?php if (($trb['C'] - $trb_pakai['C']) >= $pengajuan['C']) { ?>
                                                    <span class="badge badge-success"><i class="fa fa-check"></i> RAB Mencukupi</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-danger"><i class="fa fa-check"></i> RAB Tidak Mencukupi</span>
                                                <?php } ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Umum</th>
                                            <th><?= rupiah($trb['D'] - $trb_pakai['D']) ?></th>
                                            <th><?= rupiah($pengajuan['D']) ?></th>
                                            <th>
                                                <?php if (($trb['D'] - $trb_pakai['D']) >= $pengajuan['D']) { ?>
                                                    <span class="badge badge-success"><i class="fa fa-check"></i> RAB Mencukupi</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-danger"><i class="fa fa-check"></i> RAB Tidak Mencukupi</span>
                                                <?php } ?>
                                            </th>
                                        </tr>

                                        <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                            <th>Total RAB</th>
                                            <th><?= rupiah($trb['T']) ?></th>
                                            <th><?= rupiah($trb['T']) ?></th>
                                            <th></th>
                                        </tr>
                                    </table>
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 invoice-col">
                                <address>
                                    <strong style="color: darkblue;">Periode : <?= $bulan[$kl['bulan']] . ' ' . $kl['tahun'] ?></strong><br>
                                    <strong style="color: red; font-style: italic;">- Harap di cek dulu sebelum di Verifikasi</strong><br>
                                    <strong style="color: red; font-style: italic;">- Khawatir ada Pengajuan yang melebihi Sisa RAB</strong>
                                </address>
                                <hr>
                                <address>
                                    <?php
                                    if ($kl['stts'] == 'no') {
                                        echo "<strong style='color: red; font-style: italic;'>Mohon maaf. Pengajuan masih belum bisa di Verifikasi dikarenakan dalam proses penginputan.</strong>";
                                    } else if ($kl['verval'] == 1) {
                                        echo "<strong style='color: red; font-style: italic;'>Pengajuan ini sudah di verifikasi</strong>";
                                    } else { ?>
                                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#ex_upload"><i class="fa fa-check-square-o"></i> Verifikasi/Setujui</button><br>
                                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#ex_tolak"><i class="fa fa-times"></i> Tolak pengajuan</button><br>
                                    <?php } ?>
                                </address>

                            </div>
                        </div>
                        <!-- /.row -->
                        <hr>

                        <table id="datatable2" class="table table-striped table-bordered table-sm" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode RAB</th>
                                    <th>Periode</th>
                                    <th>PJ</th>
                                    <th>Keterangan</th>
                                    <th>Nominal</th>
                                    <th>Cair</th>
                                    <th>Ket</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                if ($kl['cair'] == 1) {
                                    $dt_bos = mysqli_query($conn, "SELECT * FROM realis WHERE kode_pengajuan = '$kode_p' ");
                                    $tt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS tot, SUM(nom_cair) AS tot2 FROM realis WHERE kode_pengajuan = '$kode_p' "));
                                } else {
                                    $dt_bos = mysqli_query($conn, "SELECT * FROM real_sm WHERE kode_pengajuan = '$kode_p' ");
                                    $tt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS tot, SUM(nom_cair) AS tot2 FROM real_sm WHERE kode_pengajuan = '$kode_p' "));
                                }
                                while ($a = mysqli_fetch_assoc($dt_bos)) { ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $a['kode'] ?></td>
                                        <td><?= $bulan[$a['bulan']] . ' ' . $a['tahun'] ?></td>
                                        <td><?= $a['pj'] ?></td>
                                        <td>
                                            <?= $a['ket'] ?>
                                            <?php
                                            if (preg_match("/honor/i", $a['ket'])) {
                                                $ssq = mysqli_query($conn, "SELECT * FROM honor_file WHERE kode_pengajuan = '$kode_p' ");
                                                $fl = mysqli_fetch_assoc($ssq);
                                                $htgd = mysqli_num_rows($ssq);
                                            ?>
                                                <?php if ($htgd > 0) { ?>
                                                    <b><i><a href="../institution/honor_file/<?= $fl['files']; ?>"> (<i class="fa fa-download"></i> Download)</a></i></b>
                                                <?php } else { ?>
                                                    <b><i><a href="#"> (Belum ada)</a></i></b>
                                            <?php }
                                            } ?>
                                        </td>
                                        <td><?= rupiah($a['nominal']) ?></td>
                                        <td><?= rupiah($a['nom_cair']) ?></td>
                                        <td><?= $a['stas'] ?></td>
                                        <td>
                                            <?php if ($kl['cair'] == 0) { ?>
                                                <a onclick="return confirm('Yakin akan dihapus ?. Menghapus data ini akan menghapus data realisasi juga')" href="<?= 'hapus.php?kd=real&id=' . $a['id_realis']; ?>"><span class="fa fa-trash-o text-danger"> Hapus</span></a>
                                                |
                                                <a type="button" data-toggle="modal" data-target=".bs-example<?= $a['id_realis'] ?>"><span class="fa fa-pencil text-success"> Edit</span></a>

                                                <div class="modal fade bs-example<?= $a['id_realis'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">

                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myModalLabel2">Edit Nominal Pengajuan</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <form action="" method="post">
                                                                <input type="hidden" name="id_rsm" value="<?= $a['id_realis'] ?>">
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label for="">Edit Nominal</label>
                                                                        <input type="text" name="nom_cair" id="uang<?= $no++ ?>" value="<?= rupiah($a['nom_cair']); ?>" class="form-control" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="">Ket. Pencairan</label><br>
                                                                        <input type="radio" name="stas" value="tunai" <?= $a['stas'] === 'tunai' ? 'checked' : ''; ?> required> Cair Tunai
                                                                        <input type="radio" name="stas" value="barang" <?= $a['stas'] === 'barang' ? 'checked' : ''; ?> required> Cair Barang
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                    <button type="submit" name="ed_nom" class="btn btn-primary">Simpan perubahan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                    <th colspan="5">SUB JUMLAH</th>
                                    <th><?= rupiah($tt['tot']) ?></th>
                                    <th colspan="3"><?= rupiah($tt['tot2']) ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- End to do list -->


    </div>
    <div class="clearfix"></div>
</div>
<!-- /page content -->

<!-- Modal Upload-->
<div class="modal fade" id="ex_upload" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Halaman Verifikasi</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="" data-parsley-validate class="form-horizontal form-label-left input_mask" action="" method="post">
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nama Lembaga <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="" name="lembaga" required="required" value="<?= $l['nama'] ?>" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Tanggal Verifikasi <span class="required">*</span>
                        </label>
                        <div class="col-md-3 col-sm-3 ">
                            <input type="text" id="datePick" name="tgl" required="required" class="form-control">
                        </div>
                        <div class="col-md-3 col-sm-3 ">
                            <input type="text" id="datePick2" name="jam" required="required" class="form-control">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Verifikator <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="" name="user" required="required" value="<?= $nama_user ?>" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name"><span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <button type="submit" name="veris" class="btn btn-success"><i class="fa fa-warning"></i> Verifikasi</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Upload-->
<div class="modal fade" id="ex_tolak" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Halaman Penolakan Pengajuan</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="" data-parsley-validate class="form-horizontal form-label-left input_mask" action="" method="post">
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nama Lembaga <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="" name="lembaga" required="required" value="<?= $l['nama'] ?>" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Tanggal Penolakan <span class="required">*</span>
                        </label>
                        <div class="col-md-3 col-sm-3 ">
                            <input type="text" id="datePick3" name="tgl" required="required" class="form-control">
                        </div>
                        <div class="col-md-3 col-sm-3 ">
                            <input type="text" id="datePick4" name="jam" required="required" class="form-control">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Catatan <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <textarea name="pesan" required="required" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Verifikator <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="" name="user" required="required" value="<?= $nama_user ?>" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name"><span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <button type="submit" name="tolakPon" class="btn btn-danger"><i class="fa fa-warning"></i> Kirim penolakan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'foot.php'; ?>
<?php
for ($i = 1; $i <= 100; $i++) { ?>
    <script type="text/javascript">
        // var id_s = uang + <?= $i; ?>;
        var rupiah<?= $i; ?> = document.getElementById('uang<?= $i; ?>');

        rupiah<?= $i; ?>.addEventListener('keyup', function(e) {
            rupiah<?= $i; ?>.value = formatRupiah(this.value);
        });

        /* Fungsi formatRupiah */
        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? rupiah : '');
        }
    </script>
<?php } ?>

<!-- Datatables -->
<script src="../main/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../main/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="../main/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="../main/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="../main/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="../main/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="../main/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="../main/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="../main/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="../main/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="../main/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="../main/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
<!-- bootstrap-daterangepicker -->
<script src="../main/vendors/moment/min/moment.min.js"></script>
<script src="../main/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap-datetimepicker -->
<script src="../main/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<script>
    $(document).ready(function() {
        $('#datatable2').DataTable();
        $('#datatable3').DataTable();

        $('#datePick').datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $('#datePick2').datetimepicker({
            format: 'H:mm'
        });
        $('#datePick3').datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $('#datePick4').datetimepicker({
            format: 'H:mm'
        });
    });
</script>

<?php
if (isset($_POST['veris'])) {

    $id = $uuid;
    $lembaga = $l['kode'];
    $id_pn = $kl['id_pn'];
    $kd_pj = $kode_p;
    $tgl = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tgl'] . ' ' . $_POST['jam']));
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['user']));
    // $rencana = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['rencana']));
    $stts = 1;
    $ket = '-';

    $sql = mysqli_query($conn, "INSERT INTO verifikasi VALUES ('$id', '$kd_pj', '$lembaga','$tgl','$user', '$stts','$ket')");
    $sql2 = mysqli_query($conn, "UPDATE pengajuan SET verval = $stts WHERE id_pn = '$id_pn' ");
    if ($sql  && $sql2) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Pengajuan sudah terverifikasi',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "<?= 'pengajuan.php' ?>"
                }, millisecondsToWait);

            });
        </script>

    <?php $psn = '
*INFORMASI PERMOHONAN PERSETUJUAN*

pengajuan dari :

Lembaga : ' . $l['nama'] . '
Kode Pengajuan : ' . $kd_pj . '
Nominal : ' . rupiah($tt['tot']) . '
telah diverifikasi oleh *' . $user . ' pada ' . $tgl . '*

*_dimohon kepada KEPALA PESANTREN untuk segera mengecek dan menyetujui nya_*
Terimakasih';

        $curl2 = curl_init();
        curl_setopt_array(
            $curl2,
            array(
                CURLOPT_URL => 'http://8.215.26.187:3000/api/sendMessageGroup',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'apiKey=fb209be1f23625e43cbf285e57c0c0f2&id_group=DfBeAZ3zGcR5qvLmBdKJaZ&message=' . $psn,
            )
        );
        $response = curl_exec($curl2);
        curl_close($curl2);

        // Japri 1
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'http://8.215.26.187:3000/api/sendMessage',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'apiKey=fb209be1f23625e43cbf285e57c0c0f2&phone=082264061060&message=' . $pesan,
            )
        );
        $response = curl_exec($curl);
        curl_close($curl);
    } else {
        echo "DATA TAK MAU MASUK";
    }
} else if (isset($_POST['tolakPon'])) {

    $id = $uuid;
    $lembaga = $l['kode'];
    $id_pn = $kl['id_pn'];
    $kd_pj = $kode_p;
    $tgl = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tgl'] . ' ' . $_POST['jam']));
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['user']));
    $pesan = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['pesan']));
    $stts = 0;
    $ket = '-';

    $sql = mysqli_query($conn, "INSERT INTO verifikasi VALUES ('$id', '$kd_pj', '$lembaga','$tgl','$user', '$stts','$pesan')");
    $sql2 = mysqli_query($conn, "UPDATE pengajuan SET stts = 'no' WHERE id_pn = '$id_pn' ");

    if ($sql  && $sql2) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Pengajuan ditolak',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "<?= 'pengajuan.php' ?>"
                }, millisecondsToWait);

            });
        </script>

<?php $psn = '
*INFORMASI PENOLAKAN PENGAJUAN*

pengajuan dari :

Lembaga : ' . $l['nama'] . '
Kode Pengajuan : ' . $kd_pj . '
Nominal : ' . rupiah($tt['tot']) . '
ditolak oleh *' . $user . '* pada *' . $tgl . '*
dengan catatan : *_' . $pesan . '_*

*_dimohon kepada KPA lembaga terkait untuk segera melakukan revisi sesuai dengan catatan yang ada_*

Terimakasih';

        $curl2 = curl_init();
        curl_setopt_array(
            $curl2,
            array(
                CURLOPT_URL => 'http://8.215.26.187:3000/api/sendMessageGroup',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'apiKey=fb209be1f23625e43cbf285e57c0c0f2&id_group=DfBeAZ3zGcR5qvLmBdKJaZ&message=' . $psn,
            )
        );
        $response = curl_exec($curl2);
        curl_close($curl2);

        // Japri 1
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'http://8.215.26.187:3000/api/sendMessage',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'apiKey=fb209be1f23625e43cbf285e57c0c0f2&phone=' . $l['hp'] . '&message=' . $psn,
            )
        );
        $response = curl_exec($curl);
        curl_close($curl);
    } else {
        echo "DATA TAK MAU MASUK";
    }
} else if (isset($_POST['ed_nom'])) {
    $id_rsm = $_POST['id_rsm'];
    $nom_cair = preg_replace("/[^0-9]/", "", $_POST['nom_cair']);
    $stas = $_POST['stas'];

    $sql = mysqli_query($conn, "UPDATE real_sm SET nom_cair = '$nom_cair', stas = '$stas' WHERE id_realis = '$id_rsm' ");

    if ($sql) {
        echo "
            <script>
                window.location = 'pengajuan_detail.php?kode=" . $kode_p . "'
            </script>
        ";
    }
}
?>