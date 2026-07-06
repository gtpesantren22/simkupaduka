<?php

use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Base;
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="<?= base_url('vertical/'); ?>assets/images/favicon-32x32.png" type="image/png" />
    <!--plugins-->
    <link href="<?= base_url('vertical/'); ?>assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
    <link href="<?= base_url('vertical/'); ?>assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="<?= base_url('vertical/'); ?>assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url('vertical/'); ?>assets/plugins/notifications/css/lobibox.min.css" />
    <link href="<?= base_url('vertical/'); ?>assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="<?= base_url('vertical/'); ?>assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <link href="<?= base_url('vertical/'); ?>assets/plugins/datetimepicker/css/classic.css" rel="stylesheet" />
    <link href="<?= base_url('vertical/'); ?>assets/plugins/datetimepicker/css/classic.time.css" rel="stylesheet" />
    <link href="<?= base_url('vertical/'); ?>assets/plugins/datetimepicker/css/classic.date.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url('vertical/'); ?>assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="<?= base_url('vertical/'); ?>assets/sw/sweetalert2.min.css" rel="stylesheet" />
    <link href="<?= base_url('vertical/'); ?>assets/plugins/select2/css/select2.min.css" rel="stylesheet" />
    <link href="<?= base_url('vertical/'); ?>assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet" />
    <!-- loader-->
    <link href="<?= base_url('vertical/'); ?>assets/css/pace.min.css" rel="stylesheet" />
    <script src="<?= base_url('vertical/'); ?>assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('vertical/'); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('vertical/'); ?>assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="<?= base_url('vertical/'); ?>assets/css/app.css" rel="stylesheet">
    <link href="<?= base_url('vertical/'); ?>assets/css/icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" />
    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="<?= base_url('vertical/'); ?>assets/css/dark-theme.css" />
    <link rel="stylesheet" href="<?= base_url('vertical/'); ?>assets/css/semi-dark.css" />
    <link rel="stylesheet" href="<?= base_url('vertical/'); ?>assets/css/header-colors.css" />
    <title>SIMKU-PADUKA</title>
    <style>
        /* CSS overrides for Horizontal Layout only on Screen >= 992px */
        @media (min-width: 992px) {

            /* Hide the default sidebar wrapper */
            .sidebar-wrapper {
                display: none !important;
            }

            /* Make topbar full-width */
            .topbar {
                left: 0 !important;
                width: 100% !important;
                border-bottom: 1px solid #ededed !important;
                box-shadow: 0 2px 6px 0 rgb(218 218 253 / 15%), 0 0px 6px 0 rgb(206 206 238 / 10%) !important;
            }

            /* Adjust page content area to begin below double header */
            .page-wrapper {
                margin-left: 0 !important;
                margin-top: 110px !important;
                /* topbar (60px) + horizontal menu (50px) */
            }

            /* Adjust page footer */
            .page-footer {
                left: 0 !important;
            }

            /* Prevent toggled/menu expansion state shifts */
            .wrapper.toggled .topbar,
            .wrapper.toggled .page-wrapper,
            .wrapper.toggled .page-footer {
                left: 0 !important;
                margin-left: 0 !important;
            }

            /* Disable mobile hamburger trigger toggling functionality on desktop */
            .mobile-toggle-menu {
                display: none !important;
            }
        }

        @media (max-width: 991px) {
            .nav-container-horizontal {
                display: none !important;
            }
        }

        /* Stylings for the Horizontal Navigation Bar */
        .nav-container-horizontal {
            position: fixed;
            top: 60px;
            left: 0;
            width: 100%;
            height: 50px;
            background-color: #ffffff;
            z-index: 1000;
            border-bottom: 1px solid #ededed;
            box-shadow: 0 2px 6px 0 rgb(218 218 253 / 15%), 0 0px 6px 0 rgb(206 206 238 / 10%);
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
        }

        .nav-container-horizontal .horizontal-menu {
            display: flex;
            align-items: center;
            list-style: none;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        .nav-container-horizontal .horizontal-menu>li {
            position: relative;
        }

        .nav-container-horizontal .horizontal-menu>li>a {
            display: flex;
            align-items: center;
            height: 50px;
            color: #4c5258;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            padding: 0 15px;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease-in-out;
            white-space: nowrap;
        }

        .nav-container-horizontal .horizontal-menu>li>a i {
            font-size: 20px;
            margin-right: 6px;
            color: #8c9094;
            transition: color 0.2s ease-in-out;
        }

        /* Hover and Active highlights */
        .nav-container-horizontal .horizontal-menu>li:hover>a,
        .nav-container-horizontal .horizontal-menu>li.active>a {
            color: #008cff;
            border-bottom: 2px solid #008cff;
            background-color: rgba(13, 110, 253, 0.04);
        }

        .nav-container-horizontal .horizontal-menu>li:hover>a i,
        .nav-container-horizontal .horizontal-menu>li.active>a i {
            color: #008cff;
        }

        /* Dropdown custom styling */
        .nav-container-horizontal .horizontal-menu .dropdown-menu-custom {
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #ffffff;
            min-width: 220px;
            border-radius: 0 0 6px 6px;
            border: 1px solid #e5e9f2;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
            display: none;
            list-style: none;
            margin: 0;
            padding: 8px 0;
            z-index: 1001;
        }

        .nav-container-horizontal .horizontal-menu .dropdown-menu-custom li {
            width: 100%;
        }

        .nav-container-horizontal .horizontal-menu .dropdown-menu-custom li a {
            display: flex;
            align-items: center;
            padding: 8px 20px;
            color: #4c5258;
            text-decoration: none;
            font-size: 13.5px;
            transition: background 0.2s, color 0.2s;
        }

        .nav-container-horizontal .horizontal-menu .dropdown-menu-custom li a i {
            font-size: 16px;
            margin-right: 8px;
            color: #8c9094;
        }

        .nav-container-horizontal .horizontal-menu .dropdown-menu-custom li a:hover,
        .nav-container-horizontal .horizontal-menu .dropdown-menu-custom li.active a {
            background-color: #f0f7ff;
            color: #008cff;
        }

        .nav-container-horizontal .horizontal-menu .dropdown-menu-custom li a:hover i,
        .nav-container-horizontal .horizontal-menu .dropdown-menu-custom li.active a i {
            color: #008cff;
        }

        /* Hover triggers dropdown */
        @media (min-width: 992px) {
            .nav-container-horizontal .horizontal-menu>li:hover .dropdown-menu-custom {
                display: block;
                animation: navFadeIn 0.15s ease-in-out;
            }
        }

        @keyframes navFadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Extra styles to ensure layout looks stunning */
        .topbar-logo {
            display: flex;
            align-items: center;
            padding: 0 15px;
            height: 100%;
        }

        .topbar-logo .logo-text {
            font-size: 20px;
            font-weight: 700;
            color: #008cff;
            letter-spacing: 1px;
            margin: 0;
        }
    </style>
</head>

<body>
    <?php
    $controller = $this->router->fetch_class();   // mis. "pengajuan"
    $method     = $this->router->fetch_method();  // mis. "detail"

    $tgld = ($controller === 'pengajuan' && $method === 'detail') ? 'toggled' : '';
    ?>
    <!--wrapper-->
    <div class="wrapper <?= $tgld ?>">
        <!--sidebar wrapper -->
        <div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">
                <div>
                    <h4 class="logo-text">SIMKUPADUKA</h4>
                </div>
                <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
                </div>
            </div>
            <!--navigation-->
            <ul class="metismenu" id="menu">
                <li class="menu-label">Halaman Admin Lembaga</li>
                <li>
                    <a href="<?= base_url('lembaga'); ?>">
                        <div class="parent-icon"><i class='bx bx-home-circle'></i>
                        </div>
                        <div class="menu-title">Dashboard</div>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('rab'); ?>">
                        <div class="parent-icon"><i class='bx bx-wallet'></i>
                        </div>
                        <div class="menu-title">RAB</div>
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class='bx bx-shopping-bag'></i>
                        </div>
                        <div class="menu-title">Realisasi</div>
                    </a>
                    <ul>
                        <li> <a href="<?= base_url('lembaga/realis'); ?>"><i class="bx bx-right-arrow-alt"></i>Data</a>
                        </li>
                        <li>
                            <a href="<?= base_url('pengajuan'); ?>"><i class="bx bx-right-arrow-alt"></i>Pengajuan</a>
                        </li>
                        <?php if ($user->lembaga == '03'): ?>
                            <li>
                                <a href="<?= base_url('pengajuan/rencana'); ?>"><i class="bx bx-right-arrow-alt"></i>Verval Pengajuan</a>
                            </li>
                        <?php endif ?>
                    </ul>
                </li>
                <?php if ($user->lembaga == '03') { ?>
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='bx bx-notepad'></i>
                            </div>
                            <div class="menu-title">SPJ</div>
                        </a>
                        <ul>
                            <li> <a href="<?= base_url('lembaga/spj'); ?>"><i class="bx bx-right-arrow-alt"></i>SPJ Saya</a></li>
                            <li><a href="<?= base_url('lembaga/spjSs'); ?>"><i class="bx bx-right-arrow-alt"></i>Verval SPJ</a></li>
                        </ul>
                    </li>
                <?php } else { ?>
                    <li>
                        <a href="<?= base_url('lembaga/spj'); ?>">
                            <div class="parent-icon"><i class='bx bx-notepad'></i>
                            </div>
                            <div class="menu-title">SPJ</div>
                        </a>
                    </li>
                <?php } ?>
                <!-- <li>
                    <a href="<?= base_url('lembaga/disposisi'); ?>">
                        <div class="parent-icon"><i class='bx bx-money'></i>
                        </div>
                        <div class="menu-title">Disposisi</div>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('lembaga/pak'); ?>">
                        <div class="parent-icon"><i class='bx bx-note'></i>
                        </div>
                        <div class="menu-title">PAK Online</div>
                    </a>
                </li> -->
                <?php if ($user->lembaga === '27') : ?>
                    <li>
                        <a href="<?= base_url('lembaga/sarpras'); ?>">
                            <div class="parent-icon"><i class='bx bx-data'></i>
                            </div>
                            <div class="menu-title">Sarpras <span class="badge bg-danger">KHUSUS</span></div>
                        </a>
                    </li>
                <?php endif;  ?>
                <?php if ($user->lembaga === '20' || $user->level === 'admin' || $user->level === 'account') : ?>
                    <li>
                        <a href="<?= base_url('lembaga/cetakNota'); ?>">
                            <div class="parent-icon"><i class='bx bx-printer'></i>
                            </div>
                            <div class="menu-title">Cetak Nota</div>
                        </a>
                    </li>
                <?php endif;  ?>
                <?php if ($user->lembaga === '31') : ?>
                    <li>
                        <a href="<?= base_url('lembaga/haflah'); ?>">
                            <div class="parent-icon"><i class='bx bx-data'></i>
                            </div>
                            <div class="menu-title">Haflah <span class="badge bg-danger">KHUSUS</span></div>
                        </a>
                    </li>
                <?php endif;  ?>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class='bx bx-shopping-bag'></i>
                        </div>
                        <div class="menu-title">Gaji/Honor</div>
                    </a>
                    <ul>
                        <li>
                            <a href="<?= base_url('honor/jamkerja'); ?>"><i class="bx bx-right-arrow-alt"></i>Input Jam (PTTY)</a>
                        </li>
                        <li>
                            <a href="<?= base_url('honor/jamkaryawan'); ?>"><i class="bx bx-right-arrow-alt"></i>Input Kehadiran (Karyawan)</a>
                        </li>
                        <li>
                            <a href="<?= base_url('honor/potongan'); ?>"><i class="bx bx-right-arrow-alt"></i>Input Potongan</a>
                        </li>
                    </ul>
                </li>
                <li class="menu-label">AddOn</li>
                <li>
                    <a href="<?= base_url('lembaga/setting'); ?>">
                        <div class="parent-icon"><i class='bx bx-cog'></i>
                        </div>
                        <div class="menu-title">Pengaturan</div>
                    </a>
                </li>
                <!-- <li>
                    <a href="<?= base_url('lembaga/rab24'); ?>">
                        <div class="parent-icon"><i class='bx bx-data'></i>
                    </div>
                    <div class="menu-title">RAB 23/24 <span class="badge bg-danger">sementara</span></div>
                </a>
            </li> -->
                <?php if ($user->lembaga == '03'): ?>
                    <li>
                        <a href="<?= base_url('programs'); ?>">
                            <div class="parent-icon"><i class='bx bx-message-detail'></i>
                            </div>
                            <div class="menu-title">Promgram</div>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('lembaga/history'); ?>">
                            <div class="parent-icon"><i class='bx bx-history'></i>
                            </div>
                            <div class="menu-title">History Pengajuan</div>
                        </a>
                    </li>
                <?php endif ?>

            </ul>
            <!--end navigation-->
        </div>
        <!--end sidebar wrapper -->
        <!--start header -->
        <header>
            <div class="topbar d-flex align-items-center">
                <nav class="navbar navbar-expand">
                    <div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
                    </div>
                    <div class="topbar-logo d-none d-lg-flex align-items-center me-3">
                        <h4 class="logo-text">SIMKUPADUKA</h4>
                    </div>
                    <div class="search-bar flex-grow-1">
                        <div class="position-relative search-bar-box">
                            <input type="text" class="form-control search-control" value="Tahun Pelajaran <?= $tahun; ?>" readonly>
                            <span class="position-absolute top-50 search-show translate-middle-y"><i class='bx bx-search'></i></span>
                            <span class="position-absolute top-50 search-close translate-middle-y"><i class='bx bx-x'></i></span>
                        </div>
                    </div>
                    <div class="top-menu ms-auto">
                        <ul class="navbar-nav align-items-center">
                            <li class="nav-item mobile-search-icon">
                                <a class="nav-link" href="#"> <i class='bx bx-search'></i>
                                </a>
                            </li>

                            <?php if ($user->level === 'admin') : ?>
                                <li class="nav-item">
                                    <button class="btn btn-sm button-primary" data-bs-toggle="modal" data-bs-target="#mdPindah"><i class="bx bx-desktop"></i>Pindah Akun</button>
                                </li>
                            <?php endif ?>

                            <li class="nav-item dropdown dropdown-large">
                                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count">7</span>
                                    <i class='bx bx-bell'></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="javascript:;">
                                        <div class="msg-header">
                                            <p class="msg-header-title">Notifications</p>
                                            <p class="msg-header-clear ms-auto">Marks all as read</p>
                                        </div>
                                    </a>
                                    <div class="header-notifications-list">
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-primary text-primary"><i class="bx bx-group"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">New Customers<span class="msg-time float-end">14 Sec
                                                            ago</span></h6>
                                                    <p class="msg-info">5 new user registered</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-danger text-danger"><i class="bx bx-cart-alt"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">New Orders <span class="msg-time float-end">2
                                                            min
                                                            ago</span></h6>
                                                    <p class="msg-info">You have recived new orders</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-success text-success"><i class="bx bx-file"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">24 PDF File<span class="msg-time float-end">19
                                                            min
                                                            ago</span></h6>
                                                    <p class="msg-info">The pdf files generated</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-warning text-warning"><i class="bx bx-send"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Time Response <span class="msg-time float-end">28 min
                                                            ago</span></h6>
                                                    <p class="msg-info">5.1 min avarage time response</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-info text-info"><i class="bx bx-home-circle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">New Product Approved <span class="msg-time float-end">2 hrs ago</span></h6>
                                                    <p class="msg-info">Your new product has approved</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-danger text-danger"><i class="bx bx-message-detail"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">New Comments <span class="msg-time float-end">4
                                                            hrs
                                                            ago</span></h6>
                                                    <p class="msg-info">New customer comments recived</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-success text-success"><i class='bx bx-check-square'></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Your item is shipped <span class="msg-time float-end">5 hrs
                                                            ago</span></h6>
                                                    <p class="msg-info">Successfully shipped your item</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-primary text-primary"><i class='bx bx-user-pin'></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">New 24 authors<span class="msg-time float-end">1 day
                                                            ago</span></h6>
                                                    <p class="msg-info">24 new authors joined last week</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-warning text-warning"><i class='bx bx-door-open'></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Defense Alerts <span class="msg-time float-end">2 weeks
                                                            ago</span></h6>
                                                    <p class="msg-info">45% less alerts last 4 weeks</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <a href="javascript:;">
                                        <div class="text-center msg-footer">View All Notifications</div>
                                    </a>
                                </div>
                            </li>
                            <li class="nav-item dropdown dropdown-large">
                                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class='bx bx-comment'></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="javascript:;">
                                        <div class="msg-header">
                                            <p class="msg-header-title">Messages</p>
                                            <p class="msg-header-clear ms-auto">Marks all as read</p>
                                        </div>
                                    </a>
                                    <div class="header-message-list">

                                    </div>
                                    <a href="javascript:;">
                                        <div class="text-center msg-footer">View All Messages</div>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="user-box dropdown">
                        <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php if ($user->foto != '') { ?>
                                <img src="<?= base_url('vertical/assets/uploads/profile/' . $user->foto); ?>" class="user-img" alt="user avatar">
                            <?php } else { ?>
                                <img src="<?= base_url('vertical/assets/uploads/profile/user-avatar.png'); ?>" class="user-img" alt="user avatar">
                            <?php } ?>

                            <div class="user-info ps-3">
                                <p class="user-name mb-0"><?= $user->nama; ?></p>
                                <p class="designattion mb-0"><?= $user->level; ?></p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="javascript:;"><i class="bx bx-user"></i><span>Profile</span></a>
                            </li>
                            <li><a class="dropdown-item" href="<?= base_url(); ?>"><i class='bx bx-home-circle'></i><span>Dashboard</span></a>
                            </li>
                            <li>
                                <div class="dropdown-divider mb-0"></div>
                            </li>
                            <li><a class="dropdown-item tbl-confirm" href="<?= base_url('login/logout'); ?>" value="Anda akan keluar dari aplikasi"><i class='bx bx-log-out-circle'></i><span>Logout</span></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>

            <!-- Horizontal Navigation Menu (Visible only on desktop screens) -->
            <div class="nav-container-horizontal d-none d-lg-flex">
                <ul class="horizontal-menu">
                    <li>
                        <a href="<?= base_url('lembaga'); ?>">
                            <i class='bx bx-home-circle'></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('rab'); ?>">
                            <i class='bx bx-wallet'></i>
                            <span>RAB</span>
                        </a>
                    </li>
                    <li class="dropdown-item-custom">
                        <a href="javascript:;" class="has-arrow-custom">
                            <i class='bx bx-shopping-bag'></i>
                            <span>Realisasi <i class='bx bx-chevron-down ms-1' style="font-size: 12px; vertical-align: middle;"></i></span>
                        </a>
                        <ul class="dropdown-menu-custom">
                            <li><a href="<?= base_url('lembaga/realis'); ?>"><i class="bx bx-right-arrow-alt"></i>Data</a></li>
                            <li><a href="<?= base_url('pengajuan'); ?>"><i class="bx bx-right-arrow-alt"></i>Pengajuan</a></li>
                            <?php if ($user->lembaga == '03'): ?>
                                <li><a href="<?= base_url('pengajuan/rencana'); ?>"><i class="bx bx-right-arrow-alt"></i>Verval Pengajuan</a></li>
                            <?php endif ?>
                        </ul>
                    </li>
                    <?php if ($user->lembaga == '03') { ?>
                        <li class="dropdown-item-custom">
                            <a href="javascript:;" class="has-arrow-custom">
                                <i class='bx bx-notepad'></i>
                                <span>SPJ <i class='bx bx-chevron-down ms-1' style="font-size: 12px; vertical-align: middle;"></i></span>
                            </a>
                            <ul class="dropdown-menu-custom">
                                <li><a href="<?= base_url('lembaga/spj'); ?>"><i class="bx bx-right-arrow-alt"></i>SPJ Saya</a></li>
                                <li><a href="<?= base_url('lembaga/spjSs'); ?>"><i class="bx bx-right-arrow-alt"></i>Verval SPJ</a></li>
                            </ul>
                        </li>
                    <?php } else { ?>
                        <li>
                            <a href="<?= base_url('lembaga/spj'); ?>">
                                <i class='bx bx-notepad'></i>
                                <span>SPJ</span>
                            </a>
                        </li>
                    <?php } ?>
                    <?php if ($user->lembaga === '27') : ?>
                        <li>
                            <a href="<?= base_url('lembaga/sarpras'); ?>">
                                <i class='bx bx-data'></i>
                                <span>Sarpras <span class="badge bg-danger">KHUSUS</span></span>
                            </a>
                        </li>
                    <?php endif;  ?>
                    <?php if ($user->lembaga === '20' || $user->level === 'admin' || $user->level === 'account') : ?>
                        <li>
                            <a href="<?= base_url('lembaga/cetakNota'); ?>">
                                <i class='bx bx-printer'></i>
                                <span>Cetak Nota</span>
                            </a>
                        </li>
                    <?php endif;  ?>
                    <?php if ($user->lembaga === '31') : ?>
                        <li>
                            <a href="<?= base_url('lembaga/haflah'); ?>">
                                <i class='bx bx-data'></i>
                                <span>Haflah <span class="badge bg-danger">KHUSUS</span></span>
                            </a>
                        </li>
                    <?php endif;  ?>
                    <li class="dropdown-item-custom">
                        <a href="javascript:;" class="has-arrow-custom">
                            <i class='bx bx-shopping-bag'></i>
                            <span>Gaji/Honor <i class='bx bx-chevron-down ms-1' style="font-size: 12px; vertical-align: middle;"></i></span>
                        </a>
                        <ul class="dropdown-menu-custom">
                            <li><a href="<?= base_url('honor/jamkerja'); ?>"><i class="bx bx-right-arrow-alt"></i>Input Jam (PTTY)</a></li>
                            <li><a href="<?= base_url('honor/jamkaryawan'); ?>"><i class="bx bx-right-arrow-alt"></i>Input Kehadiran (Karyawan)</a></li>
                            <li><a href="<?= base_url('honor/potongan'); ?>"><i class="bx bx-right-arrow-alt"></i>Input Potongan</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="<?= base_url('lembaga/setting'); ?>">
                            <i class='bx bx-cog'></i>
                            <span>Pengaturan</span>
                        </a>
                    </li>
                    <?php if ($user->lembaga == '03'): ?>
                        <li>
                            <a href="<?= base_url('programs'); ?>">
                                <i class='bx bx-message-detail'></i>
                                <span>Program</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('lembaga/history'); ?>">
                                <i class='bx bx-history'></i>
                                <span>History Pengajuan</span>
                            </a>
                        </li>
                    <?php endif ?>
                </ul>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    var currentUrl = window.location.href;
                    // Remove trailing slashes and hashes for robust comparison
                    var cleanUrl = currentUrl.replace(/\/+$/, "").split('#')[0];

                    var menuLinks = document.querySelectorAll('.horizontal-menu a');
                    menuLinks.forEach(function(link) {
                        var linkUrl = link.href.replace(/\/+$/, "").split('#')[0];
                        if (linkUrl === cleanUrl) {
                            link.classList.add('active');
                            // Add active to nearest parent li
                            var parentLi = link.closest('.horizontal-menu > li');
                            if (parentLi) {
                                parentLi.classList.add('active');
                            }
                            // Add active to dropdown triggers if inside dropdown
                            var parentDropdown = link.closest('.dropdown-menu-custom');
                            if (parentDropdown) {
                                var triggerLi = parentDropdown.closest('.dropdown-item-custom');
                                if (triggerLi) {
                                    triggerLi.classList.add('active');
                                }
                            }
                        }
                    });
                });
            </script>
        </header>

        <?php if ($user->level === 'admin') : ?>
            <div class="modal fade" id="mdPindah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Changes</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <?= form_open('admin/changeAkses');  ?>
                        <div class="modal-body">
                            <?php
                            $data = $this->db->query("SELECT * FROM lembaga WHERE tahun = '$tahun' ")->result();
                            $currData = $this->db->query("SELECT * FROM user WHERE id_user = '$user->id_user' ")->row();
                            ?>
                            <input type="hidden" name="id" value="<?= $currData->id_user ?>">
                            <div class="form-group">
                                <label for="">Pilih Lembaga</label>
                                <select name="lembaga" id="" class="form-select" required>
                                    <?php foreach ($data as $lm) : ?>
                                        <option <?= $currData->lembaga == $lm->kode ? 'selected' : '' ?> value="<?= $lm->kode ?>"><?= $lm->kode . '. ' . $lm->nama ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="form-group mt-2">
                                <label for="">Tujuan Akun</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="level" value="admin" id="admin" required>
                                    <label class="form-check-label" for="admin">Admin</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="level" value="account" id="account" required>
                                    <label class="form-check-label" for="account">Accounting</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="level" value="kasir" id="kasir" required>
                                    <label class="form-check-label" for="kasir">Kasir</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="level" value="lembaga" id="lembaga" required>
                                    <label class="form-check-label" for="lembaga">KPA Lembaga</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Pindah</button>
                        </div>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        <?php endif ?>
        <!--end header -->
        <div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
        <div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>
        <div class="flash-data-info" data-flashdata="<?= $this->session->flashdata('info') ?>"></div>
        <div class="flash-data-warning" data-flashdata="<?= $this->session->flashdata('warning') ?>"></div>