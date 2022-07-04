<?php
include 'atas.php';
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Data Tanggungan Santri
            <small>Tanggungan Perbulan</small>
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
                        <h3 class="box-title">Tanggungan Perbulan</h3>
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
                                        <th>Kelas Fomal</th>
                                        <th>Kelas Madin</th>
                                        <th>Tanggungan</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $rls = mysqli_query($conn, "SELECT a.*, b.* FROM tanggungan a JOIN tb_santri b ON a.nis=b.nis AND a.tahun = '$tahun_ajaran' ");
                                    while ($ls_jns = mysqli_fetch_assoc($rls)) {
                                        $tg = mysqli_query($conn, "SELECT * FROM tg_lembaga WHERE tahun = '$tahun_ajaran'");
                                        while ($tgr = mysqli_fetch_assoc($tg)) {
                                            $tgn = $ls_jns[$tgr['kode']];
                                        }
                                        $jml = $ls_jns['syahriah'] + $tgn;
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $ls_jns['nama']; ?></td>
                                            <td><?= $ls_jns['k_formal'] . ' ' . $ls_jns['t_formal']; ?></td>
                                            <td><?= $ls_jns['k_madin'] . ' ' . $ls_jns['r_madin']; ?></td>
                                            <td><?= rupiah($jml); ?></td>
                                            <td>
                                                <!--<button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#exampleModal<?= $ls_jns['nis']; ?>">Detail</button>-->
                                                <a href="tanggungan_e.php?nis=<?= $ls_jns['nis']; ?>"><button class="btn btn-warning btn-xs">Edit</button></a>
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
