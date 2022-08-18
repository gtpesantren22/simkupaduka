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
            <div class="col-md-12 col-sm-12  ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-bars"></i> Daftar Akun</h2>

                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <div class="tab-content" id="myTabContent">

                            <!-- Pemasukan BOS -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-striped table-bordered table-sm" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Username</th>
                                                <th>Status</th>
                                                <th>Surat Tugas</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            $dt_bos = mysqli_query($conn, "SELECT * FROM user");
                                            while ($a = mysqli_fetch_assoc($dt_bos)) {
                                                $kd = $a['lembaga'];
                                                $lm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$kd' AND tahun = '$tahun_ajaran' "));
                                            ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $a['nama'] ?></td>
                                                    <td><?= $a['username'] ?></td>
                                                    <td>
                                                        <span class='badge badge-danger'>Aktif : <?= $a['aktif'] ?></span>
                                                        <span class='badge badge-success'>Lembaga : <?= $lm['nama'] ?></span>
                                                        <span class='badge badge-primary'>Level : <?= $a['level'] ?></span>
                                                    </td>
                                                    <td><a href="<?= '../institution/spj_file/' . $a['surat'] ?>"><i class="fa fa-download"></i> Unduh berkas</a></td>
                                                    <td>
                                                        <a href="#" data-toggle="modal" data-target="#md<?= $a['id_user'] ?>"><i class="fa fa-edit"></i> Edit</a>
                                                        <a href="#" data-toggle="modal" data-target="#hp<?= $a['id_user'] ?>"><i class="fa fa-times"></i> Del</a>
                                                    </td>
                                                </tr>
                                                <!-- Modal Tambah Data -->
                                                <div class="modal fade" id="md<?= $a['id_user'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">

                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myModalLabel">Tambah Data Belanja</h4>
                                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left input_mask" action="" method="post">
                                                                <input type="hidden" name="id_user" value="<?= $a['id_user'] ?>">
                                                                <input type="hidden" name="kd_lem" value="<?= $kd ?>">
                                                                <div class="modal-body">
                                                                    <div class="item form-group">
                                                                        <label for="tahun" class="col-form-label col-md-3 col-sm-3 label-align">Pilih Lembaga <span class="required">*</span></label>
                                                                        <div class="col-md-6 col-sm-6 ">
                                                                            <select name="lembaga" class="form-control" id="" required>
                                                                                <option value=""> -pilih lembaga- </option>
                                                                                <?php
                                                                                $qr2 = mysqli_query($conn, "SELECT * FROM lembaga WHERE tahun = '$tahun_ajaran'");
                                                                                while ($a2 = mysqli_fetch_assoc($qr2)) {
                                                                                    $sc = $a2['kode'] == $a['lembaga'] ? 'selected' : '';
                                                                                ?>
                                                                                    <option <?= $sc ?> value="<?= $a2['kode'] ?>"><?= $a2['kode'] ?>. <?= $a2['nama'] ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="item form-group">
                                                                        <label for="aktif" class="col-form-label col-md-3 col-sm-3 label-align">Aktivasi akun <span class="required">*</span></label>
                                                                        <div class="col-md-6 col-sm-6 ">
                                                                            <div class="radio">
                                                                                <label>
                                                                                    <input type="radio" class="flat" <?= $a['aktif'] == 'Y' ? 'checked' : '' ?> value="Y" name="aktif"> Aktif
                                                                                </label>
                                                                            </div>
                                                                            <div class="radio">
                                                                                <label>
                                                                                    <input type="radio" class="flat" <?= $a['aktif'] == 'T' ? 'checked' : '' ?> value="T" name="aktif"> Tidak
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="item form-group">
                                                                        <label for="level" class="col-form-label col-md-3 col-sm-3 label-align">Level akun <span class="required">*</span></label>
                                                                        <div class="col-md-6 col-sm-6 ">
                                                                            <div class="radio">
                                                                                <label>
                                                                                    <input type="radio" class="flat" <?= $a['level'] == 'admin' ? 'checked' : '' ?> value="admin" name="level"> Admin Utama
                                                                                </label>
                                                                            </div>
                                                                            <div class="radio">
                                                                                <label>
                                                                                    <input type="radio" class="flat" <?= $a['level'] == 'lembaga' ? 'checked' : '' ?> value="lembaga" name="level"> Admin Lembaga
                                                                                </label>
                                                                            </div>
                                                                            <div class="radio">
                                                                                <label>
                                                                                    <input type="radio" class="flat" <?= $a['level'] == 'kasir' ? 'checked' : '' ?> value="kasir" name="level"> Kasir/Teller
                                                                                </label>
                                                                            </div>
                                                                            <div class="radio">
                                                                                <label>
                                                                                    <input type="radio" class="flat" <?= $a['level'] == 'kepala' ? 'checked' : '' ?> value="kepala" name="level"> Kepala Pesantren
                                                                                </label>
                                                                            </div>
                                                                            <div class="radio">
                                                                                <label>
                                                                                    <input type="radio" class="flat" <?= $a['level'] == 'account' ? 'checked' : '' ?> value="account" name="level"> Accounting
                                                                                </label>
                                                                            </div>
                                                                            <div class="radio">
                                                                                <label>
                                                                                    <input type="radio" class="flat" <?= $a['level'] == 'bunda' ? 'checked' : '' ?> value="bunda" name="level"> Bendahara Utama
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="item form-group">
                                                                        <label for="tahun" class="col-form-label col-md-3 col-sm-3 label-align">No. HP Kepala <span class="required">*</span></label>
                                                                        <div class="col-md-6 col-sm-6 ">
                                                                            <input type="text" name="hp_kep" class="form-control" id="" value="<?= $lm['hp_kep']; ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="item form-group">
                                                                        <label for="tahun" class="col-form-label col-md-3 col-sm-3 label-align">No. HP Admin/Bendahara Lembaga <span class="required">*</span></label>
                                                                        <div class="col-md-6 col-sm-6 ">
                                                                            <input type="text" name="hp" class="form-control" id="" value="<?= $lm['hp']; ?>" required>
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

                                                <!-- Modal Tambah Data -->
                                                <div class="modal fade" id="hp<?= $a['id_user'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-sm">
                                                        <div class="modal-content">
                                                            <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left input_mask" action="" method="post">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title" id="myModalLabel">Tambah Data Belanja</h4>
                                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <input type="hidden" name="id_user" value="<?= $a['id_user'] ?>">
                                                                <div class="modal-body">
                                                                    <p>Yakin akan dihapus ?</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <button type="submit" name="del" class="btn btn-danger">Ya hapus!</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
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
<script src="dist/sweetalert2.all.min.js"></script>
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
    $id_user =  $_POST['id_user'];
    $lembaga =  $_POST['lembaga'];
    $aktif =  $_POST['aktif'];
    $level =  $_POST['level'];
    $hp =  $_POST['hp'];
    $hp_kep =  $_POST['hp_kep'];
    $kd_lem =  $_POST['kd_lem'];


    $sql = mysqli_query($conn, "UPDATE user SET level = '$level', aktif = '$aktif', lembaga = '$lembaga' WHERE id_user = '$id_user' ");
    $sql2 = mysqli_query($conn, "UPDATE lembaga SET hp = '$hp', hp_kep = '$hp_kep' WHERE kode = '$kd_lem' AND tahun = '$tahun_ajaran' ");
    if ($sql && $sql2) { ?>
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'User berhasil diupdate',
                showConfirmButton: false
            });
            var millisecondsToWait = 1000;
            setTimeout(function() {
                document.location.href = "akun.php"
            }, millisecondsToWait);
        </script>

    <?php    }
}

if (isset($_POST['del'])) {
    $id_user =  $_POST['id_user'];

    $sql = mysqli_query($conn, "DELETE FROM user WHERE id_user = '$id_user' ");
    if ($sql) { ?>
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'User berhasil dihapus',
                showConfirmButton: false
            });
            var millisecondsToWait = 1000;
            setTimeout(function() {
                document.location.href = "akun.php"
            }, millisecondsToWait);
        </script>

<?php    }
}

?>