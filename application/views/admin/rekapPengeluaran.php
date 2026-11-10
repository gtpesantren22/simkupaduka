<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Rekap Pengeluaran</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:;">Pengeluaran</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Semua Kelompok Pengeluaran</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto d-flex align-items-center gap-2">
                <form method="get" action="<?= base_url('admin/rekapPengeluaran') ?>" class="d-flex align-items-center gap-2">
                    <label class="form-label mb-0 fw-bold fs-13 text-nowrap"><i class="bx bx-calendar me-1"></i>Tahun:</label>
                    <select name="tahun" class="form-select form-select-sm" onchange="this.form.submit()">
                        <?php foreach ($daftar_tahun as $th) : ?>
                            <option value="<?= $th->nama_tahun ?>" <?= $th->nama_tahun == $tahun ? 'selected' : '' ?>><?= $th->nama_tahun ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                    <i class="bx bx-printer me-1"></i> Cetak
                </button>
            </div>
        </div>
        <!--end breadcrumb-->

        <!-- Top Summary Cards (Grand Total) -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-3 mb-4">
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-primary shadow-sm mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary fs-13 text-uppercase fw-bold">Total Pagu</p>
                                <h4 class="my-1 text-primary fw-bold"><?= rupiah($grand_total['pagu']) ?></h4>
                                <small class="text-muted fs-12">Akumulasi seluruh kelompok pagu</small>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                                <i class='bx bx-coin-stack'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-danger shadow-sm mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary fs-13 text-uppercase fw-bold">Total Terpakai</p>
                                <h4 class="my-1 text-danger fw-bold"><?= rupiah($grand_total['pakai']) ?></h4>
                                <small class="text-muted fs-12">Total realisasi pengeluaran</small>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                                <i class='bx bx-wallet'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-success shadow-sm mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary fs-13 text-uppercase fw-bold">Total Sisa</p>
                                <h4 class="my-1 text-success fw-bold"><?= rupiah($grand_total['sisa']) ?></h4>
                                <small class="text-muted fs-12">Sisa anggaran tersedia</small>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                                <i class='bx bx-check-shield'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-4 border-warning shadow-sm mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary fs-13 text-uppercase fw-bold">Serapan Anggaran</p>
                                <h4 class="my-1 text-warning fw-bold"><?= $grand_total['persen'] ?>%</h4>
                                <div class="progress mt-1" style="height: 6px; width: 140px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: <?= min(100, $grand_total['persen']) ?>%"></div>
                                </div>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-blooker text-white ms-auto">
                                <i class='bx bx-pie-chart-alt'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end top summary-->

        <!-- Nav Shortcut Pills -->
        <div class="mb-3 d-flex flex-wrap gap-2">
            <span class="text-muted fs-13 align-self-center me-1"><i class="bx bx-bookmark me-1"></i>Pintas Kelompok:</span>
            <a href="#kelompok-rutin" class="btn btn-outline-primary btn-sm rounded-pill"><i class="bx bx-hdd me-1"></i>1. Rutin</a>
            <a href="#kelompok-prog" class="btn btn-outline-info btn-sm rounded-pill"><i class="bx bx-briefcase me-1"></i>2. Program & Pengembangan</a>
            <a href="#kelompok-terikat" class="btn btn-outline-secondary btn-sm rounded-pill"><i class="bx bx-lock-alt me-1"></i>3. Dana Terikat</a>
            <a href="#kelompok-lembaga" class="btn btn-outline-success btn-sm rounded-pill"><i class="bx bx-buildings me-1"></i>4. Realisasi Lembaga</a>
            <a href="#kelompok-khusus" class="btn btn-outline-warning btn-sm rounded-pill text-dark"><i class="bx bx-star me-1"></i>5. Pagu Khusus (Pusat)</a>
            <a href="#kelompok-lain" class="btn btn-outline-danger btn-sm rounded-pill"><i class="bx bx-layer me-1"></i>6. Pengeluaran Lainnya</a>
        </div>

        <!-- ========================================================================= -->
        <!-- KELOMPOK 1: PENGELUARAN RUTIN -->
        <!-- ========================================================================= -->
        <div class="card radius-10 border shadow-sm mb-4" id="kelompok-rutin">
            <div class="card-header bg-white py-3 border-bottom">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary p-2 fs-6 rounded-circle"><i class="bx bx-hdd"></i></span>
                        <div>
                            <h5 class="mb-0 fw-bold">1. Pengeluaran Rutin</h5>
                            <small class="text-muted">Pos langganan rutin operasional (Listrik, Internet, Honor, BPJS, dll)</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="<?= base_url('admin/outRutin') ?>" class="btn btn-outline-primary btn-sm">
                            <i class="bx bx-link-external me-1"></i> Detail Input Rutin
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th style="width: 30%;">Pos Pengeluaran Rutin</th>
                                <th class="text-end" style="width: 20%;">Pagu Anggaran</th>
                                <th class="text-end" style="width: 20%;">Terpakai (Realisasi)</th>
                                <th class="text-end" style="width: 20%;">Sisa Anggaran</th>
                                <th class="text-center" style="width: 15%;">Persentase / Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($rutin['items'])) : ?>
                                <?php $no = 1; foreach ($rutin['items'] as $item) : ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td class="fw-bold text-dark"><?= $item['nama'] ?></td>
                                        <td class="text-end"><?= rupiah($item['limit']) ?></td>
                                        <td class="text-end text-danger fw-bold"><?= rupiah($item['pakai']) ?></td>
                                        <td class="text-end fw-bold <?= $item['sisa'] < 0 ? 'text-danger' : 'text-success' ?>">
                                            <?= rupiah($item['sisa']) ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge <?= $item['badge_class'] ?>"><?= $item['persen'] ?>% - <?= $item['status'] ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6" class="text-center py-3 text-muted">Belum ada data pengeluaran rutin pada tahun <?= $tahun ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="table-secondary fw-bold">
                            <tr>
                                <td colspan="2" class="text-center">TOTAL KELOMPOK RUTIN</td>
                                <td class="text-end"><?= rupiah($rutin['total_limit']) ?></td>
                                <td class="text-end text-danger"><?= rupiah($rutin['total_pakai']) ?></td>
                                <td class="text-end <?= $rutin['total_sisa'] < 0 ? 'text-danger' : 'text-success' ?>">
                                    <?= rupiah($rutin['total_sisa']) ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-dark"><?= $rutin['total_persen'] ?>%</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- KELOMPOK 2: PROGRAM DAN PENGEMBANGAN -->
        <!-- ========================================================================= -->
        <div class="card radius-10 border shadow-sm mb-4" id="kelompok-prog">
            <div class="card-header bg-white py-3 border-bottom">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-info p-2 fs-6 rounded-circle"><i class="bx bx-briefcase"></i></span>
                        <div>
                            <h5 class="mb-0 fw-bold">2. Program & Pengembangan</h5>
                            <small class="text-muted">Pos kegiatan program dan pengembangan yayasan/institusi</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="<?= base_url('admin/outProg') ?>" class="btn btn-outline-info btn-sm">
                            <i class="bx bx-link-external me-1"></i> Detail Input Program
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th style="width: 30%;">Nama Pos Program & Pengembangan</th>
                                <th class="text-end" style="width: 20%;">Pagu Anggaran</th>
                                <th class="text-end" style="width: 20%;">Terpakai (Realisasi)</th>
                                <th class="text-end" style="width: 20%;">Sisa Anggaran</th>
                                <th class="text-center" style="width: 15%;">Persentase / Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($prog['items'])) : ?>
                                <?php $no = 1; foreach ($prog['items'] as $item) : ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td class="fw-bold text-dark"><?= $item['nama'] ?></td>
                                        <td class="text-end"><?= rupiah($item['limit']) ?></td>
                                        <td class="text-end text-danger fw-bold"><?= rupiah($item['pakai']) ?></td>
                                        <td class="text-end fw-bold <?= $item['sisa'] < 0 ? 'text-danger' : 'text-success' ?>">
                                            <?= rupiah($item['sisa']) ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge <?= $item['badge_class'] ?>"><?= $item['persen'] ?>% - <?= $item['status'] ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6" class="text-center py-3 text-muted">Belum ada data program & pengembangan pada tahun <?= $tahun ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="table-secondary fw-bold">
                            <tr>
                                <td colspan="2" class="text-center">TOTAL KELOMPOK PROGRAM & PENGEMBANGAN</td>
                                <td class="text-end"><?= rupiah($prog['total_limit']) ?></td>
                                <td class="text-end text-danger"><?= rupiah($prog['total_pakai']) ?></td>
                                <td class="text-end <?= $prog['total_sisa'] < 0 ? 'text-danger' : 'text-success' ?>">
                                    <?= rupiah($prog['total_sisa']) ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-dark"><?= $prog['total_persen'] ?>%</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- KELOMPOK 3: DANA TERIKAT -->
        <!-- ========================================================================= -->
        <div class="card radius-10 border shadow-sm mb-4" id="kelompok-terikat">
            <div class="card-header bg-white py-3 border-bottom">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-secondary p-2 fs-6 rounded-circle"><i class="bx bx-lock-alt"></i></span>
                        <div>
                            <h5 class="mb-0 fw-bold">3. Dana Terikat</h5>
                            <small class="text-muted">Pos pengeluaran dengan peruntukan khusus / dana terikat</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="<?= base_url('admin/outTerikat') ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="bx bx-link-external me-1"></i> Detail Input Dana Terikat
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th style="width: 30%;">Nama Pos Dana Terikat</th>
                                <th class="text-end" style="width: 20%;">Pagu Anggaran</th>
                                <th class="text-end" style="width: 20%;">Terpakai (Realisasi)</th>
                                <th class="text-end" style="width: 20%;">Sisa Anggaran</th>
                                <th class="text-center" style="width: 15%;">Persentase / Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($terikat['items'])) : ?>
                                <?php $no = 1; foreach ($terikat['items'] as $item) : ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td class="fw-bold text-dark"><?= $item['nama'] ?></td>
                                        <td class="text-end"><?= rupiah($item['limit']) ?></td>
                                        <td class="text-end text-danger fw-bold"><?= rupiah($item['pakai']) ?></td>
                                        <td class="text-end fw-bold <?= $item['sisa'] < 0 ? 'text-danger' : 'text-success' ?>">
                                            <?= rupiah($item['sisa']) ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge <?= $item['badge_class'] ?>"><?= $item['persen'] ?>% - <?= $item['status'] ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6" class="text-center py-3 text-muted">Belum ada data dana terikat pada tahun <?= $tahun ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="table-secondary fw-bold">
                            <tr>
                                <td colspan="2" class="text-center">TOTAL KELOMPOK DANA TERIKAT</td>
                                <td class="text-end"><?= rupiah($terikat['total_limit']) ?></td>
                                <td class="text-end text-danger"><?= rupiah($terikat['total_pakai']) ?></td>
                                <td class="text-end <?= $terikat['total_sisa'] < 0 ? 'text-danger' : 'text-success' ?>">
                                    <?= rupiah($terikat['total_sisa']) ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-dark"><?= $terikat['total_persen'] ?>%</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- KELOMPOK 4: REALISASI LEMBAGA (RAB PENGAJUAN) -->
        <!-- ========================================================================= -->
        <div class="card radius-10 border shadow-sm mb-4" id="kelompok-lembaga">
            <div class="card-header bg-white py-3 border-bottom">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-success p-2 fs-6 rounded-circle"><i class="bx bx-buildings"></i></span>
                        <div>
                            <h5 class="mb-0 fw-bold">4. Realisasi Belanja Lembaga (RAB Pengajuan)</h5>
                            <small class="text-muted">Pagu anggaran dan penyerapan realisasi belanja tiap lembaga</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="<?= base_url('admin/realis') ?>" class="btn btn-outline-success btn-sm">
                            <i class="bx bx-link-external me-1"></i> Data Realisasi Lembaga
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th style="width: 30%;">Kode & Nama Lembaga</th>
                                <th class="text-end" style="width: 20%;">Pagu Lembaga</th>
                                <th class="text-end" style="width: 20%;">Terpakai (Serapan)</th>
                                <th class="text-end" style="width: 20%;">Sisa Pagu</th>
                                <th class="text-center" style="width: 15%;">Persentase Serapan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($lembaga_summary['items'])) : ?>
                                <?php $no = 1; foreach ($lembaga_summary['items'] as $lm) : ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td class="fw-bold text-dark">
                                            <span class="badge bg-light text-dark border me-1"><?= $lm['kode'] ?></span>
                                            <?= $lm['nama'] ?>
                                        </td>
                                        <td class="text-end"><?= rupiah($lm['pagu']) ?></td>
                                        <td class="text-end text-danger fw-bold"><?= rupiah($lm['pakai']) ?></td>
                                        <td class="text-end fw-bold <?= $lm['sisa'] < 0 ? 'text-danger' : 'text-success' ?>">
                                            <?= rupiah($lm['sisa']) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $badge_lm = 'bg-success';
                                            if ($lm['persen'] > 100) $badge_lm = 'bg-danger';
                                            elseif ($lm['persen'] >= 85) $badge_lm = 'bg-warning text-dark';
                                            elseif ($lm['persen'] >= 50) $badge_lm = 'bg-info text-dark';
                                            ?>
                                            <span class="badge <?= $badge_lm ?>"><?= $lm['persen'] ?>%</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6" class="text-center py-3 text-muted">Belum ada data lembaga pada tahun <?= $tahun ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="table-secondary fw-bold">
                            <tr>
                                <td colspan="2" class="text-center">TOTAL KELOMPOK LEMBAGA</td>
                                <td class="text-end"><?= rupiah($lembaga_summary['total_pagu']) ?></td>
                                <td class="text-end text-danger"><?= rupiah($lembaga_summary['total_pakai']) ?></td>
                                <td class="text-end <?= $lembaga_summary['total_sisa'] < 0 ? 'text-danger' : 'text-success' ?>">
                                    <?= rupiah($lembaga_summary['total_sisa']) ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-dark"><?= $lembaga_summary['total_persen'] ?>%</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- KELOMPOK 5: PAGU KHUSUS (TERPUSAT) -->
        <!-- ========================================================================= -->
        <div class="card radius-10 border shadow-sm mb-4" id="kelompok-khusus">
            <div class="card-header bg-white py-3 border-bottom">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-warning p-2 fs-6 rounded-circle text-dark"><i class="bx bx-star"></i></span>
                        <div>
                            <h5 class="mb-0 fw-bold">5. Pagu Khusus (Pusat / Biro)</h5>
                            <small class="text-muted">Pagu anggaran terpusat seperti Nikmus, Disposisi, Kebijakan, Sarpras, dll</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="<?= base_url('admin/pagu') ?>" class="btn btn-outline-warning btn-sm text-dark">
                            <i class="bx bx-link-external me-1"></i> Master List Pagu
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th style="width: 30%;">Kategori Pagu Khusus</th>
                                <th class="text-end" style="width: 20%;">Pagu Anggaran</th>
                                <th class="text-end" style="width: 20%;">Terpakai (Realisasi)</th>
                                <th class="text-end" style="width: 20%;">Sisa Anggaran</th>
                                <th class="text-center" style="width: 15%;">Persentase Serapan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($khusus_summary['items'])) : ?>
                                <?php $no = 1; foreach ($khusus_summary['items'] as $item) : ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td class="fw-bold text-dark">
                                            <span class="badge bg-light text-dark border me-1"><?= $item['kode'] ?></span>
                                            <?= $item['nama'] ?>
                                        </td>
                                        <td class="text-end"><?= rupiah($item['pagu']) ?></td>
                                        <td class="text-end text-danger fw-bold"><?= rupiah($item['pakai']) ?></td>
                                        <td class="text-end fw-bold <?= $item['sisa'] < 0 ? 'text-danger' : 'text-success' ?>">
                                            <?= rupiah($item['sisa']) ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $badge_pk = 'bg-success';
                                            if ($item['persen'] > 100) $badge_pk = 'bg-danger';
                                            elseif ($item['persen'] >= 85) $badge_pk = 'bg-warning text-dark';
                                            elseif ($item['persen'] >= 50) $badge_pk = 'bg-info text-dark';
                                            ?>
                                            <span class="badge <?= $badge_pk ?>"><?= $item['persen'] ?>%</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6" class="text-center py-3 text-muted">Belum ada data pagu khusus pada tahun <?= $tahun ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="table-secondary fw-bold">
                            <tr>
                                <td colspan="2" class="text-center">TOTAL KELOMPOK PAGU KHUSUS</td>
                                <td class="text-end"><?= rupiah($khusus_summary['total_pagu']) ?></td>
                                <td class="text-end text-danger"><?= rupiah($khusus_summary['total_pakai']) ?></td>
                                <td class="text-end <?= $khusus_summary['total_sisa'] < 0 ? 'text-danger' : 'text-success' ?>">
                                    <?= rupiah($khusus_summary['total_sisa']) ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-dark"><?= $khusus_summary['total_persen'] ?>%</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- KELOMPOK 6: PENGELUARAN LAINNYA & OPERASIONAL NON-PAGU -->
        <!-- ========================================================================= -->
        <div class="card radius-10 border shadow-sm mb-4" id="kelompok-lain">
            <div class="card-header bg-white py-3 border-bottom">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-danger p-2 fs-6 rounded-circle"><i class="bx bx-layer"></i></span>
                        <div>
                            <h5 class="mb-0 fw-bold">6. Pengeluaran Lainnya (Non-Pagu / Insidental)</h5>
                            <small class="text-muted">Pencatatan pengeluaran non-pagu, panjar, dan saldo peminjaman</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th style="width: 35%;">Kategori Transaksi</th>
                                <th class="text-end" style="width: 20%;">Nominal Masuk/Pinjam</th>
                                <th class="text-end" style="width: 20%;">Nominal Keluar/Bayar</th>
                                <th class="text-end" style="width: 20%;">Sisa / Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">1</td>
                                <td class="fw-bold">Pengeluaran Lain-lain (Umum)</td>
                                <td class="text-end text-muted">-</td>
                                <td class="text-end text-danger fw-bold"><?= rupiah($lain_summary['keluar_lain']) ?></td>
                                <td class="text-end text-muted">-</td>
                            </tr>
                            <tr>
                                <td class="text-center">2</td>
                                <td class="fw-bold">Pengeluaran Panjar Kasir</td>
                                <td class="text-end text-muted">-</td>
                                <td class="text-end text-danger fw-bold"><?= rupiah($lain_summary['panjar']) ?></td>
                                <td class="text-end text-muted">-</td>
                            </tr>
                            <tr>
                                <td class="text-center">3</td>
                                <td class="fw-bold">Peminjaman & Cicilan Pengembalian</td>
                                <td class="text-end"><?= rupiah($lain_summary['pinjam']) ?></td>
                                <td class="text-end text-success fw-bold"><?= rupiah($lain_summary['cicilan']) ?></td>
                                <td class="text-end fw-bold <?= $lain_summary['sisa_pinjam'] > 0 ? 'text-danger' : 'text-success' ?>">
                                    <?= rupiah($lain_summary['sisa_pinjam']) ?> (Belum Lunas)
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
<!--end page wrapper -->

<style>
@media print {
    .sidebar-wrapper,
    header,
    .page-breadcrumb,
    .btn,
    footer,
    .switcher-wrapper {
        display: none !important;
    }
    .page-wrapper {
        margin-left: 0 !important;
        margin-top: 0 !important;
        padding: 0 !important;
    }
    .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
        break-inside: avoid;
    }
}
</style>
