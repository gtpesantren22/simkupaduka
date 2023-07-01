<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Akun Pengguna Aplikasi</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-user"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">User Akun</li>
                    </ol>
                </nav>
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
                                        <th>Username</th>
                                        <th>Status</th>
                                        <th>Surat Tugas</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data as $a) :
                                        $kd = $a->lembaga;
                                    ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $a->nama ?></td>
                                        <td><?= $a->username ?></td>
                                        <td>
                                            <span class='badge bg-danger'>Aktif : <?= $a->aktif ?></span>
                                            <span class='badge bg-success'>Lembaga : <?= $a->nm_lm ?></span>
                                            <span class='badge bg-primary'>Level : <?= $a->level ?></span>
                                        </td>
                                        <td><a href="<?= '../institution/spj_file/' . $a->surat ?>"><i
                                                    class="bx bx-download"></i>Unduh berkas</a></td>
                                        <td>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#md<?= $a->id_user ?>"><i
                                                    class="bx bx-edit"></i>Edit</a>
                                            <a href="<?= base_url('admin/delUser/' . $a->id_user); ?>"
                                                class="tombol-hapus"><i class="bx bx-no-entry"></i>Del</a>
                                        </td>
                                    </tr>
                                    <!-- Modal Tambah Data -->
                                    <div class="modal fade" id="md<?= $a->id_user ?>" tabindex="-1" role="dialog"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Data User</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="<?= base_url('admin/editUser'); ?>" method="post">
                                                    <input type="hidden" name="id_user" value="<?= $a->id_user ?>">
                                                    <input type="hidden" name="kd_lem" value="<?= $a->lembaga ?>">
                                                    <div class="modal-body">
                                                        <div class="item form-group">
                                                            <label for="tahun"
                                                                class="col-form-label col-md-3 col-sm-3 label-align">Pilih
                                                                Lembaga <span class="required">*</span></label>
                                                            <div class="col-md-6 col-sm-6 ">
                                                                <select name="lembaga" class="form-control" id=""
                                                                    required>
                                                                    <option value=""> -pilih lembaga- </option>
                                                                    <?php
                                                                        foreach ($lembaga as $a2) {
                                                                            $sc = $a2->kode == $a->lembaga ? 'selected' : '';
                                                                        ?>
                                                                    <option <?= $sc ?> value="<?= $a2->kode ?>">
                                                                        <?= $a2->kode ?>. <?= $a2->nama ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="item form-group">
                                                            <label for="aktif"
                                                                class="col-form-label col-md-3 col-sm-3 label-align">Aktivasi
                                                                akun <span class="required">*</span></label>
                                                            <div class="col-md-6 col-sm-6 ">
                                                                <div class="radio">
                                                                    <label>
                                                                        <input type="radio" class="flat"
                                                                            <?= $a->aktif == 'Y' ? 'checked' : '' ?>
                                                                            value="Y" name="aktif"> Aktif
                                                                    </label>
                                                                </div>
                                                                <div class="radio">
                                                                    <label>
                                                                        <input type="radio" class="flat"
                                                                            <?= $a->aktif == 'T' ? 'checked' : '' ?>
                                                                            value="T" name="aktif"> Tidak
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="item form-group">
                                                            <label for="level"
                                                                class="col-form-label col-md-3 col-sm-3 label-align">Level
                                                                akun <span class="required">*</span></label>
                                                            <div class="col-md-6 col-sm-6 ">
                                                                <div class="radio">
                                                                    <label>
                                                                        <input type="radio" class="flat"
                                                                            <?= $a->level == 'admin' ? 'checked' : '' ?>
                                                                            value="admin" name="level"> Admin Utama
                                                                    </label>
                                                                </div>
                                                                <div class="radio">
                                                                    <label>
                                                                        <input type="radio" class="flat"
                                                                            <?= $a->level == 'lembaga' ? 'checked' : '' ?>
                                                                            value="lembaga" name="level"> Admin Lembaga
                                                                    </label>
                                                                </div>
                                                                <div class="radio">
                                                                    <label>
                                                                        <input type="radio" class="flat"
                                                                            <?= $a->level == 'kasir' ? 'checked' : '' ?>
                                                                            value="kasir" name="level"> Kasir/Teller
                                                                    </label>
                                                                </div>
                                                                <div class="radio">
                                                                    <label>
                                                                        <input type="radio" class="flat"
                                                                            <?= $a->level == 'kepala' ? 'checked' : '' ?>
                                                                            value="kepala" name="level"> Kepala
                                                                        Pesantren
                                                                    </label>
                                                                </div>
                                                                <div class="radio">
                                                                    <label>
                                                                        <input type="radio" class="flat"
                                                                            <?= $a->level == 'account' ? 'checked' : '' ?>
                                                                            value="account" name="level"> Accounting
                                                                    </label>
                                                                </div>
                                                                <div class="radio">
                                                                    <label>
                                                                        <input type="radio" class="flat"
                                                                            <?= $a->level == 'bunda' ? 'checked' : '' ?>
                                                                            value="bunda" name="level"> Bendahara Utama
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="item form-group">
                                                            <label for="tahun"
                                                                class="col-form-label col-md-3 col-sm-3 label-align">No.
                                                                HP Kepala <span class="required">*</span></label>
                                                            <div class="col-md-6 col-sm-6 ">
                                                                <input type="text" name="hp_kep" class="form-control"
                                                                    id="" value="<?= $a->hp_kep; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="item form-group">
                                                            <label for="tahun"
                                                                class="col-form-label col-md-3 col-sm-3 label-align">No.
                                                                HP Admin/Bendahara Lembaga <span
                                                                    class="required">*</span></label>
                                                            <div class="col-md-6 col-sm-6 ">
                                                                <input type="text" name="hp" class="form-control" id=""
                                                                    value="<?= $a->hp; ?>" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" name="save" class="btn btn-success">Simpan
                                                            data</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Tambah Data -->
                                    <div class="modal fade" id="hp<?= $a->id_user ?>" tabindex="-1" role="dialog"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <form id="demo-form2" data-parsley-validate
                                                    class="form-horizontal form-label-left input_mask" action=""
                                                    method="post">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <input type="hidden" name="id_user" value="<?= $a->id_user ?>">
                                                    <div class="modal-body">
                                                        <p>Yakin akan dihapus ?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" name="del" class="btn btn-danger">Ya
                                                            hapus!</button>
                                                    </div>
                                                </form>
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