<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Pembayaran Tanggungan Santri</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-shopping-bag"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Tanggungan</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <form action="<?= base_url('import/save') ?>" method="POST">
                            <button type="submit" class="btn btn-primary float-right">Upload Proses</button>
                        </form>
                        <div class="table-responsive mt-2">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIS</th>
                                        <th>Tanggal</th>
                                        <th>Nominal</th>
                                        <th>Bulan</th>
                                        <th>Tahun</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 0;
                                    foreach ($sheetData as $row):
                                        $no++;
                                        if ($no == 1) continue; // skip header
                                    ?>
                                        <tr>
                                            <td><?= $row['A'] ?></td>
                                            <td><?= $row['B'] ?></td>
                                            <td><?= $row['C'] ?></td>
                                            <td><?= $row['D'] ?></td>
                                            <td><?= $row['E'] ?></td>
                                            <td><?= $row['F'] ?></td>
                                        </tr>
                                        <!-- Modal -->

                                    <?php endforeach ?>
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