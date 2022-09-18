<?php
include 'head.php';
?>
<!-- Datatables -->
<link href="../main/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="../main/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
<link href="../main/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
<link href="../main/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
<link href="../main/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

<!-- bootstrap-daterangepicker -->
<link href="../main/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<!-- bootstrap-datetimepicker -->
<link href="../main/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">

<!-- page content -->
<div class="right_col" role="main">
    <div class="">

        <div class="clearfix"></div>

        <div class="">
            <div class="col-md-12 col-sm-12  ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-bars"></i> Data SPJ <small>Realisasi</small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <!-- <li><button class="btn btn-info btn-sm" data-toggle="modal" data-target="#tambah"><i class="fa fa-plus-square"></i> Tambah Data</button></li> -->
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <div class="tab-content" id="myTabContent">

                            <!-- Pemasukan BOS -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-badgeledby="home-tab">

                                <table id="datatable" class="table table-striped table-bordered table-sm" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Lembaga</th>
                                            <th>Periode</th>
                                            <th>Status</th>
                                            <th>Nominal</th>
                                            <th>Berkas</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $dt_bos = mysqli_query($conn, "SELECT a.*, b.nama, b.hp FROM spj a JOIN lembaga b ON a.lembaga=b.kode WHERE a.file_spj != '' AND a.tahun = '$tahun_ajaran' AND b.tahun = '$tahun_ajaran' ");
                                        while ($a = mysqli_fetch_assoc($dt_bos)) {
                                            $kd_pj = $a['kode_pengajuan'];
                                            $jml = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nom_cair) AS jml FROM real_sm WHERE kode_pengajuan = '$kd_pj' AND tahun = '$tahun_ajaran' "));
                                            $jml2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nom_cair) AS jml FROM realis WHERE kode_pengajuan = '$kd_pj' AND tahun = '$tahun_ajaran'"));
                                            $pjan = $jml['jml'] + $jml2['jml'];

                                            if (preg_match("/DISP./i", $kd_pj)) {
                                                $rt = "<span class='badge badge-danger'>DISPOSISI</span>";
                                            } else {
                                                $rt = '';
                                            }
                                        ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $kd_pj ?></td>
                                                <td><?= $a['nama'] . ' ' . $rt; ?></td>
                                                <td><?= $bulan[$a['bulan']] . ' ' . $a['tahun'] ?></td>
                                                <td>
                                                    <?php if ($a['stts'] == 0) { ?>
                                                        <span class="badge badge-danger"><i class="fa fa-times"></i> belum upload</span>
                                                    <?php } else if ($a['stts'] == 1) { ?>
                                                        <span class="badge badge-warning btn-xs"><i class="fa fa-spinner fa-refresh-animate"></i>
                                                            proses verifikasi</span>
                                                    <?php } else { ?>
                                                        <span class="badge badge-success"><i class="fa fa-check"></i> sudah selesai</span>
                                                    <?php } ?>
                                                </td>
                                                <td><?= rupiah($pjan) ?></td>
                                                <td><a href="<?= '../institution/spj_file/' . $a['file_spj'] ?>"><i class="fa fa-download"></i> Unduh Berkas</a></td>
                                                <td>
                                                    <?php if ($a['stts'] == 1) { ?>
                                                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#tambah_bos<?= $a['id_spj'] ?>"><i class="fa fa-check"></i> Setujui</button>
                                                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#tolak_bos<?= $a['id_spj'] ?>"><i class="fa fa-times"></i> Tolak</button>
                                                    <?php } elseif ($a['stts'] == 2) { ?>
                                                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#uploads<?= $a['id_spj'] ?>"><i class="fa fa-money"></i> Upload Sisa</button>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <!-- Modal Setujui BOS-->
                                            <div class="modal fade" id="tambah_bos<?= $a['id_spj'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">

                                                        <div class="modal-header">
                                                            <h4 class="modal-title" id="myModalLabel">Form persetujuan SPJ</h4>
                                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left input_mask" action="" method="post">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="id" value="<?= $a['id_spj']; ?>">
                                                                <input type="hidden" name="kode" value="<?= $a['kode_pengajuan']; ?>">
                                                                <input type="hidden" name="hp" value="<?= $a['hp']; ?>">
                                                                <div class="item form-group">
                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Lembaga <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-md-6 col-sm-6 ">
                                                                        <input type="text" id="first-name" name="nm_lm" required="required" readonly value="<?= $a['nama'] ?>" class="form-control ">
                                                                    </div>
                                                                </div>
                                                                <div class="item form-group">
                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Periode <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-md-6 col-sm-6 ">
                                                                        <input type="text" id="first-name" required="required" disabled value="<?= $bulan[$a['bulan']] . ' ' . $a['tahun'] ?>" class="form-control ">
                                                                    </div>
                                                                </div>
                                                                <div class="item form-group">
                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nominal <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-md-6 col-sm-6 ">
                                                                        <input type="text" id="first-name" name="cair" required="required" value="<?= rupiah($pjan) ?>" class="form-control" readonly>
                                                                    </div>
                                                                </div>
                                                                <!-- <div class='item form-group'>
                                                                    <label class='col-form-label col-md-3 col-sm-3 label-align' for='first-name'>Dana Terserap <span class='required'>*</span>
                                                                    </label>
                                                                    <div class='col-md-6 col-sm-6  form-group has-feedback'>
                                                                        <input type='text' class='form-control has-feedback-left ' id='uang' name='serap' required>
                                                                        <span class='form-control-feedback left' aria-hidden='true'>Rp.</span>
                                                                    </div>
                                                                </div>
                                                                <div class="item form-group">
                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Tanggal setor <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-md-6 col-sm-6 ">
                                                                        <input type="date" id="" required="required" name="tgl_setor" class="form-control ">
                                                                    </div>
                                                                </div> -->
                                                                <div class="item form-group">
                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Tanggal disetujui <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-md-6 col-sm-6 ">
                                                                        <input type="date" id="" required="required" name="tgl" class="form-control ">
                                                                    </div>
                                                                </div>
                                                                <div class="item form-group">
                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Menyetujui <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-md-6 col-sm-6 ">
                                                                        <input type="text" required="required" name="user" value="<?= $nama_user ?>" class="form-control" readonly>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="save" class="btn btn-success"><i class="fa fa-check"></i> Setujui</button>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Tolak BOS-->
                                            <div class="modal fade" id="tolak_bos<?= $a['id_spj'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">

                                                        <div class="modal-header">
                                                            <h4 class="modal-title" id="myModalLabel">Form Penolakan SPJ</h4>
                                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left input_mask" action="" method="post">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="id" value="<?= $a['id_spj']; ?>">
                                                                <input type="hidden" name="kode" value="<?= $a['kode_pengajuan']; ?>">
                                                                <input type="hidden" name="hp" value="<?= $a['hp']; ?>">
                                                                <div class="item form-group">
                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Lembaga <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-md-6 col-sm-6 ">
                                                                        <input type="text" name="nm_lm" id="first-name" readonly value="<?= $a['nama'] ?>" class="form-control ">
                                                                    </div>
                                                                </div>
                                                                <div class="item form-group">
                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Periode <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-md-6 col-sm-6 ">
                                                                        <input type="text" id="first-name" required="required" disabled value="<?= $bulan[$a['bulan']] . ' ' . $a['tahun'] ?>" class="form-control ">
                                                                    </div>
                                                                </div>
                                                                <div class="item form-group">
                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nominal <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-md-6 col-sm-6 ">
                                                                        <input type="text" id="first-name" required="required" disabled value="<?= rupiah($pjan) ?>" class="form-control ">
                                                                    </div>
                                                                </div>
                                                                <div class="item form-group">
                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Tanggal penolakan <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-md-6 col-sm-6 ">
                                                                        <input type="date" id="" required="required" name="tgl" class="form-control ">
                                                                    </div>
                                                                </div>
                                                                <div class="item form-group">
                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Catatan Penolakan <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-md-6 col-sm-6 ">
                                                                        <textarea id="" required="required" name="isi" class="form-control "></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="item form-group">
                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Menolaks <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-md-6 col-sm-6 ">
                                                                        <input type="text" required="required" name="user" value="<?= $nama_user ?>" class="form-control" readonly>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="nono" class="btn btn-danger"><i class="fa fa-check"></i> Tolak Now</button>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Upload BOS-->
                                            <div class="modal fade" id="uploads<?= $a['id_spj'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">

                                                        <div class="modal-header">
                                                            <h4 class="modal-title" id="myModalLabel">Form persetujuan SPJ</h4>
                                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left input_mask" action="" method="post">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="id" value="<?= $a['id_spj']; ?>">
                                                                <input type="hidden" name="kode" value="<?= $a['kode_pengajuan']; ?>">
                                                                <input type="hidden" name="hp" value="<?= $a['hp']; ?>">
                                                                <div class="item form-group">
                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Lembaga <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-md-6 col-sm-6 ">
                                                                        <input type="text" id="first-name" name="nm_lm" required="required" readonly value="<?= $a['nama'] ?>" class="form-control ">
                                                                    </div>
                                                                </div>
                                                                <div class="item form-group">
                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Periode <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-md-6 col-sm-6 ">
                                                                        <input type="text" id="first-name" required="required" disabled value="<?= $bulan[$a['bulan']] . ' ' . $a['tahun'] ?>" class="form-control ">
                                                                    </div>
                                                                </div>
                                                                <div class="item form-group">
                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nominal <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-md-6 col-sm-6 ">
                                                                        <input type="text" id="first-name" name="cair" required="required" value="<?= rupiah($pjan) ?>" class="form-control" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class='item form-group'>
                                                                    <label class='col-form-label col-md-3 col-sm-3 label-align' for='first-name'>Dana Terserap <span class='required'>*</span>
                                                                    </label>
                                                                    <div class='col-md-6 col-sm-6  form-group has-feedback'>
                                                                        <input type='text' class='form-control has-feedback-left ' id='uang' name='serap' required>
                                                                        <span class='form-control-feedback left' aria-hidden='true'>Rp.</span>
                                                                    </div>
                                                                </div>
                                                                <div class="item form-group">
                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Tanggal setor <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-md-6 col-sm-6 ">
                                                                        <input type="date" id="" required="required" name="tgl_setor" class="form-control ">
                                                                    </div>
                                                                </div>
                                                                <div class="item form-group">
                                                                    <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Menyetujui <span class="required">*</span>
                                                                    </label>
                                                                    <div class="col-md-6 col-sm-6 ">
                                                                        <input type="text" required="required" name="user" value="<?= $nama_user ?>" class="form-control" readonly>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="upload" class="btn btn-success"><i class="fa fa-check"></i> Setujui</button>
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
    <div class="clearfix"></div>
</div>
<!-- /page content -->

<?php include 'foot.php'; ?>
<!-- Datatables -->
<script src="../main/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../main/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="../main/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="../main/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="../main/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="../main/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="../main/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="../main/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="../main/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="../main/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="../main/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="../main/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
<!-- bootstrap-daterangepicker -->
<script src="../main/vendors/moment/min/moment.min.js"></script>
<script src="../main/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap-datetimepicker -->
<script src="../main/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
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

    $id = $_POST['id'];
    $kode = $_POST['kode'];
    $nm_lm = $_POST['nm_lm'];
    $hp = $_POST['hp'];
    $at = date('d-m-Y H:i');
    // $idrls = rand(0, 999999999);
    // $cair = htmlspecialchars(mysqli_real_escape_string($conn, preg_replace("/[^0-9]/", "", $_POST['cair'])));
    // $serap = htmlspecialchars(mysqli_real_escape_string($conn, preg_replace("/[^0-9]/", "", $_POST['serap'])));
    // $sisa = $cair - $serap;
    // $tgl_setor = $_POST['tgl_setor'];

    if (preg_match("/DISP./i", $kode)) {
        $rt = "*(DISPOSISI)*";
    } else {
        $rt = '';
    }

    $psn = '
*INFORMASI VERIFIKASI SPJ* ' . $rt . '

Ada pelaporan SPJ dari :
    
Lembaga : ' . $nm_lm . '
Kode Pengajuan : ' . $kode . '
Pada : ' . $at . '

*_SPJ telah disetujui oleh SUB BAGIAN ACCOUNTING. Dimohon kepada KPA untuk segera menyerahkan hard copy SPJ dan sisa belanja anggaran  kepada SUB BAGIAN ACCOUNTING. Untuk bisa melakukan pengajuan berikutnya._*

Terimakasih';

    if ($serap > $cair) {
        echo "
    <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Maaf. Nominal terserap melebihi',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = 'spj.php'
                }, millisecondsToWait);

            });
        </script>
    ";
    } else {

        $sql = mysqli_query($conn, "UPDATE spj SET stts = 2 WHERE id_spj = '$id' AND tahun = '$tahun_ajaran' ");
        $sql2 = mysqli_query($conn, "UPDATE pengajuan SET spj = 2 WHERE kode_pengajuan = '$kode' AND tahun = '$tahun_ajaran' ");
        // $sql3 = mysqli_query($conn, "INSERT INTO real_sisa VALUES ('$id', '$kode', '$cair', '$serap', '$sisa', '$tgl_setor', '$tahun_ajaran') ");

        if ($sql) { ?>
            <script>
                $(document).ready(function() {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'SPJ sudah di verifikasi',
                        showConfirmButton: false
                    });
                    var millisecondsToWait = 1000;
                    setTimeout(function() {
                        document.location.href = "spj.php"
                    }, millisecondsToWait);

                });
            </script>

        <?php

            kirim_group($api_key, 'DfBeAZ3zGcR5qvLmBdKJaZ', $psn);
            kirim_group($api_key, 'FbXW8kqR5ik6w6iCB49GZK', $psn);
            kirim_person($api_key, $hp, $psn);
        }
    }
}

