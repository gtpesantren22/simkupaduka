<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Analisis Pengeluaran</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-line-chart-down"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Analisis</li>
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
                            <table id="myTable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Ket</th>
                                        <th>RAB</th>
                                        <th>Terpakai</th>
                                        <th>Sisa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($keluar as $a) : ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $a->ket; ?></td>
                                            <td><?= rupiah($a->total_rab); ?></td>
                                            <td class="amount"><?= rupiah($a->pakai); ?></td>
                                            <td><?= rupiah($a->total_rab - $a->pakai); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <tfoot>
                                    <tr>
                                        <th colspan="3">TOTAL</th>
                                        <th colspan="2" id="total"></th>
                                    </tr>
                                </tfoot>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive">
                            <table id="myTable2" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Ket</th>
                                        <th>Pagu</th>
                                        <th>Terpakai</th>
                                        <th>Sisa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($dekos as $a) : ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td>Pengeluaran Dekosan</td>
                                            <td><?= rupiah(0); ?></td>
                                            <td class="amount2"><?= rupiah($a->nominal); ?></td>
                                            <td><?= rupiah(0 - $a->nominal); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php
                                    $no = 2;
                                    foreach ($nikmus as $a) : ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td>Pengeluaran Nikmus</td>
                                            <td><?= rupiah(73000000); ?></td>
                                            <td class="amount2"><?= rupiah($a->nom_kriteria + $a->transport + $a->sopir); ?></td>
                                            <td><?= rupiah(73000000 - ($a->nom_kriteria + $a->transport + $a->sopir)); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php
                                    $no = 3;
                                    foreach ($pengajuanPsb as $a) : ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td>Pengeluaran PSB</td>
                                            <td><?= rupiah(0); ?></td>
                                            <td class="amount2"><?= rupiah($a->jml); ?></td>
                                            <td><?= rupiah(0 - $a->jml); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3">TOTAL</th>
                                        <th colspan="2" id="total2"></th>
                                    </tr>
                                </tfoot>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/accounting.js/0.4.1/accounting.min.js"></script>
<script>
    // Fungsi untuk menghitung total dan menampilkan
    function calculateTotal(idTable, idAmount, idTotal) {
        var table = document.getElementById(idTable);
        var amounts = table.getElementsByClassName(idAmount);

        var total = 0;
        for (var i = 0; i < amounts.length; i++) {
            // Menghapus "Rp." dan mengonversi tanda titik ribuan
            var amountValue = amounts[i].textContent.replace('Rp. ', '').replace(/\./g, '').replace(',', '.');
            total += parseFloat(amountValue) || 0; // Menambahkan nilai 0 jika parsing gagal
        }

        // Tampilkan total dengan format Rupiah
        document.getElementById(idTotal).textContent = 'Rp. ' + accounting.formatMoney(total, "", 0, ".", ",");
    }

    // Panggil fungsi saat halaman dimuat
    calculateTotal('myTable', 'amount', 'total');
    calculateTotal('myTable2', 'amount2', 'total2');
</script>