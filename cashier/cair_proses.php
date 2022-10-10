<?php
require 'atas.php';
$kode = $_GET['kode'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT a.*, b.nama FROM pengajuan a JOIN lembaga b ON a.lembaga=b.kode WHERE a.kode_pengajuan = '$kode' AND a.tahun = '$tahun_ajaran' AND b.tahun = '$tahun_ajaran' "));
$crr = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal_cair) as jml FROM pencairan WHERE kode_pengajuan = '$kode' AND tahun = '$tahun_ajaran' "));
$dt2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as jml, SUM(nom_cair) as jml_cair FROM real_sm WHERE kode_pengajuan = '$kode' AND tahun = '$tahun_ajaran' "));
$plih = mysqli_query($conn, "SELECT *  FROM real_sm WHERE kode_pengajuan = '$kode' AND tahun = '$tahun_ajaran' ");

if ($crr['jml'] > 0) {
    $tbl_slct = 'realis';
    $sts_tmbl = 'disabled';
    $dcair = $crr['jml'];
    $dblm = 0;
} else {
    $tbl_slct = 'real_sm';
    $sts_tmbl = '';
    $dcair = 0;
    $dblm = $dt2['jml_cair'];
}

$dt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nom_cair) as jml, SUM(IF( stas = 'tunai', nom_cair, 0)) AS tunai, SUM(IF( stas = 'barang', nom_cair, 0)) AS brg, SUM(IF( stas = 'tunai', nominal, 0)) AS tunai_asal, SUM(IF( stas = 'barang', nominal, 0)) AS brg_asal, SUM(IF( stas = 'tunai', nom_serap, 0)) AS tunai_serap, SUM(IF( stas = 'barang', nom_serap, 0)) AS brg_serap FROM $tbl_slct WHERE kode_pengajuan = '$kode' AND tahun = '$tahun_ajaran' "));

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Proses Pencairan Pengajuan
            <small>Realisasi RAB</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Pencairan</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-9">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Pengajuan Realiasasi RAB </h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th>Lembaga</th>
                                            <th>: <?= $data['nama'] ?></th>
                                        </tr>
                                        <tr>
                                            <th>Periode</th>
                                            <th>: <?= $bulan[$data['bulan']] . ' ' . $data['tahun'] ?></th>
                                        </tr>
                                        <tr>
                                            <th>Kode</th>
                                            <th>: <?= $kode ?></th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <p style="color: green; font-weight: bold; font-size: 15px;"><i class="fa fa-check"></i>
                                    Dana Cair</p>
                                <p style="color: green; font-weight: bold; font-size: 25px;"><?= rupiah($dcair) ?>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p style="color: red; font-weight: bold; font-size: 15px;"><i class="fa fa-times"></i>
                                    Belum Cair</p>
                                <p style="color: red; font-weight: bold; font-size: 25px;"><?= rupiah($dblm) ?>
                                </p>
                            </div>
                        </div>
                        <br>
                        <div class="table-responsive">
                            <form class="form-horizontal" action="" method="post">
                                <input type="hidden" name="lembaga" value="<?= $data['lembaga'] ?>">
                                <input type="hidden" name="kode_pengajuan" value="<?= $data['kode_pengajuan'] ?>">
                                <input type="hidden" name="nominal" value="<?= $dt2['jml'] ?>">

                                <!-- Cair Tunai -->
                                <table id="example1_bst" class="table table-bordered table-striped">
                                    <thead>
                                        <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                            <th>#</th>
                                            <th>Kode RAB</th>
                                            <th>PJ</th>
                                            <th>Keterangan</th>
                                            <th>Nominal</th>
                                            <th>Disetujui</th>
                                            <th>Terserap</th>
                                            <th>Ket</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $rls = mysqli_query($conn, "SELECT * FROM $tbl_slct WHERE kode_pengajuan = '$kode' AND stas = 'tunai' AND tahun = '$tahun_ajaran' ORDER BY stas ASC ");
                                        while ($ls_jns = mysqli_fetch_assoc($rls)) {
                                            $ids = explode('-', $ls_jns['id_realis']);
                                            $idOk = $ids[0];
                                        ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="" value="<?= $ls_jns['nom_cair'] ?>"
                                                    onClick="this.form.total.value=checkChoice(this);">
                                                <input type="checkbox" name="id_pnj[]"
                                                    value="<?= $ls_jns['id_realis'] ?>">

                                            </td>
                                            <td><?= $ls_jns['kode']; ?></td>
                                            <td><?= $ls_jns['pj']; ?></td>
                                            <td><?= $ls_jns['ket']; ?></td>
                                            <td><?= rupiah($ls_jns['nominal']); ?></td>
                                            <td><?= rupiah($ls_jns['nom_cair']); ?></td>
                                            <td>
                                                <form action="" method="post">
                                                    <input type="hidden" name="id" value="<?= $ls_jns['id_realis']; ?>">
                                                    <div class="col-md-10">
                                                        <input type="text" name="serap" id="psse<?= $idOk; ?>"
                                                            value="<?= rupiah($ls_jns['nom_serap']); ?>">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button class="btn btn-xs btn-success" type="submit"
                                                            name="sbmpt"><i class="fa fa-check"></i></button>
                                                    </div>
                                                </form>
                                            </td>
                                            <td><?= $ls_jns['stas']; ?></td>
                                        </tr>
                                        <script>
                                        var rupiah<?= $idOk ?> = document.getElementById(
                                            'psse<?= $idOk ?>');

                                        rupiah<?= $idOk ?>.addEventListener('keyup', function(e) {
                                            rupiah<?= $idOk ?>.value = formatRupiah(this.value);
                                        });
                                        </script>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr style="color: white; background-color: green; font-weight: bold;">
                                            <th colspan="4">Total</th>
                                            <th><?= rupiah($dt['tunai_asal']) ?></th>
                                            <th><?= rupiah($dt['tunai']) ?></th>
                                            <th><?= rupiah($dt['tunai_serap']) ?></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>

                                <!-- Cair Barang -->
                                <table id="example1_bst" class="table table-bordered table-striped">
                                    <thead>
                                        <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                            <th>#</th>
                                            <th>Kode RAB</th>
                                            <th>PJ</th>
                                            <th>Keterangan</th>
                                            <th>Nominal</th>
                                            <th>Disetujui</th>
                                            <th>Terserap</th>
                                            <th>Ket</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $rls = mysqli_query($conn, "SELECT * FROM $tbl_slct WHERE kode_pengajuan = '$kode' AND stas = 'barang' AND tahun = '$tahun_ajaran' ORDER BY stas ASC ");
                                        while ($ls_jns = mysqli_fetch_assoc($rls)) {
                                            //$kode = $ls_jns['kode'];
                                            $ids = explode('-', $ls_jns['id_realis']);
                                            $idOk = $ids[0];
                                        ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="" value="<?= $ls_jns['nom_cair'] ?>"
                                                    onClick="this.form.total.value=checkChoice(this);">
                                                <input type="checkbox" name="id_pnj[]"
                                                    value="<?= $ls_jns['id_realis'] ?>">

                                            </td>
                                            <td><?= $ls_jns['kode']; ?></td>
                                            <td><?= $ls_jns['pj']; ?></td>
                                            <td><?= $ls_jns['ket']; ?></td>
                                            <td><?= rupiah($ls_jns['nominal']); ?></td>
                                            <td><?= rupiah($ls_jns['nom_cair']); ?></td>
                                            <td>
                                                <form action="" method="post">
                                                    <input type="hidden" name="id" value="<?= $ls_jns['id_realis']; ?>">
                                                    <div class="col-md-10">
                                                        <input type="text" name="serap" id="psse<?= $idOk; ?>"
                                                            value="<?= rupiah($ls_jns['nom_serap']); ?>">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button class="btn btn-xs btn-success" type="submit"
                                                            name="sbmpt"><i class="fa fa-check"></i></button>
                                                    </div>
                                                </form>
                                            </td>
                                            <td><?= $ls_jns['stas']; ?></td>
                                        </tr>
                                        <script>
                                        var rupiah<?= $idOk ?> = document.getElementById(
                                            'psse<?= $idOk ?>');

                                        rupiah<?= $idOk ?>.addEventListener('keyup', function(e) {
                                            rupiah<?= $idOk ?>.value = formatRupiah(this.value);
                                        });
                                        </script>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr style="color: white; background-color: green; font-weight: bold;">
                                            <th colspan="4">Total</th>
                                            <th><?= rupiah($dt['brg_asal']) ?></th>
                                            <th><?= rupiah($dt['brg']) ?></th>
                                            <th><?= rupiah($dt['brg_serap']) ?></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>

                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label">Jumlah akan
                                            dicairkan</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="total" class="form-control" id=""
                                                placeholder="Rp. 0" readonly>
                                            <input type=hidden name=hiddentotal value=0>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-3 control-label">Tanggal
                                            Pencairan</label>
                                        <div class="col-sm-9">
                                            <input type="date" class="form-control" id="" name="tgl_cair"
                                                <?= $sts_tmbl; ?> required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-3 control-label">Penerima</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="" name="penerima"
                                                <?= $sts_tmbl; ?> required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-3 control-label">Pencair</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="kasir" class="form-control"
                                                value="<?= $nama_user ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" name="cairkan" class="btn btn-success pull-right"
                                                <?= $sts_tmbl; ?>><i class="fa fa-save"></i> Simpan pencairan</button>
                                            <button type="button" name="all" data-toggle="modal"
                                                data-target="#staticBackdrop" class="btn btn-warning pull-right"
                                                <?= $sts_tmbl; ?>><i class="fa fa-bookmark"></i> Cairkan semuanya
                                                langsung</button>
                                        </div>
                                    </div>
                                </div><!-- /.box-body -->
                            </form>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
            <div class="col-md-3">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">History pencairan</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="example1_bst" class="table table-bordered table-striped">
                                <thead>
                                    <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                        <th>No</th>
                                        <th>Tgl Cair</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $rls = mysqli_query($conn, "SELECT * FROM pencairan WHERE kode_pengajuan = '$kode' AND tahun = '$tahun_ajaran' ");
                                    while ($ls_jns = mysqli_fetch_assoc($rls)) { ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= date('d M Y', strtotime($ls_jns['tgl_cair'])) ?></td>
                                        <td><a target="_blank"
                                                href="<?= 'nota_pencairan.php?id=' . $ls_jns['id_cair'] ?>"><button
                                                    class="btn btn-sm btn-success"><i class="fa fa-print"></i>
                                                    Cetak</button></a></td>
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

<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class=" modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cairkan semuanya</h4>
            </div>
            <form action="" method="post" class="form-horizontal">
                <div class="modal-body">

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">Nominal pencairan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="periode" disabled
                                value="<?= rupiah($dt['jml']); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-3 control-label">Tanggal Pencairan</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" id="" name="tgl_cair" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-3 control-label">Penerima</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="" name="penerima" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-3 control-label">Pencair</label>
                        <div class="col-sm-9">
                            <input type="text" name="kasir" class="form-control" value="<?= $nama_user ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">لا تفعل</button>
                    <button type="submit" name="all" class="btn btn-success">نعم. الآن</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php
require 'bawah.php'
?>

<script type="text/javascript">
function checkAll(box) {
    let checkboxes = document.getElementsByTagName('input');

    if (box.checked) { // jika checkbox teratar dipilih maka semua tag input juga dipilih
        for (let i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox') {
                checkboxes[i].checked = true;
            }
        }
    } else { // jika checkbox teratas tidak dipilih maka semua tag input juga tidak dipilih
        for (let i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].type == 'checkbox') {
                checkboxes[i].checked = false;
            }
        }
    }
}
</script>

