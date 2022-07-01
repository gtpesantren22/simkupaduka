<?php
include 'atas.php';
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Data Tanggungan Santri TA 2022/2023
            <small>Tanggungan Pertahun</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Pengajuan</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Tanggungan Setahun</h3>
                        <a href="tg_add.php">
                            <button class="btn btn-success pull-right"><i class="fa fa-plus"></i> Tambah Baru</button>
                        </a>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="example1_bst" class="table table-bordered table-striped">
                                <thead>
                                    <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Briva</th>
                                        <th>Kelas</th>
                                        <th>Tanggungan</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $rls = mysqli_query($conn, "SELECT a.*, b.* FROM tangg a JOIN tb_santri b ON a.nis=b.nis  ");
                                    while ($ls_jns = mysqli_fetch_assoc($rls)) {
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $ls_jns['nama']; ?></td>
                                            <td><?= $ls_jns['briva']; ?></td>
                                            <td><?= $ls_jns['k_formal'] . ' ' . $ls_jns['t_formal']; ?></td>
                                            <td><?= rupiah($ls_jns['total']); ?></td>
                                            <td>
                                                <!--<button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#exampleModal<?= $ls_jns['nis']; ?>">Detail</button>-->
                                                <a href="tg_syh2.php?nis=<?= $ls_jns['nis']; ?>"><button class="btn btn-info btn-xs">Discrb</button></a>
                                                <a onclick="return confirm('Yakin akan dihapus ?')" href="<?= 'hapus.php?kd=del_tg&id=' . $ls_jns['id_tanggungan'] ?>">
                                                    <button class="btn btn-danger btn-xs">Hapus</button>
                                                </a>
                                            </td>
                                        </tr>
                                        <!-- Modal -->
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                        <th colspan="4">Total Pemasukan dari Syahrian Santri</th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
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
<!-- DataTables -->
<link rel="stylesheet" href="../institution/plugins/datatables/dataTables.bootstrap.css">
<!-- Select2 -->
<link rel="stylesheet" href="../institution/plugins/select2/select2.min.css">
<!-- jQuery 2.1.4 -->
<script src="../institution/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="../institution/bootstrap/js/bootstrap.min.js"></script>

<!-- DataTables -->
<script src="../institution/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../institution/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
    function masuk(txt, data) {
        document.getElementById('nis').value = data; // ini berfungsi mengisi value yang ber id textbox
        $("#tambah").modal('hide'); // ini berfungsi untuk menyembunyikan modal
    }
    $(function() {
        $(".select2").select2();
        $("#example1_bst").DataTable();
        $("#example2_bst").DataTable();
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
