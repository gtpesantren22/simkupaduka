<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Seetings</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-cog"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Setting</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card radius-10">
                    <div class="card-body">
                        <div>
                            <h6 class="mb-3 text-center">Daftar Akses Lembaga</h6>
                        </div>
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Lembaga</th>
                                        <th>Login</th>
                                        <th>Disp</th>
                                        <th>Pengajuan</th>
                                        <th>Tahun</th>
                                        <th>Act</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data as $a) : ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $a->nama ?></td>
                                            <td>
                                                <span class="badge bg-<?= $a->login == 'Y' ? 'success' : 'danger' ?>">
                                                    <?= $a->login == 'Y' ? 'Ya' : 'Tidak' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $a->disposisi == 'Y' ? 'success' : 'danger' ?>">
                                                    <?= $a->disposisi == 'Y' ? 'Ya' : 'Tidak' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $a->pengajuan == 'Y' ? 'success' : 'danger' ?>">
                                                    <?= $a->pengajuan == 'Y' ? 'Ya' : 'Tidak' ?>
                                                </span>
                                            </td>
                                            <td><?= $a->tahun ?></td>
                                            <td>
                                                <a href="<?= base_url('admin/delAkses/' . $a->id_akses); ?>" class="tombol-hapus text-danger me-1"><i class="bx bx-trash font-18"></i></a>
                                                <a data-bs-toggle="modal" data-bs-target="#medit<?= $a->id_akses; ?>" href="javascript:;" class="text-primary"><i class="bx bx-edit font-18"></i></a>

                                                <!-- Modal Edit Data-->
                                                <div class="modal fade" id="medit<?= $a->id_akses; ?>" tabindex="-1" aria-labelledby="editLabel<?= $a->id_akses; ?>" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">

                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editLabel<?= $a->id_akses; ?>">Edit Akses Lembaga</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="<?= base_url('admin/saveEditAkses'); ?>" method="post">
                                                                <input type="hidden" name="id_akses" value="<?= $a->id_akses; ?>">
                                                                <div class="modal-body text-start">
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-bold">Lembaga</label>
                                                                        <input class="form-control" type="text" readonly value="<?= $a->nama; ?>">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-bold d-block">Akses Login <span class="text-danger">*</span></label>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio" name="login" id="loginY_<?= $a->id_akses ?>" value="Y" <?= $a->login == 'Y' ? 'checked' : '' ?> required>
                                                                            <label class="form-check-label" for="loginY_<?= $a->id_akses ?>">Ya</label>
                                                                        </div>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio" name="login" id="loginT_<?= $a->id_akses ?>" value="T" <?= $a->login == 'T' ? 'checked' : '' ?>>
                                                                            <label class="form-check-label" for="loginT_<?= $a->id_akses ?>">Tidak</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-bold d-block">Disposisi <span class="text-danger">*</span></label>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio" name="disp" id="dispY_<?= $a->id_akses ?>" value="Y" <?= $a->disposisi == 'Y' ? 'checked' : '' ?> required>
                                                                            <label class="form-check-label" for="dispY_<?= $a->id_akses ?>">Ya</label>
                                                                        </div>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio" name="disp" id="dispT_<?= $a->id_akses ?>" value="T" <?= $a->disposisi == 'T' ? 'checked' : '' ?>>
                                                                            <label class="form-check-label" for="dispT_<?= $a->id_akses ?>">Tidak</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-bold d-block">Pengajuan <span class="text-danger">*</span></label>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio" name="pengajuan" id="pengajuanY_<?= $a->id_akses ?>" value="Y" <?= $a->pengajuan == 'Y' ? 'checked' : '' ?> required>
                                                                            <label class="form-check-label" for="pengajuanY_<?= $a->id_akses ?>">Ya</label>
                                                                        </div>
                                                                        <div class="form-check form-check-inline">
                                                                            <input class="form-check-input" type="radio" name="pengajuan" id="pengajuanT_<?= $a->id_akses ?>" value="T" <?= $a->pengajuan == 'T' ? 'checked' : '' ?>>
                                                                            <label class="form-check-label" for="pengajuanT_<?= $a->id_akses ?>">Tidak</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-bold">Tahun</label>
                                                                        <input class="form-control" type="text" readonly value="<?= $tahun; ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit" name="edit" class="btn btn-primary">Simpan Perubahan</button>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card radius-10">
                    <div class="card-body">
                        <div>
                            <h6 class="mb-3 text-center">Tambah Akses Baru</h6>
                        </div>
                        <?= form_open('admin/saveAkses'); ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih Lembaga <span class="text-danger">*</span></label>
                            <select name="lembaga" class="form-select" required>
                                <option value=""> - Pilih Lembaga - </option>
                                <?php
                                $sal = $this->db->query("SELECT * FROM lembaga WHERE NOT EXISTS (SELECT lembaga FROM akses WHERE lembaga.kode=akses.lembaga AND tahun = '$tahun') AND tahun = '$tahun' ")->result();
                                foreach ($sal as $r) {
                                ?>
                                    <option value="<?= $r->kode; ?>"><?= $r->nama; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold d-block">Akses Login <span class="text-danger">*</span></label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="login" id="addLoginY" value="Y" checked required>
                                <label class="form-check-label" for="addLoginY">Ya</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="login" id="addLoginT" value="T">
                                <label class="form-check-label" for="addLoginT">Tidak</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold d-block">Disposisi <span class="text-danger">*</span></label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="disp" id="addDispY" value="Y" checked required>
                                <label class="form-check-label" for="addDispY">Ya</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="disp" id="addDispT" value="T">
                                <label class="form-check-label" for="addDispT">Tidak</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold d-block">Pengajuan <span class="text-danger">*</span></label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="pengajuan" id="addPengajuanY" value="Y" checked required>
                                <label class="form-check-label" for="addPengajuanY">Ya</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="pengajuan" id="addPengajuanT" value="T">
                                <label class="form-check-label" for="addPengajuanT">Tidak</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tahun <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="tahun" readonly value="<?= $tahun; ?>">
                        </div>
                        <div class="d-grid gap-2 mt-3">
                            <button type="submit" class="btn btn-success"><i class="bx bx-save"></i> Simpan Akses</button>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#buatAksesAll" class="btn btn-outline-primary"><i class="bx bx-cog"></i> Generate Akses All</button>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#editAksesAll" class="btn btn-outline-info"><i class="bx bx-edit"></i> Edit Akses All</button>
                            <a href="<?= base_url('admin/truncAkses') ?>" class="btn btn-outline-danger tbl-confirm" value="Data akses akan dikosongi keseluruhan"><i class="bx bx-trash"></i> Del Akses All</a>
                        </div>
                        <?= form_close(); ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card radius-10">
                    <div class="card-body">
                        <div>
                            <h6 class="mb-3 text-center">Set Tanggal PAK</h6>
                        </div>
                        <?= form_open('admin/savePAK'); ?>
                        <?php
                        $tgl = $this->db->query("SELECT * FROM akses WHERE lembaga = 'umum' ")->row();
                        ?>

                        <div class="modal-body">
                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Tgl
                                    Aktif
                                    PAK <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <h3 class="badge bg-danger">
                                        <?= date('d F Y', strtotime($tgl->login)) . ' s/d ' . date('d F Y', strtotime($tgl->disposisi)); ?>
                                    </h3>
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Dari
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="text" name="dari" id="date" class="form-control" required>
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Sampai
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input type="text" name="sampai" id="date2" class="form-control" required>
                                </div>
                            </div>
                            <div class="item form-group">
                                <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Tahun
                                    <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 ">
                                    <input id="middle-name" class="form-control" type="text" name="tahun" readonly value="<?= $tahun; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="save_date" class="btn btn-success">Ganti Tanggal
                                Akses</button>
                        </div>
                        <?= form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<!--end page wrapper -->

<div class="modal fade" id="buatAksesAll" tabindex="-1" aria-labelledby="buatAksesAllLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="buatAksesAllLabel">Generate Akses Semua Lembaga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/buatAksesAll'); ?>" method="post">
                <div class="modal-body text-start">
                    <div class="alert alert-info py-2 mb-3">
                        <small><i class="bx bx-info-circle"></i> Lembaga yang sudah memiliki akses tidak akan ditimpa/digenerate ulang.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold d-block">Akses Login <span class="text-danger">*</span></label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="login" id="genLoginY" value="Y" checked required>
                            <label class="form-check-label" for="genLoginY">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="login" id="genLoginT" value="T">
                            <label class="form-check-label" for="genLoginT">Tidak</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold d-block">Disposisi <span class="text-danger">*</span></label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="disp" id="genDispY" value="Y" checked required>
                            <label class="form-check-label" for="genDispY">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="disp" id="genDispT" value="T">
                            <label class="form-check-label" for="genDispT">Tidak</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold d-block">Pengajuan <span class="text-danger">*</span></label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pengajuan" id="genPengajuanY" value="Y" checked required>
                            <label class="form-check-label" for="genPengajuanY">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pengajuan" id="genPengajuanT" value="T">
                            <label class="form-check-label" for="genPengajuanT">Tidak</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tahun</label>
                        <input class="form-control" type="text" readonly value="<?= $tahun; ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="edit" class="btn btn-primary">Generate Sekarang</button>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="editAksesAll" tabindex="-1" aria-labelledby="editAksesAllLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="editAksesAllLabel">Edit Akses Semua Lembaga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/editAksesAll'); ?>" method="post">
                <div class="modal-body text-start">
                    <div class="alert alert-warning py-2 mb-3">
                        <small><i class="bx bx-warning"></i> Perhatian: Fitur ini akan mengubah hak akses untuk <strong>seluruh lembaga</strong> pada tahun <?= $tahun; ?>.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold d-block">Akses Login <span class="text-danger">*</span></label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="login" id="allLoginY" value="Y" checked required>
                            <label class="form-check-label" for="allLoginY">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="login" id="allLoginT" value="T">
                            <label class="form-check-label" for="allLoginT">Tidak</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold d-block">Disposisi <span class="text-danger">*</span></label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="disp" id="allDispY" value="Y" checked required>
                            <label class="form-check-label" for="allDispY">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="disp" id="allDispT" value="T">
                            <label class="form-check-label" for="allDispT">Tidak</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold d-block">Pengajuan <span class="text-danger">*</span></label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pengajuan" id="allPengajuanY" value="Y" checked required>
                            <label class="form-check-label" for="allPengajuanY">Ya</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pengajuan" id="allPengajuanT" value="T">
                            <label class="form-check-label" for="allPengajuanT">Tidak</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tahun</label>
                        <input class="form-control" type="text" readonly value="<?= $tahun; ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="edit" class="btn btn-primary">Simpan Semua Perubahan</button>
                </div>
            </form>

        </div>
    </div>
</div>