<script language="JavaScript">
function checkChoice(whichbox) {
    with(whichbox.form) {
        if (whichbox.checked == false)
            hiddentotal.value = eval(hiddentotal.value) - eval(whichbox.value);
        else
            hiddentotal.value = eval(hiddentotal.value) + eval(whichbox.value);
        return (formatCurrency(hiddentotal.value));
    }
}

function formatCurrency(num) {
    num = num.toString().replace(/\$|\,/g, '');
    if (isNaN(num)) num = "0";
    cents = Math.floor((num * 100 + 0.5) % 100);
    num = Math.floor((num * 100 + 0.5) / 100).toString();
    if (cents < 10) cents = "0" + cents;
    for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
        num = num.substring(0, num.length - (4 * i + 3)) + '.' + num.substring(num.length - (4 * i + 3));
    return ("Rp. " + num);
}

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
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}
</script>


<?php
if (isset($_POST['cairkan'])) {

    $id = $uuid;
    $id_pnj = $_POST['id_pnj'];
    $kd_pnj = $_POST['kode_pengajuan'];
    $lembaga = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['lembaga']));
    $penerima = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['penerima']));
    $nominal = htmlspecialchars(mysqli_real_escape_string($conn, preg_replace("/[^0-9]/", "", $_POST['nominal'])));
    $nominal_cair = htmlspecialchars(mysqli_real_escape_string($conn, preg_replace("/[^0-9]/", "", $_POST['hiddentotal'])));
    $tgl_cair = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tgl_cair']));
    $kasir = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['kasir']));

    $jumlah_dipilih = count($id_pnj);

    $sql = mysqli_query($conn, "INSERT INTO pencairan VALUES ('$id', '$kd_pnj', '$lembaga', '$nominal', '$nominal_cair', '$tgl_cair','$kasir', '$penerima', '$tahun_ajaran')");
    $pnj = mysqli_query($conn, "UPDATE pengajuan SET cair = 1 WHERE kode_pengajuan = '$kd_pnj' AND tahun = '$tahun_ajaran' ");

    for ($x = 0; $x < $jumlah_dipilih; $x++) {
        $add = mysqli_query($conn, "INSERT INTO realis SELECT * FROM real_sm WHERE id_realis = '$id_pnj[$x]' AND tahun = '$tahun_ajaran' ");
        $del = mysqli_query($conn, "DELETE FROM real_sm WHERE id_realis = '$id_pnj[$x]' AND tahun = '$tahun_ajaran' ");
    }

    if ($nominal_cair < $nominal) {
        $sisa = $nominal - $nominal_cair;
        $tgl_setor = date('Y-m-d');

        mysqli_query($conn, "INSERT INTO real_sisa VALUES ('$id', '$kd_pnj', '$nominal_cair', '$nominal', '$sisa', '$tgl_setor', '$tahun_ajaran') ");
    }

    $psn = '
