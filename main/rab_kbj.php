<?php
include 'head.php';
require 'vendors/PHPExcel/Classes/PHPExcel.php';

$tot = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jml FROM kebijakan WHERE tahun = '$tahun_ajaran'"));

$sisa = ($tot['jml'] / 50000000) * 100;
$prsn = round($sisa, 1);

if ($prsn <= 20) {
    $bg = 'bg-primary';
} elseif ($prsn > 20 && $prsn <= 40) {
    $bg = 'bg-success';
} elseif ($prsn > 40 && $prsn <= 60) {
    $bg = 'bg-info';
} elseif ($prsn > 60 && $prsn <= 80) {
    $bg = 'bg-warning';
} elseif ($prsn > 80 && $prsn <= 100) {
    $bg = 'bg-danger';
}

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

                        <h2><i class="fa fa-bars"></i> Belanja Kebijakan Kepala Pesantren <small>lembaga</small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><button class="btn btn-info btn-sm" data-toggle="modal" data-target="#tambah"><i class="fa fa-plus-square"></i> Tambah Data</button></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <!-- A -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="alert alert-danger" role="alert">
                                    <strong>LIMIT : <?= rupiah(50000000); ?></strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="alert alert-success" role="alert">
                                    <strong>Terpakai : <?= rupiah($tot['jml']); ?></strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="alert alert-info" role="alert">
                                    <strong>Sisa Dana : <?= rupiah(50000000 - $tot['jml']); ?></strong>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped <?= $bg; ?>" role="progressbar" style="width: <?= $prsn; ?>%" aria-valuenow="<?= $prsn; ?>" aria-valuemin="0" aria-valuemax="100"><?= $prsn; ?>%</div>
                                </div>
                            </div>
                        </div>
                        <table id="datatable" class="table table-striped table-bordered table-sm" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama Lembaga</th>
                                    <th>Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Keterangan</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $dt_bos = mysqli_query($conn, "SELECT a.*, b.nama FROM kebijakan a JOIN lembaga b ON a.lembaga=b.kode ");
                                $tt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS tot FROM kebijakan "));
                                while ($a = mysqli_fetch_assoc($dt_bos)) { ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $a['kode_kbj'] ?></td>
                                        <td><?= $a['nama'] ?></td>
                                        <td><?= $a['tgl'] ?></td>
                                        <td><?= rupiah($a['nominal']) ?></td>
                                        <td><?= $a['ket'] ?></td>
                                        <td>
                                            <a onclick="return confirm('Yakin akan dihapus ?. Menghapus data ini akan menghapus data realisasi juga')" href="<?= 'hapus.php?kd=kbj&id=' . $a['id_kebijakan']; ?>"><span class="fa fa-trash-o text-danger"> Hapus</span></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                    <th colspan="4">SUB JUMLAH</th>
                                    <th colspan="3"><?= rupiah($tt['tot']) ?></th>
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

<!-- Modal Tambah Data -->
<div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-hidden="true">
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
                            <select name="lembaga" class="form-control" id="" required>
                                <option value=""> -pilih lembaga- </option>
                                <?php
                                $qr2 = mysqli_query($conn, "SELECT * FROM lembaga");
                                while ($a2 = mysqli_fetch_assoc($qr2)) { ?>
                                    <option value="<?= $a2['kode'] ?>"><?= $a2['kode'] ?>. <?= $a2['nama'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Bidang/bagian <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <select name="bidang" class="form-control" id="" required>
                                <option value=""> -pilih bidang- </option>
                                <?php
                                $qr2 = mysqli_query($conn, "SELECT * FROM bidang");
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
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Jumlah Nominal <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6  form-group has-feedback">
                            <input type="text" class="form-control has-feedback-left " id="uang" name="nominal" required>
                            <span class="form-control-feedback left" aria-hidden="true">Rp.</span>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Tanggal Bayar <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 xdisplay_inputx form-group row has-feedback">

                            <input type="text" name="tgl" class="form-control has-feedback-left" id="datePick" placeholder="" aria-describedby="inputSuccess2Status4">
                            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                            <span id="inputSuccess2Status4" class="sr-only">(success)</span>

                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Penanggungjawab <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="first-name" name="pj" required="required" class="form-control ">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Keterangan <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <textarea name="ket" required="required" class="form-control "></textarea>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Tahun Ajaran <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <select name="tahun" class="form-control" id="" required>
                                <option value=""> -pilih tahun- </option>
                                <?php
                                $qr2 = mysqli_query($conn, "SELECT * FROM tahun");
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
            format: 'YYYY-MM-DD'
        });
    });
</script>
<script type="text/javascript">
    var rupiah = document.getElementById('uang');
    var rupiah2 = document.getElementById('uang_2');

    rupiah.addEventListener('keyup', function(e) {
        rupiah.value = formatRupiah(this.value);
    });
    rupiah2.addEventListener('keyup', function(e) {
        rupiah2.value = formatRupiah(this.value);
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
<?php
if (isset($_POST['save'])) {

    $id = $uuid;
    $lembaga = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['lembaga']));
    $jenis = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['jenis']));
    $bidang = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['bidang']));
    $kode = 'KBJ.' . $lembaga . '.' . rand(0, 99999);
    $nominal = htmlspecialchars(mysqli_real_escape_string($conn, preg_replace("/[^0-9]/", "", $_POST['nominal'])));
    $pj = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['pj']));
    $ket = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['ket']));
    $tgl = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tgl']));
    $tahun = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tahun']));

    $sql = mysqli_query($conn, "INSERT INTO kebijakan VALUES ('$id', '$kode', '$lembaga','$bidang','$jenis','$nominal','$tgl','$pj','$ket','$tahun', NOW())");
    if ($sql) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'RAB berhasil tersimpan',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "<?= 'rab_kbj.php' ?>"
                }, millisecondsToWait);

            });
        </script>

<?php    } else {
        echo "DATA TAK MAU MASUK";
    }
}
?>