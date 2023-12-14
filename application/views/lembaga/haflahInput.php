<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Pengajuan haflah</div>
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
                        <?php

                        use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Base;

                        if ($pj->status == 'belum' | $pj->status == 'ditolak') { ?>
                            <?= form_open('lembaga/haflahAddInput'); ?>
                            <input type="hidden" name="kode_pengajuan" value="<?= $pj->kode_pengajuan ?>">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-1">
                                            <label for="inputEmail3" class="col-sm-2 control-label">Kegiatan *</label>
                                            <div class="col-sm-10">
                                                <select name="bidang" class="form-control" required>
                                                    <option value=""> -- pilih kegiatan -- </option>
                                                    <?php foreach ($bidang as $lembagaData) : ?>
                                                        <option value="<?= $lembagaData->kode_bidang ?>"><?= $lembagaData->nama ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group mb-1">
                                            <label for="inputEmail3" class="col-sm-2 control-label">Uraian *</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" name="uraian" required></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group mb-1">
                                            <label for="inputEmail3" class="col-sm-2 control-label">Tahun *</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="tahun" value="<?= $tahun ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-1">
                                            <label for="inputEmail3" class="col-sm-2 control-label">QTY *</label>
                                            <div class="col-sm-10">
                                                <input type="number" class="form-control" name="qty" required>
                                            </div>
                                        </div>
                                        <div class="form-group mb-1">
                                            <label for="inputEmail3" class="col-sm-2 control-label">Satuan *</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="satuan" required>
                                            </div>
                                        </div>
                                        <div class="form-group mb-1">
                                            <label for="inputEmail3" class="col-sm-3 control-label">Harga Satuan *</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control uang" id="" name="harga_satuan" required>
                                            </div>
                                        </div>
                                        <div class="form-group mb-1">
                                            <label for="inputEmail3" class="col-sm-3 control-label">Jenis Belanja *</label>
                                            <div class="col-sm-9">
                                                <select name="jenis" class="form-control" required>
                                                    <option value=""> -- pilih jenis -- </option>
                                                    <?php foreach ($jenis as $jns) : ?>
                                                        <option value="<?= $jns->kode_jns ?>"><?= $jns->kode_jns . ' - ' . $jns->nama ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Add Pengajuan</button>
                            </div>
                            <?= form_close(); ?>
                        <?php } ?>
                    </div>
                </div>
                <div class="card radius-10">
                    <div class="card-body">

                        <div class="row">
                            <!-- table data -->
                            <div class="col-md-3">
                                <?php if ($pj->status == 'belum' || $pj->status == 'ditolak') { ?>
                                    <a href="<?= base_url('lembaga/ajukanHaflah/' . $pj->kode_pengajuan) ?>" class="btn btn-sm btn-warning tbl-confirm" value="Pengajuan akan di lanjutkan kepada bendahara"><i class="bx bx-upload"></i>Ajukan ke Bendahara</a>
                                <?php } ?>
                            </div>
                            <div class="table-responsive mt-3">
                                <table id="example" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                            <th>No</th>
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
                                                <td><?= $ls_jns->nama; ?></td>
                                                <td><?= $ls_jns->uraian; ?></td>
                                                <td><?= $ls_jns->qty . ' ' . $ls_jns->satuan; ?></td>
                                                <td><?= rupiah($ls_jns->harga_satuan); ?></td>
                                                <td><?= rupiah($ls_jns->qty * $ls_jns->harga_satuan); ?></td>
                                                <td><?= $ls_jns->jenis . '. ' . $ls_jns->stas; ?></td>
                                                <td>
                                                    <?php if ($pj->status == 'belum' | $pj->status == 'ditolak') { ?>
                                                        <a href="<?= base_url('lembaga/delItemHaflah/' . $ls_jns->id_detail) ?>" class="btn btn-danger btn-sm tbl-confirm" value="Yakin akan menghapus item ini"><i class="bx bx-trash"></i></a>
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