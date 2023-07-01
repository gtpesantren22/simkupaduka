<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Daftar Kode Lembaga & Bidang</div>
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
                <div class="dropdown">
                    <button class="btn btn-primary  dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-add-to-queue"></i> Tambah Data</button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addLembaga">Tambah Data
                                Lembaga</a>
                        </li>
                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addBidang">Tambah Data
                                Bidang</a>
                        </li>
                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addMitra">Tambah Data
                                Mitra</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card radius-10">
                    <div class="card-body">
                        <div>
                            <h6 class="mb-3 text-center">Daftar Kode Lembaga</h6>
                        </div>
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama Lembaga</th>
                                        <!-- <th>Act</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($lembaga as $a) : ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $a->kode ?></td>
                                            <td><?= $a->nama ?></td>
                                            <!-- <td>
                                            <a data-toggle="modal" data-target="#modal_del<?= $a->id_lembaga; ?>"
                                                href="#"><i class="bx bx-trash"></i> </a> |
                                            <a data-toggle="modal" data-target="#m<?= $a->id_lembaga; ?>" href="#"><i
                                                    class="bx bx-edit"></i> </a>
                                        </td> -->
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card radius-10">
                    <div class="card-body">
                        <div>
                            <h6 class="mb-3 text-center">Daftar Kode Bidang</h6>
                        </div>
                        <div class="table-responsive">
                            <table id="example3" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama Bagian</th>
                                        <th>Level</th>
                                        <!-- <th>Act</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($bidang as $a) : ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $a->kode ?></td>
                                            <td><?= $a->nama ?></td>
                                            <td><?= $a->lv ?></td>
                                            <!-- <td>
                                            <a data-toggle="modal" data-target="#modal_del<?= $a->id_lembaga; ?>"
                                                href="#"><i class="bx bx-trash"></i> </a> |
                                            <a data-toggle="modal" data-target="#m<?= $a->id_lembaga; ?>" href="#"><i
                                                    class="bx bx-edit"></i> </a>
                                        </td> -->
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

<div class="modal fade" id="addLembaga" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Lembaga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open_multipart('admin/lembagaAdd'); ?>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="">Kode</label>
                    <input type="text" name="kode" class="form-control" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">Nama Lembaga</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">Penanggungjawab</label>
                    <input type="text" name="pj" class="form-control" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">No. HP KPA</label>
                    <input type="number" name="hp" class="form-control" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">No. HP Kepala</label>
                    <input type="number" name="hp_kep" class="form-control" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">Waktu Pelaksaan</label>
                    <input type="text" name="waktu" class="form-control" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">Level</label>
                    <select name="lv" id="" class="form-control" required>
                        <option value="ps">Pesantren</option>
                        <option value="lf">Lembaga Formal</option>
                    </select>
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

<div class="modal fade" id="addBidang" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Bidang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open_multipart('admin/bidangAdd'); ?>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="">Kode</label>
                    <input type="text" name="kode" class="form-control" value="<?= $bidangMax->kode + 1; ?>" readonly>
                </div>
                <div class="form-group mb-2">
                    <label for="">Nama Lembaga</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">Level</label>
                    <select name="lv" id="" class="form-control" required>
                        <option value="ps">Pesantren</option>
                        <option value="lf">Lembaga Formal</option>
                    </select>
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

<div class="modal fade" id="addMitra" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Mitra</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open_multipart('admin/mitraAdd'); ?>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="">Nama Mitra</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">PJ Mitra</label>
                    <input type="text" name="pj" class="form-control" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">No. HP</label>
                    <input type="number" name="hp" class="form-control" required>
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