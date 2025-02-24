<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Input Potongan Guru/Karyawan</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-notepad"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Honor</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#cloneData">Cloning data potongan</button>
                        <div class="table-responsive mt-3">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr style="color: white; background-color: #008CFF; font-weight: bold;">
                                        <th>No</th>
                                        <th>Bulan</th>
                                        <th>Nama</th>
                                        <th>Lembaga</th>
                                        <th>Jumlah Potongan</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data->result() as $ls_jns) :
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= bulan($ls_jns->bulan) . ' ' . $ls_jns->tahun; ?></td>
                                            <td><?= $lembaga; ?></td>
                                            <td><?= $ls_jns->nama; ?></td>
                                            <td><b id="total-hasil-<?= $ls_jns->id ?>"><?= rupiah($ls_jns->total); ?></b></td>
                                            <td><button class="btn btn-warning btn-sm btn-edit" data-id="<?= $ls_jns->id ?>">Edit</button></td>
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

<div class="modal fade" id="edit-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Potongan </h5>
                <button type="button" class="btn-close" value="" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table width="100%" id="table-potongan">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Jenis Potongan</th>
                                <th>Nominal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <form action="<?= base_url('honor/add_row') ?>" method="post" class="form-addrow mt-2">
                    <input type="hidden" name="id" id="id">
                    <button class="btn btn-sm btn-light" type="submit"><i class="bx bx-plus-circle"></i>Tambah baru</button>
                </form>
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="cloneData" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cloning Data Potongan </h5>
                <button type="button" class="btn-close" value="" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('honor/clonePotongan') ?>" method="post" class="mt-2">
                    <input type="hidden" name="id_asal" value="<?= $data->row('potongan_id') ?>">
                    <div class="form-group mb-2">
                        <label for="">Pilih potongan</label>
                        <select name="dipilih" id="" class="form-select" required>
                            <option value=""> -pilih- </option>
                            <?php
                            $cek = $data->row('bulan') . '_' . $data->row('tahun');
                            foreach ($potongan as $pt):
                                $cek2 = $pt->bulan . '_' . $pt->tahun;
                                if ($cek != $cek2):
                            ?>
                                    <option value="<?= $pt->potongan_id ?>"><?= bulan($pt->bulan) . ' ' . $pt->tahun ?></option>
                            <?php endif;
                            endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-sm btn-success">Simpan</button>
                    </div>
                </form>
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<!--end page wrapper -->
<script src="<?= base_url('vertical/'); ?>assets/js/jquery.min.js"></script>
<script src="<?= base_url('vertical/'); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function() {
        $('.btn-edit').on('click', function() {
            var id = $(this).data('id');
            $('#hasil-tampil').text('Proses pengambilan data.....')
            $('#edit-modal').modal('show');
            $.ajax({
                type: 'POST',
                url: '<?= base_url('honor/ambil_data/'); ?>' + id,
                dataType: 'json',
                data: {
                    id: id
                },
                success: function(response) {
                    const rows = generateTableRows(response.data);
                    $('#table-potongan tbody').html(rows);
                    $('#id').val(id);
                    $('.btn-close').attr('value', id);
                    $('.uang').mask('000.000.000.000', {
                        reverse: true
                    });
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            })
        })
    });
    $('#table-potongan').on('change', '.form-input', function() {
        var newValue = $(this).val(); // nilai baru dari input
        var id = $(this).data('id'); // id dari baris data
        var inputName = $(this).attr('name');

        $.ajax({
            url: '<?= base_url("honor/updatePotongan") ?>', // endpoint untuk update data
            type: 'POST',
            data: {
                id: id,
                value: newValue,
                inputName: inputName
            },
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    // const rows = generateTableRows(response.data);
                    // $('#table-potongan tbody').html(rows);
                    $('.uang').mask('000.000.000.000', {
                        reverse: true
                    });
                } else {
                    alert('Gagal mengupdate data');
                }
                // alert(response.hasil)
            },
            error: function() {
                alert('Terjadi kesalahan saat mengupdate data');
            }
        });
    });

    $(document).on('submit', '.form-addrow', function(e) {
        e.preventDefault(); // Mencegah form dari reload halaman

        var form = $(this); // Form yang dikirim
        var formData = form.serialize(); // Serialize data form

        // Eksekusi AJAX
        $.ajax({
            url: form.attr('action'), // Mengambil URL dari atribut form
            type: 'POST',
            data: formData,
            dataType: 'json', // Pastikan format respon JSON
            success: function(response) {
                if (response) {
                    const rows = generateTableRows(response.data);
                    $('#table-potongan tbody').html(rows);
                    $('.uang').mask('000.000.000.000', {
                        reverse: true
                    });
                } else {
                    alert('Respon kosong. Pastikan server mengembalikan data dengan benar.');
                }
            },
            error: function(xhr, status, error) {
                console.log('Error: ' + xhr.responseText);
            }
        });
    });

    function generateTableRows(response) {
        let rows = '';
        let no = 1;
        $.each(response, function(index, item) {
            rows += `
            <tr>
                <td>${no++}</td>
                <td><input type="text" name="ket" class="form-control form-input" data-id="${item.id}" value="${item.ket}"></td>
                <td><input type="text" name="nominal" class="form-control form-input uang" data-id="${item.id}" value="${item.nominal}"></td>
                <td><button class="btn btn-sm btn-danger-outline del-btn" data-id="${item.id}">Del</button></td>
            </tr>
            `;
        });
        return rows;
    }

    $(document).on('click', '.del-btn', function() {
        var id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: '<?= base_url('honor/del_row'); ?>',
            dataType: 'json',
            data: {
                id: id
            },
            success: function(response) {
                const rows = generateTableRows(response.data);
                $('#table-potongan tbody').html(rows);
                $('#id').val(id);
                $('.uang').mask('000.000.000.000', {
                    reverse: true
                });
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        })
    })

    $('.btn-close').on('click', function() {
        var id = $(this).attr('value');
        $.ajax({
            type: 'POST',
            url: '<?= base_url('honor/reload_total'); ?>',
            dataType: 'json',
            data: {
                id: id
            },
            success: function(response) {
                $('#total-hasil-' + id).text(formatRupiah(response.data))
                // alert('total-hasil-' + response.id)
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        })
    })

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    }
</script>