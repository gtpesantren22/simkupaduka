<?php
$dt = $this->db->query("SELECT SUM(nom_cair) as jml, SUM(IF( stas = 'tunai', nom_cair, 0)) AS tunai, SUM(IF( stas = 'non tunai', nom_cair, 0)) AS brg, SUM(IF( stas = 'tunai', nominal, 0)) AS tunai_asal, SUM(IF( stas = 'non tunai', nominal, 0)) AS brg_asal, SUM(IF( stas = 'tunai', nom_serap, 0)) AS tunai_serap, SUM(IF( stas = 'non tunai', nom_serap, 0)) AS brg_serap FROM $tbl_slct WHERE kode_pengajuan = '$pjn->kode_pengajuan' AND tahun = '$tahun' ")->row();
?>

<style>
    .bg-success-light {
        background-color: rgba(25, 135, 84, 0.1);
    }
    .bg-danger-light {
        background-color: rgba(220, 53, 69, 0.1);
    }
    .bg-primary-light {
        background-color: rgba(13, 110, 253, 0.08);
    }
    .hover-grow {
        transition: transform 0.15s ease;
    }
    .hover-grow:hover {
        transform: scale(1.15);
    }
    .mitra-badge-btn {
        transition: all 0.2s ease;
        font-size: 11.5px;
        font-weight: 500;
        border-radius: 30px !important;
    }
    .mitra-badge-btn:hover {
        background-color: #0d6efd !important;
        color: #fff !important;
    }
    .mitra-selected-pill {
        border-radius: 30px !important;
        font-size: 12px;
        font-weight: 600;
    }
