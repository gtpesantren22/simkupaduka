<?php
include 'atas.php';

$kode_p = $_GET['kode'];
$kl = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pengajuan WHERE kode_pengajuan = '$kode_p' "));
$vr = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM verifikasi WHERE kode_pengajuan = '$kode_p' "));

$kode = $kl['lembaga'];
$l = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$kode' "));

if (preg_match("/DISP./i", $kode_p)) {
    $rt = '*(DISPOSISI)*';
} else {
    $rt = '';
}
?>

<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Pengajuan Realisasi</h5>
                        <p class="m-b-0">Daftar pengajuan realiasai dari beberapa lembaga</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="index.php"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Pengajuan</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Page-header end -->
    <div class="pcoded-inner-content">
        <!-- Main-body start -->
        <div class="main-body">
            <div class="page-wrapper">
                <!-- Page-body start -->
                <div class="page-body">

                    <!-- Hover table card start -->
                    <div class="card">
                        <div class="card-header">
                            <h5>Detail Pengajuan <?= $l['nama'] ?></h5>
                            <div class="card-header-right">
                                <ul class="list-unstyled card-option">
                                    <li><i class="fa fa fa-wrench open-card-option"></i></li>
                                    <li><i class="fa fa-window-maximize full-card"></i></li>
                                    <li><i class="fa fa-minus minimize-card"></i></li>
                                    <li><i class="fa fa-refresh reload-card"></i></li>
                                    <li><i class="fa fa-trash close-card"></i></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-block table-border-style">
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <?php
                                            $trb = mysqli_fetch_assoc(mysqli_query($conn, "SELECT 
                                        SUM(IF( jenis = 'A', total, 0)) AS A, 
                                        SUM(IF( jenis = 'B', total, 0)) AS B, 
                                        SUM(IF( jenis = 'C', total, 0)) AS C, 
                                        SUM(IF( jenis = 'D', total, 0)) AS D, 
                                        SUM(total) AS T 
                                        FROM rab WHERE lembaga = '$kode' AND tahun = '2022' "));

                                            $trb_pakai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT 
                                        SUM(IF( jenis = 'A', nominal, 0)) AS A, 
                                        SUM(IF( jenis = 'B', nominal, 0)) AS B, 
                                        SUM(IF( jenis = 'C', nominal, 0)) AS C, 
                                        SUM(IF( jenis = 'D', nominal, 0)) AS D, 
                                        SUM(nominal) AS T 
                                        FROM realis WHERE lembaga = '$kode' AND tahun = '2022' "));

                                            $pengajuan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT 
                                        SUM(IF( jenis = 'A', nominal, 0)) AS A, 
                                        SUM(IF( jenis = 'B', nominal, 0)) AS B, 
                                        SUM(IF( jenis = 'C', nominal, 0)) AS C, 
                                        SUM(IF( jenis = 'D', nominal, 0)) AS D, 
                                        SUM(nominal) AS T 
                                        FROM real_sm WHERE lembaga = '$kode' AND tahun = '2022' "));

                                            ?>
                                            <tr style="color: white; background-color: darkorange; font-weight: bold;">
                                                <th>#</th>
                                                <th>Sisa RAB</th>
                                                <th>Dana Pengajuan</th>
                                                <th>Ket</th>
                                            </tr>
                                            <tr>
                                                <th>NOMINAL</th>
                                                <th><?= rupiah($trb['T'] - $trb_pakai['T']) ?></th>
                                                <th><?= rupiah($pengajuan['T']) ?></th>
                                                <th>
                                                    <?php if (($trb['T'] - $trb_pakai['T']) >= $pengajuan['T']) { ?>
                                                        <span class="badge badge-success"><i class="fa fa-check"></i> RAB Mencukupi</span>
                                                    <?php } else { ?>
                                                        <span class="badge badge-danger"><i class="fa fa-check"></i> RAB Tidak Mencukupi</span>
                                                    <?php } ?>
                                                </th>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="alert alert-primary background-primary">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <i class="icofont icofont-close-line-circled text-white"></i>
                                        </button>
                                        <strong><i class="fa fa-check"></i> Diverifikasi</strong><br> pada <code> <?= $vr['tgl_verval'] ?></code>
                                        oleh <code> <?= $vr['user'] ?></code><br><br>
                                        <button class="btn btn-success btn-round btn-out btn-sm" data-toggle="modal" data-target="#large-Modal"><i class="fa fa-check"></i> Setujui</button>
                                        <button class="btn btn-danger btn-round btn-out btn-sm"><i class="fa fa-times"></i> Tolak pengajuan</button>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <center>
                                <h5>Rincian Pengajuan</h5>
                            </center>
                            <br>
                            <div class="table-responsive">
                                <table id="" class="table table-striped table-bordered table-sm" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode RAB</th>
                                            <th>Periode</th>
                                            <th>PJ</th>
                                            <th>Nominal</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $dt_bos = mysqli_query($conn, "SELECT * FROM real_sm WHERE kode_pengajuan = '$kode_p' ");
                                        $tt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nom_cair) AS tot FROM real_sm WHERE kode_pengajuan = '$kode_p' "));
                                        while ($a = mysqli_fetch_assoc($dt_bos)) { ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $a['kode'] ?></td>
                                                <td><?= $bulan[$a['bulan']] . ' ' . $a['tahun'] ?></td>
                                                <td><?= $a['pj'] ?></td>
                                                <td><?= rupiah($a['nominal']) ?></td>
                                                <td><?= $a['ket'] ?></td>
                                                <!-- <td>
                                            <a onclick="return confirm('Yakin akan dihapus ?. Menghapus data ini akan menghapus data realisasi juga')" href="<?= 'hapus.php?kd=rab&id=' . $a['id_realis']; ?>"><span class="fa fa-trash-o text-danger"> Hapus</span></a>
                                        </td> -->
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr style="color: white; background-color: darkorange; font-weight: bold;">
                                            <th colspan="4">SUB JUMLAH</th>
                                            <th colspan="2"><?= rupiah($tt['tot']) ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Hover table card end -->

                </div>
                <!-- Page-body end -->
            </div>
        </div>
        <!-- Main-body end -->

        <div id="styleSelector">

        </div>
    </div>
</div>

<div class="modal fade" id="large-Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Halaman Persetujuan Pengajuan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-material" action="" method="post">
                <div class="modal-body">
                    <div class="card-block">
                        <div class="form-group form-default form-static-label">
                            <input type="text" name="footer-email" class="form-control" placeholder="Enter User Name" required="" readonly value="<?= $l['nama'] ?>">
                            <span class="form-bar"></span>
                            <label class="float-label">Nama Lembaga</label>
                        </div>
                        <div class="form-group form-default form-static-label">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="date" name="tgl" class="form-control" placeholder="Enter Email" required="">
                                    <span class="form-bar"></span>
                                    <label class="float-label">Tanggal Pengesahan</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="time" name="jam" class="form-control" placeholder="Enter Email" required="">
                                    <span class="form-bar"></span>
                                    <label class="float-label"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group form-default form-static-label">
                            <input type="text" name="user" class="form-control" placeholder="Enter User Name" required="" readonly value="<?= $nama_user ?>">
                            <span class="form-bar"></span>
                            <label class="float-label">Yang menyetujui</label>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                    <button type="submit" name="veris" class="btn btn-primary waves-effect waves-light "><i class="fa fa-check"></i> Setujui</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php
include 'bawah.php';
?>
<?php
if (isset($_POST['veris'])) {

    $id = $uuid;
    $lembaga = $l['kode'];
    $id_pn = $kl['id_pn'];
    $kd_pj = $kode_p;
    $tgl = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tgl'] . ' ' . $_POST['jam']));
    $user = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['user']));
    $stts = 1;
    $ket = '-';

    $psn = '
*INFORMASI PERMOHONAN PENCAIRAN* ' . $rt . '

pengajuan dari :

Lembaga : ' . $l['nama'] . '
Kode Pengajuan : ' . $kd_pj . '
Nominal : ' . rupiah($tt['tot']) . '
telah SETUJUI oleh *' . $user . '* pada *' . $tgl . '*

*_dimohon kepada KASiR untuk segera melakukan pencairan_*
Terimakasih';

    $psn2 = '
*INFORMASI PERSETUJAN* ' . $rt . '

pengajuan dari :

Lembaga : ' . $l['nama'] . '
Kode Pengajuan : ' . $kd_pj . '
Nominal : ' . rupiah($tt['tot']) . '
telah SETUJUI oleh *' . $user . '* pada *' . $tgl . '*

*_dimohon kepada KPA dari Lembaga terkait untuk melakukan pencairan kepada pelaksana bendahara_*
Terimakasih';


    $sql = mysqli_query($conn, "INSERT INTO approv VALUES ('$id', '$kd_pj', '$lembaga','$tgl','$user', '$stts','$ket')");
    $sql2 = mysqli_query($conn, "UPDATE pengajuan SET apr = $stts WHERE id_pn = '$id_pn' ");
    if ($sql  && $sql2) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Pengajuan sudah disetujui',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "<?= 'pengajuan.php' ?>"
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
                CURLOPT_POSTFIELDS => 'apiKey=fb209be1f23625e43cbf285e57c0c0f2&phone=' . $l['hp'] . '&message=' . $psn2,
            )
        );
        $response = curl_exec($curl);
        curl_close($curl);
    } else {
        echo "DATA TAK MAU MASUK";
    }
}
?>