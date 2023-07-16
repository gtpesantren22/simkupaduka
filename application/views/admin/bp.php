<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Daftar Biaya Pendidikan Santri</div>
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
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bx bx-upload"></i> Upload BP</button>
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
                                        <th>Nama</th>
                                        <th>Briva</th>
                                        <th>Nominal</th>
                                        <th>Tahun</th>
                                        <th>Act</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($bp as $a) : ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $a->nama ?></td>
                                            <td><?= $a->briva ?></td>
                                            <td>Rp. <?= number_format($a->total, 0, '.', '.') ?></td>
                                            <td><?= $a->tahun ?></td>
                                            <td>
                                                <!-- <a data-toggle="modal" data-target="#modal_edit<?= $a->id_tangg; ?>" href="#"><i class="fa fa-cog"></i> Edit</a> | -->
                                                <a href="<?= base_url('admin/bpDetail/') . $a->id_tangg; ?>"><i class='bx bx-message-square-edit mr-1'></i></a> |
                                                <a data-bs-toggle="modal" data-bs-target="#modal_del<?= $a->id_tangg; ?>" href="#"><i class='bx bx-trash mr-1'></i></a>

                                                <div class="modal fade" id="modal_del<?= $a->id_tangg; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Hapus
                                                                    DataSyahriah</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="<?= base_url('admin/delBp/'); ?>" method="post">
                                                                <input type="hidden" name="id_tangg" value="<?= $a->id_tangg; ?>">
                                                                <div class="modal-body">
                                                                    <h5>Yajin akan menghapus data ini ?</h5>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                                                    <button type="submit" name="delete" class="btn btn-danger">Ya.! Hapus Pon</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
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
                <h5 class="modal-title" id="exampleModalLabel">Upload Biaya Pendidikan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open_multipart('admin/process_upload'); ?>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="">Pilih Berkas</label>
                    <input type="file" name="uploadFile" class="form-control" required>
                    <small class="text-danger">* File yang diupload tidak merubah apapun dari tempalte yang di
                        download</small>
                </div>
                <a href="<?= base_url('admin/downBpTmp'); ?>"><i class="bx bx-download"></i> Donload Template Format
                    Upload Tanggungan Disini!</a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Upload Tanggungan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>