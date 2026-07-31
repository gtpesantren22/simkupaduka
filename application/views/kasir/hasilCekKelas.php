                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="example2">
                                    <thead>
                                        <tr style="background-color: purple; color: white; font-weight: bold;">
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>JKL</th>
                                            <th>Kelas</th>
                                            <th>Jul</th>
                                            <th>Ags</th>
                                            <th>Sep</th>
                                            <th>Okt</th>
                                            <th>Nov</th>
                                            <th>Des</th>
                                            <th>Jan</th>
                                            <th>Feb</th>
                                            <th>Mar</th>
                                            <th>Apr</th>
                                            <th>Mei</th>
                                            <th>Jun</th>
                                            <th>Total</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        ?>
                                        <?php foreach ($dt1 as $r) :
                                            $nis = $r->nis;
                                            $dt2 = $this->db->query("SELECT 
                                            SUM(IF( bulan = 7, nominal, 0)) as jul, 
                                            SUM(IF( bulan = 8, nominal, 0)) as ags, 
                                            SUM(IF( bulan = 9, nominal, 0)) as sep, 
                                            SUM(IF( bulan = 10, nominal, 0)) as okt, 
                                            SUM(IF( bulan = 11, nominal, 0)) as nov, 
                                            SUM(IF( bulan = 12, nominal, 0)) as des, 
                                            SUM(IF( bulan = 1, nominal, 0)) as jan, 
                                            SUM(IF( bulan = 2, nominal, 0)) as feb, 
                                            SUM(IF( bulan = 3, nominal, 0)) as mar, 
                                            SUM(IF( bulan = 4, nominal, 0)) as apr, 
                                            SUM(IF( bulan = 5, nominal, 0)) as mei, 
                                            SUM(IF( bulan = 6, nominal, 0)) as jun,
                                            SUM(nominal) as total 
                                            FROM pembayaran WHERE nis = '$nis' AND tahun = '$tahun' ")->row();
                                            $tggn = $this->db->query("SELECT 
                                            SUM(IF( bulan = 7, nominal, 0)) as jul, 
                                            SUM(IF( bulan = 8, nominal, 0)) as ags, 
                                            SUM(IF( bulan = 9, nominal, 0)) as sep, 
                                            SUM(IF( bulan = 10, nominal, 0)) as okt, 
                                            SUM(IF( bulan = 11, nominal, 0)) as nov, 
                                            SUM(IF( bulan = 12, nominal, 0)) as des, 
                                            SUM(IF( bulan = 1, nominal, 0)) as jan, 
                                            SUM(IF( bulan = 2, nominal, 0)) as feb, 
                                            SUM(IF( bulan = 3, nominal, 0)) as mar, 
                                            SUM(IF( bulan = 4, nominal, 0)) as apr, 
                                            SUM(IF( bulan = 5, nominal, 0)) as mei, 
                                            SUM(IF( bulan = 6, nominal, 0)) as jun
                                            FROM tanggungan WHERE nis = '$nis' AND tahun = '$tahun' ")->row();
                                        ?>
                                            <tr>
                                                <td><?= $i; ?></td>
                                                <td><?= $r->nama; ?></td>
                                                <td><?= $r->jkl; ?></td>
                                                <td><?= $r->k_formal; ?>-<?= $r->jurusan; ?>-<?= $r->t_formal; ?></td>

                                                <?php 
                                                $months = [
                                                    'jul' => 'jul', 'ags' => 'ags', 'sep' => 'sep', 'okt' => 'okt', 
                                                    'nov' => 'nov', 'des' => 'des', 'jan' => 'jan', 'feb' => 'feb', 
                                                    'mar' => 'mar', 'apr' => 'apr', 'mei' => 'mei', 'jun' => 'jun'
                                                ];
                                                foreach ($months as $key => $name) {
                                                    $due = $tggn ? (int)$tggn->$key : 0;
                                                    $paid = $dt2 ? (int)$dt2->$key : 0;
                                                    if ($due == 0) {
                                                        echo '<td style="background-color: #f1f1f1; color: #aaa; text-align: center;">-</td>';
                                                    } elseif ($paid >= $due) {
                                                        echo '<td style="background-color: green; color: white; font-weight: bold;">' . rupiah($paid) . '</td>';
                                                    } elseif ($paid < $due && $paid != 0) {
                                                        echo '<td style="background-color: orange; color: white; font-weight: bold;">' . rupiah($paid) . '</td>';
                                                    } else {
                                                        echo '<td style="background-color: red; color: white; font-weight: bold;">' . rupiah($paid) . '</td>';
                                                    }
                                                }
                                                ?>

                                                <td style="background-color: grey; color: white; font-weight: bold;">
                                                    <?= rupiah($dt2->total); ?></td>
                                            </tr>
                                            <?php $i++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <center>
                                <h3><span class="label label-danger">Data Dak Bayar Sama Sekali</span></h3>
                            </center>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="example">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Kelas</th>

                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        ?>
                                        <?php foreach ($dt_null as $r) : ?>
                                            <tr>
                                                <td><?= $i; ?></td>
                                                <td><?= $r->nama; ?></td>
                                                <td><?= $r->k_formal; ?>-<?= $r->jurusan; ?>-<?= $r->t_formal; ?></td>

                                            </tr>
                                            <?php $i++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <script>
                                $(document).ready(function() {
                                    $('#example').DataTable();
                                    $('#example3').DataTable();

                                    var table = $('#example2').DataTable({
                                        // lengthChange: false,
                                        buttons: ['copy', 'excel', 'pdf', 'print']
                                    });

                                    table.buttons().container()
                                        .appendTo('#example2_wrapper .col-md-6:eq(0)');

                                });
                            </script>