*INFORMASI PENCAIRAN PENGAJUAN*

Pencairan pengajuan dari :
    
Lembaga : ' . $data['nama'] . '
Kode Pengajuan : ' . $kd_pnj . '
Pada : ' . $tgl_cair . '
Nomnal : ' . rupiah($nominal_cair) . '
Penerima : ' . $penerima . '

*_telah dicairkan oleh Bendahara Bag. Admin Pencairan._*
Terimakasih';

    if ($sql && $pnj && $add && $del) { ?>
<script>
Swal.fire({
    position: 'top-end',
    icon: 'success',
    title: 'Dana Berhasil dicairkan',
    showConfirmButton: false
});
var millisecondsToWait = 1000;
setTimeout(function() {
    document.location.href = "<?= 'cair.php' ?>"
}, millisecondsToWait);
</script>

<?php

        kirim_group($api_key, 'DfBeAZ3zGcR5qvLmBdKJaZ', $psn);
        kirim_group($api_key, 'FbXW8kqR5ik6w6iCB49GZK', $psn);
    }
}

if (isset($_POST['all'])) {

    $id = $uuid;

    $kd_pnj = $data['kode_pengajuan'];
    $lembaga =  $data['lembaga'];
    $nominal = $dt2['jml'];
    $nominal_cair = $dt['jml'];
    $tgl_cair = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tgl_cair']));
    $kasir = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['kasir']));
    $penerima = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['penerima']));


    $sql = mysqli_query($conn, "INSERT INTO pencairan VALUES ('$id', '$kd_pnj','$lembaga','$nominal','$nominal_cair', '$tgl_cair','$kasir', '$penerima', '$tahun_ajaran')");
    $pnj = mysqli_query($conn, "UPDATE pengajuan SET cair = 1 WHERE kode_pengajuan = '$kd_pnj' AND tahun = '$tahun_ajaran' ");

    while ($x = mysqli_fetch_assoc($plih)) {
        $id_pnj = $x['id_realis'];
        $add = mysqli_query($conn, "INSERT INTO realis SELECT * FROM real_sm WHERE id_realis = '$id_pnj' AND tahun = '$tahun_ajaran' ");
        $del = mysqli_query($conn, "DELETE FROM real_sm WHERE id_realis = '$id_pnj' AND tahun = '$tahun_ajaran' ");
    }

    if ($nominal_cair < $nominal) {
        $sisa = $nominal - $nominal_cair;
        $tgl_setor = date('Y-m-d');

        mysqli_query($conn, "INSERT INTO real_sisa VALUES ('$id', '$kd_pnj', '$nominal_cair', '$nominal', '$sisa', '$tgl_setor', '$tahun_ajaran' ) ");
    }

    $psn = '
    *INFORMASI PENCAIRAN PENGAJUAN*

    Pencairan pengajuan dari :

    Lembaga : ' . $data['nama'] . '
    Kode Pengajuan : ' . $kd_pnj . '
    Pada : ' . $tgl_cair . '
    Nomnal : ' . rupiah($nominal_cair) . '
    Penerima : ' . $penerima . '

    *_telah dicairkan oleh Bendahara Bag. Admin Pencairan._*

    *NB : Jika dana sudah terealisasikan, diharapkan segera membuat SPJ serta melakukan pengembalian dana sisa realisasi* 
    Terimakasih';

    if ($sql && $pnj && $add && $del) { ?>
<script>
Swal.fire({
    position: 'top-end',
    icon: 'success',
    title: 'Dana Berhasil dicairkan',
    showConfirmButton: false
});
var millisecondsToWait = 1000;
setTimeout(function() {
    document.location.href = "<?= 'cair.php' ?>"
}, millisecondsToWait);
</script>

<?php

        kirim_group($api_key, 'DfBeAZ3zGcR5qvLmBdKJaZ', $psn);
        kirim_group($api_key, 'FbXW8kqR5ik6w6iCB49GZK', $psn);
    }
}

if (isset($_POST['sbmpt'])) {
    $id = $_POST['id'];
    $serap = preg_replace("/[^0-9]/", "", $_POST['serap']);

    $sql = mysqli_query($conn, "UPDATE $tbl_slct SET nom_serap = '$serap' WHERE id_realis = '$id' ");
    if ($sql) {
        echo "
        <script>
            window.location = 'cair_proses.php?kode=" . $kode . "';
        </script>
        ";
    }
}
?>