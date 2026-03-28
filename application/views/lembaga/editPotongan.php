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
                        <?php if ($gaji->status != 'kunci') { ?>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#cloneData">Cloning data potongan</button>
                        <?php } ?>
                        <!-- TOOLBAR -->
                        <div class="row px-4 py-2 align-items-center">
                            <!-- PER PAGE (KIRI) -->
                            <div class="col-md-6 col-12 mb-2 mb-md-0">
                                <div class="d-flex align-items-center gap-2">
                                    <label class="mb-0 fw-semibold">Show</label>
                                    <select id="perPage" class="form-select form-select w-auto">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    <span class="fw-semibold">entries</span>
                                </div>
                            </div>

                            <!-- SEARCH (KANAN) -->
                            <div class="col-md-6 col-12 text-md-end">
                                <div class="input-group input-group w-60 w-md-50 ms-md-auto">
                                    <span class="input-group-text">
                                        <i class="bx bx-search"></i>
                                    </span>
                                    <input
                                        type="search"
                                        id="search"
                                        class="form-control"
                                        placeholder="Cari data...">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="list-guru" class="table table-striped table-bordered" style="width:100%">
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

                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between p-3 border-top">

                            <!-- INFO -->
                            <div class="text-muted small mb-md-0">
                                Menampilkan
                                <span id="startRecord">1</span>
                                sampai
                                <span id="endRecord">10</span>
                                dari
                                <span id="totalRecords">100</span>
                                entri
                            </div>

                            <!-- PAGINATION -->
                            <div id="pagination"></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>

<?php if ($gaji->status != 'kunci') { ?>
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
                            <tbody></tbody>
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
                    <form class="mt-2 form-clone">
                        <input type="hidden" name="id_asal" id="id_asal" value="<?= $potongan->potongan_id ?>">
                        <div class="form-group mb-2">
                            <label for="">Pilih data potongan</label>
                            <select name="dipilih" id="dipilih" class="form-select" required>
                                <option value=""> -pilih- </option>
                                <?php
                                $cek = $potongan->bulan . '_' . $potongan->tahun;
                                foreach ($potongan_list as $pt):
                                    $cek2 = $pt->bulan . '_' . $pt->tahun;
                                    if ($cek != $cek2):
                                ?>
                                        <option value="<?= $pt->potongan_id ?>"><?= bulan($pt->bulan) . ' ' . $pt->tahun ?></option>
                                <?php endif;
                                endforeach ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" id="proses-clone" class="btn btn-sm btn-success">Simpan</button>
                        </div>
                    </form>
                    <div id="view-hasil" class="mt-3"></div>
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<!--end page wrapper -->
<script src="<?= base_url('vertical/'); ?>assets/js/jquery.min.js"></script>
<script src="<?= base_url('vertical/'); ?>assets/js/jquery.mask.min.js"></script>
<script>
    let state = {
        page: 1,
        perPage: 10,
        search: '',
        sortBy: 'nama',
        sortDir: 'ASC',
        total: 0,
        potongan_id: '<?= $potongan_id ?>'
    };

    function loadData() {

        const params = new URLSearchParams({
            page: state.page,
            perPage: state.perPage,
            search: state.search,
            sortBy: state.sortBy,
            sortDir: state.sortDir,
            potongan_id: state.potongan_id
        }).toString();

        fetch(`<?= base_url('honor/rincian_potong') ?>?${params}`)
            .then(res => res.json())
            .then(res => {
                renderTable(res.data, res);
                renderPagination(res);
                state.total = res.total;
                info(state.perPage, state.page, state.total);
            });
    }

    function renderTable(data, meta) {
        const tbody = document.getElementById('list-guru').querySelector('tbody');
        tbody.innerHTML = '';

        if (!Array.isArray(data)) return;
        let start = (meta.page - 1) * meta.perPage;
        // console.log(data);
        data.forEach((row, index) => {
            // console.log(row.lembaga_id + ' <> ' + row.lembaga_terpilih + ' || ' + row.lembaga_user);
            let wrn = 'black'
            const $row = $(`
                    <tr style="color: ${wrn}">
                        <td>${start + index + 1}</td>
                        <td id="ket-bulan-${row.guru_id}">${row.bulan+' '+row.tahun}</td>
                        <td>${row.nama}</td>
                        <td>${row.satminkal}</td>
                        <td id="hasil-honor-${row.guru_id}">${formatRupiah(row.nominal)}</td>
                        <td>
                            <button class="btn btn-sm btn-primary btn-edit <?= $gaji->status == 'kunci' ? 'disabled' : '' ?>" data-id="${row.id}" data-guru_id="${row.guru_id}" data-potongan_id="${row.potongan_id}">Edit Potongan</button>
                        </td>
                    </tr>
                `);

            $('#list-guru tbody').append($row);
        });
    }

    function renderPagination(meta) {
        const pag = document.getElementById('pagination');
        pag.innerHTML = `
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-rounded"></ul>
                </nav>
            `;

        const ul = pag.querySelector('ul');

        const current = meta.page;
        const last = meta.lastPage;
        const delta = 1;

        function addButton(label, page = null, active = false, disabled = false) {

            let liClass = 'page-item';
            if (active) liClass += ' active';
            if (disabled) liClass += ' disabled';

            let content = label;
            if (label === '«') {
                content = `<i class="icon-base bx bx-chevrons-left icon-sm"></i>`;
                liClass += ' first';
            }
            if (label === '»') {
                content = `<i class="icon-base bx bx-chevrons-right icon-sm"></i>`;
                liClass += ' last';
            }

            ul.innerHTML += `
                    <li class="${liClass}">
                        <a class="page-link"
                        href="javascript:void(0);"
                        ${(!disabled && page) ? `onclick="goPage(${page})"` : ''}>
                        ${content}
                        </a>
                    </li>
                `;
        }

        // Prev
        addButton('«', current - 1, false, current === 1);

        // Page 1
        addButton(1, 1, current === 1);

        let start = Math.max(2, current - delta);
        let end = Math.min(last - 1, current + delta);

        if (start > 2) addButton('...', null, false, true);

        for (let i = start; i <= end; i++) {
            addButton(i, i, current === i);
        }

        if (end < last - 1) addButton('...', null, false, true);

        // Last page
        if (last > 1) addButton(last, last, current === last);

        // Next
        addButton('»', current + 1, false, current === last);
    }


    function goPage(page) {
        state.page = page;
        loadData();
    }

    function sort(field) {
        state.sortDir = state.sortDir === 'ASC' ? 'DESC' : 'ASC';
        state.sortBy = field;
        loadData();
    }

    function info(perpage, page, total) {
        document.getElementById('startRecord').textContent = (page - 1) * perpage + 1;
        document.getElementById('endRecord').textContent = Math.min(page * perpage, total);
        document.getElementById('totalRecords').textContent = total;
    }

    /* ===== EVENTS ===== */
    document.getElementById('search').addEventListener('input', e => {
        state.search = e.target.value;
        state.page = 1;
        loadData();
        info(state.perPage, state.page, state.total);
    });

    document.getElementById('perPage').addEventListener('change', e => {
        state.perPage = e.target.value;
        state.page = 1;
        loadData();
        info(state.perPage, state.page, 0);
    });

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    }

    loadData();
