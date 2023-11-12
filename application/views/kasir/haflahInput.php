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
                                <?= form_open('kasir/cairHaflah'); ?>
                                <input type="hidden" name="kode_pengajuan" value="<?= $pj->kode_pengajuan ?>">
                                <div class="form-group mb-2">
                                    <label for="inputEmail3">Jumlah akan
                                        dicairkan</label>
                                    <input type="text" name="total" class="form-control" id="" value="<?= rupiah($dataSum->jml) ?>" readonly>

                                </div>
                                <div class="form-group mb-2">
                                    <label for="inputPassword3">Tanggal
                                        Pencairan</label>
                                    <input type="text" class="form-control datepickerFlats" id="" name="tgl_cair" required>

                                </div>
                                <div class="form-group mb-2">
                                    <label for="inputPassword3">Penerima</label>
                                    <input type="text" class="form-control" id="" name="penerima" required>
                                </div>

                                <div class="form-group mb-2">
                                    <label for="inputPassword3">Pencair</label>
                                    <input type="text" name="kasir" class="form-control" value="<?= $user->nama ?>" readonly>
                                </div>

                                <div class="form-group mb-2">

                                    <button type="submit" name="cairkan" class="btn btn-success pull-right"><i class="bx bx-save"></i> Simpan pencairan</button>

                                </div>
                                <?= form_close(); ?>
                            </div>
                            <div class="table-responsive mt-3">
                                <table id="example" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                            <th>No</th>
                                            <th>Lembaga</th>
                                            <th>Uraian</th>
                                            <th>QTY</th>
                                            <th>Harga</th>
                                            <th>Total</th>
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
                                                <td><?= $ls_jns->nama; ?></td>
                                                <td><?= $ls_jns->uraian; ?></td>
                                                <td><?= $ls_jns->qty . ' ' . $ls_jns->satuan; ?></td>
                                                <td><?= rupiah($ls_jns->harga_satuan); ?></td>
                                                <td><?= rupiah($ls_jns->qty * $ls_jns->harga_satuan); ?></td>
                                                <td>
                                                    <?php if ($pj->status == 'belum' | $pj->status == 'ditolak') { ?>
                                                        <a href="<?= base_url('kasir/delItemSarpras/' . $ls_jns->id_detail) ?>" class="btn btn-danger btn-sm tbl-confirm" value="Yakin akan menghapus item ini"><i class="bx bx-trash"></i></a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5">TOTAL</th>
                                            <th colspan="2"><?= rupiah($dataSum->jml) ?></th>
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
            <form action="<?= base_url('kasir/tolakSarpras'); ?>" method="post">
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