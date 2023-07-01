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
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <ul class="list-group">
                                    <?php foreach ($data as $a) : ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?= $a->kode . '. ' . $a->nama; ?>
                                        <a href="<?= base_url('account/rabDetail/') . $a->kode ?>"
                                            class="btn btn-primary btn-sm"><i class="bx bx-detail"></i> Lihat
                                            Detail</a>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h5 class="line_30">Daftar kode belanja</h5>
                                <table class="countries_list">
                                    <tbody>
                                        <tr>
                                            <td class="fs15 fw700 text-left"><b>A.</b></td>
                                            <td>Belanja Barang</td>
                                        </tr>
                                        <tr>
                                            <td class="fs15 fw700 text-left"><b>B.</b></td>
                                            <td>Langganan Daya dan Jasa</td>
                                        </tr>
                                        <tr>
                                            <td class="fs15 fw700 text-left"><b>C.</b></td>
                                            <td>Belanja Kegiatan</td>
                                        </tr>
                                        <tr>
                                            <td class="fs15 fw700 text-left"><b>D.</b></td>
                                            <td>Umum</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <hr>
                                <h5 class="line_30">Upload File RAB (.xls)</h5>
                                <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal"><i class="bx bx-cloud-upload"></i> Upload
                                    RAB</button><br>
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
                <h5 class="modal-title" id="exampleModalLabel">Upload RAB Lembaga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open_multipart('account/uploadRab'); ?>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="">Pilih Berkas</label>
                    <input type="file" name="uploadFile" class="form-control" required>
                    <small class="text-danger">* File yang diupload tidak merubah apapun dari tempalte yang di
                        download</small>
                </div>
                <a href="<?= base_url('account/downRabTmp'); ?>"><i class="bx bx-download"></i> Donload Template Format
                    Upload RAB Disini!</a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Upload Tanggungan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>