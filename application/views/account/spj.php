<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data SPJ Pengajuan</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-wallet"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Realisasi</li>
                    </ol>
                </nav>
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
                                        <th>Lembaga</th>
                                        <th>Periode</th>
                                        <th>Status</th>
                                        <th>Nominal</th>
                                        <th>Cair</th>
                                        <th>Serap</th>
                                        <th>File</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data as $a) :
                                        $kd_pj = $a->kode_pengajuan;

                                        if ($a->cair == 1) {
                                            $tbl_slct = 'realis';
                                        } else {
                                            $tbl_slct = 'real_sm';
                                        }
                                        $jml = $this->db->query("SELECT SUM(nominal) AS jml, SUM(nom_cair) AS jml_cair, SUM(nom_serap) AS jml_serap FROM $tbl_slct WHERE kode_pengajuan = '$kd_pj' AND tahun = '$tahun' ")->row();

                                        if (preg_match("/DISP./i", $kd_pj)) {
                                            $rt = "<span class='badge bg-danger'>DISPOSISI</span>";
                                        } else {
                                            $rt = '';
                                        }

                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $kd_pj ?></td>
                                            <td><?= $a->nama . ' ' . $rt; ?></td>
                                            <td><?= $bulan[$a->bulan] . ' ' . $a->tahun ?></td>
                                            <td>
                                                <?php if ($a->stts == 0 && $a->file_spj == '') { ?>
                                                    <span class="badge bg-danger"><i class="bx bx-times"></i> belum upload</span>
                                                <?php } else if ($a->stts == 0 && $a->file_spj != '') { ?>
                                                    <span class="badge bg-danger"><i class="bx bx-times"></i> ditolak</span>
                                                <?php } else if ($a->stts == 1) { ?>
                                                    <span class="badge bg-warning btn-xs"><i class="bx bx-recycle"></i> proses verifikasi</span>
                                                <?php } else { ?>
                                                    <span class="badge bg-success"><i class="bx bx-check"></i> selesai</span>
                                                <?php } ?>
                                            </td>
                                            <td><?= rupiah($jml->jml) ?></td>
                                            <td><?= rupiah($jml->jml_cair) ?></td>
                                            <td><?= rupiah($jml->jml_serap) ?></td>
                                            <td>
                                                <a href="<?= base_url('account/viewSpj/') . $a->kode_pengajuan ?>"><i class="bx bx-show-alt"></i>Lihat</a>
                                            </td>
                                            <td>
                                                <?php if ($a->stts == 1 && $a->perencanaan == 1) { ?>
                                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambah_bos<?= $a->id_spj ?>"><i class="bx bx-check"></i>Setujui</button>
                                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#tolak_bos<?= $a->id_spj ?>"><i class="bx bx-no-entry"></i>Tolak</button>

                                                    <!-- Modal Setujui BOS-->
                                                    <div class="modal fade" id="tambah_bos<?= $a->id_spj ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Form
                                                                        Persetujuan SPJ</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form action="<?= base_url('account/setujuiSpj'); ?>" method="post">
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="id" value="<?= $a->id_spj; ?>">
                                                                        <input type="hidden" name="kode" value="<?= $a->kode_pengajuan; ?>">
                                                                        <input type="hidden" name="hp" value="<?= $a->hp; ?>">
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Lembaga <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" id="first-name" name="nm_lm" required="required" readonly value="<?= $a->nama ?>" class="form-control ">
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Periode <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" id="first-name" required="required" disabled value="<?= $bulan[$a->bulan] . ' ' . $a->tahun ?>" class="form-control ">
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nominal <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" id="first-name" name="cair" required="required" value="<?= rupiah($jml->jml_cair) ?>" class="form-control" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Dana Terserap <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6  form-group has-feedback">
                                                                                <input type="text" class="form-control has-feedback-left uang" id="" name="serap" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Tanggal disetujui <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" id="" required="required" name="tgl" class="form-control dateFormat">
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Menyetujui <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" required="required" name="user" value="<?= $user->nama ?>" class="form-control" readonly>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        <button type="submit" name="save" class="btn btn-success"><i class="bx bx-check"></i>Setujui</button>
                                                                    </div>
                                                                </form>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Modal Tolak BOS-->
                                                    <div class="modal fade" id="tolak_bos<?= $a->id_spj ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">

                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Form
                                                                        Penolakan SPJ</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form action="<?= base_url('account/tolakSpj'); ?>" method="post">
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="id" value="<?= $a->id_spj; ?>">
                                                                        <input type="hidden" name="kode" value="<?= $a->kode_pengajuan; ?>">
                                                                        <input type="hidden" name="hp" value="<?= $a->hp; ?>">
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Lembaga <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" name="nm_lm" id="first-name" readonly value="<?= $a->nama ?>" class="form-control ">
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Periode <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" id="first-name" required="required" disabled value="<?= $bulan[$a->bulan] . ' ' . $a->tahun ?>" class="form-control ">
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nominal <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" id="first-name" required="required" disabled value="<?= rupiah($jml->jml_cair) ?>" class="form-control ">
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Tanggal penolakan <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" id="" required="required" name="tgl" class="form-control dateFormat">
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Catatan Penolakan <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <textarea id="" required="required" name="isi" class="form-control "></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Menolaks <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" required="required" name="user" value="<?= $user->nama ?>" class="form-control" readonly>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        <button type="submit" class="btn btn-danger"><i class="bx bx-check"></i>Tolak Now</button>
                                                                    </div>
                                                                </form>

                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } elseif ($a->stts == 2) { ?>
                                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#uploads<?= $a->id_spj ?>"><i class="bx bx-money"></i> Upload Sisa</button>

                                                    <!-- Modal Upload BOS-->
                                                    <div class="modal fade" id="uploads<?= $a->id_spj ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">

                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Upload Sisa
                                                                        Realisasi</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form action="<?= base_url('account/uploadSisa'); ?>" method="post">
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="id" value="<?= $a->id_spj; ?>">
                                                                        <input type="hidden" name="kode" value="<?= $a->kode_pengajuan; ?>">
                                                                        <input type="hidden" name="hp" value="<?= $a->hp; ?>">
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Lembaga <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" id="first-name" name="nm_lm" required="required" readonly value="<?= $a->nama ?>" class="form-control ">
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Periode <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" id="first-name" required="required" disabled value="<?= $bulan[$a->bulan] . ' ' . $a->tahun ?>" class="form-control ">
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nominal <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" id="first-name" name="cair" required="required" value="<?= rupiah($jml->jml_cair) ?>" class="form-control" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Dana Terserap <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6  form-group has-feedback">
                                                                                <input type="text" class="form-control has-feedback-left uang" id="" name="serap" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Tanggal setor <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" id="" required="required" name="tgl_setor" class="form-control dateFormat">
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Menyetujui <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" required="required" name="user" value="<?= $user->nama ?>" class="form-control" readonly>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        <button type="submit" name="upload" class="btn btn-success"><i class="bx bx-check"></i>Setujui</button>
                                                                    </div>
                                                                </form>

                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </td>
                                        </tr>

                                    <?php endforeach; ?>
                                    <?php
                                    foreach ($dataSr as $a2) :
                                        $kd_pj = $a2->kode_pengajuan;


                                        $jml = $this->db->query("SELECT SUM(qty * harga_satuan) AS jml, SUM(qty * harga_satuan) AS jml_cair, SUM(qty * harga_satuan) AS jml_serap FROM sarpras_detail WHERE kode_pengajuan = '$kd_pj' AND tahun = '$tahun' ")->row();

                                        if (preg_match("/DISP./i", $kd_pj)) {
                                            $rt = "<span class='badge bg-danger'>DISPOSISI</span>";
                                        } else {
                                            $rt = '';
                                        }

                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $kd_pj ?></td>
                                            <td><?= $a2->nama . ' ' . $rt; ?></td>
                                            <td><?= $bulan[$a2->bulan] . ' ' . $a2->tahun ?></td>
                                            <td>
                                                <?php if ($a2->stts == 0 && $a2->file_spj == '') { ?>
                                                    <span class="badge bg-danger"><i class="bx bx-times"></i> belum upload</span>
                                                <?php } else if ($a2->stts == 0 && $a2->file_spj != '') { ?>
                                                    <span class="badge bg-danger"><i class="bx bx-times"></i> ditolak</span>
                                                <?php } else if ($a2->stts == 1) { ?>
                                                    <span class="badge bg-warning btn-xs"><i class="bx bx-recycle"></i> proses verifikasi</span>
                                                <?php } else { ?>
                                                    <span class="badge bg-success"><i class="bx bx-check"></i> selesai</span>
                                                <?php } ?>
                                            </td>
                                            <td><?= rupiah($jml->jml) ?></td>
                                            <td><?= rupiah($jml->jml_cair) ?></td>
                                            <td><?= rupiah($jml->jml_serap) ?></td>
                                            <td>
                                                <a href="<?= base_url('account/viewSpj/') . $a2->kode_pengajuan ?>"><i class="bx bx-show-alt"></i>Lihat</a>
                                            </td>
                                            <td>
                                                <?php if ($a2->stts == 1) { ?>
                                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#setujuiSpjSarpras<?= $a2->id_spj ?>"><i class="bx bx-check"></i>Setujui</button>
                                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#tolakSpjSarpras<?= $a2->id_spj ?>"><i class="bx bx-no-entry"></i>Tolak</button>

                                                    <!-- Modal Setujui BOS-->
                                                    <div class="modal fade" id="setujuiSpjSarpras<?= $a2->id_spj ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Form
                                                                        Persetujuan SPJ</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form action="<?= base_url('account/setujuiSpj'); ?>" method="post">
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="id" value="<?= $a2->id_spj; ?>">
                                                                        <input type="hidden" name="kode" value="<?= $a2->kode_pengajuan; ?>">
                                                                        <input type="hidden" name="hp" value="<?= $a2->hp; ?>">
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Lembaga <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" id="first-name" name="nm_lm" required="required" readonly value="<?= $a2->nama ?>" class="form-control ">
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Periode <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" id="first-name" required="required" disabled value="<?= $bulan[$a2->bulan] . ' ' . $a2->tahun ?>" class="form-control ">
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nominal <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" id="first-name" name="cair" required="required" value="<?= rupiah($jml->jml_cair) ?>" class="form-control" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Dana Terserap <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6  form-group has-feedback">
                                                                                <input type="text" class="form-control has-feedback-left uang" id="" name="serap" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Tanggal disetujui <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" id="" required="required" name="tgl" class="form-control dateFormat">
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Menyetujui <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" required="required" name="user" value="<?= $user->nama ?>" class="form-control" readonly>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        <button type="submit" name="save" class="btn btn-success"><i class="bx bx-check"></i>Setujui</button>
                                                                    </div>
                                                                </form>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Modal Tolak BOS-->
                                                    <div class="modal fade" id="tolakSpjSarpras<?= $a2->id_spj ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">

                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Form
                                                                        Penolakan SPJ</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form action="<?= base_url('account/tolakSpj'); ?>" method="post">
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="id" value="<?= $a2->id_spj; ?>">
                                                                        <input type="hidden" name="kode" value="<?= $a2->kode_pengajuan; ?>">
                                                                        <input type="hidden" name="hp" value="<?= $a2->hp; ?>">
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Lembaga <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" name="nm_lm" id="first-name" readonly value="<?= $a2->nama ?>" class="form-control ">
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Periode <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" id="first-name" required="required" disabled value="<?= $bulan[$a2->bulan] . ' ' . $a2->tahun ?>" class="form-control ">
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nominal <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" id="first-name" required="required" disabled value="<?= rupiah($jml->jml_cair) ?>" class="form-control ">
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Tanggal penolakan <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" id="" required="required" name="tgl" class="form-control dateFormat">
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Catatan Penolakan <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <textarea id="" required="required" name="isi" class="form-control "></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Menolaks <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" required="required" name="user" value="<?= $user->nama ?>" class="form-control" readonly>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        <button type="submit" class="btn btn-danger"><i class="bx bx-check"></i>Tolak Now</button>
                                                                    </div>
                                                                </form>

                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } elseif ($a2->stts == 2) { ?>
                                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#uploads<?= $a2->id_spj ?>"><i class="bx bx-money"></i>Upload Sisa</button>

                                                    <!-- Modal Upload BOS-->
                                                    <div class="modal fade" id="uploads<?= $a2->id_spj ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">

                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Upload Sisa
                                                                        Realisasi</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form action="<?= base_url('account/uploadSisa'); ?>" method="post">
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="id" value="<?= $a2->id_spj; ?>">
                                                                        <input type="hidden" name="kode" value="<?= $a2->kode_pengajuan; ?>">
                                                                        <input type="hidden" name="hp" value="<?= $a2->hp; ?>">
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Lembaga <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" id="first-name" name="nm_lm" required="required" readonly value="<?= $a2->nama ?>" class="form-control ">
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Periode <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" id="first-name" required="required" disabled value="<?= $bulan[$a2->bulan] . ' ' . $a2->tahun ?>" class="form-control ">
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nominal <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" id="first-name" name="cair" required="required" value="<?= rupiah($jml->jml_cair) ?>" class="form-control" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Dana Terserap <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6  form-group has-feedback">
                                                                                <input type="text" class="form-control has-feedback-left uang" id="" name="serap" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Tanggal setor <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" id="" required="required" name="tgl_setor" class="form-control dateFormat">
                                                                            </div>
                                                                        </div>
                                                                        <div class="item form-group">
                                                                            <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Menyetujui <span class="required">*</span>
                                                                            </label>
                                                                            <div class="col-md-6 col-sm-6 ">
                                                                                <input type="text" required="required" name="user" value="<?= $user->nama ?>" class="form-control" readonly>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        <button type="submit" name="upload" class="btn btn-success"><i class="bx bx-check"></i>Setujui</button>
                                                                    </div>
                                                                </form>

                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
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