<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Daftar Pagun Anggran</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-folder-open"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Master Data</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bx bx-upload"></i> Tambah Pagu</button>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Nominal</th>
                                        <th>Tahun</th>
                                        <th>Act</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data as $a) : ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $a->kode_pagu ?></td>
                                            <td>PAGU <?= $a->nama ?></td>
                                            <td>Rp. <?= number_format($a->nominal, 0, '.', '.') ?></td>
                                            <td><?= $a->tahun ?></td>
                                            <td>
                                                <a class="btn btn-danger btn-sm" href="<?= base_url('admin/delPagu/') . $a->id_pagu; ?>" onclick="return confirm('Yakin akan dihapus ?')"><i class='bx bx-trash mr-1'></i></a>
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

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah List Pagu Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('admin/addPagu'); ?>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="">Nama Pagu</label>
                    <select name="nama" id="" class="form-select" required>
                        <option value="">pilih pagu</option>
                        <option value="NIKMUS">PAGU NIKMUS</option>
                        <option value="DISPOSISI">PAGU DISPOSISI</option>
                        <option value="KEBIJAKAN">PAGU KEBIJAKAN</option>
                        <option value="SARPRAS">PAGU SARPRAS</option>
                        <option value="HONOR">PAGU HONOR</option>
                    </select>
                </div>
                <div class="form-group mb-2">
                    <label for="">Nominal</label>
                    <input type="text" name="nominal" class="form-control uang" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">Tahun</label>
                    <select name="tahun" id="" class="form-select" required>
                        <option value="">pilih tahun</option>
                        <?php foreach ($ta as $ta) : ?>
                            <option value="<?= $ta->nama_tahun ?>"><?= $ta->nama_tahun ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>