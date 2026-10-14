<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-md-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Pengaturan</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('lembaga'); ?>"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Pengaturan Akun & Lembaga</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <!-- Card Pengaturan Akun & Login -->
            <div class="col-12 col-lg-6 mb-3">
                <div class="card radius-10 shadow-sm border-0 h-100">
                    <div class="card-header bg-transparent border-bottom py-3">
                        <div class="d-flex align-items-center">
                            <div>
                                <h5 class="mb-0 text-primary"><i class="bx bx-user-circle me-2"></i>Data Akun & Login</h5>
                                <small class="text-muted">Perbarui nama pengguna, username, dan password login</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <!-- Profile preview & photo trigger -->
                        <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3">
                            <div class="position-relative me-3">
                                <?php
                                $avatarSrc = base_url('vertical/assets/uploads/profile/user-avatar.png');
                                if (!empty($user->foto) && file_exists(FCPATH . 'vertical/assets/uploads/profile/' . $user->foto)) {
                                    $avatarSrc = base_url('vertical/assets/uploads/profile/' . $user->foto);
                                }
                                ?>
                                <img src="<?= $avatarSrc; ?>" class="rounded-circle shadow-sm" width="70" height="70" style="object-fit: cover;" alt="Foto Profil">
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold"><?= htmlspecialchars($user->nama); ?></h6>
                                <p class="text-muted mb-1 small"><?= htmlspecialchars($user->username); ?> &bull; <span class="badge bg-primary">Operator <?= ucfirst($user->level); ?></span></p>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalFoto">
                                    <i class="bx bx-camera me-1"></i>Ganti Foto Profil
                                </button>
                            </div>
                        </div>

                        <form action="<?= base_url('lembaga/updateAkun') ?>" method="post">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent"><i class="bx bx-user"></i></span>
                                    <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($user->nama); ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Username <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent"><i class="bx bx-at"></i></span>
                                    <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($user->username); ?>" required>
                                </div>
                                <small class="text-muted">Digunakan saat login ke dalam aplikasi</small>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 mb-2 mb-md-0">
                                    <label class="form-label fw-bold text-muted">Role / Level</label>
                                    <input type="text" class="form-control bg-light" disabled value="Operator <?= ucfirst($user->level); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted">Lembaga</label>
                                    <input type="text" class="form-control bg-light" disabled value="<?= $lembaga ? htmlspecialchars($lembaga->nama) : '-'; ?>">
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="alert alert-info border-0 p-3 mb-3 rounded-3">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-info-circle text-info font-24 me-2"></i>
                                    <small class="text-dark">Kosongkan kolom password di bawah ini jika <strong>tidak ingin</strong> mengganti password login.</small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Password Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent"><i class="bx bx-lock"></i></span>
                                    <input type="password" class="form-control" name="newpass" id="newpass" placeholder="Password baru (minimal 5 karakter)">
                                    <a href="javascript:;" class="input-group-text bg-transparent text-secondary" onclick="togglePass('newpass', this)"><i class="bx bx-hide font-18"></i></a>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Konfirmasi Password Baru</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent"><i class="bx bx-lock-alt"></i></span>
                                    <input type="password" class="form-control" name="confir_newpass" id="confir_newpass" placeholder="Ketik ulang password baru">
                                    <a href="javascript:;" class="input-group-text bg-transparent text-secondary" onclick="togglePass('confir_newpass', this)"><i class="bx bx-hide font-18"></i></a>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i>Simpan Perubahan Akun</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Card Data Lembaga -->
            <div class="col-12 col-lg-6 mb-3">
                <div class="card radius-10 shadow-sm border-0 h-100">
                    <div class="card-header bg-transparent border-bottom py-3">
                        <div class="d-flex align-items-center">
                            <div>
                                <h5 class="mb-0 text-info"><i class="bx bx-buildings me-2"></i>Data Informasi Lembaga</h5>
                                <small class="text-muted">Informasi penanggung jawab dan kontak operasional lembaga</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form method="post" action="<?= base_url('lembaga/updateLembaga') ?>">
                            <div class="row mb-3">
                                <div class="col-md-8 mb-2 mb-md-0">
                                    <label class="form-label fw-bold text-muted">Nama Lembaga</label>
                                    <input type="text" class="form-control bg-light" value="<?= $lembaga ? htmlspecialchars($lembaga->nama) : ''; ?>" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-muted">Kode Lembaga</label>
                                    <input type="text" class="form-control bg-light text-center fw-bold" value="<?= $lembaga ? htmlspecialchars($lembaga->kode) : ''; ?>" readonly>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Penanggung Jawab (PJ)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent"><i class="bx bx-user-check"></i></span>
                                    <input type="text" class="form-control" name="pj" placeholder="Nama PJ Lembaga" value="<?= $lembaga ? htmlspecialchars($lembaga->pj) : ''; ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">No. WhatsApp / HP (PJ) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent"><i class="bx bxl-whatsapp text-success"></i></span>
                                    <input type="text" class="form-control" name="hp" placeholder="Contoh: 081234567890" required value="<?= $lembaga ? htmlspecialchars($lembaga->hp) : ''; ?>">
                                </div>
                                <small class="text-muted">Nomor kontak penanggung jawab untuk koordinasi</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">No. WhatsApp / HP Kepala Lembaga <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent"><i class="bx bx-phone-call text-primary"></i></span>
                                    <input type="text" class="form-control" name="hp_kep" placeholder="Contoh: 081234567890" required value="<?= $lembaga ? htmlspecialchars($lembaga->hp_kep) : ''; ?>">
                                </div>
                                <small class="text-muted">Nomor kontak Kepala Lembaga</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Waktu Belanja</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent"><i class="bx bx-time-five"></i></span>
                                    <input type="text" class="form-control" name="waktu" placeholder="Jadwal waktu belanja" value="<?= $lembaga ? htmlspecialchars($lembaga->waktu) : ''; ?>">
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-info text-white"><i class="bx bx-save me-1"></i>Simpan Data Lembaga</button>
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

<!-- Modal Unggah Foto Profil -->
<div class="modal fade" id="modalFoto" tabindex="-1" aria-labelledby="modalFotoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFotoLabel"><i class="bx bx-camera me-1"></i>Unggah Foto Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open_multipart('lembaga/uploadFoto') ?>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Pilih Berkas Foto</label>
                    <input type="file" name="file" class="form-control" accept="image/png, image/jpeg, image/jpg" required>
                </div>
                <div class="alert alert-warning border-0 p-3 mb-0 rounded-3">
                    <ul class="mb-0 ps-3 small text-dark">
                        <li>Format file yang diizinkan: <strong>JPG, JPEG, PNG</strong></li>
                        <li>Ukuran file maksimal: <strong>3 MB</strong></li>
                        <li>Disarankan foto berbentuk <strong>persegi (1:1)</strong> agar hasil avatar simetris</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="bx bx-upload me-1"></i>Upload Foto</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<script>
    function togglePass(inputId, btn) {
        var input = document.getElementById(inputId);
        var icon = btn.querySelector('i');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('bx-hide');
            icon.classList.add('bx-show');
        } else {
            input.type = "password";
            icon.classList.remove('bx-show');
            icon.classList.add('bx-hide');
        }
    }
</script>