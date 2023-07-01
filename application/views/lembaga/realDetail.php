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
                                        <th>Kode</th>
                                        <th>Barang/Kegiatan</th>
                                        <th>Jenis</th>
                                        <th>Anggaran RAB</th>
                                        <th>Realiasasi</th>
                                        <th>Sisa</th>
                                        <th>Pemakaian (%)</th>
                                    </tr>
                                    <!-- <tr style="color: white; background-color: plum; font-weight: bold;">
                                        <th colspan="7">A. Belanja Barang</th>
                                    </tr> -->
                                </thead>
                                <tbody>
                                    <?php
                                    // $dt1 = mysqli_query($conn, "SELECT * FROM rab WHERE jenis = '$jenis' AND lembaga = '$kode' AND tahun = '$tahun_ajaran' ");
                                    // $dt2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total) AS tt FROM rab WHERE jenis = '$jenis' AND lembaga = '$kode' AND tahun = '$tahun_ajaran' "));
                                    // $dt3 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS tt FROM realis WHERE jenis = '$jenis' AND lembaga = '$kode' AND tahun = '$tahun_ajaran' GROUP BY kode "));
                                    $no = 1;
                                    foreach ($rabA as $r1) {
                                        $kd = $r1->kode;
                                        $rs = $this->db->query("SELECT IFNULL(SUM(nominal),0) as nom FROM realis WHERE kode = '$kd' ")->row();
                                        $sisa = $r1->total - $rs->nom;
                                        $prc = round(($rs->nom / $r1->total) * 100, 0);
                                        if ($prc >= 0 && $prc <= 25) {
                                            $bg = 'bg-success';
                                        } elseif ($prc >= 26 && $prc <= 50) {
                                            $bg = 'bg-info';
                                        } elseif ($prc >= 51 && $prc <= 75) {
                                            $bg = 'bg-warning';
                                        } elseif ($prc >= 76 && $prc <= 100) {
                                            $bg = 'bg-danger';
                                        }
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><a href="<?= base_url('lembaga/cekRealis/' . $r1->kode) ?>"><?= $r1->kode ?></a>
                                            </td>
                                            <td><?= $r1->nama ?></td>
                                            <td><?= $r1->jenis ?></td>
                                            <td><?= rupiah($r1->total) ?></td>
                                            <td><?= rupiah($rs->nom) ?></td>
                                            <td><?= rupiah($sisa) ?></td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated <?= $bg ?>" role="progressbar" style="width: <?= $prc ?>%" aria-valuenow="<?= $prc ?>" aria-valuemin="0" aria-valuemax="100">
                                                        <?= $prc ?>%</div>
                                                </div>
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