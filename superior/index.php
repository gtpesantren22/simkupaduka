<?php
include 'atas.php';

$tot = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jm FROM bos "));
$tot2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jm FROM pesantren "));
$tot3 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(sisa) AS jm FROM real_sisa "));
$tot4 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jm FROM pembayaran "));

$kas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(IF( ket = 'masuk', nominal, 0)) AS masuk, SUM(IF( ket = 'keluar', nominal, 0)) AS keluar FROM kas "));

$msk = $tot['jm'] + $tot2['jm'] + $tot3['jm'] + $tot4['jm'];
$sld = $kas['masuk'] - $kas['keluar'];
?>

<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Dashboard</h5>
                        <p class="m-b-0">Welcome to Mega Able</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="index.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Dashboard</a>
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
                    <div class="row">
                        <!-- task, page, download counter  start -->
                        <div class="col-xl-4 col-md-6">
                            <div class="card">
                                <div class="card-block">
                                    <div class="row align-items-center">
                                        <div class="col-8">
                                            <h4 class="text-c-green"><?= rupiah($msk); ?></h4>
                                            <h6 class="text-muted m-b-0">Pemasukan</h6>
                                        </div>
                                        <div class="col-4 text-right">
                                            <i class="fa fa-file-text-o f-28"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-c-green">
                                    <div class="row align-items-center">
                                        <div class="col-9">
                                            <p class="text-white m-b-0">% cek history</p>
                                        </div>
                                        <div class="col-3 text-right">
                                            <i class="fa fa-line-chart text-white f-16"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6">
                            <div class="card">
                                <div class="card-block">
                                    <div class="row align-items-center">
                                        <div class="col-8">
                                            <h4 class="text-c-red"><?= rupiah($msk - $sld); ?></h4>
                                            <h6 class="text-muted m-b-0">Pengeluran</h6>
                                        </div>
                                        <div class="col-4 text-right">
                                            <i class="fa fa-calendar-check-o f-28"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-c-red">
                                    <div class="row align-items-center">
                                        <div class="col-9">
                                            <p class="text-white m-b-0">% cek history</p>
                                        </div>
                                        <div class="col-3 text-right">
                                            <i class="fa fa-line-chart text-white f-16"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6">
                            <div class="card">
                                <div class="card-block">
                                    <div class="row align-items-center">
                                        <div class="col-8">
                                            <h4 class="text-c-purple"><?= rupiah($sld); ?></h4>
                                            <h6 class="text-muted m-b-0">Saldo Pesantren</h6>
                                        </div>
                                        <div class="col-4 text-right">
                                            <i class="fa fa-bar-chart f-28"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-c-purple">
                                    <div class="row align-items-center">
                                        <div class="col-9">
                                            <p class="text-white m-b-0">% cek history</p>
                                        </div>
                                        <div class="col-3 text-right">
                                            <i class="fa fa-line-chart text-white f-16"></i>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-xl-12 col-md-6">
                            <div class="card feed-card card-info">
                                <div class="card-header">
                                    <h5><i class="ti-bell"></i> Pengajuan menuggu persetujuan</h5>
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
                                <div class="card-block">
                                    <?php
                                    $dt_bos = mysqli_query($conn, "SELECT a.*, b.nama FROM pengajuan a JOIN lembaga b ON a.lembaga=b.kode WHERE a.verval = 1 AND a.apr = 0");
                                    while ($ar = mysqli_fetch_assoc($dt_bos)) {
                                        $kpp = $ar['kode_pengajuan'];
                                        $dt2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS nm, tgl FROM real_sm WHERE kode_pengajuan = '$kpp' "));
                                    ?>
                                        <div class="row m-b-30">
                                            <div class="col-auto p-r-0">
                                                <i class="fa fa-bell bg-c-blue feed-icon"></i>
                                            </div>
                                            <div class="col">
                                                <h6 class="m-b-5">Pengajuan dari <strong style="font-weight: bold; font-style: italic; color: #ff5252;"><?= $ar['nama']; ?></strong> <span class="text-muted f-right f-13"><?= $dt2['tgl']; ?></span></h6>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="text-center">
                                        <a href="pengajuan.php" class="b-b-primary text-primary">Cek pengajuan</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- task, page, download counter  end -->
                    </div>
                </div>
                <!-- Page-body end -->
            </div>
            <div id="styleSelector"> </div>
        </div>
    </div>
</div>

<?php
include 'bawah.php';
?>