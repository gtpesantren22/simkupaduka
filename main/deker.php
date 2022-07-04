<?php
include 'head.php';
$bk = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "July", "Agustus", "September", "Oktober", "November", "Desember");

// mysqli_query($conn, "DROP VIEW IF EXISTS tot_bos");
// mysqli_query($conn, "DROP VIEW IF EXISTS tot_pes");
// mysqli_query($conn, "DROP VIEW IF EXISTS tot_ris");
// mysqli_query($conn, "DROP VIEW IF EXISTS tot_ris_s");
// mysqli_query($conn, "DROP VIEW IF EXISTS tot_byr");
// mysqli_query($conn, "DROP VIEW IF EXISTS saldo");

// mysqli_query($conn, "CREATE VIEW tot_bos AS SELECT SUM(nominal) AS jml, 'A' AS prd, 'Pemsukan BOS' AS nama, '2022' AS tahun, 'debit' AS ket FROM bos ");
// mysqli_query($conn, "CREATE VIEW tot_pes AS SELECT SUM(nominal) AS jml, 'B' AS prd, 'Pemsukan Pesantren' AS nama, '2022' AS tahun, 'debit' AS ket FROM pesantren ");
// mysqli_query($conn, "CREATE VIEW tot_ris_s AS SELECT SUM(sisa) AS jml, 'C' AS prd, 'Pemsukan Sisa Pencairan Pengajuan' AS nama, '2022' AS tahun, 'debit' AS ket FROM real_sisa ");
// mysqli_query($conn, "CREATE VIEW tot_ris AS SELECT SUM(nominal) AS jml, 'D' AS prd, 'Pengeluaran Pencairan Pengajuan' AS nama, '2022' AS tahun, 'kredit' AS ket FROM realis ");
// mysqli_query($conn, "CREATE VIEW tot_byr AS SELECT SUM(nominal) AS jml, 'E' AS prd, 'Pemasukan Pembayaran Santri' AS nama, '2022' AS tahun, 'debit' AS ket FROM pembayaran ");

// mysqli_query($conn, "CREATE VIEW saldo AS SELECT tot_bos.*, tot_pes.*, tot_ris.*, tot_ris_s.*, tot_byr.* FROM tot_bos
// JOIN tot_pes ON tot_bos.tahun=tot_pes.tahun 
// JOIN tot_ris ON tot_bos.tahun=tot_ris.tahun 
// JOIN tot_ris_s ON tot_bos.tahun=tot_ris_s.tahun 
// JOIN tot_byr ON tot_bos.tahun=tot_byr.tahun 
// ");


$keluar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jml FROM realis WHERE tahun = '$tahun_ajaran' "));
$tot4 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jml FROM pembayaran WHERE tahun = '$tahun_ajaran' "));
$tot3 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(sisa) AS jml FROM real_sisa WHERE tahun = '$tahun_ajaran' "));
$tot2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jml FROM pesantren WHERE tahun = '$tahun_ajaran' "));
$tot = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jml FROM bos WHERE tahun = '$tahun_ajaran' "));

?>
<!-- Datatables -->
<link href="vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
<link href="vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
<link href="vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
<link href="vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

<!-- bootstrap-daterangepicker -->
<link href="vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<!-- bootstrap-datetimepicker -->
<link href="vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">

