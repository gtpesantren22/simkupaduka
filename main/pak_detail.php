<?php
include 'head.php';

$kode = $_GET['kode'];
$lm = $_GET['lm'];

$dt_pak = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) AS tt FROM pak_detail WHERE kode_pak = '$kode' AND tahun = '$tahun_ajaran' "));
$dt_rab = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) AS tt FROM rab_sm WHERE lembaga = '$lm' AND tahun = '$tahun_ajaran' "));

$rab = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) AS tt FROM rab WHERE lembaga = '$lm' AND tahun = '$tahun_ajaran' "));
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
        <div class="row">
            <div class="tile_count">
                <div class="row">
                    <div class="col-md-6">
                        <div class="tile_stats_count">
                            <span class="count_top"><i class="fa fa-money"></i> Nominal RAB yang asal</span>
                            <div class="count"><?= rupiah($rab['tt']); ?></div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-warning btn-sm" type="button" data-toggle="modal" data-target=".hapus"><i class="fa fa-refresh"></i> Sinkron RAB PAK
                            Dihapus</button>
                        <button class="btn btn-warning btn-sm" type="button" data-toggle="modal" data-target=".edit">
                            <i class="fa fa-refresh"></i>
                            Sinkron RAB PAK Diedit</button>
                        <button class="btn btn-success btn-sm" type="button" data-toggle="modal" data-target=".upload">
                            <i class="fa fa-upload"></i>
                            Upload RAB Baru</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="">
            <div class="col-md-12 col-sm-12  ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-bars"></i> RAB yang di PAK </h2>
                        <!-- <ul class="nav navbar-right panel_toolbox">
                            <li><a href="info_add.php"><button class="btn btn-info btn-sm"><i class="fa fa-plus-square"></i> Tambah Data</button></a></li>
                        </ul> -->
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <div class="tab-content" id="myTabContent">

                            <!-- Pemasukan BOS -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                                <table id="datatable" class="table table-striped table-bordered table-sm" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Barang/Kegiatan</th>
                                            <th>QTY</th>
                                            <th>Harga Satuan</th>
                                            <th>Total</th>
                                            <th>Ket</th>
                                            <!-- <td>#</td> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $dt_bos = mysqli_query($conn, "SELECT a.*, b.nama FROM pak_detail a JOIN rab b ON a.kode_rab=b.kode WHERE a.kode_pak = '$kode' AND a.tahun = '$tahun_ajaran' ");

                                        while ($r1 = mysqli_fetch_assoc($dt_bos)) {
                                        ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= $r1['kode_rab'] ?></td>
                                                <td><?= $r1['nama'] ?></td>
                                                <td><?= $r1['qty'] ?></td>
                                                <td><?= rupiah($r1['harga_satuan']) ?></td>
                                                <td><?= rupiah($r1['total']) ?></td>
                                                <td class="text-success">
                                                    <?= $r1['ket'] == 'hapus' ? "<span class='badge badge-danger btn-rounded'>hapus</span>" : "<span class='badge badge-success btn-rounded'>edit</span>" ?>
                                                </td>
                                                <!-- <td>
                                                    <?php if ($pakde['status'] === 'belum' || $pakde['status'] === 'ditolak') { ?>
                                                        <a onclick="return confirm('Yakin akan dikembalikan ?')" href="<?= 'pak_set.php?kd=kembali&pak=' . $r1['kode_pak'] . '&id=' . $r1['kode_rab']; ?>"><button class="btn btn-xs btn-danger"><i class="fa fa-trash-o"></i></button></a>
                                                    <?php } ?>
                                                </td> -->
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5">TOTAL</th>
                                            <th colspan="2"><?= rupiah($dt_pak['tt']); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-sm-12  ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-bars"></i> RAB baru yang Akan diajukan </h2>
                        <!-- <ul class="nav navbar-right panel_toolbox">
                            <li><a href="info_add.php"><button class="btn btn-info btn-sm"><i class="fa fa-plus-square"></i> Tambah Data</button></a></li>
                        </ul> -->
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <div class="tab-content" id="myTabContent">

                            <!-- Pemasukan BOS -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                                <table id="datatable2" class="table table-striped table-bordered table-sm" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Barang/Kegiatan</th>
                                            <th>QTY</th>
                                            <th>Satuan</th>
                                            <th>Harga Satuan</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $dt_sm = mysqli_query($conn, "SELECT * FROM rab_sm WHERE lembaga = '$lm' AND tahun = '$tahun_ajaran' ");
                                        while ($r1 = mysqli_fetch_assoc($dt_sm)) {
                                        ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= $r1['kode'] ?></td>
                                                <td><?= $r1['nama'] ?></td>
                                                <td><?= $r1['qty'] ?></td>
                                                <td><?= $r1['satuan'] ?></td>
                                                <td><?= rupiah($r1['harga_satuan']) ?></td>
                                                <td><?= rupiah($r1['total']) ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6">TOTAL</th>
                                            <th><?= rupiah($dt_rab['tt']); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <?= var_dump($dt_sm) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<!-- /page content -->

<div class="modal fade hapus" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="" method="post">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel2">Sinkron PAK yang Dihapus</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Fitur ini akan menghapus item RAB yang di PAK dengan status DIHAPUS</p>
                    <p>Yakin akan dilanjutkan ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="hapus" class="btn btn-warning">Sinkron</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="" method="post">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel2">Sinkron PAK yang Diedit</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Fitur ini akan mengganti item RAB yang di PAK dengan status DIEDIT</p>
                    <p>Yakin akan dilanjutkan ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="edit" class="btn btn-warning">Sinkron</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade upload" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form action="" method="post">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel2">Upload RAB yang baru</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Fitur ini akan mengupload item RAB yang baru.</p>
                    <p>Yakin akan dilanjutkan ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="upload" class="btn btn-success">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
<script src="../main/dist/sweetalert2.all.min.js"></script>
<script src="../main/vendors/ckeditor-full/ckeditor.js"></script>

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


<?php
if (isset($_POST['hapus'])) {

    $data = mysqli_query($conn, "SELECT * FROM pak_detail WHERE ket = 'hapus' AND kode_pak = '$kode' AND tahun = '$tahun_ajaran' ");
    while ($a = mysqli_fetch_assoc($data)) {
        $kd_rab = $a['kode_rab'];
        $sql = mysqli_query($conn, "DELETE FROM rab WHERE kode = '$kd_rab' ");
    }
    if ($sql) { ?>
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'RAB Berhasil disinkron',
                showConfirmButton: false
            });
            var millisecondsToWait = 1000;
            setTimeout(function() {
                document.location.href = "pak.php"
            }, millisecondsToWait);
        </script>

    <?php    }
}

