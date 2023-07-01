<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Pengembalian Honor</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-message-detail"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Pengembalian</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#tambah"><i class="bx bx-plus"></i>
                        Tambah Baru</button>
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
                                        <th>Uraian</th>
                                        <th>Tanggal Setor</th>
                                        <th>Nominal</th>
                                        <th>Penyetor</th>
                                        <th>Ket</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data as $a) : ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $a->uraian ?></td>
                                            <td><?= $a->tgl ?></td>
                                            <td><?= rupiah($a->nominal) ?></td>
                                            <td><?= $a->penyetor ?></td>
                                            <td><?= $a->ket ?></td>
                                            <td>
                                                <!-- <a href="<?= base_url('admin/infoEdit/' . $a->id_info) ?>"><i class="bx bx-edit"></i> Edit</a> -->
                                                <a href="<?= base_url('admin/delHonor/' . $a->id_kas) ?>" class="tombol-hapus"><i class="bx bx-trash"></i> Del</a>
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

<div class="modal fade" id="tambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah data baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('admin/saveHonorBack') ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Uraian</label>
                    <textarea name="uraian" id="" class="form-control" required></textarea>
                </div>
                <div class="form-group mt-2">
                    <label for="">Tanggal</label>
                    <input type="text" name="tgl" id="date" class="form-control" required></input>
                </div>
                <div class="form-group mt-2">
                    <label for="">Nominal</label>
                    <input type="text" name="nominal" id="" class="form-control uang" required></input>
                </div>
                <div class="form-group mt-2">
                    <label for="">Penyetor</label>
                    <input type="text" name="penyetor" id="" class="form-control" required></input>
                </div>
                <div class="form-group mt-2">
                    <label for="">Ket</label>
                    <select name="ket" id="" class="form-control" required>
                        <option value=""> -pilih- </option>
                        <option value="masuk">Pemasukan</option>
                        <option value="keluar">Pengeluaran</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>