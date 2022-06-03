<?php
include 'head.php';

$no = 1;
$kode = $_GET['kode'];
$l = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM rab WHERE kode = '$kode' "));
$kalem = $l['lembaga'];
$lem = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$kalem' "));
$rel = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as nn FROM realis WHERE kode = '$kode' "));

$jns = mysqli_fetch_assoc(mysqli_query($conn, "SELECT jenis, nama, kode, total, IF(jenis = 'A', 'A. Belanja Barang', IF(jenis = 'B', 'B. Langganan Daya dan Jasa', IF(jenis = 'C', 'C. Belanja Kegiatan','D. Umum'))) as nm_jenis, COUNT(jenis) as jml, SUM(total) as tot FROM rab WHERE kode = '$kode' "));

$sisa = $jns['total'] - $rel['nn'];
$prc = round(($rel['nn'] / $jns['total']) * 100, 0);
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
                                            <td> : <?= rupiah($jns['total']) ?></td>
                                        </tr>
                                        <tr>
                                            <td>Realisasi</td>
                                            <td> : <?= rupiah($rel['nn']) ?></td>
                                        </tr>
                                        <tr>
                                            <td>Sisa</td>
                                            <td> : <?= rupiah($sisa) ?></td>
                                        </tr>
                                        <tr>
                                            <td>Pembelajaan</td>
                                            <td>
                                                <br>
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: <?= $prc ?>%" aria-valuenow="<?= $prc ?>" aria-valuemin="0" aria-valuemax="100"><?= $prc ?>%</div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <hr>
                                <address>
                                    <button class="btn btn-primary btn-sm"><i class="fa fa-download"></i> Download Excel</button>
                                    <a href="<?= 'real_detail.php?lm=' . $kalem ?>"><button class="btn btn-warning btn-sm"><i class="fa fa-chevron-circle-left"></i> Kembali</button></a>
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-6 invoice-col">
                                <address>
                                    <form action="" method="post">
                                        <h2>Input pembelanjaan</h2>
                                        <div class="table-responsive">
                                            <table class="countries_list">
                                                <tr>
                                                    <th>Tanggal Belanja *</th>
                                                    <th><input type="text" class="form-control" name="tgl" id="datePick" required></th>
                                                </tr>
                                                <tr>
                                                    <th>Nama PJ *</th>
                                                    <th><input type="text" class="form-control" name="pj" required></th>
                                                </tr>
                                                <tr>
                                                    <th>Nominal *</th>
                                                    <th><input type="text" class="form-control" name="nominal" id="uang" required></th>
                                                </tr>
                                                <tr>
                                                    <th>Ket</th>
                                                    <th><textarea class="form-control" name="ket"></textarea>
                                                </tr>
                                                <tr>
                                                    <th colspan="2">
                                                        <hr>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <th>
                                                        <button class="btn btn-sm btn-success" type="submit" name="save"><i class="fa fa-save"></i> Simpan Data</button>
                                                        <button class="btn btn-sm btn-warning" type="reset"><i class="fa fa-sync-alt"></i> Reset</button>
                                                    </th>
                                                </tr>
                                            </table>
                                        </div>
                                    </form>
                                </address>
                            </div>
                        </div>
                        <!-- /.row -->
                        <hr>
                        <div class="table-responsive">
                            <table class="table countries_list">
                                <thead>
                                    <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                        <th>No</th>
                                        <th>Kode RAB</th>
                                        <th>Tanggal</th>
                                        <th>PJ</th>
                                        <th>Nominal</th>
                                        <th>Ket</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $rls = mysqli_query($conn, "SELECT * FROM realis WHERE kode = '$kode' ");
                                    while ($ls_jns = mysqli_fetch_assoc($rls)) {
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $ls_jns['kode'] ?></a></td>
                                            <td><?= $ls_jns['tgl'] ?></a></td>
                                            <td><?= $ls_jns['pj'] ?></a></td>
                                            <td><?= rupiah($ls_jns['nominal']) ?></td>
                                            <td><?= $ls_jns['ket'] ?></a></td>
                                            <td><a onclick="return confirm('Yakin akan dihapus ?')" href="<?= 'hapus.php?kd=del_real&id=' . $ls_jns['id_realis'] ?>"><span class="fa fa-trash"></span> Hapus</a></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <th colspan="4">TOTAL</th>
                                        <th><?= rupiah($rel['nn']) ?></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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
    $lembaga = $l['lembaga'];
    $bidang = $l['bidang'];
    $jenis = $l['jenis'];
    $kode = $kode;
    $nominal = htmlspecialchars(mysqli_real_escape_string($conn, preg_replace("/[^0-9]/", "", $_POST['nominal'])));
    $tgl = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tgl']));
    $pj = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['pj']));
    $tahun = $l['tahun'];
    $ket = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['ket']));

    if ($nominal > $sisa) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Maaf...',
                    text: 'Nominal belanja anda melebihi sisa Anggaran RAB'
                })
                var millisecondsToWait = 2000;
                setTimeout(function() {
                    document.location.href = "<?= 'real_add.php?kode=' . $kode ?>"
                }, millisecondsToWait);

            });
        </script>
        <?php
    } else {
        $sql = mysqli_query($conn, "INSERT INTO realis VALUES ('$id', '$lembaga','$bidang','$jenis','$kode', '$nominal', '$tgl', '$pj', '-','$tahun','$ket', 0)");
        if ($sql) { ?>
            <script>
                $(document).ready(function() {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Belanja berhasil tersimpan',
                        showConfirmButton: false
                    });
                    var millisecondsToWait = 1000;
                    setTimeout(function() {
                        document.location.href = "<?= 'real_add.php?kode=' . $kode ?>"
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
}

?>