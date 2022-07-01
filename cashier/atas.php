<?php
session_start();
if (!isset($_SESSION['lvl_kasir_qwertyuiop'])) {

    echo "
    <script>
    window.location = '../login.php';
    </script>
    ";
}

include '../koneksi.php';
require '../institution/libs/vendor/autoload.php';

use Ramsey\Uuid\Uuid;

$uuid = Uuid::uuid4()->toString();

$id = $_SESSION['id'];
$lmb = $_SESSION['lmb'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user WHERE id_user = '$id' "));
$lm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$lmb' "));

$no = 1;
$id_user = $user['id_user'];
$kol = $lm['kode'];
$nama_user = $user['nama'];
$level = $user['level'];

$info = mysqli_query($conn, "SELECT * FROM info");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SIMKU-PADUKA App</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="../institution/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../institution/libs/vendor/fortawesome/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="../institution/libs/vendor/driftyco/ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../institution/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../institution/dist/css/skins/_all-skins.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../institution/plugins/datatables/dataTables.bootstrap.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="../institution/plugins/select2/select2.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../institution/plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="../institution/plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="../institution/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="../institution/plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="../institution/plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="../institution/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <!-- Spinner -->
    <link rel="stylesheet" href="../institution/dist/animated.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <a href="index.php" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>SM</b>PD</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>SIMKU</b>PADUKA</span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">

                        <!-- Notifications: style can be found in dropdown.less -->
                        <li class="dropdown notifications-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-bell-o"></i>
                                <span class="label label-warning"><?= mysqli_num_rows($info) ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">Ada <?= mysqli_num_rows($info) ?> informasi</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu">
                                        <?php while ($if = mysqli_fetch_assoc($info)) { ?>
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-envelope text-aqua"></i> <?= $if['judul'] ?>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </li>
                                <li class="footer"><a href="info.php">Lihat semua</a></li>
                            </ul>
                        </li>

                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="../institution/dist/img/D.png" class="user-image" alt="User Image">
                                <span class="hidden-xs"><?= $nama_user ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="../institution/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                                    <p>
                                        <?= $nama_user ?>
                                        <small>Operator <?= $level ?></small>
                                    </p>
                                </li>

                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="setting.php" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="logout.php" onclick="return confirm('Yakin akan logout ?')" class="btn btn-default btn-flat">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!-- Control Sidebar Toggle Button -->

                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="../institution/dist/img/D.png" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p><?= $nama_user ?></p>
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>

                <!-- /.search form -->
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu">
                    <li class="header">MAIN NAVIGATION</li>
                    <li class="active treeview">
                        <a href="index.php">
                            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    <!-- <li><a href="cair.php"><i class="fa fa-hourglass-half"></i> <span>Pencairan</span></a></li> -->
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-hourglass-half"></i>
                            <span>Pencairan</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="cair.php"><i class="fa fa-circle-o"></i> Pengajuan</a></li>
                            <li><a href="cair_disp.php"><i class="fa fa-circle-o"></i> Pengajuan Disposisi</a></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-money"></i>
                            <span>Tanggungan Santri</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="tanggungan.php"><i class="fa fa-circle-o"></i> Data Tanggungan</a></li>
                            <li><a href="tanggungan23.php"><i class="fa fa-circle-o"></i> Data Tanggungan 22/23</a></li>
                            <li><a href="pembayaran.php"><i class="fa fa-circle-o"></i> Pembayaran</a></li>
                            <li><a href="rekap_tg.php"><i class="fa fa-circle-o"></i> Rekap Tanggungan</a></li>
                        </ul>
                    </li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-sign-in"></i>
                            <span>Pemasukan</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="bos.php"><i class="fa fa-circle-o"></i> BOS</a></li>
                            <li><a href="bp.php"><i class="fa fa-circle-o"></i> Biaya Pendidikan</a></li>
                            <li><a href="pes.php"><i class="fa fa-circle-o"></i> Pesantren</a></li>
                        </ul>
                    </li>
                    <li class="header">ADDON</li>
                    <li><a href="info.php"><i class="fa fa-info-circle text-yellow"></i> <span>Informasi</span></a></li>
                    <li><a href="setting.php"><i class="fa fa-cog text-red"></i> <span>Pengaturan</span></a></li>
                    <hr>
                    <a onclick="return confirm('Yakin akan logout ?')" href="logout.php"><button class="btn btn-danger btn-block"><i class="fa fa-power-off"></i> Logout</button></a>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>