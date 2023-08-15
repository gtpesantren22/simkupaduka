<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Pengeluaran Rutin</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-hdd"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Input Rekap</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="card radius-10 bg-danger bg-gradient">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <p class="mb-0 text-white">TOTAL PENGELURAN RUTIN</p>
                                                <h4 class="my-1 text-white"><?= rupiah($sumData->jml); ?></h4>
                                            </div>
                                            <div class="text-white ms-auto font-35"><i class='bx bx-money'></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card radius-10 bg-success bg-gradient">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="text-white ms-auto font-35">
                                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPes"><i class="bx bx-plus-circle"></i>
                                                    Tambah</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Langganan</th>
                                        <th>Lembaga | Bidang</th>
                                        <th>Tanggal</th>
                                        <th>Nominal</th>
                                        <th>Ket</th>
                                        <th>Act</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data as $a) : ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $a->langganan ?></td>
                                            <td><span class="badge bg-primary"><?= $a->nmLembaga ?></span><span class="badge bg-info"><?= $a->nmBidang ?></span></td>
                                            <td><?= $a->tanggal ?></td>
                                            <td><?= rupiah($a->nominal) ?></td>
                                            <td><?= $a->ket ?></td>
                                            <td>
                                                <a href="<?= 'delOutRutin/' . $a->id_pengeluaran_rutin; ?>" class="btn btn-danger btn-sm tombol-hapus"><i class="bx bx-trash"></i></a>
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

<div class="modal fade" id="addPes" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Lembaga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('kasir/saveOutRutin'); ?>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="">Lembaga</label>
                    <select name="lembaga" class="form-select" required>
                        <option value=""> pilih lembaga</option>
                        <?php foreach ($lembaga as $lembaga) : ?>
                            <option value="<?= $lembaga->kode ?>"><?= $lembaga->nama ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group mb-2">
                    <label for="">Bidang</label>
                    <select name="bidang" class="form-select" required>
                        <option value=""> pilih bidang</option>
                        <?php foreach ($bidang as $bidang) : ?>
                            <option value="<?= $bidang->kode ?>"><?= $bidang->nama ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group mb-2">
                    <label for="">Langganan</label>
                    <select name="langganan" class="form-select" required>
                        <option value=""> pilih lembaga</option>
                        <option value="LISTRIK"> LISTRIK</option>
                        <option value="INTERNET"> INTERNET/WIFI</option>
                        <option value="HONOR"> HONOR</option>
                    </select>
                </div>
                <div class="form-group mb-2">
                    <label for="">Tanggal</label>
                    <input type="text" name="tanggal" id="date" class="form-control" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">Nominal</label>
                    <input type="text" name="nominal" class="form-control uang" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">Ket</label>
                    <input type="text" name="ket" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Simpan Data</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>