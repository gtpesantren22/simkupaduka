<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Informasi</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-message-detail"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Informasi</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="<?= base_url('account/infoAdd'); ?>" class="btn btn-success btn-sm"><i
                            class="bx bx-plus"></i>
                        Tambah Informasi Baru</a>
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
                                        <th>Judul</th>
                                        <th>Tanggal Upload</th>
                                        <th>Isi</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data as $a) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $a->judul ?></td>
                                        <td><?= $a->tgl ?></td>
                                        <td><a href="#" data-bs-toggle="modal"
                                                data-bs-target="#isi<?= $a->id_info ?>">Lihat
                                                Isi</a></td>
                                        <td>
                                            <a href="<?= base_url('account/infoEdit/' . $a->id_info) ?>"><i
                                                    class="bx bx-edit"></i> Edit</a>
                                            <a href="<?= base_url('account/delInfo/' . $a->id_info) ?>"
                                                class="tombol-hapus"><i class="bx bx-trash"></i> Del</a>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="isi<?= $a->id_info ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"><?= $a->judul ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <?= $a->isi ?>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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
<!--end page wrapper -->