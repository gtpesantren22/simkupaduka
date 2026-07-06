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
                            <div class="col-md-2">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">Kode Lembaga</li>
                                    <li class="list-group-item">Nama Lembaga</li>
                                    <li class="list-group-item">PJ</li>
                                    <li class="list-group-item">No. Hp</li>
                                    <li class="list-group-item">Pelaksanaan</li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">: <?= $lembaga->kode; ?></li>
                                    <li class="list-group-item">: <?= $lembaga->nama; ?></li>
                                    <li class="list-group-item">: <?= $lembaga->pj; ?></li>
                                    <li class="list-group-item">: <?= $lembaga->hp; ?></li>
                                    <li class="list-group-item">: <?= $lembaga->waktu; ?></li>
                                </ul>
                            </div>
                            <div class="col-md-7">
                                <table class="table">
                                    <thead>
                                        <tr style="background-color: greenyellow;">
                                            <th>Jenis Belanja</th>
                                            <th>Jml RAB</th>
                                            <th>Terpakai</th>
                                            <th>Sisa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($jenis as $data) : ?>
                                            <tr>
                                                <td><?= $data->kode_jns . '. ' . $data->nama ?></td>
                                                <td><?= rupiah($rabJml[$data->kode_jns]->jml3); ?></td>
                                                <td><?= rupiah($pakaiJml[$data->kode_jns]->jml3); ?></td>
                                                <td><?= rupiah($rabJml[$data->kode_jns]->jml3 - $pakaiJml[$data->kode_jns]->jml3); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <!-- <tr>
                                            <td>B. Langganan & Jasa</td>
                                            <td><?= rupiah($sumB->total); ?></td>
                                            <td><?= rupiah($pakaiB->nominal); ?></td>
                                            <td><?= rupiah($sumB->total - $pakaiB->nominal); ?></td>
                                        </tr>
                                        <tr>
                                            <td>C. Belanja Kegiatan</td>
                                            <td><?= rupiah($sumC->total); ?></td>
                                            <td><?= rupiah($pakaiC->nominal); ?></td>
                                            <td><?= rupiah($sumC->total - $pakaiC->nominal); ?></td>
                                        </tr>
                                        <tr>
                                            <td>D. Umum</td>
                                            <td><?= rupiah($sumD->total); ?></td>
                                            <td><?= rupiah($pakaiD->nominal); ?></td>
                                            <td><?= rupiah($sumD->total - $pakaiD->nominal); ?></td>
                                        </tr> -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <!-- <th>TOTAL</th>
                                            <th><?= rupiah($sumA->total + $sumB->total + $sumC->total + $sumD->total); ?>
                                            </th>
                                            <th><?= rupiah(($pakaiA->nominal + $pakaiB->nominal + $pakaiC->nominal + $pakaiD->nominal)); ?>
                                            </th>
                                            <th><?= rupiah(($sumA->total + $sumB->total + $sumC->total + $sumD->total) - ($pakaiA->nominal + $pakaiB->nominal + $pakaiC->nominal + $pakaiD->nominal)); ?>
                                            </th> -->
                                        </tr>
                                    </tfoot>
                                </table>
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
                                            <th>Kode Pengajuan</th>
                                            <th>Bulan Pengajuan</th>
                                            <th>Kode Kegiatan</th>
                                            <th>Status Pengajuan</th>
                                            <th>Jumlah Realisasi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($pengajuan as $pj) {
                                            $kd_pj = $pj->kode_pengajuan;
                                            
                                            // jumlah realisasi
                                            $tblselect = $pj->cair == 1 ? 'realis' : 'real_sm';
                                            $nom = $this->db->query("SELECT SUM(nominal) as total FROM $tblselect WHERE kode_pengajuan = '$kd_pj'")->row('total');
                                            
                                            // get kode kegiatan (id_dppk) from realis_detail
                                            $kegiatans = $this->db->query("SELECT DISTINCT kode_program FROM realis_detail rd JOIN $tblselect rs ON rd.id_detail = rs.id_realis WHERE rs.kode_pengajuan = '$kd_pj'")->result();
                                            $kode_kegiatan_arr = [];
                                            foreach($kegiatans as $kg) {
                                                if (!empty($kg->kode_program)) {
                                                    $kode_kegiatan_arr[] = $kg->kode_program;
                                                }
                                            }
                                            $kode_kegiatan_str = !empty($kode_kegiatan_arr) ? implode(', ', $kode_kegiatan_arr) : '-';
                                            
                                            // status
                                            if ($pj->cair == 1) {
                                                $stts_badge = '<span class="badge bg-success">Cair</span>';
                                            } else if ($pj->stts == 'yes') {
                                                $stts_badge = '<span class="badge bg-primary">Diajukan</span>';
                                            } else {
                                                $stts_badge = '<span class="badge bg-warning text-dark">Belum Diajukan</span>';
                                            }
                                        ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= $kd_pj ?></td>
                                                <td><?= (function_exists('bulan') ? bulan($pj->bulan) : $pj->bulan) . ' ' . $pj->tahun ?></td>
                                                <td><?= $kode_kegiatan_str ?></td>
                                                <td><?= $stts_badge ?></td>
                                                <td><?= rupiah($nom ?? 0) ?></td>
                                                <td>
                                                    <a href="<?= base_url('lembaga/pengajuanDetail/' . $kd_pj) ?>" class="btn btn-sm btn-info text-white"><i class="bx bx-info-circle"></i> Detail</a>
                                                </td>
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
</div>
<!--end page wrapper -->

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Upload Biaya Pendidikan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open_multipart('lembaga/uploadBp'); ?>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="">Pilih Berkas</label>
                    <input type="file" name="uploadFile" class="form-control" required>
                    <small class="text-danger">* File yang diupload tidak merubah apapun dari tempalte yang di
                        download</small>
                </div>
                <a href="<?= base_url('lembaga/downBpTmp'); ?>"><i class="bx bx-download"></i> Donload Template Format
                    Upload Tanggungan Disini!</a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Upload Tanggungan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>