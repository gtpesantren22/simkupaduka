<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>
<?php
$ck2 = $this->db->query("SELECT * FROM pak WHERE lembaga = '$lembaga->kode' AND tahun = '$tahun' AND status != 'selesai' ")->num_rows();
?>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">PAK Online</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-notepad"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">RAB</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <?php if (date('Y-m-d') >= $tgl->login && date('Y-m-d') <= $tgl->disposisi) { ?>
                        <h4 class="badge bg-primary">
                            <?= date('d F Y', strtotime($tgl->login)) . ' - ' . date('d F Y', strtotime($tgl->disposisi)); ?>
                        </h4>
                        <?php if ($ck2 < 1) { ?>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal"><i
                                class="bx bx-plus-square"></i> Daftar PAK
                            Baru</button>
                        <?php } ?>

                        <div class="table-responsive mt-3">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr style="color: white; background-color: #008CFF; font-weight: bold;">
                                        <th>No</th>
                                        <th>Kode PAK</th>
                                        <th>Tanggal PAK</th>
                                        <th>Status</th>
                                        <th>Tahun</th>
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
                                        <td><?= $ls_jns->kode_pak; ?></td>
                                        <td><?= $ls_jns->tgl_pak; ?></td>
                                        <td><?= $ls_jns->status; ?></td>
                                        <td><?= $ls_jns->tahun; ?></td>
                                        <td><a href="<?= base_url('lembaga/pakDetail/' . $ls_jns->kode_pak) ?>"><button
                                                    class="btn btn-info btn-sm"><i class="bx bx-search"></i>
                                                    Edit PAK</button></a></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php } else { ?>
                        <center>
                            <p style="color: red; font-weight: bold;">Belum ada Jadwal PAK Aktif</p>
                        </center>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<!--end page wrapper -->

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Buat Pengajuan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('lembaga/daftarPak'); ?>
            <input type="hidden" name="jenis" value="disposisi">
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="inputEmail3" class="col-sm-2 control-label">Lembaga *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" value="<?= $lembaga->nama ?>" readonly>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal PAK *</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="tgl_pak"
                            value="<?= date('d F Y', strtotime($tgl->login)) . ' s/d ' . date('d F Y', strtotime($tgl->disposisi)); ?> "
                            readonly>
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
                <button type="submit" class="btn btn-primary">Daftar</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>