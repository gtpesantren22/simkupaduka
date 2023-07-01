<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Dispensasi</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-shopping-bag"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Tanggungan</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <?= form_open('kasir/saveDispen'); ?>
                                <div class="form-group mb-2">
                                    <label for="inputEmail3">Nama Santri</label>
                                    <input type="text" name="nama" class="form-control" value="<?= $santri->nama ?>" readonly>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="inputPassword3">BP</label>
                                    <div class="input-group"> <span class="input-group-text" id="basic-addon1">Rp.</span>
                                        <input type="text" class="form-control uang" id="" name="bp" value="<?= $dispn->bp ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="inputPassword3">Sandal Waqof</label>
                                    <div class="input-group"> <span class="input-group-text" id="basic-addon1">Rp.</span>
                                        <input type="text" class="form-control uang" id="" name="sandal" value="<?= $dispn->sandal ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="inputPassword3">Tindakan Lomba</label>
                                    <div class="input-group"> <span class="input-group-text" id="basic-addon1">Rp.</span>
                                        <input type="text" class="form-control uang" id="" name="lomba" value="<?= $dispn->lomba ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="inputPassword3">Tindakan Wilayah</label>
                                    <div class="input-group"> <span class="input-group-text" id="basic-addon1">Rp.</span>
                                        <input type="text" class="form-control uang" id="" name="wilayah" value="<?= $dispn->wilayah ?>" readonly>
                                    </div>
                                </div>

                                <div class="form-group mb-2">
                                    <!-- <button type="submit" name="cairkan" class="btn btn-success pull-right"><i class="bx bx-save"></i> Simpan</button> -->
                                </div>
                                <?= form_close(); ?>
                            </div>
                            <div class="col-md-6">
                                <?= form_open('kasir/printDispen'); ?>
                                <input type="hidden" name="nis" value="<?= $santri->nis ?>">
                                <div class="form-group mb-2">
                                    <label for="inputPassword3">Total Tanggungan</label>
                                    <div class="input-group"> <span class="input-group-text" id="basic-addon1">Rp.</span>
                                        <input type="text" class="form-control uang" id="" name="bp" value="<?= $dispn->bp + $dispn->sandal + $dispn->lomba + $dispn->wilayah ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="inputPassword3">Bayar</label>
                                    <div class="input-group"> <span class="input-group-text" id="basic-addon1">Rp.</span>
                                        <input type="text" class="form-control uang" id="" name="bayar" required value="<?= $dispn->bayar ?>">
                                    </div>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="inputPassword3">Tgl Bayar</label>
                                    <div class="input-group"> <span class="input-group-text" id="basic-addon1"><i class="bx bx-calendar"></i></span>
                                        <input type="date" class="form-control" id="" name="tgl_bayar" required value="<?= $dispn->tgl_bayar ?>">
                                    </div>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="inputPassword3">Perjanjian Pelunasan</label>
                                    <div class="input-group"> <span class="input-group-text" id="basic-addon1"><i class="bx bx-calendar"></i></span>
                                        <select name="janji" id="" class="form-control single-select" required>
                                            <option value=""> -pilih- </option>
                                            <?php for ($i = 1; $i <= 12; $i++) : ?>
                                                <option <?= $i == $dispn->janji ? 'selected' : '' ?> value="<?= $i ?>"><?= $bulan[$i] ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-2">
                                    <button type="submit" class="btn btn-success pull-right"><i class="bx bx-printer"></i> Cetak</button>
                                </div>
                                <?= form_close(); ?>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<!--end page wrapper -->