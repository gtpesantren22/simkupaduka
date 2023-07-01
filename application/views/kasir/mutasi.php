<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Mutasi Santri</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-link-external"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Mutasi</li>
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
                                        <th>NIS</th>
                                        <th>Nama</th>
                                        <th>Alasan</th>
                                        <th>Tgl Mutasi</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    
                                    foreach ($data as $dt) {
                                        if ($dt->status == 0) {
                                            $stas = "<span class='label label-danger'><i class='bx bx-no-entry'></i> Verval Bendahara</span> | <span class='label label-danger'><i class='bx bx-no-entry'></i> Verval Pendataan</span>";
                                        }
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $dt->nis; ?></td>
                                            <td><?= $dt->nama; ?></td>
                                            <td><?= $dt->alasan; ?></td>
                                            <td><?= $dt->tgl_mutasi; ?></td>
                                            <td><a class="btn btn-success btn-sm" href="<?= base_url('kasir/mutasiDtl/' . $dt->nis); ?>"><i class="bx bx-search"></i> Lihat Tanggungan</a></td>
                                        </tr>
                                    <?php } ?>
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