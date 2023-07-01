<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Santri</div>
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
            <div class="col-12 col-lg-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Kelas Formal</th>
                                        <th>Kelas Madin</th>
                                        <th>Stts</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($santri as $a) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $a->nama ?></td>
                                        <td><?= $a->desa . '-' . $a->kec . '-' . $a->kab ?></td>
                                        <td><?= $a->k_formal . ' ' . $a->t_formal ?></td>
                                        <td><?= $a->k_madin . ' ' . $a->r_madin ?></td>
                                        <td><?php $st = $a->stts;
                                                $ps = explode("-", $st);
                                                if ($ps[0] == 1) {
                                                    echo "<span class='badge bg-violet'>Ust/Usdz</span>";
                                                    echo " ";
                                                }
                                                if ($ps[1] == 2) {
                                                    echo "<span class='badge bg-primary'>Mhs/i</span>";
                                                    echo " ";
                                                }
                                                if ($ps[2] == 3) {
                                                    echo "<span class='badge bg-success'>Sdr/i</span>";
                                                    echo " ";
                                                }
                                                if ($ps[3] == 4) {
                                                    echo "<span class='badge bg-info'>Kls 6</span>";
                                                    echo " ";
                                                }
                                                if ($ps[4] == 5) {
                                                    echo "<span class='badge bg-warning'>Baru</span>";
                                                    echo " ";
                                                }
                                                if ($ps[5] == 6) {
                                                    echo "<span class='badge bg-danger'>Lama</span>";
                                                    echo " ";
                                                }
                                                if ($ps[6] == 7) {
                                                    echo "<span class='badge bg-primary'>Peng. Wilyah</span>";
                                                    echo " ";
                                                }
                                                if ($ps[7] == 8) {
                                                    echo "<span class='badge bg-dark'>Putra</span>";
                                                    echo " ";
                                                }
                                                if ($ps[8] == 9) {
                                                    echo "<span class='badge bg-info'>Putri</span>";
                                                }
                                                ?></td>
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