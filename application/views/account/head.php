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
    <!-- loader-->
    <link href="<?= base_url('vertical/'); ?>assets/css/pace.min.css" rel="stylesheet" />
    <script src="<?= base_url('vertical/'); ?>assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="<?= base_url('vertical/'); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('vertical/'); ?>assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="<?= base_url('vertical/'); ?>assets/css/app.css" rel="stylesheet">
    <link href="<?= base_url('vertical/'); ?>assets/css/icons.css" rel="stylesheet">
    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="<?= base_url('vertical/'); ?>assets/css/dark-theme.css" />
    <link rel="stylesheet" href="<?= base_url('vertical/'); ?>assets/css/semi-dark.css" />
    <link rel="stylesheet" href="<?= base_url('vertical/'); ?>assets/css/header-colors.css" />
    <title>SIMKU-PADUKA</title>
</head>

<body>
    <!--wrapper-->
    <div class="wrapper">
        <!--sidebar wrapper -->
        <div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">
                <div>
                    <!-- <img src="<?= base_url('vertical/'); ?>assets/images/logo-icon.png" class="logo-icon" alt="logo icon"> -->
                </div>
                <div>
                    <h4 class="logo-text">SIMKUPADUKA</h4>
                </div>
                <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
                </div>
            </div>
            <!--navigation-->
            <ul class="metismenu" id="menu">
                <li class="menu-label">Halaman Accounting</li>
                <li>
                    <a href="<?= base_url('account'); ?>">
                        <div class="parent-icon"><i class='bx bx-home-circle'></i>
                        </div>
                        <div class="menu-title">Dashboard</div>
                    </a>
                </li>

                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class='bx bx-money'></i>
                        </div>
                        <div class="menu-title">Pemasukan</div>
                    </a>
                    <ul>
                        <li> <a href="<?= base_url('account/pesantren'); ?>"><i class="bx bx-right-arrow-alt"></i>Pemasukan
                                Pesantren</a>
                        </li>
                        <li>
                            <a href="<?= base_url('account/bos'); ?>"><i class="bx bx-right-arrow-alt"></i>Pemasukan
                                BOS</a>
                        </li>
                        <li>
                            <a href="<?= base_url('account/sisa'); ?>"><i class="bx bx-right-arrow-alt"></i>Saldo
                                Realisasi</a>
                        </li>
                        <li>
                            <a href="<?= base_url('account/bpMasuk'); ?>"><i class="bx bx-right-arrow-alt"></i>Biaya
                                Pendidikan</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class='bx bx-shopping-bag'></i>
                        </div>
                        <div class="menu-title">Rencana Belanja</div>
                    </a>
                    <ul>
                        <li> <a href="<?= base_url('account/rab'); ?>"><i class="bx bx-right-arrow-alt"></i>RAB
                                Lembaga</a>
                        </li>
                        <li>
                            <a href="<?= base_url('account/rab_kbj'); ?>"><i class="bx bx-right-arrow-alt"></i>RAB
                                Kebijakan</a>
                        </li>
                        <li>
                            <a href="<?= base_url('account/pak'); ?>"><i class="bx bx-right-arrow-alt"></i>PAK</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class='bx bx-wallet'></i>
                        </div>
                        <div class="menu-title">Realisasi</div>
                    </a>
                    <ul>
                        <li> <a href="<?= base_url('account/realis'); ?>"><i class="bx bx-right-arrow-alt"></i>Data</a>
                        </li>
                        <li>
                            <a href="<?= base_url('account/pengajuan'); ?>"><i class="bx bx-right-arrow-alt"></i>Pengajuan</a>
                        </li>
                        <li>
                            <a href="<?= base_url('account/spj'); ?>"><i class="bx bx-right-arrow-alt"></i>SPJ</a>
                        </li>
                        <li>
                            <a href="<?= base_url('account/lain'); ?>"><i class="bx bx-right-arrow-alt"></i>Pengeluaran Lain</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class='bx bx-coin-stack'></i>
                        </div>
                        <div class="menu-title">Disposisi</div>
                    </a>
                    <ul>
                        <li> <a href="<?= base_url('account/disposisi'); ?>"><i class="bx bx-right-arrow-alt"></i>Data
                                Disposisi</a>
                        </li>
                        <li>
                            <a href="#"><i class="bx bx-right-arrow-alt"></i>Pengembalian</a>
                        </li>
                    </ul>
                </li>
                <li class="menu-label">AddOn</li>
                <li>
                    <a href="<?= base_url('account/info'); ?>">
                        <div class="parent-icon"><i class='bx bx-message-detail'></i>
                        </div>
                        <div class="menu-title">Informasi</div>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('account/history'); ?>">
                        <div class="parent-icon"><i class='bx bx-history'></i>
                        </div>
                        <div class="menu-title">History Pengajuan</div>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('account/rab24'); ?>">
                        <div class="parent-icon"><i class='bx bx-data'></i>
                        </div>
                        <div class="menu-title">RAB 23/24 <span class="badge bg-danger">sementara</span></div>
                    </a>
                </li>
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

                            <li class="nav-item dropdown dropdown-large">
                                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count"><?= $pjnData->num_rows() ?></span>
                                    <i class='bx bx-bell'></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="javascript:;">
                                        <div class="msg-header">
                                            <p class="msg-header-title">Pengajuan</p>
                                            <p class="msg-header-clear ms-auto">-</p>
                                        </div>
                                    </a>
                                    <div class="header-notifications-list">
                                        <?php foreach ($pjnData->result() as $data) :
                                            $lmb = $this->db->query("SELECT * FROM lembaga WHERE kode = '$data->lembaga' AND tahun = '$tahun' ")->row();
                                        ?>
                                            <a class="dropdown-item" href="<?= base_url('account/pengajuanDtl/' . $data->kode_pengajuan) ?>">
                                                <div class="d-flex align-items-center">
                                                    <div class="notify bg-light-primary text-primary"><i class="bx bx-wallet"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="msg-name"><?= $data->kode_pengajuan ?><span class="msg-time float-end"><?= date('d-m-Y H:i', strtotime($data->at)) ?></span></h6>
                                                        <p class="msg-info"><?= $lmb->nama ?></p>
                                                    </div>
                                                </div>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                    <a href="<?= base_url('account/pengajuan') ?>">
                                        <div class="text-center msg-footer">Lihat semua pengajuan</div>
                                    </a>
                                </div>
                            </li>
                            <li class="nav-item dropdown dropdown-large">
                                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count"><?= $spjData->num_rows() ?></span>
                                    <i class='bx bx-message-detail'></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="javascript:;">
                                        <div class="msg-header">
                                            <p class="msg-header-title">Pengajuan SPJ</p>
                                            <!-- <p class="msg-header-clear ms-auto">Marks all as read</p> -->
                                        </div>
                                    </a>
                                    <div class="header-message-list">
                                        <?php foreach ($spjData->result() as $data) :
                                            $lmb = $this->db->query("SELECT * FROM lembaga WHERE kode = '$data->lembaga' AND tahun = '$tahun' ")->row();
                                        ?>
                                            <a class="dropdown-item" href="<?= base_url('account/viewSpj/' . $data->kode_pengajuan) ?>">
                                                <div class="d-flex align-items-center">
                                                    <div class="notify bg-light-primary text-primary"><i class="bx bx-notepad"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="msg-name">SPJ - <?= $data->kode_pengajuan ?><span class="msg-time float-end"><?= $data->tgl_upload ?></span></h6>
                                                        <p class="msg-info"><?= $lmb->nama ?></p>
                                                    </div>
                                                </div>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                    <a href="<?= base_url('account/spj') ?>">
                                        <div class="text-center msg-footer">Lihat semua SPJ</div>
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
                            <li><a class="dropdown-item" href="<?= base_url('account/setting') ?>"><i class="bx bx-user"></i><span>Profile</span></a>
                            </li>
                            <li><a class="dropdown-item" href="<?= base_url('account'); ?>"><i class='bx bx-home-circle'></i><span>Dashboard</span></a>
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
        </header>
        <!--end header -->