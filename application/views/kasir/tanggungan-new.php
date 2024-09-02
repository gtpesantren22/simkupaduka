<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Tanggungan Santri (Bulan Ini)</div>
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
                        <div class="row">
                            <div class="col-md-3">
                                <h3>Informasi Tagihan</h3>
                                <hr>
                                <table class="table table-sm ">
                                    <tr>
                                        <th>Jumlah Tagihan</th>
                                        <th><?= $jmltagihan->total ?> item</th>
                                    </tr>
                                    <tr>
                                        <th>Lunas</th>
                                        <th><?= $jmlbayar->total ?> item</th>
                                    </tr>
                                    <tr>
                                        <th>Belum Lunas</th>
                                        <th><?= $jmltagihan->total - $jmlbayar->total ?> item</th>
                                    </tr>
                                </table>
                                <button class="btn btn-success mb-1" data-bs-target="#uploadTagihan" data-bs-toggle="modal"><i class="bx bx-upload"></i> Upload Tagihan</button>
                                <button class="btn btn-warning mb-1" data-bs-target="#kirimTagihan" data-bs-toggle="modal"><i class="bx bx-envelope"></i> Kirim Tagihan</button>
                            </div>
                            <div class="col-md-9">
                                <div class="table-responsive">
                                    <table id="userTable" class="table table-striped table-bordered display" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Nominal</th>
                                                <th>Bulan</th>
                                                <th>Tahun</th>
                                                <th>Ket</th>
                                                <th>Act</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Card  -->
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Rincian Tagihan Setiap Bulan</h4>
                                <div class="table-responsive">
                                    <table id="" class="table table-striped table-bordered display" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Bulan</th>
                                                <th>Total item</th>
                                                <th>Lunas</th>
                                                <th>Belum</th>
                                                <th>Act</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1;
                                            foreach ($tanggungan_bulan as $row) : ?>
                                                <tr>
                                                    <td><?= $no++; ?></td>
                                                    <td><?= bulan($row['bulan']) ?></td>
                                                    <td><?= $row['jmltanggungan'] ?> items</td>
                                                    <td><?= $row['jmlbayar'] ?> items</td>
                                                    <td><?= $row['jmltanggungan'] - $row['jmlbayar'] ?> items</td>
                                                    <td>
                                                        <button class="btn btn-primary btn-sm rincian-btn" data-bulan="<?= $row['bulan'] ?>" data-total="<?= $row['jmltanggungan'] ?>" data-bayar="<?= $row['jmlbayar'] ?>">Rincian</button>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
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

