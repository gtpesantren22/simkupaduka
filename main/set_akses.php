<?php
include 'head.php';
?>
<!-- Datatables -->
<link href="vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
<link href="vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
<link href="vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
<link href="vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

<!-- bootstrap-daterangepicker -->
<link href="vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<!-- bootstrap-datetimepicker -->
<link href="vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">

<!-- page content -->
<div class="right_col" role="main">
    <div class="">

        <div class="clearfix"></div>

        <div class="">
            <div class="col-md-6 col-sm-12  ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-bars"></i> Setting Hak Akses Lembaga</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <!-- <li><button class="btn btn-info btn-sm" data-toggle="modal" data-target="#tambah"><i class="fa fa-plus-square"></i> Tambah Data</button></li> -->
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <div class="tab-content" id="myTabContent">

                            <!-- Pemasukan BOS -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="table-responsive">
                                    <table id="datatable2" class="table table-striped table-bordered table-sm"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Lembaga</th>
                                                <th>Login</th>
                                                <th>Disp</th>
                                                <th>Tahun</th>
                                                <th>Act</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            $dt_bos = mysqli_query($conn, "SELECT a.*, b.nama FROM akses a JOIN lembaga b ON a.lembaga=b.kode WHERE a.tahun = '$tahun_ajaran' AND b.tahun = '$tahun_ajaran' ");
                                            while ($a = mysqli_fetch_assoc($dt_bos)) { ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $a['nama'] ?></td>
                                                <td><?= $a['login'] ?></td>
                                                <td><?= $a['disposisi'] ?></td>
                                                <td><?= $a['tahun'] ?></td>
                                                <td>
                                                    <a data-toggle="modal"
                                                        data-target="#modal_del<?= $a['id_akses']; ?>" href="#"><i
                                                            class="fa fa-trash-o"></i></a> |
                                                    <a data-toggle="modal" data-target="#medit<?= $a['id_akses']; ?>"
                                                        href="#"><i class="fa fa-edit"></i></a>

                                                    <!-- Modal Hapus -->
                                                    <div class="modal fade bs-example-modal-sm"
                                                        id="modal_del<?= $a['id_akses']; ?>" tabindex="-1" role="dialog"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-sm">
                                                            <div class="modal-content">

                                                                <div class="modal-header">
                                                                    <h4 class="modal-title" id="myModalLabel2">Hapus
                                                                        Daftar Kode</h4>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close"><span
                                                                            aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <form action="" method="post">
                                                                    <input type="hidden" name="id_akses"
                                                                        value="<?= $a['id_akses']; ?>">
                                                                    <div class="modal-body">
                                                                        <h4>Yakin akan menghapu data ini ?</h4>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">No</button>
                                                                        <button type="submit" name="delete"
                                                                            class="btn btn-danger">Ya.! Hapus
                                                                            Pon</button>
                                                                    </div>
                                                                </form>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Modal Edit Data-->
                                                    <div class="modal fade" id="medit<?= $a['id_akses']; ?>"
                                                        tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">

                                                                <div class="modal-header">
                                                                    <h4 class="modal-title" id="myModalLabel">Tambah
                                                                        Data Bidang Lembaga</h4>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal"><span
                                                                            aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <form id="demo-form2" data-parsley-validate
                                                                    class="form-horizontal form-label-left input_mask"
                                                                    action="" method="post">
                                                                    <input type="hidden" name="id_akses"
                                                                        value="<?= $a['id_akses']; ?>">
                                                                    <div class="modal-body">
                                                                        <div class="item form-group">
                                                                            <label for="middle-name"
                                                                                class="col-form-label col-md-3 col-sm-3 label-align">Tahun
                                                                                <span class="required">*</span></label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input id="middle-name"
                                                                                    class="form-control" type="text"
                                                                                    name="pj" readonly
                                                                                    value="<?= $a['nama']; ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label
                                                                                class="col-form-label col-md-3 col-sm-3 label-align"
                                                                                for="first-name">Akses Login <span
                                                                                    class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <p>
                                                                                    <input type="radio" class="flat"
                                                                                        name="login" id="genderM"
                                                                                        value="Y"
                                                                                        <?= $a['login'] == 'Y' ? 'checked' : '' ?> />
                                                                                    Ya
                                                                                    <input type="radio" class="flat"
                                                                                        name="login" id="genderF"
                                                                                        value="T"
                                                                                        <?= $a['login'] == 'T' ? 'checked' : '' ?> />
                                                                                    Tidak
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label
                                                                                class="col-form-label col-md-3 col-sm-3 label-align"
                                                                                for="first-name">Disposisi <span
                                                                                    class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <p>
                                                                                    <input type="radio" class="flat"
                                                                                        name="disp" id="genderM"
                                                                                        value="Y"
                                                                                        <?= $a['disposisi'] == 'Y' ? 'checked' : '' ?> />
                                                                                    Ya
                                                                                    <input type="radio" class="flat"
                                                                                        name="disp" id="genderF"
                                                                                        value="T"
                                                                                        <?= $a['disposisi'] == 'T' ? 'checked' : '' ?> />
                                                                                    Tidak
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label for="middle-name"
                                                                                class="col-form-label col-md-3 col-sm-3 label-align">Tahun
                                                                                <span class="required">*</span></label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input id="middle-name"
                                                                                    class="form-control" type="text"
                                                                                    name="pj" readonly
                                                                                    value="<?= $tahun_ajaran; ?>">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">Close</button>
                                                                        <button type="submit" name="edit"
                                                                            class="btn btn-success">Simpan data</button>
                                                                    </div>
                                                                </form>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
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

            <div class="col-md-6 col-sm-12  ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Buat Akses Baru</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left input_mask"
                            action="" method="post">
                            <div class="modal-body">
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Pilih
                                        Lembaga <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <select name="lembaga" id="" class="form-control" required>
                                            <option value=""> -pilih lembaga- </option>
                                            <?php
                                            $sal = mysqli_query($conn, "SELECT * FROM lembaga WHERE NOT EXISTS (SELECT lembaga FROM akses WHERE lembaga.kode=akses.lembaga AND tahun = '$tahun_ajaran') ");
                                            while ($r = mysqli_fetch_assoc($sal)) {
                                            ?>
                                            <option value="<?= $r['kode']; ?>"><?= $r['nama']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Akses
                                        Login <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <p>
                                            <input type="radio" class="flat" name="login" id="genderM" value="Y" /> Ya
                                            <input type="radio" class="flat" name="login" id="genderF" value="T" />
                                            Tidak
                                        </p>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align"
                                        for="first-name">Disposisi <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <p>
                                            <input type="radio" class="flat" name="disp" id="genderM" value="Y" /> Ya
                                            <input type="radio" class="flat" name="disp" id="genderF" value="T" /> Tidak
                                        </p>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Tahun
                                        <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <input id="middle-name" class="form-control" type="text" name="pj" readonly
                                            value="<?= $tahun_ajaran; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" name="save" class="btn btn-success">Simpan data</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <div class="col-md-6 col-sm-12  ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Setting Akses PAK Online</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <?php
                        $tgl = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM akses WHERE lembaga = 'umum' "));
                        ?>
                        <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left input_mask"
                            action="" method="post">
                            <div class="modal-body">
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Tgl
                                        Aktif
                                        PAK <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <h3 class="badge badge-success">
                                            <?= date('d F Y', strtotime($tgl['login'])) . ' s/d ' . date('d F Y', strtotime($tgl['disposisi'])); ?>
                                        </h3>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Dari
                                        <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <input type="date" name="dari" id="" class="form-control" required>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Sampai
                                        <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <input type="date" name="sampai" id="" class="form-control" required>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Tahun
                                        <span class="required">*</span></label>
                                    <div class="col-md-6 col-sm-6 ">
                                        <input id="middle-name" class="form-control" type="text" name="tahun" readonly
                                            value="<?= $tahun_ajaran; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="save_date" class="btn btn-success">Ganti Tanggal
                                    Akses</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!-- End to do list -->
    </div>
    <div class="clearfix"></div>
</div>
<!-- /page content -->


<?php include 'foot.php'; ?>
<!-- Datatables -->
<script src="vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
<!-- bootstrap-daterangepicker -->
<script src="vendors/moment/min/moment.min.js"></script>
<script src="vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap-datetimepicker -->
<script src="vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
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
if (isset($_POST['save'])) {

    $lembaga = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['lembaga']));
    $login = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['login']));
    $disp = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['disp']));

    $sql = mysqli_query($conn, "INSERT INTO akses VALUES ('', '$lembaga', '$login','$disp','$tahun_ajaran')");
    if ($sql) { ?>
<script>
$(document).ready(function() {
    Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Akses berhasil tersimpan',
        showConfirmButton: false
    });
    var millisecondsToWait = 1000;
    setTimeout(function() {
        document.location.href = "set_akses.php"
    }, millisecondsToWait);

});
</script>

