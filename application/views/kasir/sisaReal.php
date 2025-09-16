<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Saldo Realisasi</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-money"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Pemasukan</li>
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
                                        <th>Kode</th>
                                        <th>Lembaga</th>
                                        <th>Nominal</th>
                                        <th>Terserap</th>
                                        <th>Sisa</th>
                                        <th>Inp</th>
                                        <th>Act</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data as $a) :
                                        $lembaga = $this->db->query("SELECT lembaga.nama FROM pengajuan JOIN lembaga ON pengajuan.lembaga=lembaga.kode WHERE kode_pengajuan = '$a->kode_pengajuan' AND lembaga.tahun='$tahun' ")->row();
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $a->kode_pengajuan ?></td>
                                            <td><?= $lembaga->nama ?></td>
                                            <td><?= rupiah($a->dana_cair) ?></td>
                                            <td><?= rupiah($a->dana_serap) ?></td>
                                            <td><?= rupiah($a->sisa) ?></td>
                                            <td><?= $a->kasir ?></td>
                                            <td>
                                                <a href="<?= 'tarikSisa/' . $a->id_sisa; ?>" class="btn btn-success btn-sm tbl-confirm" value="Sisa dana ini akan tarik. Dan pengajuan berikut nya akan dibuka untuk lembaga terkait"><i class="bx bx-check"></i>Setujui</a>
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