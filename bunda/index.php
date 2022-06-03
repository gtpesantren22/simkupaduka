<?php
include 'head.php';

$masuk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jml FROM kas WHERE ket = 'masuk' GROUP BY ket "));
$keluar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jml FROM kas WHERE ket = 'keluar' GROUP BY ket "));

$tot = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jm FROM bos "));
$tot2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jm FROM pesantren "));
$tot3 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(sisa) AS jm FROM real_sisa "));
$tot4 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jm FROM pembayaran "));

$kas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(IF( ket = 'masuk', nominal, 0)) AS masuk, SUM(IF( ket = 'keluar', nominal, 0)) AS keluar FROM kas "));

$msk = $tot['jm'] + $tot2['jm'] + $tot3['jm'] + $tot4['jm'];
$sld = $kas['masuk'] - $kas['keluar'];
?>

<!-- [ Main Content ] start -->
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Dashboard </h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Dashboard </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
        <div class="row">
            <!-- table card-1 start -->
            <div class="col-md-12">
                <center>
                    <h3><?= strtoupper('Rekapitulasi Pemasukan dan Pengeluaran dari Bulan Januari 2022'); ?></h3>
                </center>
                <br>
            </div>
            <div class="col-md-12 col-xl-4">

                <div class="card flat-card widget-primary-card">
                    <div class="row-table">
                        <div class="col-sm-3 card-body">
                            <i class="feather icon-star-on"></i>
                        </div>
                        <div class="col-sm-9">
                            <h4><?= rupiah($masuk['jml']); ?></h4>
                            <h6>Jumlah Pemasukan</h6>
                        </div>
                    </div>
                </div>
                <!-- widget primary card end -->
            </div>
            <!-- table card-1 end -->
            <!-- table card-2 start -->
            <div class="col-md-12 col-xl-4">

                <!-- widget-success-card start -->
                <div class="card flat-card widget-primary-card">
                    <div class="row-table">
                        <div class="col-sm-3 card-body">
                            <i class="feather icon-star-on"></i>
                        </div>
                        <div class="col-sm-9">
                            <h4><?= rupiah($keluar['jml']); ?></h4>
                            <h6>Jumlah Pengeluaran</h6>
                        </div>
                    </div>
                </div>
                <!-- widget-success-card end -->
            </div>
            <!-- table card-2 end -->

            <!-- table card-2 start -->
            <div class="col-md-12 col-xl-4">

                <!-- widget-success-card start -->
                <div class="card flat-card widget-primary-card">
                    <div class="row-table">
                        <div class="col-sm-3 card-body">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="col-sm-9">
                            <h4><?= rupiah($masuk['jml'] - $keluar['jml']); ?></h4>
                            <h6>Saldo</h6>
                        </div>
                    </div>
                </div>
                <!-- widget-success-card end -->
            </div>
            <!-- table card-2 end -->


        </div>
        <hr>
        <div class="row">
            <!-- table card-1 start -->
            <div class="col-md-12">
                <center>
                    <h3><?= strtoupper('Rekapitulasi Pemasukan dan Pengeluaran dari Bulan Juli 2021') ?></h3>
                </center>
                <br>
            </div>
            <div class="col-md-12 col-xl-4">

                <div class="card flat-card widget-purple-card">
                    <div class="row-table">
                        <div class="col-sm-3 card-body">
                            <i class="feather icon-star-on"></i>
                        </div>
                        <div class="col-sm-9">
                            <h4><?= rupiah($msk); ?></h4>
                            <h6>Jumlah Pemasukan</h6>
                        </div>
                    </div>
                </div>
                <!-- widget primary card end -->
            </div>
            <!-- table card-1 end -->
            <!-- table card-2 start -->
            <div class="col-md-12 col-xl-4">

                <!-- widget-success-card start -->
                <div class="card flat-card widget-purple-card">
                    <div class="row-table">
                        <div class="col-sm-3 card-body">
                            <i class="feather icon-star-on"></i>
                        </div>
                        <div class="col-sm-9">
                            <h4><?= rupiah($msk - $sld); ?></h4>
                            <h6>Jumlah Pengeluaran</h6>
                        </div>
                    </div>
                </div>
                <!-- widget-success-card end -->
            </div>
            <!-- table card-2 end -->

            <!-- table card-2 start -->
            <div class="col-md-12 col-xl-4">

                <!-- widget-success-card start -->
                <div class="card flat-card widget-purple-card">
                    <div class="row-table">
                        <div class="col-sm-3 card-body">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="col-sm-9">
                            <h4><?= rupiah($sld); ?></h4>
                            <h6>Saldo</h6>
                        </div>
                    </div>
                </div>
                <!-- widget-success-card end -->
            </div>
            <!-- table card-2 end -->


        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>
<!-- [ Main Content ] end -->
<?php include 'foot.php' ?>