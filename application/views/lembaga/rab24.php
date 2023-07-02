<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<style>
    .wrap-text {
        white-space: normal;
        word-wrap: break-word;
    }
</style>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">RAB 2023/2024</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-message-detail"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">RAB</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <!-- <div class="col-12 col-lg-6">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Program</th>
                                        <th>Kegiatan</th>
                                        <th>Indikator</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($dppk as $a) : ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $a->id_dppk; ?></td>
                                            <td><?= $a->program; ?></td>
                                            <td><?= $a->kegiatan; ?></td>
                                            <td><?= $a->indikator; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="col-12 col-lg-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="alert border-0 border-start border-5 border-success fade show py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="font-35 text-success"><i class='bx bxs-check-circle'></i></div>
                                        <div class="ms-3">
                                            <h6 class="mb-0 text-success"><?= rupiah($lembaga->pagu) ?></h6>
                                            <div>Pagu Anggaran</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="alert border-0 border-start border-5 border-danger fade show py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="font-35 text-danger"><i class='bx bx-info-square'></i></div>
                                        <div class="ms-3">
                                            <h6 class="mb-0 text-danger"><?= rupiah($rab24Total->row('jml')) ?></h6>
                                            <div>Total RAB Baru</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-md-12">
                                <form action="<?= base_url('lembaga/addRab24'); ?>" method="post">
                                    <input type="hidden" name="pagu" value="<?= $lembaga->pagu ?>">
                                    <div class="modal-body">
                                        <div class="row form-group">
                                            <label for="tahun" class="col-form-label col-md-4 col-sm-4 label-align">Lembaga <span class="required">*</span></label>
                                            <div class="col-md-8 col-sm-8 ">
                                                <input type="text" id="first-name" name="lembaga" disabled class="form-control" value="<?= $lembaga->kode . '. ' . $lembaga->nama ?>">
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="first-name">DPPK <span class="required">*</span>
                                            </label>
                                            <div class="col-md-8 col-sm-8 ">
                                                <select name="dppk" class="form-control" id="" required>
                                                    <option value=""> -pilih program- </option>
                                                    <?php
                                                    foreach ($dppk as $a2) { ?>
                                                        <option value="<?= $a2->id_dppk ?>"><?= $a2->id_dppk ?>. <?= $a2->program ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="first-name">Bidang/bagian <span class="required">*</span>
                                            </label>
                                            <div class="col-md-8 col-sm-8 ">
                                                <select name="bidang" class="form-control" id="" required>
                                                    <option value=""> -pilih bidang- </option>
                                                    <?php
                                                    foreach ($bidang as $a2) { ?>
                                                        <option value="<?= $a2->kode ?>"><?= $a2->kode ?>. <?= $a2->nama ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label for="tahun" class="col-form-label col-md-4 col-sm-4 label-align">Pilih Jns Belanja
                                                <span class="required">*</span></label>
                                            <div class="col-md-8 col-sm-8 ">
                                                <select name="jenis" id="" required class="form-control">
                                                    <option value=""> -- pilih jenis -- </option>
                                                    <option value="A1"> A1. Belanja Barang - Tunai </option>
                                                    <option value="A2"> A2. Belanja Barang - Non Tunai </option>
                                                    <option value="B1"> B1. Belanja Jasa - Tunai </option>
                                                    <option value="B2"> B2. Belanja Jasa - Non Tunai </option>
                                                    <option value="C1"> C1. Belanja Sarpras - Tunai </option>
                                                    <option value="C2"> C2. Belanja Sarpras - Non Tunai </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="first-name">Nama
                                                Brg/Kegiatan <span class="required">*</span>
                                            </label>
                                            <div class="col-md-8 col-sm-8 ">
                                                <input type="text" id="first-name" name="nama" required="required" class="form-control ">
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="last-name">Rencana Waktu <span class="required">*</span>
                                            </label>
                                            <div class="col-md-8 col-sm-8 ">
                                                <select name="rencana" id="" required class="form-control">
                                                    <option value=""> -- pilih waktu -- </option>
                                                    <option value="Semester 1"> Semester 1 </option>
                                                    <option value="Semester 2"> Semester 2 </option>
                                                    <option value="Semester 1&2"> Semester 1&2 </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label for="middle-name" class="col-form-label col-md-4 col-sm-4 label-align">QTY/Satuan <span class="required">*</span></label>
                                            <div class="col-md-4 col-sm-8 ">
                                                <input id="middle-name" class="form-control" type="number" name="qty" required>
                                            </div>
                                            <div class="col-md-4 col-sm-8 ">
                                                <input id="middle-name" class="form-control" type="text" name="satuan" required placeholder="ex : rim,pack,pcs,dll">
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="col-form-label col-md-4 col-sm-4 label-align" for="first-name">Harga Satuan <span class="required">*</span>
                                            </label>
                                            <div class="col-md-8 col-sm-8 ">
                                                <input type="text" class="form-control uang" id="" name="harga_satuan" required>
                                            </div>

                                        </div>
                                        <div class="row form-group">
                                            <label for="tahun" class="col-form-label col-md-4 col-sm-4 label-align">Tahun Ajaran<span class="required">*</span></label>
                                            <div class="col-md-8 col-sm-8 ">
                                                <input type="text" name="tahun" required class="form-control" value="<?= $tahun; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="save" class="btn btn-success btn-sm">Simpan data</button>
                                    </div>
                                </form>
                            </div> -->
                            <div class="col-md-6">

                                <button class="btn btn-sm btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#tambah_bos"><i class="bx bx-plus"></i>Input RAB</button>
                                <button class="btn btn-success btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bx bx-cloud-upload"></i>Upload RAB</button>
                                <a href="<?= base_url('lembaga/kosongiRab') ?>" value="Data RAB ini akan dikosongi/dihapus seluruhnya" class="btn btn-danger btn-sm mb-1 tbl-confirm"><i class="bx bx-trash-alt"></i>Kosongi</a>
                                <a href="<?= base_url('lembaga/ajukanRab24') ?>" value="Data RAB akan diajukan ke Bendahara dan tidak ada perubahan lagi" class="btn btn-warning btn-sm mb-1 tbl-confirm"><i class="bx bx-up-arrow-circle"></i>Ajukan ke Bendahara</a>

                                <button class="btn btn-info btn-sm mb-1" id="button_find2" data-bs-toggle="modal" data-bs-target="#addLembaga"><i class="bx bx-list-ul"></i>Daftar DPPK</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="" class="table table-bordered mb-0 table-sm" style="width:100%">
                                <thead>
                                    <tr>
                                        <!-- <th>No</th> -->
                                        <th>Program</th>
                                        <th>Kode RAB</th>
                                        <th>Nama Barang</th>
                                        <th>QTY</th>
                                        <th>Hrg Satuan</th>
                                        <th>Total</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rab as $kodePak => $list) : ?>
                                        <?php foreach ($list as $item) : ?>
                                            <?php $no = 1; ?>
                                            <tr>
                                                <!-- <td><?= $no++ ?></td> -->
                                                <?php if ($item === reset($list)) : ?>
                                                    <td class="wrap-text" rowspan="<?= count($list); ?>">
                                                        <?= '#' . $kodePak . ' - ' . $item->nama_dppk; ?><br>
                                                        <b>Jml Item : <?= count($list) ?></b><br>
                                                        < <!-- <a class="tbl-confirm" value="Kode RAB akan direalis secara otomatis oleh sistem" href="<?= base_url('lembaga/realisKode/' . $kodePak) ?>">Realis Kode Item RAB</a> -->

                                                            <a href="#" class="getDataLink" data-kode_pak="<?= $kodePak ?>">Realis Kode Item RAB</a>

                                                    </td>
                                                <?php endif; ?>
                                                <td><?= $item->kode ?></td>
                                                <td><?= $item->nama ?></td>
                                                <td><?= $item->qty . ' ' . $item->satuan ?></td>
                                                <td><?= rupiah($item->harga_satuan) ?></td>
                                                <td><?= rupiah($item->total) ?></td>
                                                <!-- <td><?= round($rls->vol / $item->qty * 100, 1); ?>%</td> -->
                                                <td>
                                                    <?php if ($cekData < 1) { ?>
                                                        <a class="tombol-hapus" href="<?= base_url('lembaga/delRabSm24/' .  $item->id_rab); ?>"><span class="text-danger"><i class="bx bx-trash"></i></span></a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
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
<!--end page wrapper -->

<div class="modal fade" id="addLembaga" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">List DPPK Lembaga Saya</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="display_results2"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <!-- <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Simpan Data</button> -->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Upload RAB Lembaga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open_multipart('lembaga/process_upload'); ?>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="">Pilih Berkas</label>
                    <input type="file" name="uploadFile" class="form-control" required>
                    <small class="text-danger">* File yang diupload tidak merubah apapun dari tempalte yang di
                        download</small>
                </div>
                <a href="<?= base_url('lembaga/downRabTmp'); ?>"><i class="bx bx-download"></i> Donload Template Format
                    Upload RAB Disini!</a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Upload RAB</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="tambah_bos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Buat RAB Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('lembaga/addRab24'); ?>" method="post">
                <input type="hidden" name="pagu" value="<?= $lembaga->pagu ?>">
                <div class="modal-body">
                    <div class="row form-group">
                        <label for="tahun" class="col-form-label col-md-3 col-sm-3 label-align">Lembaga <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="first-name" name="lembaga" disabled class="form-control" value="<?= $lembaga->kode . '. ' . $lembaga->nama ?>">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">DPPK <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <select name="dppk" class="form-control" id="" required>
                                <option value=""> -pilih program- </option>
                                <?php
                                foreach ($dppk as $a2) { ?>
                                    <option value="<?= $a2->id_dppk ?>"><?= $a2->id_dppk ?>. <?= $a2->program ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Bidang/bagian <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <select name="bidang" class="form-control" id="" required>
                                <option value=""> -pilih bidang- </option>
                                <?php
                                foreach ($bidang as $a2) { ?>
                                    <option value="<?= $a2->kode ?>"><?= $a2->kode ?>. <?= $a2->nama ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label for="tahun" class="col-form-label col-md-3 col-sm-3 label-align">Pilih Jenis Belanja
                            <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 ">
                            <select name="jenis" id="" required class="form-control">
                                <option value=""> -- pilih jenis -- </option>
                                <option value="A1"> A1. Belanja Barang - Tunai </option>
                                <option value="A2"> A2. Belanja Barang - Non Tunai </option>
                                <option value="B1"> B1. Belanja Jasa - Tunai </option>
                                <option value="B2"> B2. Belanja Jasa - Non Tunai </option>
                                <option value="C1"> C1. Belanja Sarpras - Tunai </option>
                                <option value="C2"> C2. Belanja Sarpras - Non Tunai </option>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nama
                            Barang/Kegiatan <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="first-name" name="nama" required="required" class="form-control ">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Rencana Waktu <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <select name="rencana" id="" required class="form-control">
                                <option value=""> -- pilih waktu -- </option>
                                <option value="1"> Januari </option>
                                <option value="2"> Februari </option>
                                <option value="3"> Maret </option>
                                <option value="4"> April </option>
                                <option value="5"> Mei </option>
                                <option value="6"> Juni </option>
                                <option value="7"> Juli </option>
                                <option value="8"> Agustus </option>
                                <option value="9"> September </option>
                                <option value="10"> Oktober </option>
                                <option value="11"> November </option>
                                <option value="12"> Desember </option>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">QTY/Satuan <span class="required">*</span></label>
                        <div class="col-md-3 col-sm-6 ">
                            <input id="middle-name" class="form-control" type="number" name="qty" required>
                        </div>
                        <div class="col-md-3 col-sm-6 ">
                            <input id="middle-name" class="form-control" type="text" name="satuan" required placeholder="ex : rim,pack,pcs,dll">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Harga Satuan <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" class="form-control uang" id="" name="harga_satuan" required>
                        </div>

                    </div>
                    <div class="row form-group">
                        <label for="tahun" class="col-form-label col-md-3 col-sm-3 label-align">Tahun Ajaran<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-5 ">
                            <input type="text" name="tahun" required class="form-control" value="<?= $tahun; ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="save" class="btn btn-success">Simpan data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.getDataLink').on('click', function(e) {
            e.preventDefault(); // Mencegah aksi bawaan tautan

            var kode_pak = $(this).data('kode_pak');

            $.ajax({
                url: '<?php echo site_url("lembaga/realisKode"); ?>', // Ganti "controller/method" sesuai dengan URL controller Anda
                type: 'POST',
                data: {
                    kode_pak: kode_pak,
                },
                success: function(response) {
                    // Tampilkan data dalam tabel atau lakukan aksi lain sesuai kebutuhan
                    location.reload();

                    // console.log('Data berhasil disimpan');
                }
            });
        });

    });
</script>