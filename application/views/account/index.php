<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<style>
    #content-hide {
        display: none;
    }

    i {
        cursor: pointer;
    }
</style>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!-- <h1><?= $status ?></h1> -->
        <div class="row ">
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Total Pemasukan</p>
                                <h4 class="my-1 text-info"><?= rupiah($masuk) ?></h4>
                                <p class="mb-0 font-13">Jumlah pemasukan pada tahun ini</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                                <i class='bx bxs-cart'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Total Pengeluaran</p>
                                <h4 class="my-1 text-danger"><?= rupiah($keluar) ?></h4>
                                <p class="mb-0 font-13">Jumlah pemasukan pada tahun ini</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto"><i class='bx bxs-wallet'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Saldo</p>
                                <h4 class="my-1 text-success"><?= rupiah($masuk - $keluar) ?></h4>
                                <p class="mb-0 font-13">Saldo bendahara tahun ini</p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                                <i id="toggleIcon" class='bx bxs-bar-chart-alt-2'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->

        <div class="row">
            <div id="content-hide">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <h5 class="card-title">Saldo Bank</h5>
                            </div>
                            <div class="col">
                                <div class="p-0 border border-3 border-success text-center text-danger rounded bg-light">
                                    <?php foreach ($saldo->result() as $data) : ?>
                                        <h2><?= rupiah($data->nominal) ?></h2>
                                        Update : <i class="bx bx-calendar"></i><?= date('d-M-Y', strtotime($data->last)) ?> <i class="bx bx-time"></i><?= date('H:i:s', strtotime($data->last)) ?>
                                    <?php endforeach; ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <h5 class="card-title">Saldo Cash</h5>
                            </div>
                            <div class="col">
                                <div class="p-0 border border-3 border-primary text-center text-danger rounded bg-light">
                                    <?php foreach ($cash->result() as $data) : ?>
                                        <h2><?= rupiah($data->nominal) ?></h2>
                                        <i class="bx bx-calendar"></i><?= date('d-M-Y', strtotime($data->last)) ?> <i class="bx bx-time"></i><?= date('H:i:s', strtotime($data->last)) ?>
                                    <?php endforeach; ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <h5 class="card-title">Dana Cadangan</h5>
                            </div>
                            <div class="col">
                                <div class="p-0 border border-3 border-warning text-center text-danger rounded bg-light">
                                    <h2><?= rupiah($cadangan) ?></h2>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <!-- <h5 class="card-title">Selisih Saldo</h5> -->
                            </div>
                            <div class="col">
                                <center>
                                    <strong style="color: #FC6A83;">(Saldo Bank + Saldo Tunai) <?= rupiah($saldo->row('nominal') + $cash->row('nominal')) ?></strong>
                                </center>
                                <div class="p-1 border border-1 text-center border-success rounded bg-light mb-1 mt-1">
                                    <strong>Selisih = (Saldo Bank + Saldo Tunai) - Saldo Sistem </strong><br>
                                </div>
                                <center>
                                    <h4><strong><?= rupiah(($saldo->row('nominal') + $cash->row('nominal')) - ($masuk - $keluar)) ?></strong></h4>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <h5 class="card-title">Realisasi Nikmus</h5>
                        </div>
                        <div class="col">
                            <div class="p-1 border border-1 text-center border-success rounded bg-light">
                                <strong>Pagu Anggaran</strong><br>
                                <strong><?= rupiah(72390000) ?></strong>
                            </div>
                            <div class="p-1 border border-1 text-center border-warning rounded bg-light">
                                <strong>Terpakai</strong><br>
                                <?php $dipakai = $nikmus->nom_kriteria + $nikmus->sopir + $nikmus->transport ?>
                                <strong><?= rupiah($dipakai) ?> (<?= round(($dipakai / 72390000) * 100, 1) ?>%)</strong>
                            </div>
                            <div class="p-1 border border-1 text-center border-info rounded bg-light">
                                <strong>Sisa</strong><br>
                                <strong><?= rupiah(72390000 - $dipakai) ?> (<?= round(((72390000 - $dipakai) / 72390000) * 100, 1) ?>%)</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <h5 class="card-title">Setoran Dekosan</h5>
                        </div>
                        <div class="col">
                            <div class="card radius-10 border-start border-0 border-3 border-info">
                                <div class="card-body">
                                    <p class="mb-0 text-secondary">Total Setoran</p>
                                    <h5 class="my-1 text-info"><?= rupiah($dekos->nominal) ?></h5>
                                </div>
                            </div>
                            <button class="btn btn-block btn-primary" type="button" id="button_find" data-bs-toggle="modal" data-bs-target="#addLembaga">Lihat Rincian</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="mb-0">Statistik Penggunaan RAB</h6>
                            </div>
                            <div class="dropdown ms-auto">
                                <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:;">Action</a>
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:;">Another action</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="d-flex align-items-center ms-auto font-13 gap-2 my-3">
                            <span class="border px-1 rounded cursor-pointer"><i class="bx bxs-circle me-1" style="color: #ffc107"></i>Penggunaan/Realisasi RAB dalam porsentase (%)</span>
                        </div>
                        <div class="chart-container-1">
                            <canvas id="chart1"></canvas>
                        </div>
                    </div>
                    <!-- <div class="row row-cols-1 row-cols-md-3 row-cols-xl-3 g-0 row-group text-center border-top">
                        <div class="col">
                            <div class="p-3">
                                <h5 class="mb-0">24.15M</h5>
                                <small class="mb-0">Overall Visitor <span> <i class="bx bx-up-arrow-alt align-middle"></i> 2.43%</span></small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-3">
                                <h5 class="mb-0">12:38</h5>
                                <small class="mb-0">Visitor Duration <span> <i class="bx bx-up-arrow-alt align-middle"></i> 12.65%</span></small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="p-3">
                                <h5 class="mb-0">639.82</h5>
                                <small class="mb-0">Pages/Visit <span> <i class="bx bx-up-arrow-alt align-middle"></i>
                                        5.62%</span></small>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<!--end page wrapper -->