<?php    }
}

if (isset($_POST['edit'])) {

    $id_akses = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['id_akses']));
    $login = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['login']));
    $disp = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['disp']));

    $sql = mysqli_query($conn, "UPDATE akses SET login =  '$login', disposisi = '$disp' WHERE id_akses = $id_akses ");
    if ($sql) { ?>
<script>
$(document).ready(function() {
    Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Hak akses sudah berhasil diperbarui',
        showConfirmButton: false
    });
    var millisecondsToWait = 1000;
    setTimeout(function() {
        document.location.href = "set_akses.php"
    }, millisecondsToWait);
});
</script>

<?php    }
}

if (isset($_POST['save_date'])) {

    $dari = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['dari']));
    $sampai = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['sampai']));

    $sql = mysqli_query($conn, "UPDATE akses SET login = '$dari', disposisi = '$sampai' WHERE lembaga = 'umum' ");
    if ($sql) { ?>
<script>
$(document).ready(function() {
    Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Tanggal Aktif PAK sudah diperbarui',
        showConfirmButton: false
    });
    var millisecondsToWait = 1000;
    setTimeout(function() {
        document.location.href = "set_akses.php"
    }, millisecondsToWait);
});
</script>

<?php    }
}

if (isset($_POST['save'])) {

    $lembaga = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['lembaga']));
    $login = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['login']));
    $disp = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['disp']));

    $sql = mysqli_query($conn, "INSERT INTO akses VALUES ('', '$lembaga', '$login','$disp','$tahun_ajaran')");
    if ($sql) { ?>
<script>
$(document).ready(function() {
    Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Akses berhasil tersimpan',
        showConfirmButton: false
    });
    var millisecondsToWait = 1000;
    setTimeout(function() {
        document.location.href = "set_akses.php"
    }, millisecondsToWait);

});
</script>

<?php    }
}
?>