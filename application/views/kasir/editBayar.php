<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Tanggungan Santri</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-shopping-bag"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Tanggungan</li>
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
                            <div class="col-md-7">
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
                                        <tr>
                                            <th>Tempat Kos</th>
                                            <th>: <?= $tmpKos[$sn->t_kos] ?></th>
                                        </tr>
                                        <tr>
                                            <th>Keterangan</th>
                                            <th>: <?= $kter[$sn->ket] ?></th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-5 mt-2">
                                <form action="<?= base_url('kasir/saveEditBayar') ?>" method="POST">
                                    <input type="hidden" name="id" value="<?= $data->id; ?>">
                                    <input type="hidden" name="nis" value="<?= $data->nis; ?>">
                                    <div class="box-body">
                                        <div class="form-group mb-2">
                                            <label for="exampleInputEmail1">Nominal Pembyaran</label>
                                            <input type="text" name="nominal" class="form-control form-control-sm uang" id="" placeholder="Masukan nominal" required value="<?= $data->nominal ?>">
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="exampleInputPassword1">Tanggal</label>
                                            <input type="text" id="date" name="tgl" class="form-control form-control-sm" id="exampleInputPassword1" placeholder="Password" required value="<?= $data->tgl ?>">
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="exampleInputPassword1">Bulan Dekosan</label>
                                            <select name="bulan" class="form-control form-control-sm">
                                                <option value=""> -pilih bulan- </option>
                                                <?php
                                                for ($i = 1; $i <= 12; $i++) { ?>
                                                    <option <?= $data->bulan == $i ? 'selected' : '' ?> value="<?= $i; ?>"><?= $bulan[$i]; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group mb-2">
                                            <button type="submit" class="btn btn-primary btn-sm"><i class="bx bx-save"></i> Simpan</button>
                                        </div>
                                    </div><!-- /.box-body -->

                                </form>
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