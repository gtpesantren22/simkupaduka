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

                                        <th>Nominal</th>
                                        <th>Tahun</th>
                                        <th>Act</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    $no = 1;
                                    $dt_bos = mysqli_query($conn, "SELECT a.*, b.nama FROM tangg a JOIN tb_santri b ON a.nis=b.nis WHERE a.tahun = '$tahun_ajaran' ");
                                    while ($a = mysqli_fetch_assoc($dt_bos)) { ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $a['nama'] ?></td>
                                            <td><?= $a['briva'] ?></td>
                                            <td>Rp. <?= number_format($a['total'], 0, '.', '.') ?></td>
                                            <td><?= $a['tahun'] ?></td>
                                            <td>
                                                <!--<button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#exampleModal<?= $ls_jns['nis']; ?>">Detail</button>-->
                                                <a href="tg_syh2.php?nis=<?= $a['nis']; ?>"><button class="btn btn-info btn-xs">Discrb</button></a>
                                                <a onclick="return confirm('Yakin akan dihapus ?')" href="<?= 'hapus.php?kd=del_tg&id=' . $a['id_tangg'] ?>">

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
