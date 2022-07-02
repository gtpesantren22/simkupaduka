<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="upload_ex2.php" method="post" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <button type="submit" name="upload">Upload</button>
    </form>
    <table border="1">
        <thead>
            <th>NO</th>
            <th>CUSTOMER ID</th>
            <th>CUSTOMER NAME</th>
            <th>CUSTOMER GROUP ID</th>
            <th>CUSTOMER GROUP NAME</th>
            <th>BILLTYPE ID</th>
            <th>BILLTYPE</th>
            <th>AMOUNT</th>
            <th>DISCOUNT</th>
            <th>DATE</th>
            <th>REMARKS</th>
        </thead>
        <tbody>
            <?php

            include 'koneksi.php';
            $no = 1;
            $dt = mysqli_query($conn, "SELECT * FROM cost");
            while ($r = mysqli_fetch_assoc($dt)) {

                $stas = $r['stas'];
                if ($stas === 'yatim') {
                    $pot = 40;
                } elseif ($stas === 'yatim-piatu') {
                    $pot = 100;
                } elseif ($stas === 'fakir') {
                    $pot = 30;
                } elseif ($stas === 'pegawai') {
                    $pot = 40;
                } elseif ($stas === 'pengasuh') {
                    $pot = 100;
                } elseif ($stas === 'saudara') {
                    $pot = 15;
                } elseif ($stas === 'normal') {
                    $pot = 0;
                }

                $nom_awal = $r['tgn'] * ($pot / 100);

                if ($r['dekos'] == 0) {
                    $tnn = $nom_awal / 12;
                    $nom_kos = 0;
                } elseif ($r['dekos'] > 0) {
                    $tnn = ($nom_awal + $r['dekos']) / 12;
                    $nom_kos = 300000;
                }

                for ($i = 1; $i <= 12; $i++) {
                    $bbl = strlen($i) < 2 ? '0' . $i : $i;
                    $tgl = '2022-' . $i . '-12';
                    $tagih = '2022' . $bbl . '15';
                    if ($i == 4 || $i == 5) {
                        $tnnOk = $tnn - $nom_kos;
                    } else {
                        $tnnOk = $tnn;
                    }
            ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $r['cost_id']; ?></td>
                        <td><?= $r['cost_name']; ?></td>
                        <td><?= $r['group_id']; ?></td>
                        <td><?= $r['group_name']; ?></td>
                        <td><?= $r['bill_id']++; ?></td>
                        <td><?= $r['bill_name']; ?> [<?= date('M', strtotime($tgl)); ?>]</td>
                        <td><?= floor($tnnOk); ?></td>
                        <td>0</td>
                        <td><?= $tagih; ?></td>
                        <td>0</td>
                    </tr>
            <?php
                }
            } ?>
        </tbody>
    </table>
</body>

</html>