</style>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Pengajuan Lembaga</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-wallet"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Realisasi</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <!-- Metadata & Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 border-start border-3 border-primary">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted small mb-3">Informasi Pengajuan</h6>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bx bx-home-alt fs-4 text-primary me-2"></i>
                            <div>
                                <span class="text-secondary small">Lembaga</span>
                                <h6 class="mb-0 text-dark fw-bold"><?= $lembaga->nama ?></h6>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bx bx-calendar fs-4 text-primary me-2"></i>
                            <div>
                                <span class="text-secondary small">Periode</span>
                                <h6 class="mb-0 text-dark fw-bold"><?= $bulan[$pjn->bulan] . ' ' . $pjn->tahun ?></h6>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bx bx-hash fs-4 text-primary me-2"></i>
                            <div>
                                <span class="text-secondary small">Kode Pengajuan</span>
                                <h6 class="mb-0 text-primary fw-bold"><?= $pjn->kode_pengajuan ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 border-start border-3 border-success">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h6 class="text-uppercase text-muted small mb-1">Dana Cair</h6>
                            <span class="badge bg-success-light text-success mb-3"><i class="bx bx-check-circle me-1"></i>Sudah Cair</span>
                        </div>
                        <h3 class="text-success fw-bold mb-0"><?= rupiah($dcair) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 border-start border-3 border-danger">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h6 class="text-uppercase text-muted small mb-1">Belum Cair</h6>
                            <span class="badge bg-danger-light text-danger mb-3"><i class="bx bx-time me-1"></i>Menunggu Pencairan</span>
                        </div>
                        <h3 class="text-danger fw-bold mb-0"><?= rupiah($dblm) ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card radius-10 shadow-sm border-0">
                    <div class="card-body">
                        <!-- CAIR TUNAI SECTION -->
                        <h5 class="mb-3 d-flex align-items-center gap-2 text-primary"><i class="bx bx-money fs-4"></i> Realisasi Belanja Tunai</h5>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered align-middle" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Kode RAB</th>
                                        <th>Keterangan</th>
                                        <th>Nominal</th>
                                        <th>Disetujui</th>
                                        <th style="width: 250px;">Akan dicairkan</th>
                                        <th>Ket</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no1 = 1;
                                    foreach ($rls as $ls_jns) {
                                        $ids = explode('-', $ls_jns->id_realis);
                                        $idOk = $ids[0];
                                    ?>
                                        <tr>
                                            <td><?= $no1++ ?></td>
                                            <td class="font-monospace small"><?= $ls_jns->kode; ?></td>
                                            <td><?= $ls_jns->ket; ?></td>
                                            <td><?= rupiah($ls_jns->nominal); ?></td>
                                            <td class="fw-semibold text-success"><?= rupiah($ls_jns->nom_cair); ?></td>
                                            <td>
                                                <form action="<?= base_url('kasir/editSerap'); ?>" method="post">
                                                    <input type="hidden" name="id" value="<?= $ls_jns->id_realis; ?>">
                                                    <input type="hidden" name="nom_cair" value="<?= $ls_jns->nom_cair; ?>">
                                                    <input type="hidden" name="kode_pengajuan" value="<?= $ls_jns->kode_pengajuan; ?>">
                                                    <input type="hidden" name="table" value="<?= $tbl_slct ?>">
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control form-control-sm uang fw-bold text-dark" name="serap" value="<?= $ls_jns->nom_serap; ?>" <?= $pjn->cair == 1 ? 'disabled' : '' ?> aria-describedby="button-addon2">
                                                        <button class="btn btn-success" type="submit" <?= $pjn->cair == 1 ? 'disabled' : '' ?> id="button-addon2"><i class="bx bx-check"></i></button>
                                                    </div>
                                                </form>
                                            </td>
                                            <td><span class="badge bg-primary text-uppercase"><?= $ls_jns->stas; ?></span></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-light fw-bold">
                                        <th colspan="3" class="text-end">Total</th>
                                        <th><?= rupiah($dt->tunai_asal) ?></th>
                                        <th class="text-success"><?= rupiah($dt->tunai) ?></th>
                                        <th class="text-primary"><?= rupiah($dt->tunai_serap) ?></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- CAIR BARANG SECTION -->
                        <hr class="my-4">
                        <h5 class="mb-3 d-flex align-items-center gap-2 text-danger"><i class="bx bx-package fs-4"></i> Realisasi Belanja Barang (Mitra)</h5>
                        
                        <div id="mitraTableContainer">
                            <div class="table-responsive">
                                <table id="3" class="table table-hover table-striped table-bordered align-middle" style="width:100%">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th>Kode RAB</th>
                                            <th>Keterangan</th>
                                            <th style="width: 250px;">Mitra / Penyedia</th>
                                            <th>Nominal</th>
                                            <th>Disetujui</th>
                                            <th style="width: 250px;">Akan dicairkan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no2 = 1;
                                        foreach ($rls2 as $ls_jns) {
                                            $ids = explode('-', $ls_jns->id_realis);
                                            $idOk = $ids[0];
                                        ?>
                                            <tr>
                                                <td><?= $no2++ ?></td>
                                                <td class="font-monospace small"><?= $ls_jns->kode; ?></td>
                                                <td><?= $ls_jns->ket; ?></td>
                                                <td>
                                                    <?php if ($ls_jns->pjnDataMitra) { ?>
                                                        <div class="d-inline-flex align-items-center bg-light border border-secondary border-opacity-10 rounded-pill px-2.5 py-1 gap-2">
                                                            <i class="bx bx-store-alt text-secondary"></i>
                                                            <span class="text-dark fw-semibold small"><?= $ls_jns->pjnDataMitra->nama ?></span>
                                                            <?php if ($pjn->cair != 1): ?>
                                                                <a href="#" class="delOrderMitra text-danger ms-1 hover-grow d-flex align-items-center justify-content-center" data-id_order="<?= $ls_jns->pjnDataMitra->id_order ?>" title="Hapus Mitra">
                                                                    <i class="bx bxs-x-circle fs-5"></i>
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="d-flex flex-wrap gap-1" style="max-width: 240px;">
                                                            <?php foreach ($mitra as $mtr) : ?>
                                                                <a href="#" class="getDataLink badge bg-light text-primary border border-primary border-opacity-25 py-1.5 px-2.5 text-decoration-none hover-bg-primary mitra-badge-btn" data-id_mitra="<?= $mtr->id_mitra ?>" data-kode="<?= $ls_jns->kode ?>" data-kode_pengajuan="<?= $ls_jns->kode_pengajuan ?>">
                                                                    <i class="bx bx-plus me-0.5 small"></i><?= $mtr->nama ?>
                                                                </a>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php } ?>
                                                </td>
                                                <td><?= rupiah($ls_jns->nominal); ?></td>
                                                <td class="fw-semibold text-success"><?= rupiah($ls_jns->nom_cair); ?></td>
                                                <td>
                                                    <form action="<?= base_url('kasir/editSerap'); ?>" method="post">
                                                        <input type="hidden" name="id" value="<?= $ls_jns->id_realis; ?>">
                                                        <input type="hidden" name="nom_cair" value="<?= $ls_jns->nom_cair; ?>">
                                                        <input type="hidden" name="kode_pengajuan" value="<?= $ls_jns->kode_pengajuan; ?>">
                                                        <input type="hidden" name="table" value="<?= $tbl_slct ?>">
                                                        <div class="input-group input-group-sm">
                                                            <input type="text" class="form-control form-control-sm uang fw-bold text-dark" <?= $pjn->cair == 1 ? 'disabled' : '' ?> name="serap" value="<?= $ls_jns->nom_serap; ?>" aria-describedby="button-addon2">
                                                            <button class="btn btn-success" type="submit" id="button-addon2" <?= $pjn->cair == 1 ? 'disabled' : '' ?>><i class="bx bx-check"></i></button>
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-light fw-bold">
                                            <th colspan="4" class="text-end">Total</th>
                                            <th><?= rupiah($dt->brg_asal) ?></th>
                                            <th class="text-success"><?= rupiah($dt->brg) ?></th>
                                            <th class="text-primary"><?= rupiah($dt->brg_serap) ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- FORMS & MITRA SUMMARY SECTION -->
                        <hr class="my-4">
                        <div class="row g-4">
                            <?php if ($pjn->cair == 0) : ?>
                                <div class="col-md-7">
                                    <div class="card shadow-sm border-0 border-top border-3 border-success h-100">
                                        <div class="card-body p-4">
                                            <h5 class="card-title mb-4 text-success d-flex align-items-center gap-2"><i class="bx bx-wallet"></i> Form Pencairan Dana</h5>
                                            <?= form_open('kasir/cairkan'); ?>
                                            <input type="hidden" name="kode_pengajuan" value="<?= $pjn->kode_pengajuan ?>">
                                            
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold text-muted">Jumlah yang akan dicairkan</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light text-success fw-bold">Rp</span>
                                                    <input type="text" name="total" class="form-control fw-bold text-success fs-5" id="" value="<?= rupiah($dt->brg_serap + $dt->tunai_serap) ?>" readonly>
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Tanggal Pencairan</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light"><i class="bx bx-calendar"></i></span>
                                                        <input type="text" class="form-control datepickerFlats" id="" name="tgl_cair" placeholder="Pilih Tanggal..." required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Nama Penerima</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control" id="" name="penerima" placeholder="Nama lengkap penerima..." required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <label class="form-label fw-semibold text-muted">Petugas Kasir</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light"><i class="bx bx-user-check"></i></span>
                                                    <input type="text" name="kasir" class="form-control text-muted" value="<?= $user->nama ?>" readonly>
                                                </div>
                                            </div>

                                            <button type="submit" name="cairkan" class="btn btn-success w-100 d-flex align-items-center justify-content-center gap-2 py-2.5 fs-15"><i class="bx bx-save fs-5"></i> Simpan & Selesaikan Pencairan</button>
                                            <?= form_close(); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="<?= $pjn->cair == 0 ? 'col-md-5' : 'col-md-12' ?>">
                                <div class="card shadow-sm border-0 border-top border-3 border-primary h-100">
                                    <div class="card-body p-4" id="mitraListContainer">
                                        <h5 class="card-title mb-4 text-primary d-flex align-items-center gap-2"><i class="bx bx-store-alt"></i> List Pencairan Mitra</h5>
                                        <?php if (empty($mitraHasil)): ?>
                                            <div class="text-center py-5 text-muted border border-dashed rounded-3">
                                                <i class="bx bx-info-circle fs-2 mb-2 d-block text-secondary text-opacity-50"></i>
                                                Belum ada mitra yang dipilih
                                            </div>
                                        <?php else: ?>
                                            <div class="list-group list-group-flush mb-4 border border-top-0 border-start-0 border-end-0">
                                                <?php foreach ($mitraHasil as $row) : ?>
                                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div class="bg-primary-light text-primary rounded-circle p-2.5 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                                                                <i class="bx bx-store fs-4"></i>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0 text-dark fw-bold"><?= $row['mitra_info']->nama ?></h6>
                                                                <small class="text-muted"><i class="bx bx-list-ol small"></i> <?= $row['mitra_jml'] ?> Item Belanja</small>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex gap-1.5">
                                                            <button type="button" class="btn btn-light btn-sm text-dark font-monospace fw-bold" style="cursor: default;"><?= $row['mitra_jml'] ?></button>
                                                            <a href="<?= base_url('kasir/notaMitra/' . $pjn->kode_pengajuan . '/' . $row['mitra_info']->id_mitra) ?>" target="_blank" class="btn btn-sm btn-outline-secondary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Cetak Nota Mitra">
                                                                <i class="bx bx-printer"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <a target="_blank" href="<?= base_url('kasir/notaKPA/' . $pjn->kode_pengajuan) ?>" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2 py-2"><i class="bx bx-printer fs-5"></i> Cetak Nota KPA</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<!--end page wrapper -->

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
    $(document).ready(function() {
        $(document).on('click', '.getDataLink', function(e) {
            e.preventDefault(); // Mencegah aksi bawaan tautan

            var $link = $(this);
            if ($link.hasClass('disabled')) return;
            $link.addClass('disabled').css('opacity', 0.5);

            var id_mitra = $link.data('id_mitra');
            var kode = $link.data('kode');
            var kode_pengajuan = $link.data('kode_pengajuan');

            $.ajax({
                url: '<?php echo site_url("kasir/addOrderMitra"); ?>', // Ganti "controller/method" sesuai dengan URL controller Anda
                type: 'POST',
                data: {
                    id_mitra: id_mitra,
                    kode: kode,
                    kode_pengajuan: kode_pengajuan
                },
                success: function(response) {
                    $('#mitraTableContainer').load(window.location.href + ' #mitraTableContainer > *');
                    $('#mitraListContainer').load(window.location.href + ' #mitraListContainer > *');
                },
                error: function() {
                    $link.removeClass('disabled').css('opacity', 1);
                    alert('Gagal memilih mitra');
                }
            });
        });

        $(document).on('click', '.delOrderMitra', function(e) {
            e.preventDefault();

            var $link = $(this);
            if ($link.hasClass('disabled')) return;
            $link.addClass('disabled').css('opacity', 0.5);

            var id_order = $link.data('id_order');

            $.ajax({
                url: '<?= base_url('kasir/delOrderMitra') ?>',
                type: 'POST',
                data: {
                    id_order: id_order
                },
                success: function(response) {
                    $('#mitraTableContainer').load(window.location.href + ' #mitraTableContainer > *');
                    $('#mitraListContainer').load(window.location.href + ' #mitraListContainer > *');
                },
                error: function() {
                    $link.removeClass('disabled').css('opacity', 1);
                    alert('Gagal menghapus mitra');
                }
            });
        });
    });
</script>