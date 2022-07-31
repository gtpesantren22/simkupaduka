<?php
include 'atas.php';

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Mutasi Santri
            <small>Pesantren</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Mutasi</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Informasi Tanggungan Mutasi Santri</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="example1_bst" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIS</th>
                                        <th>Nama</th>
                                        <th>Alasan</th>
                                        <th>Tgl Mutasi</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $sql = mysqli_query($conn_sekretaris, "SELECT a.*, b.* FROM mutasi a JOIN tb_santri b ON a.nis=b.nis WHERE status = 0 AND aktif = 'Y' ORDER BY id_mutasi DESC ");
                                    while ($dt = mysqli_fetch_assoc($sql)) {
                                        if ($dt['status'] == 0) {
                                            $stas = "<span class='label label-danger'><i class='fa fa-times'></i> Verval Bendahara</span> | <span class='label label-danger'><i class='fa fa-times'></i> Verval Pendataan</span>";
                                        }
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $dt['nis']; ?></td>
                                            <td><?= $dt['nama']; ?></td>
                                            <td><?= $dt['alasan']; ?></td>
                                            <td><?= $dt['tgl_mutasi']; ?></td>
                                            <td><a class="btn btn-success btn-xs" href="<?= 'mutasi_dtl.php?nis=' . $dt['nis']; ?>"><i class="fa fa-search"></i> Lihat Tanggungan</a></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
    </section><!-- /.content -->
</div>


<!-- DataTables -->
<link rel="stylesheet" href="../institution/plugins/datatables/dataTables.bootstrap.css">
<!-- jQuery 2.1.4 -->
<script src="../institution/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="../institution/bootstrap/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="../institution/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../institution/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
    $(function() {
        $("#example1_bst").DataTable();
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
    });
</script>
<?php
include 'bawah.php';


?>