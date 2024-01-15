<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Daftar PAK</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-folder-open"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Rencana Belanja</li>
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
                                        <th>Kode PAK</th>
                                        <th>Lembaga</th>
                                        <th>Tanggal PAK</th>
                                        <th>Status</th>
                                        <th>Tahun</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data as $a) :
                                        $lmb = $this->db->query("SELECT * FROM lembaga WHERE kode = '$a->lembaga' AND tahun = '$a->tahun' ")->row();
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $a->kode_pak; ?></td>
                                            <td><?= $lmb->nama; ?></td>
                                            <td><?= $a->tgl_pak; ?></td>
                                            <td><?= $a->status == 'selesai' ? "<span class='badge bg-success'>selesai</span>" : $a->status; ?> </td>
                                            <td><?= $a->tahun; ?></td>
                                            <td>
                                                <a href="<?= base_url('account/pakDetail/' . $a->kode_pak) ?>"><button class="btn btn-primary btn-sm"><i class="bx bx-search"></i>
                                                        Cek PAK</button></a>
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