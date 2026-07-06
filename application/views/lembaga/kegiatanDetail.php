<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Detail Kegiatan</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('rab') ?>"><i class="bx bx-folder-open"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Rincian Program & Kegiatan</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">1. Rincian Program</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="20%">Kode Program</th>
                                <td><?= $dppk->kode_program ?></td>
                            </tr>
                            <tr>
                                <th>Kode Kegiatan</th>
                                <td><?= $dppk->id_dppk ?></td>
                            </tr>
                            <tr>
                                <th>Program</th>
                                <td><?= $dppk->program ?></td>
                            </tr>
                            <tr>
                                <th>Kegiatan</th>
                                <td><?= $dppk->kegiatan ?></td>
                            </tr>
                            <tr>
                                <th>Rencana/Bulan Program</th>
                                <td>
                                    <?php
                                    if (!empty($dppk->bulan)) {
                                        $input_bulan = array_map('intval', explode(',', $dppk->bulan));
                                        if (function_exists('bulan')) {
                                            $output = array_map('bulan', $input_bulan);
                                            echo implode(', ', $output);
                                        } else {
                                            echo $dppk->bulan;
                                        }
                                    } else {
                                        echo "-";
                                    }
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card radius-10">
                    <div class="card-body">
                        <h5 class="mb-3">2. Rincian RAB</h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode RAB</th>
                                        <th>Nama Barang/Jasa</th>
                                        <th>Volume</th>
                                        <th>Satuan</th>
                                        <th>Harga Satuan</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($rab as $r) : ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $r->kode ?></td>
                                            <td><?= $r->nama ?></td>
                                            <td><?= $r->qty ?></td>
                                            <td><?= $r->satuan ?></td>
                                            <td><?= rupiah($r->harga_satuan) ?></td>
                                            <td><?= rupiah($r->total) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="6" class="text-end">Total Nominal RAB:</th>
                                        <th><?= rupiah($totalRab->total ?? 0) ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card radius-10">
                    <div class="card-body">
                        <h5 class="mb-3">3. Rincian Pemakaian (Realisasi)</h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Pencairan</th>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>Sumber</th>
                                        <th>Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    // Combine realis and real_sm if needed, or list them separately. We'll list them all.
                                    foreach ($realis as $pakai) : ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $pakai->kode ?></td>
                                            <td><?= $pakai->tgl ?? '-' ?></td>
                                            <td><?= $pakai->keterangan ?? '-' ?></td>
                                            <td>Bank</td>
                                            <td><?= rupiah($pakai->nominal) ?></td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <?php
                                    foreach ($real_sm as $pakai_sm) : ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $pakai_sm->kode ?></td>
                                            <td><?= $pakai_sm->tgl ?? '-' ?></td>
                                            <td><?= $pakai_sm->keterangan ?? '-' ?></td>
                                            <td>Tunai (SM)</td>
                                            <td><?= rupiah($pakai_sm->nominal) ?></td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <?php if (empty($realis) && empty($real_sm)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Belum ada pemakaian untuk kegiatan ini.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-end">Total Pemakaian:</th>
                                        <th><?= rupiah($totalPakai ?? 0) ?></th>
                                    </tr>
                                    <tr class="bg-light">
                                        <th colspan="5" class="text-end">Sisa Anggaran:</th>
                                        <th><?= rupiah(($totalRab->total ?? 0) - ($totalPakai ?? 0)) ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->