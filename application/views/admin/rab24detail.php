<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<style>
    .wrap-text {
        white-space: normal;
        word-wrap: break-word;
    }
</style>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">RAB 2023/2024</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-message-detail"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">RAB</li>
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
                            <div class="col-md-4">
                                <div class="alert border-0 border-start border-5 border-success fade show py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="font-35 text-success"><i class='bx bxs-check-circle'></i></div>
                                        <div class="ms-3">
                                            <h6 class="mb-0 text-success"><?= rupiah($lembaga->pagu) ?></h6>
                                            <div>Pagu Anggaran</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="alert border-0 border-start border-5 border-danger fade show py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="font-35 text-danger"><i class='bx bx-info-square'></i></div>
                                        <div class="ms-3">
                                            <h6 class="mb-0 text-danger"><?= rupiah($rab24Total->row('jml')) ?></h6>
                                            <div>Total RAB Baru</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">

                                <a href="<?= base_url('admin/rabUploadSnc24/' . $lembaga->kode) ?>" value="RAB akan disinkron. Sebelumnya pastikan semuanya sudah fix" class="btn btn-success btn-sm mb-1 tbl-confirm"><i class="bx bx-refresh"></i>Sinkron RAB</a>
                                <a href="<?= base_url('admin/rabDone24/' . $lembaga->kode) ?>" value="Pengajuan akan diselesaikan" class="btn btn-primary btn-sm mb-1 tbl-confirm"><i class="bx bx-check-circle"></i>Selesaikan</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="" class="table table-bordered mb-0 table-sm" style="width:100%">
                                <thead>
                                    <tr>
                                        <!-- <th>No</th> -->
                                        <th>Program</th>
                                        <th>Kode RAB</th>
                                        <th>Nama Barang</th>
                                        <th>QTY</th>
                                        <th>Hrg Satuan</th>
                                        <th>Total</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rab as $kodePak => $list) : ?>
                                        <?php foreach ($list as $item) : ?>
                                            <?php $no = 1; ?>
                                            <tr>
                                                <!-- <td><?= $no++ ?></td> -->
                                                <?php if ($item === reset($list)) : ?>
                                                    <td class="wrap-text" rowspan="<?= count($list); ?>">
                                                        <?= '#' . $kodePak . ' - ' . $item->nama_dppk; ?><br>
                                                        <b>Jml Item : <?= count($list) ?></b><br>

                                                    </td>
                                                <?php endif; ?>
                                                <td><?= $item->kode ?></td>
                                                <td><?= $item->nama ?></td>
                                                <td><?= $item->qty . ' ' . $item->satuan ?></td>
                                                <td><?= rupiah($item->harga_satuan) ?></td>
                                                <td><?= rupiah($item->total) ?></td>
                                                <!-- <td><?= round($rls->vol / $item->qty * 100, 1); ?>%</td> -->
                                                <td>

                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
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