if (isset($_POST['edit'])) {

    $data = mysqli_query($conn, "SELECT * FROM pak_detail WHERE ket = 'edit' AND kode_pak = '$kode' AND tahun = '$tahun_ajaran' ");
    while ($a = mysqli_fetch_assoc($data)) {
        $kd_rab = $a['kode_rab'];
        $jml = $a['qty'];
        $sql = mysqli_query($conn, "UPDATE rab SET qty = qty - $jml WHERE kode = '$kd_rab' ");
    }
    if ($sql) { ?>
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'RAB Berhasil disinkron',
                showConfirmButton: false
            });
            var millisecondsToWait = 1000;
            setTimeout(function() {
                document.location.href = "pak.php"
            }, millisecondsToWait);
        </script>

    <?php    }
}

if (isset($_POST['upload'])) {

    $data = mysqli_query($conn, "INSERT INTO rab SELECT * FROM rab_sm WHERE lembaga = '$lm' AND tahun = '$tahun_ajaran' ");
    // $data = mysqli_query($conn, "SELECT * FROM rab_sm WHERE lembaga = '$lm' AND tahun = '$tahun_ajaran' ");
    // while ($a = mysqli_fetch_assoc($data)) {
    //     $kd_rab = $a['kode'];
    //     $jml = $a['qty'];
    //     $sql = mysqli_query($conn, "DELETE FROM rab  WHERE kode = '$kd_rab' ");
    // }
    if ($data) {

        $psn = '
*INFORMASI PENOLAKAN PAK*

Ada pengajuan baru dari :
    
Lembaga : ' . $lm['nama'] . '
Kode PAK : ' . $kode . '

PAK ditolak oleh accounting dengan catatan :
*' . mysqli_real_escape_string($conn, $_POST['catatan']) . '*

Terimakasih';

        kirim_group($api_key, '120363042148360147@g.us', $psn);
        kirim_group($api_key, '120363042148360147@g.us', $psn);

    ?>
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'RAB Berhasil disinkron',
                showConfirmButton: false
            });
            var millisecondsToWait = 1000;
            setTimeout(function() {
                document.location.href = "pak.php"
            }, millisecondsToWait);
        </script>

<?php    }
}

?>