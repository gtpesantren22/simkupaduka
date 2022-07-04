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
            <div class="col-md-8 col-sm-12  ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-bars"></i> Daftar pembiayaan <small>lembaga formal</small></h2>
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
                                            <th>Kode</th>
                                            <th>Keterangan</th>
                                            <th>Nominal</th>
                                            <th>Tahun</th>
                                            <th>Act</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $dt_bos = mysqli_query($conn, "SELECT * FROM tahapan WHERE tahun = '$tahun_ajaran' ORDER BY lembaga DESC");
                                        while ($a = mysqli_fetch_assoc($dt_bos)) { ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $a['kode'] ?></td>
                                                <td><?= $a['lembaga'] . ' - ' . $a['kelas'] . ' - ' . $a['jurusan'] ?></td>
                                                <td>Rp. <?= number_format($a['nominal'], 0, '.', '.') ?></td>
                                                <td><?= $a['tahun'] ?></td>
                                                <td>
                                                    <a data-toggle="modal" data-target="#modal_edit<?= $a['id_th']; ?>" href="#"><i class="fa fa-cog"></i> Edit</a> |
                                                    <a data-toggle="modal" data-target="#modal_del<?= $a['id_th']; ?>" href="#"><i class="fa fa-trash-o"></i> Del</a>

                                                    <!-- Modal Edit Data-->
                                                    <div class="modal fade" id="modal_edit<?= $a['id_th']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">

                                                                <div class="modal-header">
                                                                    <h4 class="modal-title" id="myModalLabel">Edit data pembiayaan pesantren</h4>
                                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <form class="form-horizontal form-label-left" action="" method="post">
                                                                    <input type="hidden" name="id_th" value="<?= $a['id_th']; ?>">
                                                                    <div class="modal-body">
                                                                        <div class="row">

                                                                            <div class="col-lg-6">
                                                                                <div class="item form-group">
                                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Lembaga <span class="required">*</span>
                                                                                    </label>
                                                                                    <div class="col-md-9 col-sm-9">
                                                                                        <select name="lembaga" id="" required class="form-control">
                                                                                            <option value="<?= $a['lembaga'] ?>"><?= $a['lembaga'] ?></option>
                                                                                            <option value=""> -- pilih -- </option>
                                                                                            <option value="MTs">MTs</option>
                                                                                            <option value="SMP">SMP</option>
                                                                                            <option value="MA">MA</option>
                                                                                            <option value="SMK">SMK</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="item form-group">
                                                                                    <label for="kelas" class="col-form-label col-md-3 col-sm-3 label-align">Piiih kelas <span class="required">*</span></label>
                                                                                    <div class="col-md-9 col-sm-9">
                                                                                        <select name="kelas" id="" required class="form-control">
                                                                                            <option value="<?= $a['kelas'] ?>"><?= $a['kelas'] ?></option>
                                                                                            <option value=""> --pilih-- </option>
                                                                                            <option value="VII">VII</option>
                                                                                            <option value="VIII">VIII</option>
                                                                                            <option value="IX">IX</option>
                                                                                            <option value="X">X</option>
                                                                                            <option value="XI">XI</option>
                                                                                            <option value="XII">XII</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="item form-group">
                                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Jurusan <span class="required">*</span>
                                                                                    </label>
                                                                                    <div class="col-md-9 col-sm-9">
                                                                                        <input type="text" id="first-name" value="<?= $a['jurusan'] ?>" name="jurusan" required="required" class="form-control ">
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-lg-6">
                                                                                <div class="item form-group">
                                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nominal <span class="required">*</span>
                                                                                    </label>
                                                                                    <div class="col-md-9 col-sm-9  form-group has-feedback">
                                                                                        <input type="text" class="form-control has-feedback-left " id="uang_2" value="<?= $a['nominal'] ?>" name="nominal" required>
                                                                                        <span class="form-control-feedback left" aria-hidden="true">Rp.</span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="item form-group">
                                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Pilih Tahun <span class="required">*</span>
                                                                                    </label>
                                                                                    <div class="col-md-9 col-sm-9">
                                                                                        <select name="tahun" id="" required class="form-control">
                                                                                            <option value="<?= $a['tahun'] ?>"><?= $a['tahun'] ?></option>
                                                                                            <option value=""> -- pilih -- </option>
                                                                                            <option value="2021/2022">2021/2022</option>
                                                                                        </select>
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
                                                    <div class="modal fade bs-example-modal-sm" id="modal_del<?= $a['id_th']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog modal-sm">
                                                            <div class="modal-content">

                                                                <div class="modal-header">
                                                                    <h4 class="modal-title" id="myModalLabel2">Hapus Data Tahapan formal</h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <form action="" method="post">
                                                                    <input type="hidden" name="id_th" value="<?= $a['id_th']; ?>">
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
                                                <h4 class="modal-title" id="myModalLabel">Tambah data pembiayaan lembaga formal</h4>
                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <form class="form-horizontal form-label-left" action="" method="post">
                                                <div class="modal-body">
                                                    <div class="row">

                                                        <div class="col-lg-6">
                                                            <div class="item form-group">
                                                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Lembaga <span class="required">*</span>
                                                                </label>
                                                                <div class="col-md-9 col-sm-9">
                                                                    <select name="lembaga" id="" required class="form-control">
                                                                        <option value=""> -- pilih -- </option>
                                                                        <option value="MTs">MTs</option>
                                                                        <option value="SMP">SMP</option>
                                                                        <option value="MA">MA</option>
                                                                        <option value="SMK">SMK</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="item form-group">
                                                                <label for="kelas" class="col-form-label col-md-3 col-sm-3 label-align">Piiih kelas <span class="required">*</span></label>
                                                                <div class="col-md-9 col-sm-9">
                                                                    <select name="kelas" id="" required class="form-control">
                                                                        <option value=""> --pilih-- </option>
                                                                        <option value="VII">VII</option>
                                                                        <option value="VIII">VIII</option>
                                                                        <option value="IX">IX</option>
                                                                        <option value="X">X</option>
                                                                        <option value="XI">XI</option>
                                                                        <option value="XII">XII</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="item form-group">
                                                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Jurusan <span class="required">*</span>
                                                                </label>
                                                                <div class="col-md-9 col-sm-9">
                                                                    <input type="text" id="first-name" name="jurusan" required="required" class="form-control ">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <div class="item form-group">
                                                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nominal <span class="required">*</span>
                                                                </label>
                                                                <div class="col-md-9 col-sm-9  form-group has-feedback">
                                                                    <input type="text" class="form-control has-feedback-left " id="uang" name="nominal" required>
                                                                    <span class="form-control-feedback left" aria-hidden="true">Rp.</span>
                                                                </div>
                                                            </div>
                                                            <div class="item form-group">
                                                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Pilih Tahun <span class="required">*</span>
                                                                </label>
                                                                <div class="col-md-9 col-sm-9">
                                                                    <select name="tahun" id="" required class="form-control">
                                                                        <option value=""> -- pilih -- </option>
                                                                        <option value="2021/2022">2021/2022</option>
                                                                    </select>
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

            <div class="col-md-4 col-sm-12  ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Daftar Kode <small>lembaga</small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="">
                            <?php
                            $kd = mysqli_query($conn, "SELECT * FROM tahapan WHERE tahun = '$tahun_ajaran' GROUP BY lembaga ORDER BY lembaga DESC");
                            while ($d = mysqli_fetch_assoc($kd)) {
                                $wr = ['green', 'dimgray', 'lightskyblue', 'orange']
                            ?>
                                <ul class="to_do">
                                    <h5>Kode <?= $d['lembaga'] ?></h5>
                                    <div class="row">
                                        <?php
                                        $lm = $d['lembaga'];
                                        $kd2 = mysqli_query($conn, "SELECT * FROM tahapan WHERE lembaga = '$lm' AND tahun = '$tahun_ajaran' ");
                                        while ($d2 = mysqli_fetch_assoc($kd2)) {
                                        ?>
                                            <div class="col-md-4">
                                                <li style="color: white; background-color: orange;">
                                                    <p>
                                                        <?= $d2['kode'] ?>
                                                    </p>
                                                </li>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </ul>
                            <?php } ?>
                        </div>
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
<?php foreach ($dt_bos as $a) : ?>
    <script type="text/javascript">
        var id_s = uang + <?= $a['id_th']; ?>
        var rupiah<?= $a['id_th']; ?> = document.getElementById(id_s);

        rupiah<?= $a['id_th']; ?>.addEventListener('keyup', function(e) {
            rupiah<?= $a['id_th']; ?>.value = formatRupiah(this.value);
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

    $lembaga = $_POST['lembaga'];
    $kelas = $_POST['kelas'];
    $jurusan = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['jurusan']));
    $kode = strtoupper($lembaga . '.' . $kelas . '/' . $jurusan);
    $nominal = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['nominal']));
    $nom = preg_replace("/[^0-9]/", "", $nominal);
    $tahun = $_POST['tahun'];

    $sql = mysqli_query($conn, "INSERT INTO tahapan VALUES ('', '$kode', '$lembaga', '$kelas', '$jurusan', '$nom', '$tahun_ajaran' ) ");
    if ($sql) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Data tahapan lembaga berhasil tersimpan',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "bpfor.php"
                }, millisecondsToWait);

            });
        </script>

    <?php    }
}

if (isset($_POST['edit'])) {

    $id_th = $_POST['id_th'];
    $lembaga = $_POST['lembaga'];
    $kelas = $_POST['kelas'];
    $jurusan = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['jurusan']));
    $kode = strtoupper($lembaga . '.' . $kelas . '/' . $jurusan);
    $nominal = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['nominal']));
    $nom = preg_replace("/[^0-9]/", "", $nominal);
    $tahun = $_POST['tahun'];

    $sql = mysqli_query($conn, "UPDATE tahapan SET kode = '$kode', lembaga = '$lembaga', kelas = '$kelas', jurusan = '$jurusan', nominal = '$nom', tahun = '$tahun_ajaran' WHERE id_th = $id_th ");
    if ($sql) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Tanggungan formal berhasil perbarui',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "bpfor.php"
                }, millisecondsToWait);

            });
        </script>

    <?php    }
}

if (isset($_POST['delete'])) {

    $id_th = $_POST['id_th'];

    $sql = mysqli_query($conn, "DELETE FROM tahapan WHERE id_th = $id_th AND tahun = '$tahun_ajaran' ");
    if ($sql) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Tahapan berhasil dihapus',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "bpfor.php"
                }, millisecondsToWait);

            });
        </script>

<?php    }
}
?>