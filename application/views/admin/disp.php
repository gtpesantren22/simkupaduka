<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<?php
$total = $pakai->total;
$sisa = ($total / 50000000) * 100;
$prsn = round($sisa, 1);

if ($prsn <= 20) {
    $bg = 'bg-primary';
} elseif ($prsn > 20 && $prsn <= 40) {
    $bg = 'bg-success';
} elseif ($prsn > 40 && $prsn <= 60) {
    $bg = 'bg-info';
} elseif ($prsn > 60 && $prsn <= 80) {
    $bg = 'bg-warning';
} elseif ($prsn > 80 && $prsn <= 100) {
    $bg = 'bg-danger';
}

?>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Pengajuan Disposisi</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-coin-stack"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Disposisi</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row row-cols-1 row-cols-md-3 row-cols-xl-12">
            <div class="col">
                <div class="card radius-10 bg-primary bg-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-white">Limit Disposisi</p>
                                <h4 class="my-1 text-white"><?= rupiah(50000000); ?></h4>
                            </div>
                            <div class="text-white ms-auto font-35"><i class='bx bx-cart-alt'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 bg-danger bg-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-white">Terpakai</p>
                                <h4 class="my-1 text-white"><?= rupiah($pakai->total); ?></h4>
                            </div>
                            <div class="text-white ms-auto font-35"><i class='bx bx-dollar'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 bg-success bg-gradient">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-white">Sisa Dana</p>
                                <h4 class="my-1 text-white"><?= rupiah(50000000 - $pakai->total); ?></h4>
                            </div>
                            <div class="text-white ms-auto font-35"><i class='bx bx-comment-detail'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-3">
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated <?= $bg; ?>" role="progressbar"
                    style="width: <?= $prsn; ?>%" aria-valuenow="<?= $prsn; ?>" aria-valuemin="0" aria-valuemax="100">
                    <?= $prsn; ?>%</div>
            </div>
        </div>
        <!--end row-->
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Lembaga</th>
                                        <th>Tanggal</th>
                                        <th>Nominal Cair</th>
                                        <th>Status</th>
                                        <!-- <th>#</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data as $a) :

                                        $kd_pj = $a->kode_pengajuan;
                                        $jml = $this->db->query("SELECT SUM(nom_cair) AS jml FROM real_sm WHERE kode_pengajuan = '$kd_pj' AND tahun = '$tahun' ")->row();
                                        $jml2 = $this->db->query("SELECT SUM(nom_cair) AS jml FROM realis WHERE kode_pengajuan = '$kd_pj' AND tahun = '$tahun' ")->row();
                                        $kfe = $jml->jml + $jml2->jml;
                                    ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $a->kode_pengajuan ?></td>
                                        <td><?= $a->nama ?></td>
                                        <td><?= $a->at ?></td>
                                        <td><?= rupiah($kfe) ?></td>
                                        <td>
                                            <?= $a->verval == 1 ? "<span class='badge bg-success'><i class='bx bx-check'></i>sudah</span>" : "<span class='badge bg-danger'><i class='bx bx-times'></i>belum</span>"; ?>
                                            <?= $a->apr == 1 ? "<span class='badge bg-success'><i class='bx bx-check'></i>sudah</span>" : "<span class='badge bg-danger'><i class='bx bx-times'></i>belum</span>"; ?>
                                            <?= $a->cair == 1 ? "<span class='badge bg-success'><i class='bx bx-check'></i>sudah</span>" : "<span class='badge bg-danger'><i class='bx bx-times'></i>belum</span>"; ?>
                                            <?php if ($a->spj == 0) { ?>
                                            <span class="badge bg-danger"><i class="bx bx-times"></i>belum
                                                upload</span>
                                            <?php } else if ($a->spj == 1) { ?>
                                            <span class="badge bg-warning btn-xs"><i
                                                    class="bx bx-spinner fa-refresh-animate"></i>
                                                proses verifikasi</span>
                                            <?php } else { ?>
                                            <span class="badge bg-success"><i class="bx bx-check"></i>sudah
                                                selesai</span>
                                            <?php } ?>
                                        </td>
                                        <!-- <td>
                                                    <a href="<?= 'edit_disp.php?id=' . $a->id_disp ?>"><button class="btn btn-warning btn-sm"><i class="bx bx-edit"></i>Edit</button></a>
                                                    <a onclick="return confirm('Yakin akan dihapus ?')" href="<?= 'hapus.php?kd=dsp&id=' . $a->id_disp ?>"><button class="btn btn-danger btn-sm"><i class="bx bx-trash"></i>Del</button></a>
                                                </td> -->
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<!--end page wrapper -->
<!--end page wrapper -->