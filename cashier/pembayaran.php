<?php
include 'atas.php';
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Data Pembayaran Syahriyah Santri
            <small>Tahun Ajaran 2021/2022</small>
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
                        <h3 class="box-title">Daftar Pembyaran</h3>
                        <a href="tg_syh.php">
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
                                        <th>Tanggal Bayar</th>
                                        <th>Bulan</th>
                                        <th>Nominal</th>
                                        <th>Tahun Ajaran</th>
                                        <th>Penerima</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $rls = mysqli_query($conn, "SELECT * FROM pembayaran WHERE tahun = '$tahun_ajaran' ORDER BY tgl DESC ");
                                    while ($ls_jns = mysqli_fetch_assoc($rls)) { ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $ls_jns['nama']; ?></td>
                                        <td><?= $ls_jns['tgl']; ?></td>
                                        <td><?= $bulan[$ls_jns['bulan']]; ?></td>
                                        <td><?= rupiah($ls_jns['nominal']); ?></td>
                                        <td><?= $ls_jns['tahun']; ?></td>
                                        <td><?= $ls_jns['kasir']; ?></td>
                                        <td>
                                            <button class="btn btn-warning btn-xs">Edit</button>
                                            <a onclick="return confirm('Yakin akan dihapus ?')"
                                                href="<?= 'hapus.php?kd=del_by&id=' . $ls_jns['id'] ?>">
                                                <button class="btn btn-danger btn-xs">Hapus</button>
                                            </a>
                                        </td>
                                    </tr>
                                    <!-- Modal -->

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