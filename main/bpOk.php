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
                            <li><button class="btn btn-info btn-sm" data-toggle="modal" data-target="#tambah"><i class="fa fa-upload"></i> Upload Data Baru</button></li>
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
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-striped table-bordered table-sm" style="width:100%">
                                        <thead>
                                            <tr>
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
                                                        <a data-toggle="modal" data-target="#modal_edit<?= $a['id_tangg']; ?>" href="#"><i class="fa fa-cog"></i> Edit</a> |
                                                        <a data-toggle="modal" data-target="#modal_del<?= $a['id_tangg']; ?>" href="#"><i class="fa fa-trash-o"></i> Hapus</a>

                                                        <!-- Modal Edit Data-->
                                                        <div class="modal fade" id="modal_edit<?= $a['id_tangg']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">

                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title" id="myModalLabel">Edit data pembiayaan pesantren</h4>
                                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                                                        </button>
                                                                    </div>
                                                                    <form class="form-horizontal form-label-left" action="" method="post">
                                                                        <input type="hidden" name="id_tangg" value="<?= $a['id_tangg']; ?>">
                                                                        <div class="modal-body">
                                                                            <div class="row">

                                                                                <div class="col-lg-6">
                                                                                    <div class="item form-group">
                                                                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">NIS <span class="required">*</span>
                                                                                        </label>
                                                                                        <div class="col-md-9 col-sm-9">
                                                                                            <input type="text" id="first-name" name="nis" value="<?= $a['nis'] ?>" required="required" class="form-control " readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="item form-group">
                                                                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nama <span class="required">*</span>
                                                                                        </label>
                                                                                        <div class="col-md-9 col-sm-9">
                                                                                            <input type="text" id="first-name" name="nama" value="<?= $a['nama'] ?>" required="required" class="form-control " readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="item form-group">
                                                                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">No. Briva <span class="required">*</span>
                                                                                        </label>
                                                                                        <div class="col-md-9 col-sm-9">
                                                                                            <input type="text" id="first-name" name="briva" value="<?= $a['briva'] ?>" required="required" class="form-control " readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-lg-6">
                                                                                    <div class="item form-group">
                                                                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Jul-Apr <span class="required">*</span>
                                                                                        </label>
                                                                                        <div class="col-md-9 col-sm-9  form-group has-feedback">
                                                                                            <input type="text" class="form-control has-feedback-left " id="uang<?= $a['briva'] ?>" name="ju_ap" value="<?= $a['ju_ap'] ?>" required>
                                                                                            <span class="form-control-feedback left" aria-hidden="true">Rp.</span>
                                                                                        </div>

                                                                                    </div>
                                                                                    <div class="item form-group">
                                                                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Mei-Jun <span class="required">*</span>
                                                                                        </label>
                                                                                        <div class="col-md-9 col-sm-9  form-group has-feedback">
                                                                                            <input type="text" class="form-control has-feedback-left " id="uang1<?= $a['briva'] ?>" name="me_ju" value="<?= $a['me_ju'] ?>" required>
                                                                                            <span class="form-control-feedback left" aria-hidden="true">Rp.</span>
                                                                                        </div>

                                                                                    </div>
                                                                                    <div class="item form-group">
                                                                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Total <span class="required">*</span>
                                                                                        </label>
                                                                                        <div class="col-md-9 col-sm-9  form-group has-feedback">
                                                                                            <input type="text" class="form-control has-feedback-left " id="uang2<?= $a['briva'] ?>" name="total" value="<?= number_format($a['total']) ?>" required readonly>
                                                                                            <span class="form-control-feedback left" aria-hidden="true">Rp.</span>
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
                                                        <div class="modal fade bs-example-modal-sm" id="modal_del<?= $a['id_tangg']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog modal-sm">
                                                                <div class="modal-content">

                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title" id="myModalLabel2">Hapus Data Syahriah</h4>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                                                                        </button>
                                                                    </div>
                                                                    <form action="" method="post">
                                                                        <input type="hidden" name="id_tangg" value="<?= $a['id_tangg']; ?>">
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
                                </div>
                                <!-- Modal Tambah Data-->
                                <div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Tambah data pembiayaan pesantren</h4>
                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <form class="form-horizontal form-label-left" action="" method="post" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    <div class="row">

                                                        <div class="col-lg-6">
                                                            <div class="item form-group">
                                                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Pilih File <span class="required">*</span>
                                                                </label>
                                                                <div class="col-md-9 col-sm-9">
                                                                    <input type="file" id="first-name" name="file" required="required" class="form-control ">
                                                                </div>
                                                            </div>
                                                            <div class="item form-group">
                                                                <label for="tahun" class="col-form-label col-md-3 col-sm-3 label-align">Ket <span class="required">*</span></label>
                                                                <div class="col-md-9 col-sm-9">
                                                                    <p>File yang diupload harus berupa Excel dengan formal .xls (Excel 97-2003)</p>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="col-lg-6">
                                                            <div class="item form-group">
                                                                <label for="tahun" class="col-form-label col-md-6 col-sm-6 label-align">Download Format Upload <span class="required">*</span></label>
                                                                <div class="col-md-6 col-sm-6">
                                                                    <a class="btn btn-success btn-sm" href="file_rab/File example/Template-Upload-Tanggungan.xls"><i class="fa fa-download"></i> Download</a>
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
        var rupiah<?= $a['briva']; ?> = document.getElementById('uang<?= $a['briva']; ?>');
        var rupiah1<?= $a['briva']; ?> = document.getElementById('uang1<?= $a['briva']; ?>');
        var rupiah2<?= $a['briva']; ?> = document.getElementById('uang2<?= $a['briva']; ?>');

        rupiah<?= $a['briva']; ?>.addEventListener('keyup', function(e) {
            rupiah<?= $a['briva']; ?>.value = formatRupiah(this.value);
        });

        rupiah1<?= $a['briva']; ?>.addEventListener('keyup', function(e) {
            rupiah1<?= $a['briva']; ?>.value = formatRupiah(this.value);
        });

        rupiah2<?= $a['briva']; ?>.addEventListener('keyup', function(e) {
            rupiah2<?= $a['briva']; ?>.value = formatRupiah(this.value);
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
    require 'libs/vendor/autoload.php';
    require_once 'excel_reader2.php';

    $target = basename($_FILES['file']['name']);
    move_uploaded_file($_FILES['file']['tmp_name'], $target);

    chmod($_FILES['file']['name'], 07777);

    $data = new Spreadsheet_Excel_Reader($_FILES['file']['name'], false);

    $jumbar = $data->rowcount($sheet_index = 0);

    $success = 0;

    for ($i = 2; $i <= $jumbar; $i++) {

        $nis = $data->val($i, 1);
        $id_cost = $data->val($i, 2);
        $briva = $data->val($i, 3);
        $ju_ap = $data->val($i, 4);
        $me_ju = mysqli_real_escape_string($conn, $data->val($i, 5));
        $tahun = $data->val($i, 6);
        $total = ($ju_ap * 10) + ($me_ju * 2);

        $cek = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tangg WHERE nis = '$nis' AND tahun = '$tahun_ajaran' "));

        if ($cek > 0) {
            mysqli_query($conn, "UPDATE tangg SET id_cos = '$id_cost', briva = '$briva', ju_ap = '$ju_ap', me_ju = '$me_ju', total = '$total', tahun = '$tahun_ajaran' WHERE nis = '$nis' ");
        } else {
            mysqli_query($conn, "INSERT INTO tangg VALUES ('', '$nis', '$id_cost', '$briva','$ju_ap','$me_ju','$total','$tahun_ajaran')");
        }
        $success++;
    }

    unlink($_FILES['file']['name']);

    if ($success > 0) {
?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Tanggungan berhasil diupload',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "bpOk.php"
                }, millisecondsToWait);

            });
        </script>
    <?php
    }
}

if (isset($_POST['edit'])) {

    $id_tangg = $_POST['id_tangg'];
    $ju_ap = preg_replace("/[^0-9]/", "", $_POST['ju_ap']);
    $me_ju = preg_replace("/[^0-9]/", "", $_POST['me_ju']);
    $total = ($ju_ap * 10) + ($me_ju * 2);

    $sql = mysqli_query($conn, "UPDATE tangg SET ju_ap = '$ju_ap', me_ju = '$me_ju', total = '$total' WHERE id_tangg = $id_tangg AND tahun = '$tahun_ajaran' ");
    if ($sql) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Tanggungan berhasil perbarui',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "bpOk.php"
                }, millisecondsToWait);

            });
        </script>

    <?php    }
}

if (isset($_POST['delete'])) {

    $id_tangg = $_POST['id_tangg'];

    $sql = mysqli_query($conn, "DELETE FROM tangg WHERE id_tangg = $id_tangg AND tahun = '$tahun_ajaran' ");
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
                    document.location.href = "bpOk.php"
                }, millisecondsToWait);

            });
        </script>

<?php    }
}
?>