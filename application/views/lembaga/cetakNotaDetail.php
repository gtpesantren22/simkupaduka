<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Cetak Nota</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('lembaga/cetakNota') ?>"><i class="bx bx-printer"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail Nota Mitra</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <a href="<?= base_url('lembaga/cetakNotaKPAPrint/' . $pj->kode_pengajuan) ?>" target="_blank" class="btn btn-danger btn-sm px-3 me-2"><i class="bx bx-printer"></i> Cetak Nota KPA</a>
                <a href="<?= base_url('lembaga/cetakNota') ?>" class="btn btn-secondary btn-sm px-3"><i class="bx bx-arrow-back"></i> Kembali</a>
            </div>
        </div>
        <!--end breadcrumb-->

        <!-- Info Pengajuan Card -->
        <div class="card radius-10 bg-gradient-cosmic text-white">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-1 text-white">Informasi Pengajuan</h5>
                        <p class="mb-0 font-monospace" style="opacity: 0.9;">Kode: <?= $pj->kode_pengajuan ?></p>
                    </div>
                    <div class="text-end">
                        <h6 class="mb-1 text-white">Periode: <?= $bulan[$pj->bulan] ?> <?= $pj->tahun ?></h6>
                        <span class="badge bg-light text-dark shadow-sm">
                            <i class="bx bx-info-circle align-middle me-1"></i> 
                            <?= $pj->cair == 1 ? "Sudah Dicairkan" : "Proses Pencairan" ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <?php if (empty($mitra)) : ?>
            <div class="alert border-0 border-start border-5 border-warning alert-dismissible fade show py-2">
                <div class="d-flex align-items-center">
                    <div class="font-35 text-warning"><i class='bx bx-info-circle'></i></div>
                    <div class="ms-3">
                        <h6 class="mb-0 text-warning">Tidak Ada Data Mitra</h6>
                        <div>Pengajuan ini tidak memiliki item belanja barang non-tunai yang ditugaskan ke Mitra.</div>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <!-- Grouped by Mitra -->
            <?php foreach ($mitra as $m) : ?>
                <div class="card radius-10 border-top border-0 border-3 border-danger mb-4 shadow-sm">
                    <div class="card-header bg-light py-3">
                        <div class="row align-items-center">
                            <!-- Mitra Details Header -->
                            <div class="col-md-7">
                                <h6 class="mb-1 text-dark"><i class="bx bx-store-alt align-middle me-1 text-danger"></i> <?= $m->nama ?></h6>
                                <p class="mb-0 text-muted small">
                                    <strong>PJ:</strong> <?= $m->pj ?> &nbsp;|&nbsp; <strong>HP:</strong> <?= $m->hp ?>
                                </p>
                            </div>
                            <!-- Rincian & Action Buttons on the Header -->
                            <div class="col-md-5 text-md-end mt-2 mt-md-0 d-flex justify-content-md-end align-items-center gap-3">
                                <div>
                                    <small class="text-muted d-block">Total Rincian Mitra</small>
                                    <span class="fs-6 fw-bold text-danger"><?= rupiah($m->total) ?></span>
                                </div>
                                <a href="<?= base_url('lembaga/cetakNotaPrint/' . $pj->kode_pengajuan . '/' . $m->id_mitra) ?>" target="_blank" class="btn btn-outline-danger btn-sm px-3 radius-30">
                                    <i class="bx bx-printer"></i> Cetak Nota
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" width="50">No</th>
                                        <th>Nama Item Belanja</th>
                                        <th class="text-center" width="100">Quantity</th>
                                        <th class="text-center" width="100">Satuan</th>
                                        <th class="text-end" width="150">Harga</th>
                                        <th class="text-end" width="150">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1; 
                                    foreach ($m->items as $item) : 
                                        $ket = $item->ket;
                                        preg_match('/^(.*?)\s*-\s*@/u', $ket, $namaMatch);
                                        $nama = isset($namaMatch[1]) ? trim($namaMatch[1]) : null;

                                        preg_match('/\d+\s+([^\s]+)\s+x/u', $ket, $satuanMatch);
                                        $satuan = isset($satuanMatch[1]) ? trim($satuanMatch[1]) : null;

                                        $display_nama = $nama ? $nama : $ket;
                                        $display_satuan = $satuan ? $satuan : '-';
                                        
                                        $harga = isset($item->harga) && $item->harga > 0 ? $item->harga : ($item->vol > 0 ? $item->nominal / $item->vol : 0);
                                    ?>
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
                                            <td><?= $display_nama ?></td>
                                            <td class="text-center"><?= $item->vol ?></td>
                                            <td class="text-center"><?= $display_satuan ?></td>
                                            <td class="text-end"><?= rupiah($harga) ?></td>
                                            <td class="text-end fw-bold"><?= rupiah($item->vol * $harga) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<!--end page wrapper -->

<style>
.bg-gradient-cosmic {
    background: #8e2de2;
    background: -webkit-linear-gradient(to right, #4a00e0, #8e2de2);
    background: linear-gradient(to right, #4a00e0, #8e2de2);
}
</style>
