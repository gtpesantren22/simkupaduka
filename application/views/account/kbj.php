<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">RAB Kebijakan</div>
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
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bx bx-plus-circle"></i> Input Data Baru</button>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row row-cols-1 row-cols-md-3 row-cols-xl-12">
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">LIMIT</p>
                                <h4 class="my-1 text-danger"><?= rupiah(50000000); ?></h4>
                                <p class="mb-0 font-13">Total limit RAB Kebijakan</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto"><i class='bx bxs-wallet'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Terpakai</p>
                                <h4 class="my-1 text-info"><?= rupiah($pakai->jml); ?></h4>
                                <p class="mb-0 font-13">Nominal Pemaikaian RAB KEbijakan</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto"><i class='bx bxs-cart'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Sisa Dana</p>
                                <h4 class="my-1 text-success"><?= rupiah(50000000 - $pakai->jml); ?></h4>
                                <p class="mb-0 font-13">Sisa Dana Kebijakan</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i class='bx bxs-bar-chart-alt-2'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->

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
                                        <th>Nama Lembaga</th>
                                        <th>Tanggal</th>
                                        <th>Jumlah</th>
                                        <th>Keterangan</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data as $a) : ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $a->kode_kbj ?></td>
                                            <td><?= $a->nama ?></td>
                                            <td><?= $a->tgl ?></td>
                                            <td><?= rupiah($a->nominal) ?></td>
                                            <td><?= $a->ket ?></td>
                                            <td>
                                                <a class="tombol-hapus" href="<?= 'delKbj/' . $a->id_kebijakan; ?>"><span class="bx bx-trash text-danger">Hapus</span></a>
                                                <!-- <a href="#" class="tbl-confirm" value="Isi dari text">Coba</a> -->
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

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Input Data RAB Kebijakan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open_multipart('account/saveKbj'); ?>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="">Lembaga</label>
                    <select name="lembaga" class="form-control" id="" required>
                        <option value=""> -pilih lembaga- </option>
                        <?php
                        foreach ($lembaga as $a) { ?>
                            <option value="<?= $a->kode ?>"><?= $a->kode ?>.
                                <?= $a->nama ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group mb-2">
                    <label for="">Bidang</label>
                    <select name="bidang" class="form-control" id="" required>
                        <option value=""> -pilih bidang- </option>
                        <?php
                        foreach ($bidang as $a) { ?>
                            <option value="<?= $a->kode ?>"><?= $a->kode ?>.
                                <?= $a->nama ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group mb-2">
                    <label for="tahun" class="">Pilih Jenis Belanja</label>
                    <select name="jenis" id="" required class="form-control">
                        <option value=""> -- pilih jenis -- </option>
                        <option value="A"> A. Belanja Barang </option>
                        <option value="B"> B. Langganan Daya dan Jasa </option>
                        <option value="C"> C. Belanja Kegiatan </option>
                        <option value="D"> D. Umum </option>
                    </select>
                </div>
                <div class="form-group mb-2">
                    <label for="tahun" class="">Nominal</label>
                    <input type="text" name="nominal" class="form-control uang" required>
                </div>
                <div class="form-group mb-2">
                    <label for="tahun" class="">Tanggal Bayar</label>
                    <input type="text" name="tgl" id="date" class="form-control" required>
                </div>
                <div class="form-group mb-2">
                    <label for="" class="">Penanggungjawab</label>
                    <input type="text" name="pj" class="form-control" required>
                </div>
                <div class="form-group mb-2">
                    <label for="" class="">Keterangan</label>
                    <textarea name="ket" class="form-control" required></textarea>
                </div>
                <div class="form-group mb-2">
                    <label for="">Tahan Pelajaran</label>
                    <select name="tahun" class="form-control" id="" required>
                        <option value=""> -pilih tahun- </option>
                        <?php
                        foreach ($tahunData as $a) { ?>
                            <option value="<?= $a->nama_tahun ?>"><?= $a->nama_tahun ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Simpan</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>