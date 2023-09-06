                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="example2">
                                    <thead>
                                        <tr style="background-color: purple; color: white; font-weight: bold;">
                                            <th>No</th>
                                            <th>Nama</th>
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
                                            SUM(IF( bulan = 7, nominal, '-')) as jul, 
                                            SUM(IF( bulan = 8, nominal, '-')) as ags, 
                                            SUM(IF( bulan = 9, nominal, '-')) as sep, 
                                            SUM(IF( bulan = 10, nominal, '-')) as okt, 
                                            SUM(IF( bulan = 11, nominal, '-')) as nov, 
                                            SUM(IF( bulan = 12, nominal, '-')) as des, 
                                            SUM(IF( bulan = 1, nominal, '-')) as jan, 
                                            SUM(IF( bulan = 2, nominal, '-')) as feb, 
                                            SUM(IF( bulan = 3, nominal, '-')) as mar, 
                                            SUM(IF( bulan = 4, nominal, '-')) as apr, 
                                            SUM(IF( bulan = 5, nominal, '-')) as mei, 
                                            SUM(IF( bulan = 6, nominal, '-')) as jun,
                                            SUM(nominal) as total 
                                            FROM pembayaran WHERE nis = '$nis' AND tahun = '$tahun' ")->row();
                                            $tggn = $this->db->query("SELECT * FROM tangg WHERE nis = $nis AND tahun = '$tahun' ")->row();
                                        ?>
                                            <tr>
                                                <td><?= $i; ?></td>
                                                <td><?= $r->nama; ?></td>
                                                <td><?= $r->k_formal; ?>-<?= $r->jurusan; ?>-<?= $r->t_formal; ?></td>

                                                <!-- Juli -->
                                                <?php if ($dt2->jul >= $tggn->ju_ap) { ?>
                                                    <td style="background-color: green; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->jul); ?></td>
                                                <?php } elseif ($dt2->jul < $tggn->ju_ap && $dt2->jul != 0) { ?>
                                                    <td style="background-color: orange; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->jul); ?></td>
                                                <?php } else { ?>
                                                    <td style="background-color: red; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->jul); ?></td>
                                                <?php } ?>

                                                <!-- ags -->
                                                <?php if ($dt2->ags >= $tggn->ju_ap) { ?>
                                                    <td style="background-color: green; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->ags); ?></td>
                                                <?php } elseif ($dt2->ags < $tggn->ju_ap && $dt2->ags != 0) { ?>
                                                    <td style="background-color: orange; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->ags); ?></td>
                                                <?php } else { ?>
                                                    <td style="background-color: red; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->ags); ?></td>
                                                <?php } ?>

                                                <!-- sep -->
                                                <?php if ($dt2->sep >= $tggn->ju_ap) { ?>
                                                    <td style="background-color: green; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->sep); ?></td>
                                                <?php } elseif ($dt2->sep < $tggn->ju_ap && $dt2->sep != 0) { ?>
                                                    <td style="background-color: orange; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->sep); ?></td>
                                                <?php } else { ?>
                                                    <td style="background-color: red; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->sep); ?></td>
                                                <?php } ?>

                                                <!-- okt -->
                                                <?php if ($dt2->okt >= $tggn->ju_ap) { ?>
                                                    <td style="background-color: green; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->okt); ?></td>
                                                <?php } elseif ($dt2->okt < $tggn->ju_ap && $dt2->okt != 0) { ?>
                                                    <td style="background-color: orange; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->okt); ?></td>
                                                <?php } else { ?>
                                                    <td style="background-color: red; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->okt); ?></td>
                                                <?php } ?>

                                                <!-- nov -->
                                                <?php if ($dt2->nov >= $tggn->ju_ap) { ?>
                                                    <td style="background-color: green; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->nov); ?></td>
                                                <?php } elseif ($dt2->nov < $tggn->ju_ap && $dt2->nov != 0) { ?>
                                                    <td style="background-color: orange; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->nov); ?></td>
                                                <?php } else { ?>
                                                    <td style="background-color: red; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->nov); ?></td>
                                                <?php } ?>

                                                <!-- des -->
                                                <?php if ($dt2->des >= $tggn->ju_ap) { ?>
                                                    <td style="background-color: green; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->des); ?></td>
                                                <?php } elseif ($dt2->des < $tggn->ju_ap && $dt2->des != 0) { ?>
                                                    <td style="background-color: orange; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->des); ?></td>
                                                <?php } else { ?>
                                                    <td style="background-color: red; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->des); ?></td>
                                                <?php } ?>

                                                <!-- jan -->
                                                <?php if ($dt2->jan >= $tggn->ju_ap) { ?>
                                                    <td style="background-color: green; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->jan); ?></td>
                                                <?php } elseif ($dt2->jan < $tggn->ju_ap && $dt2->jan != 0) { ?>
                                                    <td style="background-color: orange; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->jan); ?></td>
                                                <?php } else { ?>
                                                    <td style="background-color: red; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->jan); ?></td>
                                                <?php } ?>

                                                <!-- feb -->
                                                <?php if ($dt2->feb >= $tggn->ju_ap) { ?>
                                                    <td style="background-color: green; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->feb); ?></td>
                                                <?php } elseif ($dt2->feb < $tggn->ju_ap && $dt2->feb != 0) { ?>
                                                    <td style="background-color: orange; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->feb); ?></td>
                                                <?php } else { ?>
                                                    <td style="background-color: red; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->feb); ?></td>
                                                <?php } ?>

                                                <!-- mar -->
                                                <?php if ($dt2->mar >= $tggn->ju_ap) { ?>
                                                    <td style="background-color: green; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->mar); ?></td>
                                                <?php } elseif ($dt2->mar < $tggn->ju_ap && $dt2->mar != 0) { ?>
                                                    <td style="background-color: orange; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->mar); ?></td>
                                                <?php } else { ?>
                                                    <td style="background-color: red; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->mar); ?></td>
                                                <?php } ?>

                                                <!-- apr -->
                                                <?php if ($dt2->apr >= $tggn->ju_ap) { ?>
                                                    <td style="background-color: green; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->apr); ?></td>
                                                <?php } elseif ($dt2->apr < $tggn->ju_ap && $dt2->apr != 0) { ?>
                                                    <td style="background-color: orange; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->apr); ?></td>
                                                <?php } else { ?>
                                                    <td style="background-color: red; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->apr); ?></td>
                                                <?php } ?>

                                                <!-- mei -->
                                                <?php if ($dt2->mei >= $tggn->me_ju) { ?>
                                                    <td style="background-color: green; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->mei); ?></td>
                                                <?php } elseif ($dt2->mei < $tggn->me_ju && $dt2->mei != 0) { ?>
                                                    <td style="background-color: orange; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->mei); ?></td>
                                                <?php } else { ?>
                                                    <td style="background-color: red; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->mei); ?></td>
                                                <?php } ?>

                                                <!-- jun -->
                                                <?php if ($dt2->jun >= $tggn->me_ju) { ?>
                                                    <td style="background-color: green; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->jun); ?></td>
                                                <?php } elseif ($dt2->jun < $tggn->me_ju && $dt2->jun != 0) { ?>
                                                    <td style="background-color: orange; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->jun); ?></td>
                                                <?php } else { ?>
                                                    <td style="background-color: red; color: white; font-weight: bold;">
                                                        <?= rupiah($dt2->jun); ?></td>
                                                <?php } ?>

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