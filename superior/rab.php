<?php
include 'atas.php';
$tot = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) as tt, COUNT(jenis) AS jm FROM rab  "));
?>

<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Daftar RAB Lembaga</h5>
                        <p class="m-b-0">DaftarRAB dari beberapa lembaga</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="index.php"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">RAB</a>
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
                    <div class="card">
                        <div class="card-block">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="text-c-purple">TOTAL RAB LEMBAGA : <?= rupiah($tot['tt']) ?></h4>
                                    <h6 class="text-muted m-b-0">Dengan total item : <?= $tot['jm'] ?> item</h6>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="fa fa-bar-chart f-28"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                        $df = mysqli_query($conn, "SELECT b.nama, a.lembaga FROM rab a JOIN lembaga b ON a.lembaga=b.kode GROUP BY a.lembaga ORDER BY a.lembaga");
                        while ($r = mysqli_fetch_assoc($df)) {
                            $lembaga = $r['lembaga']
                        ?>
                            <div class="col-xl-6">
                                <!-- Tooltip style 1 card start -->
                                <div class="card o-visible">
                                    <div class="card-header">
                                        <h5>RAB Lembaga <?= $r['nama']; ?></h5>
                                    </div>
                                    <div class="card-block">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped">
                                                <tbody>
                                                    <?php
                                                    $df2 = mysqli_query($conn, "SELECT jenis, IF(jenis = 'A', 'Belanja Barang', IF(jenis = 'B', 'Langganan Daya dan Jasa', IF(jenis = 'C', 'Belanja Kegiatan','Umum'))) as nm_jenis, COUNT(jenis) as jml, SUM(total) as tot FROM rab WHERE lembaga = '$lembaga' GROUP BY jenis ORDER BY jenis ASC");
                                                    $tt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) as tt, COUNT(jenis) AS jm FROM rab WHERE lembaga = '$lembaga' "));
                                                    while ($r2 = mysqli_fetch_assoc($df2)) {
                                                        $lembaga = $r['lembaga']
                                                    ?>
                                                        <tr>
                                                            <td><b><?= $r2['jenis'] ?>.</b></td>
                                                            <td><?= $r2['nm_jenis'] ?></td>
                                                            <td><?= $r2['jml'] ?> item</td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td class="text-left" colspan="2" style="background-color: lightseagreen; color: white; ">JML RAB : <?= rupiah($tt['tt']) ?></td>
                                                        <td style="background-color: #DC3545; color: white;"><?= $tt['jm'] ?> item</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- Tooltip style 1 card end -->
                            </div>
                        <?php } ?>
                    </div>

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