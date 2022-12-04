<?php
include 'head.php';

$no = 1;
$kode = $_GET['kode'];
$l = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM rab WHERE kode = '$kode' AND tahun = '$tahun_ajaran' "));
$kalem = $l['lembaga'];
$lem = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$kalem' AND tahun = '$tahun_ajaran' "));
$rel = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as nn FROM realis WHERE kode = '$kode' AND tahun = '$tahun_ajaran' "));

$jns = mysqli_fetch_assoc(mysqli_query($conn, "SELECT jenis, nama, kode, total, IF(jenis = 'A', 'A. Belanja Barang', IF(jenis = 'B', 'B. Langganan Daya dan Jasa', IF(jenis = 'C', 'C. Belanja Kegiatan','D. Umum'))) as nm_jenis, COUNT(jenis) as jml, SUM(total) as tot FROM rab WHERE kode = '$kode' AND tahun = '$tahun_ajaran' "));

$sisa = $jns['total'] - $rel['nn'];
$prc = round(($rel['nn'] / $jns['total']) * 100, 0);
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
                        <h2><i class="fa fa-bars"></i> Realisasi RAB <small>lembaga</small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <center>
                            <h3>
                                <small class=""><u>PEMBELANJAAN RAB <?= strtoupper($l['nama']) ?></u></small>
                            </h3>
                        </center>
                        <br>
                        <!-- info row -->
                        <div class="row invoice-info">
                            <div class="col-sm-6 invoice-col">
                                <table class="countries_list">
                                    <tbody>
                                        <tr>
                                            <td>Nama Lembaga</td>
                                            <td> : <?= $lem['nama'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Jenis Belanja</td>
                                            <td> : <?= $jns['nm_jenis'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Kode Item</td>
                                            <td> : <?= $jns['kode'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Nama Item</td>
                                            <td> : <?= $jns['nama'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Anggaran RAB</td>
                                            <td> : <?= rupiah($jns['total']) ?>
                                                <b><i><?= '(' . rupiah($l['harga_satuan']) . ' x ' . $l['qty'] . ' ' . $l['satuan'] . ')' ?></i></b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Realisasi</td>
                                            <td> : <?= rupiah($rel['nn']) ?></td>
                                        </tr>
                                        <tr>
                                            <td>Sisa</td>
                                            <td> : <?= rupiah($sisa) ?>
                                                <b><i><?= '(' . $sisa / $l['harga_satuan'] . ' ' . $l['satuan'] . ')' ?></i></b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Pembelajaan</td>
                                            <td>
                                                <br>
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                                        role="progressbar" style="width: <?= $prc ?>%"
                                                        aria-valuenow="<?= $prc ?>" aria-valuemin="0"
                                                        aria-valuemax="100"><?= $prc ?>%</div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <hr>
                                <!-- <address>
                                    <button class="btn btn-primary btn-sm"><i class="fa fa-download"></i> Download
                                        Excel</button>
                                    <a href="<?= 'real_detail.php?lm=' . $kalem ?>"><button
                                            class="btn btn-warning btn-sm"><i class="fa fa-chevron-circle-left"></i>
                                            Kembali</button></a>
                                </address> -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-6 invoice-col">
                                <address>
                                    <form action="" method="post">
                                        <h2>Input pembelanjaan</h2>
                                        <div class="table-responsive">
                                            <table class="countries_list">

                                                <tr>
                                                    <th>Ganti Jumlah Satuan *</th>
                                                    <th><input type="number" class="form-control" name="satuan"
                                                            required></th>
                                                </tr>
                                                <tr>
                                                    <th>Harga Satuan *</th>
                                                    <th><input type="text" class="form-control" name="harga_satuan"
                                                            value="<?= rupiah($l['harga_satuan']); ?>" readonly></th>
                                                </tr>

                                                <tr class="mt-1">
                                                    <th></th>
                                                    <th>
                                                        <button class="btn btn-sm btn-success" type="submit"
                                                            name="save"><i class="fa fa-save"></i> Simpan Data</button>
                                                    </th>
                                                </tr>
                                            </table>
                                        </div>
                                    </form>
                                </address>
                            </div>
                        </div>
                        <!-- /.row -->
                    </div>
                </div>
            </div>
        </div>
        <!-- End to do list -->


    </div>
    <div class="clearfix"></div>
</div>
<!-- /page content -->



<?php include 'foot.php'; ?>
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


    $kode = $kode;
    $jumlah = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['satuan']));
    $harga_satuan = htmlspecialchars(mysqli_real_escape_string($conn, preg_replace("/[^0-9]/", "", $_POST['harga_satuan'])));
    $total = $jumlah * $harga_satuan;

    $sql = mysqli_query($conn, "UPDATE rab SET qty = '$jumlah', harga_satuan = '$harga_satuan', total = '$total' WHERE kode = '$kode' ");
    if ($sql) { ?>
<script>
$(document).ready(function() {
    Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Belanja berhasil diubah',
        showConfirmButton: false
    });
    var millisecondsToWait = 1000;
    setTimeout(function() {
        document.location.href = "<?= 'edit_rab.php?kode=' . $kode ?>"
    }, millisecondsToWait);

});
</script>
<?php
    } else {
        echo "
                <script>
                alert('Gagal  simpan');
                </script>
            ";
    }
}


?>