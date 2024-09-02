<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Rekap Tabungan Wali Santri</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-hdd"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Input Rekap</li>
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
                                <div class="card radius-10 bg-primary bg-gradient">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <p class="mb-0 text-white">TABUNGAN</p>
                                                <h4 class="my-1 text-white"><?= rupiah($sumData->jml); ?></h4>
                                            </div>
                                            <div class="text-white ms-auto font-35"><i class='bx bx-money'></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card radius-10 bg-danger bg-gradient">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <p class="mb-0 text-white">PENGELUARAN</p>
                                                <h4 class="my-1 text-white"><?= rupiah($sumData->jml - $sumkeluar->jml); ?></h4>
                                            </div>
                                            <div class="text-white ms-auto font-35"><i class='bx bx-money'></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card radius-10 bg-success bg-gradient">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <p class="mb-0 text-white">SALD0</p>
                                                <h4 class="my-1 text-white"><?= rupiah($sumkeluar->jml); ?></h4>
                                            </div>
                                            <div class="text-white ms-auto font-35"><i class='bx bx-money'></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card radius-10 border-success border-bottom border-3 border-0">
                                    <div class=" card-header">
                                        <h5 class="text-success">Tabungan Masuk</h5>
                                    </div>
                                    <div class="card-body">
                                        <?= form_open('kasir/saveTabungan'); ?>
                                        <div class="form-group mb-1">
                                            <label for="">Santri</label>
                                            <select name="nis" class="form-select single-select" required>
                                                <option value=""> pilih santri</option>
                                                <?php foreach ($santri as $snt) : ?>
                                                    <option value="<?= $snt->nis ?>"><?= $snt->nama ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                        <div class="form-group mb-1">
                                            <label for="">Tanggal</label>
                                            <input type="text" name="tanggal" id="date" class="form-control" required>
                                        </div>
                                        <div class="form-group mb-1">
                                            <label for="">Nominal</label>
                                            <input type="text" name="jumlah" class="form-control uang" required>
                                        </div>
                                        <div class="form-group mb-1">
                                            <label for="">Keterangan</label>
                                            <input type="text" name="ket" class="form-control" required>
                                        </div>
                                        <div class="form-group mb-1">
                                            <button type="submit" class="btn btn-inverse-success btn-sm mt-1"><i class="bx bx-save"></i>Simpan Data</button>
                                            <button type="reset" class="btn btn-secondary  btn-sm mt-1"><i class="bx bx-refresh"></i>Reset</button>
                                        </div>
                                    </div>
                                    <?= form_close(); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card radius-10 border-danger border-bottom border-3 border-0">
                                    <div class=" card-header">
                                        <h5 class="text-danger">Tabungan Keluar</h5>
                                    </div>
                                    <div class="card-body">
                                        <?= form_open('kasir/outTabungan'); ?>
                                        <input type="hidden" name="nama" id="nama-santri">
                                        <div class="row mb-1">
                                            <label for="input35" class="col-sm-3 col-form-label">Santri</label>
                                            <div class="col-sm-9">
                                                <select name="nis" class="form-select single-select" id="santriTabunganOut" required>
                                                    <option value=""> pilih santri</option>
                                                    <?php foreach ($santri as $sn) : ?>
                                                        <option value="<?= $sn->nis ?>"><?= $sn->nama ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="input36" class="col-sm-3 col-form-label">Tanggal</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="tgl" id="date2" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="input36" class="col-sm-3 col-form-label">Nominal</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="nominal" class="form-control uang" required>
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="input36" class="col-sm-3 col-form-label">Keterangan</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="ket" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="bulan" class="col-sm-3 col-form-label">Bulan</label>
                                            <div class="col-sm-9">
                                                <select name="bulan" class="form-select single-select" required>
                                                    <option value=""> pilih bulan</option>
                                                    <?php for ($i = 1; $i <= 12; $i++) : ?>
                                                        <option value="<?= $i ?>"><?= bulan($i) ?></option>
                                                    <?php endfor ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="input36" class="col-sm-3 col-form-label">Admin</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="admin" class="form-control uang">
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <label for="input36" class="col-sm-3 col-form-label">Dekosan</label>
                                            <div class="col-sm-9">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="dekos" id="flexRadioDefault1" value="Y" required>
                                                        <label class="form-check-label" for="flexRadioDefault1">Ya</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="dekos" id="flexRadioDefault2" value="T" required>
                                                        <label class="form-check-label" for="flexRadioDefault2">Tidak</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mb-1">
                                            <button type="submit" class="btn btn-inverse-danger btn-sm mt-1"><i class="bx bx-save"></i>Simpan Data</button>
                                            <button type="reset" class="btn btn-secondary  btn-sm mt-1"><i class="bx bx-refresh"></i>Reset</button>
                                        </div>
                                    </div>
                                    <?= form_close(); ?>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="tabunganData" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Santri</th>
                                                <th>Tabungan</th>
                                                <th>Terpakai</th>
                                                <th>Saldo</th>
                                                <th>Act</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
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
<div class="modal fade" id="modalRincian" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Rincian Tabungan <b id="nama-santri"></b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered rincianTabungan" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nominal</th>
                                <th>Ket</th>
                                <th>Status</th>
                                <th>Act</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('vertical/'); ?>assets/js/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tabunganData').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('kasir/tabunganData'); ?>",
                "type": "POST",

            },
            "columns": [{
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1; // Nomor urut
                    }
                },
                {
                    "data": 3
                },
                {
                    "data": 4,
                    "render": function(data, type, row) {
                        return 'Rp. ' + new Intl.NumberFormat('id-ID', {
                            style: 'decimal',
                            minimumFractionDigits: 0
                        }).format(data);
                    }
                },
                {
                    "data": 5,
                    "render": function(data, type, row) {
                        return 'Rp. ' + new Intl.NumberFormat('id-ID', {
                            style: 'decimal',
                            minimumFractionDigits: 0
                        }).format(data);
                    }
                },
                {
                    "data": 6,
                    "render": function(data, type, row) {
                        return 'Rp. ' + new Intl.NumberFormat('id-ID', {
                            style: 'decimal',
                            minimumFractionDigits: 0
                        }).format(data);
                    }
                },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return `
                        <div class="dropdown">
                            <button class="btn btn-outline-info btn-sm rincian-btn" data-nis="${row[2]}" data-nama="${row[3]}">Rincian</button>
                        </div>
                    `;
                    }
                }
            ],
            "pageLength": 10,
            "searchDelay": 500
        });


        // Event handler untuk tombol rincian
        $('#tabunganData').on('click', '.rincian-btn', function() {
            var nis = $(this).data('nis');
            var nama = $(this).data('nama');

            $('#nama-santri').text(nama);
            $('#modalRincian').modal('show');

            // Hancurkan DataTable jika sudah ada
            if ($.fn.DataTable.isDataTable('.rincianTabungan')) {
                $('.rincianTabungan').DataTable().destroy();
            }
            $('.rincianTabungan tbody').empty();

            // Inisialisasi DataTable
            $('.rincianTabungan').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "<?= base_url('kasir/rincianTabungan/') ?>" + nis,
                    "type": "POST",
                },
                "columns": [{
                        "data": 0
                    },
                    {
                        "data": 4
                    },
                    {
                        "data": 3,
                        "render": function(data, type, row) {
                            return 'Rp. ' + new Intl.NumberFormat('id-ID', {
                                style: 'decimal',
                                minimumFractionDigits: 0
                            }).format(data);
                        }
                    },
                    {
                        "data": 5
                    },
                    {
                        "data": 6,
                        "render": function(data, type, row) {
                            return data === 'masuk' ?
                                "<span class='badge bg-success'>masuk</span>" :
                                "<span class='badge bg-danger'>keluar</span>";
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            var id = row[1];
                            return `
                        <a href="<?= base_url('kasir/delTabungan/') ?>${id}" class="text-danger">
                            <span class="bx bx-trash"></span>
                        </a>
                        <a href="<?= base_url('kasir/cetakNotaTabungan/') ?>${id}" class="text-primary">
                            <span class="bx bx-printer"></span>
                        </a>
                    `;
                        }
                    }
                ],
                "pageLength": 10,
                "searchDelay": 500
            });
        });

        // Fungsi cetakNota
        function cetakNota(id) {
            $.ajax({
                type: "POST",
                url: "<?= base_url('kasir/cetakNotaTabungan/') ?>" + id,
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(resp) {
                    console.log(resp);
                },
                error: function() {
                    alert('Terjadi kesalahan saat mencetak.');
                }
            });
        }

        $('#santriTabunganOut').on('change', function() {
            var nis = $(this).val();
            $.ajax({
                type: "POST",
                url: "<?= base_url('kasir/getDetailSantri') ?>",
                data: {
                    nis: nis
                },
                dataType: 'json',
                success: function(data) {
                    $('#nama-santri').val(data.nama);
                    console.log(data.nama)
                }
            })
        })

    });
</script>