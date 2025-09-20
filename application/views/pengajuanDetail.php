<?php
require 'lembaga/head.php';
?>
<link href="<?= base_url(''); ?>assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<link href="<?= base_url(''); ?>assets/plugins/select2/css/select2.min.css" rel="stylesheet" />
<link href="<?= base_url(''); ?>assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Pengajuan</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-folder-open"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Detail Pengajuan</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-10">
                <div class="card" id="orderList">
                    <div class="card-header align-items-xl-center d-xl-flex">
                        <h5 class="card-title mb-0 flex-grow-1 mb-xl-0">Status : <?= $pj->stts == 'yes' ? 'sudah diajuakan' : 'belum diajuakan' ?></h5>
                        <div class="flex-shrink-0">
                            <ul class="nav nav-tabs nav-primary" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#barang" role="tab" aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-box font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Item Barang</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tunai" role="tab" aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-money font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Item Tunai</div>
                                        </div>
                                    </a>
                                </li>
                            </ul>

                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Tab panes -->
                        <div class="tab-content text-muted">

                            <!-- Form Barang -->
                            <div class="tab-pane" id="barang" role="tabpanel">
                                <form action="" id="form-barang">
                                    <input type="hidden" name="kode_pengajuan" value="<?= $pj->kode_pengajuan ?>">
                                    <div class="row">
                                        <div class="col-sm-6">

                                            <div class="row mb-2">
                                                <div class="col-lg-3">
                                                    <label for="nameInput" class="form-label">Pilih Program</label>
                                                </div>
                                                <div class="col-lg-9">
                                                    <select class="js-example-basic-single" name="program" id="program" required>
                                                        <option>pilih program</option>
                                                        <?php foreach ($program as $program1): ?>
                                                            <option value="<?= $program1->id_dppk ?>"><b><?= $program1->id_dppk . '. ' . $program1->program ?></b></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-lg-3">
                                                    <label for="nameInput" class="form-label">Pilih Akun (COA)</label>
                                                </div>
                                                <div class="col-lg-9">
                                                    <select class="js-example-basic-single select2-standalone" id="p-coa" required>
                                                        <option>pilih akun</option>
                                                        <?php foreach ($coa as $coa1):
                                                            if ($coa1->parrent == '') { ?>
                                                                <option value="<?= $coa1->kode ?>"><b><?= $coa1->kode . ' ' . $coa1->nama ?></b></option>
                                                        <?php }
                                                        endforeach ?>
                                                    </select>
                                                    <select class="js-example-basic-single select-dependent" id="c-coa" name="coa" required>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-lg-3">
                                                    <label for="kegiatan" class="form-label">Nama Kegiatan</label>
                                                </div>
                                                <div class="col-lg-9">
                                                    <input type="text" class="fr-kegiatan form-control" name="kegiatan" placeholder="Masukan nama kegiata" required>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-lg-3">
                                                    <label for="nameInput" class="form-label">Pilih Item (SSH)</label>
                                                </div>
                                                <div class="col-lg-9">
                                                    <span role="button" data-bs-toggle="modal" data-bs-target="#inputno-modal" style="cursor: pointer;" class="badge bg-primary mb-2">Barang tidak ada di SSH</span>
                                                    <select class="js-example-basic-single select-dependent" id="item-ssh" name="ssh" required>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-lg-3">
                                                    <label for="jumlah" class="form-label">Jumlah (qty)</label>
                                                </div>
                                                <div class="col-lg-9">
                                                    <input type="number" class="form-control" id="input-qty" name="qty" placeholder="Masukan jumlah/qty" disabled required>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-sm-6">

                                            <div class="d-flex mt-0">
                                                <!-- <div class="flex-shrink-0">
                                                    <img src="assets/images/users/avatar-1.jpg" alt="" class="avatar-sm rounded" />
                                                </div> -->
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1 fs-14" id="coa-p-desc">-</h6>
                                                    <p class="mb-0" id="coa-c-desc">-</p>
                                                </div>
                                            </div>
                                            <!-- <div class="d-flex mt-3"> -->
                                            <div class="table-responsive mt-2 mb-2">
                                                <table class="table table-bordered text-center table-nowrap align-middle mb-0" id="products-list">
                                                    <thead>
                                                        <tr class="table-active">
                                                            <th scope="col" style="width: 50px;">#</th>
                                                            <th scope="col">Nama Barang</th>
                                                            <th scope="col">Harga</th>
                                                            <th scope="col">Satuan</th>
                                                            <th scope="col">Quantity</th>
                                                            <th scope="col" class="text-end">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th scope="row" id="kategori"></th>
                                                            <td class="text-start">
                                                                <span class="fw-medium" id="nama-item"></span>
                                                                <p class="text-muted mb-0" id="nama-kategori"></p>
                                                            </td>
                                                            <td id="harga"></td>
                                                            <td id="satuan"></td>
                                                            <td id="qty"></td>
                                                            <td id="total-harga" class="text-end"></td>
                                                        </tr>
                                                    </tbody>
                                                </table><!--end table-->
                                                <!-- </div> -->
                                            </div>
                                            <div class="">
                                                <button type="submit" class="btn btn-primary btn-sm" id="btn-tambah"><i class="bx bx-plus-circle"></i> Tambahkan</button>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                </form>
                            </div>

                            <!-- Form Tunai -->
                            <div class="tab-pane" id="tunai" role="tabpanel">
                                <div class="row">
                                    <form action="" id="form-tunai">
                                        <input type="hidden" name="kode_pengajuan" value="<?= $pj->kode_pengajuan ?>">
                                        <div class="row">
                                            <div class="col-sm-6">

                                                <div class="row mb-2">
                                                    <div class="col-lg-3">
                                                        <label for="nameInput" class="form-label">Pilih Program</label>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <select class="js-example-basic-single" name="program-tunai" id="program-tunai" required>
                                                            <option>pilih program</option>
                                                            <?php foreach ($program as $program2): ?>
                                                                <option value="<?= $program2->id_dppk ?>"><b><?= $program2->id_dppk . '. ' . $program2->program ?></b></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-lg-3">
                                                        <label for="nameInput" class="form-label">Pilih Akun (COA)</label>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <select class="js-example-basic-single select2-standalone" id="p-coa-tunai" required>
                                                            <option>pilih akun</option>
                                                            <?php foreach ($coa as $coa2):
                                                                if ($coa2->parrent == '') { ?>
                                                                    <option value="<?= $coa2->kode ?>"><b><?= $coa2->kode . ' ' . $coa2->nama ?></b></option>
                                                            <?php }
                                                            endforeach ?>
                                                        </select>
                                                        <select class="js-example-basic-single select-dependent" id="c-coa-tunai" name="coa" required>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-lg-3">
                                                        <label for="kegiatan" class="form-label">Nama Kegiatan</label>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <input type="text" class="fr-kegiatan form-control" name="kegiatan" placeholder="Masukan nama kegiata" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-sm-6">
                                                <div class="row mb-2">
                                                    <div class="col-lg-3">
                                                        <label for="nameInput" class="form-label">Nama Item</label>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control" id="nama-barang" name="barang" placeholder="Masukan nama barang" required>
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-lg-3">
                                                        <label for="nameInput" class="form-label">Harga</label>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <input type="text" class="form-control uang" id="harga" name="harga" placeholder="Masukan harga barang" required>
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-lg-3">
                                                        <label for="jumlah" class="form-label">Jumlah (qty)</label>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <input type="number" class="form-control" id="input-qty" name="qty" placeholder="jumlah/qty" required>
                                                    </div>
                                                    <div class="col-lg-5">
                                                        <select class="js-example-basic-single" id="satuan-select" name="satuan">
                                                            <option value="">-satuan-</option>
                                                            <?php foreach ($satuan as $satuanTn): ?>
                                                                <option value="<?= $satuanTn->nama ?>"><?= $satuanTn->nama ?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="">
                                                    <button type="submit" class="btn btn-primary btn-sm" id="btn-tambah"><i class="bx bx-plus-circle"></i> Tambahkan</button>
                                                </div>
                                            </div>
                                            <!--end col-->
                                        </div>
                                    </form>
                                </div>
                                <!--end row-->
                            </div>
                        </div>
                    </div><!-- end card-body -->

                    <div class="card-body pt-0">
                        <div class="row contacts">
                            <div class="col invoice-to">
                                <!-- <h2 class="to">John Doe</h2> -->
                                <a href="<?= base_url('pengajuan/ajukan/' . $pj->kode_pengajuan) ?>" class="btn btn-sm btn-success mb-2 tbl-confirm" value="Pengajuan akan dilanjutkan ke Bendahara dan Perencanaan"><i class="bx bx-upload"></i> Ajukan ke Bendahara</a>
                            </div>
                            <div class="col invoice-details">
                                <h4 class="invoice-id">Total : <b id="total-pengajuan"></b></h4>
                            </div>
                        </div>
                        <!-- <b class="folat-right">Total </b> -->
                        <div class="table-responsive mb-1">
                            <table class="table table-nowrap align-middle" id="tableData">
                                <thead class="text-muted table-dark">
                                    <tr class="">
                                        <th class="text-light" data-sort="id">No</th>
                                        <!-- <th class="text-light" data-sort="id">Kode Item</th> -->
                                        <th class="text-light" data-sort="customer_name">Akun/COA</th>
                                        <th class="text-light" data-sort="product_name">Nama Item</th>
                                        <th class="text-light" data-sort="amount">Harga</th>
                                        <th class="text-light" data-sort="date">Jumlah</th>
                                        <th class="text-light" data-sort="payment">Total</th>
                                        <th class="text-light" data-sort="status">Jenis</th>
                                        <th class="text-light" data-sort="city">#</th>
                                    </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <div class="col-12 col-lg-2">

                </div>
            </div>
        </div>
    </div>
    <!--end row-->
