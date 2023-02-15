<?php
include 'atas.php';

$lembaga = $kol;
$kode_pengajuan = $_GET['kode'];
$lem = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$lembaga' AND tahun = '$tahun_ajaran' "));
$ck = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pengajuan WHERE kode_pengajuan = '$kode_pengajuan' AND tahun = '$tahun_ajaran' "));

$jml = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nom_cair) AS jml FROM real_sm WHERE kode_pengajuan LIKE 'DISP.%' AND tahun = '$tahun_ajaran' "));
$jml2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nom_cair) AS jml FROM realis WHERE kode_pengajuan LIKE 'DISP.%' AND tahun = '$tahun_ajaran' "));
$kfe = 50000000 - ($jml['jml'] + $jml2['jml']);
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
                                                        <th>Bidang</th>
                                                        <td>
                                                            <select name="bidang" class="form-control" id="" required>
                                                                <option value=""> -pilih bidang- </option>
                                                                <?php
                                                                $qr2 = mysqli_query($conn, "SELECT * FROM bidang WHERE tahun = '$tahun_ajaran'");
                                                                while ($a2 = mysqli_fetch_assoc($qr2)) { ?>
                                                                    <option value="<?= $a2['kode'] ?>"><?= $a2['kode'] ?>.
                                                                        <?= $a2['nama'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Jenis Belanja</th>
                                                        <td>
                                                            <select name="jenis" id="" class="form-control" required>
                                                                <option value=""> - pilih - </option>
                                                                <option value="A">A. Belanja Barang</option>
                                                                <option value="B">B. Barang dan Jasa</option>
                                                                <option value="C">C. Belanja Kegiatan</option>
                                                                <option value="D">D. Umum</option>
                                                            </select>
                                                        </td>
                                                    </tr>
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
                                                </tbody>
                                            </table>
                                        </div><!-- /.col -->
                                    </div>
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <tbody>
                                                    <tr>
                                                        <th>Nama Barang/Kegiatan</th>
                                                        <th><input type="text" name="nm_rab" class="form-control" required>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>Jumlah/QTY</th>
                                                        <th>
                                                            <div class="row">
                                                                <div class="col-md-5">
                                                                    <input type="number" name="qty" id="" class="form-control" required placeholder="Jumlah/QTY">
                                                                </div>
                                                                <div class="col-md-7">
                                                                    <input type="text" name="satuan" id="" class="form-control" required placeholder="Satuan (dos/pcs/rim/dll..)">
                                                                </div>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>Harga Satuan</th>
                                                        <th><input type="text" name="harga" id="uang" class="form-control" required></th>
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
                                                    <tr>
                                                        <th colspan="2"><button class="btn btn-success btn-sm pull-right" type="submit" name="save"><i class="fa fa-save"></i>
                                                                Simpan</button></th>
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
                            <a href="pj_ok.php?kd=<?= $kode_pengajuan; ?>" onclick="return confirm('Yakin pengajuan sudah selesai dan akan diajukan kepada accouting ?')"><button class="btn btn-info pull-right btn-sm"><i class="fa fa-check"></i> Pengajuan sudah
                                    selesai ? Ajukan sekarang</button></a>
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
                                        <th>Cair</th>
                                        <th>Ket</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($ck['cair'] ==  1) {
                                        $rls = mysqli_query($conn, "SELECT * FROM realis WHERE kode_pengajuan = '$kode_pengajuan' AND tahun = '$tahun_ajaran' ");
                                    } else {
                                        $rls = mysqli_query($conn, "SELECT * FROM real_sm WHERE kode_pengajuan = '$kode_pengajuan' ");
                                    }
                                    $nm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jml FROM real_sm WHERE kode_pengajuan = '$kode_pengajuan' AND tahun = '$tahun_ajaran' "));

                                    while ($ls_jns = mysqli_fetch_assoc($rls)) {
                                        $kd_rab = $ls_jns['kode'];
                                        $kd_ppnj = $ls_jns['kode_pengajuan'];
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td>Disposisi</td>
                                            <td><?= $bulan[$ls_jns['bulan']]; ?> <?= $ls_jns['tahun']; ?></td>
                                            <td><?= $ls_jns['pj']; ?></td>
                                            <td><?= rupiah($ls_jns['nominal']); ?></td>
                                            <!-- <td><?= date('H:i', strtotime($ls_jns['tgl'])); ?></td> -->
                                            <td>
                                                <?= $ls_jns['ket']; ?>
                                                <?php
                                                if (preg_match("/honor/i", $ls_jns['ket'])) {
                                                    $jm_upd = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM honor_file WHERE kode_pengajuan = '$kd_ppnj' AND kode_rab = '$kd_rab' AND tahun = '$tahun_ajaran' "));
                                                    if ($jm_upd > 0) {
                                                        $ktb = 'update';
                                                        $lbl = "<span class='label label-success'><i class='fa fa-check'></i> sudah</span>";
                                                    } else {
                                                        $ktb = 'baru';
                                                        $lbl = "<span class='label label-danger'><i class='fa fa-times'></i> belum</span>";
                                                    }
                                                ?>
                                                    <hr>
                                                    <i>Jika pengajuan honor. Maka diwajibkan untuk upload rincian honor.
                                                        (xls/xlsx)</i>
                                                    <form action="" method="post" enctype="multipart/form-data">
                                                        <input type="hidden" name="kd_rab" value="<?= $kd_rab; ?>">
                                                        <input type="hidden" name="kd_ppnj" value="<?= $kd_ppnj; ?>">
                                                        <input type="hidden" name="ktb" value="<?= $ktb; ?>">
                                                        <input type="file" name="f_rin" class="form-control" required>
                                                        <button class="btn btn-success btn-sm" type="submit" name="save_baru"><i class="fa fa-save"></i></button>
                                                        | <b class="">Status Upload : <?= $lbl; ?></b>
                                                    </form>
                                                <?php } ?>
                                            </td>
                                            <td><?= $ls_jns['stas']; ?></td>
                                            <td>
                                                <?php if ($ck['verval'] == 0 && $ck['stts'] == 'no') { ?>
                                                    <a href="<?= 'hapus.php?kd=del_real_sm_disp&id=' . $ls_jns['id_realis'] ?>" onclick="return confirm('Yakin akan dihapus ?')"><button class="btn btn-danger btn-xs">Hapus</button></a>
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
    var rupiah = document.getElementById('uang');
    var rupiah2 = document.getElementById('uang_2');

    rupiah.addEventListener('keyup', function(e) {
        rupiah.value = formatRupiah(this.value);
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

<?php
include 'bawah.php';
?>

<?php
if (isset($_POST['save'])) {

    $id = $uuid;
    // $kode = $_POST['id_rab'];
    // $kd_rab = $l['kode'];
    // $lembaga = $l['lembaga'];
    $bidang = $_POST['bidang'];
    $jenis = $_POST['jenis'];
    $harga = htmlspecialchars(mysqli_real_escape_string($conn, preg_replace("/[^0-9]/", "", $_POST['harga'])));
    $qty = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['qty']));
    $nominal = $harga * $qty;
    $satuan = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['satuan']));
    $tgl = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tgl']));
    $pj = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['pj']));
    $bulan = $ck['bulan'];
    $tahun = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tahun']));
    // $ket = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['ket']));
    $nm_rab = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['nm_rab']));

    $ket = $nm_rab . ' - @ ' . $qty . ' ' . $satuan . ' x ' . number_format($harga, 0, ',', '.');
    $kd_pjn = $kode_pengajuan;
    // $sisa_jml = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['sisa_jml']));

    if ($jenis === 'A') {
        $stas = 'barang';
    } else {
        $stas = 'tunai';
    }

    if ($harga > $kfe) {
        echo "
            <script>
                alert('Maaf, Limit disposisi sudah tidak mencukupi');
                window.location = 'pengajuan_add_disp.php?kode=" . $kd_pjn . "';
            </script>
        ";
    } else {

        $sql12 = mysqli_query($conn, "INSERT INTO real_sm VALUES ('$id', '$lembaga','$bidang','$jenis','DISP', '$qty', '$nominal', '$tgl', '$pj', '$bulan','$tahun_ajaran','$ket', '$kd_pjn', '$nominal', '$nominal', '$stas')");
        if ($sql12) {
            echo "
                <script>
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Pengajuan berhasil tersimpan',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = 'pengajuan_add_disp.php?kode=' " . $kd_pjn . "'
                }, millisecondsToWait);
            </script>
            ";
        }
    }
}

