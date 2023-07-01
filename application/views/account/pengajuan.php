<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Pengajuan Lembaga</div>
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
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Lembaga</th>
                                        <th>Periode</th>
                                        <th>Verval / Approv / Cair / SPJ</th>
                                        <th>Nominal</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data as $a) :
                                        $kd_pj = $a->kode_pengajuan;

                                        $jml = $this->db->query("SELECT SUM(nominal) AS jml FROM real_sm WHERE kode_pengajuan = '$kd_pj' AND tahun = '$tahun' ")->row();
                                        $jml2 = $this->db->query("SELECT SUM(nominal) AS jml FROM realis WHERE kode_pengajuan = '$kd_pj' AND tahun = '$tahun' ")->row();
                                        $kfe = $jml->jml + $jml2->jml;

                                        if (preg_match("/DISP./i", $kd_pj)) {
                                            $rt = "<span class='badge bg-danger'>DISPOSISI</span>";
                                        } else {
                                            $rt = '';
                                        }

                                    ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $kd_pj ?></td>
                                        <td><?= $a->nama . ' ' . $rt ?></td>
                                        <td><?= $bulan[$a->bulan] . ' ' . $a->tahun ?></td>
                                        <td>
                                            <?= $a->verval == 1 ? "<span class='badge bg-success'><i class='bx bx-check'></i> sudah</span>" : "<span class='badge bg-danger'><i class='bx bx-no-entry'></i> belum</span>"; ?>
                                            <?= $a->apr == 1 ? "<span class='badge bg-success'><i class='bx bx-check'></i> sudah</span>" : "<span class='badge bg-danger'><i class='bx bx-no-entry'></i> belum</span>"; ?>
                                            <?= $a->cair == 1 ? "<span class='badge bg-success'><i class='bx bx-check'></i> sudah</span>" : "<span class='badge bg-danger'><i class='bx bx-no-entry'></i> belum</span>"; ?>
                                            <?php if ($a->spj == 0) { ?>
                                            <span class="badge bg-danger"><i class="bx bx-no-entry"></i> belum
                                                upload</span>
                                            <?php } else if ($a->spj == 1) { ?>
                                            <span class="badge bg-warning btn-xs">
                                                <i class="bx bx-refresh"></i>
                                                proses verifikasi
                                            </span>
                                            <?php } else { ?>
                                            <span class="badge bg-success"><i class="bx bx-check"></i> sudah
                                                selesai</span>
                                            <?php } ?>
                                        </td>
                                        <td><?= rupiah($kfe) ?></td>
                                        <td><a href="<?= base_url('account/pengajuanDtl/' . $a->kode_pengajuan) ?>"><button
                                                    class="btn btn-primary btn-sm"><i class="bx bx-search"></i> Cek &
                                                    Verifikasi</button></a></td>
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