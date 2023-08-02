<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Analisis Pengeluaran</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-folder-open"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Rencana Belanja</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-danger" role="tablist">
                            <?php foreach ($dtasal as $dt) : ?>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link " data-bs-toggle="tab" href="#dangerhome<?= $dt->rencana ?>" role="tab" aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon">
                                                <!-- <i class='bx bx-home font-18 me-1'></i> -->
                                            </div>
                                            <div class="tab-title"><?= $bulan[$dt->rencana] ?></div>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="tab-content py-3">
                            <?php foreach ($dtasal as $dt) : ?>
                                <div class="tab-pane fade show " id="dangerhome<?= $dt->rencana ?>" role="tabpanel">
                                    <h5>Analisis Pengeluaran Bulan <?= $bulan[$dt->rencana] ?></h5>
                                    <div class="table-responsive">
                                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Lembaga</th>
                                                    <th>Total RAB</th>
                                                    <th colspan="2">Realisasi</th>
                                                    <th>Total Item</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $no = 1;
                                                $lmb = $this->db->query("SELECT * FROM lembaga WHERE tahun = '$dt->tahun' ")->result();
                                                $totalAll = $this->db->query("SELECT SUM(total) as total FROM rab WHERE tahun = '$dt->tahun' AND rencana = '$dt->rencana' ")->row();
                                                $jmlItem = $this->db->query("SELECT COUNT(*) as jml FROM rab WHERE tahun = '$dt->tahun' AND rencana = '$dt->rencana' ")->row();
                                                foreach ($lmb as $lmb) :
                                                    $totlaRencana = $this->db->query("SELECT SUM(total) as total FROM rab WHERE tahun = '$dt->tahun' AND lembaga = '$lmb->kode' AND rencana = '$dt->rencana' ")->row();
                                                    $totlItem = $this->db->query("SELECT COUNT(*) as jml FROM rab WHERE tahun = '$dt->tahun' AND lembaga = '$lmb->kode' AND rencana = '$dt->rencana' ")->row();
                                                    $totalPakai = $this->db->query("SELECT SUM(realis.nominal) AS pakai FROM realis JOIN rab ON realis.kode=rab.kode WHERE realis.tahun = '$dt->tahun' AND rab.tahun = '$dt->tahun' AND realis.lembaga = '$lmb->kode' AND rab.rencana = '$dt->rencana' ")->row();

                                                    $prsn = $totalPakai->pakai == 0 ? 0 : ($totalPakai->pakai / $totlaRencana->total) * 100;

                                                ?>
                                                    <tr>
                                                        <td><?= $no++ ?></td>
                                                        <td><?= $lmb->nama ?></td>
                                                        <td><?= rupiah($totlaRencana->total) ?></td>
                                                        <td><?= rupiah($totalPakai->pakai) ?></td>
                                                        <td><b>(<?= round($prsn, 2) ?>%)</b></td>
                                                        <td><?= $totlItem->jml ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="2">TOTAL</th>
                                                    <th><?= rupiah($totalAll->total) ?></th>
                                                    <th><?= $jmlItem->jml ?></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode PAK</th>
                                        <th>Tanggal PAK</th>
                                        <th>Status</th>
                                        <th>Tahun</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data as $a) : ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $a->kode_pak; ?></td>
                                            <td><?= $a->tgl_pak; ?></td>
                                            <td><?= $a->status; ?></td>
                                            <td><?= $a->tahun; ?></td>
                                            <td>
                                                <a href="<?= base_url('account/pakDetail/' . $a->kode_pak) ?>"><button class="btn btn-primary btn-sm"><i class="bx bx-search"></i>
                                                        Cek PAK</button></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<!--end page wrapper -->