if (isset($_POST['upload'])) {

    $id = $_POST['id'];
    $kode = $_POST['kode'];
    $nm_lm = $_POST['nm_lm'];
    $hp = $_POST['hp'];
    $at = date('d-m-Y H:i');
    $idrls = rand(0, 999999999);
    $cair = htmlspecialchars(mysqli_real_escape_string($conn, preg_replace("/[^0-9]/", "", $_POST['cair'])));
    $serap = htmlspecialchars(mysqli_real_escape_string($conn, preg_replace("/[^0-9]/", "", $_POST['serap'])));
    $sisa = $cair - $serap;
    $tgl_setor = $_POST['tgl_setor'];

    if (preg_match("/DISP./i", $kode)) {
        $rt = "*(DISPOSISI)*";
    } else {
        $rt = '';
    }

    $psn = '
*INFORMASI VERIFIKASI SPJ* ' . $rt . '

Ada pelaporan SPJ dari :
    
Lembaga : ' . $nm_lm . '
Kode Pengajuan : ' . $kode . '
Pada : ' . $at . '

*_Hard copy SPJ dan sisa belanja anggaran telah disetor kepada SUB BAGIAN ACCOUNTING. Untuk pengajuan berikutnya sudah bisa dilakukan._*

Terimakasih
https://simkupaduka.ppdwk.com/';

    if ($serap > $cair) {
        echo "
    <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Maaf. Nominal terserap melebihi',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = 'spj.php'
                }, millisecondsToWait);

            });
        </script>
    ";
    } else {

        $sql = mysqli_query($conn, "UPDATE spj SET stts = 3 WHERE id_spj = '$id' AND tahun = '$tahun_ajaran' ");
        $sql2 = mysqli_query($conn, "UPDATE pengajuan SET spj = 3 WHERE kode_pengajuan = '$kode' AND tahun = '$tahun_ajaran' ");
        $sql3 = mysqli_query($conn, "INSERT INTO real_sisa VALUES ('$id', '$kode', '$cair', '$serap', '$sisa', '$tgl_setor', '$tahun_ajaran') ");

        if ($sql) { ?>
            <script>
                $(document).ready(function() {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'SPJ sudah di verifikasi',
                        showConfirmButton: false
                    });
                    var millisecondsToWait = 1000;
                    setTimeout(function() {
                        document.location.href = "spj.php"
                    }, millisecondsToWait);

                });
            </script>

        <?php

            kirim_group($api_key, 'DfBeAZ3zGcR5qvLmBdKJaZ', $psn);
            kirim_group($api_key, 'FbXW8kqR5ik6w6iCB49GZK', $psn);
            kirim_person($api_key, $hp, $psn);
        }
    }
}

