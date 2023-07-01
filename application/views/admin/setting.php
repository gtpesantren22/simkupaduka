<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Seetings</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-cog"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Setting</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card radius-10">
                    <div class="card-body">
                        <div>
                            <h6 class="mb-3 text-center">Daftar Akses Lembaga</h6>
                        </div>
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Lembaga</th>
                                        <th>Login</th>
                                        <th>Disp</th>
                                        <th>Tahun</th>
                                        <th>Act</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data as $a) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $a->nama ?></td>
                                        <td><?= $a->login ?></td>
                                        <td><?= $a->disposisi ?></td>
                                        <td><?= $a->tahun ?></td>
                                        <td>
                                            <a href="<?= base_url('admin/delAkses/' . $a->id_akses); ?>"
                                                class="tombol-hapus"><i class="bx bx-trash"></i></a> |
                                            <a data-bs-toggle="modal" data-bs-target="#medit<?= $a->id_akses; ?>"
                                                href="#"><i class="bx bx-edit"></i></a>

                                            <!-- Modal Edit Data-->
                                            <div class="modal fade" id="medit<?= $a->id_akses; ?>" tabindex="-1"
                                                role="dialog" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">

                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Edit Akses
                                                                Lembaha</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="<?= base_url('admin/saveEditAkses'); ?>"
                                                            method="post">
                                                            <input type="hidden" name="id_akses"
                                                                value="<?= $a->id_akses; ?>">
                                                            <div class="modal-body">
                                                                <div class="item form-group">
                                                                    <label for="middle-name"
                                                                        class="col-form-label col-md-3 col-sm-3 label-align">Tahun
                                                                        <span class="required">*</span></label>
                                                                    <div class="col-md-6 col-sm-6 ">
                                                                        <input id="middle-name" class="form-control"
                                                                            type="text" name="pj" readonly
                                                                            value="<?= $a->nama; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="item form-group">
                                                                    <label
                                                                        class="col-form-label col-md-3 col-sm-3 label-align"
                                                                        for="first-name">Akses Login <span
                                                                            class="required">*</span>
                                                                    </label>
                                                                    <div class="col-md-6 col-sm-6 ">
                                                                        <p>
                                                                            <input type="radio" class="flat"
                                                                                name="login" id="genderM" value="Y"
                                                                                <?= $a->login == 'Y' ? 'checked' : '' ?> />
                                                                            Ya
                                                                            <input type="radio" class="flat"
                                                                                name="login" id="genderF" value="T"
                                                                                <?= $a->login == 'T' ? 'checked' : '' ?> />
                                                                            Tidak
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="item form-group">
                                                                    <label
                                                                        class="col-form-label col-md-3 col-sm-3 label-align"
                                                                        for="first-name">Disposisi <span
                                                                            class="required">*</span>
                                                                    </label>
                                                                    <div class="col-md-6 col-sm-6 ">
                                                                        <p>
                                                                            <input type="radio" class="flat" name="disp"
                                                                                id="genderM" value="Y"
                                                                                <?= $a->disposisi == 'Y' ? 'checked' : '' ?> />
                                                                            Ya
                                                                            <input type="radio" class="flat" name="disp"
                                                                                id="genderF" value="T"
                                                                                <?= $a->disposisi == 'T' ? 'checked' : '' ?> />
                                                                            Tidak
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="item form-group">
                                                                    <label for="middle-name"
                                                                        class="col-form-label col-md-3 col-sm-3 label-align">Tahun
                                                                        <span class="required">*</span></label>
                                                                    <div class="col-md-6 col-sm-6 ">
                                                                        <input id="middle-name" class="form-control"
                                                                            type="text" name="pj" readonly
                                                                            value="<?= $tahun; ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                                <button type="submit" name="edit"
                                                                    class="btn btn-success">Simpan data</button>
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

            <div class="col-12 col-lg-6">
                <div class="card radius-10">
                    <div class="card-body">
                        <div>
                            <h6 class="mb-3 text-center">Tambah Akses Baru</h6>
                        </div>
                        <?= form_open('admin/saveAkses'); ?>
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Pilih
                                Lembaga <span class="required">*</span>
                            </label>
                            <div class="col-md-12 col-sm-6">
                                <select name="lembaga" id="" class="form-control" required>
                                    <option value=""> -pilih lembaga- </option>
                                    <?php
                                    $sal = $this->db->query("SELECT * FROM lembaga WHERE NOT EXISTS (SELECT lembaga FROM akses WHERE lembaga.kode=akses.lembaga AND tahun = '$tahun') ")->result();
                                    foreach ($sal as $r) {
                                    ?>
                                    <option value="<?= $r->kode; ?>"><?= $r->nama; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Akses
                                Login <span class="required">*</span>
                            </label>
                            <div class="col-md-12 col-sm-6 ">
                                <p>
                                    <input type="radio" class="flat" name="login" id="genderM" value="Y" /> Ya
                                    <input type="radio" class="flat" name="login" id="genderF" value="T" />
                                    Tidak
                                </p>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Disposisi <span
                                    class="required">*</span>
                            </label>
                            <div class="col-md-12 col-sm-6 ">
                                <p>
                                    <input type="radio" class="flat" name="disp" id="genderM" value="Y" /> Ya
                                    <input type="radio" class="flat" name="disp" id="genderF" value="T" /> Tidak
                                </p>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Tahun
                                <span class="required">*</span></label>
                            <div class="col-md-12 col-sm-6 ">
                                <input id="middle-name" class="form-control" type="text" name="tahun" readonly
                                    value="<?= $tahun; ?>">
                            </div>
                        </div>
                        <div class="item form-group mt-2">
                            <div class="col-md-12 col-sm-6 ">
                                <button type="submit" class="btn btn-sm btn-success">Simpan</button>
                            </div>
                        </div>
                        <?= form_close(); ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card radius-10">
                    <div class="card-body">
                        <div>
                            <h6 class="mb-3 text-center">Set Tanggal PAK</h6>
                        </div>
                        <?= form_open('admin/savePAK'); ?>
                        <?php
                        $tgl = $this->db->query("SELECT * FROM akses WHERE lembaga = 'umum' ")->row();
                        ?>

                        <div class="modal-body">
                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Tgl
                                    Aktif
                                    PAK <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <h3 class="badge bg-danger">
                                        <?= date('d F Y', strtotime($tgl->login)) . ' s/d ' . date('d F Y', strtotime($tgl->disposisi)); ?>
                                    </h3>
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Dari
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="text" name="dari" id="date" class="form-control" required>
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Sampai
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="text" name="sampai" id="date2" class="form-control" required>
                                </div>
                            </div>
                            <div class="item form-group">
                                <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Tahun
                                    <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input id="middle-name" class="form-control" type="text" name="tahun" readonly
                                        value="<?= $tahun; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="save_date" class="btn btn-success">Ganti Tanggal
                                Akses</button>
                        </div>
                        <?= form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<!--end page wrapper -->