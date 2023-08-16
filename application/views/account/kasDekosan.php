<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Pembantu Kas Dekosan</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-book-content"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Buku Kas</li>
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
                            <!-- <div class="col-md-5">
                                <div class="card radius-10 bg-danger bg-gradient">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <p class="mb-0 text-white">TOTAL PEMBANTU KAS HARIAN</p>
                                                <h4 class="my-1 text-white"><?= rupiah(0); ?></h4>
                                            </div>
                                            <div class="text-white ms-auto font-35"><i class='bx bx-money'></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="example2" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Jenis</th>
                                                <th>Debit</th>
                                                <th>Kredit</th>
                                                <th>Saldo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            $saldo = 0;
                                            $kredit = 0;
                                            $debit = 0;
                                            foreach ($kas as $a) : ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $a->tanggal ?></td>
                                                    <td><?= $a->jenis ?></td>
                                                    <?php
                                                    if ($no == 1) {
                                                        echo "<td> " . rupiah($a->debit) . "</td>";
                                                        echo "<td> " . rupiah($a->kredit) . "</td>";
                                                        $debit = $a->debit;
                                                        $kredit = $a->kredit;
                                                        $saldo = $debit - $kredit;
                                                        echo "<td> " . rupiah($saldo) . "</td>";
                                                    } else {
                                                        // $saldo = $debit - $kredit;
                                                        if ($a->debit != 0) {
                                                            echo "<td> " . rupiah($a->debit) . "</td>";
                                                            echo "<td> " . rupiah($a->kredit) . "</td>";
                                                            $debit = $debit + $a->debit;
                                                            $saldo = $saldo + $a->debit;
                                                            echo "<td> " . rupiah($saldo) . "</td>";
                                                        } else {
                                                            echo "<td> " . rupiah($a->debit) . "</td>";
                                                            echo "<td> " . rupiah($a->kredit) . "</td>";
                                                            $kredit = $kredit + $a->kredit;
                                                            $saldo = $saldo - $a->kredit;
                                                            echo "<td> " . rupiah($saldo) . "</td>";
                                                        }
                                                    }
                                                    ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3">Total</th>
                                                <th><?= rupiah($debit) ?></th>
                                                <th><?= rupiah($kredit) ?></th>
                                                <th><?= rupiah($saldo) ?></th>
                                            </tr>
                                        </tfoot>
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