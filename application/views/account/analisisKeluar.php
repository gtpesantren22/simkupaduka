<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Analisis Pengeluaran</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-line-chart-down"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Analisis</li>
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
                            <table id="" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Ket</th>
                                        <th>RAB</th>
                                        <th>Terpakai</th>
                                        <th>Sisa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($keluar as $a) : ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $a->ket; ?></td>
                                            <td><?= rupiah($a->total_rab); ?></td>
                                            <td><?= rupiah($a->pakai); ?></td>
                                            <td><?= rupiah($a->total_rab - $a->pakai); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive">
                            <table id="" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Ket</th>
                                        <th>Pagu</th>
                                        <th>Terpakai</th>
                                        <th>Sisa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($dekos as $a) : ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td>Pengeluaran Dekosan</td>
                                            <td><?= rupiah(0); ?></td>
                                            <td><?= rupiah($a->nominal); ?></td>
                                            <td><?= rupiah(0 - $a->nominal); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php
                                    $no = 2;
                                    foreach ($nikmus as $a) : ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td>Pengeluaran Nikmus</td>
                                            <td><?= rupiah(73000000); ?></td>
                                            <td><?= rupiah($a->nom_kriteria + $a->transport + $a->sopir); ?></td>
                                            <td><?= rupiah(73000000 - ($a->nom_kriteria + $a->transport + $a->sopir)); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php
                                    $no = 3;
                                    foreach ($pengajuanPsb as $a) : ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td>Pengeluaran PSB</td>
                                            <td><?= rupiah(0); ?></td>
                                            <td><?= rupiah($a->jml); ?></td>
                                            <td><?= rupiah(0 - $a->jml); ?></td>
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