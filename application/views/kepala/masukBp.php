<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Pemasukan Pembayaran Biaya Pendidikan</div>
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

                        <div class="card radius-10 bg-success bg-gradient">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-white">TOTAL PEMASUKAN DARI BP</p>
                                        <h4 class="my-1 text-white"><?= rupiah($sumBp->nominal); ?></h4>
                                    </div>
                                    <div class="text-white ms-auto font-35"><i class='bx bx-money'></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Tanggal Bayar</th>
                                        <th>Nominal</th>
                                        <th>Tahun Ajaran</th>
                                        <th>Penerima</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data as $ls_jns) : ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $ls_jns->nama; ?></td>
                                        <td><?= $ls_jns->tgl; ?></td>
                                        <td><?= rupiah($ls_jns->nominal); ?></td>
                                        <td><?= $ls_jns->tahun; ?></td>
                                        <td><?= $ls_jns->kasir; ?></td>
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