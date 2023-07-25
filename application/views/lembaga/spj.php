<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data SPJ Pengajuan</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-notepad"></i></a>
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

                        <div class="table-responsive mt-3">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                        <th>No</th>
                                        <th>Kode Pengajuan</th>
                                        <th>Periode</th>
                                        <th>Nominal Pengajuan</th>
                                        <th>Status</th>
                                        <th>File</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data as $ls_jns) :
                                        $kd_pj = $ls_jns->kode_pengajuan;
                                        $jml = $this->db->query("SELECT SUM(nominal) AS jml FROM real_sm WHERE kode_pengajuan = '$kd_pj' AND tahun = '$tahun' ")->row();
                                        $jml2 = $this->db->query("SELECT SUM(nominal) AS jml FROM realis WHERE kode_pengajuan = '$kd_pj' AND tahun = '$tahun' ")->row();
                                        $pjan = $jml->jml + $jml2->jml;

                                        if (preg_match("/DISP./i", $kd_pj)) {
                                            $rt = "<span class='badge bg-danger'>DISPOSISI</span>";
                                        } else {
                                            $rt = '';
                                        }
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $ls_jns->kode_pengajuan . ' ' . $rt; ?></td>
                                            <td><?= $bulan[$ls_jns->bulan] . ' ' . $ls_jns->tahun; ?></td>
                                            <td><?= rupiah($pjan); ?></td>
                                            <td>
                                                <?php if ($ls_jns->stts == 0) { ?>
                                                    <span class="badge bg-danger"><i class="bx bx-no-entry"></i> belum
                                                        upload</span>
                                                    <?php if ($ls_jns->b_cair == 1) { ?>
                                                        | <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#stt<?= $ls_jns->id_spj; ?>"><i class="bx bx-upload"></i>
                                                            Upload berkas SPJ</button>
                                                    <?php } ?>
                                                <?php } else if ($ls_jns->stts == 1) { ?>
                                                    <span class="badge bg-warning"><i class="bx bx-recycle"></i>
                                                        proses verifikasi</span>
                                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#stt<?= $ls_jns->id_spj; ?>"><i class="bx bx-upload"></i>
                                                        Upload ulang berkas SPJ</button>
                                                <?php } else { ?>
                                                    <span class="badge bg-success"><i class="bx bx-check"></i> sudah
                                                        selesai</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php if ($ls_jns->stts == 1) { ?>
                                                    <a href="<?= 'file_spj/' . $ls_jns->file_spj ?>"><i class="bx bx-download"></i> <?= $ls_jns->file_spj ?></a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <!-- Modal -->
                                        <div class="modal fade" id="stt<?= $ls_jns->id_spj; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form action="<?= base_url('lembaga/uploadSpj'); ?>" method="post" class="" enctype="multipart/form-data">
                                                    <input type="hidden" name="bulan" value="<?= $ls_jns->bulan; ?>">
                                                    <input type="hidden" name="tahun" value="<?= $ls_jns->tahun; ?>">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Upload Berkas SPJ
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="inputEmail3" class="col-sm-3 control-label">Kode
                                                                    Pengajuan</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" name="kode" class="form-control" value="<?= $ls_jns->kode_pengajuan; ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <div class="form-group">
                                                                <label for="inputEmail3" class="col-sm-3 control-label">Pilih berkas</label>
                                                                <div class="col-sm-9">
                                                                    <input type="file" class="form-control" name="f_rin" required>
                                                                </div>
                                                            </div>
                                                            <br><br>
                                                            <b style="color: red;"><i>Catatan :</i></b><br>
                                                            <ul>
                                                                <li> <span style="color: red;"><i>Untuk Upload berkas SPJ
                                                                            dilakukan
                                                                            1
                                                                            kali setiap pengajuan. Jika ada penguploadan
                                                                            baru,
                                                                            maka
                                                                            file lama akan terhapus</i>
                                                                    </span>
                                                                </li>
                                                                <li> <span style="color: red;"><i>File yang doperbolehkan hanya PDF (.pdf). Max file 5
                                                                            Mb</i>
                                                                    </span>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-success"><i class="bx bx-save"></i> Simpan berkas</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </div>
    <!--end page wrapper -->