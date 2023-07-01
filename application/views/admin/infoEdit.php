<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Edit Data Informasi</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-message-detail"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Informasi</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-0 text-uppercase">Form Edit Data Informasi</h6>
                        <hr>
                        <form method="post" action="<?= base_url('admin/saveEditInfo'); ?>">
                            <input type="hidden" name="id" value="<?= $data->id_info ?>">
                            <div class="row mb-3">
                                <label for="inputEnterYourName" class="col-sm-3 col-form-label">Judul Informasi</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="inputEnterYourName" name="judul"
                                        placeholder="Judul Informasi" value="<?= $data->judul; ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPhoneNo2" class="col-sm-3 col-form-label">Tanggal Informasi</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="date" name="tgl"
                                        placeholder="Tanggal Informasi" value="<?= $data->tgl; ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputEmailAddress2" class="col-sm-3 col-form-label">Tujuan</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="tujuan" required>
                                        <option value=""> -pilih- </option>
                                        <option <?= $data->tujuan == 'umum' ? 'selected' : '' ?> value="umum">Umum
                                        </option>
                                        <option <?= $data->tujuan == 'rab' ? 'selected' : '' ?> value="rab">Untuk RAB
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputChoosePassword2" class="col-sm-3 col-form-label">Uploader</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="uploader" readonly
                                        value="<?= $user ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputAddress4" class="col-sm-3 col-form-label">Isi Informasi</label>
                                <div class="col-sm-9">
                                    <textarea id="mytextarea" name="isi" required><?= $data->isi; ?>"</textarea>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="col-sm-9">
                                    <button type="submit" class="btn btn-info px-5 btn-sm">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<!--end page wrapper -->
<!--end page wrapper -->