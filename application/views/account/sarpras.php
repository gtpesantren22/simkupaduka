<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Pengajuan Sarpras</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-cog"></i></a>
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
                        <div class="row ">
                            <div class="col">
                                <div class="card radius-10 border-start border-0 border-3 border-info">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <p class="mb-0 text-secondary">Pagu</p>
                                                <h4 class="my-1 text-info"><?= rupiah($pagu) ?></h4>
                                                <p class="mb-0 font-13">Jumlah pagu untuk sarpras</p>
                                            </div>
                                            <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                                                <i class='bx bxs-cart'></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="card radius-10 border-start border-0 border-3 border-danger">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <p class="mb-0 text-secondary">Terpakai</p>
                                                <h4 class="my-1 text-danger"><?= rupiah($pakai->jml) ?></h4>
                                                <p class="mb-0 font-13">Jumlah pemakaian anggaran</p>
                                            </div>
                                            <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto"><i class='bx bxs-wallet'></i>
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
                                                <p class="mb-0 text-secondary">Sisa Pagu</p>
                                                <h4 class="my-1 text-success"><?= rupiah($pagu - $pakai->jml) ?></h4>
                                                <p class="mb-0 font-13">Sisa saldo pagu anggaran</p>
                                            </div>
                                            <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                                                <i class='bx bxs-bar-chart-alt-2'></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-3">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nominal</th>
                                        <th>Bulan</th>
                                        <th>Tahun</th>
                                        <th>Status</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data as $ls_jns) :
                                        $kd_pj = $ls_jns->kode_pengajuan;
                                        $jml = $this->db->query("SELECT SUM(qty * harga_satuan) AS jml FROM sarpras_detail WHERE kode_pengajuan = '$kd_pj' AND tahun = '$tahun' ")->row();

                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $ls_jns->kode_pengajuan; ?></td>
                                            <td><?= rupiah($jml->jml); ?></td>
                                            <td><?= $bulan[$ls_jns->bulan]; ?></td>
                                            <td><?= $ls_jns->tahun; ?></td>
                                            <td>
                                                <?php
                                                if ($ls_jns->status == 'proses') {
                                                    echo "<span class='badge bg-primary'><i class='bx bx-check'></i> Proses</span>";
                                                } elseif ($ls_jns->status == 'ditolak') {
                                                    echo "<span class='badge bg-danger'><i class='bx bx-x'></i> Ditolak</span>";
                                                } elseif ($ls_jns->status == 'disetujui') {
                                                    echo "<span class='badge bg-success'><i class='bx bx-check'></i> Disetujui</span>";
                                                } elseif ($ls_jns->status == 'selesai') {
                                                    echo "<span class='badge bg-info'><i class='bx bx-check'></i> Selesai</span>";
                                                } elseif ($ls_jns->status == 'belum') {
                                                    echo "<span class='badge bg-warning'>Input</span>";
                                                } elseif ($ls_jns->status == 'selesai') {
                                                    echo "<span class='badge bg-success'>Selesai</span>";
                                                } elseif ($ls_jns->status == 'dicairkan') {
                                                    echo "<span class='badge bg-info'>Dicairkan</span>";
                                                }
                                                ?>
                                            </td>
                                            <td><a href="<?= base_url('account/sarprasDetail/' . $ls_jns->kode_pengajuan) ?>"><button class="btn btn-info btn-sm"><i class="bx bx-search"></i>cek</button></a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <h5>File SPJ</h5>
                        <div class="table-responsive">
                            <table id="example3" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Periode</th>
                                        <th>Status</th>
                                        <th>Nominal</th>
                                        <th>Cair</th>
                                        <th>Serap</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
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
                                            <td><?= $bulan[$a2->bulan] . ' ' . $a2->tahun ?></td>
                                            <td>
                                                <?php if ($a2->stts == 0) { ?>
                                                    <span class="badge bg-danger"><i class="bx bx-times"></i> belum</span>
                                                <?php } else if ($a2->stts == 1) { ?>
                                                    <span class="badge bg-warning btn-xs"><i class="bx bx-recycle"></i> proses</span>
                                                <?php } else { ?>
                                                    <span class="badge bg-success"><i class="bx bx-check"></i> sudah</span>
                                                <?php } ?>
                                            </td>
                                            <td><?= rupiah($jml->jml) ?></td>
                                            <td><?= rupiah($jml->jml_cair) ?></td>
                                            <td><?= rupiah($jml->jml_serap) ?></td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Act</button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" href="<?= base_url('account/viewSpj/') . $a2->kode_pengajuan ?>"><i class="bx bx-show-alt"></i>Lihat SPJ</a></li>
                                                            <?php if ($a2->stts == 1) { ?>
                                                                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#setujuiSpjSarpras<?= $a2->id_spj ?>"><i class="bx bx-check"></i>Setujui</a></li>
                                                                <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#tolakSpjSarpras<?= $a2->id_spj ?>"><i class="bx bx-check"></i><i class="bx bx-no-entry"></i>Tolak</a>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <?php if ($a2->stts == 1) { ?>
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