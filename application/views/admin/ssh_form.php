<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3"><?= isset($ssh) && $ssh ? 'Edit' : 'Tambah'; ?> Data SSH</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-folder-open"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Standard Satuan Harga (SSH)</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-0 text-uppercase">Form <?= isset($ssh) && $ssh ? 'Edit' : 'Tambah'; ?> Data SSH</h6>
                        <hr>
                        <form method="post" action="<?= base_url(isset($ssh) && $ssh ? 'admin/updateSsh' : 'admin/saveSsh'); ?>">
                            <?php if (isset($ssh) && $ssh): ?>
                                <input type="hidden" name="id" value="<?= htmlspecialchars($ssh->kode); ?>" />
                            <?php endif; ?>

                            <div class="row mb-3">
                                <label for="kode" class="col-sm-3 col-form-label">Kode SSH</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="kode" name="kode"
                                        placeholder="Contoh: SSH-001" required value="<?= isset($ssh) && $ssh ? htmlspecialchars($ssh->kode) : ''; ?>" <?= isset($ssh) && $ssh ? 'readonly' : ''; ?>>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="nama" class="col-sm-3 col-form-label">Nama Barang / Jasa</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="nama" name="nama"
                                        placeholder="Nama Standard Satuan Harga" required value="<?= isset($ssh) && $ssh ? htmlspecialchars($ssh->nama) : ''; ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="satuan" class="col-sm-3 col-form-label">Satuan</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="satuan" name="satuan"
                                        placeholder="Contoh: Pcs, Rim, Box, Unit, Orang" required value="<?= isset($ssh) && $ssh ? htmlspecialchars($ssh->satuan) : ''; ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="harga" class="col-sm-3 col-form-label">Harga Satuan (Rp)</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control uang" id="harga" name="harga"
                                        placeholder="Harga Satuan" required value="<?= isset($ssh) && $ssh ? number_format($ssh->harga, 0, ',', '.') : ''; ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="kategori" class="col-sm-3 col-form-label">Kategori</label>
                                <div class="col-sm-9">
                                    <select class="form-select" id="kategori" name="kategori" required>
                                        <option value="">- pilih kategori -</option>
                                        <?php foreach ($kategori as $kat): ?>
                                            <option value="<?= htmlspecialchars($kat->kode_kategori); ?>" <?= isset($ssh) && $ssh && $ssh->kategori == $kat->kode_kategori ? 'selected' : ''; ?>>
                                                <?= htmlspecialchars($kat->nama_kategori); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="ket" class="col-sm-3 col-form-label">Keterangan</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="ket" name="ket" rows="3" placeholder="Keterangan / spesifikasi detail"><?= isset($ssh) && $ssh ? htmlspecialchars($ssh->ket) : ''; ?></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-9">
                                    <button type="submit" class="btn btn-primary px-5 btn-sm">Simpan</button>
                                    <a href="<?= base_url('admin/ssh'); ?>" class="btn btn-secondary px-5 btn-sm">Batal</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<!--end page wrapper -->
