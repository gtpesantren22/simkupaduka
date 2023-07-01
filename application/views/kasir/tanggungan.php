<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Tanggungan Santri</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-shopping-bag"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Tanggungan</li>
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
                                        <th>Briva</th>
                                        <th>Nominal</th>
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
                                            <td><?= $a->briva ?></td>
                                            <td>Rp. <?= number_format($a->total, 0, '.', '.') ?></td>
                                            <td><?= $a->tahun ?></td>
                                            <td>
                                                <a href="<?= base_url('kasir/discrb/' . $a->nis); ?>"><button class="btn btn-info btn-sm">Discrb</button></a>
                                                <a class="tombol-hapus" href="<?= base_url('kasir/delTanggungan/' . $a->id_tangg) ?>">
                                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                                </a>
                                            </td>
                                        </tr>
                                        <!-- Modal -->
                                    <?php endforeach ?>
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