</div>

<div class="modal fade" id="inputno-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Barang Pengajuan </h5>
                <button type="button" class="btn-close" value="" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('pengajuan/addItemBarangModal') ?>" method="post" class="form-addrow mt-2">
                    <input type="hidden" name="kode_pengajuan" value="<?= $pj->kode_pengajuan ?>">
                    <input type="hidden" id="program_modal" name="program" value="">
                    <input type="hidden" id="coa_modal" name="coa" value="">
                    <div class="form-group mb-2">
                        <label for="">Nama Barang</label>
                        <input type="text" class="form-control" name="nama" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="">Satuan</label>
                        <select class="form-select" name="satuan" required>
                            <option value="">-satuan-</option>
                            <?php foreach ($satuan as $satuanMd): ?>
                                <option value="<?= $satuanMd->nama ?>"><?= $satuanMd->nama ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="">Harga Barang</label>
                        <input type="text" class="form-control uang" name="harga_satuan" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="">QTY</label>
                        <input type="number" class="form-control" name="qty" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for=""></label>
                        <button class="btn btn-success btn-sm" type="submit">Tambahkan</button>
                    </div>
                </form>
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<!-- End Page-content -->
<?php require 'lembaga/foot.php' ?>
<script src="<?= base_url(''); ?>assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url(''); ?>assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<!--select2 cdn-->
<script src="<?= base_url(''); ?>assets/plugins/select2/js/select2.min.js"></script>
<script src="<?= base_url(''); ?>assets/js/jquery.mask.min.js"></script>
<!-- Notfy -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
    const notyf = new Notyf({
        duration: 3000,
        position: {
            x: 'center',
            y: 'top'
        }
    });
    $(document).ready(function() {
        $('#table1').DataTable()
        $('.js-example-basic-single').select2({
            theme: 'bootstrap4',
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder'),
            allowClear: Boolean($(this).data('allow-clear')),
        });
        $('.uang').mask('000.000.000.000', {
            reverse: true
        });
        loadSSH()
        showTable()
        totalPengajuan()
    })
    $('#p-coa').on('change', function() {
        var kode = $(this).val();
        $.ajax({
            type: "POST",
            url: "<?= base_url('pengajuan/child_coa') ?>",
            data: {
                kode: kode
            },
            dataType: 'json',
            success: function(data) {
                $('#c-coa').empty();
                $('#c-coa').append('<option>pilih coa</option>')
                $.each(data.hasil, function(index, item) {
                    $('#c-coa').append(
                        $('<option>', {
                            value: item.kode, // Sesuaikan dengan data dari server
                            text: item.kode + ' ' + item.nama // Sesuaikan dengan data dari server
                        })
                    );
                });

                // $('#c-coa').trigger('change');
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText);
            }
        })
    });
    $('#p-coa-tunai').on('change', function() {
        var kode = $(this).val();
        $.ajax({
            type: "POST",
            url: "<?= base_url('pengajuan/child_coa') ?>",
            data: {
                kode: kode
            },
            dataType: 'json',
            success: function(data) {
                $('#c-coa-tunai').empty();
                $('#c-coa-tunai').append('<option>pilih coa</option>')
                $.each(data.hasil, function(index, item) {
                    $('#c-coa-tunai').append(
                        $('<option>', {
                            value: item.kode, // Sesuaikan dengan data dari server
                            text: item.kode + ' ' + item.nama // Sesuaikan dengan data dari server
                        })
                    );
                });

                // $('#c-coa').trigger('change');
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText);
            }
        })
    });
    $('#c-coa').on('change', function() {
        var kode = $(this).val();
        $.ajax({
            type: "POST",
            url: "<?= base_url('pengajuan/child_coa') ?>",
            data: {
                kode: kode
            },
            dataType: 'json',
            success: function(data) {
                // alert(data)
                $('#coa-p-desc').text(data.parent.kode + ' ' + data.parent.nama)
                $('#coa-c-desc').text(data.parent.keterangan)
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText);
            }
        })
    });
    $('#item-ssh').on('change', function() {
        var kode = $(this).val();
        $.ajax({
            type: "POST",
            url: "<?= base_url('pengajuan/detilSsh') ?>",
            data: {
                kode: kode
            },
            dataType: 'json',
            success: function(data) {
                $('#kategori').text(data.hasil.kategori)
                $('#nama-item').text(data.hasil.nama)
                $('#nama-kategori').text(data.kategori.nama_kategori)
                let hargaFormatted = Number(data.hasil.harga).toLocaleString('id-ID');
                $('#harga').text(hargaFormatted);

                $('#satuan').text(data.hasil.satuan)
                $('#input-qty').prop('disabled', false).val('')
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText);
            }
        })
    });
    $('#input-qty').on('input', function() {
        var jml = $(this).val();
        var ssh = $('#item-ssh').val();
        $.ajax({
            type: "POST",
            url: "<?= base_url('pengajuan/detilSsh') ?>",
            data: {
                kode: ssh
            },
            dataType: 'json',
            success: function(data) {
                $('#qty').text(jml)
                let hargaFormatted = Number(data.hasil.harga * jml).toLocaleString('id-ID');
                $('#total-harga').text(hargaFormatted)
            },
            error: function(xhr, status, error) {
                alert(xhr.responseText);
            }
        })
        // alert(ssh)
    });

    function loadSSH() {
        $.ajax({
            type: "GET",
            url: "<?= base_url('pengajuan/loadSSH') ?>",
            dataType: 'json',
            success: function(data) {
                const select = $('#item-ssh');
                select.empty();
                select.append('<option>pilih barang</option>')
                data.forEach(function(group) {
                    const optgroup = $('<optgroup>', {
                        label: group.label
                    });
                    group.options.forEach(function(opt) {
                        $('<option>', {
                            value: opt.value,
                            text: opt.text
                        }).appendTo(optgroup);
                    });
                    optgroup.appendTo(select);
                });
                // console.log(data);

            }
        })
    }

    $('#form-barang').on('submit', function(e) {
        e.preventDefault();
        var dataForm = $(this).serialize()
        $.ajax({
            type: "POST",
            url: "<?= base_url('pengajuan/addItemBarang') ?>",
            data: dataForm,
            dataType: 'json',
            success: function(data) {
                if (data.status == 'success') {
                    notyf.success(data.message);
                    showTable()
                    resetFormAndTable()
                    totalPengajuan()
                } else {
                    notyf.error(data.message);
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        })
    })
    $('#form-tunai').on('submit', function(e) {
        e.preventDefault();
        var dataForm = $(this).serialize()
        $.ajax({
            type: "POST",
            url: "<?= base_url('pengajuan/addItemTunai') ?>",
            data: dataForm,
            dataType: 'json',
            success: function(data) {
                if (data.status == 'success') {
                    notyf.success(data.message);
                    showTable()
                    resetFormAndTable()
                    totalPengajuan()
                } else {
                    notyf.error(data.message);
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        })
        // console.log(dataForm);

    })

    function showTable() {
        $.ajax({
            type: "POST",
            url: "<?= base_url('pengajuan/loadTable') ?>",
            data: {
                kode: '<?= $pj->kode_pengajuan ?>'
            },
            dataType: 'json',
            success: function(response) {
                if ($.fn.DataTable.isDataTable('#tableData')) {
                    // kalau sudah ada DataTable, cukup update datanya
                    let table = $('#tableData').DataTable();
                    table.clear(); // kosongkan dulu
                    if (response.length > 0) {
                        response.forEach(function(item, index) {
                            table.row.add([
                                index + 1,
                                // `<a href="#" class="fw-medium link-primary">${item.kode_item}</a>`,
                                item.coa,
                                item.ssh == null && item.ket ? parseItemDetail(item.ket)?.nama : item.ssh,
                                rupiah(item.harga),
                                `${item.vol} <small class="text-muted">${item.satuan == null && item.ket ? parseItemDetail(item.ket)?.satuan : item.satuan}</small>`,
                                rupiah(item.vol * item.harga),
                                `<span class="badge bg-warning-subtle text-success text-uppercase">${item.stas =='tunai'?'Tunai':'Non-Tunai'}</span>`,
                                `<button class="btn btn-sm btn-danger" onclick="delItem('${item.id_realis}')">Del</button>`
                            ]);
                        });
                    }
                    table.draw(); // render ulang
                } else {
                    // kalau pertama kali load, baru bikin DataTable
                    $('#tableData').DataTable({
                        data: response.map((item, index) => [
                            index + 1,
                            // `<a href="#" class="fw-medium link-primary">${item.kode_item}</a>`,
                            item.coa,
                            item.ssh == null && item.ket ? parseItemDetail(item.ket)?.nama : item.ssh,
                            rupiah(item.harga),
                            `${item.vol} <small class="text-muted">${item.satuan == null && item.ket ? parseItemDetail(item.ket)?.satuan : item.satuan}</small>`,
                            rupiah(item.vol * item.harga),
                            `<span class="badge bg-warning-subtle text-success text-uppercase">${item.stas =='tunai'?'Tunai':'Non-Tunai'}</span>`,
                            `<button class="btn btn-sm btn-danger" onclick="delItem('${item.id_realis}')">Del</button>`
                        ]),
                        columns: [{
                                title: "#"
                            },
                            // {
                            //     title: "Kode Item"
                            // },
                            {
                                title: "COA"
                            },
                            {
                                title: "Detail"
                            },
                            {
                                title: "Harga"
                            },
                            {
                                title: "Volume"
                            },
                            {
                                title: "Total"
                            },
                            {
                                title: "Status"
                            },
                            {
                                title: "Aksi"
                            }
                        ],
                        paging: false,
                        searching: true,
                        ordering: true,
                        responsive: true,
                        autoWidth: false,
                        dom: 'Bfrtip', // B = Buttons, f = filter, r = processing, t = table, i = info, p = pagination
                        buttons: [{
                                extend: 'excelHtml5',
                                title: 'Data Realisasi',
                                text: '<i class="bx bx-excel"></i> Export Excel',
                                className: 'btn btn-warning btn-sm'
                            },
                            {
                                extend: 'csvHtml5',
                                title: 'Data Realisasi',
                                text: '<i class="bx bx-file-csv"></i> Export CSV',
                                className: 'btn btn-info btn-sm'
                            },
                            {
                                extend: 'pdfHtml5',
                                title: 'Data Realisasi',
                                text: '<i class="bx bx-file-pdf"></i> Export PDF',
                                className: 'btn btn-danger btn-sm'
                            },
                            {
                                extend: 'print',
                                text: '<i class="bx bx-printer"></i> Print',
                                className: 'btn btn-secondary btn-sm'
                            }
                        ],
                        language: {
                            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                // console.log(xhr.responseText);
                console.error('Gagal memuat data:', error);
                $('#tableData tbody').html(`<tr><td colspan="5" class="text-center text-red-500">Gagal memuat data</td></tr>`);
            }
        })
    }

    function rupiah(number) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0
        }).format(number);
    }

    $('#program').on('change', function() {
        let kode = $(this).val();
        $('#program_modal').val(kode)
    })
    $('#c-coa').on('change', function() {
        let kode = $(this).val();
        $('#coa_modal').val(kode)
    })

    function resetFormAndTable() {
        $('#form-barang')[0].reset();
        $('#form-tunai')[0].reset();
        $('.select2-standalone').val(null).trigger('change');
        $('.select-dependent').html('<option value=""> pilih </option>');
        $('#program').val(null).trigger('change');
        $('#program-tunai').val(null).trigger('change');
        // $('#item-ssh').val(null).trigger('change');
        // $('#products-list tbody').html('');
        $('#coa-p-desc').text('-');
        $('#coa-c-desc').text('-');
        loadSSH()

        // Table list Hasil
        $('#kategori').text('')
        $('#nama-item').text('')
        $('#nama-kategori').text('')
        $('#harga').text('');
        $('#satuan').text('')
        $('.fr-kegiatan').text('')
        $('#qty').text(0)
        $('#total-harga').text(0)
    }

    function delItem(id) {
        Swal.fire({
            title: 'Yakin?',
            text: "Data akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('pengajuan/delItem') ?>',
                    type: 'POST',
                    dataType: 'json', // pastikan response dibaca sebagai JSON
                    data: {
                        id: id
                    },
                    success: function(psn) {
                        if (psn.status === 'success') {
                            notyf.success('Data berhasil dihapus!');
                            showTable();
                            totalPengajuan();
                        } else {
                            notyf.error(psn.message || 'Terjadi kesalahan!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        notyf.error('Gagal menghapus data!');
                    }
                });
            }
        });

    }

    function totalPengajuan() {
        $.ajax({
            type: "POST",
            url: "<?= base_url('pengajuan/totalPengjuan') ?>",
            data: {
                kode: '<?= $pj->kode_pengajuan ?>'
            },
            dataType: 'json',
            success: function(response) {
                $('#total-pengajuan').text(rupiah(response))
            },
            error: function(xhr, status, error) {
                $('#total-pengajuan').text(error)
            }
        })
    }

    function parseItemDetail(text) {
        if (!text || !text.includes(" - @ ") || !text.includes(" x ")) {
            return {
                nama: '',
                jumlah: 0,
                satuan: '',
                harga: 0
            }; // fallback aman
        }

        const [namaBarang, detail] = text.split(" - @ ");
        const detailParts = detail.split(" x ");

        if (!detailParts[0] || !detailParts[1]) {
            return {
                nama: namaBarang || '',
                jumlah: 0,
                satuan: '',
                harga: 0
            };
        }

        const [jumlah, satuan] = detailParts[0].split(" ");
        const harga = detailParts[1].replace(/\./g, "");

        return {
            nama: namaBarang?.trim() || '',
            jumlah: parseInt(jumlah) || 0,
            satuan: satuan?.trim() || '',
            harga: parseInt(harga) || 0
        };
    }
</script>