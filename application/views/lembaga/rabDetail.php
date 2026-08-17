<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>
<?php

// $total = $sumA1->total + $sumB1->total + $sumC1->total + $sumA2->total + $sumB2->total + $sumC2->total;
// $pakai = $pakaiA1->nominal + $pakaiB1->nominal + $pakaiC1->nominal  + $pakaiA2->nominal + $pakaiB2->nominal + $pakaiC2->nominal;

try {
    //code...
    $pesern = round(($totalReal->jml / $totalRab->jml) * 100, 0);
} catch (DivisionByZeroError $th) {
    //throw $th;
    $pesern = 0;
}
if ($pesern >= 0 && $pesern <= 25) {
    $bg = 'progress-bar-success';
} elseif ($pesern >= 26 && $pesern <= 50) {
    $bg = 'progress-bar-primary';
} elseif ($pesern >= 51 && $pesern <= 75) {
    $bg = 'progress-bar-warning';
} elseif ($pesern >= 76 && $pesern <= 100) {
    $bg = 'progress-bar-danger';
}

?>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Daftar RAB Lembaga</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-folder-open"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Rencana Belanja</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">Kode Lembaga</li>
                                    <li class="list-group-item">Nama</li>
                                    <li class="list-group-item">PJ</li>
                                    <li class="list-group-item">No. Hp</li>
                                    <li class="list-group-item">Pelaksanaan</li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">: <?= $lembaga->kode; ?></li>
                                    <li class="list-group-item">: <?= $lembaga->nama; ?></li>
                                    <li class="list-group-item">: <?= $lembaga->pj; ?></li>
                                    <li class="list-group-item">: <?= $lembaga->hp; ?></li>
                                    <li class="list-group-item">: <?= $lembaga->waktu; ?></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group">
                                    <?php foreach ($jenis as $dtJenis) : ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $dtJenis->kode_jns . '. ' . $dtJenis->nama ?> <span><?= rupiah($rabJml[$dtJenis->kode_jns]->jml3); ?></span></li>
                                    <?php endforeach; ?>
                                    <li class=" list-group-item d-flex justify-content-between align-items-center active" aria-current="true">TOTAL RAB <span">
                                            <?= rupiah($totalRab->jml); ?></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="progress" style="height:20px;">
                                    <div class="progress-bar <?= $bg ?> progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?= $pesern ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $pesern ?>%"><?= $pesern ?>%</div>
                                </div>

                                <!-- <div class="progress active">
                                    <div class="progress-bar <?= $bg ?> progress-bar-striped" role="progressbar"
                                        aria-valuenow="<?= $pesern ?>" aria-valuemin="0" aria-valuemax="100"
                                        style="width: <?= $pesern ?>%"><?= $pesern ?>%
                                    </div>
                                </div> -->

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card radius-10">
                    <div class="card-body">
                        <!-- Dropdown filter bulan di atas tabel -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-auto">
                                <label for="filterBulan" class="form-label mb-0 fw-bold"><i class="bx bx-calendar"></i> Filter Bulan:</label>
                            </div>
                            <div class="col-auto">
                                <form method="get" action="">
                                    <select name="bulan" id="filterBulan" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="">-- Semua Bulan --</option>
                                        <?php for ($m = 1; $m <= 12; $m++): ?>
                                            <option value="<?= $m ?>" <?= (isset($selected_bulan) && $selected_bulan == $m) ? 'selected' : '' ?>><?= bulan($m) ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="example2" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Program</th>
                                        <th>Kegiatan</th>
                                        <th>Jadwal Bulan</th>
                                        <th>Total RAB</th>
                                        <th>Sisa</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($program as $index => $a) : ?>
                                        <!-- Baris utama program. Dapat di-klik untuk toggle rincian. -->
                                        <tr data-bs-toggle="collapse" data-bs-target="#rincian-<?= $index ?>" style="cursor: pointer;">
                                            <td><?= $no++ ?></td>
                                            <td>
                                                <i class="bx bx-plus-circle text-primary me-1"></i>
                                                <?= $a['kode_program'] . '. ' . $a['program'] ?>
                                            </td>
                                            <td><?= $a['kegiatan'] ?></td>
                                            <td><?= !empty($a['bulan']) ? $a['bulan'] : '-' ?></td>
                                            <td><?= rupiah($a['total']) ?></td>
                                            <td><?= rupiah($a['sisa']) ?></td>
                                            <td>
                                                <a href="<?= base_url('rab/kegiatanDetail/' . $a['id_dppk']) ?>" class="btn btn-sm btn-info text-white" onclick="event.stopPropagation()"><i class="bx bx-info-circle"></i> Detail</a>
                                            </td>
                                        </tr>
                                        <!-- Baris rincian item belanja collapsible -->
                                        <tr class="collapse" id="rincian-<?= $index ?>">
                                            <td colspan="7" class="bg-light p-3">
                                                <div class="card card-body bg-white mb-0 shadow-none border">
                                                    <h6 class="fw-bold mb-3 text-dark"><i class="bx bx-list-ul"></i> Rincian Item Belanja Kegiatan</h6>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-sm bg-white mb-0 text-center">
                                                            <thead class="bg-light">
                                                                <tr>
                                                                    <th width="5%">No</th>
                                                                    <th>Nama Barang/Jasa</th>
                                                                    <th>Volume</th>
                                                                    <th>Satuan</th>
                                                                    <th>Harga Satuan</th>
                                                                    <th>Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (empty($a['rab_items'])) : ?>
                                                                    <tr>
                                                                        <td colspan="6" class="text-muted py-3">Rincian item belanja belum diisi.</td>
                                                                    </tr>
                                                                <?php else : ?>
                                                                    <?php $subNo = 1; foreach ($a['rab_items'] as $item) : ?>
                                                                        <tr>
                                                                            <td><?= $subNo++ ?></td>
                                                                            <td class="text-start"><?= $item->nama ?></td>
                                                                            <td><?= $item->qty ?></td>
                                                                            <td><?= $item->satuan ?></td>
                                                                            <td><?= rupiah($item->harga_satuan) ?></td>
                                                                            <td><?= rupiah($item->total) ?></td>
                                                                        </tr>
                                                                    <?php endforeach; ?>
                                                                <?php endif; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
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
<!--end page wrapper -->