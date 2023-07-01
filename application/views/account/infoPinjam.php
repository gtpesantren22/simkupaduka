<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Peminjam Dana</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-wallet"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Realisasi</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="<?= base_url('admin/pinjam'); ?>" class="btn btn-light btn-sm"><i class="bx bx-subdirectory-left"></i>
                        Kembali</a>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-2 row-cols-xxl-4">
                    <div class="col">
                        <div class="card radius-10 bg-gradient-ohhappiness">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-auto">
                                        <p class="mb-0 text-white">Nominal Peminjam</p>
                                        <h4 class="my-1 text-white"><?= rupiah($dataPinjam->nominal); ?></h4>
                                        <table style="color: white;">
                                            <tr>
                                                <td>Cicilan</td>
                                                <td>:</td>
                                                <td><?= rupiah($dataPinjam->nominal / $dataPinjam->jml_cicil) ?> (<?= $dataPinjam->jml_cicil ?> kali)</td>
                                            </tr>
                                            <tr>
                                                <td>Sudah Bayar</td>
                                                <td>:</td>
                                                <td><?= rupiah($sumCicil->jml) ?></td>
                                            </tr>
                                            <tr>
                                                <td>Sisa</td>
                                                <td>:</td>
                                                <td><?= rupiah($dataPinjam->nominal - $sumCicil->jml) ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div id="chart3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <h4>Input pembayaran cicilan</h3>
                            <?= form_open('admin/addCicil') ?>
                            <input type="hidden" name="kode_pinjam" value="<?= $dataPinjam->kode_pinjam ?>">
                            <div class="form-group mb-2">
                                <!-- <label for="">Tgl Setor</label> -->
                                <input type="text" class="form-control" id="date" name="tgl_setor" placeholder="Tanggal Setor" required>
                            </div>
                            <div class="form-group mb-2">
                                <!-- <label for="">Ket</label> -->
                                <input type="text" class="form-control" id="" name="ket" placeholder="Keterangan" required>
                            </div>
                            <div class="form-group mb-2">
                                <button class="btn btn-success btn-sm" type="submit"><i class="bx bx-save"></i>Simpan Data</button>
                            </div>
                            <?= form_close() ?>
                    </div>
                </div>
                <div class="card radius-10">
                    <div class="card-body">
                        <div>
                            <h5 class="card-title">History pembayaran cicilan</h5>
                        </div>
                        <hr />
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Pinjam</th>
                                        <th>Keterangan</th>
                                        <th>Tgl Setor</th>
                                        <th>Nominal</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($cicil as $r1) : ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $r1->kode_pinjam ?></td>
                                            <td><?= $r1->ket ?></td>
                                            <td><?= $r1->tgl_setor ?></td>
                                            <td><?= rupiah($r1->nominal) ?></td>
                                            <td>
                                                <a href="<?= base_url('admin/delCicil/' . $r1->id_cicilan) ?>" class="btn btn-danger btn-sm tombol-hapus">Del</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3">TOTAL</th>
                                        <th><?= rupiah($sumCicil->jml); ?></th>
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