<div class="modal fade modalInfo" id="exampleWarningModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-warning">
            <div class="modal-header border-dark">
                <h5 class="modal-title text-dark"><i class="bx bx-error"></i> Informasi Tugas Saya</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-dark">
                <p>Beberapa tugas menunggu</p>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="mb-0"><i class="bx bx-task"></i> VerVal Pengajuan</h6>
                        <span class="text-secondary"><?= $pjnData->num_rows() ?> data</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="mb-0"><i class="bx bx-file"></i> VerVal SPJ</h6>
                        <span class="text-secondary"><?= $spjData->num_rows() ?> data</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="mb-0"><i class="bx bx-add-to-queue"></i> VerVal PAK</h6>
                        <span class="text-secondary"><?= $pakData->num_rows() ?> data</span>
                    </li>
                </ul>
            </div>
            <div class="modal-footer border-dark">
                <!-- <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button> -->
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Ok. Siap Laksanakan!</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addLembaga" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">List Setoran Dekosan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="display_results"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <!-- <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Simpan Data</button> -->
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('vertical/'); ?>assets/js/jquery.min.js"></script>
<script src="<?= base_url('vertical/'); ?>assets/plugins/chartjs/js/Chart.min.js"></script>
<script src="<?= base_url('vertical/'); ?>assets/plugins/chartjs/js/Chart.extension.js"></script>
<script>
    $(function() {
        "use strict";

        // chart 1

        var ctx = document.getElementById("chart1").getContext('2d');

        var gradientStroke1 = ctx.createLinearGradient(0, 0, 0, 300);
        gradientStroke1.addColorStop(0, '#6078ea');
        gradientStroke1.addColorStop(1, '#17c5ea');

        var gradientStroke2 = ctx.createLinearGradient(0, 0, 0, 300);
        gradientStroke2.addColorStop(0, '#ff8359');
        gradientStroke2.addColorStop(1, '#ffdf40');

        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    <?php foreach ($lembaga as $dt) { ?> '<?= $dt->nama ?>',
                    <?php } ?>
                ],
                datasets: [{
                    label: 'Realisasi',
                    data: [
                        <?php foreach ($lembaga as $dt) {
                            $rab = $this->db->query("SELECT IFNULL(SUM(total), 0) as jml FROM rab WHERE tahun = '$tahun' AND lembaga = '$dt->kode' ")->row();
                            $pakai = $this->db->query("SELECT IFNULL(SUM(nominal), 0) as jml FROM realis WHERE tahun = '$tahun' AND lembaga = '$dt->kode' ")->row();
                            if ($rab->jml == 0) {
                                $prsn = 0;
                            } else {
                                $prsn = round($pakai->jml / $rab->jml * 100, 1);
                            }
                        ?> '<?= $prsn ?>',
                        <?php } ?>
                    ],
                    borderColor: gradientStroke2,
                    backgroundColor: gradientStroke2,
                    hoverBackgroundColor: gradientStroke2,
                    pointRadius: 0,
                    fill: false,
                    borderWidth: 0
                }]
            },

            options: {
                maintainAspectRatio: false,
                legend: {
                    position: 'bottom',
                    display: false,
                    labels: {
                        boxWidth: 8
                    }
                },
                tooltips: {
                    displayColors: false,
                },
                scales: {
                    xAxes: [{
                        barPercentage: .5
                    }]
                }
            }
        });

    });

    // Saat halaman dimuat
    $(document).ready(function() {
        // Tampilkan modal
        <?php if ($pjnData->num_rows() > 0 || $spjData->num_rows() > 0 || $pakData->num_rows() > 0) { ?>
            $('.modalInfo').modal('show');
        <?php } ?>
    });
</script>
<script>
    // Ambil elemen <i> dan elemen target
    const toggleIcon = document.getElementById('toggleIcon');
    const content = document.getElementById('content-hide');

    // Tambahkan event listener ke elemen <i>
    toggleIcon.addEventListener('click', () => {
        if (content.style.display === 'none') {
            content.style.display = 'block'; // Tampilkan konten
            // toggleIcon.textContent = '🙈'; // Ubah ikon
        } else {
            content.style.display = 'none'; // Sembunyikan konten
            // toggleIcon.textContent = '👁️'; // Ubah ikon kembali
        }
    });
</script>