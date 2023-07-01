<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Rekapan Pembayaran Tanggungan Santri</div>
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
                                        <th>Bulan</th>
                                        <th>Nominal</th>
                                        <th>Jml Pembayaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($hasil as $ls_jns) {
                                        $total = $this->db->query("SELECT SUM(nominal) as nm FROM pembayaran WHERE tahun = '$tahun' AND bulan = $ls_jns->bulan ")->row();
                                        $jml = $this->db->query("SELECT COUNT(*) as jml FROM pembayaran WHERE tahun = '$tahun' AND bulan = $ls_jns->bulan ")->row();
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $bulan[$ls_jns->bulan]; ?></td>
                                            <td><?= rupiah($total->nm); ?></td>
                                            <td>
                                                <?= $jml->jml ?> item
                                            </td>
                                        </tr>
                                        <!-- Modal -->

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