<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Daftar Realisasi Belanja</div>
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
                    <a href="<?= base_url('account/realisDetail/' . $lem->kode); ?>" class="btn btn-light btn-sm"><i
                            class="bx bx-subdirectory-left"></i>
                        Kembali</a>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row row-cols-1 row-cols-md-1 row-cols-lg-12 row-cols-xl-12">

            <div class="col">
                <div class="card border-success border-bottom border-3 border-0">
                    <div class="card-body">
                        <h5 class="card-title text-success">Realisasi item RAB : <?= $rab->nama; ?></h5>

                        <table class="countries_list">
                            <tbody>
                                <tr>
                                    <td>Nama Lembaga</td>
                                    <td> : <?= $lem->nama ?></td>
                                </tr>
                                <tr>
                                    <td>Jenis Belanja</td>
                                    <td> : <?= $rab->jenis ?></td>
                                </tr>
                                <tr>
                                    <td>Kode Item</td>
                                    <td> : <?= $rab->kode ?></td>
                                </tr>
                                <tr>
                                    <td>Nama Item</td>
                                    <td> : <?= $rab->nama ?></td>
                                </tr>
                                <tr>
                                    <td>Anggaran RAB</td>
                                    <td> : <?= rupiah($rab->total) ?>
                                        <b><i><?= '(' . rupiah($rab->harga_satuan) . ' x ' . $rab->qty . ' ' . $rab->satuan . ')' ?></i></b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Realisasi</td>
                                    <td> : <?= rupiah($rel->nominal) ?></td>
                                </tr>
                                <tr>
                                    <td>Sisa</td>
                                    <td> : <?= rupiah($rab->total - $rel->nominal) ?>
                                        <b><i><?= '(' . ($rab->total - $rel->nominal) / $rab->harga_satuan . ' ' . $rab->satuan . ')' ?></i></b>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Pembelajaan</td>
                                    <td>
                                        <br>
                                        <?php $prc = round(($rel->nominal / $rab->total) * 100, 0); ?>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                                role="progressbar" style="width: <?= $prc ?>%"
                                                aria-valuenow="<?= $prc ?>" aria-valuemin="0" aria-valuemax="100">
                                                <?= $prc ?>%</div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="d-flex align-items-center gap-2">

                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card border-danger border-bottom border-3 border-0">
                    <div class="card-body">
                        <h5 class="card-title text-danger">Daftar Realisasi item RAB</h5>
                        <div class="table-responsive">
                            <table id="example2" class="table table-striped table-bordered">
                                <thead>
                                    <tr style="color: white; background-color: #FD3B56; font-weight: bold;">
                                        <th>No</th>
                                        <th>Kode RAB</th>
                                        <th>Tanggal</th>
                                        <th>PJ</th>
                                        <th>Nominal</th>
                                        <th>Ket</th>
                                        <!-- <th>#</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($relData as $r1) { ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $r1->kode ?></a></td>
                                        <td><?= $r1->tgl ?></a></td>
                                        <td><?= $r1->pj ?></a></td>
                                        <td><?= rupiah($r1->nominal) ?></td>
                                        <td><?= $r1->ket ?></a></td>
                                        <!-- <td><a onclick="return confirm('Yakin akan dihapus ?')"
                                                href="<?= 'hapus.php?kd=del_real&id=' . $r1->id_realis ?>"><span
                                                    class="fa fa-trash"></span> Hapus</a></td> -->
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr style="color: white; background-color: #FD3B56; font-weight: bold;">
                                        <th colspan="4">TOTAL</th>
                                        <th colspan="2"><?= rupiah($rel->nominal); ?></th>
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
<!--end page wrapper -->