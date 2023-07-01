<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Pemasukan Pesantren</div>
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
                        <div class="row">
                            <div class="col-md-10">
                                <div class="card radius-10 bg-success bg-gradient">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <p class="mb-0 text-white">TOTAL PEMASUKAN DARI SISA REALISASI</p>
                                                <h4 class="my-1 text-white"><?= rupiah($sumSisa->sisa); ?></h4>
                                            </div>
                                            <div class="text-white ms-auto font-35"><i class='bx bx-money'></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-md-2">
                                <div class="card radius-10 bg-primary bg-gradient">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="text-white ms-auto font-35">
                                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPes"><i class="bx bx-plus-circle"></i>
                                                    Tambah</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>

                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Pnj</th>
                                        <th>Lembaga</th>
                                        <th>Periode</th>
                                        <th>Dana Cair</th>
                                        <th>Terserap</th>
                                        <th>Sisa</th>
                                        <th>Tgl Setor</th>
                                        <th>Act</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($sisa as $a) : ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $a->kode_pengajuan ?></td>
                                            <td><?= $a->nama ?></td>
                                            <td><?= $bulan[$a->bulan] ?></td>
                                            <td><?= rupiah($a->dana_cair) ?></td>
                                            <td><?= rupiah($a->dana_serap) ?></td>
                                            <td><?= rupiah($a->dana_cair - $a->dana_serap) ?></td>
                                            <td><?= date('d-M-Y', strtotime($a->tgl_setor)) ?></td>
                                            <td>
                                                <a href="<?= 'delSisa/' . $a->id_sisa; ?>" class="btn btn-danger btn-sm tombol-hapus"><i class="bx bx-trash"></i>
                                                    Del</a>
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