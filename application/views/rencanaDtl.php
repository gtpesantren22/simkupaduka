<?php
include 'lembaga/head.php';
$lembg = $this->db->query("SELECT * FROM lembaga WHERE kode = '$pengajuan->lembaga' AND tahun = '$pengajuan->tahun' ")->row();
?>
<link href="<?= base_url(''); ?>assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Pengajuan Lembaga</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-wallet"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Realisasi</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card radius-10">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0">Rincian Pengajuan</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-5">
                                <table class="table table-striped">
                                    <tr>
                                        <th>Kode Pengajuan</th>
                                        <td><?= $pengajuan->kode_pengajuan; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Bulan</th>
                                        <td><?= bulan($pengajuan->bulan); ?></td>
                                    </tr>
                                    <tr>
                                        <th>Tahun</th>
                                        <td><?= $pengajuan->tahun; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Lembaga</th>
                                        <td><?= $lembg->nama; ?></td>
                                    </tr>
                                </table>
                            </div>
                            <?php if ($pengajuan->apr == 0) { ?>
                                <div class="col-md-2">
                                    <a href="<?= base_url('pengajuan/vervalRencana/' . $pengajuan->kode_pengajuan) ?>" class="btn btn-success tbl-confirm" value="Pengajuan akan diverfikasi perencanaan"><i class="bx bx-check-circle"></i> Verifikasi Pengajuan</a>
                                </div>
                                <div class="col-md-5">
                                    <form action="<?= base_url('pengajuan/tolakRencana') ?>" method="post">
                                        <input type="hidden" name="kode_pengajuan" value="<?= $pengajuan->kode_pengajuan ?>">
                                        <h5>Form penolakan pengajuan</h5>
                                        <div class="form-group mb-1">
                                            <label for="alasan">Catatan penolakan</label>
                                            <textarea class="form-control" id="alasan" name="alasan"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for=""></label>
                                            <button class="btn btn-danger btn-sm">Tolak pengajuan</button>
                                        </div>
                                    </form>
                                </div>
                            <?php } else {
                                echo "Pengajuan sudah diverifikasi";
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card radius-10">
                    <div class="card-header">
                        <h6 class="mb-0">Program-program Bulan <?= bulan($pengajuan->bulan) ?></h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">

                            <table id="" class="table table-hover align-middle table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Kode</th>
                                        <th scope="col">Program</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    // var_dump($data);
                                    foreach ($dppk as $d) : ?>
                                        <tr>
                                            <td scope="row"><?= $no++ ?></td>
                                            <td><?= $d->id_dppk ?></td>
                                            <td><?= $d->program ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8">
                <div class="card radius-10">
                    <div class="card-header">
                        <h6 class="mb-0">Data Pengajuan</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="" class="table table-hover align-middle table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Kode</th>
                                        <th scope="col">Program</th>
                                        <th scope="col">Bulan</th>
                                        <th scope="col">Rincian Belanja</th>
                                        <th scope="col">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    // var_dump($data);
                                    foreach ($dataKirim as $a) : ?>
                                        <tr>
                                            <td scope="row"><?= $no++ ?></td>
                                            <td><?= $a['kode'] ?></td>
                                            <td><?= $a['program'] ?></td>
                                            <td>
                                                <?php
                                                foreach (explode(',', $a['bulan']) as $b) {
                                                    echo ($b == $pengajuan->bulan)
                                                        ? '<b style="color:red">' . $b . '</b>, '
                                                        : $b . ', ';
                                                }
                                                ?>
                                            </td>
                                            <td><?= preg_replace('/x\s*\d+(\.\d+)?/', '', $a['rincian']) ?></td>
                                            <td><a class="btn btn-danger btn-sm tombol-hapus" href="<?= base_url('pengajuan/delRencana/' . $a['id_realis']) ?>">Hapus</a></td>
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
<!-- End Page-content -->
<?php include 'lembaga/foot.php' ?>
<script src="<?= base_url(''); ?>assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url(''); ?>assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#table1').DataTable();
    })
</script>