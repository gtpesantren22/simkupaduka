<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Panjar</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-wallet"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Realisasi</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#tambah"><i class="bx bx-plus-circle"></i>Tambah Data</button>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="example2" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Jenis</th>
                                                <th>Tanggal</th>
                                                <th>Nama Kegiatan</th>
                                                <th>Nominal</th>
                                                <th>Berkas</th>
                                                <th>PJ</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            foreach ($panjar as $a) : ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $a->jenis ?></td>
                                                    <td><?= $a->tanggal ?></td>
                                                    <td><?= $a->kegiatan ?></td>
                                                    <td><?= rupiah($a->nominal) ?></td>
                                                    <td><?= $a->berkas ?></td>
                                                    <td><?= $a->pj ?></td>
                                                    <td></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4">Total</th>
                                                <th colspan="4"><?= rupiah($total->total) ?></th>
                                            </tr>
                                        </tfoot>
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

<div class="modal fade" id="tambah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Panjar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open_multipart('kasir/savePanjar'); ?>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="">Jenis</label>
                    <select name="jenis" class="form-select" required>
                        <option value=""> pilih jenis</option>
                        <option value="KEPANITIAAN"> KEPANITIAAN</option>
                        <option value="PEMBANGUNAN"> PEMBANGUNAN</option>
                        <option value="UNIT USAHA"> UNIT USAHA</option>
                    </select>
                </div>
                <div class="form-group mb-2">
                    <label for="">Nama Kegitan</label>
                    <textarea name="kegiatan" class="form-control" required></textarea>
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
                    <label for="">Berkas</label>
                    <input type="file" name="berkas" class="form-control">
                    <small class="text-danger">* File harus berupa PDF dan Max. 10 Mb</small>
                </div>
                <div class="form-group mb-2">
                    <label for="">Penanggungjawab/PJ</label>
                    <input type="text" name="pj" class="form-control" required>
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