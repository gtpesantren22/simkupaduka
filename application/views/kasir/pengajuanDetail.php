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
                            <div class="col-md-7">
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
                            <div class="col-md-5">
                                <!-- Sisa -->
                                <address>
                                    <strong style="color: darkblue;">Periode :
                                        <?= $bulan[$rinci->bulan] . ' ' . $rinci->tahun ?></strong><br>
                                    <strong style="color: red; font-style: italic;">- Harap di cek dulu sebelum di
                                        Verifikasi</strong><br>
                                    <strong style="color: red; font-style: italic;">- Khawatir ada Pengajuan yang
                                        melebihi Sisa RAB</strong>
                                </address>
                                <hr>
                                <address>
                                    <?php
                                    if ($rinci->stts == 'no') {
                                        echo "<strong style='color: red; font-style: italic;'>Mohon maaf. Pengajuan masih belum bisa di Verifikasi dikarenakan dalam proses penginputan.</strong>";
                                    } else if ($rinci->verval == 1) {
                                        echo "<strong style='color: red; font-style: italic;'>Pengajuan ini sudah di verifikasi</strong>";
                                    } else { ?>
                                        <a href="<?= base_url('account/vervalPengajuan/' . $rinci->kode_pengajuan); ?>" class="btn btn-success btn-sm tbl-confirm" value="Pengajuan akan disetujui dan selanjutnya akan dicairkan"><i class="bx bx-check-circle"></i>Verifikasi/Setujui</a><br>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#ex_tolak"><i class="bx bx-no-entry"></i>Tolak
                                            pengajuan</button><br>
                                    <?php } ?>
                                </address>

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
                                        <th>#</th>
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
                                                        <b><i><a href="<?= base_url('account/downHonor/' . $fl->files); ?>"> (<i class="bx bx-download"></i> Download)</a></i></b>
                                                    <?php } else { ?>
                                                        <b><i><a href="#"> (Belum ada)</a></i></b>
                                                <?php }
                                                } ?>
                                            </td>
                                            <td><?= rupiah($a->nominal) ?></td>
                                            <td><?= rupiah($a->nom_cair) ?></td>
                                            <td><?= $a->stas ?></td>
                                            <td>
                                                <?php if ($rinci->cair == 0) { ?>
                                                    <a href="<?= base_url('account/delRealSm/' . $a->id_realis) ?>" class="tombol-hapus"><span class="bx bx-no-entry text-danger">
                                                        </span></a>
                                                    |
                                                    <a type="button" data-bs-toggle="modal" data-bs-target="#bs-example<?= $a->id_realis ?>"><span class="bx bx-pencil text-success"> </span></a>

                                                    <div class="modal fade" id="bs-example<?= $a->id_realis ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Nominal
                                                                        Pengajuan</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form action="<?= base_url('account/editRealSm'); ?>" method="post">
                                                                    <input type="hidden" name="id_rsm" value="<?= $a->id_realis ?>">
                                                                    <div class="modal-body">
                                                                        <div class="form-group mb-2 mb-2">
                                                                            <label for="">Keterangan</label>
                                                                            <textarea name="ket" class="form-control" required>
                                                                            <?= $a->ket; ?>
                                                                        </textarea>
                                                                        </div>
                                                                        <div class="form-group mb-2 mb-2">
                                                                            <label for="">Edit Nominal</label>
                                                                            <input type="text" name="nom_cair" value="<?= $a->nom_cair; ?>" class="form-control uang" required>
                                                                        </div>

                                                                        <div class="form-group mb-2 mb-2">
                                                                            <label for="">Ket. Pencairan</label><br>
                                                                            <input type="radio" name="stas" value="tunai" <?= $a->stas === 'tunai' ? 'checked' : ''; ?> required> Cair Tunai
                                                                            <input type="radio" name="stas" value="barang" <?= $a->stas === 'barang' ? 'checked' : ''; ?> required> Cair Barang
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                        <button type="submit" name="ed_nom" class="btn btn-primary">Simpan perubahan</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
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
<!--end page wrapper -->

<div class="modal fade" id="ex_tolak" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Pengajuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('account/tolakPengajuan'); ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="kode" value="<?= $rinci->kode_pengajuan; ?>">
                    <div class="form-group mb-2">
                        <label class="" for="first-name">Nama Lembaga <span class="required">*</span>
                        </label>
                        <div class="">
                            <input type="text" id="" name="lembaga" required="required" value="<?= $lembaga->nama ?>" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label class="" for="first-name">Tanggal Penolakan
                            <span class="required">*</span>
                        </label>
                        <div class="">
                            <input type="text" id="date-time" name="tgl" required="required" class="form-control">
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label class="" for="first-name">Catatan <span class="required">*</span>
                        </label>
                        <div class="">
                            <textarea name="pesan" required="required" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label class="" for="first-name">Verifikator <span class="required">*</span>
                        </label>
                        <div class="">
                            <input type="text" id="" name="user" required="required" value="<?= $user->nama ?>" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
                </div>
            </form>
        </div>
    </div>
</div>