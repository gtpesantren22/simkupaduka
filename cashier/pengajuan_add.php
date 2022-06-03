<?php
include 'atas.php';

$lembaga = $kol;
$lem = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$lembaga' "));

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Pengajuan Realiasasi
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
                        <h3 class="box-title">Tambah Pengajuan Realiasasi RAB</h3>
                        <a href="pengajuan.php"><button class="btn btn-warning pull-right btn-xs"><i class="fa fa-arrow-left"></i> Kembali</button></a>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <form action="" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <tbody>
                                                <tr>
                                                    <th>Nama Lembaga</th>
                                                    <td><input type="text" class="form-control" value="<?= $lem['nama'] ?>" disabled></td>
                                                </tr>
                                                <tr>
                                                    <th>Pilih RAB</th>
                                                    <td>
                                                        <div class="row">

                                                            <div class="col-md-9">
                                                                <select class="form-control select2" name="id_rab" id="search_query" required>
                                                                    <option value="">-pilih RAB-</option>
                                                                    <?php
                                                                    $rb = mysqli_query($conn, "SELECT a.kode, a.nama, b.nama as nmb FROM rab a JOIN bidang b ON a.bidang=b.kode WHERE a.lembaga = '$lembaga' ");
                                                                    while ($lm = mysqli_fetch_assoc($rb)) { ?>
                                                                        <option value="<?= $lm['kode'] ?>"><?= $lm['nama'] ?> - (<?= $lm['nmb'] ?>)</option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <button class="btn btn-success btn-sm" type="button" id="button_find"><i class="fa fa-search"></i> Cari</button>
                                                            </div>

                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div id="display_results"></div>
                                    </div><!-- /.col -->
                                </div>
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <tbody>
                                                <tr>
                                                    <th>Rencana</th>
                                                    <th>
                                                        <div class="row">
                                                            <div class="col-md-7">
                                                                <select name="bulan" id="" class="form-control">
                                                                    <option value=""> -pilih bulan pemakaian- </option>
                                                                    <?php for ($i = 1; $i <= 12; $i++) { ?>
                                                                        <option value="<?= $i ?>"><?= $bulan[$i] ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <!-- <select name="tahun" id="" class="form-control">
                                                                <option value=""> -pilih tahun- </option>
                                                                <?php for ($i = 2010; $i <= date('Y'); $i++) { ?>
                                                                    <option value="<?= $i ?>"><?= $i ?></option>
                                                                <?php } ?>
                                                            </select> -->
                                                                <input type="text" name="tahun" id="" class="form-control" value="<?= date('Y') ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>Nama PJ</th>
                                                    <th><input type="text" name="pj" class="form-control" required></th>
                                                </tr>
                                                <tr>
                                                    <th>Nominal</th>
                                                    <th><input type="text" name="nominal" id="uang" class="form-control" required>
                                                        <i style="color: red; ">*Jumlah nominal tidak boleh melebihi dari sisa RAB</i>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>Keterangan</th>
                                                    <th><textarea name="ket" id="" cols="30" rows="3" required class="form-control"></textarea>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th colspan="2"><button class="btn btn-success btn-sm pull-right" type="submit" name="save"><i class="fa fa-save"></i> Simpan</button></th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div><!-- /.col -->
                                </div>
                            </div>
                        </form>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>

            <!-- Table ke2 -->
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Daftar Pengajuan Realisasi RAB</h3>
                        <a href="pengajuan.php"><button class="btn btn-warning pull-right btn-xs"><i class="fa fa-arrow-left"></i> Kembali</button></a>

                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="example1_bst" class="table table-bordered table-striped">
                                <thead>
                                    <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                        <th>No</th>
                                        <th>Kode RAB</th>
                                        <th>Bulan</th>
                                        <th>PJ</th>
                                        <th>Nominal</th>
                                        <!-- <th>Tgl</th> -->
                                        <th>Ket</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $rls = mysqli_query($conn, "SELECT * FROM real_sm WHERE lembaga = '$kol' ");
                                    while ($ls_jns = mysqli_fetch_assoc($rls)) {
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $ls_jns['kode']; ?></td>
                                            <td><?= $bulan[$ls_jns['bulan']]; ?> <?= $ls_jns['tahun']; ?></td>
                                            <td><?= $ls_jns['pj']; ?></td>
                                            <td><?= rupiah($ls_jns['nominal']); ?></td>
                                            <!-- <td><?= date('H:i', strtotime($ls_jns['tgl'])); ?></td> -->
                                            <td><?= $ls_jns['ket']; ?></td>
                                            <td><a href="<?= 'hapus.php?kd=del_real_sm&id=' . $ls_jns['id_realis'] ?>" onclick="return confirm('Yakin akan dihapus ?')"><button class="btn btn-danger btn-xs">Hapus</button></a></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div>
    </section><!-- /.content -->
</div>

<!-- DataTables -->
<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
<!-- Select2 -->
<link rel="stylesheet" href="plugins/select2/select2.min.css">
<!-- jQuery 2.1.4 -->
<script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="plugins/select2/select2.full.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
    $(function() {
        $(".select2").select2();
        $("#example1_bst").DataTable();
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
<script type='text/javascript'>
    $(document).ready(function() {
        //$("#search_results").slideUp();
        $("#button_find").click(function(event) {
            event.preventDefault();
            //search_ajax_way();
            ajax_search();
        });
        // $("#search_query").keyup(function(event) {
        // 	event.preventDefault();
        // 	//search_ajax_way();
        // 	ajax_search();
        // });
    });

    function ajax_search() {

        var judul = $("#search_query").val();
        $.ajax({
            url: "cari.php",
            data: "judul=" + judul,
            success: function(data) {
                // jika data sukses diambil dari server, tampilkan di <select id=kota>
                $("#display_results").html(data);
            }
        });

    }
</script>
<?php
include 'bawah.php';
?>
<?php
if (isset($_POST['save'])) {

    $pj = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(no_urut) as nu FROM pengajuan"));
    $urut = $pj['nu'] + 1;
    $id = $uuid;
    $kode = $_POST['id_rab'];
    $bulan = $_POST['bulan'];
    $l = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM rab WHERE kode = '$kode' "));
    $lembaga = $l['lembaga'];
    $bidang = $l['bidang'];
    $jenis = $l['jenis'];
    $nominal = htmlspecialchars(mysqli_real_escape_string($conn, preg_replace("/[^0-9]/", "", $_POST['nominal'])));
    $tgl = date('Y-m-d');
    $pj = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['pj']));
    $tahun = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tahun']));
    $ket = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['ket']));
    $kd_pjn = $lembaga . '.' . $bulan . '.' . $tahun;


    $sql = mysqli_query($conn, "INSERT INTO real_sm VALUES ('$id', '$lembaga','$bidang','$jenis','$kode', '$nominal', '$tgl', '$pj', '$bulan','$tahun','$ket', '$kd_pjn')");
    if ($sql) {
        $cek = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pengajuan WHERE kode = '$kd_pjn' "));
        if ($cek == 0) {
            mysqli_query($conn, "INSERT INTO pengajuan(id_pn, kode, lembaga, bulan, tahun, no_urut) VALUES ('$id', '$kd_pjn','$lembaga','$bulan','$tahun', '$urut') ");
            mysqli_query($conn, "INSERT INTO spj(id_spj, kode, lembaga, bulan, tahun, no_urut) VALUES ('$id', '$kd_pjn','$lembaga','$bulan','$tahun','$urut') ");
        }
?>

        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Belanja berhasil tersimpan',
                showConfirmButton: false
            });
            var millisecondsToWait = 1000;
            setTimeout(function() {
                document.location.href = "<?= 'pengajuan_add.php' ?>"
            }, millisecondsToWait);
        </script>
<?php
    } else {
        echo "
                <script>
                alert('Gagal  simpan');
                </script>
            ";
    }
}


?>