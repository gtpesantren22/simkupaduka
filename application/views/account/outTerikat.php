<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Dana Terikat</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-lock-alt"></i></a>
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
                                <h6 class="mb-0 fw-bold"><i class="bx bx-lock-alt text-primary me-1"></i> Dana Terikat (Tahun <?= $tahun ?>)</h6>
                                <small class="text-muted">Total Realisasi Terpakai: <strong class="text-danger"><?= rupiah($sumData->jml ?? 0); ?></strong></small>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTrackingTerikat" aria-expanded="false" aria-controls="collapseTrackingTerikat">
                                    <i class="bx bx-chart me-1"></i> Tracking Limit Anggaran
                                </button>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addTerikatModal">
                                    <i class="bx bx-plus-circle me-1"></i> Tambah Pengeluaran
                                </button>
                            </div>
                        </div>

                        <!-- Collapsible Progress Cards Grid (Hidden by default) -->
                        <div class="collapse mt-3" id="collapseTrackingTerikat">
                            <div class="p-3 border radius-10 bg-light">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="mb-0 fw-bold fs-13 text-uppercase text-muted"><i class="bx bx-tachometer text-primary me-1"></i> Status Realisasi per Pos Dana Terikat (Tahun <?= $tahun ?>)</h6>
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="badge bg-primary fs-11"><?= count($tracking_terikat['items'] ?? []) ?> Pos Dana Terikat</span>
                                        <a href="javascript:;" class="badge bg-secondary text-white text-decoration-none fs-11" data-bs-toggle="collapse" data-bs-target="#collapseTrackingTerikat" title="Tutup Tracking">
                                            <i class="bx bx-x me-1"></i>Tutup
                                        </a>
                                    </div>
                                </div>
                                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-3">
                                    <?php if (!empty($tracking_terikat['items'])) : ?>
                                        <?php foreach ($tracking_terikat['items'] as $item) : ?>
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
                                <h6 class="mb-0 fw-bold"><i class="bx bx-list-ul me-1"></i> Data Riwayat Pengeluaran Dana Terikat</h6>
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
                                        <th>Kategori / Pos Dana Terikat</th>
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
                                                <a href="<?= base_url('account/delOutTerikat/' . $a->id_pengeluaran_terikat); ?>" class="btn btn-danger btn-sm tombol-hapus"><i class="bx bx-trash"></i></a>
                                                <button data-id="<?= $a->id_pengeluaran_terikat ?>" data-pos="<?= $a->nama_pos ?>" data-tanggal="<?= $a->tanggal ?>" data-nominal="<?= $a->nominal ?>" data-ket="<?= $a->ket ?>" class="btn btn-warning btn-sm btn-edit-terikat"><i class="bx bx-edit"></i></button>
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

<!-- Modal Tambah Pengeluaran Dana Terikat -->
<div class="modal fade" id="addTerikatModal" tabindex="-1" aria-labelledby="addTerikatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTerikatModalLabel">Tambah Pengeluaran Dana Terikat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('account/saveOutTerikat', ['id' => 'formAddTerikat']); ?>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="pos_terikat_select">Kategori / Pos Dana Terikat</label>
                    <select name="nama_pos" id="pos_terikat_select" class="form-select" required>
                        <option value="">-- Pilih Pos Dana Terikat --</option>
                        <?php if (!empty($tracking_terikat['items'])) : ?>
                            <?php foreach ($tracking_terikat['items'] as $code => $tr) : ?>
                                <?php $is_habis = ($tr['limit'] > 0 && $tr['sisa'] <= 0); ?>
                                <option value="<?= $code ?>" 
                                        data-limit="<?= $tr['limit'] ?>" 
                                        data-pakai="<?= $tr['pakai'] ?>" 
                                        data-sisa="<?= $tr['sisa'] ?>"
                                        <?= $is_habis ? 'disabled' : '' ?>>
                                    <?= $tr['nama'] ?> <?= $tr['limit'] > 0 ? '(Sisa: ' . rupiah($tr['sisa']) . ($is_habis ? ' - HABIS' : '') . ')' : '(Belum Ada Limit)' ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <div id="terikat_limit_info" class="mt-1 fs-12"></div>
                </div>
                <div class="form-group mb-2">
                    <label for="date_terikat">Tanggal</label>
                    <input type="text" name="tanggal" id="date_terikat" class="form-control date" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="form-group mb-2">
                    <label for="nominal_terikat_add">Nominal</label>
                    <input type="text" name="nominal" id="nominal_terikat_add" class="form-control uang" required autocomplete="off" placeholder="Masukkan nominal rupiah">
                    <div id="terikat_nominal_error" class="text-danger mt-1 fs-12 d-none fw-bold"><i class="bx bx-error-circle me-1"></i>Nominal melebihi sisa limit anggaran!</div>
                </div>
                <div class="form-group mb-2">
                    <label for="">Keterangan</label>
                    <input type="text" name="ket" class="form-control" required placeholder="Uraian pengeluaran dana terikat">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" id="btnSubmitAddTerikat" class="btn btn-primary"><i class="bx bx-save"></i> Simpan Data</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<!-- Modal Edit Pengeluaran Dana Terikat -->
