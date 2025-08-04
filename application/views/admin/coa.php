<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Daftar Nama Akun (COA)</div>
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
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bx bx-plus-circle"></i> Tambah Data (Utama)</button>
                    &nbsp;
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal2"><i class="bx bx-plus-circle"></i> Tambah Data (Turunan)</button>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-hover table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <!-- <th>No</th> -->
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Type</th>
                                        <th>Keterangan</th>
                                        <th>Uraian</th>
                                        <th>Cair</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data as $row): ?>
                                        <!-- Parent Row -->
                                        <tr style="background-color: #dfefff; font-weight: bold;">
                                            <td><?= $row['parrent']->kode ?></td>
                                            <td><?= $row['parrent']->nama ?></td>
                                            <td><?= $row['parrent']->tipe ?></td>
                                            <td><?= $row['parrent']->keterangan ?></td>
                                            <td><?= $row['parrent']->uraian ?></td>
                                            <td><?= $row['parrent']->cair ?></td>
                                            <td><?= $row['parrent']->tahun ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-warning">Edit</button>
                                                <a href="<?= base_url('admin/delCoa/' . $row['parrent']->kode) ?>" onclick="return confirm('Yakin akan dihapus ?')" class="btn btn-sm btn-danger">Del</a>
                                            </td>
                                        </tr>

                                        <!-- Child Rows -->
                                        <?php foreach ($row['child'] as $child): ?>
                                            <tr>
                                                <td style="padding-left: 20px;">→ <?= $child->kode ?></td>
                                                <td><?= $child->nama ?></td>
                                                <td><?= $child->tipe ?></td>
                                                <td><?= $child->keterangan ?></td>
                                                <td><?= $child->uraian ?></td>
                                                <td><?= $child->cair ?></td>
                                                <td><?= $child->tahun ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning">Edit</button>
                                                    <a href="<?= base_url('admin/delCoa/' . $child->kode) ?>" onclick="return confirm('Yakin akan dihapus ?')" class="btn btn-sm btn-danger">Del</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
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
                <h5 class="modal-title" id="exampleModalLabel">Tambah Akun Belanja (Utama)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('admin/addCoa'); ?>
            <input type="hidden" name="tahun" value="<?= $tahun ?>">
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="">Kode</label>
                    <input type="text" name="kode" class="form-control" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">Nama Akun</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">Tipe Akun</label>
                    <input type="text" name="tipe" class="form-control" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">Keterangan</label>
                    <textarea name="keterangan" id="" class="form-control" required></textarea>
                </div>
                <div class="form-group mb-2">
                    <label for="">Uraian</label>
                    <textarea name="uraian" id="" class="form-control" required></textarea>
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
<div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Akun Belanja (Turunan)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('admin/addCoaNext'); ?>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="">Pilih akun induk</label>
                    <select name="nama" id="" class="form-select" required>
                        <option value="">pilih akun</option>
                        <?php foreach ($coa as $item): ?>
                            <option value="<?= $item->kode; ?>"><?= $item->kode . ' - ' . $item->nama; ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group mb-2">
                    <label for="">Nama Akun</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">Tipe Akun</label>
                    <input type="text" name="tipe" class="form-control" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">Keterangan</label>
                    <textarea name="keterangan" id="" class="form-control" required></textarea>
                </div>
                <div class="form-group mb-2">
                    <label for="">Uraian</label>
                    <textarea name="uraian" id="" class="form-control" required></textarea>
                </div>
                <div class="form-group mb-2">
                    <label for="">Jenis Cair</label>
                    <select name="cair" id="" class="form-select" required>
                        <option value="">-pilih-</option>
                        <option value="tunai">Tunai</option>
                        <option value="non-tunai">Non Tunai</option>
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