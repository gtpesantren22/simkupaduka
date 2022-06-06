<?php
include 'atas.php';

$lembaga = $kol;
$kode_pengajuan = $_GET['kode'];
$lem = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$lembaga' "));
$ck = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pengajuan WHERE kode_pengajuan = '$kode_pengajuan' "));

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
            <?php if ($ck['verval'] == 0 && $ck['apr'] == 0 && $ck['cair'] == 0 && $ck['spj'] == 0 && $ck['stts'] == 'no') { ?>
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
                                                        <th>Jenis Belanja</th>
                                                        <td>
                                                            <select name="jenis" id="jakarta" class="form-control" required>
                                                                <option value=""> - pilih - </option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>RAB</th>
                                                        <td>
                                                            <div class="row">

                                                                <div class="col-md-9">
                                                                    <select class="form-control" name="id_rab" id="search_query" required>
                                                                        <option value="">-pilih RAB-</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <button class="btn btn-success btn-sm" type="button" id="button_find"><i class="fa fa-search"></i> Cek</button>
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
                                                                    <input type="text" name="bulan" id="" class="form-control" value="<?= $bulan[$ck['bulan']] ?>" readonly>
                                                                </div>
                                                                <div class="col-md-5">
                                                                    <input type="text" name="tahun" id="" class="form-control" value="<?= $ck['tahun'] ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>Nama PJ</th>
                                                        <th><input type="text" name="pj" class="form-control" required></th>
                                                    </tr>
                                                    <tr>
                                                        <th>Tgl Pengajuan</th>
                                                        <th><input type="date" name="tgl" class="form-control" required>
                                                        </th>
                                                    </tr>
                                                    <!-- <tr>
                                                        <th>Keterangan</th>
                                                        <th><textarea name="ket" id="" cols="30" rows="3" required class="form-control"></textarea>
                                                        </th>
                                                    </tr> -->
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
            <?php } ?>

            <!-- Table ke2 -->
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Rincian Pengajuan Realisasi RAB</h3>
                        <a href="pengajuan.php"><button class="btn btn-warning pull-right btn-xs"><i class="fa fa-arrow-left"></i> Kembali</button></a>
                        <?php if ($ck['verval'] == 0 && $ck['apr'] == 0 && $ck['cair'] == 0 && $ck['spj'] == 0 && $ck['stts'] == 'no') { ?>
                            <a href="pj_ok.php?kd=<?= $kode_pengajuan; ?>" onclick="return confirm('Yakin pengajuan sudah selesai dan akan diajukan kepada accouting ?')"><button class="btn btn-info pull-right btn-sm"><i class="fa fa-check"></i> Pengajuan sudah selesai ? Ajukan sekarang</button></a>
                        <?php } ?>
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
                                    $nm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jml FROM real_sm WHERE lembaga = '$kol' "));

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
                                            <td>
                                                <?php if ($ck['verval'] == 0 && $ck['stts'] == 'no') { ?>
                                                    <a href="<?= 'hapus.php?kd=del_real_sm&id=' . $ls_jns['id_realis'] ?>" onclick="return confirm('Yakin akan dihapus ?')"><button class="btn btn-danger btn-xs">Hapus</button></a>
                                                <?php } else { ?>
                                                    <button class="btn btn-danger btn-xs" disabled>Hapus</button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4">TOTAL PENGAJUAN</th>
                                        <th colspan="3"><?= rupiah($nm['jml']); ?></th>
                                    </tr>
                                </tfoot>
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
<script type="text/javascript">
    $(document).ready(function() {
        var app = {
            show: function() {
                $.ajax({
                    url: "show.php",
                    method: "GET",
                    success: function(data) {
                        $("#jakarta").html(data)
                    }
                })
            },
            tampil: function() {
                var kotaId = $(this).val();
                $.ajax({
                    url: "data.php",
                    method: "POST",
                    data: {
                        kotaId: kotaId
                    },
                    success: function(data) {
                        $("#search_query").html(data)
                    }
                })
            }
        }
        app.show();
        $(document).on("change", "#jakarta", app.tampil)
    })
</script>
<script type='text/javascript'>
    $(document).ready(function() {
        //$("#search_results").slideUp();
        $("#button_find").click(function(event) {
            event.preventDefault();
            //search_ajax_way();
            ajax_search();
        });
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

    $id = $uuid;
    $kode = $_POST['id_rab'];
    $bulan = $ck['bulan'];
    $l = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM rab WHERE kode = '$kode' "));
    $lembaga = $l['lembaga'];
    $bidang = $l['bidang'];
    $jenis = $l['jenis'];
    $nominal = htmlspecialchars(mysqli_real_escape_string($conn, preg_replace("/[^0-9]/", "", $_POST['nominal'])));
    $tgl = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tgl']));
    $qty = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['qty']));
    $pj = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['pj']));
    $tahun = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tahun']));
    // $ket = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['ket']));
    $nm_rab = mysqli_real_escape_string($conn, $l['nama']);
    $ket = $nm_rab . ' - @ ' . $qty . ' x ' . number_format($l['harga_satuan'], 0, ',', '.');
    $kd_pjn = $kode_pengajuan;
    $sisa_jml = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['sisa_jml']));

    if ($jenis === 'A') {
        $stas = 'barang';
    } else {
        $stas = 'tunai';
    }

    if ($qty > $sisa_jml) { ?>

        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Maaf. Jumlah pengajuan anda melebihi dari yang tersisa',
                showConfirmButton: false
            });
            var millisecondsToWait = 1000;
            setTimeout(function() {
                document.location.href = "<?= 'pengajuan_add.php?kode=' . $kode_pengajuan ?>"
            }, millisecondsToWait);
        </script>
        <?php
    } else {
        $sql = mysqli_query($conn, "INSERT INTO real_sm VALUES ('$id', '$lembaga','$bidang','$jenis','$kode', '$qty', '$nominal', '$tgl', '$pj', '$bulan','$tahun','$ket', '$kd_pjn', '$nominal', '$stas')");
        if ($sql) { ?>

            <script>
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Pengajuan berhasil tersimpan',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "<?= 'pengajuan_add.php?kode=' . $kode_pengajuan ?>"
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
}

?>