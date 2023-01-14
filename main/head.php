<?php
session_start();
if (!isset($_SESSION['lvl_adm_qwertyuiop'])) {

    echo "
    <script>
    window.location = '../login.php';
    </script>
    ";
}
include 'libs/vendor/autoload.php';
include '../koneksi.php';
include '../func_wa.php';

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
//uuid1

$uuid = Uuid::uuid4()->toString();

$id = $_SESSION['id'];
$lmb = $_SESSION['lmb'];
$tahun_ajaran = $_SESSION['tahun'];

$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user WHERE id_user = '$id' "));
$lm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$lmb' "));


$no = 1;
$id_user = $user['id_user'];
$kol = $lm['kode'];
$nama_user = $user['nama'];
$level = $user['level'];

$bulan = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "July", "Agustus", "September", "Oktober", "November", "Desember");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> -->
    <!-- Meta, title, CSS, favicons, etc. -->
    <!-- <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="production/images/favicon.ico" type="image/ico" />

    <title>App Sentralisasi DWK </title>

    <!-- Bootstrap -->
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <link href="vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet" />
    <!-- bootstrap-daterangepicker -->
    <link href="vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">


    <!-- Custom Theme Style -->
    <link href="build/css/custom.min.css" rel="stylesheet">
</head>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="#" class="site_title"><i class="fa fa-paw"></i> <span>Sentralisasi</span></a>
                    </div>

                    <div class="clearfix"></div>

                    <!-- menu profile quick info -->
                    <div class="profile clearfix">
                        <div class="profile_pic">
                            <img src="production/images/D (1).png" alt="..." class="img-circle profile_img">
                        </div>
                        <div class="profile_info">
                            <span>Welcome,</span>
                            <h2><?= $nama_user ?></h2>
                        </div>
                    </div>
                    <!-- /menu profile quick info -->

                    <br />

                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            <h3>Tahun Pelajaran - <?= $tahun_ajaran; ?></h3>
                            <hr>
                            <h3>General</h3>
                            <ul class="nav side-menu">
                                <li><a href="index.php"><i class="fa fa-dashboard"></i> Dahsboard </span></a></li>
                                <li><a><i class="fa fa-folder-open"></i> Master Data <span
                                            class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="santri.php">Data santri</a></li>
                                        <!-- <li><a>Daftar Biaya Pendidikan<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li><a href="bppes.php">BP Pesantren</a>
                                                </li>
                                                <li><a href="bpfor.php">BP Formal</a>
                                                </li>
                                            </ul>
                                        </li> -->
                                        <li><a href="bpOk.php"> Biaya Pendidikan </span></a></li>
                                        <li><a href="kode.php"> Daftar Kode </span></a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-money"></i> Pemasukan <span
                                            class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="masuk.php">Semua Pemasukan</a></li>
                                        <li><a href="bp.php">Biaya Pendidikan</a></li>
                                    </ul>
                                </li>
                                <li><a href="#"><i class="fa fa-shopping-cart"></i> Rencana Belanja <span
                                            class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="rab.php">Lembaga</a></li>
                                        <li><a href="rab_kbj.php">Kebijakan</a></li>
                                        <li><a href="pak.php">PAK</a></li>
                                    </ul>
                                </li>
                                <li><a href="#"><i class="fa fa-credit-card"></i> Realisasi <span
                                            class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="real.php">Data</a></li>
                                        <li><a href="pengajuan.php">Pengajuan</a></li>
                                        <li><a href="spj.php">SPJ</a></li>
                                    </ul>
                                </li>
                                <li><a href="#"><i class="fa fa-money"></i> Saldo Utama <span
                                            class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="masuk_saldo.php">Pemasukan</a></li>
                                        <li><a href="keluar_saldo.php">Pengeluaran</a></li>
                                    </ul>
                                </li>
                                <li><a href="#"><i class="fa fa-bank"></i> Disposisi <span
                                            class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="dispo.php">Data disposisi</a></li>
                                        <li><a href="dispo_sisa.php">Pengembalian</a></li>
                                    </ul>
                                </li>
                            </ul>
                            <h3>AddOn</h3>
                            <ul class="nav side-menu">
                                <li><a><i class="fa fa-user-md"></i> User Akun <span
                                            class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="akun.php">Data Akun</a></li>
                                        <li><a href="#">Profil</a></li>
                                    </ul>
                                </li>
                                <li><a href="info.php"><i class="fa fa-clipboard"></i> Informasi </span></a></li>
                                <li><a href="history_pengajuan.php"><i class="fa fa-bookmark"></i> History Pengajuan
                                        </span></a></li>
                                <li><a href="#"><i class="fa fa-bookmark"></i> Dana Cadangan </span></a></li>
                                <li><a><i class="fa fa-list"></i> Cetak Laporan <span
                                            class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="#">Tutup Kas</a></li>
                                        <li><a href="#">BA Pem. Kas</a></li>
                                        <li><a href="#">Buku Kas Umum</a></li>
                                        <li><a href="#">Rincian Objek Belanja</a></li>
                                        <li><a href="#">SPTJM</a></li>
                                        <li><a href="deker.php">Rekap Debit/Kredit</a></li>
                                        <li><a href="rekap_belanja_kpa.php">Rekap Belanja Per KPA</a></li>
                                        <li><a href="rekap_rab_jenis.php">Rekap Per Jenis Belanja</a></li>
                                        <li><a href="rekap_rab_kpa.php">RAB Per KPA</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <!-- /sidebar menu -->

                    <!-- /menu footer buttons -->
                    <div class="sidebar-footer hidden-small">
                        <a data-toggle="tooltip" data-placement="top" title="Settings">
                            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                            <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Lock">
                            <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
                            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                        </a>
                    </div>
                    <!-- /menu footer buttons -->
                </div>
            </div>

            <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>
                    <nav class="nav navbar-nav">
                        <ul class="navbar-right">
                            <li class="nav-item dropdown open" style="padding-left: 15px;">
                                <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true"
                                    id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                                    <img src="production/images/D (1).png" alt=""><?= $nama_user ?>
                                </a>
                                <div class="dropdown-menu dropdown-usermenu pull-right"
                                    aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="javascript:;"> Profile</a>
                                    <a class="dropdown-item" href="set_akses.php"> Setting Akses</a>
                                    <a class="dropdown-item" href="logout.php"
                                        onclick="return confirm('Yakin akan logout ?')"><i
                                            class="fa fa-sign-out pull-right"></i> Log Out</a>
                                </div>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->