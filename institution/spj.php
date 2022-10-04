<?php
include 'atas.php';
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            SPJ
            <small>Realiasasi Belanja</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">SPJ</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Daftar SPJ Belanja</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="example1_bst" class="table table-bordered table-striped">
                                <thead>
                                    <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                        <th>No</th>
                                        <th>Kode Pengajuan</th>
                                        <th>Periode</th>
                                        <th>Nominal Pengajuan</th>
                                        <th>Status</th>
                                        <th>File</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $rls = mysqli_query($conn, "SELECT a.*, b.cair as b_cair, b.kode_pengajuan as b_kode FROM spj a JOIN pengajuan b ON a.kode_pengajuan=b.kode_pengajuan WHERE a.lembaga = '$kol' AND a.tahun = '$tahun_ajaran' ");
                                    while ($ls_jns = mysqli_fetch_assoc($rls)) {
                                        $kd_pj = $ls_jns['kode_pengajuan'];
                                        $jml = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jml FROM real_sm WHERE kode_pengajuan = '$kd_pj' AND tahun = '$tahun_ajaran' "));
                                        $jml2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jml FROM realis WHERE kode_pengajuan = '$kd_pj' AND tahun = '$tahun_ajaran' "));
                                        $pjan = $jml['jml'] + $jml2['jml'];

                                        if (preg_match("/DISP./i", $kd_pj)) {
                                            $rt = "<span class='label label-danger'>DISPOSISI</span>";
                                        } else {
                                            $rt = '';
                                        }
                                    ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $ls_jns['kode_pengajuan'] . ' ' . $rt; ?></td>
                                        <td><?= $bulan[$ls_jns['bulan']] . ' ' . $ls_jns['tahun']; ?></td>
                                        <td><?= rupiah($pjan); ?></td>
                                        <td>
                                            <?php if ($ls_jns['stts'] == 0) { ?>
                                            <span class="label label-danger"><i class="fa fa-times"></i> belum
                                                upload</span>
                                            <?php if ($ls_jns['b_cair'] == 1) { ?>
                                            | <button class="btn btn-success btn-xs" data-toggle="modal"
                                                data-target="#stt<?= $ls_jns['id_spj']; ?>"><i class="fa fa-upload"></i>
                                                Upload berkas SPJ</button>
                                            <?php } ?>
                                            <?php } else if ($ls_jns['stts'] == 1) { ?>
                                            <button class="btn btn-warning btn-xs"><i
                                                    class="fa fa-spinner fa-refresh-animate"></i>
                                                proses verifikasi</button>
                                            <button class="btn btn-success btn-xs" data-toggle="modal"
                                                data-target="#stt<?= $ls_jns['id_spj']; ?>"><i class="fa fa-upload"></i>
                                                Upload ulang berkas SPJ</button>
                                            <?php } else { ?>
                                            <span class="label label-success"><i class="fa fa-check"></i> sudah
                                                selesai</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if ($ls_jns['stts'] == 1) { ?>
                                            <a href="<?= 'file_spj/' . $ls_jns['file_spj'] ?>"><i
                                                    class="fa fa-download"></i> <?= $ls_jns['file_spj'] ?></a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <!-- Modal -->
                                    <div class="modal fade" id="stt<?= $ls_jns['id_spj']; ?>" data-backdrop="static"
                                        data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="" method="post" class="form-horizontal"
                                                enctype="multipart/form-data">
                                                <input type="hidden" name="bulan" value="<?= $ls_jns['bulan']; ?>">
                                                <input type="hidden" name="tahun" value="<?= $ls_jns['tahun']; ?>">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">Upload Berkas
                                                            SPJ</h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="inputEmail3" class="col-sm-3 control-label">Kode
                                                                Pengajuan</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" name="kode" class="form-control"
                                                                    value="<?= $ls_jns['kode_pengajuan']; ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="form-group">
                                                            <label for="inputEmail3"
                                                                class="col-sm-3 control-label">Pilih berkas</label>
                                                            <div class="col-sm-9">
                                                                <input type="file" class="form-control" name="file"
                                                                    required>
                                                            </div>
                                                        </div>
                                                        <br><br>
                                                        <b style="color: red;"><i>Catatan :</i></b><br>
                                                        <b style="color: red;"><i>Untuk Upload berkas SPJ dilakukan 1
                                                                kali setiap pengajuan. Jika ada penguploadan baru, maka
                                                                file lama akan terhapus</i></b><br>
                                                        <b style="color: red;"><i>File yang doperbolehkan : WORD, EXCEL,
                                                                dan PDF (doc,docx,xls,xlsx,pdf)</i></b>
                                                        <br>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" name="upload" class="btn btn-success"><i
                                                                class="fa fa-save"></i> Simpan berkas</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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

if (isset($_POST['upload'])) {
    $id = $uuid;
    $kode  = $_POST['kode'];
    $bulan  = $_POST['bulan'];
    $tahun  = $_POST['tahun'];
    $date = date('Y-m-d');
    $at = date('Y-m-d H:i:s');

    $ekstensi =  array('doc', 'docx', 'xls', 'xlsx', 'pdf');
    $filename = $_FILES['file']['name'];
    $ukuran = $_FILES['file']['size'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $extensi = explode('.', $filename);

    if (preg_match("/DISP./i", $kode)) {
        $rt = "*(DISPOSISI)*";
    } else {
        $rt = '';
    }

    $psn = '
*INFORMASI VERIFIKASI SPJ* ' . $rt . '

Ada pelaporan SPJ dari :
    
Lembaga : ' . $kol_nama . '
Kode Pengajuan : ' . $kode . '
Pada : ' . $at . '

*_dimohon kepada TIM ACCOUNTING untuk segera mengecek nya di https://simkupaduka.ppdwk.com/_*
Terimakasih';

    if (!in_array($ext, $ekstensi)) {
        echo "<script>
Swal.fire({
    icon: 'error',
    title: 'Maaf..',
    text: 'File yang anda upload bukan file yang diperbolehkan!'
});
var millisecondsToWait = 1000;
setTimeout(function() {
    document.location.href = 'spj.php' 
}, millisecondsToWait);
</script>";
    } else {

        $xx = $kode . '.' . end($extensi);
        $file_lama = 'spj_file/' . $xx;
        if (file_exists($file_lama)) {
            unlink($file_lama);
        }
        $sql1 = mysqli_query($conn, "UPDATE spj SET stts = 1, file_spj = '$xx', tgl_upload = '$date' WHERE kode_pengajuan = '$kode' AND tahun = '$tahun_ajaran' ");
        $sql2 = mysqli_query($conn, "UPDATE pengajuan SET spj = 1 WHERE kode_pengajuan = '$kode' AND tahun = '$tahun_ajaran' ");
        if ($sql1 && $sql2) { ?>
<script>
Swal.fire({
    position: 'top-end',
    icon: 'success',
    title: 'Upload file berhasil',
    showConfirmButton: false
});
var millisecondsToWait = 1000;
setTimeout(function() {
    document.location.href = "<?= 'spj.php' ?>"
}, millisecondsToWait);
</script>

<?php
            kirim_group($api_key, 'DfBeAZ3zGcR5qvLmBdKJaZ', $psn);
            kirim_group($api_key, 'FbXW8kqR5ik6w6iCB49GZK', $psn);
        }
        move_uploaded_file($_FILES['file']['tmp_name'], 'spj_file/' . $xx);
    }
}
?>