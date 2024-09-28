<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>
<?php

$total = $rabTotal->jml;
$pakai = $realTotal->jml;

$pesern = round(($pakai / $total) * 100, 0);
if ($pesern >= 0 && $pesern <= 25) {
    $bg = 'progress-bar-success';
} elseif ($pesern >= 26 && $pesern <= 50) {
    $bg = 'progress-bar-primary';
} elseif ($pesern >= 51 && $pesern <= 75) {
    $bg = 'progress-bar-warning';
} elseif ($pesern >= 76 && $pesern <= 100) {
    $bg = 'progress-bar-danger';
}

?>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">PAK Online</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-notepad"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">RAB</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-12">
                <?php if (date('Y-m-d') >= $tgl->login && date('Y-m-d') <= $tgl->disposisi) { ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="col">
                                        <div class="card radius-10 bg-success">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <p class="mb-0 text-white">Jumlah Anggaran RAB</p>
                                                        <h4 class="my-1 text-white"><?= rupiah($rabTotal->jml); ?></h4>
                                                        <p class="mb-0 font-13 text-white"><i class="bx bxs-up-arrow align-middle"></i> Total RAB dalam
                                                            setahun
                                                        </p>
                                                    </div>
                                                    <div class="widgets-icons bg-white text-success ms-auto"><i class="bx bxs-wallet"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <a href="<?= base_url('lembaga/ajukanPAK/' . $data->kode_pak); ?>" value="Pengajuan akan dilanjutkan kepada Sekretariat untuk proses Verifikasi" class="btn btn-success btn-sm tbl-confirm mb-2"><i class="bx bx-window-open"></i>Ajukan ke PAK</a>
                                    <br>
                                    Pemakaian
                                    <div class="progress active">
                                        <div class="progress-bar <?= $bg ?> progress-bar-striped" role="progressbar" aria-valuenow="<?= $pesern ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $pesern ?>%"><?= $pesern ?>%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example2" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Barang/Kegiatan</th>
                                            <th>Rencana</th>
                                            <th>QTY</th>
                                            <th>Harga Satuan</th>
                                            <th>Total</th>
                                            <th>Terpakai</th>
                                            <td>#</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($rab as $r1) :

                                            $kd = $r1->kode;
                                            $pakai = $this->db->query("SELECT IFNULL (SUM(nominal),0) AS jml, IFNULL (SUM(vol),0) AS qty FROM realis WHERE kode = '$kd' AND tahun = '$tahun' ")->row();
                                            $pakaiSm = $this->db->query("SELECT IFNULL (SUM(nominal),0) AS jml, IFNULL (SUM(vol),0) AS qty FROM real_sm WHERE kode = '$kd' AND tahun = '$tahun' ")->row();

                                            $sisa = $pakai->jml / $r1->total * 100;

                                        ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= $r1->kode ?></td>
                                                <td><?= $r1->nama ?></td>
                                                <td><?= bulan($r1->rencana) ?></td>
                                                <td><?= $r1->qty ?></td>
                                                <td><?= rupiah($r1->harga_satuan) ?></td>
                                                <td><?= rupiah($r1->total) ?></td>
                                                <td class="text-success">
                                                    <?= $pakaiSm->qty > 0 ? "<span class='badge bg-warning'>dalam pengajuan</span>" : round($sisa, 1) . '%' ?>
                                                </td>
                                                <td>
                                                    <?php if ($data->status === 'belum' || $data->status === 'ditolak') {
                                                        if ($pakaiSm->qty > 0 || $sisa == 100) {
                                                        } elseif ($pakaiSm->qty < 1 && $pakai->qty < 1) { ?>
                                                            <a class="tbl-confirm" value="RAB ini akan di PAK dengan status dihapus." href="<?= base_url('lembaga/addDelPak/' . $data->kode_pak . '/' . $r1->id_rab); ?>"><button class="btn btn-sm btn-danger"><i class="bx bx-trash"></i></button></a>
                                                            <a href="<?= base_url('lembaga/pakDetailEdit/' . $data->kode_pak . '/' . $r1->id_rab); ?>"><button class="btn btn-sm btn-warning"><i class="bx bx-pencil"></i></button></a>
                                                        <?php } elseif ($pakaiSm->qty < 1 && $pakai->qty > 0) { ?>
                                                            <a href="<?= base_url('lembaga/pakDetailEdit/' . $data->kode_pak . '/' . $r1->id_rab); ?>"><button class="btn btn-sm btn-warning"><i class="bx bx-pencil"></i></button></a>
                                                    <?php }
                                                    } ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card radius-10">
                                <div class="card-body">
                                    <h4>RAB yang di PAK</h4>
                                    <div class="col">
                                        <div class="card radius-10 bg-primary bg-gradient">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <?php
                                                        $dt_pak = $this->db->query("SELECT SUM(total) AS tt FROM pak_detail WHERE kode_pak = '$data->kode_pak' AND tahun = '$tahun' ")->row();
                                                        ?>
                                                        <p class="mb-0 text-white">Total Nominal PAK</p>
                                                        <h4 class="my-1 text-white"><?= rupiah($dt_pak->tt); ?></h4>
                                                    </div>
                                                    <div class="text-white ms-auto font-35"><i class='bx bx-cart-alt'></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="example" class="table table-striped table-bordered">
                                            <thead>
                                                <tr style="color: white; background-color: #1D99FF; font-weight: bold;">
                                                    <!-- <th>No</th> -->
                                                    <th>Kode</th>
                                                    <th>Barang/Kegiatan</th>
                                                    <th>QTY</th>
                                                    <!-- <th>Harga Satuan</th> -->
                                                    <th>Total</th>
                                                    <th>Ket</th>
                                                    <td>#</td>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php
                                                $dt1 = $this->db->query("SELECT a.*, b.nama FROM pak_detail a JOIN rab b ON a.kode_rab=b.kode WHERE a.kode_pak = '$data->kode_pak' AND a.tahun = '$tahun' ")->result();
                                                foreach ($dt1 as $r1) {

                                                ?>
                                                    <tr>
                                                        <!-- <td><?= $no++; ?></td> -->
                                                        <td><?= $r1->kode_rab ?></td>
                                                        <td><?= $r1->nama ?></td>
                                                        <td><?= $r1->qty . ' x ' . number_format($r1->harga_satuan) ?></td>
                                                        <!-- <td><?= rupiah($r1->harga_satuan) ?></td> -->
                                                        <td><?= number_format($r1->total) ?></td>
                                                        <td class="text-success">
                                                            <?= $r1->ket == 'hapus' ? "<span class='badge bg-danger btn-rounded'>hapus</span>" : "<span class='badge bg-success btn-rounded'>edit</span>" ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($data->status === 'belum' || $data->status === 'ditolak') { ?>
                                                                <a href="<?= base_url('lembaga/delPakDetail/' . $r1->kode_pak . '/' . $r1->kode_rab); ?>" class="text-danger tombol-hapus"><i class="bx bx-trash"></i></a>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card radius-10">
                                <div class="card-body">
                                    <h4>RAB baru yang akan diajukan</h4>
                                    <div class="col">
                                        <div class="card radius-10 bg-danger bg-gradient">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <?php
                                                        $dt1 = $this->db->query("SELECT * FROM rab_sm WHERE lembaga = '$lembaga->kode' AND tahun = '$tahun' AND kode_pak = '$data->kode_pak' ")->result();
                                                        $dt_rab = $this->db->query("SELECT SUM(total) AS tt FROM rab_sm WHERE lembaga = '$lembaga->kode' AND tahun = '$tahun' AND kode_pak = '$data->kode_pak' ")->row();
                                                        ?>
                                                        <p class="mb-0 text-white">Total Nominal RAB Baru</p>
                                                        <h4 class="my-1 text-white"><?= rupiah($dt_rab->tt); ?></h4>
                                                    </div>
                                                    <?php if ($data->status === 'belum' || $data->status === 'ditolak') { ?>
                                                        <div class="text-white ms-auto font-35"><button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#tambah_bos"><i class="bx bx-plus"></i>Buat
                                                                RAB</button>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="example3" class="table table-striped table-bordered">
                                            <thead>
                                                <tr style="color: white; background-color: #FD4D65; font-weight: bold;">
                                                    <!-- <th>No</th> -->
                                                    <th>Kode</th>
                                                    <th>Barang/Kegiatan</th>
                                                    <th>Bln</th>
                                                    <th>QTY</th>
                                                    <th>Total</th>
                                                    <td>#</td>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php foreach ($dt1 as $r1) { ?>
                                                    <tr>
                                                        <!-- <td><?= $no++; ?></td> -->
                                                        <td><?= $r1->kode ?></td>
                                                        <td><?= $r1->nama ?></td>
                                                        <td><?= $r1->rencana ?></td>
                                                        <td><?= $r1->qty . ' x ' . number_format($r1->harga_satuan) ?></td>
                                                        <!-- <td><?= rupiah($r1->harga_satuan) ?></td> -->
                                                        <td><?= number_format($r1->total) ?></td>
                                                        <td>
                                                            <?php if ($data->status === 'belum' || $data->status === 'ditolak') { ?>
                                                                <a class="tombol-hapus" href="<?= base_url('lembaga/delRabSm/' . $data->kode_pak . '/' . $r1->kode); ?>"><button class="btn btn-sm btn-danger"><i class="bx bx-trash"></i></button></a>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <center>
                        <p style="color: red; font-weight: bold;">Belum ada Jadwal PAK Aktif</p>
                    </center>
                <?php } ?>
            </div>
        </div>
    </div>
    <!--end row-->
</div>
<!--end page wrapper -->

<div class="modal fade" id="tambah_bos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Buat RAB Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('lembaga/addRab'); ?>" method="post">
                <input type="hidden" name="kode_pak" value="<?= $data->kode_pak ?>">
                <div class="modal-body">
                    <div class="row form-group">
                        <label for="tahun" class="col-form-label col-md-3 col-sm-3 label-align">Lembaga <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="first-name" name="lembaga" disabled class="form-control" value="<?= $lembaga->kode . '. ' . $lembaga->nama ?>">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Program <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 ">
                            <select name="program" class="form-control" id="" required>
                                <option value=""> -pilih program- </option>
                                <?php
                                foreach ($dppk as $a2) { ?>
                                    <option value="<?= $a2->id_dppk ?>"><?= $a2->id_dppk ?>. <?= $a2->program ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align">Nama Kegiatan <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="first-name" name="kegiatan" class="form-control" required>
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
                                <?php
                                foreach ($jenis as $a2) { ?>
                                    <option value="<?= $a2->kode_jns ?>"><?= $a2->kode_jns ?>. <?= $a2->nama ?></option>
                                <?php } ?>
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
                                <option value=""> -- pilih rencana -- </option>
                                <?php for ($i = 1; $i <= 12; $i++) : ?>
                                    <option value="<?= $i ?>"><?= $bulan[$i] ?></option>
                                <?php endfor ?>
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