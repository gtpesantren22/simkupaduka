<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Program & Pengembangan</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-cube-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Pengeluaran</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <!-- Tracking & Action Toolbar -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card radius-10 border shadow-none mb-0">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <h6 class="mb-0 fw-bold"><i class="bx bx-cube-alt text-primary me-1"></i> Program & Pengembangan (Tahun <?= $tahun ?>)</h6>
                                <small class="text-muted">Total Realisasi Terpakai: <strong class="text-danger"><?= rupiah($sumData->jml ?? 0); ?></strong></small>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <div class="dropdown">
                                    <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bx bx-slider-alt me-1"></i> Limit & Anggaran
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li>
                                            <a class="dropdown-item" href="javascript:;" data-bs-toggle="collapse" data-bs-target="#collapseTrackingProg" aria-expanded="false" aria-controls="collapseTrackingProg">
                                                <i class="bx bx-chart me-2 text-primary"></i> Tracking Limit Anggaran
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:;" data-bs-toggle="modal" data-bs-target="#modalSettingAnggaranProg">
                                                <i class="bx bx-cog me-2 text-primary"></i> Atur Limit & Kategori
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProgModal">
                                    <i class="bx bx-plus-circle me-1"></i> Tambah Pengeluaran
                                </button>
                            </div>
                        </div>

                        <!-- Collapsible Progress Cards Grid (Hidden by default) -->
                        <div class="collapse mt-3" id="collapseTrackingProg">
                            <div class="p-3 border radius-10 bg-light">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="mb-0 fw-bold fs-13 text-uppercase text-muted"><i class="bx bx-tachometer text-primary me-1"></i> Status Realisasi per Pos Program (Tahun <?= $tahun ?>)</h6>
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="badge bg-primary fs-11"><?= count($tracking_prog['items'] ?? []) ?> Pos Program</span>
                                        <a href="javascript:;" class="badge bg-secondary text-white text-decoration-none fs-11" data-bs-toggle="collapse" data-bs-target="#collapseTrackingProg" title="Tutup Tracking">
                                            <i class="bx bx-x me-1"></i>Tutup
                                        </a>
                                    </div>
                                </div>
                                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-3">
                                    <?php if (!empty($tracking_prog['items'])) : ?>
                                        <?php foreach ($tracking_prog['items'] as $item) : ?>
                                            <div class="col">
                                                <div class="card radius-10 border shadow-sm h-100 mb-0 bg-white">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                            <span class="fw-bold text-dark fs-13"><?= $item['nama'] ?></span>
                                                            <span class="badge <?= $item['badge_class'] ?> fs-11"><?= $item['status'] ?></span>
                                                        </div>
                                                        <div class="mb-2">
                                                            <div class="d-flex justify-content-between text-muted fs-12 mb-1">
                                                                <span>Pakai: <strong class="text-dark"><?= rupiah($item['pakai']) ?></strong></span>
                                                                <span>Limit: <?= $item['limit'] > 0 ? rupiah($item['limit']) : '-' ?></span>
                                                            </div>
                                                            <div class="progress" style="height: 7px;">
                                                                <div class="progress-bar <?= $item['bar_class'] ?>" role="progressbar" style="width: <?= min(100, $item['persen']) ?>%;" aria-valuenow="<?= $item['persen'] ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center fs-12 pt-1 border-top">
                                                            <span class="text-muted">Sisa: <strong class="<?= $item['sisa'] < 0 ? 'text-danger' : 'text-success' ?>"><?= rupiah($item['sisa']) ?></strong></span>
                                                            <span class="text-muted fw-bold"><?= $item['limit'] > 0 ? $item['persen'] . '%' : '-' ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="mb-0 fw-bold"><i class="bx bx-list-ul me-1"></i> Data Riwayat Program & Pengembangan</h6>
                            </div>
                            <div>
                                <span class="badge bg-danger fs-13 px-3 py-2">Total Terpakai: <?= rupiah($sumData->jml ?? 0); ?></span>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kategori / Pos Program</th>
                                        <th>Tanggal</th>
                                        <th>Nominal</th>
                                        <th>Ket</th>
                                        <th>Act</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data as $a) : ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><span class="badge bg-dark"><?= $a->nama_pos ?></span></td>
                                            <td><?= $a->tanggal ?></td>
                                            <td class="fw-bold"><?= rupiah($a->nominal) ?></td>
                                            <td><?= $a->ket ?></td>
                                            <td>
                                                <a href="<?= base_url('account/delOutProg/' . $a->id_pengeluaran_prog); ?>" class="btn btn-danger btn-sm tombol-hapus"><i class="bx bx-trash"></i></a>
                                                <button data-id="<?= $a->id_pengeluaran_prog ?>" data-pos="<?= $a->nama_pos ?>" data-tanggal="<?= $a->tanggal ?>" data-nominal="<?= $a->nominal ?>" data-ket="<?= $a->ket ?>" class="btn btn-warning btn-sm btn-edit-prog"><i class="bx bx-edit"></i></button>
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
        <!--end row-->
    </div>
