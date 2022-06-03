<?php
include 'atas.php';

$ss = mysqli_query($conn, "SELECT * FROM pengajuan WHERE lembaga = '$kol' ORDER BY no_urut DESC LIMIT 1");
$ck = mysqli_fetch_assoc($ss);
$ck2 = mysqli_num_rows($ss);
$lm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$kol' "));
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Pengajuan Realiasasi
            <small>Realiasasi Belanja</small>
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
                        <h3 class="box-title">Pengajuan Realiasasi RAB</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?php if ($ck2 < 1) { ?>
                            <button class="btn btn-success" data-toggle="modal" data-target="#staticBackdrop"><i class="fa fa-plus-square"></i> Tambah Pengajuan Baru</button>
                            <br><br>
                        <?php } elseif ($ck['verval'] == 1 && $ck['spj'] != 2) { ?>
                            <button class="btn btn-success" disabled><i class="fa fa-plus-square"></i> Tambah Pengajuan Baru</button>
                            <br><br>
                        <?php } elseif ($ck['verval'] == 1 && $ck['spj'] == 2 || $ck['verval'] == NULL) { ?>
                            <button class="btn btn-success" data-toggle="modal" data-target="#staticBackdrop"><i class="fa fa-plus-square"></i> Tambah Pengajuan Baru</button>
                            <br><br>
                        <?php }  ?>
                        <div class="table-responsive">
                            <table id="example1_bst" class="table table-bordered table-striped">
                                <thead>
                                    <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Bulan</th>
                                        <th>Tahun</th>
                                        <th>Verifikasi</th>
                                        <th>Persetujuan</th>
                                        <th>Pencairan</th>
                                        <th>SPJ</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $rls = mysqli_query($conn, "SELECT * FROM pengajuan WHERE lembaga = '$kol' GROUP BY kode_pengajuan ");
                                    while ($ls_jns = mysqli_fetch_assoc($rls)) {
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $ls_jns['kode_pengajuan']; ?></td>
                                            <td><?= $bulan[$ls_jns['bulan']]; ?></td>
                                            <td><?= $ls_jns['tahun']; ?></td>
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
                                                <?php if ($ls_jns['spj'] == 0) { ?>
                                                    <span class="label label-danger"><i class="fa fa-times"></i> belum upload</span>
                                                <?php } else if ($ls_jns['spj'] == 1) { ?>
                                                    <button class="btn btn-warning btn-xs"><i class="fa fa-spinner fa-refresh-animate"></i>
                                                        proses verifikasi</button>
                                                <?php } else { ?>
                                                    <span class="label label-success"><i class="fa fa-check"></i> sudah selesai</span>
                                                <?php } ?>
                                            </td>
                                            <td><a href="<?= 'pengajuan_add.php?kode=' . $ls_jns['kode_pengajuan'] ?>"><button class="btn btn-info btn-xs"><i class="fa fa-search"></i> cek</button></a></td>
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

<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class=" modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Tambah Pemasukan BOS</h4>
            </div>
            <form action="" method="post" class="form-horizontal">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Lembaga *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="<?= $lm['nama'] ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Bulan *</label>
                        <div class="col-sm-10">
                            <select name="bulan" class="form-control" required>
                                <option value=""> -- pilih bulan -- </option>
                                <?php
                                for ($i = 1; $i < count($bulan); $i++) { ?>
                                    <option value="<?= $i ?>"><?= $bulan[$i] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Tahun *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="tahun" value="<?= date('Y') ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" name="save" class="btn btn-success">Buat Pengajuan</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- DataTables -->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<!-- jQuery 2.1.4 -->
<script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
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

<?php
if (isset($_POST['save'])) {

    $pj = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(no_urut) as nu FROM pengajuan"));
    $urut = $pj['nu'] + 1;
    $id = $uuid;
    $lembaga = $kol;
    $tahun = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tahun']));
    $bln = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['bulan']));
    $kd_pjn = $lembaga . '.' . date('dd') . '.' . $bln . '.' . $tahun;


    $cek = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pengajuan WHERE kode_pengajuan = '$kd_pjn' "));
    if ($cek == 0) {
        $sql = mysqli_query($conn, "INSERT INTO pengajuan(id_pn, kode_pengajuan, lembaga, bulan, tahun, no_urut, at, stts) VALUES ('$id', '$kd_pjn','$lembaga','$bln','$tahun', '$urut', NOW(), 'no') ");
        $sql1 = mysqli_query($conn, "INSERT INTO spj(id_spj, kode_pengajuan, lembaga, bulan, tahun, no_urut) VALUES ('$id', '$kd_pjn','$lembaga','$bln','$tahun','$urut') ");

        if ($sql && $sql1) {
?>
            <script>
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Pengajuan baru berhasil tersimpan',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "<?= 'pengajuan.php' ?>"
                }, millisecondsToWait);
            </script>
<?php
        }
    }
}

?>