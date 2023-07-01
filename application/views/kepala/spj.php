<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data SPJ Pengajuan</div>
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
                                        <th>Status</th>
                                        <th>Nominal</th>
                                        <th>Cair</th>
                                        <th>Serap</th>
                                        <th>Berkas</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data as $a) :
                                        $kd_pj = $a->kode_pengajuan;

                                        if ($a->cair == 1) {
                                            $tbl_slct = 'realis';
                                        } else {
                                            $tbl_slct = 'real_sm';
                                        }
                                        $jml = $this->db->query("SELECT SUM(nominal) AS jml, SUM(nom_cair) AS jml_cair, SUM(nom_serap) AS jml_serap FROM $tbl_slct WHERE kode_pengajuan = '$kd_pj' AND tahun = '$tahun' ")->row();

                                        if (preg_match("/DISP./i", $kd_pj)) {
                                            $rt = "<span class='badge bg-danger'>DISPOSISI</span>";
                                        } else {
                                            $rt = '';
                                        }

                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $kd_pj ?></td>
                                            <td><?= $a->nama . ' ' . $rt; ?></td>
                                            <td><?= $bulan[$a->bulan] . ' ' . $a->tahun ?></td>
                                            <td>
                                                <?php if ($a->stts == 0) { ?>
                                                    <span class="badge bg-danger"><i class="bx bx-times"></i> belum
                                                        upload</span>
                                                <?php } else if ($a->stts == 1) { ?>
                                                    <span class="badge bg-warning btn-xs"><i class="bx bx-recycle"></i> proses
                                                        verifikasi</span>
                                                <?php } else { ?>
                                                    <span class="badge bg-success"><i class="bx bx-check"></i> sudah
                                                        selesai</span>
                                                <?php } ?>
                                            </td>
                                            <td><?= rupiah($jml->jml) ?></td>
                                            <td><?= rupiah($jml->jml_cair) ?></td>
                                            <td><?= rupiah($jml->jml_serap) ?></td>
                                            <td><a href="<?= '../institution/spj_file/' . $a->file_spj ?>"><i class="bx bx-download"></i>Unduh Berkas</a></td>
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