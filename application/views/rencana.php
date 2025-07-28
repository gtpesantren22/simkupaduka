<?php
include 'lembaga/head.php';
?>
<link href="<?= base_url(''); ?>assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Pengajuan Lembaga</div>
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
                        <div class="table-responsive">
                            <table id="table1" class="table table-hover align-middle table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Kode</th>
                                        <th scope="col">Bulan</th>
                                        <th scope="col">Bendahara</th>
                                        <th scope="col">Perencanaan</th>
                                        <th scope="col">Pencairan</th>
                                        <th scope="col">SPJ</th>
                                        <th scope="col">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    // var_dump($data);
                                    foreach ($dataPj as $a) : ?>
                                        <tr>
                                            <td scope="row"><?= $no++ ?></td>
                                            <td><?= $a->kode_pengajuan ?></td>
                                            <td><?= bulan($a->bulan) . ' ' . $a->tahun ?></td>
                                            <td>
                                                <?= $a->verval == 1 ? "<span class='badge bg-success'><i class='bx bx-check'></i> sudah</span>" : "<span class='badge bg-danger'><i class='bx bx-no-entry'></i> belum</span>"; ?>
                                            </td>
                                            <td>
                                                <?= $a->apr == 1 ? "<span class='badge bg-success'><i class='bx bx-check'></i> sudah</span>" : "<span class='badge bg-danger'><i class='bx bx-no-entry'></i> belum</span>"; ?>
                                            </td>
                                            <td>
                                                <?= $a->cair == 1 ? "<span class='badge bg-success'><i class='bx bx-check'></i> sudah</span>" : "<span class='badge bg-danger'><i class='bx bx-no-entry'></i> belum</span>"; ?>
                                            </td>
                                            <td>
                                                <?php if ($a->spj == 0) { ?>
                                                    <span class="badge bg-danger"><i class="bx bx-no-entry"></i> belum
                                                        upload</span>
                                                <?php } else if ($a->spj == 1) { ?>
                                                    <span class="badge bg-warning"><i class="bx bx-recycle"></i>
                                                        proses verifikasi</span>
                                                <?php } else { ?>
                                                    <span class="badge bg-success"><i class="bx bx-check"></i> sudah
                                                        selesai</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <button onclick="window.location='<?= base_url('pengajuan/rencanaDtl/' . $a->kode_pengajuan) ?>'" type="button" class="btn btn-success btn-label waves-effect waves-light btn-sm"> Detail</button>
                                            </td>
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
<!-- End Page-content -->
<?php include 'lembaga/foot.php' ?>
<script src="<?= base_url(''); ?>assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url(''); ?>assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#table1').DataTable();
    })
</script>