<div class="modal fade" id="modal-edit-terikat" tabindex="-1" aria-labelledby="modalEditTerikatLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditTerikatLabel">Edit Pengeluaran Dana Terikat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('account/editOutTerikat', ['id' => 'formEditTerikat']); ?>
            <input type="hidden" id="id_terikat_edit" name="id_pengeluaran_terikat">
            <input type="hidden" id="id_out_terikat_edit" name="id_out">
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="pos_terikat_edit">Kategori / Pos Dana Terikat</label>
                    <select name="nama_pos" id="pos_terikat_edit" class="form-select" required>
                        <option value="">-- Pilih Pos Dana Terikat --</option>
                        <?php if (!empty($tracking_terikat['items'])) : ?>
                            <?php foreach ($tracking_terikat['items'] as $code => $tr) : ?>
                                <option value="<?= $code ?>" 
                                        data-limit="<?= $tr['limit'] ?>" 
                                        data-pakai="<?= $tr['pakai'] ?>" 
                                        data-sisa="<?= $tr['sisa'] ?>">
                                    <?= $tr['nama'] ?> <?= $tr['limit'] > 0 ? '(Sisa: ' . rupiah($tr['sisa']) . ')' : '(Belum Ada Limit)' ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <div id="terikat_edit_limit_info" class="mt-1 fs-12"></div>
                </div>
                <div class="form-group mb-2">
                    <label for="">Tanggal</label>
                    <input type="text" name="tanggal" class="form-control date tanggal-edit-terikat" required>
                </div>
                <div class="form-group mb-2">
                    <label for="nominal_terikat_edit">Nominal</label>
                    <input type="text" name="nominal" id="nominal_terikat_edit" class="form-control uang" required autocomplete="off">
                    <div id="terikat_edit_nominal_error" class="text-danger mt-1 fs-12 d-none fw-bold"><i class="bx bx-error-circle me-1"></i>Nominal melebihi sisa limit anggaran!</div>
                </div>
                <div class="form-group mb-2">
                    <label for="ket_terikat_edit">Keterangan</label>
                    <input type="text" name="ket" id="ket_terikat_edit" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" id="btnSubmitEditTerikat" class="btn btn-primary"><i class="bx bx-save"></i> Simpan Perubahan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script src="<?= base_url('vertical/'); ?>assets/js/jquery.min.js"></script>

