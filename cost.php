<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
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
                $nom_kur = $r['nominal'] < 3000000 ? 0 : 3000000;
                $nom_add = $r['nominal'] < 3000000 ? 0 : 300000;

                for ($i = 1; $i <= 6; $i++) {
                    $tnn = ($r['nominal'] - $nom_kur) / 12;
                    $bbl = strlen($i) < 2 ? '0' . $i : $i;
                    $tgl = '2022-' . $i . '-12';
                    $tagih = '2022' . $bbl . '15';
                    if ($i <= 4) {
                        $tnnOk = $tnn + $nom_add;
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
                        <td><?= rupiah($tnnOk); ?></td>
                        <td>0</td>
                        <td><?= $tagih; ?></td>
                        <td>0</td>
                    </tr>
                <?php
                }

                for ($i = 7; $i <= 12; $i++) {
                    $tnn = ($r['nominal'] - $nom_kur) / 12;
                    $tnnOk = $tnn + $nom_add;
                    $bbl = strlen($i) < 2 ? '0' . $i : $i;

                    $tgl = '2022-' . $i . '-12';
                    $tagih = '2022' . $bbl . '15';

                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $r['cost_id']; ?></td>
                        <td><?= $r['cost_name']; ?></td>
                        <td><?= $r['group_id']; ?></td>
                        <td><?= $r['group_name']; ?></td>
                        <td><?= $r['bill_id']++; ?></td>
                        <td><?= $r['bill_name']; ?> [<?= date('M', strtotime($tgl)); ?>]</td>
                        <td><?= rupiah($tnnOk); ?></td>
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