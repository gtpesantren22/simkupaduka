<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data SPJ Pengajuan</div>
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
                    <div class="card-header">
                        <!-- <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambah_bos<?= $spj->id_spj ?>"><i class="bx bx-check"></i>Setujui</button> -->
                        <!-- <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#tolak_bos<?= $spj->id_spj ?>"><i class="bx bx-no-entry"></i>Tolak</button> -->

                        <button class="btn btn-warning btn-sm" onclick="window.location='<?= base_url('lembaga/spjSs') ?>'">Kembali</button>
                    </div>
                    <div class="card-body">
                        <iframe src="<?= base_url('vertical/assets/uploads/' . $spj->file_spj) ?>" style="width: 100%; height: 700px;"></iframe>
                        <!-- <iframe src="<?= base_url('../simkupaduka-ok/institution/spj_file/05.0909.3.2022.pdf') ?>" style="width: 100%; height: 500px;"></iframe> -->
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<!--end page wrapper -->