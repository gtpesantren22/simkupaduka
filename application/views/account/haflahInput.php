<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Pengajuan Haflah</div>
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

                        <div class="row">
                            <!-- table data -->
                            <div class="col-md-8">
                                <a href="<?= base_url('account/vervalHaflah/' . $pj->kode_pengajuan); ?>" class="btn btn-success btn-sm tbl-confirm" value="Pengajuan akan disetujui dan selanjutnya akan dicairkan"><i class="bx bx-check-circle"></i>Verifikasi/Setujui</a>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#ex_tolak"><i class="bx bx-no-entry"></i>Tolak pengajuan</button>
                            </div>
                            <div class="table-responsive mt-3">
                                <table id="" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr style="background-color: #5ae8ff; font-weight: bold;">
                                            <th>No</th>
                                            <th colspan="2">Program</th>
                                            <th>Estimasi</th>
                                            <th>Terpakai</th>
                                            <th>Sisa</th>
                                            <th>Pengajuan</th>
                                            <th>Ket</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($program as $ls_jns) :
                                            $progam = $this->db->query("SELECT nama, pagu FROM haflah_bidang WHERE kode_bidang = '$ls_jns->bidang' ")->row();
                                            $pakai = $this->db->query("SELECT SUM(qty*harga_satuan) AS pakai FROM haflah_detail JOIN haflah ON haflah_detail.kode_pengajuan=haflah.kode_pengajuan WHERE haflah_detail.bidang = '$ls_jns->bidang' AND (status = 'selesai' OR status = 'dicairkan') ")->row();
                                            $sisa = $progam->pagu - $pakai->pakai;
                                        ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= $progam->nama; ?></td>
                                                <td><?= $ls_jns->items; ?> item</td>
                                                <td><?= rupiah($progam->pagu); ?></td>
                                                <td><?= rupiah($pakai->pakai); ?></td>
                                                <td class="fw-bold text-success"><?= rupiah($sisa); ?></td>
                                                <td class="fw-bold text-primary"><?= rupiah($ls_jns->jumlah); ?></td>
                                                <td>
                                                    <?php if ($sisa >= $ls_jns->jumlah) { ?>
                                                        <span class="badge bg-success">cukup</span>
                                                    <?php } else { ?>
                                                        <span class="badge bg-danger">kurang</span>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6">TOTAL</th>
                                            <th class="text-primary" colspan="2"><?= rupiah($dataSum->jml) ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <table id="example" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                            <th>No</th>
                                            <th>Prog</th>
                                            <th>Kegiatan</th>
                                            <th>Uraian</th>
                                            <th>QTY</th>
                                            <th>Harga</th>
                                            <th>Total</th>
                                            <th>Jenis</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($data as $ls_jns) :
                                        ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= $ls_jns->bidang; ?></td>
                                                <td><?= $ls_jns->nama; ?></td>
                                                <td><?= $ls_jns->uraian; ?></td>
                                                <td><?= $ls_jns->qty . ' ' . $ls_jns->satuan; ?></td>
                                                <td><?= rupiah($ls_jns->harga_satuan); ?></td>
                                                <td><?= rupiah($ls_jns->qty * $ls_jns->harga_satuan); ?></td>
                                                <td><?= $ls_jns->jenis . '. ' . $ls_jns->stas; ?></td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#edit<?= $ls_jns->id_detail ?>">Edit</button>

                                                    <div class="modal fade" id="edit<?= $ls_jns->id_detail ?>" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog ">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Tolak Pengajuan</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <?= form_open('account/haflahEditInput'); ?>
                                                                <input type="hidden" name="id" value="<?= $ls_jns->id_detail ?>">
                                                                <input type="hidden" name="kode" value="<?= $ls_jns->kode_pengajuan ?>">
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group mb-1">
                                                                                <label for="" class="col-sm-2 control-label">Kegiatan *</label>
                                                                                <div class="col-sm-10">
                                                                                    <b> <?= $ls_jns->nama ?></b>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group mb-1">
                                                                                <label for="" class="col-sm-2 control-label">Uraian *</label>
                                                                                <div class="col-sm-10">
                                                                                    <b><?= $ls_jns->uraian ?></b>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group mb-1">
                                                                                <label for="" class="col-sm-2 control-label">QTY *</label>
                                                                                <div class="col-sm-10">
                                                                                    <input type="number" class="form-control" name="qty" value="<?= $ls_jns->qty ?>" required>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group mb-1">
                                                                                <label for="" class="col-sm-2 control-label">Satuan *</label>
                                                                                <div class="col-sm-10">
                                                                                    <input type="text" class="form-control" name="satuan" value="<?= $ls_jns->satuan ?>" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group mb-1">
                                                                                <label for="" class="col-sm-3 control-label">Harga Satuan *</label>
                                                                                <div class="col-sm-9">
                                                                                    <input type="text" class="form-control uang" id="" name="harga_satuan" value="<?= $ls_jns->harga_satuan ?>" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group mb-1">
                                                                                <label for="" class="col-sm-3 control-label">Jenis Belanja *</label>
                                                                                <div class="col-sm-9">
                                                                                    <select name="jenis" class="form-control" required>
                                                                                        <option value=""> -- pilih jenis -- </option>
                                                                                        <?php foreach ($jenis as $jns) : ?>
                                                                                            <option <?= $jns->kode_jns == $ls_jns->jenis ? 'selected' : '' ?> value="<?= $jns->kode_jns . '-' . $jns->stas ?>"><?= $jns->kode_jns . ' - ' . $jns->nama ?></option>
                                                                                        <?php endforeach; ?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                                </div>
                                                                <?= form_close(); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6">TOTAL</th>
                                            <th colspan="3"><?= rupiah($dataSum->jml) ?></th>
                                        </tr>
                                    </tfoot>
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

<div class="modal fade" id="ex_tolak" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Pengajuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('account/tolakHaflah'); ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="kode" value="<?= $pj->kode_pengajuan; ?>">
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