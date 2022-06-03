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
                        <h2><i class="fa fa-bars"></i> Daftar pembiayaan <small>pesantren</small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><button class="btn btn-info btn-sm" data-toggle="modal" data-target="#tambah"><i class="fa fa-plus-square"></i> Tambah Data</button></li>
                            <!-- <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#">Settings 1</a>
                                    <a class="dropdown-item" href="#">Settings 2</a>
                                </div>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li> -->
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <div class="tab-content" id="myTabContent">

                            <!-- Pemasukan BOS -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                                <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Status</th>
                                            <th>Nominal</th>
                                            <th>Tahun</th>
                                            <th>Act</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $dt_bos = mysqli_query($conn, "SELECT * FROM syahriah");
                                        while ($a = mysqli_fetch_assoc($dt_bos)) { ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $a['nama'] ?></td>
                                                <td><?php $st = $a["stts"];
                                                    $ps = explode("-", $st);
                                                    if ($ps[0] == 1) {
                                                        echo "<span class='badge badge-violet'>Ust/Usdz</span>";
                                                        echo " ";
                                                    }
                                                    if ($ps[1] == 2) {
                                                        echo "<span class='badge badge-primary'>Mhs/i</span>";
                                                        echo " ";
                                                    }
                                                    if ($ps[2] == 3) {
                                                        echo "<span class='badge badge-success'>Sdr/i</span>";
                                                        echo " ";
                                                    }
                                                    if ($ps[3] == 4) {
                                                        echo "<span class='badge badge-info'>Kls 6</span>";
                                                        echo " ";
                                                    }
                                                    if ($ps[4] == 5) {
                                                        echo "<span class='badge badge-warning'>Baru</span>";
                                                        echo " ";
                                                    }
                                                    if ($ps[5] == 6) {
                                                        echo "<span class='badge badge-danger'>Lama</span>";
                                                        echo " ";
                                                    }
                                                    if ($ps[6] == 7) {
                                                        echo "<span class='badge badge-primary'>Peng. Wilyah</span>";
                                                        echo " ";
                                                    }
                                                    if ($ps[7] == 8) {
                                                        echo "<span class='badge badge-dark'>Putra</span>";
                                                        echo " ";
                                                    }
                                                    if ($ps[8] == 9) {
                                                        echo "<span class='badge badge-info'>Putri</span>";
                                                    }
                                                    ?></td>
                                                <td>Rp. <?= number_format($a['nominal'], 0, '.', '.') ?></td>
                                                <td><?= $a['tahun'] ?></td>
                                                <td>
                                                    <a data-toggle="modal" data-target="#modal_edit<?= $a['id_sy']; ?>" href="#"><i class="fa fa-cog"></i> Edit</a> |
                                                    <a data-toggle="modal" data-target="#modal_del<?= $a['id_sy']; ?>" href="#"><i class="fa fa-trash-o"></i> Hapus</a>

                                                    <!-- Modal Edit Data-->
                                                    <div class="modal fade" id="modal_edit<?= $a['id_sy']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">

                                                                <div class="modal-header">
                                                                    <h4 class="modal-title" id="myModalLabel">Edit data pembiayaan pesantren</h4>
                                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <form class="form-horizontal form-label-left" action="" method="post">
                                                                    <input type="hidden" name="id_sy" value="<?= $a['id_sy']; ?>">
                                                                    <div class="modal-body">
                                                                        <div class="row">

                                                                            <div class="col-lg-6">
                                                                                <div class="item form-group">
                                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nama <span class="required">*</span>
                                                                                    </label>
                                                                                    <div class="col-md-9 col-sm-9">
                                                                                        <input type="text" id="first-name" name="nama" value="<?= $a['nama'] ?>" required="required" class="form-control ">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="item form-group">
                                                                                    <label for="tahun" class="col-form-label col-md-3 col-sm-3 label-align">Tahun <span class="required">*</span></label>
                                                                                    <div class="col-md-9 col-sm-9">
                                                                                        <select name="tahun" id="" required class="form-control">
                                                                                            <option value="<?= $a['tahun'] ?>"><?= $a['tahun'] ?></option>
                                                                                            <option value=""> -- pilih tahun ajaran-- </option>
                                                                                            <option value="2021/2022">2021/2022</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="item form-group">
                                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nominal <span class="required">*</span>
                                                                                    </label>
                                                                                    <div class="col-md-9 col-sm-9  form-group has-feedback">
                                                                                        <input type="text" class="form-control has-feedback-left " id="uang<?= $a['id_sy'] ?>" name="nominal" value="<?= $a['nominal'] ?>" required>
                                                                                        <span class="form-control-feedback left" aria-hidden="true">Rp.</span>
                                                                                    </div>

                                                                                </div>
                                                                            </div>

                                                                            <div class="col-lg-6">
                                                                                <div class="item form-group">
                                                                                    <label for="tahun" class="col-form-label col-md-3 col-sm-3 label-align">Status <span class="required">*</span></label>
                                                                                    <div class="col-md-9 col-sm-9">
                                                                                        <div class="checkbox">
                                                                                            <table>
                                                                                                <?php $st = explode("-", $a['stts']); ?>
                                                                                                <tr>
                                                                                                    <td><label><input type="checkbox" class="flat" name="usd" <?= $st[0] == 1 ? 'checked' : '' ?> value="1"> Ust/Ustd</label></td>
                                                                                                    <td><label><input type="checkbox" class="flat" name="mhs" <?= $st[1] == 2 ? 'checked' : '' ?> value="2"> Mhs/i</label></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td><label><input type="checkbox" class="flat" name="sdr" <?= $st[2] == 3 ? 'checked' : '' ?> value="3"> Sdr/i</label></td>
                                                                                                    <td><label><input type="checkbox" class="flat" name="kls6" <?= $st[3] == 4 ? 'checked' : '' ?> value="4"> Kelas 6</label></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td><label><input type="checkbox" class="flat" name="br" <?= $st[4] == 5 ? 'checked' : '' ?> value="5"> Baru</label></td>
                                                                                                    <td><label><input type="checkbox" class="flat" name="lm" <?= $st[5] == 6 ? 'checked' : '' ?> value="6"> Lama</label></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td><label><input type="checkbox" class="flat" name="pwl" <?= $st[6] == 7 ? 'checked' : '' ?> value="7"> Peng. Wilayah</label></td>
                                                                                                    <td><label><input type="checkbox" class="flat" name="pa" <?= $st[7] == 8 ? 'checked' : '' ?> value="8"> Putra</label></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td><label><input type="checkbox" class="flat" name="pi" <?= $st[8] == 9 ? 'checked' : '' ?> value="9"> Putri</label></td>
                                                                                                </tr>
                                                                                            </table>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        <button type="submit" name="edit" class="btn btn-success">Simpan data</button>
                                                                    </div>
                                                                </form>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Modal Hapus -->
                                                    <div class="modal fade bs-example-modal-sm" id="modal_del<?= $a['id_sy']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog modal-sm">
                                                            <div class="modal-content">

                                                                <div class="modal-header">
                                                                    <h4 class="modal-title" id="myModalLabel2">Hapus Data Syahriah</h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <form action="" method="post">
                                                                    <input type="hidden" name="id_sy" value="<?= $a['id_sy']; ?>">
                                                                    <div class="modal-body">
                                                                        <h4>Yajin akan menghapu data ini ?</h4>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                                                        <button type="submit" name="delete" class="btn btn-danger">Ya.! Hapus Pon</button>
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

                                <!-- Modal Tambah Data-->
                                <div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Tambah data pembiayaan pesantren</h4>
                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <form class="form-horizontal form-label-left" action="" method="post">
                                                <div class="modal-body">
                                                    <div class="row">

                                                        <div class="col-lg-6">
                                                            <div class="item form-group">
                                                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nama <span class="required">*</span>
                                                                </label>
                                                                <div class="col-md-9 col-sm-9">
                                                                    <input type="text" id="first-name" name="nama" required="required" class="form-control ">
                                                                </div>
                                                            </div>
                                                            <div class="item form-group">
                                                                <label for="tahun" class="col-form-label col-md-3 col-sm-3 label-align">Tahun <span class="required">*</span></label>
                                                                <div class="col-md-9 col-sm-9">
                                                                    <select name="tahun" id="" required class="form-control">
                                                                        <option value=""> -- pilih tahun ajaran-- </option>
                                                                        <option value="2021/2022">2021/2022</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="item form-group">
                                                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nominal <span class="required">*</span>
                                                                </label>
                                                                <div class="col-md-9 col-sm-9  form-group has-feedback">
                                                                    <input type="text" class="form-control has-feedback-left " id="uang" name="nominal" required>
                                                                    <span class="form-control-feedback left" aria-hidden="true">Rp.</span>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <div class="item form-group">
                                                                <label for="tahun" class="col-form-label col-md-3 col-sm-3 label-align">Status <span class="required">*</span></label>
                                                                <div class="col-md-9 col-sm-9">
                                                                    <div class="checkbox">
                                                                        <table>
                                                                            <tr>
                                                                                <td><label><input type="checkbox" class="flat" name="usd" value="1"> Ust/Ustd</label></td>
                                                                                <td><label><input type="checkbox" class="flat" name="mhs" value="2"> Mhs/i</label></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><label><input type="checkbox" class="flat" name="sdr" value="3"> Sdr/i</label></td>
                                                                                <td><label><input type="checkbox" class="flat" name="kls6" value="4"> Kelas 6</label></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><label><input type="checkbox" class="flat" name="br" value="5"> Baru</label></td>
                                                                                <td><label><input type="checkbox" class="flat" name="lm" value="6"> Lama</label></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><label><input type="checkbox" class="flat" name="pwl" value="7"> Peng. Wilayah</label></td>
                                                                                <td><label><input type="checkbox" class="flat" name="pa" value="8"> Putra</label></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><label><input type="checkbox" class="flat" name="pi" value="9"> Putri</label></td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
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
<?php foreach ($dt_bos as $a) : ?>
    <script type="text/javascript">
        var id_s = uang + <?= $a['id_sy']; ?>
        var rupiah<?= $a['id_sy']; ?> = document.getElementById(id_s);

        rupiah<?= $a['id_sy']; ?>.addEventListener('keyup', function(e) {
            rupiah<?= $a['id_sy']; ?>.value = formatRupiah(this.value);
        });

        /* Fungsi formatRupiah */
        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? rupiah : '');
        }
    </script>
