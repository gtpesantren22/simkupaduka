<?php
include 'head.php';

$kode = $_GET['kode'];
$l = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$kode' AND tahun = '$tahun_ajaran' "));
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
                        <h2><i class="fa fa-bars"></i> Daftar Rencana Belanja <small>lembaga</small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <center>
                            <h3>
                                <small class=""><u>RENCANA ANGGARAN BELANJA <?= strtoupper($l['nama']) ?></u></small>
                            </h3>
                        </center>
                        <br>
                        <!-- info row -->
                        <div class="row invoice-info">
                            <div class="col-sm-2 invoice-col">
                                <address>
                                    <strong>Nama Lembaga</strong><br>
                                    <strong>Pelakasana</strong><br>
                                    <strong>PJ/HP</strong><br>
                                    <strong>Pelaksanaan</strong>
                                </address>
                                <hr>
                                <address>
                                    <a href="rab.php"><button class="btn btn-warning btn-sm"><i
                                                class="fa fa-chevron-circle-left"></i> Kembali</button></a>
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-3 invoice-col">
                                <address>
                                    <strong>: <?= $l['nama'] ?></strong><br>
                                    <strong>: <?= $l['pelaksana'] ?></strong><br>
                                    <strong>: <?= $l['pj'] . ' / ' . $l['hp'] ?></strong><br>
                                    <strong>: <?= $l['waktu'] ?></strong>
                                </address>
                                <hr>
                                <address>
                                    <button class="btn btn-primary btn-sm"><i class="fa fa-download"></i> Download
                                        Excel</button><br>
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
                                        FROM rab WHERE lembaga = '$kode' AND tahun = '$tahun_ajaran' "));
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


                        <div class="table-responsive">
                            <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama Barang</th>
                                        <th>Rencana Waktu</th>
                                        <th>QTY</th>
                                        <th>Harga Satuan</th>
                                        <th>Jumlah</th>
                                        <th>%</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $dt_bos = mysqli_query($conn, "SELECT * FROM rab WHERE lembaga = '$kode' AND tahun = '$tahun_ajaran' ");
                                    $tt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) AS tot FROM rab WHERE lembaga = '$kode' AND tahun = '$tahun_ajaran' "));
                                    while ($a = mysqli_fetch_assoc($dt_bos)) {
                                        $kd_rab = $a['kode'];
                                        $rls  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT vol FROM realis WHERE kode = '$kd_rab'"));
                                    ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $a['kode'] ?></td>
                                        <td><?= $a['nama'] ?></td>
                                        <td><?= $a['rencana'] ?></td>
                                        <td><?= $a['qty'] . ' ' . $a['satuan'] ?></td>
                                        <td><?= rupiah($a['harga_satuan']) ?></td>
                                        <td><?= rupiah($a['total']) ?></td>
                                        <td><?= round($rls['vol'] / $a['qty'] * 100, 1); ?>%</td>
                                        <td>
                                            <a onclick="return confirm('Yakin akan dihapus ?. Menghapus data ini akan menghapus data realisasi juga')"
                                                href="<?= 'hapus.php?kd=rab&id=' . $a['id_rab']; ?>"><span
                                                    class="fa fa-trash-o text-danger"> Hapus</span></a>
                                            <a href="<?= 'edit_rab.php?kode=' . $a['kode']; ?>"><span
                                                    class="fa fa-edit text-warning"> Edit</span></a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                        <th colspan="6">SUB JUMLAH</th>
                                        <th colspan="3"><?= rupiah($tt['tot']) ?></th>
                                    </tr>
                                </tfoot>
                            </table>

                        </div>
                        <!-- A -->
                        <!-- <h4>A. BELANJA BARANG</h4>
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
                                $dt_bos = mysqli_query($conn, "SELECT * FROM rab WHERE lembaga = '$kode' AND jenis = 'A' AND tahun = '$tahun_ajaran' ");
                                $tt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) AS tot FROM rab WHERE lembaga = '$kode' AND jenis = 'A' AND tahun = '$tahun_ajaran' "));
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
                                        <a onclick="return confirm('Yakin akan dihapus ?. Menghapus data ini akan menghapus data realisasi juga')"
                                            href="<?= 'hapus.php?kd=rab&id=' . $a['id_rab']; ?>"><span
                                                class="fa fa-trash-o text-danger"> Hapus</span></a>
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
                        </table> -->

                        <!-- B -->
                        <hr>
                        <!-- <h4>B. LANGGANAN DAYA & JASA</h4>
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
                                $dt_bos = mysqli_query($conn, "SELECT * FROM rab WHERE lembaga = '$kode' AND jenis = 'B' AND tahun = '$tahun_ajaran' ");
                                $tt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) AS tot FROM rab WHERE lembaga = '$kode' AND jenis = 'B' AND tahun = '$tahun_ajaran' "));
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
                                        <a onclick="return confirm('Yakin akan dihapus ?. Menghapus data ini akan menghapus data realisasi juga')"
                                            href="<?= 'hapus.php?kd=rab&id=' . $a['id_rab']; ?>"><span
                                                class="fa fa-trash-o text-danger"> Hapus</span></a>
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
                        </table> -->

                        <!-- C -->
                        <hr>
                        <!-- <h4>C. BELANJA KEGIATAN</h4>
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
                                $dt_bos = mysqli_query($conn, "SELECT * FROM rab WHERE lembaga = '$kode' AND jenis = 'C' AND tahun = '$tahun_ajaran' ");
                                $tt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) AS tot FROM rab WHERE lembaga = '$kode' AND jenis = 'C' AND tahun = '$tahun_ajaran' "));
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
                                        <a onclick="return confirm('Yakin akan dihapus ?. Menghapus data ini akan menghapus data realisasi juga')"
                                            href="<?= 'hapus.php?kd=rab&id=' . $a['id_rab']; ?>"><span
                                                class="fa fa-trash-o text-danger"> Hapus</span></a>
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
                        </table> -->

                        <!-- B -->
                        <hr>
                        <!-- <h4>D. UMUM</h4>
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
                                $dt_bos = mysqli_query($conn, "SELECT * FROM rab WHERE lembaga = '$kode' AND jenis = 'D' AND tahun = '$tahun_ajaran' ");
                                $tt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) AS tot FROM rab WHERE lembaga = '$kode' AND jenis = 'D' AND tahun = '$tahun_ajaran'"));
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
                                        <a onclick="return confirm('Yakin akan dihapus ?. Menghapus data ini akan menghapus data realisasi juga')"
                                            href="<?= 'hapus.php?kd=rab&id=' . $a['id_rab']; ?>"><span
                                                class="fa fa-trash-o text-danger"> Hapus</span></a>
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
                        </table> -->
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