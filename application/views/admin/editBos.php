<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Pemasukan BOS</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-money"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Pemasukan</li>
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
                            <div class="col-md-10">
                                <div class="card radius-10 bg-success bg-gradient">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <p class="mb-0 text-white">TOTAL PEMASUKAN DARI BOS</p>
                                                <h4 class="my-1 text-white"><?= rupiah($sumBos->nominal); ?></h4>
                                            </div>
                                            <div class="text-white ms-auto font-35"><i class='bx bx-money'></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card radius-10 bg-primary bg-gradient">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="text-white ms-auto font-35">
                                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPes"><i class="bx bx-plus-circle"></i>
                                                    Tambah</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?= form_open('admin/saveEditBos'); ?>
                        <input type="hidden" name="id_bos" value="<?= $pes->id_bos ?>">
                        <div class="modal-body">
                            <div class="form-group mb-2">
                                <label for="">Lembaga</label>
                                <select name="lembaga" class="form-control" id="" required>
                                    <option value=""> -pilih lembaga- </option>
                                    <?php
                                    foreach ($lembaga as $a) { ?>
                                        <option <?= $a->kode == $pes->lembaga ? 'selected' : '' ?> value="<?= $a->kode ?>">
                                            <?= $a->kode ?>.
                                            <?= $a->nama ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label for="">Uraian</label>
                                <input type="text" name="uraian" class="form-control" required value="<?= $pes->uraian; ?>">
                            </div>
                            <div class="form-group mb-2">
                                <label for="">Periode</label>
                                <input type="text" name="periode" class="form-control" required value="<?= $pes->periode; ?>">
                            </div>
                            <div class="form-group mb-2">
                                <label for="">Nominal</label>
                                <input type="text" name="nominal" class="form-control uang" required value="<?= $pes->nominal; ?>">
                            </div>
                            <div class="form-group mb-2">
                                <label for="">Tahan Pelajaran</label>
                                <select name="tahun" class="form-control" id="" required>
                                    <option value=""> -pilih tahun- </option>
                                    <?php
                                    foreach ($tahunData as $a) { ?>
                                        <option <?= $a->nama_tahun == $pes->tahun ? 'selected' : '' ?> value="<?= $a->nama_tahun ?>"><?= $a->nama_tahun ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label for="">Tanggal Setor</label>
                                <input type="text" name="tgl_setor" id="date" class="form-control" required value="<?= $pes->tgl_setor; ?>">
                            </div>
                            <div class="form-group mb-2">
                                <label for="">Penerima</label>
                                <input type="text" name="kasir" class="form-control" required value="<?= $pes->kasir; ?>">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Simpan
                                Data</button>
                        </div>
                        <?= form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<!--end page wrapper -->