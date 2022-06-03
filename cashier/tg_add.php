<?php
include 'atas.php';
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Pencairan pengajuan
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
                        <form action="" method="post" class="form-horizontal">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Pilih santri *</label>
                                    <div class="col-md-10">
                                        <!-- <input type="text" name="nis" id="nis" class="form-control"> -->
                                        <select name="nis" id="" class="form-control cari1">
                                            <option value=""> -cari santri- </option>
                                            <?php
                                            $santri = mysqli_query($conn, "SELECT * FROM tb_santri WHERE aktif = 'Y' AND NOT EXISTS (SELECT * FROM tanggungan WHERE tb_santri.nis=tanggungan.nis)");
                                            while ($r = mysqli_fetch_assoc($santri)) { ?>
                                                <option value="<?= $r['nis']; ?>"><?= $r['k_formal'] . ' ' . $r['t_formal'] . ' - ' . $r['nama']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <!-- <div class="col-md-2">
                                        <a href="#" type="button" data-toggle="modal" class="btn btn-success" data-target="#cek"><span class="fa fa-search"> </span>
                                            Cari Santri</a>
                                    </div> -->
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-2 control-label">Pilih Tanggungan</label>
                                    <div class="col-md-5">
                                        <table class="table table-striped table-bordered table-hover">
                                            <?php
                                            $sca = mysqli_query($conn, "SELECT * FROM tg_lembaga WHERE jkl = 'all' ");
                                            while ($da = mysqli_fetch_assoc($sca)) { ?>
                                                <tr>
                                                    <td>
                                                        <label style="color: green;"><?= $da['nama']; ?></label> <strong style="color: red; font-style: italic;">= <?= rupiah($da['nominal']); ?></strong> <label class="pull-right" style="color: darkorange;">(Umum)</label><br>
                                                        <input type="radio" name="<?= $da['kode']; ?>" value="<?= $da['nominal']; ?>"> Ya
                                                        <input type="radio" name="<?= $da['kode']; ?>" value="0" checked> Tidak
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </table>
                                    </div>
                                    <div class="col-md-1">&nbsp;</div>
                                    <div class="col-md-4">
                                        <table class="table table-striped table-bordered table-hover">
                                            <?php
                                            $sc = mysqli_query($conn, "SELECT * FROM tg_lembaga WHERE jkl = 'Perempuan' OR jkl = 'Laki-laki' ");
                                            while ($d = mysqli_fetch_assoc($sc)) { ?>
                                                <tr>
                                                    <td>
                                                        <label style="color: green;"><?= $d['nama']; ?></label> <strong style="color: red; font-style: italic;">= <?= rupiah($d['nominal']); ?></strong> <label class="pull-right" style="color: darkorange;">(Khusus <?= $d['jkl'] == 'Laki-laki' ? 'Putra' : 'Putri' ?>)</label><br>
                                                        <input type="radio" name="<?= $d['kode']; ?>" value="<?= $d['nominal']; ?>"> Ya
                                                        <input type="radio" name="<?= $d['kode']; ?>" value="0" checked> Tidak
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="tanggungan.php">
                                    <button type="button" class="btn btn-danger">Batal</button>
                                </a>
                                <button type="submit" name="save" class="btn btn-success">Simpan</button>
                            </div>
                        </form>
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
<!-- Select2 -->
<script src="../institution/plugins/select2/select2.full.min.js"></script>
<!-- DataTables -->
<script src="../institution/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../institution/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
    function masuk(txt, data) {
        document.getElementById('nis').value = data; // ini berfungsi mengisi value yang ber id textbox
        //$("#cek").modal('hide'); // ini berfungsi untuk menyembunyikan modal
    }
    $(function() {
        //$(".select2").select2();
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
<script>
    $(document).ready(function() {
        $(".cari1").select2();
    });
</script>
<?php
include 'bawah.php';

if (isset($_POST['save'])) {

    $nis = $_POST['nis'];
    $syh = mysqli_fetch_assoc(mysqli_query($conn, "SELECT a.*, b.stts FROM syahriah a JOIN tb_santri b on a.stts=b.stts WHERE b.nis = '$nis' "));
    $syhnya = $syh['nominal'];

    $sql = "INSERT INTO tanggungan VALUES ('', '$nis', '$syhnya' ";
    $tg = mysqli_query($conn, "SELECT * FROM tg_lembaga ");
    while ($ak = mysqli_fetch_assoc($tg)) {
        $nm = $_POST[$ak['kode']];
        $sql .= ",'$nm'";
    }
    //$sql = substr($sql, 0, -4);
    $sql1 =   $sql . ')';

    $qr = mysqli_query($conn, $sql1);
    if ($qr) { ?>
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Tanggungan sudah masuk',
                showConfirmButton: false
            });
            var millisecondsToWait = 1000;
            setTimeout(function() {
                document.location.href = "<?= 'tanggungan.php' ?>"
            }, millisecondsToWait);
        </script>

<?php    }
}
