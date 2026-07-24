<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<style>
    .wrap-text {
        white-space: normal;
        word-wrap: break-word;
    }
</style>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">RAB <?= htmlspecialchars($tahun) ?></div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-message-detail"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">RAB</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">

            <div class="col-12 col-lg-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="alert border-0 border-start border-5 border-success fade show py-2 mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="font-35 text-success"><i class='bx bxs-check-circle'></i></div>
                                        <div class="ms-3">
                                            <h6 class="mb-0 text-success"><?= rupiah($lembaga->pagu) ?></h6>
                                            <div>Pagu Anggaran</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="alert border-0 border-start border-5 border-danger fade show py-2 mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="font-35 text-danger"><i class='bx bx-info-square'></i></div>
                                        <div class="ms-3">
                                            <h6 class="mb-0 text-danger"><?= rupiah($rab24Total->row('jml')) ?></h6>
                                            <div>Total RAB Baru</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="alert border-0 border-start border-5 border-info fade show py-2 mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="font-35 text-info"><i class='bx bx-list-ul'></i></div>
                                        <div class="ms-3">
                                            <h6 class="mb-0 text-info"><?= count($dppk) ?> Program</h6>
                                            <div>Jumlah Program DPPK</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Upload Forms -->
                            <div class="col-md-6 mt-3">
                                <div class="card shadow-none border mb-0">
                                    <div class="card-header py-1 bg-light">
                                        <small class="fw-bold text-dark"><i class="bx bx-upload text-warning"></i> 1. Upload DPPK (Excel)</small>
                                    </div>
                                    <div class="card-body py-2">
                                        <form id="form-upload-dppk" action="<?= base_url('admin/uploadDppk24/' . $lembaga->kode) ?>" method="post" enctype="multipart/form-data">
                                            <div class="input-group input-group-sm">
                                                <input type="file" name="uploadFile" class="form-control" accept=".xls,.xlsx" required>
                                                <button type="submit" class="btn btn-warning text-dark btn-sm fw-bold">Upload DPPK</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="card shadow-none border mb-0">
                                    <div class="card-header py-1 bg-light">
                                        <small class="fw-bold text-dark"><i class="bx bx-upload text-primary"></i> 2. Upload RAB (Excel)</small>
                                    </div>
                                    <div class="card-body py-2">
                                        <form id="form-upload-rab" action="<?= base_url('admin/uploadRab24/' . $lembaga->kode) ?>" method="post" enctype="multipart/form-data">
                                            <div class="input-group input-group-sm">
                                                <input type="file" name="uploadFile" class="form-control" accept=".xls,.xlsx" required>
                                                <button type="submit" class="btn btn-primary btn-sm fw-bold">Upload RAB</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12 mt-3 d-flex gap-2">
                                <a href="<?= base_url('admin/kosongiRab24/' . $lembaga->kode) ?>" value="Draf RAB ini akan dikosongkan/dihapus semua." class="btn btn-danger btn-sm tbl-confirm"><i class="bx bx-trash"></i> Kosongkan Draf RAB</a>
                                <a href="<?= base_url('admin/clearDppk24/' . $lembaga->kode) ?>" value="Semua program DPPK untuk lembaga ini akan dikosongkan/dihapus." class="btn btn-danger btn-sm tbl-confirm"><i class="bx bx-trash"></i> Kosongkan Semua DPPK</a>
                                <a href="<?= base_url('admin/rabUploadSnc24/' . $lembaga->kode) ?>" value="RAB akan disinkron. Sebelumnya pastikan semuanya sudah fix" class="btn btn-success btn-sm tbl-confirm"><i class="bx bx-refresh"></i> Sinkron RAB ke Utama</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Navigation Tabs for tables -->
            <div class="col-12 col-lg-12 mt-3">
                <ul class="nav nav-tabs nav-primary" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tab-rab" role="tab" aria-selected="true">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class="bx bx-list-check font-18 me-1"></i></div>
                                <div class="tab-title">Rincian RAB</div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-dppk" role="tab" aria-selected="false">
                            <div class="d-flex align-items-center">
                                <div class="tab-icon"><i class="bx bx-list-ul font-18 me-1"></i></div>
                                <div class="tab-title">Daftar DPPK</div>
                            </div>
                        </a>
                    </li>
                </ul>
                <div class="tab-content py-3">
                    <div class="tab-pane fade show active" id="tab-rab" role="tabpanel">
                        <div class="card radius-10">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="" class="table table-bordered mb-0 table-sm" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Program</th>
                                                <th>Kode RAB</th>
                                                <th>COA</th>
                                                <th>Nama Barang</th>
                                                <th>QTY</th>
                                                <th>Hrg Satuan</th>
                                                <th>Total</th>
                                                <th>#</th>
                                            </tr>
                                         </thead>
                                       <tbody>
                                            <?php if (empty($rab)) : ?>
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted py-3">Belum ada item rincian RAB. Silakan unggah berkas excel Anda.</td>
                                                </tr>
                                            <?php else : ?>
                                                <?php foreach ($rab as $kodePak => $list) : ?>
                                                    <?php foreach ($list as $item) : ?>
                                                        <tr>
                                                            <?php if ($item === reset($list)) : ?>
                                                                <td class="wrap-text" rowspan="<?= count($list); ?>">
                                                                    <?= '#' . $kodePak . ' - ' . $item->nama_dppk; ?><br>
                                                                    <b>Jml Item : <?= count($list) ?></b>
                                                                </td>
                                                            <?php endif; ?>
                                                            <td class="font-monospace"><?= htmlspecialchars($item->kode) ?></td>
                                                            <td class="font-monospace text-secondary"><?= !empty($item->coa) ? htmlspecialchars($item->coa) : '-' ?></td>
                                                            <td><?= htmlspecialchars($item->nama) ?></td>
                                                            <td><?= htmlspecialchars($item->qty . ' ' . $item->satuan) ?></td>
                                                            <td><?= rupiah($item->harga_satuan) ?></td>
                                                            <td class="fw-bold text-success"><?= rupiah($item->total) ?></td>
                                                            <td></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab-dppk" role="tabpanel">
                        <div class="card radius-10">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered align-middle table-sm" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">No</th>
                                                <th style="width: 20%">Kode DPPK</th>
                                                <th style="width: 55%">Program Kerja / Kegiatan</th>
                                                <th style="width: 10%">Tahun</th>
                                                <th style="width: 10%">#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($dppk)) : ?>
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted py-3">Belum ada data DPPK. Silakan upload terlebih dahulu.</td>
                                                </tr>
                                            <?php else : ?>
                                                <?php $no = 1; foreach ($dppk as $d) : ?>
                                                    <tr>
                                                        <td><?= $no++ ?></td>
                                                        <td class="font-monospace fw-bold text-primary"><?= htmlspecialchars($d->id_dppk) ?></td>
                                                        <td><?= htmlspecialchars($d->program) ?></td>
                                                        <td><?= htmlspecialchars($d->tahun) ?></td>
                                                        <td>
                                                            <a href="<?= base_url('admin/deleteDppk24/' . $d->id_dppk . '/' . $lembaga->kode) ?>" class="btn btn-danger btn-sm tbl-confirm" value="Hapus program DPPK ini?"><i class="bx bx-trash"></i> Hapus</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
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

<script>
window.addEventListener("load", function() {
    // Handle DPPK Upload Form
    $('#form-upload-dppk').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        
        Swal.fire({
            title: 'Sedang Mengunggah DPPK...',
            text: 'Harap tunggu beberapa saat, data sedang diproses.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    text: 'Koneksi terputus atau server mengalami timeout.'
                });
            }
        });
    });

    // Handle RAB Upload Form
    $('#form-upload-rab').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        
        Swal.fire({
            title: 'Sedang Mengunggah RAB...',
            text: 'Harap tunggu beberapa saat, data sedang diproses.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    text: 'Koneksi terputus atau server mengalami timeout.'
                });
            }
        });
    });
});
</script>