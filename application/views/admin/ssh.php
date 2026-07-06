<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Standard Satuan Harga (SSH)</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-folder-open"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Master Data</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="<?= base_url('admin/sshAdd'); ?>" class="btn btn-success btn-sm"><i class="bx bx-plus-circle"></i> Tambah SSH</a>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
        
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Satuan</th>
                                        <th>Harga</th>
                                        <th>Kategori</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach ($ssh as $row): ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= htmlspecialchars($row->kode); ?></td>
                                            <td><?= htmlspecialchars($row->nama); ?></td>
                                            <td><?= htmlspecialchars($row->satuan); ?></td>
                                            <td>Rp. <?= number_format($row->harga, 0, ',', '.'); ?></td>
                                            <td><?= htmlspecialchars($row->nama_kategori ? $row->nama_kategori : $row->kategori); ?></td>
                                            <td><?= htmlspecialchars($row->ket); ?></td>
                                            <td>
                                                <a href="<?= base_url('admin/sshEdit/' . $row->kode); ?>" class="btn btn-sm btn-warning text-white"><i class="bx bx-edit"></i> Edit</a>
                                                <a href="<?= base_url('admin/delSsh/' . $row->kode); ?>" class="btn btn-sm btn-danger tbl-confirm" value="Hapus data SSH <?= htmlspecialchars($row->nama); ?>?" onclick="return confirm('Yakin hapus?');"><i class="bx bx-trash"></i> Delete</a>
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
