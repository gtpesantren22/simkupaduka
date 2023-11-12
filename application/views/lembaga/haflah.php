<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Pengajuan Haflah</div>
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
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addBaruPengajuan"><i class="bx bx-plus"></i>Tambah Pengajuan Baru</button>
                        <?php if (!$pj) { ?>
                        <?php } else if ($pj->status == 'selesai') { ?>
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addBaruPengajuan"><i class="bx bx-plus"></i>Tambah Pengajuan Baru</button>
                        <?php } ?>

                        <div class="table-responsive mt-3">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Bulan</th>
                                        <th>Tahun</th>
                                        <th>Status</th>
                                        <th>SPJ</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data as $ls_jns) :
                                        $kdpj = $ls_jns->kode_pengajuan;
                                        $spj = $this->db->query("SELECT * FROM spj WHERE kode_pengajuan = '$kdpj' ")->row();
                                        $sttsSpj = $spj ? $spj->stts : 0;
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $ls_jns->kode_pengajuan; ?></td>
                                            <td><?= $bulan[$ls_jns->bulan]; ?></td>
                                            <td><?= $ls_jns->tahun; ?></td>
                                            <td>
                                                <?php
                                                if ($ls_jns->status == 'proses') {
                                                    echo "<span class='badge bg-primary'><i class='bx bx-check'></i> Proses</span>";
                                                } elseif ($ls_jns->status == 'ditolak') {
                                                    echo "<span class='badge bg-danger'><i class='bx bx-x'></i> Ditolak</span>";
                                                } elseif ($ls_jns->status == 'disetujui') {
                                                    echo "<span class='badge bg-success'><i class='bx bx-check'></i> Disetujui</span>";
                                                } elseif ($ls_jns->status == 'dicairkan') {
                                                    echo "<span class='badge bg-success'><i class='bx bx-check'></i> Dicairkan</span>";
                                                } elseif ($ls_jns->status == 'selesai') {
                                                    echo "<span class='badge bg-info'><i class='bx bx-check'></i> Selesai</span>";
                                                } elseif ($ls_jns->status == 'belum') {
                                                    echo "<span class='badge bg-warning'>Input</span>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <!-- <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">Upload SPJ</button> -->

                                                <?php if ($sttsSpj == 0) { ?>
                                                    <span class="badge bg-danger"><i class="bx bx-no-entry"></i> belum
                                                        upload</span>
                                                    | <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $ls_jns->id_haflah ?>"><i class="bx bx-upload"></i> Upload berkas SPJ</button>
                                                <?php } else if ($sttsSpj == 1) { ?>
                                                    <span class="badge bg-warning"><i class="bx bx-recycle"></i>
                                                        proses verifikasi</span>
                                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $ls_jns->id_haflah ?>"><i class="bx bx-upload"></i>
                                                        Upload ulang berkas SPJ</button>
                                                <?php } else { ?>
                                                    <span class="badge bg-success"><i class="bx bx-check"></i> sudah
                                                        selesai</span>
                                                <?php } ?>

                                                <div class="modal fade" id="exampleModal<?= $ls_jns->id_haflah ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Form Upload SPJ Haflah</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <?= form_open_multipart('lembaga/uploadSpjHaflah') ?>
                                                            <input type="hidden" name="kode" value="<?= $ls_jns->kode_pengajuan ?>">
                                                            <div class="modal-body">
                                                                <h6>Kode Pengajuan : <?= $ls_jns->kode_pengajuan ?></h6>
                                                                <div class="form-group">
                                                                    <label for="">Pilih File</label>
                                                                    <input type="file" name="file" class="form-control" required>
                                                                    <small style="color: red;">* File upload harus PDF dan Max File 5 MB</small>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Upload File</button>
                                                            </div>
                                                            <?= form_close() ?>
                                                        </div>
                                                    </div>
                                                </div>

                                            </td>
                                            <td><a href="<?= base_url('lembaga/haflahDetail/' . $ls_jns->kode_pengajuan) ?>"><button class="btn btn-info btn-sm"><i class="bx bx-search"></i>cek</button></a></td>
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

<div class="modal fade" id="addBaruPengajuan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Buat Pengajuan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('lembaga/haflahAdd'); ?>
            <input type="hidden" name="jenis" value="biasa">
            <div class="modal-body">
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
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="date" name="tanggal">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tahun *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="tahun" value="<?= $tahun ?>" readonly>
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
<?php if (!$pj || $pj->status == 'selesai') { ?>
<?php } ?>