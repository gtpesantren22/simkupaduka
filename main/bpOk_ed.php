<?php
include 'head.php';

$id = $_GET['id'];

$qrs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT a.*, b.nama FROM tangg a JOIN tb_santri b ON a.nis=b.nis WHERE a.tahun = '$tahun_ajaran' AND id_tangg = '$id' "));

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
                                <form class="form-horizontal form-label-left" action="" method="post">
                                    <input type="hidden" name="id_tangg" value="<?= $qrs['id_tangg']; ?>">
                                    <div class="modal-body">
                                        <div class="row">

                                            <div class="col-lg-6">
                                                <div class="item form-group">
                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">NIS <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-9 col-sm-9">
                                                        <input type="text" id="first-name" name="nis" value="<?= $qrs['nis'] ?>" required="required" class="form-control " readonly>
                                                    </div>
                                                </div>
                                                <div class="item form-group">
                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nama <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-9 col-sm-9">
                                                        <input type="text" id="first-name" name="nama" value="<?= $qrs['nama'] ?>" required="required" class="form-control " readonly>
                                                    </div>
                                                </div>
                                                <div class="item form-group">
                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">No. Briva <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-9 col-sm-9">
                                                        <input type="text" id="first-name" name="briva" value="<?= $qrs['briva'] ?>" required="required" class="form-control " readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="item form-group">
                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Jul-Apr <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-9 col-sm-9  form-group has-feedback">
                                                        <input type="text" class="form-control has-feedback-left " id="uang" name="ju_ap" value="<?= $qrs['ju_ap'] ?>" required>
                                                        <span class="form-control-feedback left" aria-hidden="true">Rp.</span>
                                                    </div>

                                                </div>
                                                <div class="item form-group">
                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Mei-Jun <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-9 col-sm-9  form-group has-feedback">
                                                        <input type="text" class="form-control has-feedback-left " id="uang1" name="me_ju" value="<?= $qrs['me_ju'] ?>" required>
                                                        <span class="form-control-feedback left" aria-hidden="true">Rp.</span>
                                                    </div>

                                                </div>
                                                <div class="item form-group">
                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Total <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-9 col-sm-9  form-group has-feedback">
                                                        <input type="text" class="form-control has-feedback-left " id="uang2" name="total" value="<?= number_format($qrs['total']) ?>" required readonly>
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

<script type="text/javascript">
    var rupiah = document.getElementById('uang');
    var rupiah1 = document.getElementById('uang1');
    var rupiah2 = document.getElementById('uang2');

    rupiah.addEventListener('keyup', function(e) {
        rupiah.value = formatRupiah(this.value);
    });

    rupiah1.addEventListener('keyup', function(e) {
        rupiah1.value = formatRupiah(this.value);
    });

    rupiah2.addEventListener('keyup', function(e) {
        rupiah2.value = formatRupiah(this.value);
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


?>