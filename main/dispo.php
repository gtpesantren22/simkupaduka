<?php
include 'head.php';

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
                        <h2><i class="fa fa-bars"></i> Data Pencairan Disposisi <small>Pencairan</small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><button class="btn btn-info btn-sm" data-toggle="modal" data-target="#tambah_bos"><i class="fa fa-plus-square"></i> Tambah Data</button></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <div class="tab-content" id="myTabContent">

                            <!-- Pemasukan BOS -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-badgeledby="home-tab">

                                <table id="datatable" class="table table-striped table-bordered table-sm" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Lembaga</th>
                                            <th>Tanggal</th>
                                            <th>Nominal</th>
                                            <th>Uraian</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $dt_bos = mysqli_query($conn, "SELECT a.*, b.nama FROM disposisi a JOIN lembaga b ON a.lembaga=b.kode WHERE a.tahun = '$tahun_ajaran' ORDER BY id_disp DESC");
                                        while ($a = mysqli_fetch_assoc($dt_bos)) {
                                            // $kd_pj = $a['kode_pengajuan'];
                                            // $jml = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jml FROM real_sm WHERE kode_pengajuan = '$kd_pj' "));
                                            // $jml2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jml FROM realis WHERE kode_pengajuan = '$kd_pj' "));
                                            // $kfe = $jml['jml'] + $jml2['jml'];
                                        ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $a['kode'] ?></td>
                                                <td><?= $a['nama'] ?></td>
                                                <td><?= $a['tanggal'] ?></td>
                                                <td><?= rupiah($a['nominal']) ?></td>
                                                <td><?= $a['uraian'] ?></td>
                                                <td>
                                                    <a href="<?= 'edit_disp.php?id=' . $a['id_disp'] ?>"><button class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</button></a>
                                                    <a onclick="return confirm('Yakin akan dihapus ?')" href="<?= 'hapus.php?kd=dsp&id=' . $a['id_disp'] ?>"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Del</button></a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>

<!-- Modal Tambah Data BOS-->
<div class="modal fade" id="tambah_bos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Tambah Data Pencairan Disposisi</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left input_mask" action="" method="post">
                <div class="modal-body">

                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Lembaga pemohon <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <select name="kode" id="" class="form-control" required>
                                <option value=""> -pilih lembaga- </option>
                                <?php
                                $dw = mysqli_query($conn, "SELECT * FROM lembaga WHERE tahun = '$tahun_ajaran'");
                                while ($k = mysqli_fetch_assoc($dw)) { ?>
                                    <option value="<?= $k['kode'] ?>"><?= $k['kode'] . '. ' . $k['nama'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nominal <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6  form-group has-feedback">
                            <input type="text" class="form-control has-feedback-left " id="uang" name="nominal" required>
                            <span class="form-control-feedback left" aria-hidden="true">Rp.</span>
                        </div>

                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Tanggal Disposisi <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 xdisplay_inputx form-group row has-feedback">

                            <input type="text" name="tgl_bayar" class="form-control has-feedback-left" id="datePick" placeholder="" aria-describedby="inputSuccess2Status4">
                            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                            <span id="inputSuccess2Status4" class="sr-only">(success)</span>

                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Uraian <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <textarea name="uraian" required="required" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Catatan <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <textarea name="catatan" required="required" class="form-control"> </textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="save_bos" class="btn btn-success">Simpan data</button>
                </div>
            </form>

        </div>
    </div>
</div>
<!-- /page content -->

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

    rupiah.addEventListener('keyup', function(e) {
        rupiah.value = formatRupiah(this.value);
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
if (isset($_POST['save_bos'])) {
    $kode = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['kode']));
    $nominal = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['nominal']));
    $uraian = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['uraian']));
    $tgl_bayar = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tgl_bayar']));
    $catatan = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['catatan']));
    $nom = preg_replace("/[^0-9]/", "", $nominal);

    $data = mysqli_fetch_array(mysqli_query($conn, "SELECT max(kode) as kodeTerbesar FROM disposisi WHERE tahun = '$tahun_ajaran'"));
    $kodeB = $data['kodeTerbesar'];
    $urutan = (int) substr($kodeB, 4, 4);
    $urutan++;
    $huruf =   $kode . "_";
    $kodeBarang = $huruf . sprintf("%04s", $urutan);

    $sql = mysqli_query($conn, "INSERT INTO disposisi VALUES ('', '$kodeBarang', '$kode', '$tgl_bayar', '$nom', '$uraian', '$catatana', NOW(), '$tahun_ajaran' ) ");
    if ($sql) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Data berhasil tersimpan',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "dispo.php"
                }, millisecondsToWait);

            });
        </script>

<?php    }
}
?>