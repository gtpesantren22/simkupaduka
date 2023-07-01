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
                                            <td><a href="<?= '../institution/spj_file/' . $a->surat ?>"><i class="bx bx-download"></i>Unduh berkas</a></td>
                                            <td>
                                                <!-- <a href="#" data-bs-toggle="modal" data-bs-target="#md<?= $a->id_user ?>"><i class="bx bx-edit"></i>Edit</a> -->
                                                <!-- <a href="<?= base_url('admin/delUser/' . $a->id_user); ?>" class="tombol-hapus"><i class="bx bx-no-entry"></i>Del</a> -->
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
<!--end page wrapper -->