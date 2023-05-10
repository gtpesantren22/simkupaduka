<?php
include 'head.php';
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
                        <h2><i class="fa fa-bars"></i> PAK Lembaga </h2>
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
                                            <th>Kode PAK</th>
                                            <th>Tanggal PAK</th>
                                            <th>Status</th>
                                            <th>Tahun</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $dt_bos = mysqli_query($conn, "SELECT a.*, b.nama FROM pak a JOIN lembaga b ON a.lembaga=b.kode WHERE a.tahun = '$tahun_ajaran' AND a.status = 'proses' ");
                                        while ($a = mysqli_fetch_assoc($dt_bos)) {
                                        ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= $a['kode_pak']; ?></td>
                                                <td><?= $a['nama']; ?></td>
                                                <td><?= $a['tgl_pak']; ?></td>
                                                <td><?= $a['status']; ?></td>
                                                <td><?= $a['tahun']; ?></td>
                                                <td><a href="<?= 'pak_detail.php?kode=' . $a['kode_pak'] . '&lm=' . $a['lembaga'] ?>"><button class="btn btn-info btn-sm"><i class="fa fa-search"></i>
                                                            Cek PAK</button></a></td>
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
if (isset($_POST['del'])) {
    $id_info =  $_POST['id_info'];

    $sql = mysqli_query($conn, "DELETE FROM info WHERE id_info = '$id_info' AND tahun = '$tahun_ajaran' ");
    if ($sql) { ?>
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Informasi berhasil dihapus',
                showConfirmButton: false
            });
            var millisecondsToWait = 1000;
            setTimeout(function() {
                document.location.href = "info.php"
            }, millisecondsToWait);
        </script>

<?php    }
}

?>