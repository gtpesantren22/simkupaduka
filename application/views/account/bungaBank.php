<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Pemasukan Bunga Bank</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-money"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Pemasukan</li>
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
                                <div class="card radius-10 bg-success bg-gradient">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <p class="mb-0 text-white">TOTAL PEMASUKAN DARI BUNGA BANK</p>
                                                <h4 class="my-1 text-white"><?= rupiah($sum->nominal); ?></h4>
                                            </div>
                                            <div class="text-white ms-auto font-35"><i class='bx bx-money'></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card radius-10 bg-primary bg-gradient">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="text-white ms-auto font-35">
                                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPes"><i class="bx bx-plus-circle"></i>
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
                                        <th>Bulan</th>
                                        <th>Tanggal Setor</th>
                                        <th>Nominal</th>
                                        <th>Uraian</th>
                                        <th>Tahun</th>
                                        <th>Act</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data as $a) : ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= bulan($a->bulan) ?></td>
                                            <td><?= $a->tanggal ?></td>
                                            <td><?= rupiah($a->nominal) ?></td>
                                            <td><?= $a->ket ?></td>
                                            <td><?= $a->tahun ?></td>
                                            <td>
                                                <button class="btn btn-warning btn-sm btn-edit" data-id="<?= $a->id_bunga ?>" data-tanggal="<?= $a->tanggal ?>" data-nominal="<?= $a->nominal ?>" data-ket="<?= $a->ket ?>" data-bulan="<?= $a->bulan ?>" data-tahun="<?= $a->tahun ?>"><i class="bx bx-edit"></i> Edit</button>
                                                <a href="<?= 'delBunga/' . $a->id_bunga; ?>" class="btn btn-danger btn-sm tombol-hapus"><i class="bx bx-trash"></i>
                                                    Del</a>
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
                <h5 class="modal-title" id="exampleModalLabel">Tambah Pemasukan Bunga Bank</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('account/bungaAdd'); ?>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="">Bulan</label>
                    <select name="bulan" class="form-control" id="" required>
                        <option value=""> -pilih bulan- </option>
                        <?php
                        for ($b = 1; $b <= 12; $b++) { ?>
                            <option value="<?= $b ?>"><?= bulan($b) ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group mb-2">
                    <label for="">Uraian</label>
                    <input type="text" name="ket" class="form-control" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">Nominal</label>
                    <input type="text" name="nominal" class="form-control uang" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">Tanggal Setor</label>
                    <input type="text" name="tanggal" id="" class="form-control date" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">Tahun</label>
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
                <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Simpan Data</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Pemasukan Bunga Bank</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('account/bungaEdit'); ?>
            <input type="hidden" name="id" id="id">
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="">Bulan</label>
                    <select name="bulan" id="bulan" class="form-control" required>
                        <option value=""> -pilih bulan- </option>
                        <?php
                        for ($b = 1; $b <= 12; $b++) { ?>
                            <option value="<?= $b ?>"><?= bulan($b) ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group mb-2">
                    <label for="">Uraian</label>
                    <input type="text" name="ket" id="ket" class="form-control" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">Nominal</label>
                    <input type="text" name="nominal" id="nominal" class="form-control uang" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">Tanggal Setor</label>
                    <input type="text" name="tanggal" id="tanggal" class="form-control date" required>
                </div>
                <div class="form-group mb-2">
                    <label for="">Tahun</label>
                    <select name="tahun" id="tahun" class="form-control" required>
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
                <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Simpan Data</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script src="<?= base_url('vertical/'); ?>assets/js/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('.btn-edit').on('click', function() {
            var id = $(this).data('id');
            var tanggal = $(this).data('tanggal');
            var nominal = $(this).data('nominal');
            var ket = $(this).data('ket');
            var bulan = $(this).data('bulan');
            var tahun = $(this).data('tahun');

            $('#id').val(id);
            $('#tanggal').val(tanggal);
            $('#nominal').val(nominal);
            $('#ket').val(ket);
            $('#bulan').val(bulan);
            $('#tahun').val(tahun);
            $('#modalEdit').modal('show');
        });
    });
</script>