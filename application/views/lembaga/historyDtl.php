<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Detail History Pengajuan</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="#"><i class="bx bx-history"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">History</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="row invoice-info">
                            <div class="col-sm-5">
                                <table class="countries_list">
                                    <tbody>
                                        <tr>
                                            <th>Lembaga</th>
                                            <th>: <?= $lembaga->nama ?></th>
                                        </tr>
                                        <tr>
                                            <th>Pelakasana</th>
                                            <th>: <?= $lembaga->pelaksana ?></th>
                                        </tr>
                                        <tr>
                                            <th>PJ/HP</th>
                                            <th>: <?= $lembaga->pj . ' / ' . $lembaga->hp ?></th>
                                        </tr>
                                        <tr>
                                            <th>Pelaksanaan</th>
                                            <th>: <?= $lembaga->waktu ?></th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.col -->

                            <!-- /.col -->
                            <div class="col-sm-4">
                                <center>
                                    <p style="font-size: 23px;">
                                        <b>NOMINAL PENGAJUAN</b><br>
                                        <b style="color: limegreen;"><?= rupiah($real->jml + $real_sm->jml); ?>
                                        </b>
                                    </p>
                                </center>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-3 invoice-col">
                                <center>
                                    <b>Status Pengajuan</b><br>
                                    Bendahara :
                                    <?= $a->verval == 1 ? "<span class='badge bg-success'><i class='bx bx-check'></i> sudah</span>" : "<span class='badge bg-danger'><i class='bx bx-times'></i> belum</span>"; ?><br>
                                    Perencanaan :
                                    <?= $a->apr == 1 ? "<span class='badge bg-success'><i class='bx bx-check'></i> sudah</span>" : "<span class='badge bg-danger'><i class='bx bx-times'></i> belum</span>"; ?><br>
                                    Cair :
                                    <?= $a->cair == 1 ? "<span class='badge bg-success'><i class='bx bx-check'></i> sudah</span>" : "<span class='badge bg-danger'><i class='bx bx-times'></i> belum</span>"; ?><br>
                                    SPJ : <?php if ($a->spj == 0) { ?>
                                        <span class="badge bg-danger"><i class="bx bx-times"></i> belum upload</span>
                                    <?php } else if ($a->spj == 1) { ?>
                                        <span class="badge bg-warning btn-xs"><i
                                                class="bx bx-spinner fa-refresh-animate"></i>
                                            proses verifikasi</span>
                                    <?php } else { ?>

                                        <span class="badge bg-success"><i class="bx bx-check"></i> sudah selesai</span>
                                        <a href="../institution/spj_file/<?= $spj->file_spj; ?>"> <span
                                                class="badge bg-warning"><i class="bx bx-download"> Unduh
                                                    SPJ</i></span></a>

                                    <?php } ?>
                                </center>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <hr>
                                <h3>History perjalanan pengajuan</h3>
                                <div class="list-group">
                                    <?php foreach ($history as $vv) { ?>
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h5 class="mb-1"><?= $vv->pesan; ?></h5>
                                                <small class="text-muted"><span class="badge bg-primary"><?= $vv->stts ?></span></small>
                                            </div>
                                            <p class="mb-1">Oleh : <?= $vv->user ?></p> <small class="text-muted">Pada :
                                                <span><?= $vv->tgl_verval; ?></small>
                                        </a>
                                    <?php } ?>
                                </div>

                                <div class="table-responsive mt-5">
                                    <table id="example" class="table table-striped table-bordered table-sm"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode RAB</th>
                                                <th>Periode</th>
                                                <th>PJ</th>
                                                <!-- <th>Nominal</th> -->
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            $kode_p = $a->kode_pengajuan;
                                            if ($data->cair == 1) {
                                                $dt_bos = $this->db->query("SELECT * FROM realis WHERE kode_pengajuan = '$kode_p' AND tahun = '$tahun' ")->result();
                                                $tt = $this->db->query("SELECT SUM(nominal) AS tot FROM realis WHERE kode_pengajuan = '$kode_p' AND tahun = '$tahun' ")->row();
                                            } else {
                                                $dt_bos = $this->db->query("SELECT * FROM real_sm WHERE kode_pengajuan = '$kode_p' AND tahun = '$tahun' ")->result();
                                                $tt = $this->db->query("SELECT SUM(nominal) AS tot FROM real_sm WHERE kode_pengajuan = '$kode_p' AND tahun = '$tahun' ")->row();
                                            }
                                            foreach ($dt_bos as $ade) { ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $ade->kode ?></td>
                                                    <td><?= $bulan[$ade->bulan] . ' ' . $ade->tahun ?></td>
                                                    <td><?= $ade->pj ?></td>
                                                    <!-- <td><?= rupiah($ade->nominal) ?></td> -->
                                                    <td><?= $ade->ket ?></td>
                                                    <!-- <td>
                                            <a onclick="return confirm('Yakin akan dihapus ?. Menghapus data ini akan menghapus data realisasi juga')" href="<?= 'hapus.php?kd=rab&id=' . $ade->id_realis; ?>"><span class="fa fa-trash-o text-danger"> Hapus</span></a>
                                        </td> -->
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <!-- <tfoot>
                                            <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                                <th colspan="4">SUB JUMLAH</th>
                                                <th colspan="2"><?= rupiah($tt->tot) ?></th>
                                            </tr>
                                        </tfoot> -->
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