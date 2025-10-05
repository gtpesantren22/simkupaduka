<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Pembayaran Tanggungan Santri</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-shopping-bag"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Tanggungan</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <button class="btn btn-primary float-right" data-bs-toggle="modal" data-bs-target="#uploadBp">Upload Pembayaran</button>
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
                                        <th>Nama</th>
                                        <th>JKL</th>
                                        <th>KelSas</th>
                                        <th>Tanggal Bayar</th>
                                        <th>Bulan</th>
                                        <th>Nominal</th>
                                        <th>Tahun Ajaran</th>
                                        <th>Penerima</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($rls as $ls_jns) { ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $ls_jns->nama; ?></td>
                                            <td><?= $ls_jns->jkl; ?></td>
                                            <td><?= $ls_jns->k_formal . ' - ' . $ls_jns->t_formal; ?></td>
                                            <td><?= $ls_jns->tgl; ?></td>
                                            <td><?= $bulan[$ls_jns->bulan]; ?></td>
                                            <td><?= rupiah($ls_jns->nominal); ?></td>
                                            <td><?= $ls_jns->tahun; ?></td>
                                            <td><?= $ls_jns->kasir; ?></td>
                                            <td>
                                                <a href="<?= base_url('kasir/delBayar/' . $ls_jns->id); ?>" class="tbl-confirm" value="Data ini akan dihapus dan akan menghapus data dekosan nya juga"><span class="btn btn-danger btn-sm">Del</span></a>
                                            </td>
                                        </tr>
                                        <!-- Modal -->

                                    <?php } ?>
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
<div class="modal fade" id="uploadBp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Upload Pembayaran BP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open_multipart('import/preview'); ?>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="">Pilih File</label>
                    <input type="file" name="file_excel" accept=".xls,.xlsx" class="form-control" required></input>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Upload</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>