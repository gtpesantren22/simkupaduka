<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Daftar Realisasi Belanja</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-wallet"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Realisasi</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr style="background-color: greenyellow;">
                                                <th>Jenis Belanja</th>
                                                <th>Sisa RAB</th>
                                                <th>Dana Pengajuan</th>
                                                <th>Ket</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>A. Belanja Barang</td>
                                                <td><?= rupiah($sisaA); ?></td>
                                                <td><?= rupiah($nomA->jml); ?></td>
                                                <td>
                                                    <?php if ($sisaA >= $nomA->jml) { ?>
                                                        <span class="badge bg-success"><i class="bx bx-check"></i> RAB
                                                            Mencukupi</span>
                                                    <?php } else { ?>
                                                        <span class="badge bg-danger"><i class="bx bx-no-entry"></i> RAB
                                                            Tidak
                                                            Mencukupi</span>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>B. Langganan & Jasa</td>
                                                <td><?= rupiah($sisaB); ?></td>
                                                <td><?= rupiah($nomB->jml); ?></td>
                                                <td>
                                                    <?php if ($sisaB >= $nomB->jml) { ?>
                                                        <span class="badge bg-success"><i class="bx bx-check"></i> RAB
                                                            Mencukupi</span>
                                                    <?php } else { ?>
                                                        <span class="badge bg-danger"><i class="bx bx-no-entry"></i> RAB
                                                            Tidak
                                                            Mencukupi</span>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>C. Belanja Kegiatan</td>
                                                <td><?= rupiah($sisaC); ?></td>
                                                <td><?= rupiah($nomC->jml); ?></td>
                                                <td>
                                                    <?php if ($sisaC >= $nomC->jml) { ?>
                                                        <span class="badge bg-success"><i class="bx bx-check"></i> RAB
                                                            Mencukupi</span>
                                                    <?php } else { ?>
                                                        <span class="badge bg-danger"><i class="bx bx-no-entry"></i> RAB
                                                            Tidak
                                                            Mencukupi</span>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>D. Umum</td>
                                                <td><?= rupiah($sisaD); ?></td>
                                                <td><?= rupiah($nomD->jml); ?></td>
                                                <td>
                                                    <?php if ($sisaD >= $nomD->jml) { ?>
                                                        <span class="badge bg-success"><i class="bx bx-check"></i> RAB
                                                            Mencukupi</span>
                                                    <?php } else { ?>
                                                        <span class="badge bg-danger"><i class="bx bx-no-entry"></i> RAB
                                                            Tidak
                                                            Mencukupi</span>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>TOTAL</th>
                                                <th><?= rupiah($sisaA + $sisaB + $sisaC + $sisaD); ?>
                                                </th>
                                                <th><?= rupiah(($nomA->jml + $nomB->jml + $nomC->jml + $nomD->jml)); ?>
                                                </th>
                                                <th></th>
                                                <!-- <th><?= rupiah(($sisaA + $sisaB + $sisaC + $sisaD) - ($nomA->jml + $nomB->jml + $nomC->jml + $nomD->jml)); ?></th> -->
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example2" class="table table-striped table-bordered">
                                <thead>
                                    <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                        <th>No</th>
                                        <th>Kode RAB</th>
                                        <th>Periode</th>
                                        <th>PJ</th>
                                        <th>Keterangan</th>
                                        <th>Nominal</th>
                                        <th>Cair</th>
                                        <th>Ket</th>
                                    </tr>
                                    <!-- <tr style="color: white; background-color: plum; font-weight: bold;">
                                        <th colspan="7">A. Belanja Barang</th>
                                    </tr> -->
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data as $a) { ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $a->kode ?></td>
                                            <td><?= $bulan[$a->bulan] . ' ' . $a->tahun ?></td>
                                            <td><?= $a->pj ?></td>
                                            <td>
                                                <?= $a->ket ?>
                                                <?php
                                                if (preg_match("/honor/i", $a->ket)) {
                                                    $fl = $honor->row();
                                                    $htgd = $honor->num_rows();
                                                ?>
                                                    <?php if ($htgd > 0) { ?>
                                                        <b><i><a href="../institution/honor_file/<?= $fl->files; ?>"> (<i class="bx bx-download"></i> Download)</a></i></b>
                                                    <?php } else { ?>
                                                        <b><i><a href="#"> (Belum ada)</a></i></b>
                                                <?php }
                                                } ?>
                                            </td>
                                            <td><?= rupiah($a->nominal) ?></td>
                                            <td><?= rupiah($a->nom_cair) ?></td>
                                            <td><?= $a->stas ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->
</div>
<!--end page wrapper -->