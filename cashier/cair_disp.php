<?php
include 'atas.php';
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Pencairan pengajuan Disposisi
            <small>Realiasasi Belanja </small>
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
                        <h3 class="box-title">Pengajuan Realiasasi Disposisi</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="example1_bst" class="table table-bordered table-striped">
                                <thead>
                                    <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Lembaga</th>
                                        <th>Periode</th>
                                        <th>Verifikasi</th>
                                        <th>Persetujuan</th>
                                        <th>Peleburan</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $rls = mysqli_query($conn, "SELECT a.*, b.nama FROM pengajuan a JOIN lembaga b ON a.lembaga=b.kode WHERE a.verval = 1 AND a.apr = 1 AND a.kode_pengajuan LIKE '%DISP.%' AND a.tahun = '$tahun_ajaran' GROUP BY a.kode_pengajuan ");
                                    while ($ls_jns = mysqli_fetch_assoc($rls)) {
                                        $kode = $ls_jns['kode_pengajuan'];
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $ls_jns['kode_pengajuan']; ?></td>
                                            <td><?= $ls_jns['nama']; ?></td>
                                            <td><?= $bulan[$ls_jns['bulan']] . ' ' . $ls_jns['tahun']; ?></td>
                                            <td>
                                                <?= $ls_jns['verval'] == 1 ? "<span class='label label-success'><i class='fa fa-check'></i> sudah</span>" : "<span class='label label-danger'><i class='fa fa-times'></i> belum</span>"; ?>
                                            </td>
                                            <td>
                                                <?= $ls_jns['apr'] == 1 ? "<span class='label label-success'><i class='fa fa-check'></i> sudah</span>" : "<span class='label label-danger'><i class='fa fa-times'></i> belum</span>"; ?>
                                            </td>
                                            <td>
                                                <?= $ls_jns['cair'] == 1 ? "<span class='label label-success'><i class='fa fa-check'></i> sudah</span>" : "<span class='label label-danger'><i class='fa fa-times'></i> belum</span>"; ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($ls_jns['verval'] == 1 && $ls_jns['apr'] == 1) { ?>
                                                    <a href="<?= 'cair_proses.php?kode=' . $kode ?>"><button class="btn bg-purple btn-xs"><i class="fa fa-search"></i> Lihat detail / <i class="fa fa-hourglass-half"></i> Cairkan</button></a>
                                                <?php } ?>
                                            </td>
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