<?php endforeach; ?>
<script type="text/javascript">
    var rupiah = document.getElementById('uang');

    rupiah.addEventListener('keyup', function(e) {
        rupiah.value = formatRupiah(this.value);
    });

    /* Fungsi formatRupiah */
    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? rupiah : '');
    }
</script>

<?php
if (isset($_POST['save'])) {
    $nama = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['nama']));
    $tahun = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tahun']));
    $nominal = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['nominal']));
    $nom = preg_replace("/[^0-9]/", "", $nominal);
    $stts = $_POST['usd'] . "-" . $_POST['mhs'] . "-" . $_POST['sdr'] . "-" . $_POST['kls6'] . "-" . $_POST['br'] . "-" . $_POST['lm'] . "-" . $_POST['pwl'] . "-" . $_POST['pa'] . "-" . $_POST['pi'];

    $sql = mysqli_query($conn, "INSERT INTO syahriah VALUES ('', '$nama', '$stts', '$nom', '$tahun' ) ");
    if ($sql) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Data syahriah berhasil tersimpan',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "bppes.php"
                }, millisecondsToWait);

            });
        </script>

    <?php    }
}

if (isset($_POST['edit'])) {

    $id_sy = $_POST['id_sy'];
    $nama = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['nama']));
    $tahun = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tahun']));
    $nominal = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['nominal']));
    $nom = preg_replace("/[^0-9]/", "", $nominal);
    $stts = $_POST['usd'] . "-" . $_POST['mhs'] . "-" . $_POST['sdr'] . "-" . $_POST['kls6'] . "-" . $_POST['br'] . "-" . $_POST['lm'] . "-" . $_POST['pwl'] . "-" . $_POST['pa'] . "-" . $_POST['pi'];

    $sql = mysqli_query($conn, "UPDATE syahriah SET nama = '$nama', tahun = '$tahun', stts = '$stts', nominal = '$nom' WHERE id_sy = $id_sy ");
    if ($sql) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Tanggungan syahriah berhasil perbarui',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "bppes.php"
                }, millisecondsToWait);

            });
        </script>

    <?php    }
}

if (isset($_POST['delete'])) {

    $id_sy = $_POST['id_sy'];

    $sql = mysqli_query($conn, "DELETE FROM syahriah WHERE id_sy = $id_sy ");
    if ($sql) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Tanggungan syahriah berhasil dihapus',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "bppes.php"
                }, millisecondsToWait);

            });
        </script>

<?php    }
}
?>