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
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="<?= base_url('account/rabDetail/' . $lembaga->kode); ?>" class="btn btn-warning btn-sm"><i
                            class="bx bx-subdirectory-left"></i>
                        Kembali</a>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="row invoice-info">
                            <div class="col-sm-6 invoice-col">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <td>Nama Lembaga</td>
                                            <td> : <?= $lembaga->nama ?></td>
                                        </tr>
                                        <tr>
                                            <td>Jenis Belanja</td>
                                            <td> : <?= $data->jenis ?></td>
                                        </tr>
                                        <tr>
                                            <td>Kode Item</td>
                                            <td> : <?= $data->kode ?></td>
                                        </tr>
                                        <tr>
                                            <td>Nama Item</td>
                                            <td> : <?= $data->nama ?></td>
                                        </tr>
                                        <tr>
                                            <td>Anggaran RAB</td>
                                            <td> : <?= rupiah($data->total) ?>
                                                <b><i><?= '(' . rupiah($data->harga_satuan) . ' x ' . $data->qty . ' ' . $data->satuan . ')' ?></i></b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Realisasi</td>
                                            <td> : <?= rupiah($rel->jml) ?></td>
                                        </tr>
                                        <tr>
                                            <td>Sisa</td>
                                            <td> : <?= rupiah($data->total - $rel->jml) ?>
                                                <b><i><?= '(' . ($data->total - $rel->jml) / $data->harga_satuan . ' ' . $data->satuan . ')' ?></i></b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Pembelajaan</td>
                                            <td>
                                                <div class="progress" style="height:15px;">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                        role="progressbar"
                                                        aria-valuenow="<?= round(($rel->jml / $data->total) * 100, 0) ?>"
                                                        aria-valuemin="0" aria-valuemax="100"
                                                        style="width: <?= round(($rel->jml / $data->total) * 100, 0) ?>%">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <hr>
                                <!-- <address>
                                    <button class="btn btn-primary btn-sm"><i class="fa fa-download"></i> Download
                                        Excel</button>
                                    <a href="<?= 'real_detail.php?lm=' . $kalem ?>"><button
                                            class="btn btn-warning btn-sm"><i class="fa fa-chevron-circle-left"></i>
                                            Kembali</button></a>
                                </address> -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-6 invoice-col">
                                <address>
                                    <?php if (($data->total - $rel->jml) > 0) { ?>
                                    <form action="<?= base_url('account/saveEditRab'); ?>" method="post">
                                        <input type="hidden" name="id" value="<?= $data->id_rab; ?>">
                                        <h4>Input pembelanjaan</h3>
                                            <div class="table-responsive">
                                                <table class="countries_list">

                                                    <tr>
                                                        <th>Harga Satuan </th>
                                                        <th> <input type="text" class="form-control" name="harga_satuan"
                                                                value="<?= rupiah($data->harga_satuan); ?>" readonly>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>Ganti Jumlah Satuan </th>
                                                        <th> <input type="number" class="form-control" name="jml"
                                                                required>
                                                            <small class="text-danger">* Jumlah ini tidak boleh melebihi
                                                                sisa</small>
                                                        </th>
                                                    </tr>

                                                    <tr class="mt-3">
                                                        <th></th>
                                                        <th>
                                                            <button class="btn btn-sm btn-success mt-3" type="submit"
                                                                name="save"><i class="bx bx-save"></i> Simpan
                                                                Data</button>
                                                        </th>
                                                    </tr>
                                                </table>
                                            </div>
                                    </form>
                                    <?php } ?>
                                </address>
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

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Upload Biaya Pendidikan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open_multipart('account/uploadBp'); ?>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="">Pilih Berkas</label>
                    <input type="file" name="uploadFile" class="form-control" required>
                    <small class="text-danger">* File yang diupload tidak merubah apapun dari tempalte yang di
                        download</small>
                </div>
                <a href="<?= base_url('account/downBpTmp'); ?>"><i class="bx bx-download"></i> Donload Template Format
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