<?php
$bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
?>
<!DOCTYPE html>
<html>

<head>
    <title>Surat</title>
    <style type="text/css">
        /* CSS untuk layout halaman surat */
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            padding-bottom: 5pt;
            text-decoration: underline;
            /* border-bottom: 1px solid black; */
        }

        .content {
            padding: 10pt 0;
        }

        .footer {
            text-align: right;
            padding-top: 10pt;
            border-top: 1px solid black;
        }
    </style>
    <style type="text/css" media="print">
        /* CSS untuk mencetak surat */
        body {
            margin: 1cm;
        }
    </style>
</head>

<body>

    <div class="header">
        <h3>SURAT PERJANJIAN</h3>
    </div>
    <div class="content">
        <p>Yang bertanda tangan dibawah ini wali santri dari :</p>
        <table>
            <tr>
                <td>Nama Santri</td>
                <td>:</td>
                <td><?= $santri->nama ?></td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td><?= $santri->k_formal . ' ' . $santri->t_formal ?></td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td><?= $santri->desa . ' - ' . $santri->kec . ' - ' . $santri->kab ?></td>
            </tr>
            <tr>
                <td>Tetala</td>
                <td>:</td>
                <td><?= $santri->tempat . ', ' . tanggalIndo($santri->tanggal) ?></td>
            </tr>
            <tr>
                <td>No HP WA Aktif</td>
                <td>:</td>
                <td><?= $santri->hp ?></td>
            </tr>
        </table>
        <p>Menyatakan dengan sebenar benarnya, memiliki tanggungan biaya pendidikan di Pondok Pesantren Darul Lughah Wal Karomah dengan rincian sebagai berikut :</p>
        <table>
            <tr>
                <td>a)</td>
                <td>Biaya Pendidikan s/d April 2023</td>
                <td>:</td>
                <td><u><?= rupiah($dispn->bp) ?></u></td>
            </tr>
            <tr>
                <td>b)</td>
                <td>Sandal Wakaf</td>
                <td>:</td>
                <td><u><?= rupiah($dispn->sandal) ?></u></td>
            </tr>
            <tr>
                <td>c)</td>
                <td>Tindakan Lomba Haflah</td>
                <td>:</td>
                <td><u><?= rupiah($dispn->lomba) ?></u></td>
            </tr>
            <tr>
                <td>d)</td>
                <td>Tindakan Wilayah</td>
                <td>:</td>
                <td><u><?= rupiah($dispn->wilayah) ?></u></td>
            </tr>
            <tr>
                <td></td>
                <td><b>JUMLAH TANGGUNGAN</b></td>
                <td><b>:</b></td>
                <td><u><b><?= rupiah($bp) ?></b></u></td>
            </tr>
            <tr>
                <td></td>
                <td><b>DIBAYARKAN</b></td>
                <td><b>:</b></td>
                <td><u><b><?= rupiah($bayar) ?></b></u></td>
            </tr>
            <tr>
                <td></td>
                <td><b>SISA TANGGUNGAN</b></td>
                <td><b>:</b></td>
                <td><u><b><?= rupiah($bp - $bayar) ?></b></u></td>
            </tr>
        </table>
        <p>
            Adapun sisa tanggungan kami tersebut diatas, Saya berjanji akan melunasi tanggungan tersebut pada <b>Bulan <?= $bulan[$janji] ?></b> dengan tambahan BP Bulan Berjalan.
        </p>
        <p>
            Saya bersedia dipanggil oleh Bendahara pesantren jika melebihi batas waktu yang saya janjikan.
            Demikian surat Perjanjian ini saya buat , tanpa ada paksaan dan atas dasar keikhlasan dan rasa tanggung jawab.
        </p>
        <p>
            Demikian surat Perjanjian ini saya buat , tanpa ada paksaan dan atas dasar keikhlasan dan rasa tanggung jawab.
        </p>
        <br>
        <table border="0">
            <tr>
                <td>Saksi</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>Kraksaan, <?= date('d/m/Y') ?></td>
            </tr>
            <tr>
                <td>Bendahara Pesantren</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>Yang Membuat Perjanjian</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>(_________________________)</td>
                <td></td>
                <td>(_________________________)</td>
            </tr>
        </table>
    </div>
</body>

<script>
    window.print();
</script>

</html>