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
                            <div class="col-md-8">
                                <?= form_open('kasir/saveDispen'); ?>
                                <div class="form-group mb-2">
                                    <label for="inputEmail3">Pilih Santri</label>
                                    <select name="nis" id="" class="form-control single-select" required>
                                        <option value=""> -pilih- </option>
                                        <?php foreach ($santri as $sn) : ?>
                                            <option value="<?= $sn->nis ?>"><?= $sn->nama ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="inputPassword3">Sandal Waqof</label>
                                    <input type="text" class="form-control uang" id="" name="sandal" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="inputPassword3">Tindakan Lomba</label>
                                    <input type="text" class="form-control uang" id="" name="lomba" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="inputPassword3">Tindakan Wilayah</label>
                                    <input type="text" class="form-control uang" id="" name="wilayah" required>
                                </div>

                                <div class="form-group mb-2">

                                    <button type="submit" name="cairkan" class="btn btn-success pull-right"><i class="bx bx-save"></i> Simpan</button>

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