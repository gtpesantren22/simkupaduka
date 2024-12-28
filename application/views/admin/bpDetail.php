<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Daftar Biaya Pendidikan Santri</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-folder-open"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Master Data</li>
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
                            <?= form_open('admin/bpEdit') ?>
                            <div class="col-6">
                                <label for="" class="form-label">* NIS Santri</label>
                                <input class="form-control mb-3" type=" text" placeholder="Readonly input here..." aria-label="readonly input example1" name="nis" value="<?= $bp->nis; ?>" readonly>
                                <label for="" class="form-label">* Nama</label>
                                <input class="form-control mb-3" type=" text" placeholder="Readonly input here..." aria-label="readonly input example2" name="nama" value="<?= $bp->nama; ?>" readonly>
                                <label for="" class="form-label">* No. Briva</label>
                                <input class="form-control mb-3" type=" text" placeholder="Readonly input here..." aria-label="readonly input example3" name="briva" value="<?= $bp->briva; ?>">
                            </div>
                            <div class="col-6">
                                <input type="hidden" name="id" value="<?= $bp->id_tangg; ?>">
                                <label for="" class="form-label">* Juli - April</label>
                                <input class="form-control mb-3 uang" type="text" placeholder="Default input" aria-label="default input example" name="ju_ap" value="<?= $bp->ju_ap; ?>" required>
                                <label for="" class="form-label">* Mei - Juni</label>
                                <input class="form-control mb-3 uang" type="text" placeholder="Default input" aria-label="default input example" name="me_ju" value="<?= $bp->me_ju; ?>" required>
                                <label for="" class="form-label">* Total</label>
                                <input class="form-control mb-3" type="text" placeholder="Default input" aria-label="default input example" name="total" value="<?= rupiah($bp->ju_ap * 10 + $bp->me_ju * 2); ?>" readonly>
                                <button class="btn btn-success"><i class="bx bx-check-circle"></i>SIMPAN</button>
                                <a href="<?= base_url('admin/bp'); ?>" class="btn btn-warning"><i class="bx bx-left-arrow-circle"></i>KEMBALI</a>
                            </div>
                            <?= form_close() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<!--end page wrapper -->