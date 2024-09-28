<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>
<?php

// $total = $sumA1->total + $sumB1->total + $sumC1->total + $sumA2->total + $sumB2->total + $sumC2->total;
// $pakai = $pakaiA1->nominal + $pakaiB1->nominal + $pakaiC1->nominal  + $pakaiA2->nominal + $pakaiB2->nominal + $pakaiC2->nominal;

try {
    //code...
    $pesern = round(($totalReal->jml / $totalRab->jml) * 100, 0);
} catch (DivisionByZeroError $th) {
    //throw $th;
    $pesern = 0;
}
if ($pesern >= 0 && $pesern <= 25) {
    $bg = 'progress-bar-success';
} elseif ($pesern >= 26 && $pesern <= 50) {
    $bg = 'progress-bar-primary';
} elseif ($pesern >= 51 && $pesern <= 75) {
    $bg = 'progress-bar-warning';
} elseif ($pesern >= 76 && $pesern <= 100) {
    $bg = 'progress-bar-danger';
}

?>
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Daftar RAB Lembaga</div>
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
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">Kode Lembaga</li>
                                    <li class="list-group-item">Nama</li>
                                    <li class="list-group-item">PJ</li>
                                    <li class="list-group-item">No. Hp</li>
                                    <li class="list-group-item">Pelaksanaan</li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">: <?= $lembaga->kode; ?></li>
                                    <li class="list-group-item">: <?= $lembaga->nama; ?></li>
                                    <li class="list-group-item">: <?= $lembaga->pj; ?></li>
                                    <li class="list-group-item">: <?= $lembaga->hp; ?></li>
                                    <li class="list-group-item">: <?= $lembaga->waktu; ?></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group">
                                    <?php foreach ($jenis as $dtJenis) : ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $dtJenis->kode_jns . '. ' . $dtJenis->nama ?> <span><?= rupiah($rabJml[$dtJenis->kode_jns]->jml3); ?></span></li>
                                    <?php endforeach; ?>
                                    <li class=" list-group-item d-flex justify-content-between align-items-center active" aria-current="true">TOTAL RAB <span">
                                            <?= rupiah($totalRab->jml); ?></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="progress" style="height:20px;">
                                    <div class="progress-bar <?= $bg ?> progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="<?= $pesern ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $pesern ?>%"><?= $pesern ?>%</div>
                                </div>

                                <!-- <div class="progress active">
                                    <div class="progress-bar <?= $bg ?> progress-bar-striped" role="progressbar"
                                        aria-valuenow="<?= $pesern ?>" aria-valuemin="0" aria-valuemax="100"
                                        style="width: <?= $pesern ?>%"><?= $pesern ?>%
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example2" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama Barang</th>
                                        <th>Rencana Waktu</th>
                                        <th>QTY</th>
                                        <th>Harga Satuan</th>
                                        <th>Jumlah</th>
                                        <!-- <th>%</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data as $a) : ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $a->kode ?></td>
                                            <td><?= $a->nama ?></td>
                                            <td><?= bulan($a->rencana) ?></td>
                                            <td><?= $a->qty . ' ' . $a->satuan ?></td>
                                            <td><?= rupiah($a->harga_satuan) ?></td>
                                            <td><?= rupiah($a->total) ?></td>
                                            <!-- <td><?= round($rls->vol / $a->qty * 100, 1); ?>%</td> -->
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Nama Barang</th>
                                        <th>Rencana Waktu</th>
                                        <th>QTY</th>
                                        <th>Harga Satuan</th>
                                        <th>Jumlah</th>
                                        <!-- <th>%</th> -->
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->
</div>
<!--end page wrapper -->