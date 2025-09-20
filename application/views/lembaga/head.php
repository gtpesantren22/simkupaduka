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
                <?php if ($user->lembaga === '20') : ?>
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