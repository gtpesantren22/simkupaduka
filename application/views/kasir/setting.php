<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-md-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Setting</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-cog"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card radius-10">
                    <div class="card-body">
                        <form action="<?= base_url('kasir/updateAkun') ?>" method="post">
                            <input type="hidden" name="pass_lama" value="<?= $user->password ?>" required>
                            <div class="box-body">
                                <div class="form-group mb-2">
                                    <label for="inputEmail3">Nama Lengkap</label>

                                    <input type="text" class="form-control" name="nama" value="<?= $user->nama ?>" required>

                                </div>
                                <div class="form-group mb-2">
                                    <label for="inputPassword3">Username</label>

                                    <input type="text" class="form-control" name="username" value="<?= $user->username ?>" required>

                                </div>
                                <div class="form-group mb-2">
                                    <label for="inputPassword3">Level</label>

                                    <input type="text" class="form-control" disabled value="Operator <?= $user->level ?>">

                                </div>
                                <div class="form-group mb-2">
                                    <label for="inputPassword3">Lembaga</label>

                                    <input type="text" class="form-control" value="<?= $lembaga->nama ?>" disabled required>

                                </div>
                                <hr>
                                <div class="form-group mb-2">
                                    <label for="inputPassword3">Password baru</label>

                                    <input type="password" class="form-control" name="newpass" placeholder="Password Baru">

                                </div>
                                <div class="form-group mb-2">
                                    <label for="inputPassword3">Confirm password</label>

                                    <input type="password" class="form-control" name="confir_newpass" placeholder="Konfirmasi Password Baru">

                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" name="" class="btn btn-warning btn-sm">Update InfoAkun</button>
                                <button type="button" name="" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bx bx-upload"></i>Upload Foto Profil</button>
                            </div><!-- /.box-footer -->
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card radius-10">
                    <div class="card-body">
                        <form class="form-horizontal" method="post" action="<?= base_url('kasir/updateLembaga') ?>">
                            <div class="box-body">
                                <div class="form-group mb-2">
                                    <label for="inputEmail3" class="">Nama Lembaga</label>

                                    <input type="text" class="form-control" value="<?= $lembaga->nama ?>" disabled>

                                </div>
                                <div class="form-group mb-2">
                                    <label for="inputEmail3" class="">Penanggungjawab</label>

                                    <input type="text" class="form-control" placeholder="Email" name="pj" value="<?= $lembaga->pj ?>">

                                </div>
                                <div class="form-group mb-2">
                                    <label for="inputEmail3" class="">No. HP (PJ)</label>

                                    <input type="text" class="form-control" name="hp" placeholder="No HP" required value="<?= $lembaga->hp ?>">

                                </div>
                                <div class="form-group mb-2">
                                    <label for="inputEmail3" class="">No. HP Kepala</label>

                                    <input type="text" class="form-control" name="hp_kep" placeholder="No HP" required value="<?= $lembaga->hp_kep ?>">

                                </div>
                                <div class="form-group mb-2">
                                    <label for="inputEmail3" class="">Waktu</label>

                                    <input type="text" class="form-control" placeholder="Waktu belanja" name="waktu" value="<?= $lembaga->waktu ?>">

                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" name="" class="btn btn-info pull-right">Update Info Akun Lembaga</button>
                            </div><!-- /.box-footer -->
                        </form>
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
                <h5 class="modal-title" id="exampleModalLabel">Unggah Foto Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open_multipart('kasir/uploadFoto') ?>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="">Upload Foto</label>
                    <input type="file" name="file" class="form-control form-control-sm" required>
                </div>
                <small class="text-danger">- Foto yang diupload berbentuk PERSEGI agar hasilnya pas</small><br>
                <small class="text-danger">- Foto yang diupload berupa JPG,JPEG, dan PNG</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Upload Foto</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>