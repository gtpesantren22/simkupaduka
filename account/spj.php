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
                                        $dt_bos = mysqli_query($conn, "SELECT a.*, b.nama, b.hp FROM spj a JOIN lembaga b ON a.lembaga=b.kode WHERE a.stts != 2");
                                        while ($a = mysqli_fetch_assoc($dt_bos)) {
                                            $kd_pj = $a['kode_pengajuan'];
                                            $jml = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jml FROM real_sm WHERE kode_pengajuan = '$kd_pj' "));
                                            $jml2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jml FROM realis WHERE kode_pengajuan = '$kd_pj' "));
                                            $pjan = $jml['jml'] + $jml2['jml'];

                                            if (preg_match("/DISP./i", $kd_pj)) {
                                                $rt = "<span class='badge badge-danger'>DISPOSISI</span>";
                                            } else {
                                                $rt = '';
                                            }
                                        ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
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
                                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#tambah_bos<?= $a['id_spj'] ?>"><i class="fa fa-check"></i> Setujui</button>
                                                    <?php if ($a['stts'] == 1) { ?>
                                                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#tolak_bos<?= $a['id_spj'] ?>"><i class="fa fa-times"></i> Tolak</button>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <!-- Modal Tambah Data BOS-->
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
                                                                        <input type="text" id="first-name" required="required" disabled value="<?= rupiah($pjan) ?>" class="form-control ">
                                                                    </div>
                                                                </div>
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

<?php

if (isset($_POST['save'])) {

    $id = $_POST['id'];
    $kode = $_POST['kode'];
    $nm_lm = $_POST['nm_lm'];
    $hp = $_POST['hp'];
    $at = date('d-m-Y H:i');

    if (preg_match("/DISP./i", $kode)) {
        $rt = "*(DISPOSISI)*";
    } else {
        $rt = '';
    }

    $psn = '
*INFORMASI VERIFIKASI SPJ* ' . $rt . '

Ada pelaporan SPJ dari :
    
Lembaga : ' . $nm_lm . '
Kode Pengjuan : ' . $kode . '
Pada : ' . $at . '

*_SPJ telah disetujui oleh TIM ACCOUNTING. Selesai_*
Bisa dilanjutkan untuk pengajuan berikutnya.
Terimakasih';

    $sql = mysqli_query($conn, "UPDATE spj SET stts = 2 WHERE id_spj = '$id' ");
    $sql2 = mysqli_query($conn, "UPDATE pengajuan SET spj = 2 WHERE kode_pengajuan = '$kode' ");
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
        $curl2 = curl_init();
        curl_setopt_array(
            $curl2,
            array(
                CURLOPT_URL => 'http://8.215.26.187:3000/api/sendMessageGroup',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'apiKey=fb209be1f23625e43cbf285e57c0c0f2&id_group=DfBeAZ3zGcR5qvLmBdKJaZ&message=' . $psn,
            )
        );
        $response = curl_exec($curl2);
        curl_close($curl2);

        $curl3 = curl_init();
        curl_setopt_array(
            $curl3,
            array(
                CURLOPT_URL => 'http://8.215.26.187:3000/api/sendMessageGroup',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'apiKey=fb209be1f23625e43cbf285e57c0c0f2&id_group=FbXW8kqR5ik6w6iCB49GZK&message=' . $psn,
            )
        );
        $response = curl_exec($curl3);
        curl_close($curl3);

        // Japri 1
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'http://8.215.26.187:3000/api/sendMessage',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'apiKey=fb209be1f23625e43cbf285e57c0c0f2&phone=' . $hp . '&message=' . $psn,
            )
        );
        $response = curl_exec($curl);
        curl_close($curl);
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
Kode Pengjuan : ' . $kode . '
Pada : ' . $at . '

*_SPJ DITOLAK oleh TIM ACCOUNTING. dengan catatan ' . $isi . '_*
Mohon kepada lembaga terkait untuk segera memperbaikinya dan mengupload ulang SPJ yang sudah diperbaiki di https://simkupaduka.ppdwk.com/.
Terimakasih';

    $sql = mysqli_query($conn, "UPDATE spj SET stts = 0 WHERE id_spj = '$id' ");
    $sql2 = mysqli_query($conn, "UPDATE pengajuan SET spj = 0 WHERE kode_pengajuan = '$kode' ");
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
        $curl2 = curl_init();
        curl_setopt_array(
            $curl2,
            array(
                CURLOPT_URL => 'http://8.215.26.187:3000/api/sendMessageGroup',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'apiKey=fb209be1f23625e43cbf285e57c0c0f2&id_group=DfBeAZ3zGcR5qvLmBdKJaZ&message=' . $psn,
            )
        );
        $response = curl_exec($curl2);
        curl_close($curl2);

        $curl3 = curl_init();
        curl_setopt_array(
            $curl3,
            array(
                CURLOPT_URL => 'http://8.215.26.187:3000/api/sendMessageGroup',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'apiKey=fb209be1f23625e43cbf285e57c0c0f2&id_group=FbXW8kqR5ik6w6iCB49GZK&message=' . $psn,
            )
        );
        $response = curl_exec($curl3);
        curl_close($curl3);

        // Japri 1
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'http://8.215.26.187:3000/api/sendMessage',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'apiKey=fb209be1f23625e43cbf285e57c0c0f2&phone=' . $hp . '&message=' . $psn,
            )
        );
        $response = curl_exec($curl);
        curl_close($curl);
    }
}
?>