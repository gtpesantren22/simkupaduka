<?php
include 'head.php';
$tot = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jm FROM bos WHERE tahun = '$tahun_ajaran' "));
$tot2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jm FROM pesantren WHERE tahun = '$tahun_ajaran' "));
$tot3 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(sisa) AS jm FROM real_sisa WHERE tahun = '$tahun_ajaran' "));
$tot4 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) AS jm FROM pembayaran WHERE tahun = '$tahun_ajaran' "));

$bk = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "July", "Agustus", "September", "Oktober", "November", "Desember");

?>
<!-- Datatables -->
<link href="vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
<link href="vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
<link href="vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
<link href="vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

<!-- bootstrap-daterangepicker -->
<link href="vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<!-- bootstrap-datetimepicker -->
<link href="vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">

<!-- page content -->
<div class="right_col" role="main">
    <div class="">

        <div class="animated flipInY col-lg-12 col-md-3 col-sm-6  ">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-money"></i>
                </div>
                <div class="count">Rp. <?= number_format(($tot['jm'] + $tot2['jm'] + $tot3['jm'] + $tot4['jm']), 0, '.', '.') ?></div>

                <h3>Total pemasukan tahun ini</h3>
                <p>Pemasukan ini diadapatkan dari data BOS, Pemasukan Pesantren, dan Biaya pendidikan santri</p>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="">
            <div class="col-md-12 col-sm-12  ">
                <div class="x_panel">
                    <div class="x_title">
                        <h2><i class="fa fa-bars"></i> Rincian Pemasukan <small>untuk pesantren</small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#">Settings 1</a>
                                    <a class="dropdown-item" href="#">Settings 2</a>
                                </div>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Pemasukan BOS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Pemasukan Pesantren</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="aki-tab" data-toggle="tab" href="#aki" role="tab" aria-controls="aki" aria-selected="false">Saldo Realisasi</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">

                            <!-- Pemasukan BOS -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <br>
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="alert alert-success">
                                            <h5>Data pemasukan BOS</h5>
                                            <h5>Total Pemasukan : Rp. <?= number_format($tot['jm'], 0, '.', '.') ?></h5>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-lg btn-info btn-block" data-toggle="modal" data-target="#tambah_bos"><i class="fa fa-plus-square"></i> Tambah Data</button>
                                    </div>
                                </div>
                                <hr>
                                <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Uraian</th>
                                            <th>Periode</th>
                                            <th>Nominal</th>
                                            <th>Tanggal Bayar</th>
                                            <th>Tahun</th>
                                            <th>Act</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $dt_bos = mysqli_query($conn, "SELECT * FROM bos WHERE tahun = '$tahun_ajaran'");
                                        while ($a = mysqli_fetch_assoc($dt_bos)) { ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $a['kode'] ?></td>
                                                <td><?= $a['uraian'] ?></td>
                                                <td><?= $a['periode'] ?></td>
                                                <td>Rp. <?= number_format($a['nominal'], 0, '.', '.') ?></td>
                                                <td><?= $a['tgl_setor'] ?></td>
                                                <td><?= $a['tahun'] ?></td>
                                                <td>
                                                    <a href="<?= 'edit_bos.php?id=' . $a['id_bos']; ?>"><button class="btn btn-warning btn-sm"><i class="fa fa-cog"></i> Edit</button></a>
                                                    <a onclick="return confirm('Yakin akan dihapus ?')" href="<?= 'hapus.php?kd=bos&id=' . $a['id_bos']; ?>"><button class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Del</button></a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <!-- Modal Tambah Data BOS-->
                                <div class="modal fade" id="tambah_bos" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Tambah data pemasukan BOS</h4>
                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left input_mask" action="" method="post">
                                                <div class="modal-body">

                                                    <div class="item form-group">
                                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Kode <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-6 col-sm-6 ">
                                                            <select name="kode" class="form-control" id="" required>
                                                                <option value=""> -pilih lembaga- </option>
                                                                <?php
                                                                $qr = mysqli_query($conn, "SELECT * FROM lembaga WHERE lv = 'lf' AND tahun = '$tahun_ajaran' ");
                                                                while ($a = mysqli_fetch_assoc($qr)) { ?>
                                                                    <option value="<?= $a['kode'] ?>"><?= $a['kode'] ?>. <?= $a['nama'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="item form-group">
                                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Uraian <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-6 col-sm-6 ">
                                                            <input type="text" id="last-name" name="uraian" required="required" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="item form-group">
                                                        <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Periode <span class="required">*</span></label>
                                                        <div class="col-md-6 col-sm-6 ">
                                                            <input id="middle-name" class="form-control" type="text" name="periode" required>
                                                        </div>
                                                    </div>
                                                    <div class="item form-group">
                                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nominal <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-6 col-sm-6  form-group has-feedback">
                                                            <input type="text" class="form-control has-feedback-left " id="uang" name="nominal" required>
                                                            <span class="form-control-feedback left" aria-hidden="true">Rp.</span>
                                                        </div>

                                                    </div>
                                                    <div class="item form-group">
                                                        <label for="tahun" class="col-form-label col-md-3 col-sm-3 label-align">Tahun <span class="required">*</span></label>
                                                        <div class="col-md-6 col-sm-6 ">
                                                            <select name="tahun" id="" required class="form-control">
                                                                <option value=""> -- pilih tahun -- </option>
                                                                <?php
                                                                $th = date('Y');
                                                                for ($i = 2020; $i <= $th; $i++) { ?>
                                                                    <option value="<?= $i; ?>"><?= $i; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="item form-group">
                                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Tanggal Bayar <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-6 col-sm-6 xdisplay_inputx form-group row has-feedback">

                                                            <input type="text" name="tgl_bayar" class="form-control has-feedback-left" id="datePick" placeholder="" aria-describedby="inputSuccess2Status4">
                                                            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                                            <span id="inputSuccess2Status4" class="sr-only">(success)</span>

                                                        </div>
                                                    </div>
                                                    <div class="item form-group">
                                                        <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Inputer <span class="required">*</span></label>
                                                        <div class="col-md-6 col-sm-6 ">
                                                            <input id="middle-name" class="form-control" type="text" name="kasir" readonly value="<?= $nama_user ?>" required>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="save_bos" class="btn btn-success">Simpan data</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Pemasukan Pesantren -->
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <br>
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="alert alert-warning">
                                            <h5>Data pemasukan Pesantren</h5>
                                            <h5>Total Pemasukan : Rp. <?= number_format($tot2['jm'], 0, '.', '.') ?></h5>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-lg btn-info btn-block" data-toggle="modal" data-target="#tambah_pes"><i class="fa fa-plus-square"></i> Tambah Data</button>
                                    </div>
                                </div>
                                <hr>
                                <table id="datatable2" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Uraian</th>
                                            <th>Periode</th>
                                            <th>Nominal</th>
                                            <th>Tanggal Bayar</th>
                                            <th>Tahun</th>
                                            <th>Act</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $dt = mysqli_query($conn, "SELECT * FROM pesantren WHERE tahun = '$tahun_ajaran'");
                                        while ($a = mysqli_fetch_assoc($dt)) { ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $a['kode'] ?></td>
                                                <td><?= $a['uraian'] ?></td>
                                                <td><?= $a['periode'] ?></td>
                                                <td>Rp. <?= number_format($a['nominal'], 0, '.', '.') ?></td>
                                                <td><?= $a['tgl_bayar'] ?></td>
                                                <td><?= $a['tahun'] ?></td>
                                                <td>
                                                    <a href="<?= 'edit_pes.php?id=' . $a['id_pes']; ?>"><button class="btn btn-warning btn-sm"><i class="fa fa-cog"></i> Edit</button></a>
                                                    <a onclick="return confirm('Yakin akan dihapus ?')" href="<?= 'hapus.php?kd=pes&id=' . $a['id_pes']; ?>"><button class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Del</button></a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                                <!-- Modal Tambah Data Pemsaukan Pesantren-->
                                <div class="modal fade" id="tambah_pes" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Tambah data pemasukan dari Pesantren</h4>
                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left input_mask" action="" method="post">
                                                <div class="modal-body">
                                                    <div class="item form-group">
                                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Lembaga <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-6 col-sm-6 ">
                                                            <select name="lembaga" class="form-control" id="" required>
                                                                <option value=""> -pilih lembaga- </option>
                                                                <?php
                                                                $qr = mysqli_query($conn, "SELECT * FROM lembaga WHERE lv = 'ps' AND tahun = '$tahun_ajaran' ");
                                                                while ($a = mysqli_fetch_assoc($qr)) { ?>
                                                                    <option value="<?= $a['kode'] ?>"><?= $a['kode'] ?>. <?= $a['nama'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="item form-group">
                                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Bidang/bagian <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-6 col-sm-6 ">
                                                            <select name="bidang" class="form-control" id="" required>
                                                                <option value=""> -pilih bidang- </option>
                                                                <?php
                                                                $qr2 = mysqli_query($conn, "SELECT * FROM bidang WHERE tahun = '$tahun_ajaran'");
                                                                while ($a2 = mysqli_fetch_assoc($qr2)) { ?>
                                                                    <option value="<?= $a2['kode'] ?>"><?= $a2['kode'] ?>. <?= $a2['nama'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="item form-group">
                                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Uraian <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-6 col-sm-6 ">
                                                            <input type="text" id="last-name" name="uraian" required="required" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="item form-group">
                                                        <label for="middle-name" class="col-form-label col-md-3 col-sm-3 label-align">Periode <span class="required">*</span></label>
                                                        <div class="col-md-6 col-sm-6 ">
                                                            <input id="middle-name" class="form-control" type="text" name="periode" required>
                                                        </div>
                                                    </div>
                                                    <div class="item form-group">
                                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Nominal <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-6 col-sm-6  form-group has-feedback">
                                                            <input type="text" class="form-control has-feedback-left " id="uang_2" name="nominal" required>
                                                            <span class="form-control-feedback left" aria-hidden="true">Rp.</span>
                                                        </div>

                                                    </div>
                                                    <div class="item form-group">
                                                        <label for="tahun" class="col-form-label col-md-3 col-sm-3 label-align">Tahun <span class="required">*</span></label>
                                                        <div class="col-md-6 col-sm-6 ">
                                                            <select name="tahun" id="" required class="form-control">
                                                                <option value=""> -- pilih tahun -- </option>
                                                                <?php
                                                                $th = date('Y');
                                                                for ($i = 2022; $i <= $th; $i++) { ?>
                                                                    <option value="<?= $i; ?>"><?= $i; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="item form-group">
                                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Tanggal Bayar <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-6 col-sm-6 xdisplay_inputx form-group row has-feedback">

                                                            <input type="text" name="tgl_bayar" class="form-control has-feedback-left" id="datePick2" placeholder="" aria-describedby="inputSuccess2Status4">
                                                            <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                                            <span id="inputSuccess2Status4" class="sr-only">(success)</span>

                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="save_pes" class="btn btn-success">Simpan data</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SAldo Aki -->
                            <div class="tab-pane fade" id="aki" role="tabpanel" aria-labelledby="aki-tab">
                                <br>
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="alert alert-danger">
                                            <h5>Saldo Realisasi</h5>
                                            <h5>Total : Rp. <?= number_format($tot3['jm'], 0, '.', '.') ?></h5>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-lg btn-info btn-block" data-toggle="modal" data-target="#tambah_aki"><i class="fa fa-plus-square"></i> Tambah Data</button>
                                    </div>
                                </div>
                                <hr>
                                <table id="datatable4" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Pnj</th>
                                            <th>Lembaga</th>
                                            <th>Periode</th>
                                            <th>Dana Cair</th>
                                            <th>Terserap</th>
                                            <th>Sisa</th>
                                            <th>Act</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $dt = mysqli_query($conn, "SELECT * FROM real_sisa WHERE tahun = '$tahun_ajaran'");
                                        while ($a = mysqli_fetch_assoc($dt)) {
                                            $kd = explode('.', $a['kode_pengajuan']);
                                            $lm = $kd[0];
                                            $bl = $kd[2];
                                            $th = $kd[3];
                                            $nm =  mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$lm' AND tahun = '$tahun_ajaran' "));
                                        ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $a['kode_pengajuan'] ?></td>
                                                <td><?= $nm['nama'] ?></td>
                                                <td><?= $bk[$bl] . ' ' . $th ?></td>
                                                <td>Rp. <?= number_format($a['dana_cair'], 0, '.', '.') ?></td>
                                                <td>Rp. <?= number_format($a['dana_serap'], 0, '.', '.') ?></td>
                                                <td>Rp. <?= number_format($a['sisa'], 0, '.', '.') ?></td>
                                                <td>
                                                    <a onclick="return confirm('Yakin akan dihapus ?')" href="<?= 'hapus.php?kd=sisa&id=' . $a['id_sisa']; ?>"><button class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> Del</button></a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                                <!-- Modal Tambah Data Akien-->
                                <div class="modal fade" id="tambah_aki" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Tambah data saldo realisasi</h4>
                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left input_mask" action="" method="post">
                                                <div class="modal-body">
                                                    <div class="item form-group">
                                                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Pilih Realisasi <span class="required">*</span>
                                                        </label>
                                                        <div class="col-md-5 col-sm-5">
                                                            <select name="kode_pengajuan" class="form-control" id="search_query" required>
                                                                <option value=""> -pilih kode realis- </option>
                                                                <?php
                                                                $qr = mysqli_query($conn, "SELECT a.*, b.nama FROM realis a JOIN lembaga b ON a.lembaga = b.kode WHERE a.tahun = '$tahun_ajaran' GROUP BY kode_pengajuan ");
                                                                while ($a = mysqli_fetch_assoc($qr)) { ?>
                                                                    <option value="<?= $a['kode_pengajuan'] ?>"><?= $a['kode_pengajuan'] ?> (<?= $a['nama'] . ', ' . $bulan[$a['bulan']] . ' ' . $a['tahun'] ?>)</option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button class="btn btn-success" type="button" id="button_find"><i class="fa fa-search"></i> Cek</button>
                                                        </div>
                                                    </div>
                                                    <div id="display_results"></div>
                                                    <div class='item form-group'>
                                                        <label class='col-form-label col-md-3 col-sm-3 label-align' for='first-name'>Dana Terserap <span class='required'>*</span>
                                                        </label>
                                                        <div class='col-md-6 col-sm-6  form-group has-feedback'>
                                                            <input type='text' class='form-control has-feedback-left ' id='uang_3' name='nominal' required>
                                                            <span class='form-control-feedback left' aria-hidden='true'>Rp.</span>
                                                        </div>
                                                    </div>
                                                    <div class='item form-group'>
                                                        <label class='col-form-label col-md-3 col-sm-3 label-align'>Tanggal Setor <span class='required'>*</span>
                                                        </label>
                                                        <div class='col-md-6 col-sm-6 xdisplay_inputx form-group row has-feedback'>

                                                            <input type='text' name='tgl_setor' class='form-control has-feedback-left' id='datePick3' placeholder='' aria-describedby='inputSuccess2Status4'>
                                                            <span class='fa fa-calendar-o form-control-feedback left' aria-hidden='true'></span>
                                                            <span id='inputSuccess2Status4' class='sr-only'>(success)</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="save_aki" class="btn btn-success">Simpan data</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<!-- /page content -->

<?php include 'foot.php'; ?>
<!-- Datatables -->
<script src="vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
<!-- bootstrap-daterangepicker -->
<script src="vendors/moment/min/moment.min.js"></script>
<script src="vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap-datetimepicker -->
<script src="vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<script type='text/javascript'>
    $(document).ready(function() {
        //$("#search_results").slideUp();
        $("#button_find").click(function(event) {
            event.preventDefault();
            ajax_search();
        });
    });

    function ajax_search() {

        var judul = $("#search_query").val();
        $.ajax({
            url: "cari.php",
            data: "judul=" + judul,
            success: function(data) {
                // jika data sukses diambil dari server, tampilkan di <select id=kota>
                $("#display_results").html(data);
            }
        });

    }
</script>
<script>
    $(document).ready(function() {
        $('#datatable2').DataTable();
        $('#datatable3').DataTable();
        $('#datatable4').DataTable();

        $('#datePick').datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $('#datePick2').datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $('#datePick3').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    });
</script>
<script type="text/javascript">
    var rupiah = document.getElementById('uang');
    var rupiah2 = document.getElementById('uang_2');
    var rupiah3 = document.getElementById('uang_3');

    rupiah.addEventListener('keyup', function(e) {
        rupiah.value = formatRupiah(this.value);
    });
    rupiah2.addEventListener('keyup', function(e) {
        rupiah2.value = formatRupiah(this.value);
    });
    rupiah3.addEventListener('keyup', function(e) {
        rupiah3.value = formatRupiah(this.value);
    });

    /* Fungsi formatRupiah */
    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? rupiah : '');
    }
</script>

<?php
if (isset($_POST['save_bos'])) {

    $id = $uuid;
    $kode = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['kode']));
    $uraian = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['uraian']));
    $periode = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['periode']));
    $nominal = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['nominal']));
    $tahun = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tahun']));
    $tgl_bayar = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tgl_bayar']));
    $nom = preg_replace("/[^0-9]/", "", $nominal);
    // $kasir = htmlspecialchars(mysqli_real_escape_string($conn, $kasir));

    $sql = mysqli_query($conn, "INSERT INTO bos VALUES ('$id', '$kode', '$kode', '$uraian', '$periode', '$nom', '$tahun_ajaran', '$tgl_bayar', '$nama_user', NOW() ) ");
    if ($sql) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Data BOS berhasil tersimpan',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "masuk.php"
                }, millisecondsToWait);

            });
        </script>

    <?php    }
}