if (isset($_POST['save_baru'])) {
    $kd_rab = $_POST['kd_rab'];
    $kd_ppnj = $_POST['kd_ppnj'];
    $ktb = $_POST['ktb'];

    $filename = $_FILES['f_rin']['name'];
    $dir = $_FILES['f_rin']['tmp_name'];
    $ekstensi =  array('xls', 'xlsx');
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if (!in_array($ext, $ekstensi)) {
        echo "
            <script>
                alert('Yang anda upload bukan file excel');
                window.location = 'pengajuan_add_disp.php?kode=" . $kd_ppnj . "';
                </script>
                ";
    } else {
        $nm_file = uniqid() . '.' . $ext;

        if ($ktb === 'baru') {
            $sql = mysqli_query($conn, "INSERT INTO honor_file VALUES ('', '$kd_ppnj', '$kd_rab', NOW(),'$nm_file', '$tahun_ajaran') ");
        } elseif ($ktb === 'update') {
            $sql = mysqli_query($conn, "UPDATE honor_file SET files = '$nm_file' WHERE kode_rab = '$kd_rab' AND kode_pengajuan = '$kd_ppnj' AND tahun = '$tahun_ajaran' ");
        }
        move_uploaded_file($dir, 'honor_file/' . $nm_file);
        if ($sql) {
            echo "
                    <script>
                    window.location = 'pengajuan_add_disp.php?kode=" . $kd_ppnj . "';
            </script>
            ";
        }
    }
}

?>