<?php
include  'konversi.php';
include 'fungsi.php';
$bln = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "July", "Agustus", "September", "Oktober", "November", "Desember");
$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pencairan WHERE id_cair =  '$id' AND tahun = '$tahun_ajaran' "));

$kd_lm = $data['lembaga'];
$lm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$kd_lm' AND tahun = '$tahun_ajaran' "));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<link rel="stylesheet" href="bootstrap-5.1.3/dist/css/bootstrap.min.css">
<script src="bootstrap-5.1.3/dist/js/bootstrap.min.js"></script>

<body>
    <div class="container-fluid">
        <br><br>
        <center>
            <h5><b><u>BERITA ACARA SERAH TERIMA PENCAIRAN REALISASI BELANJA</u></b></h5>
            <p>NOMOR : __________________________________________________</p>
        </center>

        <div class="row">
            <div class="col-md-12">

                <p style="text-indent: 3em;">
                    Pada hari ini <?= hariIndo(date('l')) ?> tanggal <?= terbilang_tgl(date('d')) ?> bulan <?= $bln[date('m')] ?> tahun <?= terbilang_tgl(date('Y')) ?> bertempat di Kantor Keuangan Pondok Pesantren Darul Lughah Wal Karomah, kami yang bertanda tangan di bawah ini :
                </p>
            </div>
            <div class="col-md-12">
                <table width="50%">
                    <tr>
                        <td>1. </td>
                        <td>Nama</td>
                        <td>:<b> <?= $data['kasir'] ?></b></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td>Jabatan</td>
                        <td>:<b> Kasir/Teller</b></td>
                    </tr>
                </table>
                <p>
                    Dalam hal ini bertindak untuk dan atas nama Biro Keuangan Pondok Pesantren Darul Lughah Wal Karomah, yang selanjutnya disebut sebagai PIHAK KESATU.
                </p>
            </div>
            <div class="col-md-12">
                <table width="100%">
                    <tr>
                        <td>2. </td>
                        <td>Nama</td>
                        <td>:<b> ....................................................</b></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td>Jabatan</td>
                        <td>:<b> Bendahara / KPA <?= $lm['nama'] ?> Darul Lughah Wal Karomah</b></td>
                    </tr>
                </table>
                <p>
                    Dalam hal ini bertindak untuk dan atas nama <b><?= $lm['nama'] ?> Darul Lughah Wal Karomah</b> Kraksaan yang selanjutnya disebut sebagai PIHAK KEDUA.
                </p>
                <p style="text-indent: 3em;">
                    PIHAK KESATU telah menyerahkan uang kepada PIHAK KEDUA sebesar <b><?= rupiah($data['nominal_cair']) ?> (<?= terbilang($data['nominal_cair']) ?>)</b> untuk Realisasi Belanja Kegiatan berdasarkan hasil Verifikasi dan Validasi Kepala Pesantren dan Biro Keuangan. demikian pula halnya PIHAK KEDUA menerima penyerahan tersebut dari PIHAK KESATU.
                    Selanjutnya dalam rangka pelaksanaan prinsip transparansi dan akuntabilitas, PIHAK KEDUA wajib menyampaikan laporan pertanggung jawaban penggunaan uang tersebut kepada PIHAK KESATU paling lambat 10 (sepuluh) hari setelah kegiatan selesai.
                </p>
                <p>Demikian Berita Acara ini dibuat untuk dapat dipergunakan sebagaimana mestinya. </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-borderless">
                    <tr>
                        <td style="width: 30%;"></td>
                        <td style="width: 20%;">&nbsp;</td>
                        <td style="width: 30%;">
                            Probolinggo, <?= date('d') . ' ' . date('m') . ' ' . date('Y') ?><br>
                        </td>
                    </tr>
                    <tr>
                        <th>PIHAK KEDUA</th>
                        <th>&nbsp;</th>
                        <th>PIHAK PERTAMA</th>
                    </tr>
                    <tr>
                        <th colspan="3"></th>
                    </tr>
                    <tr>
                        <th colspan="3"></th>
                    </tr>
                    <tr>
                        <th colspan="3"></th>
                    </tr>
                    <tr>
                        <th colspan="3"></th>
                    </tr>
                    <tr>
                        <th>_______________________</th>
                        <th>&nbsp;</th>
                        <th><u><?= $data['kasir'] ?></u></th>
                    </tr>
                </table>
            </div>
        </div>
        <br><br><br><br><br><br><br><br><br><br>
        <div class="row">
            <div class="col-md-12">
                <div class="card border-dark">
                    <h5 class="card-header text-center border-dark">KUITANSI / BUKTI PEMBAYARAN</h5>
                    <div class="card-body border-dark">
                        <table class="table table-borderless">
                            <tr>
                                <td style="width: 30%;"></td>
                                <td style="width: 20%;">&nbsp;</td>
                                <td style="width: 30%;">
                                    Tahun Anggaran : _____ <br>
                                    No. Bukti : ______
                                </td>
                            </tr>
                        </table>
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td>Sudah terima dari</td>
                                <td>: <?= $data['kasir'] ?> (Kasir/Teller Biro Keuangan)</td>
                            </tr>
                            <tr>
                                <td>Lembaga</td>
                                <td>: Biro Keuangan Pondok Pesantren Darul Lughah Wal Karomah</td>
                            </tr>
                            <tr>
                                <td>Jumlah Uang</td>
                                <td>: <?= rupiah($data['nominal_cair']) ?></td>
                            </tr>
                            <tr>
                                <td>Terbilang</td>
                                <td>: <i><?= terbilang($data['nominal_cair']) ?></i></td>
                            </tr>
                            <tr>
                                <td>Untuk Pembayaran</td>
                                <td>: Pengajuan Realiasasi Belanja Bulan ______________</td>
                            </tr>
                            <tr>
                                <td>Kode Pengajuan</td>
                                <td>: <?= $data['kode_pengajuan'] ?></td>
                            </tr>
                        </table>
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td style="width: 30%;"></td>
                                <td style="width: 20%;">&nbsp;</td>
                                <td style="width: 30%;">
                                    Probolinggo, <?= date('d') . ' ' . $bln[date('m')] . ' ' . date('Y') ?><br>
                                </td>
                            </tr>
                            <tr>
                                <th>Kasir/Teller</th>
                                <th>&nbsp;</th>
                                <th>Penerima Uang</th>
                            </tr>
                            <tr>
                                <th colspan="3"></th>
                            </tr>
                            <tr>
                                <th colspan="3"></th>
                            </tr>
                            <tr>
                                <th colspan="3"></th>
                            </tr>
                            <tr>
                                <th><u><?= $data['kasir'] ?></u></th>
                                <th>&nbsp;</th>
                                <th>_______________________</th>
                            </tr>
                        </table>
                    </div>
                    <div class="card-footer bg-transparent border-dark">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td style="width: 30%;">Setuju dikeluarkan</td>
                                <td style="width: 20%;">&nbsp;</td>
                                <td style="width: 30%;">
                                    Lunas dibayar tgl <?= date('d M Y') ?><br>
                                </td>
                            </tr>
                            <tr>
                                <th>Kepala Pesantren,</th>
                                <th>&nbsp;</th>
                                <th>Biro Keuangan,</th>
                            </tr>
                            <tr>
                                <th colspan="3"></th>
                            </tr>
                            <tr>
                                <th colspan="3"></th>
                            </tr>
                            <tr>
                                <th colspan="3"></th>
                            </tr>
                            <tr>
                                <th colspan="3"></th>
                            </tr>
                            <tr>
                                <th><u>K. Moh. Zaini bin Ali Wafa, S.HI</u></th>
                                <th>&nbsp;</th>
                                <th><u>Ny. Zahrotul Muawanah</u></th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    window.print();
</script>

</html>