<div class="modal fade" id="uploadTagihan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Unggah Tagihan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('kasir/uploadTagihan') ?>" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label for="">Upload Foto</label>
                        <input type="file" name="file" class="form-control form-control-sm" required>
                    </div>
                    <small class="text-danger">- Download format upload tagihan <a href="<?= base_url('kasir/downloadFormatTagihan') ?>">disini!</a></small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload Tagihan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="kirimTagihan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kirim Tagihan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="send-form">
                    <p>Total Tagihan : <?= $jmltagihan->total ?></p>
                    <p>Terbayarkan : <?= $jmlbayar->total ?></p>
                    <p>Tagihan Belum Lunas : <?= $jmltagihan->total - $jmlbayar->total ?></p>
                    <button class="btn btn-sm btn-dark"><i class="bx bx-send"></i> Kirim Tagihan (<?= $jmltagihan->total - $jmlbayar->total ?> item)</button>
                </form>
            </div>
            <div class="modal-footer">
                <small>Proses pengiriman membutuhkan beberapa waktu menyesuaikan banyaknya data</small>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="formBayar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Bayar Tagihan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('kasir/addBayar') ?>" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label for="">NIS</label>
                        <input type="text" name="nis" class="form-control form-control-sm" id="edit-nis" readonly>
                    </div>
                    <div class="form-group mb-2">
                        <label for="">Nama</label>
                        <input type="text" name="nama" class="form-control form-control-sm" id="edit-nama" readonly>
                    </div>
                    <div class="form-group mb-2">
                        <label for="">Nominal</label>
                        <input type="text" name="nominal" class="form-control form-control-sm uang" id="edit-nominal" readonly>
                    </div>
                    <div class="form-group mb-2">
                        <label for="">Bulan</label>
                        <input type="text" name="bulan" class="form-control form-control-sm" id="edit-bulan" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="">Tahun</label>
                        <input type="text" name="tahun" class="form-control form-control-sm" id="edit-tahun" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="">Tanggal Bayar</label>
                        <input type="text" name="tgl" class="form-control form-control-sm" id="date" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="">Dekosan</label><br>
                        <input type="radio" name="dekos" value="Y" required> Ya
                        <input type="radio" name="dekos" value="T" required> Tidak
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan Bayar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="rincianTagihan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Rincian Tagihan Bulan <b id="isi-bulan"></b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="" class="table table-striped table-bordered display rincianPerBulan" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Nominal</th>
                                <th>Bulan</th>
                                <th>Tahun</th>
                                <th>Ket</th>
                                <th>Act</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <!-- <small>Proses pengiriman membutuhkan beberapa waktu menyesuaikan banyaknya data</small> -->
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('vertical/'); ?>assets/js/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Open modal and populate fields

        $('.rincian-btn').on('click', function() {
            var bulan = $(this).data('bulan');
            var total = $(this).data('total');
            var bayar = $(this).data('bayar');

            $('#isi-bulan').text(bulan);
            $('#rincianTagihan').modal('show');

            // Hancurkan DataTable jika sudah ada
            if ($.fn.DataTable.isDataTable('.rincianPerBulan')) {
                $('.rincianPerBulan').DataTable().destroy();
            }

            // Bersihkan konten HTML tabel untuk menghindari duplikasi
            $('.rincianPerBulan tbody').empty();

            $('.rincianPerBulan').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "<?= base_url('kasir/tanggunganBulan/'); ?>" + bulan,
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
                        "data": 5
                    },
                    {
                        "data": 6
                    },
                    {
                        "data": 7,
                        "render": function(data, type, row) {
                            if (data > 0) {
                                return "<span class='badge bg-success'><i class='bx bx-check'></i> sudah</span>";
                            } else {
                                return "<span class='badge bg-danger'><i class='bx bx-x'></i> belum</span>";
                            }
                        }
                    },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return `
                        <div class="dropdown">
                            <button class="btn btn-outline-dark btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Act</button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item edit-btn" data-id="${row[1]}" data-nis="${row[2]}" data-nama="${row[3]}" data-nominal="${row[4]}" data-bulan="${row[5]}" data-tahun="${row[6]}">bayar</a></li>
                                <li><a class="dropdown-item tombol-hapus" href="<?= base_url('kasir/delTanggungan/') ?>${row[1]}">hapus</a></li>
                            </ul>
                        </div>
                    `;
                        }
                    }
                ],
                "pageLength": 10,
                "searchDelay": 500
            });
        })

        $('#userTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('kasir/tanggunganBulan/' . date('m')); ?>",
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
                    "data": 5
                },
                {
                    "data": 6
                },
                {
                    "data": 7,
                    "render": function(data, type, row) {
                        if (data > 0) {
                            return "<span class='badge bg-success'><i class='bx bx-check'></i> sudah</span>";
                        } else {
                            return "<span class='badge bg-danger'><i class='bx bx-x'></i> belum</span>";
                        }
                    }
                },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return `
                        <div class="dropdown">
                            <button class="btn btn-outline-dark btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Act</button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item edit-btn" data-id="${row[1]}" data-nis="${row[2]}" data-nama="${row[3]}" data-nominal="${row[4]}" data-bulan="${row[5]}" data-tahun="${row[6]}">bayar</a></li>
                                <li><a class="dropdown-item tombol-hapus" href="<?= base_url('kasir/delTanggungan/') ?>${row[1]}">hapus</a></li>
                            </ul>
                        </div>
                    `;
                    }
                }
            ],
            "pageLength": 10,
            "searchDelay": 500
        });

        $('#userTable').on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            var nominal = $(this).data('nominal');
            var nis = $(this).data('nis');
            var tahun = $(this).data('tahun');
            var bulan = $(this).data('bulan');

            $('#edit-id').val(id);
            $('#edit-nama').val(nama);
            $('#edit-nominal').val(nominal);
            $('#edit-nis').val(nis);
            $('#edit-tahun').val(tahun);
            $('#edit-bulan').val(bulan);

            $('#formBayar').modal('show');
        });
        $('#userTable').on('click', '.tombol-hapus', function(e) {
            e.preventDefault();
            var href = $(this).attr('href');
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: "Apakah Anda yakin ingin menghapus data ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });
    })
</script>