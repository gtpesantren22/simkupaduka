<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <div class="row row-cols-1 row-cols-md-3 row-cols-xl-12">
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">RAB Saya</p>
                                <h4 class="my-1 text-info"><?= rupiah($rab->jml); ?></h4>
                                <p class="mb-0 font-13">Jumlah Anggaran dalam 1 Tahun</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                                <i class='bx bxs-wallet'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Realisasi</p>
                                <h4 class="my-1 text-danger"><?= rupiah($realis->jml); ?></h4>
                                <p class="mb-0 font-13">Total RAB yang terpakai</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto"><i class='bx bxs-cart'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Pengajuan Terakhir</p>
                                <?php if ($spj) { ?>
                                    <p class="mb-0 font-13">VerVal :
                                        <?= $pj->verval == 1 ? "<span class='badge bg-success'><i class='bx bx-check'></i> sudah</span>" : "<span class='badge bg-danger'><i class='bx bx-no-entry'></i> belum</span>"; ?>
                                    </p>
                                    <p class="mb-0 font-13">Pencairan :
                                        <?= $pj->cair == 1 ? "<span class='badge bg-success'><i class='bx bx-check'></i> sudah</span>" : "<span class='badge bg-danger'><i class='bx bx-no-entry'></i> belum</span>"; ?>
                                    </p>
                                    <p class="mb-0 font-13">SPJ : <?php if ($spj->stts == 0) { ?>
                                            <span class="badge bg-danger"><i class="bx bx-no-entry"></i> belum upload</span>
                                        <?php } else if ($spj->stts == 1) { ?>
                                            <span class="badge bg-warning"><i class="bx bx-recycle"></i>
                                                proses verifikasi</span>
                                        <?php } else { ?>
                                            <span class="badge bg-success"><i class="bx bx-check"></i> sudah selesai</span>
                                        <?php } ?>
                                    </p>
                                <?php } ?>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-blooker text-white ms-auto">
                                <i class='bx bxs-shopping-bag'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->

        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="mb-0">Statistik Penggunaan RAB (%)</h6>
                            </div>
                            <div class="dropdown ms-auto">
                                <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:;">Action</a>
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:;">Another action</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="d-flex align-items-center ms-auto font-13 gap-2 my-3">
                            <span class="border px-1 rounded cursor-pointer"><i class="bx bxs-circle me-1" style="color: #14abef"></i>Realisasi RAB (%)</span>
                            <!-- <span class="border px-1 rounded cursor-pointer"><i class="bx bxs-circle me-1"
                                    style="color: #ffc107"></i>Visits</span> -->
                        </div>
                        <div class="chart-container-1">
                            <canvas id="chart1"></canvas>
                        </div>
                    </div>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-12 g-0 row-group text-center border-top">
                        <div class="col">
                            <div class="p-3">
                                <h5 class="mb-0"><?= $realis->jml != 0 || $rab->jml != 0 ? round($realis->jml / $rab->jml * 100, 1) : 0 ?> %</h5>
                                <small class="mb-0">Porsentase Pemakaian RAB <span> <i class="bx bx-up-arrow-alt align-middle"></i> (%)</span></small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-3">
                                <h5 class="mb-0"><?= $realis->jml != 0 || $rab->jml != 0 ? round(100 - ($realis->jml / $rab->jml * 100), 1) : 0 ?> %</h5>
                                <small class="mb-0">Porsentase Sisa RAB <span> <i class="bx bx-up-arrow-alt align-middle"></i> (%)</span></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="mb-0">informasi</h6>
                            </div>
                            <div class="dropdown ms-auto">
                                <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:;">Action</a>
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:;">Another action</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="list-group">
                            <?php foreach ($info as $ar) : ?>
                                <a href="javascript:;" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1"><?= $ar->judul; ?></h5>
                                    </div>
                                    <p class="mb-1"><?= $ar->isi; ?></p> <small class="text-muted">diupload pada :
                                        <?= tanggalIndo($ar->tgl); ?></small>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<!--end page wrapper -->