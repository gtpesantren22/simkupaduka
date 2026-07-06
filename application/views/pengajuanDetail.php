<?php
require 'lembaga/head.php';
?>
<link href="<?= base_url(''); ?>assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<link href="<?= base_url(''); ?>assets/plugins/select2/css/select2.min.css" rel="stylesheet" />
<link href="<?= base_url(''); ?>assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<!-- Start right Content here -->
<!-- ============================================================== -->
<style>
    body {
        background-color: #f5f5f5;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .shopee-header {
        background-color: #EE4D2D;
        color: white;
        border-radius: 12px 12px 0 0;
    }

    .status-card {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border: none;
        margin-bottom: 20px;
    }

    .step-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
        font-size: 20px;
    }

    .step-icon-active {
        background-color: #EE4D2D;
        color: white;
        border: 3px solid #EE4D2D;
    }

    .step-icon-completed {
        background-color: #EE4D2D;
        color: white;
        border: 3px solid #EE4D2D;
    }

    .step-icon-inactive {
        background-color: white;
        color: #9E9E9E;
        border: 3px solid #E0E0E0;
    }

    .step-active {
        color: #EE4D2D;
        font-weight: bold;
    }

    .step-inactive {
        color: #9E9E9E;
    }

    .progress-connector {
        height: 3px;
        flex-grow: 1;
        margin: 0 10px;
        margin-top: 23px;
    }

    .detail-list {
        border-left: 2px dashed #EE4D2D;
        margin-left: 10px;
        padding-left: 20px;
    }

    .tracking-map {
        border-radius: 10px;
        height: 200px;
        background: linear-gradient(rgba(238, 77, 45, 0.1), rgba(238, 77, 45, 0.05));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        border: 1px dashed #EE4D2D;
    }

    .btn-orange {
        background-color: #EE4D2D;
        color: white;
    }

    .btn-orange:hover {
        background-color: #d84327;
        color: white;
    }

    .btn-outline-orange {
        border-color: #EE4D2D;
        color: #EE4D2D;
    }

    .btn-outline-orange:hover {
        background-color: #EE4D2D;
        color: white;
    }

    .latest-status {
        font-weight: 700;
        color: #EE4D2D;
    }

    .product-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
    }

    .status-badge {
        background-color: #FFF0EE;
        color: #EE4D2D;
        border-radius: 20px;
        padding: 5px 12px;
        font-weight: 500;
    }

    .text-orange {
        color: #EE4D2D !important;
    }

    .bg-orange {
        background-color: #EE4D2D !important;
    }

    .bg-orange-subtle {
        background-color: #FFF0EE !important;
    }

    .border-orange {
        border-color: #EE4D2D !important;
    }

    .letter-spacing-1 {
        letter-spacing: 1px;
    }

    .input-shadow:focus {
        box-shadow: 0 0 0 0.25rem rgba(238, 77, 45, 0.25);
        border-color: #EE4D2D;
    }

    /* RAB Item Cards styling */
    .rab-card {
        background: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 10px 12px;
        margin-bottom: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .rab-card:hover {
        border-color: #EE4D2D;
        background-color: #FFF0EE;
        box-shadow: 0 2px 8px rgba(238, 77, 45, 0.1);
        transform: translateY(-1px);
    }

    .rab-card.selected {
        border-color: #EE4D2D;
        background-color: #FFF0EE;
        box-shadow: 0 0 0 2px rgba(238, 77, 45, 0.2);
    }

    .rab-card .rab-title {
        font-weight: 600;
        font-size: 13px;
        color: #212529;
        margin-bottom: 4px;
        line-height: 1.3;
    }

    .rab-card .rab-meta {
        font-size: 11px;
        color: #6c757d;
        display: flex;
        justify-content: space-between;
    }

    /* Tunai specific hover to keep colors in harmony */
    .rab-card-tunai:hover {
        border-color: #198754 !important;
        background-color: #f4fcf7 !important;
        box-shadow: 0 2px 8px rgba(25, 135, 84, 0.1) !important;
    }

    .rab-card-tunai.selected {
        border-color: #198754 !important;
        background-color: #f4fcf7 !important;
        box-shadow: 0 0 0 2px rgba(25, 135, 84, 0.2) !important;
    }

    /* Horizontal Dashboard Styling */
    .fs-11 {
        font-size: 11px !important;
    }

    .fs-13 {
        font-size: 13px !important;
    }

    .fs-14 {
        font-size: 14px !important;
    }

    .fs-12 {
        font-size: 12px !important;
    }

    @media (min-width: 768px) {
        .border-end-md {
            border-right: 1px solid #eff2f7 !important;
        }
    }

    @media (min-width: 992px) {
        .border-end-lg {
            border-right: 1px solid #eff2f7 !important;
        }
    }
</style>
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Pengajuan</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-folder-open"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Detail Pengajuan</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <!-- New Horizontal Information Dashboard -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card shadow-sm border-0 mb-0" style="border-radius: 12px; background: #ffffff;">
                    <div class="card-body p-3">
                        <div class="row align-items-center g-3">
                            <!-- Left: Status Badges -->
                            <div class="col-12 col-lg-5 border-end-lg">
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <div>
                                        <small class="text-muted d-block fw-bold text-uppercase fs-11 mb-1">Status Pengajuan</small>
                                        <?= $pj->stts == 'yes' ? "<span class='badge bg-success px-3 py-2 fs-13' style='border-radius: 6px;'><i class='bx bx-message-square-check me-1'></i> Diajukan</span>" : "<span class='badge bg-danger px-3 py-2 fs-13' style='border-radius: 6px;'><i class='bx bx-message-square-x me-1'></i> Belum</span>" ?>
                                    </div>
                                    <div class="vr bg-light opacity-50" style="height: 35px;"></div>
                                    <div>
                                        <small class="text-muted d-block fw-bold text-uppercase fs-11 mb-1">WA Gateway</small>
                                        <?= $statusWA['results']['state'] == 'CONNECTED' ? "<span class='badge bg-success px-3 py-2 fs-13' style='border-radius: 6px;'><i class='bx bx-wifi me-1'></i> Online</span>" : "<span class='badge bg-danger px-3 py-2 fs-13' style='border-radius: 6px;'><i class='bx bx-wifi-off me-1'></i> Terputus</span>" ?>
                                    </div>
                                    <div class="vr bg-light opacity-50" style="height: 35px;"></div>
                                    <div>
                                        <small class="text-muted d-block fw-bold text-uppercase fs-11 mb-1">Total Pengajuan</small>
                                        <span class="badge bg-orange text-white px-3 py-2 fs-13 fw-bold" style="border-radius: 6px; box-shadow: 0 4px 6px rgba(238, 77, 45, 0.15);" id="total-pengajuan-top">Rp 0</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Middle: Latest Tracking Message -->
                            <div class="col-12 col-lg-4 border-end-lg">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-orange bg-opacity-10 text-orange rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                        <i class="bx bx-git-commit fs-4"></i>
                                    </div>
                                    <div style="min-width: 0;">
                                        <small class="text-muted d-block fw-bold text-uppercase fs-11">Aktivitas Terakhir</small>
                                        <?php
                                        if (!empty($history)) {
                                            $latest = $history[0];
                                            $latestDate = new DateTime($latest->tgl_verval);
                                            echo '<span class="fw-semibold text-dark text-truncate d-block fs-14" title="' . $latest->pesan . '">' . $latest->pesan . '</span>';
                                            echo '<small class="text-muted fs-12">' . $latestDate->format("d M Y, H:i") . ' oleh ' . ($latest->user ?? 'Sistem') . '</small>';
                                        } else {
                                            echo '<span class="fw-semibold text-dark fs-14">- Belum ada riwayat -</span>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Actions & Notes -->
                            <div class="col-12 col-lg-3 text-lg-end">
                                <div class="d-flex flex-column flex-md-row align-items-lg-center justify-content-lg-end gap-2">
                                    <?php if ($pj->stts != 'yes'): ?>
                                        <a href="<?= base_url('pengajuan/ajukan/' . $pj->kode_pengajuan) ?>" class="btn btn-sm btn-success tbl-confirm shadow-sm d-inline-flex align-items-center gap-2 justify-content-center" value="Pengajuan akan dilanjutkan ke Bendahara dan Perencanaan" style="border-radius: 8px; padding: 7px 15px; font-weight: 600; font-size: 13px; min-height: 35px;">
                                            <i class="bx bx-paper-plane"></i> Ajukan ke Bendahara
                                        </a>
                                    <?php endif; ?>
                                    <button class="btn btn-outline-secondary btn-sm px-3 py-2 d-flex align-items-center gap-1 justify-content-center" style="border-radius: 8px; min-height: 35px;" type="button" data-bs-toggle="collapse" data-bs-target="#historyCollapse" aria-expanded="false" aria-controls="historyCollapse">
                                        <i class="bx bx-history"></i> Riwayat Log
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Collapsible Timeline Log -->
                        <div class="collapse mt-3" id="historyCollapse">
                            <hr class="my-3 opacity-25">
                            <h6 class="fw-bold text-dark mb-3"><i class="bx bx-list-ol text-orange me-1"></i> Detail Perjalanan Pengajuan</h6>
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">
                                <?php
                                $stepIdx = 1;
                                foreach (array_reverse($history) as $hst):
                                    $date = new DateTime($hst->tgl_verval);
                                ?>
                                    <div class="col">
                                        <div class="p-3 border rounded h-100 bg-light bg-opacity-50" style="border-radius: 8px !important;">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <span class="badge bg-secondary-subtle text-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 22px; height: 22px; font-size: 11px;"><?= $stepIdx++ ?></span>
                                                <small class="text-muted fw-semibold"><?= $date->format("d M Y, H:i"); ?></small>
                                            </div>
                                            <h6 class="mb-1 text-dark fs-13"><?= $hst->pesan ?></h6>
                                            <small class="text-muted d-block fs-11">User: <?= $hst->user ?? 'System' ?></small>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                            <div class="mt-2 text-muted" style="font-size: 11px;">
                                <i class="bx bx-info-circle text-orange"></i> Pastikan status WA Gateway <b>Online</b> agar pesan notifikasi terkirim otomatis ke Bendahara & Perencanaan.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card" id="orderList">
                    <div class="card-header align-items-xl-center d-xl-flex">
                        <h5 class="card-title mb-0 flex-grow-1 mb-xl-0">List Pengajuan</h5>
                        <div class="flex-shrink-0">
                            <ul class="nav nav-tabs nav-primary" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#barang" role="tab" aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-box font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Item Barang</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tunai" role="tab" aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-money font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Item Tunai</div>
                                        </div>
                                    </a>
                                </li>
                            </ul>

                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Tab panes -->
                        <div class="tab-content text-muted">

                            <!-- Form Barang -->
                            <div class="tab-pane" id="barang" role="tabpanel">
                                <form action="" id="form-barang">
                                    <input type="hidden" name="kode_pengajuan" value="<?= $pj->kode_pengajuan ?>">
                                    <div class="row g-3">
                                        <!-- Card 1: Pilih Program, Kegiatan, COA & Inputs -->
                                        <div class="col-lg-4">
                                            <div class="card shadow-sm border-0 mb-0 h-100" style="border-radius: 12px; background-color: #fdfdfd;">
                                                <div class="card-body p-4">
                                                    <h6 class="text-uppercase text-muted fw-bold mb-4"><i class="bx bx-layer text-orange me-1"></i> 1. Kegiatan & Input</h6>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-dark">Pilih Program</label>
                                                        <select class="js-example-basic-single w-100" name="program" id="program" required>
                                                            <option value="">pilih program</option>
                                                            <?php foreach ($program as $program1): ?>
                                                                <option value="<?= $program1->kode_program ?>"><?= $program1->kode_program . '. ' . $program1->program ?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3" id="kegiatan-barang-wrapper" style="display:none;">
                                                        <label class="form-label fw-bold text-dark">Pilih Kegiatan</label>
                                                        <select class="form-select" name="kegiatan_dppk" id="kegiatan-barang" style="border-radius: 8px;">
                                                            <option value="">pilih kegiatan</option>
                                                        </select>
                                                        <small class="text-muted mt-1 d-block" id="kegiatan-info-barang"></small>
                                                    </div>

                                                    <input type="hidden" class="fr-kegiatan" name="kegiatan">

                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-dark">Pilih Akun (COA)</label>
                                                        <div class="row g-2">
                                                            <div class="col-6">
                                                                <select class="js-example-basic-single select2-standalone w-100" id="p-coa" required>
                                                                    <option value="">pilih parent</option>
                                                                    <?php foreach ($coa as $coa1):
                                                                        if ($coa1->parrent == '') { ?>
                                                                            <option value="<?= $coa1->kode ?>"><b><?= $coa1->kode . ' ' . $coa1->nama ?></b></option>
                                                                    <?php }
                                                                    endforeach ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-6">
                                                                <select class="js-example-basic-single select-dependent w-100" id="c-coa" name="coa" required>
                                                                    <option value="">pilih sub-akun</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-dark">Pilih Item (SSH)</label>
                                                        <div class="d-flex gap-2">
                                                            <div class="flex-grow-1">
                                                                <select class="js-example-basic-single select-dependent w-100" id="item-ssh" name="ssh" required>
                                                                    <option value="">pilih item</option>
                                                                </select>
                                                            </div>
                                                            <button type="button" data-bs-toggle="modal" data-bs-target="#inputno-modal" class="btn btn-outline-orange d-inline-flex align-items-center gap-1" style="border-radius: 8px; padding: 0 15px; white-space: nowrap; height: 38px;"><i class="bx bx-plus"></i> Manual</button>
                                                        </div>
                                                    </div>

                                                    <div class="mb-0">
                                                        <label class="form-label fw-bold text-dark">Jumlah (qty)</label>
                                                        <input type="number" class="form-control bg-light border-0 px-3 py-2 input-shadow" id="input-qty" name="qty" placeholder="Masukan jumlah/qty" disabled required style="border-radius: 8px;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Card 3: Preview & Submit -->
                                        <div class="col-lg-4 flex-grow-1">
                                            <div class="card shadow-sm border-0 mb-0 h-100" style="border-radius: 12px; background: linear-gradient(135deg, #EE4D2D, #FF7B54); box-shadow: 0 10px 20px rgba(238, 77, 45, 0.2);">
                                                <div class="card-body p-4 d-flex flex-column position-relative z-1">
                                                    <h5 class="text-white mb-4 fw-bold"><i class="bx bx-receipt me-2"></i>Preview & Tambah</h5>

                                                    <div class="mb-4 bg-white bg-opacity-10 rounded p-3" style="backdrop-filter: blur(5px);">
                                                        <p class="text-white-50 mb-1 fs-12 text-uppercase fw-bold letter-spacing-1">Akun COA Terpilih</p>
                                                        <h6 class="text-white mb-1" id="coa-p-desc">-</h6>
                                                        <p class="text-white-50 mb-0 fs-13" id="coa-c-desc">-</p>
                                                    </div>

                                                    <div class="bg-white rounded p-3 mb-auto shadow-sm">
                                                        <div class="d-flex align-items-start mb-3">
                                                            <div class="bg-orange bg-opacity-10 text-orange rounded d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; flex-shrink: 0;">
                                                                <i class="bx bx-package fs-5"></i>
                                                            </div>
                                                            <div class="flex-grow-1" style="min-width: 0;">
                                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                                    <span class="badge bg-orange-subtle text-orange px-2 py-0.5 border border-orange" id="kategori" style="font-size: 10px;">Kategori</span>
                                                                    <small class="text-muted" id="satuan">-</small>
                                                                </div>
                                                                <h6 class="text-dark mb-0 fw-bold text-truncate" id="nama-item" style="line-height: 1.3; font-size: 13px;">Nama Barang</h6>
                                                                <small class="text-muted d-block text-truncate" id="nama-kategori" style="font-size: 11px;">-</small>
                                                            </div>
                                                        </div>
                                                        <hr class="border-secondary opacity-25 my-3">
                                                        <div class="row text-center">
                                                            <div class="col-4 border-end">
                                                                <small class="text-muted d-block fw-semibold mb-1">Harga</small>
                                                                <span class="fw-bold text-dark fs-12" id="harga">-</span>
                                                            </div>
                                                            <div class="col-4 border-end">
                                                                <small class="text-muted d-block fw-semibold mb-1">Satuan</small>
                                                                <span class="fw-bold text-dark fs-12" id="satuan">-</span>
                                                            </div>
                                                            <div class="col-4">
                                                                <small class="text-muted d-block fw-semibold mb-1">Qty</small>
                                                                <span class="fw-bold text-dark fs-12" id="qty">-</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="bg-white rounded p-3 text-dark d-flex justify-content-between align-items-center mt-3 shadow-lg border border-orange">
                                                        <div>
                                                            <small class="text-muted d-block fw-bold text-uppercase fs-11">Total Biaya</small>
                                                            <h4 class="mb-0 text-orange fw-bolder" id="total-harga">Rp 0</h4>
                                                        </div>
                                                        <button type="submit" class="btn btn-orange px-3 py-2 fw-bold shadow-sm d-flex align-items-center gap-1" id="btn-tambah" style="border-radius: 8px;">
                                                            <i class="bx bx-plus-circle fs-5"></i> Tambah
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Card 2: RAB Items Panel -->
                                        <div class="col-lg-4" id="rab-panel-barang" style="display:none;">
                                            <div class="card shadow-sm border-0 mb-0 h-100" style="border-radius: 12px; background-color: #fdfdfd;">
                                                <div class="card-body p-4">
                                                    <h6 class="text-uppercase text-muted fw-bold mb-3"><i class="bx bx-list-check text-orange me-1"></i> 2. Item RAB</h6>
                                                    <div class="mb-3 text-end">
                                                        <span class="badge bg-orange-subtle text-orange border border-orange px-2 py-1" id="sisa-anggaran-barang">Sisa: -</span>
                                                    </div>
                                                    <div id="rab-list-barang" class="border rounded p-2" style="max-height: 250px; overflow-y: auto; border-radius: 8px !important; background: #fafafa;">
                                                        <p class="text-muted text-center mb-0 py-2"><i class="bx bx-loader-alt bx-spin"></i> Memuat item RAB...</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Form Tunai -->
                            <div class="tab-pane" id="tunai" role="tabpanel">
                                <form action="" id="form-tunai">
                                    <input type="hidden" name="kode_pengajuan" value="<?= $pj->kode_pengajuan ?>">
                                    <div class="row g-3">
                                        <!-- Card 1: Pilih Program, Kegiatan, COA & Nama Kegiatan -->
                                        <div class="col-lg-4">
                                            <div class="card shadow-sm border-0 mb-0 h-100" style="border-radius: 12px; background-color: #fcfcfc;">
                                                <div class="card-body p-4">
                                                    <h6 class="text-uppercase text-muted fw-bold mb-4"><i class="bx bx-layer text-success me-1"></i> 1. Kegiatan & Input</h6>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-dark">Pilih Program</label>
                                                        <select class="js-example-basic-single w-100" name="program-tunai" id="program-tunai" required>
                                                            <option value="">pilih program</option>
                                                            <?php foreach ($program as $program2): ?>
                                                                <option value="<?= $program2->kode_program ?>"><b><?= $program2->kode_program . '. ' . $program2->program ?></b></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3" id="kegiatan-tunai-wrapper" style="display:none;">
                                                        <label class="form-label fw-bold text-dark">Pilih Kegiatan</label>
                                                        <select class="form-select" name="kegiatan_dppk" id="kegiatan-tunai" style="border-radius: 8px;">
                                                            <option value="">pilih kegiatan</option>
                                                        </select>
                                                        <small class="text-muted mt-1 d-block" id="kegiatan-info-tunai"></small>
                                                    </div>

                                                    <div class="mb-0">
                                                        <label class="form-label fw-bold text-dark">Pilih Akun (COA)</label>
                                                        <div class="row g-2">
                                                            <div class="col-6">
                                                                <select class="js-example-basic-single select2-standalone w-100" id="p-coa-tunai" required>
                                                                    <option value="">pilih parent</option>
                                                                    <?php foreach ($coa as $coa2):
                                                                        if ($coa2->parrent == '') { ?>
                                                                            <option value="<?= $coa2->kode ?>"><b><?= $coa2->kode . ' ' . $coa2->nama ?></b></option>
                                                                    <?php }
                                                                    endforeach ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-6">
                                                                <select class="js-example-basic-single select-dependent w-100" id="c-coa-tunai" name="coa" required>
                                                                    <option value="">pilih sub-akun</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" class="fr-kegiatan" name="kegiatan">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Card 3: Form Rincian & Simpan -->
                                        <div class="col-lg-4 flex-grow-1">
                                            <div class="card shadow-sm mb-0 h-100" style="border-radius: 12px; background-color: #f4fcf7; border: 1px solid #d1f0df;">
                                                <div class="card-body p-4 d-flex flex-column">
                                                    <h6 class="text-uppercase text-success fw-bold mb-4"><i class="bx bx-money me-1"></i> 3. Rincian Tunai</h6>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-dark">Nama Item / Keperluan</label>
                                                        <input type="text" class="form-control bg-white border px-3 py-2" id="nama-barang" name="barang" placeholder="Contoh: Honor Narasumber" required style="border-radius: 8px;">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold text-dark">Harga / Nominal</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-success text-white border-success fw-bold" style="border-radius: 8px 0 0 8px;">Rp</span>
                                                            <input type="text" class="form-control uang bg-white border-start-0 px-3 py-2" id="harga" name="harga" placeholder="0" required style="border-radius: 0 8px 8px 0;">
                                                        </div>
                                                    </div>

                                                    <div class="mb-4">
                                                        <label class="form-label fw-bold text-dark">Jumlah & Satuan</label>
                                                        <div class="row g-2">
                                                            <div class="col-4">
                                                                <input type="number" class="form-control bg-white border px-3 py-2" id="input-qty" name="qty" placeholder="Qty" required style="border-radius: 8px;">
                                                            </div>
                                                            <div class="col-8">
                                                                <select class="js-example-basic-single w-100" id="satuan-select" name="satuan" required>
                                                                    <option value="">-satuan-</option>
                                                                    <?php foreach ($satuan as $satuanTn): ?>
                                                                        <option value="<?= $satuanTn->nama ?>"><?= $satuanTn->nama ?></option>
                                                                    <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="mt-auto text-end border-top pt-3 border-success border-opacity-25">
                                                        <button type="submit" class="btn btn-success px-4 py-2 fw-bold shadow-sm d-inline-flex align-items-center gap-2" id="btn-tambah" style="border-radius: 8px; width: 100%; justify-content: center;">
                                                            <i class="bx bx-check-circle fs-5"></i> Simpan Item Tunai
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Card 2: RAB Items Panel -->
                                        <div class="col-lg-4" id="rab-panel-tunai" style="display:none;">
                                            <div class="card shadow-sm border-0 mb-0 h-100" style="border-radius: 12px; background-color: #fcfcfc;">
                                                <div class="card-body p-4">
                                                    <h6 class="text-uppercase text-muted fw-bold mb-3"><i class="bx bx-list-check text-success me-1"></i> 2. Item RAB</h6>
                                                    <div class="mb-3 text-end">
                                                        <span class="badge px-2 py-1" id="sisa-anggaran-tunai" style="background-color: #d1f0df; color: #198754; border: 1px solid #198754;">Sisa: -</span>
                                                    </div>
                                                    <div id="rab-list-tunai" class="border rounded p-2" style="max-height: 250px; overflow-y: auto; border-radius: 8px !important; background: #fafafa;">
                                                        <p class="text-muted text-center mb-0 py-2"><i class="bx bx-loader-alt bx-spin"></i> Memuat item RAB...</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div><!-- end card-body -->

                    <div class="card-body pt-0">
                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <span id="total-pengajuan" style="display: none;"></span>
                        </div>
                        <!-- <b class="folat-right">Total </b> -->
                        <div class="table-responsive mb-1">
                            <table class="table table-nowrap align-middle" id="tableData">
                                <thead class="text-muted table-dark">
                                    <tr class="">
                                        <th class="text-light" data-sort="id">No</th>
                                        <th class="text-light" data-sort="id">Kode Item</th>
                                        <th class="text-light" data-sort="customer_name">Akun/COA</th>
                                        <th class="text-light" data-sort="product_name">Nama Item</th>
                                        <th class="text-light" data-sort="amount">Harga</th>
                                        <th class="text-light" data-sort="date">Jumlah</th>
                                        <th class="text-light" data-sort="payment">Total</th>
                                        <th class="text-light" data-sort="status">Jenis</th>
                                        <th class="text-light" data-sort="city">#</th>
                                    </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
    <!--end row-->
</div>

<div class="modal fade" id="inputno-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Barang Pengajuan </h5>
                <button type="button" class="btn-close" value="" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('pengajuan/addItemBarangModal') ?>" method="post" class="form-addrow mt-2" id="form-barang-modal">
                    <input type="hidden" name="kode_pengajuan" value="<?= $pj->kode_pengajuan ?>">
                    <input type="hidden" id="program_modal" name="program">
                    <input type="hidden" id="coa_modal" name="coa">
                    <input type="hidden" id="kegiatan_modal" name="kegiatan">
                    <input type="hidden" id="kegiatan_dppk_modal" name="kegiatan_dppk">
                    <div class="form-group mb-2">
                        <label for="">Nama Barang</label>
                        <input type="text" class="form-control" name="nama" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="">Satuan</label>
                        <select class="form-select" name="satuan" required>
                            <option value="">-satuan-</option>
                            <?php foreach ($satuan as $satuanMd): ?>
                                <option value="<?= $satuanMd->nama ?>"><?= $satuanMd->nama ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="">Harga Barang</label>
                        <input type="text" class="form-control uang" name="harga_satuan" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="">QTY</label>
                        <input type="number" class="form-control" name="qty" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for=""></label>
                        <button class="btn btn-success btn-sm px-3 d-inline-flex align-items-center gap-1" type="submit" style="border-radius: 6px;"><i class="bx bx-check-circle"></i> Tambahkan</button>
                    </div>
                </form>
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<!-- End Page-content -->
<?php require 'lembaga/foot.php' ?>
<script src="<?= base_url(''); ?>assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url(''); ?>assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<!--select2 cdn-->
<script src="<?= base_url(''); ?>assets/plugins/select2/js/select2.min.js"></script>
<script src="<?= base_url(''); ?>assets/js/jquery.mask.min.js"></script>
<!-- Notfy -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
    const notyf = new Notyf({
        duration: 3000,
        position: {
            x: 'center',
            y: 'top'
        },
        types: [{
            type: 'warning',
            background: '#ffc107',
            icon: {
                className: 'bx bx-error-circle',
                tagName: 'i',
                color: '#ffffff'
            }
        }]
    });
    $(document).ready(function() {
        $('#table1').DataTable()
        $('.js-example-basic-single').each(function() {
            $(this).select2({
                theme: 'bootstrap4',
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                placeholder: $(this).data('placeholder'),
                allowClear: Boolean($(this).data('allow-clear')),
            });
        });
        $('.uang').mask('000.000.000.000', {
            reverse: true
        });
        loadSSH()
        showTable()
        totalPengajuan()

        // Automatically manage modal inputs based on main form selection
        $('#inputno-modal').on('show.bs.modal', function(e) {
            let programVal = $('#program').val();
            let coaVal = $('#c-coa').val();
            let kegiatanDppkVal = $('#kegiatan-barang').val();
            let kegiatanVal = $('#kegiatan-barang').find('option:selected').attr('data-kegiatan') || '';

            if (!programVal) {
                notyf.error('Silakan pilih Program terlebih dahulu');
                e.preventDefault();
                return;
            }
            if (!kegiatanDppkVal) {
                notyf.error('Silakan pilih Kegiatan terlebih dahulu');
                e.preventDefault();
                return;
            }
            if (!coaVal || coaVal === 'pilih coa') {
                notyf.error('Silakan pilih Akun (COA) terlebih dahulu');
                e.preventDefault();
                return;
            }

            // If opened via the "+ Manual" button (relatedTarget is defined), clear custom inputs
            if (e.relatedTarget) {
                $('#inputno-modal input[name="nama"]').val('');
                $('#inputno-modal select[name="satuan"]').val('');
                $('#inputno-modal input[name="harga_satuan"]').val('').trigger('input');
                $('#inputno-modal input[name="qty"]').val('');
            }

            $('#program_modal').val(programVal);
            $('#coa_modal').val(coaVal);
            $('#kegiatan_modal').val(kegiatanVal);
            $('#kegiatan_dppk_modal').val(kegiatanDppkVal);
        });
    })
    $('#p-coa').on('change', function() {
        var kode = $(this).val();
        $.ajax({
            type: "POST",
            url: "<?= base_url('pengajuan/child_coa') ?>",
            data: {
                kode: kode
            },
            dataType: 'json',
            success: function(data) {
                $('#c-coa').empty();
                $('#c-coa').append('<option>pilih coa</option>')
                $.each(data.hasil, function(index, item) {
                    $('#c-coa').append(
                        $('<option>', {
                            value: item.kode, // Sesuaikan dengan data dari server
                            text: item.kode + ' ' + item.nama // Sesuaikan dengan data dari server
                        })
                    );
                });

                // $('#c-coa').trigger('change');
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText);
            }
        })
    });
    $('#p-coa-tunai').on('change', function() {
        var kode = $(this).val();
        $.ajax({
            type: "POST",
            url: "<?= base_url('pengajuan/child_coa') ?>",
            data: {
                kode: kode
            },
            dataType: 'json',
            success: function(data) {
                $('#c-coa-tunai').empty();
                $('#c-coa-tunai').append('<option>pilih coa</option>')
                $.each(data.hasil, function(index, item) {
                    $('#c-coa-tunai').append(
                        $('<option>', {
                            value: item.kode, // Sesuaikan dengan data dari server
                            text: item.kode + ' ' + item.nama // Sesuaikan dengan data dari server
                        })
                    );
                });

                // $('#c-coa').trigger('change');
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText);
            }
        })
    });
    $('#c-coa').on('change', function() {
        var kode = $(this).val();
        $.ajax({
            type: "POST",
            url: "<?= base_url('pengajuan/child_coa') ?>",
            data: {
                kode: kode
            },
            dataType: 'json',
            success: function(data) {
                // alert(data)
                $('#coa-p-desc').text(data.parent.kode + ' ' + data.parent.nama)
                $('#coa-c-desc').text(data.parent.keterangan)
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText);
            }
        })
    });
    $('#item-ssh').on('change', function() {
        var kode = $(this).val();
        $.ajax({
            type: "POST",
            url: "<?= base_url('pengajuan/detilSsh') ?>",
            data: {
                kode: kode
            },
            dataType: 'json',
            success: function(data) {
                $('#kategori').text(data.hasil.kategori)
                $('#nama-item').text(data.hasil.nama)
                $('#nama-kategori').text(data.kategori.nama_kategori)
                let hargaFormatted = Number(data.hasil.harga).toLocaleString('id-ID');
                $('#harga').text(hargaFormatted);

                $('#satuan').text(data.hasil.satuan)
                $('#input-qty').prop('disabled', false);
                if (window.pendingQty) {
                    $('#input-qty').val(window.pendingQty).trigger('input');
                    window.pendingQty = null;
                } else {
                    $('#input-qty').val('');
                }
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText);
            }
        })
    });
    $('#input-qty').on('input', function() {
        var jml = $(this).val();
        var ssh = $('#item-ssh').val();
        if (ssh && ssh != 'pilih barang') {
            $.ajax({
                type: "POST",
                url: "<?= base_url('pengajuan/detilSsh') ?>",
                data: {
                    kode: ssh
                },
                dataType: 'json',
                success: function(data) {
                    $('#qty').text(jml)
                    let hargaFormatted = Number(data.hasil.harga * jml).toLocaleString('id-ID');
                    $('#total-harga').text(hargaFormatted)
                },
                error: function(xhr, status, error) {
                    alert(xhr.responseText);
                }
            })
        } else {
            notyf.error('Silahkan pilih barangnya');
        }

        // alert(ssh)
    });

    function loadSSH() {
        $.ajax({
            type: "GET",
            url: "<?= base_url('pengajuan/loadSSH') ?>",
            dataType: 'json',
            success: function(data) {
                const select = $('#item-ssh');
                select.empty();
                select.append('<option>pilih barang</option>')
                data.forEach(function(group) {
                    const optgroup = $('<optgroup>', {
                        label: group.label
                    });
                    group.options.forEach(function(opt) {
                        $('<option>', {
                            value: opt.value,
                            text: opt.text
                        }).appendTo(optgroup);
                    });
                    optgroup.appendTo(select);
                });
                // console.log(data);

            }
        })
    }

    $('#form-barang').on('submit', function(e) {
        e.preventDefault();
        // cek validasi HTML5
        if (!this.checkValidity()) {
            this.reportValidity(); // munculkan pesan error bawaan browser
            return; // hentikan
        }
        var dataForm = $(this).serialize()
        $.ajax({
            type: "POST",
            url: "<?= base_url('pengajuan/addItemBarang') ?>",
            data: dataForm,
            dataType: 'json',
            success: function(data) {
                if (data.status == 'success') {
                    notyf.success(data.message);
                    showTable()
                    resetFormAndTable()
                    totalPengajuan()
                } else {
                    notyf.error(data.message);
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        })
    })
    $('#form-tunai').on('submit', function(e) {
        e.preventDefault();
        // cek validasi HTML5
        if (!this.checkValidity()) {
            this.reportValidity(); // munculkan pesan error bawaan browser
            return; // hentikan
        }

        var dataForm = $(this).serialize()
        $.ajax({
            type: "POST",
            url: "<?= base_url('pengajuan/addItemTunai') ?>",
            data: dataForm,
            dataType: 'json',
            success: function(data) {
                if (data.status == 'success') {
                    notyf.success(data.message);
                    showTable()
                    resetFormAndTable()
                    totalPengajuan()
                } else {
                    notyf.error(data.message);
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        })
        // console.log(dataForm);

    })

    $('#form-barang-modal').on('submit', function(e) {
        e.preventDefault();
        // cek validasi HTML5
        if (!this.checkValidity()) {
            this.reportValidity(); // munculkan pesan error bawaan browser
            return; // hentikan
        }
        var dataForm = $(this).serialize()
        $.ajax({
            type: "POST",
            url: "<?= base_url('pengajuan/addItemBarangModal') ?>",
            data: dataForm,
            dataType: 'json',
            success: function(data) {
                if (data.status == 'success') {
                    notyf.success(data.message);
                    $('#inputno-modal').modal('hide');
                    showTable()
                    resetFormAndTable()
                    totalPengajuan()
                } else {
                    notyf.error(data.message);
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        })
    })

    function showTable() {
        $.ajax({
            type: "POST",
            url: "<?= base_url('pengajuan/loadTable') ?>",
            data: {
                kode: '<?= $pj->kode_pengajuan ?>'
            },
            dataType: 'json',
            success: function(response) {
                if ($.fn.DataTable.isDataTable('#tableData')) {
                    // kalau sudah ada DataTable, cukup update datanya
                    let table = $('#tableData').DataTable();
                    table.clear(); // kosongkan dulu
                    if (response.length > 0) {
                        response.forEach(function(item, index) {
                            table.row.add([
                                index + 1,
                                `<a href="#" class="fw-medium link-primary">${item.kode_item}</a>`,
                                item.coa,
                                item.ssh == null && item.ket ? parseItemDetail(item.ket)?.nama : item.ssh,
                                rupiah(item.harga),
                                `${item.vol} <small class="text-muted">${item.satuan == null && item.ket ? parseItemDetail(item.ket)?.satuan : item.satuan}</small>`,
                                rupiah(item.vol * item.harga),
                                `<span class="badge bg-warning-subtle text-success text-uppercase">${item.stas =='tunai'?'Tunai':'Non-Tunai'}</span>`,
                                `<button class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1" onclick="delItem('${item.id_realis}')" style="border-radius: 6px; padding: 4px 8px;"><i class="bx bx-trash"></i> Hapus</button>`
                            ]);
                        });
                    }
                    table.draw(); // render ulang
                } else {
                    // kalau pertama kali load, baru bikin DataTable
                    $('#tableData').DataTable({
                        data: response.map((item, index) => [
                            index + 1,
                            `<a href="#" class="fw-medium link-primary">${item.kode_item}</a>`,
                            item.coa,
                            item.ssh == null && item.ket ? parseItemDetail(item.ket)?.nama : item.ssh,
                            rupiah(item.harga),
                            `${item.vol} <small class="text-muted">${item.satuan == null && item.ket ? parseItemDetail(item.ket)?.satuan : item.satuan}</small>`,
                            rupiah(item.vol * item.harga),
                            `<span class="badge bg-warning-subtle text-success text-uppercase">${item.stas =='tunai'?'Tunai':'Non-Tunai'}</span>`,
                            `<button class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1" onclick="delItem('${item.id_realis}')" style="border-radius: 6px; padding: 4px 8px;"><i class="bx bx-trash"></i> Hapus</button>`
                        ]),
                        columns: [{
                                title: "#"
                            },
                            {
                                title: "Kode Item"
                            },
                            {
                                title: "COA"
                            },
                            {
                                title: "Detail"
                            },
                            {
                                title: "Harga"
                            },
                            {
                                title: "Volume"
                            },
                            {
                                title: "Total"
                            },
                            {
                                title: "Status"
                            },
                            {
                                title: "Aksi"
                            }
                        ],
                        paging: false,
                        searching: true,
                        ordering: true,
                        responsive: false,
                        autoWidth: false,
                        dom: 'Bfrtip', // B = Buttons, f = filter, r = processing, t = table, i = info, p = pagination
                        buttons: [{
                                extend: 'excelHtml5',
                                title: 'Data Realisasi',
                                text: '<i class="bx bx-excel"></i> Export Excel',
                                className: 'btn btn-warning btn-sm'
                            },
                            {
                                extend: 'csvHtml5',
                                title: 'Data Realisasi',
                                text: '<i class="bx bx-file-csv"></i> Export CSV',
                                className: 'btn btn-info btn-sm'
                            },
                            {
                                extend: 'pdfHtml5',
                                title: 'Data Realisasi',
                                text: '<i class="bx bx-file-pdf"></i> Export PDF',
                                className: 'btn btn-danger btn-sm'
                            },
                            {
                                extend: 'print',
                                text: '<i class="bx bx-printer"></i> Print',
                                className: 'btn btn-secondary btn-sm'
                            }
                        ],
                        language: {
                            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                // console.log(xhr.responseText);
                console.error('Gagal memuat data:', error);
                $('#tableData tbody').html(`<tr><td colspan="5" class="text-center text-red-500">Gagal memuat data</td></tr>`);
            }
        })
    }

    function rupiah(number) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0
        }).format(number);
    }

    // Item Barang Tab Dynamic Logic
    $('#program').on('change', function() {
        let program_id = $(this).val();
        $('#program_modal').val(program_id);

        if (!program_id) {
            $('#kegiatan-barang-wrapper').hide();
            $('#rab-panel-barang').hide();
            $('#kegiatan-barang').empty().append('<option value="">pilih kegiatan</option>');
            return;
        }

        $.ajax({
            type: "POST",
            url: "<?= base_url('pengajuan/getKegiatanByProgram') ?>",
            data: {
                program_id: program_id,
                bulan_pj: '<?= $pj->bulan ?>'
            },
            dataType: 'json',
            success: function(response) {
                let select = $('#kegiatan-barang');
                select.empty().append('<option value="">pilih kegiatan</option>');
                if (response.status === 'success' && response.data.length > 0) {
                    response.data.forEach(function(item) {
                        $('<option>', {
                            value: item.id_dppk,
                            text: item.kode_kegiatan + ' - ' + item.kegiatan
                        }).attr('data-kegiatan', item.kegiatan).appendTo(select);
                    });
                    $('#kegiatan-barang-wrapper').show();
                } else {
                    $('#kegiatan-barang-wrapper').hide();
                }
                $('#rab-panel-barang').hide();
            },
            error: function(xhr, status, error) {
                console.error('Gagal mengambil kegiatan:', error);
            }
        });
    });

    $('#kegiatan-barang').on('change', function() {
        let id_dppk = $(this).val();
        let kegiatanVal = $(this).find('option:selected').attr('data-kegiatan') || '';
        $('#form-barang input[name="kegiatan"]').val(kegiatanVal);

        if (!id_dppk) {
            $('#rab-panel-barang').hide();
            return;
        }

        $('#rab-list-barang').html('<p class="text-muted text-center mb-0 py-2"><i class="bx bx-loader-alt bx-spin"></i> Memuat item RAB...</p>');
        $('#rab-panel-barang').show();

        $.ajax({
            type: "POST",
            url: "<?= base_url('pengajuan/getRabByDppk') ?>",
            data: {
                id_dppk: id_dppk
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#sisa-anggaran-barang').text('Sisa: ' + rupiah(response.sisa));

                    let container = $('#rab-list-barang');
                    container.empty();

                    if (response.data.length > 0) {
                        response.data.forEach(function(item) {
                            let totalFormatted = rupiah(item.total);
                            let hargaFormatted = rupiah(item.harga_satuan);
                            let card = $(`
                                <div class="rab-card" data-id="${item.kode}">
                                    <div class="rab-title">${item.nama}</div>
                                    <div class="rab-meta">
                                        <span>${item.qty} ${item.satuan} &times; ${hargaFormatted}</span>
                                        <span class="fw-bold text-orange">${totalFormatted}</span>
                                    </div>
                                </div>
                            `);
                            card.data('raw-item', item);
                            container.append(card);
                        });
                    } else {
                        container.html('<p class="text-muted text-center mb-0 py-2">Tidak ada item RAB untuk kegiatan ini</p>');
                    }
                } else {
                    notyf.error(response.message || 'Gagal mengambil RAB');
                }
            },
            error: function(xhr, status, error) {
                console.error('Gagal mengambil RAB:', error);
                $('#rab-list-barang').html('<p class="text-danger text-center mb-0 py-2">Gagal memuat RAB</p>');
            }
        });
    });

    $(document).on('click', '#rab-list-barang .rab-card', function() {
        $('#rab-list-barang .rab-card').removeClass('selected');
        $(this).addClass('selected');

        let item = $(this).data('raw-item');
        if (!item) return;

        window.pendingQty = item.qty;

        let rabName = item.nama.toLowerCase().trim();
        let bestMatchVal = "";
        let exactMatchVal = "";

        $('#item-ssh option').each(function() {
            let optText = $(this).text().toLowerCase().trim();
            let optVal = $(this).val();
            if (!optVal || optVal === "pilih barang") return;

            if (optText === rabName) {
                exactMatchVal = optVal;
                return false;
            }
            if (optText.indexOf(rabName) !== -1 || rabName.indexOf(optText) !== -1) {
                bestMatchVal = optVal;
            }
        });

        let matchedVal = exactMatchVal || bestMatchVal;
        if (matchedVal) {
            $('#item-ssh').val(matchedVal).trigger('change');
            notyf.success('Item SSH terpilih: ' + $('#item-ssh option:selected').text());
        } else {
            notyf.open({
                type: 'warning',
                message: 'Item SSH tidak cocok otomatis. Membuka form input manual...'
            });

            // Prefill the modal inputs
            $('#inputno-modal input[name="nama"]').val(item.nama);

            // Try to match/set the Satuan select dropdown
            let satuanVal = item.satuan || '';
            let matchedSatuan = "";
            $('#inputno-modal select[name="satuan"] option').each(function() {
                if ($(this).val().toLowerCase().trim() === satuanVal.toLowerCase().trim()) {
                    matchedSatuan = $(this).val();
                    return false;
                }
            });
            if (matchedSatuan) {
                $('#inputno-modal select[name="satuan"]').val(matchedSatuan);
            } else {
                $('#inputno-modal select[name="satuan"]').val('');
            }

            // Set harga (triggering input ensures standard money masking is applied)
            let rawHarga = Math.round(Number(item.harga_satuan)).toString();
            $('#inputno-modal input[name="harga_satuan"]').val(rawHarga).trigger('input');

            // Set qty
            $('#inputno-modal input[name="qty"]').val(item.qty);

            // Open the modal after a short delay to allow visual transition
            setTimeout(function() {
                $('#inputno-modal').modal('show');
            }, 300);
        }
    });

    // Item Tunai Tab Dynamic Logic
    $('#program-tunai').on('change', function() {
        let program_id = $(this).val();

        if (!program_id) {
            $('#kegiatan-tunai-wrapper').hide();
            $('#rab-panel-tunai').hide();
            $('#kegiatan-tunai').empty().append('<option value="">pilih kegiatan</option>');
            return;
        }

        $.ajax({
            type: "POST",
            url: "<?= base_url('pengajuan/getKegiatanByProgram') ?>",
            data: {
                program_id: program_id,
                bulan_pj: '<?= $pj->bulan ?>'
            },
            dataType: 'json',
            success: function(response) {
                let select = $('#kegiatan-tunai');
                select.empty().append('<option value="">pilih kegiatan</option>');
                if (response.status === 'success' && response.data.length > 0) {
                    response.data.forEach(function(item) {
                        $('<option>', {
                            value: item.id_dppk,
                            text: item.kode_kegiatan + ' - ' + item.kegiatan
                        }).attr('data-kegiatan', item.kegiatan).appendTo(select);
                    });
                    $('#kegiatan-tunai-wrapper').show();
                } else {
                    $('#kegiatan-tunai-wrapper').hide();
                }
                $('#rab-panel-tunai').hide();
            },
            error: function(xhr, status, error) {
                console.error('Gagal mengambil kegiatan:', error);
            }
        });
    });

    $('#kegiatan-tunai').on('change', function() {
        let id_dppk = $(this).val();
        let kegiatanVal = $(this).find('option:selected').attr('data-kegiatan') || '';
        $('#form-tunai input[name="kegiatan"]').val(kegiatanVal);

        if (!id_dppk) {
            $('#rab-panel-tunai').hide();
            return;
        }

        $('#rab-list-tunai').html('<p class="text-muted text-center mb-0 py-2"><i class="bx bx-loader-alt bx-spin"></i> Memuat item RAB...</p>');
        $('#rab-panel-tunai').show();

        $.ajax({
            type: "POST",
            url: "<?= base_url('pengajuan/getRabByDppk') ?>",
            data: {
                id_dppk: id_dppk
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#sisa-anggaran-tunai').text('Sisa: ' + rupiah(response.sisa));

                    let container = $('#rab-list-tunai');
                    container.empty();

                    if (response.data.length > 0) {
                        response.data.forEach(function(item) {
                            let totalFormatted = rupiah(item.total);
                            let hargaFormatted = rupiah(item.harga_satuan);
                            let card = $(`
                                <div class="rab-card rab-card-tunai" data-id="${item.kode}">
                                    <div class="rab-title">${item.nama}</div>
                                    <div class="rab-meta">
                                        <span>${item.qty} ${item.satuan} &times; ${hargaFormatted}</span>
                                        <span class="fw-bold text-success">${totalFormatted}</span>
                                    </div>
                                </div>
                            `);
                            card.data('raw-item', item);
                            container.append(card);
                        });
                    } else {
                        container.html('<p class="text-muted text-center mb-0 py-2">Tidak ada item RAB untuk kegiatan ini</p>');
                    }
                } else {
                    notyf.error(response.message || 'Gagal mengambil RAB');
                }
            },
            error: function(xhr, status, error) {
                console.error('Gagal mengambil RAB:', error);
                $('#rab-list-tunai').html('<p class="text-danger text-center mb-0 py-2">Gagal memuat RAB</p>');
            }
        });
    });

    $(document).on('click', '#rab-list-tunai .rab-card', function() {
        $('#rab-list-tunai .rab-card').removeClass('selected');
        $(this).addClass('selected');

        let item = $(this).data('raw-item');
        if (!item) return;

        $('#form-tunai #nama-barang').val(item.nama);

        let rawHarga = Math.round(Number(item.harga_satuan)).toString();
        $('#form-tunai #harga').val(rawHarga).trigger('input');

        $('#form-tunai #input-qty').val(item.qty);
        $('#form-tunai #satuan-select').val(item.satuan).trigger('change');

        notyf.success('Form Tunai diisi dari item RAB: ' + item.nama);
    });

    $('#c-coa').on('change', function() {
        let kode = $(this).val();
        $('#coa_modal').val(kode)
    });

    function resetFormAndTable() {
        $('#form-barang')[0].reset();
        $('#form-tunai')[0].reset();
        if ($('#form-barang-modal').length > 0) {
            $('#form-barang-modal')[0].reset();
        }
        $('.select2-standalone').val(null).trigger('change');
        $('.select-dependent').html('<option value=""> pilih </option>');

        // Hide and clear custom wrappers
        $('#kegiatan-barang-wrapper').hide();
        $('#kegiatan-tunai-wrapper').hide();
        $('#rab-panel-barang').hide();
        $('#rab-panel-tunai').hide();
        $('#kegiatan-barang').empty().append('<option value="">pilih kegiatan</option>');
        $('#kegiatan-tunai').empty().append('<option value="">pilih kegiatan</option>');
        $('#rab-list-barang').empty();
        $('#rab-list-tunai').empty();

        // Reset Program Selects safely
        $('#program').val(null).trigger('change.select2');
        $('#program-tunai').val(null).trigger('change.select2');

        $('#coa-p-desc').text('-');
        $('#coa-c-desc').text('-');
        window.pendingQty = null;
        loadSSH()

        // Table list Hasil
        $('#kategori').text('')
        $('#nama-item').text('')
        $('#nama-kategori').text('')
        $('#harga').text('');
        $('#satuan').text('')
        $('.fr-kegiatan').val('') // Clear input value (use .val() instead of .text() for input elements)
        $('#qty').text(0)
        $('#total-harga').text(0)
    }

    function delItem(id) {
        Swal.fire({
            title: 'Yakin?',
            text: "Data akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('pengajuan/delItem') ?>',
                    type: 'POST',
                    dataType: 'json', // pastikan response dibaca sebagai JSON
                    data: {
                        id: id
                    },
                    success: function(psn) {
                        if (psn.status === 'success') {
                            notyf.success('Data berhasil dihapus!');
                            showTable();
                            totalPengajuan();
                        } else {
                            notyf.error(psn.message || 'Terjadi kesalahan!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        notyf.error('Gagal menghapus data!');
                    }
                });
            }
        });

    }

    function totalPengajuan() {
        $.ajax({
            type: "POST",
            url: "<?= base_url('pengajuan/totalPengjuan') ?>",
            data: {
                kode: '<?= $pj->kode_pengajuan ?>'
            },
            dataType: 'json',
            success: function(response) {
                let formatted = rupiah(response);
                $('#total-pengajuan').text(formatted);
                $('#total-pengajuan-top').text(formatted);
            },
            error: function(xhr, status, error) {
                $('#total-pengajuan').text(error);
                $('#total-pengajuan-top').text(error);
            }
        })
    }

    function parseItemDetail(text) {
        if (!text || !text.includes(" - @ ") || !text.includes(" x ")) {
            return {
                nama: '',
                jumlah: 0,
                satuan: '',
                harga: 0
            }; // fallback aman
        }

        const [namaBarang, detail] = text.split(" - @ ");
        const detailParts = detail.split(" x ");

        if (!detailParts[0] || !detailParts[1]) {
            return {
                nama: namaBarang || '',
                jumlah: 0,
                satuan: '',
                harga: 0
            };
        }

        const [jumlah, satuan] = detailParts[0].split(" ");
        const harga = detailParts[1].replace(/\./g, "");

        return {
            nama: namaBarang?.trim() || '',
            jumlah: parseInt(jumlah) || 0,
            satuan: satuan?.trim() || '',
            harga: parseInt(harga) || 0
        };
    }
</script>