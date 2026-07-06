<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Cetak Nota</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-printer"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">List Pengajuan</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card radius-10 border-top border-0 border-3 border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div>
                                <h6 class="mb-0">Daftar Pengajuan Anggaran yang Disetujui</h6>
                                <small class="text-muted">Hanya menampilkan pengajuan yang telah diverval oleh Bendahara dan Perencanaan</small>
                            </div>
                        </div>

                        <div class="table-responsive mt-3">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                        <th>No</th>
                                        <th>Kode Pengajuan</th>
                                        <th>Lembaga</th>
                                        <th>Bulan</th>
                                        <th>Tahun</th>
                                        <th>Verval Bendahara</th>
                                        <th>Persetujuan Perencanaan</th>
                                        <th>Status Cair</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data as $ls_jns) :
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><span class="font-monospace text-secondary fw-bold"><?= $ls_jns->kode_pengajuan; ?></span></td>
                                            <td><span class="fw-bold text-dark"><?= $ls_jns->nama_lembaga; ?></span></td>
                                            <td><?= $bulan[$ls_jns->bulan]; ?></td>
                                            <td><?= $ls_jns->tahun; ?></td>
                                            <td>
                                                <?= $ls_jns->verval == 1 ? "<span class='badge bg-success'><i class='bx bx-check'></i> sudah</span>" : "<span class='badge bg-danger'><i class='bx bx-no-entry'></i> belum</span>"; ?>
                                            </td>
                                            <td>
                                                <?= $ls_jns->apr == 1 ? "<span class='badge bg-success'><i class='bx bx-check'></i> sudah</span>" : "<span class='badge bg-danger'><i class='bx bx-no-entry'></i> belum</span>"; ?>
                                            </td>
                                            <td>
                                                <?= $ls_jns->cair == 1 ? "<span class='badge bg-success'><i class='bx bx-check'></i> dicairkan</span>" : "<span class='badge bg-warning'><i class='bx bx-time'></i> proses</span>"; ?>
                                            </td>
                                            <td class="text-center">
                                                <a href="<?= base_url('lembaga/cetakNotaDetail/' . $ls_jns->kode_pengajuan) ?>" class="btn btn-primary btn-sm px-3 radius-30">
                                                    <i class="bx bx-search"></i> Detail
                                                </a>
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