if (isset($_POST['save_pes'])) {
    $lembaga = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['lembaga']));
    $bidang = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['bidang']));
    $kode = $lembaga . '.' . $bidang;
    $tgl_bayar = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tgl_bayar']));
    $uraian = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['uraian']));
    $periode = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['periode']));
    $nominal = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['nominal']));
    $tahun = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tahun']));
    $tgl_bayar = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tgl_bayar']));
    $nom = preg_replace("/[^0-9]/", "", $nominal);

    $sql = mysqli_query($conn, "INSERT INTO pesantren VALUES ('$uuid', '$lembaga', '$bidang', '$kode', '$uraian', '$periode', '$nom', '$tahun_ajaran', '$tgl_bayar', NOW() ) ");
    if ($sql) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Pemasukan Pesantern berhasil ditambahkan',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "masuk.php"
                }, millisecondsToWait);

            });
        </script>

    <?php    }
}

if (isset($_POST['save_aki'])) {

    $id = $uuid;
    $kode_pengajuan = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['kode_pengajuan']));
    $nominal = htmlspecialchars(mysqli_real_escape_string($conn, preg_replace("/[^0-9]/", "", $_POST['nominal'])));
    $cair = htmlspecialchars(mysqli_real_escape_string($conn, preg_replace("/[^0-9]/", "", $_POST['cair'])));
    $tgl_setor = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tgl_setor']));
    $sisa = $cair - $nominal;

    $sql = mysqli_query($conn, "INSERT INTO real_sisa VALUES ('$id', '$kode_pengajuan', '$cair', '$nominal', '$sisa', '$tgl_setor', '$tahun_ajaran' ) ");
    if ($sql) { ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Sisa berhasil disimpan',
                    showConfirmButton: false
                });
                var millisecondsToWait = 1000;
                setTimeout(function() {
                    document.location.href = "masuk.php"
                }, millisecondsToWait);

            });
        </script>

<?php    }
}

?>