</div>
<!--end page wrapper -->

<!-- Modal Setting Limit & Kategori Anggaran Program -->
<div class="modal fade" id="modalSettingAnggaranProg" tabindex="-1" aria-labelledby="modalSettingAnggaranProgLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="modalSettingAnggaranProgLabel"><i class="bx bx-slider-alt me-1"></i> Atur Limit & Pos Program (Tahun <?= $tahun ?>)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('account/saveAnggaranProg'); ?>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle mb-3">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th style="width: 5%;">No</th>
                                <th>Nama Pos / Program</th>
                                <th style="width: 45%;">Batas Limit Nominal</th>
                                <th style="width: 10%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($tracking_prog['items'])) : ?>
                                <?php $no = 1; foreach ($tracking_prog['items'] as $code => $tr) : ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td>
                                            <span class="fw-bold"><?= $tr['nama'] ?></span>
                                            <div class="text-muted fs-11">Terpakai: <?= rupiah($tr['pakai']) ?></div>
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">Rp</span>
                                                <input type="text" name="limit[<?= $tr['id'] ?>]" value="<?= $tr['limit'] > 0 ? rupiah($tr['limit']) : '' ?>" class="form-control uang" placeholder="0">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <?php if (!empty($tr['id'])) : ?>
                                                <a href="<?= base_url('account/delPosProg/' . $tr['id']) ?>" class="btn btn-outline-danger btn-sm p-1 tombol-hapus" title="Hapus Pos"><i class="bx bx-trash"></i></a>
                                            <?php else : ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="card border border-dashed radius-10 mb-0 bg-light">
                    <div class="card-body p-2">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bx bx-plus-circle text-primary me-1"></i>
                            <span class="fw-bold fs-12 text-primary">Tambah Pos Program Baru</span>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-7">
                                <input type="text" name="new_pos" class="form-control form-control-sm text-uppercase" placeholder="Nama Pos Program Baru">
                            </div>
                            <div class="col-md-5">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" name="new_limit" class="form-control uang" placeholder="Limit Nominal">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Simpan Perubahan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- Modal Tambah Pengeluaran Program -->
<div class="modal fade" id="addProgModal" tabindex="-1" aria-labelledby="addProgModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProgModalLabel">Tambah Program & Pengembangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('account/saveOutProg'); ?>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="">Kategori / Pos Program</label>
                    <select name="nama_pos" class="form-select" required>
                        <option value="">-- Pilih Pos Program --</option>
                        <?php if (!empty($tracking_prog['items'])) : ?>
                            <?php foreach ($tracking_prog['items'] as $code => $tr) : ?>
                                <option value="<?= $code ?>"><?= $tr['nama'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group mb-2">
                    <label for="">Tanggal</label>
                    <input type="text" name="tanggal" id="date" class="form-control" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">Nominal</label>
                    <input type="text" name="nominal" class="form-control uang" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">Ket</label>
                    <input type="text" name="ket" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Simpan Data</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- Modal Edit Pengeluaran Program -->
<div class="modal fade" id="modal-edit-prog" tabindex="-1" aria-labelledby="modalEditProgLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditProgLabel">Edit Program & Pengembangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('account/editOutProg'); ?>
            <input type="hidden" id="id_prog_edit" name="id_pengeluaran_prog">
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="">Kategori / Pos Program</label>
                    <select name="nama_pos" id="pos_prog_edit" class="form-select" required>
                        <option value="">-- Pilih Pos Program --</option>
                        <?php if (!empty($tracking_prog['items'])) : ?>
                            <?php foreach ($tracking_prog['items'] as $code => $tr) : ?>
                                <option value="<?= $code ?>"><?= $tr['nama'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group mb-2">
                    <label for="">Tanggal</label>
                    <input type="text" name="tanggal" class="form-control tanggal-edit-prog" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">Nominal</label>
                    <input type="text" name="nominal" id="nominal_prog_edit" class="form-control uang" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">Ket</label>
                    <input type="text" name="ket" id="ket_prog_edit" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Simpan Data</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script src="<?= base_url('vertical/'); ?>assets/js/jquery.min.js"></script>

<script>
    $('.btn-edit-prog').on('click', function(e) {
        var id = $(this).data('id');
        var pos = $(this).data('pos');
        var tanggal = $(this).data('tanggal');
        var nominal = $(this).data('nominal');
        var ket = $(this).data('ket');

        $('#id_prog_edit').val(id);
        $('#pos_prog_edit').val(pos).change();
        $('.tanggal-edit-prog').val(tanggal);
        $('#nominal_prog_edit').val(nominal);
        $('#ket_prog_edit').val(ket);

        $('#modal-edit-prog').modal('show');
    });
</script>
