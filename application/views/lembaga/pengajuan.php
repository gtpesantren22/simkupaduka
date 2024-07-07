<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

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
                        <?php if ($akses->pengajuan === 'Y') {
                            if ($cekSPJ < 1) { ?>
                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bx bx-plus"></i> Tambah Pengajuan Baru</button>
                            <?php } else if ($cekPjn < 1) { ?>
                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bx bx-plus"></i> Tambah Pengajuan Baru</button>
                        <?php }
                        } ?>
                        <?php
                        echo $akses->pengajuan . '<br>';
                        echo $cekSPJ . '<br>';
                        echo $cekPjn . '<br>';
                        ?>

                        <div class="table-responsive mt-3">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Bulan</th>
                                        <th>Tahun</th>
                                        <th>Verifikasi</th>
                                        <th>Persetujuan</th>
                                        <th>Pencairan</th>
                                        <th>SPJ</th>
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
                                            <td><?= $ls_jns->kode_pengajuan; ?></td>
                                            <td><?= $bulan[$ls_jns->bulan]; ?></td>
                                            <td><?= $ls_jns->tahun; ?></td>
                                            <td>
                                                <?= $ls_jns->verval == 1 ? "<span class='badge bg-success'><i class='bx bx-check'></i> sudah</span>" : "<span class='badge bg-danger'><i class='bx bx-no-entry'></i> belum</span>"; ?>
                                            </td>
                                            <td>
                                                <?= $ls_jns->apr == 1 ? "<span class='badge bg-success'><i class='bx bx-check'></i> sudah</span>" : "<span class='badge bg-danger'><i class='bx bx-no-entry'></i> belum</span>"; ?>
                                            </td>
                                            <td>
                                                <?= $ls_jns->cair == 1 ? "<span class='badge bg-success'><i class='bx bx-check'></i> sudah</span>" : "<span class='badge bg-danger'><i class='bx bx-no-entry'></i> belum</span>"; ?>
                                            </td>
                                            <td>
                                                <?php if ($ls_jns->spj == 0) { ?>
                                                    <span class="badge bg-danger"><i class="bx bx-no-entry"></i> belum
                                                        upload</span>
                                                <?php } else if ($ls_jns->spj == 1) { ?>
                                                    <span class="badge bg-warning"><i class="bx bx-recycle"></i>
                                                        proses verifikasi</span>
                                                <?php } else { ?>
                                                    <span class="badge bg-success"><i class="bx bx-check"></i> sudah
                                                        selesai</span>
                                                <?php } ?>
                                            </td>
                                            <td><a href="<?= base_url('lembaga/pengajuanDetail/' . $ls_jns->kode_pengajuan) ?>"><button class="btn btn-info btn-sm"><i class="bx bx-search"></i>
                                                        cek</button></a></td>
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
<?php if ($akses->pengajuan === 'Y') : ?>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Buat Pengajuan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?= form_open('lembaga/pengajuanAdd'); ?>
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
                            <input type="text" class="form-control" name="tahun" value="<?= date('Y') ?>" readonly>
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