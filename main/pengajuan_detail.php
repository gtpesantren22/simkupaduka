<?php
include 'head.php';
require 'vendors/PHPExcel/Classes/PHPExcel.php';

$kode_p = $_GET['kode'];
$kl = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pengajuan WHERE kode_pengajuan = '$kode_p' AND tahun = '$tahun_ajaran' "));
$kode = $kl['lembaga'];
$l = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$kode' AND tahun = '$tahun_ajaran' "));
?>
<!-- Datatables -->
<link href="vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
<link href="vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
<link href="vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
<link href="vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

<!-- bootstrap-daterangepicker -->
<link href="vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<!-- bootstrap-datetimepicker -->
<link href="vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">

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
                                        FROM rab WHERE lembaga = '$kode' AND tahun = '$tahun_ajaran' "));

                                        $trb_pakai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT 
                                        SUM(IF( jenis = 'A', nominal, 0)) AS A, 
                                        SUM(IF( jenis = 'B', nominal, 0)) AS B, 
                                        SUM(IF( jenis = 'C', nominal, 0)) AS C, 
                                        SUM(IF( jenis = 'D', nominal, 0)) AS D, 
                                        SUM(nominal) AS T 
                                        FROM realis WHERE lembaga = '$kode' AND tahun = '$tahun_ajaran' "));

                                        $pengajuan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT 
                                        SUM(IF( jenis = 'A', nominal, 0)) AS A, 
                                        SUM(IF( jenis = 'B', nominal, 0)) AS B, 
                                        SUM(IF( jenis = 'C', nominal, 0)) AS C, 
                                        SUM(IF( jenis = 'D', nominal, 0)) AS D, 
                                        SUM(nominal) AS T 
                                        FROM real_sm WHERE lembaga = '$kode' AND tahun = '$tahun_ajaran' "));

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
                                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#ex_upload"><i class="fa fa-check-square-o"></i> Verifikasi/Setujui</button><br>
                                    <button class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Tolak pengajuan</button><br>
                                </address>

                            </div>
                        </div>
                        <!-- /.row -->
                        <hr>

                        <table id="datatable-buttons" class="table table-striped table-bordered table-sm" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode RAB</th>
                                    <th>Periode</th>
                                    <th>PJ</th>
                                    <th>Nominal</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                if ($kl['cair'] == 1) {
                                    $dt_bos = mysqli_query($conn, "SELECT * FROM realis WHERE kode_pengajuan = '$kode_p' AND tahun = '$tahun_ajaran' ");
                                    $tt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS tot FROM realis WHERE kode_pengajuan = '$kode_p' AND tahun = '$tahun_ajaran' "));
                                } else {
                                    $dt_bos = mysqli_query($conn, "SELECT * FROM real_sm WHERE kode_pengajuan = '$kode_p' AND tahun = '$tahun_ajaran' ");
                                    $tt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS tot FROM real_sm WHERE kode_pengajuan = '$kode_p' AND tahun = '$tahun_ajaran' "));
                                }
                                while ($a = mysqli_fetch_assoc($dt_bos)) { ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $a['kode'] ?></td>
                                        <td><?= $bulan[$a['bulan']] . ' ' . $a['tahun'] ?></td>
                                        <td><?= $a['pj'] ?></td>
                                        <td><?= rupiah($a['nominal']) ?></td>
                                        <td><?= $a['ket'] ?></td>
                                        <!-- <td>
                                            <a onclick="return confirm('Yakin akan dihapus ?. Menghapus data ini akan menghapus data realisasi juga')" href="<?= 'hapus.php?kd=rab&id=' . $a['id_realis']; ?>"><span class="fa fa-trash-o text-danger"> Hapus</span></a>
                                        </td> -->
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                    <th colspan="4">SUB JUMLAH</th>
                                    <th colspan="2"><?= rupiah($tt['tot']) ?></th>
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

<?php include 'foot.php'; ?>
<!-- Datatables -->
<script src="vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
<!-- bootstrap-daterangepicker -->
<script src="vendors/moment/min/moment.min.js"></script>
<script src="vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap-datetimepicker -->
<script src="vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
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
    $rencana = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['rencana']));
    $stts = 1;
    $ket = '-';

    $sql = mysqli_query($conn, "INSERT INTO verifikasi VALUES ('$id', '$kd_pj', '$lembaga','$tgl','$user', '$stts','$ket', '$tahun_ajaran')");
    $sql2 = mysqli_query($conn, "UPDATE pengajuan SET verval = $stts WHERE id_pn = '$id_pn' AND tahun = '$tahun_ajaran' ");
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

<?php    } else {
        echo "DATA TAK MAU MASUK";
    }
}
?>