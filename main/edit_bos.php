<?php
include 'head.php';
$id_bos = $_GET['id'];
$a = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM bos WHERE id_bos = '$id_bos' AND tahun = '$tahun_ajaran' "));
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

        <div class="animated flipInY col-lg-12 col-md-3 col-sm-6  ">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-money"></i>
                </div>

                <h3>Total pemasukan tahun ini</h3>
                <p>Pemasukan ini diadapatkan dari data BOS, Pemasukan Pesantren, dan Biaya pendidikan santri</p>
                <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left input_mask" action="" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="id_bos" value="<?= $a['id_bos']; ?>">
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Kode <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 ">
                                <input type="text" id="first-name" name="kode" required="required" class="form-control" value="<?= $a['kode']; ?>">
                            </div>
                        </div>
                        <div class=" item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Uraian <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 ">
                                <input type="text" id="last-name" name="uraian" required="required" class="form-control" value="<?= $a['uraian']; ?>">
                            </div>
                        </div>
                        <div class=" item form-group">
                            <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Peridode <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 ">
                                <input id="middle-name" class="form-control" type="text" name="periode" required value="<?= $a['periode']; ?>">
                            </div>
                        </div>
                        <div class=" item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nominal <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6  form-group has-feedback">
                                <input type="text" class="form-control has-feedback-left uang" id="uang" name="nominal" value="<?= $a['nominal']; ?>" required>
                                <span class=" form-control-feedback left" aria-hidden="true">Rp.</span>
                            </div>

                        </div>
                        <div class="item form-group">
                            <label for="tahun" class="col-form-label col-md-3 col-sm-3 label-align">Tahun <span class="required">*</span></label>
                            <div class="col-md-6 col-sm-6 ">
                                <select name="tahun" id="" required class="form-control">
                                    <option value=""> -- pilih tahun -- </option>
                                    <?php
                                    $th = date('Y');
                                    for ($i = 2020; $i <= $th; $i++) {
                                        $i == $a['tahun'] ? $sc = 'selected' : $sc = '';
                                    ?>
                                        <option <?= $sc ?> value="<?= $i; ?>"><?= $i; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Tanggal Bayar <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 xdisplay_inputx form-group row has-feedback">

                                <input type="text" name="tgl_bayar" class="form-control has-feedback-left" id="datePick" placeholder="" aria-describedby="inputSuccess2Status4" value="<?= $a['tgl_bayar']; ?>">
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                <span id="inputSuccess2Status4" class="sr-only">(success)</span>

                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="edit_bos" class="btn btn-success">Update data</button>
                    </div>
                </form>
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
    });
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
if (isset($_POST['edit_bos'])) {

    $id = $_POST['id_bos'];
    $kode = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['kode']));
    $uraian = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['uraian']));
    $periode = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['periode']));
    $nominal = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['nominal']));
    $tahun = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tahun']));
    $tgl_bayar = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tgl_bayar']));
    $nom = preg_replace("/[^0-9]/", "", $nominal);

    $sql = mysqli_query($conn, "UPDATE bos SET kode = '$kode', uraian = '$uraian', periode = '$periode', nominal = '$nom', tahun = '$tahun', tgl_bayar = '$tgl_bayar' WHERE id_bos = $id_bos AND tahun = '$tahun_ajaran' ");
    if ($sql) { ?>
        <!-- echo "
        <script>
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'Data berhasil terupdate',
            showConfirmButton: false,
            timer: 1500}
            function(){
                window.location = 'masuk.php';
            });
        </script>
        "; -->
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Data BOS berhasil diupdate',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "masuk.php"
                }, millisecondsToWait);

            });
        </script>
<?php  }
}

?>