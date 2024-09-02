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
                            <div class="col-md-8">
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
                                <div class="row">
                                    <div class="col">
                                        <div class="card radius-10 border-start border-0 border-3 border-info">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <p class="mb-0 text-secondary">Tanggungan</p>
                                                        <h4 class="my-1 text-info"><?= rupiah($tanggungan->jml) ?></h4>
                                                        <!-- <p class="mb-0 font-13">Jumlah Tanggunagn dalam 1 tahun</p> -->
                                                    </div>
                                                    <!-- <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                                                        <i class='bx bxs-cart'></i>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="card radius-10 border-start border-0 border-3 border-danger">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <p class="mb-0 text-secondary">Belum Bayar</p>
                                                        <h4 class="my-1 text-danger">
                                                            <?= rupiah($tanggungan->jml - $masuk->jml) ?></h4>
                                                        <!-- <p class="mb-0 font-13">Sisa tanggungan yang belum lunas</p> -->
                                                    </div>
                                                    <!-- <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                                                        <i class='bx bxs-wallet'></i>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="card radius-10 border-start border-0 border-3 border-success">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <p class="mb-0 text-secondary">Pembayaran</p>
                                                        <h4 class="my-1 text-success"><?= rupiah($masuk->jml) ?></h4>
                                                        <!-- <p class="mb-0 font-13">Jumlah yang sudah dibayar</p> -->
                                                    </div>
                                                    <!-- <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                                                        <i class='bx bxs-bar-chart-alt-2'></i>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="table-responsive table-sm">
                                    <table class="table table-striped">
                                        <tr>
                                            <th colspan="4" class="text-center">Tagihan Santri</th>
                                        </tr>
                                        <?php
                                        foreach ($tgn as $a) {
                                            $cek = $this->db->query("SELECT * FROM pembayaran WHERE nis = '$a->nis' AND tahun = '$a->tahun' AND bulan = '$a->bulan' ")->row();
                                            $snt = $this->db->query("SELECT * FROM tb_santri WHERE nis = '$a->nis' ")->row();
                                            $stts = $cek ? "<i class='bx bx-check-circle text-success'></i>" : "<i class='bx bx-x-circle text-danger'></i>";
                                        ?>
                                            <tr>
                                                <th><?= $bulan[$a->bulan]; ?></th>
                                                <th><?= rupiah($a->nominal); ?></th>
                                                <th><?= $stts ?></th>
                                                <th>
                                                    <button class="btn btn-info btn-sm edit-btn" data-id="<?= $a->id_tanggungan ?>" data-nis="<?= $a->nis ?>" data-nama="<?= $snt->nama ?>" data-nominal="<?= $a->nominal ?>" data-bulan="<?= $a->bulan ?>" data-tahun="<?= $a->tahun ?>">Bayar</button>
                                                </th>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
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
                                                        <a href="<?= base_url('kasir/editBayar/' . $r->id); ?>"><span class="btn btn-warning btn-sm">Edit</span></a>
                                                        <a href="<?= base_url('kasir/delBayar/' . $r->id); ?>" class="tbl-confirm" value="Data ini akan dihapus dan akan menghapus data dekosan nya juga"><span class="btn btn-danger btn-sm">Del</span></a>
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
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<!--end page wrapper -->

<!-- <form action="<?= base_url('kasir/addbayar') ?>" method="POST">
    <input type="hidden" name="nis" value="<?= $sn->nis; ?>">
    <input type="hidden" name="nama" value="<?= $sn->nama; ?>">
    <input type="hidden" name="ttl" value="<?= $tgn->total; ?>">
    <input type="hidden" name="masuk" value="<?= $masuk->jml; ?>">
    <div class="box-body">
        <div class="form-group mb-2">
            <label for="exampleInputEmail1">Nominal Pembyaran</label>
            <input type="text" name="nominal" class="form-control form-control-sm uang" id="" placeholder="Masukan nominal" required>
        </div>
        <div class="form-group mb-2">
            <label for="exampleInputPassword1">Tanggal</label>
            <input type="text" id="date" name="tgl" class="form-control form-control-sm" id="exampleInputPassword1" placeholder="Password" required>
        </div>
        <div class="form-group mb-2">
            <label for="exampleInputPassword1">Dekosan</label><br>
            <input type="radio" name="dekos" value="Y" required> Ya
            <input type="radio" name="dekos" value="T" required> Tidak
        </div>
        <div class="form-group mb-2">
            <label for="exampleInputPassword1">Bulan Dekosan</label>
            <select name="bulan" class="form-control form-control-sm">
                <option value=""> -pilih bulan- </option>
                <?php
                for ($i = 1; $i <= 12; $i++) { ?>
                    <option value="<?= $i; ?>"><?= $bulan[$i]; ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group mb-2">
            <button type="submit" class="btn btn-primary btn-sm"><i class="bx bx-save"></i> Simpan</button>
        </div>
    </div>

</form> -->
<div class="modal fade" id="formBayar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Bayar Tagihan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('kasir/addBayar') ?>" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label for="">NIS</label>
                        <input type="text" name="nis" class="form-control form-control-sm" id="edit-nis" readonly>
                    </div>
                    <div class="form-group mb-2">
                        <label for="">Nama</label>
                        <input type="text" name="nama" class="form-control form-control-sm" id="edit-nama" readonly>
                    </div>
                    <div class="form-group mb-2">
                        <label for="">Nominal</label>
                        <input type="text" name="nominal" class="form-control form-control-sm uang" id="edit-nominal" readonly>
                    </div>
                    <div class="form-group mb-2">
                        <label for="">Bulan</label>
                        <input type="text" name="bulan" class="form-control form-control-sm" id="edit-bulan" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="">Tahun</label>
                        <input type="text" name="tahun" class="form-control form-control-sm" id="edit-tahun" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="">Tanggal Bayar</label>
                        <input type="text" name="tgl" class="form-control form-control-sm" id="date" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="">Dekosan</label><br>
                        <input type="radio" name="dekos" value="Y" required> Ya
                        <input type="radio" name="dekos" value="T" required> Tidak
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan Bayar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= base_url('vertical/'); ?>assets/js/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Open modal and populate fields
        $('.edit-btn').on('click', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            var nominal = $(this).data('nominal');
            var nis = $(this).data('nis');
            var tahun = $(this).data('tahun');
            var bulan = $(this).data('bulan');

            $('#edit-id').val(id);
            $('#edit-nama').val(nama);
            $('#edit-nominal').val(nominal);
            $('#edit-nis').val(nis);
            $('#edit-tahun').val(tahun);
            $('#edit-bulan').val(bulan);

            $('#formBayar').modal('show');
        });
    })
</script>