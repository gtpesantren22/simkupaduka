<?php
include 'atas.php';
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
                            <h5>Daftar Pengajuan</h5>
                            <span>use class <code>table-hover</code> inside table element</span>
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
                            <div class="table-responsive">
                                <table class="table table-hover table-sm" id="table_id">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Lembaga</th>
                                            <th>Periode</th>
                                            <th>Verval / Approv / Cair / SPJ</th>
                                            <th>Nominal</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $dt_bos = mysqli_query($conn, "SELECT a.*, b.nama FROM pengajuan a JOIN lembaga b ON a.lembaga=b.kode WHERE a.spj != 2 AND a.tahun = '$tahun_ajaran' AND b.tahun = '$tahun_ajaran' ");
                                        while ($a = mysqli_fetch_assoc($dt_bos)) {
                                            $kd_pj = $a['kode_pengajuan'];
                                            $jml = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jml FROM real_sm WHERE kode_pengajuan = '$kd_pj' AND tahun = '$tahun_ajaran' "));
                                            $jml2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jml FROM realis WHERE kode_pengajuan = '$kd_pj' AND tahun = '$tahun_ajaran' "));
                                            if ($jml['jml'] == 0) {
                                                $jj = $jml2['jml'];
                                            } else {
                                                $jj = $jml['jml'];
                                            }

                                            if (preg_match("/DISP./i", $kd_pj)) {
                                                $rt = "<span class='badge badge-danger'>DISPOSISI</span>";
                                            } else {
                                                $rt = '';
                                            }
                                        ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $a['nama'] . ' ' . $rt ?></td>
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
                                                <td><?= rupiah($jj) ?></td>
                                                <td>
                                                    <?php if ($a['verval'] == 0) { ?>
                                                        <button class="btn btn-disabled btn-sm" disabled><i class="fa fa-search"></i> Cek & Setujui</button>
                                                    <?php } else { ?>
                                                        <a href="<?= 'pengajuan_detail.php?kode=' . $a['kode_pengajuan'] ?>"><button class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Cek & Setujui</button></a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
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

<?php
include 'bawah.php';
?>