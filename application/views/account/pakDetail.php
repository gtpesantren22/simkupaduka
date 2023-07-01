<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Daftar PAK</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-folder-open"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Rencana Belanja</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="<?= base_url('account/pak'); ?>" class="btn btn-light btn-sm"><i class="bx bx-subdirectory-left"></i>
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
                                        <p class="mb-0 text-white">Nominal RAB yang asal</p>
                                        <h4 class="my-1 text-white"><?= rupiah($ttl->total); ?></h4>
                                        <p class="mb-0 font-13 text-white">Total RAB yang awal dari lembaga terkait</p>
                                    </div>
                                    <div id="chart3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <?php if ($data->status === 'proses') { ?>
                            <a href="<?= base_url('account/setujuiPAK/' . $data->kode_pak) ?>" class="btn btn-success btn-sm mt-2 tbl-confirm" value="PAK akan disetujui dan akan dilanjutkan proses sinkronisasi"><i class="bx bx-check-circle"></i>Setujui PAK</a>
                            <button data-bs-toggle="modal" data-bs-target="#ex_tolak" class="btn btn-danger btn-sm mt-2"><i class="bx bx-x-circle"></i>Tolak PAK</button>
                        <?php } else { ?>
                            PAK masih dalam proses input
                        <?php } ?>
                    </div>
                </div>
                <div class="card radius-10">
                    <div class="card-body">
                        <div>
                            <h5 class="card-title">RAB yang di PAK</h5>
                        </div>
                        <hr />
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Barang/Kegiatan</th>
                                        <th>QTY</th>
                                        <th>Harga Satuan</th>
                                        <th>Total</th>
                                        <th>Ket</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($rpak as $r1) : ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $r1->kode_rab ?></td>
                                            <td><?= $r1->nm ?></td>
                                            <td><?= $r1->qty ?></td>
                                            <td><?= rupiah($r1->harga_satuan) ?></td>
                                            <td><?= rupiah($r1->total) ?></td>
                                            <td class="text-success">
                                                <?= $r1->ket == 'hapus' ? "<span class='badge bg-danger btn-rounded'>hapus</span>" : "<span class='badge bg-success btn-rounded'>edit</span>" ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5">TOTAL</th>
                                        <th colspan="2"><?= rupiah($rpakSum->total); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card radius-10">
                    <div class="card-body">
                        <div>
                            <h5 class="card-title">RAB Baru yang diajukan</h5>
                        </div>
                        <hr />
                        <div class="table-responsive">
                            <table id="example3" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Barang/Kegiatan</th>
                                        <th>QTY</th>
                                        <th>Satuan</th>
                                        <th>Harga Satuan</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($rabnew as $r2) : ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $r2->kode ?></td>
                                            <td><?= $r2->nama ?></td>
                                            <td><?= $r2->qty ?></td>
                                            <td><?= $r2->satuan ?></td>
                                            <td><?= rupiah($r2->harga_satuan) ?></td>
                                            <td><?= rupiah($r2->total) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="6">TOTAL</th>
                                        <th><?= rupiah($rabnewSum->total); ?></th>
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

<div class="modal fade" id="ex_tolak" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak PAK</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('account/tolakPAK'); ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="kode" value="<?= $data->kode_pak; ?>">
                    <div class="form-group mb-2">
                        <label class="" for="first-name">Nama Lembaga <span class="required">*</span>
                        </label>
                        <div class="">
                            <input type="text" id="" name="lembaga" required="required" value="<?= $lembaga->nama ?>" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label class="" for="first-name">Tanggal Penolakan
                            <span class="required">*</span>
                        </label>
                        <div class="">
                            <input type="text" id="date-time" name="tgl" required="required" class="form-control">
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label class="" for="first-name">Catatan <span class="required">*</span>
                        </label>
                        <div class="">
                            <textarea name="pesan" required="required" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label class="" for="first-name">Verifikator <span class="required">*</span>
                        </label>
                        <div class="">
                            <input type="text" id="" name="user" required="required" value="<?= $user->nama ?>" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
                </div>
            </form>
        </div>
    </div>
</div>