</script>
<script>
    $(document).on('click', '.btn-edit', function() {
        var guru_id = $(this).data('guru_id');
        var potongan_id = $(this).data('potongan_id');
        var id = $(this).data('id');

        $('#hasil-tampil').text('Proses pengambilan data.....')
        $('#edit-modal').modal('show');
        $.ajax({
            type: 'POST',
            url: '<?= base_url('honor/ambil_data'); ?>',
            dataType: 'json',
            data: {
                guru_id: guru_id,
                potongan_id: potongan_id
            },
            success: function(response) {
                const rows = generateTableRows(response.data);
                $('#table-potongan tbody').html(rows);
                // $('#guru_id').val(guru_id);
                // $('#potongan_id').val(potongan_id);
                $('#id').val(id);
                $('.btn-close').attr('value', response.id);
                $('.uang').mask('000.000.000.000', {
                    reverse: true
                });
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        })
    })

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
                $('#hasil-honor-' + response.guru_id).text(formatRupiah(response.data))
                // alert('total-hasil-' + response.id)
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        })
    })

    $(document).on('submit', '.form-clone', function(e) {
        e.preventDefault(); // Mencegah form dari reload halaman

        var id_asal = $('#id_asal').val();
        var dipilih = $('#dipilih').val();

        var $button = $('#proses-clone');
        $('#dipilih').prop('disabled', true);
        $button.prop('disabled', true);
        $button.text('Cloning on Process. Please wait. Jangan di lighulih takut burung!!');

        $.ajax({
            url: '<?= base_url("honor/getDataguru") ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                const hasil = $('#view-hasil');
                let berhasil = 0;
                let gagal = 0;


                function updateProgress() {
                    hasil.html(`
                            <strong class="mb-1">Total success : ${berhasil}</strong><br>
                            <strong class="mb-1 text-danger">Total error : ${gagal}</strong><br>
                        `);
                }

                const ajaxRequests = response.map((item, index) => {
                    return new Promise((resolve) => {
                        setTimeout(() => {
                            $.ajax({
                                url: '<?= base_url("honor/clonePotongan") ?>',
                                type: 'POST',
                                data: {
                                    guru_id: item.guru_id,
                                    id_asal: id_asal,
                                    dipilih: dipilih
                                },
                                dataType: 'json',
                                success: function(response) {
                                    berhasil++;
                                    updateProgress();
                                    console.log(response.message);
                                },
                                error: function() {
                                    gagal++;
                                    updateProgress();
                                    console.log(response.message);
                                },
                                complete: resolve
                            });
                        }, index * 500);
                    });
                });

                Promise.all(ajaxRequests)
                    .then(function() {
                        window.location.reload()
                    })
                    .catch(function(error) {
                        console.error('Ada permintaan AJAX yang gagal', error);
                    });
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                console.log(status);
                console.log(error);
                // alert(xhr.responseText);
            }
        });
    });


    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    }
</script>