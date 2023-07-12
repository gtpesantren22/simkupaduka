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
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-12 row-cols-xl-12">
            <?php foreach ($data as $ar) :
                $lembaga = $ar->kode;
                $tt = $this->db->query("SELECT SUM(total) as tt FROM rab WHERE lembaga = '$lembaga' AND tahun = '$tahun_ajaran' ")->row();
            ?>
                <div class="col">
                    <div class="card border-success border-bottom border-3 border-0">
                        <div class="card-body">
                            <h5 class="card-title text-success"><?= $ar->nama; ?></h5>
                            <ul class="list-group list-group-flush">
                                <?php

                                $jenis = $this->db->query("SELECT * FROM jenis WHERE tahun = '$tahun_ajaran' ")->result();
                                foreach ($jenis as $r2) {

                                    $df2 = $this->db->query("SELECT  COUNT(jenis) as jml FROM rab WHERE lembaga = '$lembaga' AND tahun = '$tahun_ajaran' AND jenis = '$r2->kode_jns' ")->row();

                                ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?= $r2->kode_jns . '. ' . $r2->nama ?> <span class="badge bg-primary rounded-pill">
                                            <?= $df2->jml . ' item' ?></span>
                                    </li>
                                <?php } ?>
                            </ul>
                            <hr>
                            <div class="d-flex align-items-center gap-2">
                                <a href="<?= base_url('account/realisDetail/' . $lembaga); ?>" class="btn btn-success btn-sm text-end"><i class='bx bx-search'></i>Detail</a>
                                <span class="fw-bold"><?= rupiah($tt->tt); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <!--end row-->
    </div>
</div>
<!--end page wrapper -->