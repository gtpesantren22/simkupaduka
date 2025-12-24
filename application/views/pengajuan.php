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

                        <button class="btn btn-success btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bx bx-plus-circle"></i> Buat Pengajuan Baru</button>
                        <div class="table-responsive">
                            <table id="table1" class="table table-hover align-middle table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Kode</th>
                                        <th scope="col">Bulan</th>
                                        <th scope="col">Perencanaan</th>
                                        <th scope="col">Bendahara</th>
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
                                                <?= $a->apr == 1 ? "<span class='badge bg-success'><i class='bx bx-check'></i> sudah</span>" : "<span class='badge bg-danger'><i class='bx bx-no-entry'></i> belum</span>";  ?>
                                            </td>
                                            <td>
                                                <?= $a->verval == 1 ? "<span class='badge bg-success'><i class='bx bx-check'></i> sudah</span>" : "<span class='badge bg-danger'><i class='bx bx-no-entry'></i> belum</span>"; ?>
                                            </td>
                                            <td>
                                                <?= $a->cair == 1 ? "<span class='badge bg-success'><i class='bx bx-check'></i> sudah</span>" : "<span class='badge bg-danger'><i class='bx bx-no-entry'></i> belum</span>"; ?>
                                            </td>
                                            <td>
                                                <?php if ($a->spj == 0 && $a->file_spj == '') { ?>
                                                    <span class="badge bg-danger"><i class="bx bx-no-entry"></i> belum upload</span>
                                                <?php } else if ($a->spj == 0 && $a->file_spj != '') { ?>
                                                    <span class="badge bg-danger"><i class="bx bx-x"></i> ditolak</span>
                                                <?php } else if ($a->spj == 1) { ?>
                                                    <span class="badge bg-warning"><i class="bx bx-recycle"></i>
                                                        proses verifikasi</span>
                                                <?php } else if ($a->spj == 2) { ?>
                                                    <span class="badge bg-warning"><i class="bx bx-message-square-error"></i> setor SPJ Gan!</span>
                                                <?php } else { ?>
                                                    <span class="badge bg-success"><i class="bx bx-check"></i> sudah
                                                        selesai</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <button onclick="window.location='<?= base_url('pengajuan/detail/' . $a->kode_pengajuan) ?>'" type="button" class="btn btn-success btn-label waves-effect waves-light btn-sm"> Detail</button>
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





<?php if ($akses && $akses->pengajuan === 'Y') : ?>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Buat Pengajuan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?= form_open('pengajuan/pengajuanAdd'); ?>
                <input type="hidden" name="jenis" value="biasa">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="inputEmail3" class="col-sm-2 control-label">Lembaga *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="<?= $lembaga->nama ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="inputEmail3" class="col-sm-2 control-label">Bulan *</label>
                        <div class="col-sm-10">
                            <select name="bulan" class="form-control" required>
                                <option value=""> -- pilih bulan -- </option>
                                <?php
                                for ($i = 1; $i < count($bulan); $i++) { ?>
                                    <option <?= date('m') == $i ? 'selected' : '' ?> value="<?= $i ?>"><?= $bulan[$i] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="inputEmail3" class="col-sm-2 control-label">Tahun *</label>
                        <div class="col-sm-10">
                            <select name="tahun" class="form-control" required>
                                <option value=""> -- pilih tahun -- </option>
                                <?php
                                for ($i = 2000; $i <= date('Y') + 1; $i++) { ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Buat Pengajuan</button>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
<?php endif; ?>