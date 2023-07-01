<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Mutasi Santri</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-link-external"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Mutasi</li>
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
                            <div class="col-md-5">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h6 class="mb-3">Identitas Santri</h6>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table no-margin">
                                        <tr>
                                            <th>NIS</th>
                                            <th>: <?= $sn->nis; ?></th>
                                        </tr>
                                        <tr>
                                            <th>Nama</th>
                                            <th>: <?= $sn->nama; ?></th>
                                        </tr>
                                        <tr>
                                            <th>Alamat</th>
                                            <th>: <?= $sn->desa . ' - ' . $sn->kec . ' - ' . $sn->kab; ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Formal</th>
                                            <th>: <?= $sn->k_formal . ' - ' . $sn->t_formal ?></th>
                                        </tr>
                                        <tr>
                                            <th>Madin</th>
                                            <th>: <?= $sn->k_madin . ' ' . $sn->r_madin ?></th>
                                        </tr>
                                        <tr>
                                            <th>Kamar</th>
                                            <th>: <?= $sn->kamar ?></th>
                                        </tr>
                                        <tr>
                                            <th>Komplek</th>
                                            <th>: <?= $sn->komplek ?></th>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-7 mt-2">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h6 class="mb-3">Hisytory bayar santri</h6>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tgl Bayar</th>
                                                <th>Nominal</th>
                                                <th>Bulan</th>
                                                <th>Tahun</th>
                                                <th>Penerima</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            foreach ($bayar as $r) { ?>
                                                <tr>
                                                    <td><?= $no++; ?></td>
                                                    <td><?= $r->tgl; ?></td>
                                                    <td><?= rupiah($r->nominal); ?></td>
                                                    <td><?= $bulan[$r->bulan]; ?></td>
                                                    <td><?= $r->tahun; ?></td>
                                                    <td><span class="badge bg-success"><?= $r->kasir; ?></span>
                                                    </td>
                                                    <td>
                                                        <a href="<?= base_url('kasir/delBayar/' . $r->id); ?>" class="tbl-confirm" value="Data ini akan dihapus dan akan menghapus data dekosan nya juga"><span class="btn btn-danger btn-sm">Del</span></a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-5 mt-5">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h6 class="mb-3">Input Pembayaran Baru</h6>
                                    </div>
                                </div>
                                <form role="form" action="<?= base_url('kasir/bayarMutasi') ?>" method="POST">
                                    <input type="hidden" name="nis" value="<?= $sn->nis; ?>">
                                    <input type="hidden" name="nama" value="<?= $sn->nama; ?>">
                                    <input type="hidden" name="ttl" value="<?= $tgn->total; ?>">
                                    <input type="hidden" name="masuk" value="<?= $masuk->jml; ?>">
                                    <div class="box-body">
                                        <div class="form-group mb-3">
                                            <label for="exampleInputEmail1">Nominal Biaya Pendidikan</label>
                                            <input type="text" name="nominal_bp" class="form-control form-control-sm uang" id="" placeholder="Masukan nominal" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="exampleInputEmail1">Nominal Dekosan</label>
                                            <input type="text" name="nominal_dks" class="form-control form-control-sm uang" id="" placeholder="Masukan nominal" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="exampleInputPassword1">Tanggal</label>
                                            <input type="text" name="tgl" class="form-control form-control-sm" id="date" placeholder="Password" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="exampleInputPassword1">Bulan Dekosan</label>
                                            <select name="bulan" class="form-control form-control-sm">
                                                <option value=""> -pilih bulan- </option>
                                                <?php
                                                for ($i = 1; $i <= 12; $i++) { ?>
                                                    <option value="<?= $i; ?>"><?= $bulan[$i]; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <button type="submit" name="add" class="btn btn-primary btn-sm"><i class="bx bx-save"></i> Simpan</button>
                                        </div>
                                    </div><!-- /.box-body -->
                                </form>
                            </div>

                            <div class="col-md-7 mt-5">
                                <div class="table-responsive">
                                    <h4 class="box-title">Tanggal Mutasi Santri : <?= $mts->tgl_mutasi; ?></h4>
                                    <table class="table no-margin">
                                        <thead>
                                            <tr>
                                                <th>Biaya Pendidikan</th>
                                                <th>:</th>
                                                <th><?= rupiah($tgbyr) . ' (' . date('M', strtotime($mts->tgl_mutasi)) . ')'; ?> - <i>dikurangi dekosan</i></th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th>:</th>
                                                <th><?= rupiah($tgbyr) . ' / 30 hari = ' . rupiah($tgbyr / 30); ?> (perhari)</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th>:</th>
                                                <th><?= rupiah($tgbyr / 30) . ' x ' . $tglbr . ' (Tgl Mutasi) = '; ?> <b style="color: green; text-decoration: underline;"><?= rupiah(($tgbyr / 30) * $tglbr); ?> (total bayar)</b></th>
                                            </tr>
                                            <tr>
                                                <th>Biaya Dekos Makan</th>
                                                <th>:</th>
                                                <th><?= rupiah($dekos) . ' (sebulan)'; ?></th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th>:</th>
                                                <th><?= rupiah($dekos) . ' / 30 hari = ' . rupiah($dekos / 30); ?> (perhari)</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th>:</th>
                                                <th><?= rupiah($dekos / 30) . ' x ' . $tglbr . ' (Tgl Mutasi) = '; ?> <b style="color: green; text-decoration: underline;"><?= rupiah(($dekos / 30) * $tglbr); ?> (total bayar)</b></th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th>
                                                    <a href="<?= base_url('kasir/vervalMutasi/' . $mts->id_mutasi); ?>" class="btn btn-success btn-sm tbl-confirm" value="Data ini akan dilanjutkan ke sekretariat untuk selanjutnya diterbitkan surat mutasi"><i class="bx bx-check"></i> </i> Verifikasi Tanggungan</button>
                                                        <a href="<?= base_url('kasir/discrb/' . $sn->nis); ?>" class="btn btn-warning btn-sm"><i class="bx bx-search"></i> </i> Cek Perbulan</button>
                                                </th>

                                            </tr>
                                        </thead>
                                    </table>
                                </div><!-- /.table-responsive -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<!--end page wrapper -->