if (isset($_POST['nono'])) {

    $id = $_POST['id'];
    $kode = $_POST['kode'];
    $nm_lm = $_POST['nm_lm'];
    $hp = $_POST['hp'];
    $isi = addslashes(mysqli_real_escape_string($conn, $_POST['isi']));
    $at = date('d-m-Y H:i');

    if (preg_match("/DISP./i", $kode)) {
        $rt = "*(DISPOSISI)*";
    } else {
        $rt = '';
    }

    $psn = '
*INFORMASI VERIFIKASI SPJ* ' . $rt . '

Ada Penolakan SPJ dari :
    
Lembaga : ' . $nm_lm . '
Kode Pengajuan : ' . $kode . '
Pada : ' . $at . '

*_SPJ DITOLAK oleh TIM ACCOUNTING. dengan catatan ' . $isi . '_*
Mohon kepada lembaga terkait untuk segera memperbaikinya dan mengupload ulang SPJ yang sudah diperbaiki di https://simkupaduka.ppdwk.com/.
Terimakasih';

    $sql = mysqli_query($conn, "UPDATE spj SET stts = 0 WHERE id_spj = '$id' AND tahun = '$tahun_ajaran' ");
    $sql2 = mysqli_query($conn, "UPDATE pengajuan SET spj = 0 WHERE kode_pengajuan = '$kode' AND tahun = '$tahun_ajaran' ");
    if ($sql) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'SPJ di tolak',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "spj.php"
                }, millisecondsToWait);

            });
        </script>

<?php

        // Send WA
        kirim_group($api_key, 'DfBeAZ3zGcR5qvLmBdKJaZ', $psn);
        kirim_group($api_key, 'FbXW8kqR5ik6w6iCB49GZK', $psn);
        kirim_person($api_key, $hp, $psn);
        //End send WA
    }
}
?>