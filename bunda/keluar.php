<?php include 'head.php'; ?>

<!-- [ Main Content ] start -->
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Pengeluaran </h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Pengeluaran </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Data Pengeluaran</h5>
                        <a href="add_keluar.php">
                            <button class="btn btn-danger float-right btn-sm"><i class="fa fa-plus"></i> Tambah Pengeluaran</button>
                        </a>
                    </div>
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover  table-sm" id="example">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Uraian</th>
                                        <th>Penerima</th>
                                        <th>Nominal</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $sql = mysqli_query($conn, "SELECT * FROM kas WHERE ket = 'keluar' ORDER BY at DESC ");
                                    while ($r = mysqli_fetch_assoc($sql)) { ?>
                                        <tr>
                                            <td><?= $no++; ?> </td>
                                            <td><?= $r['tgl']; ?></td>
                                            <td><?= $r['uraian']; ?></td>
                                            <td><?= $r['penyetor']; ?></td>
                                            <td><?= rupiah($r['nominal']); ?></td>
                                            <td>
                                                <a href="edit_keluar.php?id=<?= $r['id_kas']; ?>"><button class="btn btn-warning btn-sm">Edit</button></a>
                                                <a onclick="return confirm('Yakin akan dihapus ?')" href="hapus.php?kd=out&id=<?= $r['id_kas']; ?>"><button class="btn btn-danger btn-sm">Hapus</button></a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>
<!-- [ Main Content ] end -->
<?php include 'foot.php' ?>