<script>
    // Realtime limit checker for Add Modal
    function checkTerikatLimitAdd() {
        var selected = $('#pos_terikat_select').find(':selected');
        var limit = parseFloat(selected.data('limit')) || 0;
        var sisa = parseFloat(selected.data('sisa')) || 0;
        var rawNom = $('#nominal_terikat_add').val().replace(/[^0-9]/g, '');
        var nominal = parseFloat(rawNom) || 0;

        if (selected.val() && limit > 0) {
            $('#terikat_limit_info').html('<span class="text-muted">Limit: <strong>Rp ' + limit.toLocaleString('id-ID') + '</strong> | Sisa Limit: <strong class="' + (sisa <= 0 ? 'text-danger' : 'text-success') + '">Rp ' + sisa.toLocaleString('id-ID') + '</strong></span>');
        } else if (selected.val()) {
            $('#terikat_limit_info').html('<span class="text-secondary"><i class="bx bx-info-circle me-1"></i>Pos ini belum dibatasi limit nominal oleh Admin.</span>');
        } else {
            $('#terikat_limit_info').html('');
        }

        if (limit > 0 && nominal > sisa) {
            $('#terikat_nominal_error').removeClass('d-none');
            $('#btnSubmitAddTerikat').prop('disabled', true);
            return false;
        } else {
            $('#terikat_nominal_error').addClass('d-none');
            $('#btnSubmitAddTerikat').prop('disabled', false);
            return true;
        }
    }

    $('#pos_terikat_select').on('change', checkTerikatLimitAdd);
    $('#nominal_terikat_add').on('input keyup change', checkTerikatLimitAdd);

    $('#formAddTerikat').on('submit', function(e) {
        if (!checkTerikatLimitAdd()) {
            e.preventDefault();
            alert('Input ditolak! Nominal melebihi sisa limit anggaran yang tersedia.');
            return false;
        }
    });

    // Realtime limit checker for Edit Modal
    var currentTerikatItemNominal = 0;
    var currentTerikatOriginalPos = '';

    function checkTerikatLimitEdit() {
        var selected = $('#pos_terikat_edit').find(':selected');
        var limit = parseFloat(selected.data('limit')) || 0;
        var sisa = parseFloat(selected.data('sisa')) || 0;
        var rawNom = $('#nominal_terikat_edit').val().replace(/[^0-9]/g, '');
        var nominal = parseFloat(rawNom) || 0;

        // If editing the same category, available budget includes current item's nominal
        var availableSisa = sisa;
        if (selected.val() === currentTerikatOriginalPos) {
            availableSisa = sisa + currentTerikatItemNominal;
        }

        if (selected.val() && limit > 0) {
            $('#terikat_edit_limit_info').html('<span class="text-muted">Limit: <strong>Rp ' + limit.toLocaleString('id-ID') + '</strong> | Sisa Tersedia: <strong class="' + (availableSisa <= 0 ? 'text-danger' : 'text-success') + '">Rp ' + availableSisa.toLocaleString('id-ID') + '</strong></span>');
        } else if (selected.val()) {
            $('#terikat_edit_limit_info').html('<span class="text-secondary"><i class="bx bx-info-circle me-1"></i>Pos ini belum dibatasi limit nominal oleh Admin.</span>');
        } else {
            $('#terikat_edit_limit_info').html('');
        }

        if (limit > 0 && nominal > availableSisa) {
            $('#terikat_edit_nominal_error').removeClass('d-none');
            $('#btnSubmitEditTerikat').prop('disabled', true);
            return false;
        } else {
            $('#terikat_edit_nominal_error').addClass('d-none');
            $('#btnSubmitEditTerikat').prop('disabled', false);
            return true;
        }
    }

    $('#pos_terikat_edit').on('change', checkTerikatLimitEdit);
    $('#nominal_terikat_edit').on('input keyup change', checkTerikatLimitEdit);

    $('#formEditTerikat').on('submit', function(e) {
        if (!checkTerikatLimitEdit()) {
            e.preventDefault();
            alert('Edit ditolak! Nominal melebihi sisa limit anggaran yang tersedia.');
            return false;
        }
    });

    $('.btn-edit-terikat').on('click', function(e) {
        var id = $(this).data('id');
        var pos = $(this).data('pos');
        var tanggal = $(this).data('tanggal');
        var nominal = $(this).data('nominal');
        var ket = $(this).data('ket');

        currentTerikatItemNominal = parseFloat(nominal) || 0;
        currentTerikatOriginalPos = pos;

        $('#id_terikat_edit').val(id);
        $('#id_out_terikat_edit').val(id);
        $('#pos_terikat_edit').val(pos).change();
        $('.tanggal-edit-terikat').val(tanggal);
        $('#nominal_terikat_edit').val(nominal);
        $('#ket_terikat_edit').val(ket);

        checkTerikatLimitEdit();

        $('#modal-edit-terikat').modal('show');
    });
</script>
