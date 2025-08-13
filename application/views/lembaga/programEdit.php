<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Program</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-message-detail"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Data Program</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <form action="<?= base_url('programs/ubah') ?>" method="post">
                            <input type="hidden" name="id" value="<?= $dt->id ?>">
                            <div class="form-group mb-2">
                                <label for="">Kode Program</label>
                                <input type="text" class="form-control" value="<?= $dt->id_dppk ?>" readonly>
                            </div>
                            <div class="form-group mb-2">
                                <label for="">Nama Program</label>
                                <input type="text" class="form-control" name="program" value="<?= $dt->program ?>" required>
                            </div>
                            <div class="form-group mb-2">
                                <label for="">Bulan</label>
                                <?php
                                $selected = $dt->bulan;
                                $selectedArray = explode(',', $selected);
                                for ($b = 1; $b <= 12; $b++): ?>
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="bulan[]"
                                            value="<?= $b ?>"
                                            id="flexCheckDefault<?= $b ?>"
                                            <?= in_array($b, $selectedArray) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="flexCheckDefault<?= $b ?>">
                                            <?= bulan($b) ?>
                                        </label>
                                    </div>
                                <?php endfor ?>
                            </div>
                            <div class="form-group mb-2">
                                <label for="">Tahun</label>
                                <input type="text" class="form-control" value="<?= $dt->tahun ?>" readonly>
                            </div>
                            <div class="form-group mb-2">
                                <label for=""></label>
                                <button class="btn btn-success btn-sm" type="submit">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<!--end page wrapper -->
<!--end page wrapper -->