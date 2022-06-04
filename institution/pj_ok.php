<?php
include 'fungsi.php';
include 'bawah.php';

$kd = $_GET['kd'];
$dt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pengajuan WHERE kode_pengajuan = '$kd'"));
$l = $dt['lembaga'];
$lm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$l' "));

$sql = mysqli_query($conn, "UPDATE pengajuan SET stts = 'yes' WHERE kode_pengajuan = '$kd' ");

$jml =  mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jm FROM real_sm WHERE kode_pengajuan = '$kd' GROUP BY kode_pengajuan "));
$perod = $bulan[$dt['bulan']] . ' ' . $dt['tahun'];
// $ww = date('d-M-Y H:i:s');

$psn = '
*INFORMASI PERMOHONAN VARIFIKASI*

Ada pengajuan baru dari :
    
Lembaga : ' . $lm['nama'] . '
Kode Pengjuan : ' . $kd . '
Periode : ' . $perod . '
Pada : ' . $dt['at'] . '
Nomnal : ' . rupiah($jml['jm']) . '

*_dimohon kepada TIM ACCOUNTING untuk segera mengecek nya_*
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
            CURLOPT_POSTFIELDS => 'apiKey=fb209be1f23625e43cbf285e57c0c0f2&phone=082302301003&message=' . $pesan,
        )
    );
    $response = curl_exec($curl);
    curl_close($curl);
}
