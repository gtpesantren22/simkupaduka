<?php
include 'head.php';
require 'vendors/PHPExcel/Classes/PHPExcel.php';

//$kode = $_GET['kode'];
//$l = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$kode' "));
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
                        <h2><i class="fa fa-bars"></i> Daftar Rencana Belanja <small>lembaga</small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <center>
                            <h3>
                                <small class=""><u>RENCANA ANGGARAN BELANJA KEBIJAKAN</u></small>
                            </h3>
                        </center>
                        <br>
                        <!-- info row -->
                        <div class="row invoice-info">
                            <div class="col-sm-2 invoice-col">
                                <address>
                                    <strong>Nama Lembaga</strong><br>
                                    <strong>Pelkasana</strong><br>
                                    <strong>PJ/HP</strong><br>
                                    <strong>Waktu</strong>
                                </address>
                                <hr>
                                <address>
                                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#tambah_bos"><i class="fa fa-plus-square"> </i> Tambah Data</button><br>
                                    <a href="rab.php"><button class="btn btn-warning btn-sm"><i class="fa fa-chevron-circle-left"></i> Kembali</button></a>
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-3 invoice-col">
                                <address>
                                    <strong>: PP DWK</strong><br>
                                    <strong>: Kondisional</strong><br>
                                    <strong>: Accounting</strong><br>
                                    <strong>: -</strong>
                                </address>
                                <hr>
                                <address>
                                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#ex_upload" disabled><i class="fa fa-upload"></i> Upload Excel</button><br>
                                    <button class="btn btn-primary btn-sm"><i class="fa fa-download"></i> Download Excel</button><br>
                                </address>

                            </div>
                            <!-- /.col -->
                            <div class="col-sm-7 invoice-col">
                                <address>
                                    <table class="table">
                                        <?php
                                        $trb = mysqli_fetch_assoc(mysqli_query($conn, "SELECT 
                                        SUM(IF( jenis = 'A', total, 0)) AS A, 
                                        SUM(IF( jenis = 'B', total, 0)) AS B, 
                                        SUM(IF( jenis = 'C', total, 0)) AS C, 
                                        SUM(IF( jenis = 'D', total, 0)) AS D, 
                                        SUM(total) AS T 
                                        FROM rab WHERE lembaga = '01' AND tahun = '2022' "));
                                        ?>
                                        <tr>
                                            <th>Belanja Barang</th>
                                            <th>: <?= rupiah($trb['A']) ?></th>
                                        </tr>
                                        <tr>
                                            <th>Tagihan & Jasa</th>
                                            <th>: <?= rupiah($trb['B']) ?></th>
                                        </tr>
                                        <tr>
                                            <th>Belanja Kegiatan</th>
                                            <th>: <?= rupiah($trb['C']) ?></th>
                                        </tr>
                                        <tr>
                                            <th>Umum</th>
                                            <th>: <?= rupiah($trb['D']) ?></th>
                                        </tr>
                                        <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                            <th>Total RAB</th>
                                            <th>: <?= rupiah($trb['T']) ?></th>
                                        </tr>
                                    </table>
                                </address>
                            </div>
                        </div>
                        <!-- /.row -->
                        <hr>

                        <!-- A -->
                        <h4>A. BELANJA BARANG</h4>
                        <table id="" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama Barang</th>
                                    <th>Rencana Waktu</th>
                                    <th>QTY</th>
                                    <th>Harga Satuan</th>
                                    <th>Jumlah</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $dt_bos = mysqli_query($conn, "SELECT * FROM rab WHERE lembaga = '01' AND jenis = 'A' ");
                                $tt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) AS tot FROM rab WHERE lembaga = '01' AND jenis = 'A' "));
                                while ($a = mysqli_fetch_assoc($dt_bos)) { ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $a['kode'] ?></td>
                                        <td><?= $a['nama'] ?></td>
                                        <td><?= $a['rencana'] ?></td>
                                        <td><?= $a['qty'] . ' ' . $a['satuan'] ?></td>
                                        <td><?= rupiah($a['harga_satuan']) ?></td>
                                        <td><?= rupiah($a['total']) ?></td>
                                        <td>
                                            <a onclick="return confirm('Yakin akan dihapus ?. Menghapus data ini akan menghapus data realisasi juga')" href="<?= 'hapus.php?kd=rab&id=' . $a['id_rab']; ?>"><span class="fa fa-trash-o text-danger"> Hapus</span></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                    <th colspan="6">SUB JUMLAH</th>
                                    <th colspan="2"><?= rupiah($tt['tot']) ?></th>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- B -->
                        <hr>
                        <h4>B. LANGGANAN DAYA & JASA</h4>
                        <table id="" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama Barang</th>
                                    <th>Rencana Waktu</th>
                                    <th>QTY</th>
                                    <th>Harga Satuan</th>
                                    <th>Jumlah</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $dt_bos = mysqli_query($conn, "SELECT * FROM rab WHERE lembaga = '01' AND jenis = 'B' ");
                                $tt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) AS tot FROM rab WHERE lembaga = '01' AND jenis = 'B' "));
                                while ($a = mysqli_fetch_assoc($dt_bos)) { ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $a['kode'] ?></td>
                                        <td><?= $a['nama'] ?></td>
                                        <td><?= $a['rencana'] ?></td>
                                        <td><?= $a['qty'] . ' ' . $a['satuan'] ?></td>
                                        <td><?= rupiah($a['harga_satuan']) ?></td>
                                        <td><?= rupiah($a['total']) ?></td>
                                        <td>
                                            <a onclick="return confirm('Yakin akan dihapus ?. Menghapus data ini akan menghapus data realisasi juga')" href="<?= 'hapus.php?kd=rab&id=' . $a['id_rab']; ?>"><span class="fa fa-trash-o text-danger"> Hapus</span></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                    <th colspan="6">SUB JUMLAH</th>
                                    <th colspan="2"><?= rupiah($tt['tot']) ?></th>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- C -->
                        <hr>
                        <h4>C. BELANJA KEGIATAN</h4>
                        <table id="" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama Barang</th>
                                    <th>Rencana Waktu</th>
                                    <th>QTY</th>
                                    <th>Harga Satuan</th>
                                    <th>Jumlah</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $dt_bos = mysqli_query($conn, "SELECT * FROM rab WHERE lembaga = '01' AND jenis = 'C' ");
                                $tt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) AS tot FROM rab WHERE lembaga = '01' AND jenis = 'C' "));
                                while ($a = mysqli_fetch_assoc($dt_bos)) { ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $a['kode'] ?></td>
                                        <td><?= $a['nama'] ?></td>
                                        <td><?= $a['rencana'] ?></td>
                                        <td><?= $a['qty'] . ' ' . $a['satuan'] ?></td>
                                        <td><?= rupiah($a['harga_satuan']) ?></td>
                                        <td><?= rupiah($a['total']) ?></td>
                                        <td>
                                            <a onclick="return confirm('Yakin akan dihapus ?. Menghapus data ini akan menghapus data realisasi juga')" href="<?= 'hapus.php?kd=rab&id=' . $a['id_rab']; ?>"><span class="fa fa-trash-o text-danger"> Hapus</span></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                    <th colspan="6">SUB JUMLAH</th>
                                    <th colspan="2"><?= rupiah($tt['tot']) ?></th>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- B -->
                        <hr>
                        <h4>D. UMUM</h4>
                        <table id="" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama Barang</th>
                                    <th>Rencana Waktu</th>
                                    <th>QTY</th>
                                    <th>Harga Satuan</th>
                                    <th>Jumlah</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $dt_bos = mysqli_query($conn, "SELECT * FROM rab WHERE lembaga = '01' AND jenis = 'D' ");
                                $tt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) AS tot FROM rab WHERE lembaga = '01' AND jenis = 'D' "));
                                while ($a = mysqli_fetch_assoc($dt_bos)) { ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $a['kode'] ?></td>
                                        <td><?= $a['nama'] ?></td>
                                        <td><?= $a['rencana'] ?></td>
                                        <td><?= $a['qty'] . ' ' . $a['satuan'] ?></td>
                                        <td><?= rupiah($a['harga_satuan']) ?></td>
                                        <td><?= rupiah($a['total']) ?></td>
                                        <td>
                                            <a onclick="return confirm('Yakin akan dihapus ?. Menghapus data ini akan menghapus data realisasi juga')" href="<?= 'hapus.php?kd=rab&id=' . $a['id_rab']; ?>"><span class="fa fa-trash-o text-danger"> Hapus</span></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                    <th colspan="6">SUB JUMLAH</th>
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

<!-- Modal Tambah Data -->
<div class="modal fade" id="tambah_bos" tabindex="-1" role="dialog" aria-hidden="true">
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
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nama Barang/Kegiatan <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="first-name" name="nama" required="required" class="form-control ">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Rencana Waktu <span class="required">*</span>
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
                        <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">QTY/Satuan <span class="required">*</span></label>
                        <div class="col-md-3 col-sm-6 ">
                            <input id="middle-name" class="form-control" type="number" name="qty" required>
                        </div>
                        <div class="col-md-3 col-sm-6 ">
                            <input id="middle-name" class="form-control" type="text" name="satuan" required placeholder="ex : rim,pack,pcs,dll">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Harga Satuan <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6  form-group has-feedback">
                            <input type="text" class="form-control has-feedback-left " id="uang" name="harga_satuan" required>
                            <span class="form-control-feedback left" aria-hidden="true">Rp.</span>
                        </div>

                    </div>
                    <div class="item form-group">
                        <label for="tahun" class="col-form-label col-md-3 col-sm-3 label-align">Tahun Ajaran<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 ">
                            <select name="tahun" id="" required class="form-control">
                                <option value=""> -- pilih tahun -- </option>
                                <option value="2022">2022</option>
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

<!-- Modal Upload-->
<div class="modal fade" id="ex_upload" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Upload File RAB (.xls)</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="item form-group">
                    <label for="tahun" class="col-form-label col-md-3 col-sm-3 label-align">Download Template <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 ">
                        <a href="file_rab/File example/Templetes RAB.xlsx">
                            <button class="btn btn-sm btn-success"><i class="fa fa-file-excel"></i> Download Template RAB (.xls)</button>
                        </a>
                    </div>
                </div>
                <hr>
                <form id="" data-parsley-validate class="form-horizontal form-label-left input_mask" action="upload_ex.php" method="post" enctype="multipart/form-data">
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nama Barang/Kegiatan <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="file" id="" name="file" required="required" class="form-control ">
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name"><span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <button type="submit" name="upload" class="btn btn-success">Upload File</button>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
    $kode = $lembaga . '.' . $bidang .  '.' . $jenis .  '.' . rand();
    $nama = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['nama']));
    $rencana = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['rencana']));
    $qty = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['qty']));
    $satuan = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['satuan']));
    $harga_satuan = htmlspecialchars(mysqli_real_escape_string($conn, preg_replace("/[^0-9]/", "", $_POST['harga_satuan'])));
    $total = $qty * preg_replace("/[^0-9]/", "", $harga_satuan);
    $tahun = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tahun']));

    $sql = mysqli_query($conn, "INSERT INTO rab VALUES ('$id', '$lembaga','$bidang','$jenis','$kode', '$nama','$rencana','$qty','$satuan','$harga_satuan','$total','$tahun', NOW())");
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