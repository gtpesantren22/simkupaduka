<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Daftar Kode Lembaga & Bidang</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-folder-open"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Master Data</li>
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
                            <h6 class="mb-3 text-center">Daftar Kode Lembaga</h6>
                        </div>
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama Lembaga</th>
                                        <!-- <th>Act</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($lembaga as $a) : ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $a->kode ?></td>
                                            <td><?= $a->nama ?></td>
                                            <!-- <td>
                                            <a data-toggle="modal" data-target="#modal_del<?= $a->id_lembaga; ?>"
                                                href="#"><i class="bx bx-trash"></i> </a> |
                                            <a data-toggle="modal" data-target="#m<?= $a->id_lembaga; ?>" href="#"><i
                                                    class="bx bx-edit"></i> </a>
                                        </td> -->
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
                            <h6 class="mb-3 text-center">Daftar Kode Bidang</h6>
                        </div>
                        <div class="table-responsive">
                            <table id="example3" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama Bagian</th>
                                        <th>Level</th>
                                        <!-- <th>Act</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($bidang as $a) : ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $a->kode ?></td>
                                            <td><?= $a->nama ?></td>
                                            <td><?= $a->lv ?></td>
                                            <!-- <td>
                                            <a data-toggle="modal" data-target="#modal_del<?= $a->id_lembaga; ?>"
                                                href="#"><i class="bx bx-trash"></i> </a> |
                                            <a data-toggle="modal" data-target="#m<?= $a->id_lembaga; ?>" href="#"><i
                                                    class="bx bx-edit"></i> </a>
                                        </td> -->
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