<!-- page content -->
<div class="right_col" role="main">
    <div class="">

        <div class="clearfix"></div>

        <div class="">
            <div class="col-md-12 col-sm-12  ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-bars"></i> Data Pengeluaran Dari Bendahara Utama </h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <!-- <li><button class="btn btn-info btn-sm" data-toggle="modal" data-target="#tambah"><i class="fa fa-plus-square"></i> Tambah Data</button></li> -->
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <div class="tab-content" id="myTabContent">

                            <!-- Pemasukan BOS -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-badgeledby="home-tab">
                                <div class="row">
                                    <div class="col-md-9">
                                        <table id="" class="table table-striped table-bordered table-sm" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nama</th>
                                                    <th>Debit</th>
                                                    <th>Kredit</th>
                                                    <th>Saldo</th>
                                                </tr>
                                            </thead>
                                            <tr>
                                                <td>A</td>
                                                <td>Pemasukan Bos</td>
                                                <td>Rp. <?= rupiah($tot['jml']) ?></td>
                                                <td>Rp. 0</td>
                                                <td>Rp. <?= rupiah($tot['jml']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>B</td>
                                                <td>Pemasukan Pesantren</td>
                                                <td>Rp. <?= rupiah($tot2['jml']) ?></td>
                                                <td>Rp. 0</td>
                                                <td>Rp. <?= rupiah($tot['jml'] + $tot2['jml']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>C</td>
                                                <td>Pemasukan Sisa Realisasi RAB</td>
                                                <td>Rp. <?= rupiah($tot3['jml']) ?></td>
                                                <td>Rp. 0</td>
                                                <td>Rp. <?= rupiah($tot['jml'] + $tot2['jml'] + $tot3['jml']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>D</td>
                                                <td>Pemasukan Pembayaran BP Santri (Syahriah)</td>
                                                <td>Rp. <?= rupiah($tot4['jml']) ?></td>
                                                <td>Rp. 0</td>
                                                <td>Rp. <?= rupiah($tot['jml'] + $tot2['jml'] + $tot3['jml'] + $tot4['jml']) ?></td>
                                            </tr>
                                            <tr>
                                                <td>E</td>
                                                <td>Pengeluaran Pencairan/Realisasi</td>
                                                <td>Rp. 0</td>
                                                <td>Rp. <?= rupiah($keluar['jml']) ?></td>
                                                <td>Rp. <?= rupiah($tot['jml'] + $tot2['jml'] + $tot3['jml'] + $tot4['jml'] - $keluar['jml']) ?></td>
                                            </tr>

                                            <tfoot>
                                                <tr style="background-color: darkcyan; color: white;">
                                                    <th colspan="4">TOTAL</th>
                                                    <th colspan="3"><?= rupiah($tot['jml'] + $tot2['jml'] + $tot3['jml'] + $tot4['jml'] - $keluar['jml']); ?></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <hr>
                                <!-- BOS -->
                                <h3>A. Pemasukan BOS</h3>
                                <table id="datatable" class="table table-striped table-bordered table-sm" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Uraian</th>
                                            <th>Periode</th>
                                            <th>Nominal</th>
                                            <th>Tanggal Bayar</th>
                                            <th>Tahun</th>
                                        </tr>
                                    </thead>
                                    <?php
                                    $no = 1;
                                    $dt_bos = mysqli_query($conn, "SELECT * FROM bos WHERE tahun = '$tahun_ajaran'");
                                    while ($a = mysqli_fetch_assoc($dt_bos)) { ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $a['kode'] ?></td>
                                            <td><?= $a['uraian'] ?></td>
                                            <td><?= $a['periode'] ?></td>
                                            <td>Rp. <?= number_format($a['nominal'], 0, '.', '.') ?></td>
                                            <td><?= $a['tgl_setor'] ?></td>
                                            <td><?= $a['tahun'] ?></td>

                                        </tr>
                                    <?php } ?>
                                    <tfoot>
                                        <tr style="background-color: blueviolet; color: white;">
                                            <th colspan="4">TOTAL</th>
                                            <th colspan="3"><?= rupiah($tot['jml']); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <hr>

                                <!-- Pesantern -->
                                <h3>B. Pemasukan Pesantren</h3>
                                <table id="datatable2" class="table table-striped table-bordered table-sm" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Uraian</th>
                                            <th>Periode</th>
                                            <th>Nominal</th>
                                            <th>Tanggal Bayar</th>
                                            <th>Tahun</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $dt = mysqli_query($conn, "SELECT * FROM pesantren WHERE tahun = '$tahun_ajaran' ");

                                        while ($a = mysqli_fetch_assoc($dt)) { ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $a['kode'] ?></td>
                                                <td><?= $a['uraian'] ?></td>
                                                <td><?= $a['periode'] ?></td>
                                                <td>Rp. <?= number_format($a['nominal'], 0, '.', '.') ?></td>
                                                <td><?= $a['tgl_bayar'] ?></td>
                                                <td><?= $a['tahun'] ?></td>

                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr style="background-color: blueviolet; color: white;">
                                            <th colspan="4">TOTAL</th>
                                            <th colspan="3"><?= rupiah($tot2['jml']); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <hr>

                                <!-- Sisa Saldo Realisasi -->
                                <h3>C. Sisa Saldo Realisasi</h3>
                                <table id="datatable3" class="table table-striped table-bordered table-sm" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Pnj</th>
                                            <th>Lembaga</th>
                                            <th>Periode</th>
                                            <th>Dana Cair</th>
                                            <th>Terserap</th>
                                            <th>Sisa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $dt = mysqli_query($conn, "SELECT * FROM real_sisa WHERE tahun = '$tahun_ajaran'");

                                        while ($a = mysqli_fetch_assoc($dt)) {
                                            $kd = explode('.', $a['kode_pengajuan']);
                                            if (preg_match("/DISP./i", $a['kode_pengajuan'])) {
                                                $lm = $kd[1];
                                                $bl = $kd[3];
                                                $th = $kd[4];
                                            } else {
                                                $lm = $kd[0];
                                                $bl = $kd[2];
                                                $th = $kd[3];
                                            }

                                            $nm =  mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$lm' AND tahun = '$tahun_ajaran' "));
                                        ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $a['kode_pengajuan'] ?></td>
                                                <td><?= $nm['nama'] ?></td>
                                                <td><?= $bk[$bl] . ' ' . $th ?></td>
                                                <td>Rp. <?= number_format($a['dana_cair'], 0, '.', '.') ?></td>
                                                <td>Rp. <?= number_format($a['dana_serap'], 0, '.', '.') ?></td>
                                                <td>Rp. <?= number_format($a['sisa'], 0, '.', '.') ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr style="background-color: blueviolet; color: white;">
                                            <th colspan="4">TOTAL</th>
                                            <th colspan="3"><?= rupiah($tot3['jml']); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <hr>

                                <!-- Pemasukan BP Non Formal (Syahriah) -->
                                <h3>D. Pemasukan BP Non Formal (Syahriah)</h3>
                                <table id="datatable4" class="table table-striped table-bordered table-sm" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Tanggal Bayar</th>
                                            <th>Nominal</th>
                                            <th>Tahun Ajaran</th>
                                            <th>Penerima</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $rls = mysqli_query($conn, "SELECT * FROM pembayaran WHERE tahun = '$tahun_ajaran' ORDER BY tgl DESC");
                                        while ($ls_jns = mysqli_fetch_assoc($rls)) { ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= $ls_jns['nama']; ?></td>
                                                <td><?= $ls_jns['tgl']; ?></td>
                                                <td><?= rupiah($ls_jns['nominal']); ?></td>
                                                <td><?= $ls_jns['tahun']; ?></td>
                                                <td><?= $ls_jns['kasir']; ?></td>

                                            </tr>
                                            <!-- Modal -->

                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr style="background-color: blueviolet; color: white;">
                                            <th colspan="3">TOTAL</th>
                                            <th colspan="3"><?= rupiah($tot4['jml']); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <hr>

                                <!-- Pengeluaran Pencairan Pengajuan -->
                                <h3>E. Pengeluaran Pencairan Pengajuan</h3>
                                <table id="datatable5" class="table table-striped table-bordered table-sm" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Lembaga</th>
                                            <th>Periode</th>
                                            <th>Verval / Approv / Cair / SPJ</th>
                                            <th>Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $dt_bos = mysqli_query($conn, "SELECT a.*, b.nama FROM pengajuan a JOIN lembaga b ON a.lembaga=b.kode WHERE a.spj != 2 AND a.tahun = '$tahun_ajaran'");
                                        while ($a = mysqli_fetch_assoc($dt_bos)) {
                                            $kd_pj = $a['kode_pengajuan'];
                                            $jml = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jml FROM real_sm WHERE kode_pengajuan = '$kd_pj' AND tahun = '$tahun_ajaran'"));
                                            $jml2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jml FROM realis WHERE kode_pengajuan = '$kd_pj' AND tahun = '$tahun_ajaran'"));

                                            $kfe = $jml['jml'] + $jml2['jml'];
                                            // $st = count($kfe);
                                        ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $a['nama'] ?></td>
                                                <td><?= $bulan[$a['bulan']] . ' ' . $a['tahun'] ?></td>
                                                <td>
                                                    <?= $a['verval'] == 1 ? "<span class='badge badge-success'><i class='fa fa-check'></i> sudah</span>" : "<span class='badge badge-danger'><i class='fa fa-times'></i> belum</span>"; ?>
                                                    <?= $a['apr'] == 1 ? "<span class='badge badge-success'><i class='fa fa-check'></i> sudah</span>" : "<span class='badge badge-danger'><i class='fa fa-times'></i> belum</span>"; ?>
                                                    <?= $a['cair'] == 1 ? "<span class='badge badge-success'><i class='fa fa-check'></i> sudah</span>" : "<span class='badge badge-danger'><i class='fa fa-times'></i> belum</span>"; ?>
                                                    <?php if ($a['spj'] == 0) { ?>
                                                        <span class="badge badge-danger"><i class="fa fa-times"></i> belum upload</span>
                                                    <?php } else if ($a['spj'] == 1) { ?>
                                                        <span class="badge badge-warning btn-xs"><i class="fa fa-spinner fa-refresh-animate"></i>
                                                            proses verifikasi</span>
                                                    <?php } else { ?>
                                                        <span class="badge badge-success"><i class="fa fa-check"></i> sudah selesai</span>
                                                    <?php } ?>
                                                </td>
                                                <td><?= rupiah($kfe) ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr style="background-color: blueviolet; color: white;">
                                            <th colspan="4">TOTAL</th>
                                            <th><?= rupiah($keluar['jml']); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <hr>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<!-- /page content -->

<?php include 'foot.php'; ?>
<!-- Datatables -->
<script src="vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
<!-- bootstrap-daterangepicker -->
<script src="vendors/moment/min/moment.min.js"></script>
<script src="vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap-datetimepicker -->
<script src="vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<script>
    $(document).ready(function() {
        $('#datatable2').DataTable();
        $('#datatable3').DataTable();
        $('#datatable4').DataTable();
        $('#datatable5').DataTable();
        $('#datatable6').DataTable();

        $('#datePick').datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $('#datePick2').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    });
</script>