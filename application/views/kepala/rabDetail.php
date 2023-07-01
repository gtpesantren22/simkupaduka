<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Daftar RAB Lembaga</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-folder-open"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Rencana Belanja</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">Kode Lembaga</li>
                                    <li class="list-group-item">Nama Lembaga</li>
                                    <li class="list-group-item">PJ</li>
                                    <li class="list-group-item">No. Hp</li>
                                    <li class="list-group-item">Pelaksanaan</li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">: <?= $lembaga->kode; ?></li>
                                    <li class="list-group-item">: <?= $lembaga->nama; ?></li>
                                    <li class="list-group-item">: <?= $lembaga->pj; ?></li>
                                    <li class="list-group-item">: <?= $lembaga->hp; ?></li>
                                    <li class="list-group-item">: <?= $lembaga->waktu; ?></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">A.
                                        Belanja
                                        Barang <span"><?= rupiah($sumA->total); ?></span></li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">B.
                                        Langganan &
                                        Jasa <span"><?= rupiah($sumB->total); ?></span></li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">C.
                                        Belanja
                                        Kegiatan <span"><?= rupiah($sumC->total); ?></span></li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">D.
                                        Umum
                                        <span><?= rupiah($sumD->total); ?></span>
                                    </li>
                                    <li class=" list-group-item d-flex justify-content-between align-items-center active" aria-current="true">TOTAL RAB <span">
                                            <?= rupiah($sumA->total + $sumB->total + $sumC->total + $sumD->total); ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example2" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama Barang</th>
                                        <th>Rencana Waktu</th>
                                        <th>QTY</th>
                                        <th>Harga Satuan</th>
                                        <th>Jumlah</th>
                                        <!-- <th>%</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data as $a) : ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $a->kode ?></td>
                                            <td><?= $a->nama ?></td>
                                            <td><?= $a->rencana ?></td>
                                            <td><?= $a->qty . ' ' . $a->satuan ?></td>
                                            <td><?= rupiah($a->harga_satuan) ?></td>
                                            <td><?= rupiah($a->total) ?></td>
                                            <!-- <td><?= round($rls->vol / $a->qty * 100, 1); ?>%</td> -->

                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama Barang</th>
                                        <th>Rencana Waktu</th>
                                        <th>QTY</th>
                                        <th>Harga Satuan</th>
                                        <th>Jumlah</th>
                                        <!-- <th>%</th> -->
                                    </tr>
                                </tfoot>
                            </table>
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

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Upload Biaya Pendidikan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open_multipart('admin/uploadBp'); ?>
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