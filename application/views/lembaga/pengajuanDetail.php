<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Pengajuan Lembaga</div>
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
                    <a href="<?= base_url('lembaga/pengajuan'); ?>" class="btn btn-light btn-sm"><i class="bx bx-subdirectory-left"></i>
                        Kembali</a>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-12">
                <?php if ($pj->stts === 'no') { ?>
                    <div class="card radius-10">
                        <div class="card-body">
                            <?= form_open('lembaga/addItem'); ?>
                            <input type="hidden" name="bln_pj" value="<?= $pj->bulan; ?>">
                            <input type="hidden" name="kode_pengajuan" value="<?= $pj->kode_pengajuan; ?>">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row mb-3">
                                        <label for="selectDppk" class="col-sm-3 col-form-label">Pilih Program</label>
                                        <div class="col-sm-9">
                                            <select class="form-control single-select" name="id_dppk" id="selectDppk" required>
                                                <option value="">-pilih Program-</option>
                                                <?php foreach ($dppk as $dppk) : ?>
                                                    <option value="<?= $dppk->id_dppk ?>"><?= $dppk->id_dppk . ' - ' . $dppk->program ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="tabelData">
                                        <!-- Hasil dari permintaan Ajax akan dimuat di sini -->
                                    </div>
                                </div>
                            </div>
                            <?= form_close(); ?>
                        </div>
                    </div>
                <?php } ?>

                <div class="card radius-10">
                    <div class="card-body">
                        <?php if ($pj->stts === 'no') { ?>
                            <a href="<?= base_url('lembaga/ajukan/' . $pj->kode_pengajuan); ?>" value="Pengajuan akan dilanjutkan kepada Accounting untuk proses Verifikasi" class="btn btn-success btn-sm tbl-confirm mb-2"><i class="bx bx-window-open"></i>Ajukan ke Bendahara</a>
                            <button class="btn btn-warning btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bx bx-edit"></i>Edit PJ</button>
                        <?php } ?>
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                        <th>No</th>
                                        <th>Kode RAB</th>
                                        <th>Bulan</th>
                                        <th>PJ</th>
                                        <th>Nominal</th>
                                        <th>Ket</th>
                                        <th>Cair</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    if ($pj->cair ==  1) {
                                        $rls = $this->db->query("SELECT * FROM realis WHERE kode_pengajuan = '$pj->kode_pengajuan' AND tahun = '$tahun' ")->result();
                                        $nm = $this->db->query("SELECT SUM(nominal) AS jml FROM realis WHERE kode_pengajuan = '$pj->kode_pengajuan' AND tahun = '$tahun' ")->row();
                                    } else {
                                        $rls = $this->db->query("SELECT * FROM real_sm WHERE kode_pengajuan = '$pj->kode_pengajuan' AND tahun = '$tahun' ")->result();
                                        $nm = $this->db->query("SELECT SUM(nominal) AS jml FROM real_sm WHERE kode_pengajuan = '$pj->kode_pengajuan' AND tahun = '$tahun' ")->row();
                                    }

                                    foreach ($rls as $ls_jns) {
                                        $kd_rab = $ls_jns->kode;
                                        $kd_ppnj = $ls_jns->kode_pengajuan;
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $ls_jns->kode; ?></td>
                                            <td><?= $bulan[$ls_jns->bulan]; ?> <?= $ls_jns->tahun; ?></td>
                                            <td><?= $ls_jns->pj; ?></td>
                                            <td><?= rupiah($ls_jns->nominal); ?></td>
                                            <!-- <td><?= date('H:i', strtotime($ls_jns->tgl)); ?></td> -->
                                            <td>
                                                <?= $ls_jns->ket; ?>
                                                <?php
                                                if (preg_match("/honor/i", $ls_jns->ket)) {
                                                    $jm_upd = $this->db->query("SELECT * FROM honor_file WHERE kode_pengajuan = '$kd_ppnj' AND kode_rab = '$kd_rab' AND tahun = '$tahun' ")->num_rows();
                                                    if ($jm_upd > 0) {
                                                        $ktb = 'update';
                                                        $lbl = "<span class='badge bg-success'><i class='bx bx-check'></i> sudah</span>";
                                                    } else {
                                                        $ktb = 'baru';
                                                        $lbl = "<span class='badge bg-danger'><i class='bx bx-no-entry'></i> belum</span>";
                                                    }
                                                ?>
                                                    <hr>
                                                    <i>Jika pengajuan honor. Maka diwajibkan untuk upload rincian honor.
                                                        (xls/xlsx)</i>
                                                    <form action="<?= base_url('lembaga/uploadHonor'); ?>" method="post" enctype="multipart/form-data">
                                                        <input type="hidden" name="kd_rab" value="<?= $kd_rab; ?>">
                                                        <input type="hidden" name="kd_ppnj" value="<?= $kd_ppnj; ?>">
                                                        <input type="hidden" name="ktb" value="<?= $ktb; ?>">
                                                        <div class="row mb-3">
                                                            <div class="col-sm-6">
                                                                <input type="file" name="f_rin" class="form-control" required>
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <button class="btn btn-success btn-sm" type="submit"><i class="bx bx-save"></i></button>
                                                                | <b class="">Status Upload : <?= $lbl; ?></b>
                                                            </div>
                                                        </div>
                                                    </form>
                                                <?php } ?>
                                            </td>
                                            <td><?= $ls_jns->stas; ?></td>
                                            <td>
                                                <?php if ($pj->verval == 0 && $pj->stts == 'no') { ?>
                                                    <a href="<?= base_url('lembaga/delReal/' . $ls_jns->id_realis) ?>" class="btn btn-danger btn-sm tombol-hapus">Hapus</a>
                                                <?php } else { ?>
                                                    <button class="btn btn-danger btn-sm" disabled>Hapus</button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4">TOTAL PENGAJUAN</th>
                                        <th colspan="3"><?= rupiah($nm->jml); ?></th>
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

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Isi Tanggal dan PJ Pengajuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('lembaga/editPajukan') ?>
            <div class="modal-body">
                <div class="row mb-1">
                    <label for="input35" class="col-sm-3 col-form-label">Kode</label>
                    <div class="col-sm-9">
                        <input type="text" name="kode_pengajuan" class="form-control" id="input35" value="<?= $pj->kode_pengajuan ?>" readonly>
                    </div>
                </div>
                <div class="row mb-1">
                    <label for="input35" class="col-sm-3 col-form-label">PJ</label>
                    <div class="col-sm-9">
                        <input type="text" name="pj" class="form-control" id="input35" required>
                    </div>
                </div>
                <div class="row mb-1">
                    <label for="input35" class="col-sm-3 col-form-label">Tanggal</label>
                    <div class="col-sm-9">
                        <input type="text" name="tgl" class="form-control date-time" id="input35" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Ajukan Sekarang</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<!--end page wrapper -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#selectDppk').change(function() {
            var dppk = $(this).val();

            $.ajax({
                url: '<?php echo base_url('lembaga/getDataBydppk'); ?>',
                type: 'POST',
                data: {
                    dppk: dppk
                },
                success: function(response) {
                    $('#tabelData').html(response);
                }
            });
        });

        $('#checkboxControl').change(function() {
            if (this.checked) {
                $('#checkboxTarget').prop('checked', true);
            } else {
                $('#checkboxTarget').prop('checked', false);
            }
        });
    });
</script>