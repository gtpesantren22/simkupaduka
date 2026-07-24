<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Daftar RAB <?= htmlspecialchars($tahun) ?></div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-folder-open"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Rencana Belanja</li>
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
                            <table id="example" class="table table-striped table-bordered align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Lembaga</th>
                                        <th>Pagu Anggaran</th>
                                        <th>Tahun Pelajaran</th>
                                        <th>Status</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $total_pagu = 0;
                                    foreach ($data as $a) : 
                                        $total_pagu += (float)$a->pagu;
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td class="font-monospace fw-bold"><?= htmlspecialchars($a->lembaga); ?></td>
                                            <td><?= htmlspecialchars($a->nama); ?></td>
                                            <td class="fw-bold"><?= rupiah($a->pagu); ?></td>
                                            <td><?= htmlspecialchars($a->tahun); ?></td>
                                            <td>
                                                <?php
                                                $status = $a->status;
                                                if (empty($status)) {
                                                    echo '<span class="badge bg-secondary">Belum Upload</span>';
                                                } elseif ($status === 'proses') {
                                                    echo '<span class="badge bg-warning text-dark">Proses Pengajuan</span>';
                                                } elseif ($status === 'disetujui') {
                                                    echo '<span class="badge bg-success">Disetujui Bendahara</span>';
                                                } elseif ($status === 'selesai') {
                                                    echo '<span class="badge bg-primary">Selesai Sinkron</span>';
                                                } else {
                                                    echo '<span class="badge bg-dark">' . htmlspecialchars($status) . '</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('admin/rab24detail/' . $a->lembaga) ?>"><button class="btn btn-primary btn-sm"><i class="bx bx-search"></i>
                                                         Cek List RAB</button></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <th colspan="3" class="text-center">Total Pagu Anggaran</th>
                                        <th class="fw-bold text-danger"><?= rupiah($total_pagu); ?></th>
                                        <th colspan="3"></th>
                                    </tr>
                                </tfoot>
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