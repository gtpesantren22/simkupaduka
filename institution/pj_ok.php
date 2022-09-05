<?php
include 'fungsi.php';
include 'bawah.php';

$kd = $_GET['kd'];
$dt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pengajuan WHERE kode_pengajuan = '$kd' AND tahun = '$tahun_ajaran'"));
$l = $dt['lembaga'];
$lm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$l' AND tahun = '$tahun_ajaran' "));

$sql = mysqli_query($conn, "UPDATE pengajuan SET stts = 'yes' WHERE kode_pengajuan = '$kd' AND tahun = '$tahun_ajaran' ");

$jml =  mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jm FROM real_sm WHERE kode_pengajuan = '$kd' GROUP BY kode_pengajuan AND tahun = '$tahun_ajaran' "));
$perod = $bulan[$dt['bulan']] . ' ' . $dt['tahun'];
// $ww = date('d-M-Y H:i:s');

if (preg_match("/DISP./i", $kd)) {
    $rt = '*(DISPOSISI)*';
} else {
    $rt = '';
}

$psn = '
*INFORMASI PERMOHONAN VERIFIKASI* ' . $rt . '

Ada pengajuan baru dari :
    
Lembaga : ' . $lm['nama'] . '
Kode Pengajuan : ' . $kd . '
Periode : ' . $perod . '
Pada : ' . $dt['at'] . '
Nomnal : ' . rupiah($jml['jm']) . '

*_dimohon kepada TIM ACCOUNTING untuk segera mengecek nya di https://simkupaduka.ppdwk.com/_*
Terimakasih';

if ($sql) { ?>
    <script>
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'Pengajuan sudah dilanjutkan ke accounting',
            showConfirmButton: false
        });
        var millisecondsToWait = 1000;
        setTimeout(function() {
            document.location.href = "pengajuan.php"
        }, millisecondsToWait);
    </script>

<?php

    kirim_group($api_key, 'DfBeAZ3zGcR5qvLmBdKJaZ', $psn);
    kirim_group($api_key, 'FbXW8kqR5ik6w6iCB49GZK', $psn);
    kirim_person($api_key, '082302301003', $psn);
}
