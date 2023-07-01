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
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="<?= base_url('lembaga/pakDetail/' . $pak->kode_pak); ?>" class="btn btn-light btn-sm"><i
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

                        <div class="row">
                            <div class="col-md-6">
                                <table class="countries_list">
                                    <tbody>
                                        <tr>
                                            <td>Nama Lembaga</td>
                                            <td> : <?= $lembaga->nama ?></td>
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
                                            <td> : <?= rupiah($rel->jml) ?></td>
                                        </tr>
                                        <tr>
                                            <td>Sisa</td>
                                            <td> : <?= rupiah($rab->total - $rel->nominal) ?>
                                                <b><i><?= '(' . ($rab->total - $rel->nominal) / $rab->harga_satuan . ' ' . $rab->satuan . ')' ?></i></b>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <?= form_open('lembaga/addEditPak'); ?>
                                <label for="">QTY yang akan diajukan</label>
                                <input type="number" class="form-control" name="jml" required>
                                <input type="hidden" class="form-control" name="sisa"
                                    value="<?= $rab->qty - $relJml->jml; ?>">
                                <input type="hidden" class="form-control" name="kd_rab" value="<?= $rab->kode ?>">
                                <input type="hidden" class="form-control" name="kd_pak" value="<?= $pak->kode_pak ?>">
                                <small class="text-danger">* QTY ini tidak boleh melebihi dari
                                    QTY yang
                                    tersisa</small><br><br>
                                <button type="submit" class="btn btn-sm btn-success pull-right"><i
                                        class="fa fa-check"></i> Simpan</button>
                                <?= form_close(); ?>
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