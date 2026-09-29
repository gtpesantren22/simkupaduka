<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Input Jam Guru (PTTY)</div>
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
                        <?php if ($gaji->status != 'kunci'): ?>
                            <!-- <h5>Tambahan Guru</h5> -->
                            <!-- <form action="<?= base_url('honor/addPtty') ?>" method="post">
                                <input type="hidden" name="honor_id" value="<?= $honor->honor_id ?>">
                                <input type="hidden" name="bulan" value="<?= $honor->bulan ?>">
                                <input type="hidden" name="tahun" value="<?= $honor->tahun ?>">
                                <div class="form-group row">
                                    <label class="col-form-label col-lg-2">Nama Guru</label>
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <select class="form-control single-select" name="guru_id" id="nama_guru" required>
                                            <option value="">-pilih guru-</option>
                                            <?php foreach ($ptty as $guru): ?>
                                                <option value="<?= $guru->guru_id ?>"><?= $guru->nama . ' - ' . $guru->lembaga ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <button type="submit" class="btn btn-sm btn-success">Tambahkan</button>
                                    </div>
                                </div>
                            </form> -->
                            <hr>
                        <?php endif ?>
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
                                        type="text"
                                        id="search"
                                        class="form-control"
                                        placeholder="Cari data...">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="table-honor" class="table table-striped table-bordered align-middle" style="width:100%">
                                <thead>
                                    <tr style="color: white; background-color: #008CFF; font-weight: bold;">
                                        <th style="width: 5%;">No</th>
                                        <th style="width: 15%;">Bulan</th>
                                        <th style="width: 25%;">Nama Guru</th>
                                        <th style="width: 10%;">Ket</th>
                                        <th style="width: 12%;">Jml Jam</th>
                                        <th style="width: 10%;">Hasil</th>
                                        <th style="width: 23%;">Status / Akumulasi Jam</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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

                        <!-- Card Peringatan Batas Maksimal Jam PTTY -->
                        <div class="card border-0 border-start border-4 border-warning shadow-sm mt-4 bg-light-warning">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="fs-2 text-warning lh-1">
                                        <i class="bx bx-error-circle"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold text-dark mb-2">Batas Maksimal Jam PTTY</h5>
                                        <p class="mb-2 text-dark">
                                            Jumlah jam kehadiran PTTY yang diperhitungkan untuk honor insentif maksimal <strong>20 jam per minggu</strong> dan <strong>80 jam per bulan</strong>.
                                        </p>
                                        <p class="mb-2 text-dark">
                                            Jam kehadiran yang melebihi batas tersebut <strong>tidak diperhitungkan</strong> dalam pembayaran honor insentif.
                                        </p>
                                        <p class="mb-2 text-dark">
                                            Apabila terdapat kelebihan jam, silakan dikoordinasikan terlebih dahulu dengan <strong>Biro Pendidikan</strong>.
                                        </p>
                                        <p class="mb-0 text-muted fst-italic">
                                            Silakan periksa kembali data sebelum disimpan.
                                        </p>
                                    </div>
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
<script src="<?= base_url('vertical/'); ?>assets/js/jquery.min.js"></script>

<script>
    let state = {
        page: 1,
        perPage: 10,
        search: '',
        sortBy: 'nama',
        sortDir: 'ASC',
        total: 0,
        honor_id: '<?= $honor_id ?>'
    };

    function loadData() {

        const params = new URLSearchParams({
            page: state.page,
            perPage: state.perPage,
            search: state.search,
            sortBy: state.sortBy,
            sortDir: state.sortDir,
            honor_id: state.honor_id
        }).toString();

        fetch(`<?= base_url('honor/rincian') ?>?${params}`)
            .then(res => res.json())
            .then(res => {
                renderTable(res.data, res);
                renderPagination(res);
                state.total = res.total;
                info(state.perPage, state.page, state.total);
            });
    }

    function renderTable(data, meta) {
        const tbody = document.getElementById('table-honor').querySelector('tbody');
        tbody.innerHTML = '';

        if (!Array.isArray(data)) return;
        let start = (meta.page - 1) * meta.perPage;

        data.forEach((row, index) => {
            let isOver = row.is_over_limit === true;
            let rowClass = isOver ? 'table-warning' : '';
            
            let statusHtml = '';
            if (isOver) {
                statusHtml = `
                    <div class="d-flex flex-column gap-1">
                        <div>
                            <span class="badge bg-danger text-white fs-12 px-2 py-1 shadow-sm">
                                <i class="bx bx-error-circle me-1"></i> Total: ${row.total_akumulasi} Jam (> 80 JP)
                            </span>
                        </div>
                        <div class="text-danger small fw-bold">
                            <i class="bx bx-error align-middle"></i> Melebihi batas maksimal (${row.kelebihan_jam} jam lebih)
                        </div>
                        ${row.rincian_lembaga ? `<div class="text-muted fs-11"><i class="bx bx-buildings align-middle"></i> ${row.rincian_lembaga}</div>` : ''}
                    </div>
                `;
            } else if (row.total_akumulasi > 0) {
                statusHtml = `
                    <div class="d-flex flex-column gap-1">
                        <div>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-12">
                                <i class="bx bx-check-circle me-1"></i> Total: ${row.total_akumulasi} Jam
                            </span>
                        </div>
                        ${row.rincian_lembaga ? `<div class="text-muted fs-11"><i class="bx bx-buildings align-middle"></i> ${row.rincian_lembaga}</div>` : ''}
                    </div>
                `;
            } else {
                statusHtml = `<span class="text-muted fs-12">-</span>`;
            }

            const $row = $(`
                    <tr class="${rowClass}" id="row-guru-${row.guru_id}">
                        <td class="text-center">${start + index + 1}</td>
                        <td id="ket-bulan-${row.guru_id}">${row.bulan ? (row.bulan + ' ' + row.tahun) : '-'}</td>
                        <td>
                            <span class="fw-bold">${row.nama}</span>
                            <div class="text-muted fs-11">${row.satminkal || ''}</div>
                        </td>
                        <td>${row.ket}</td>
                        <td>
                            <input type="number" step="any" class="form-control form-control-sm form-input ${isOver ? 'is-invalid' : ''}" 
                                id="input-jam-${row.guru_id}"
                                <?= $gaji->status == 'kunci' ? 'disabled' : '' ?> 
                                data-id="${row.id}" 
                                data-honor_id="${row.honor_id}" 
                                data-guru_id="${row.guru_id}" 
                                data-satminkal="${row.satminkal}" 
                                data-ket="${row.ket}" 
                                data-satminkal_id="${row.satminkal_id}" 
                                value="${row.hadir}">
                        </td>
                        <td id="hasil-honor-${row.guru_id}" class="fw-bold">${row.hadir} jam</td>
                        <td id="status-akumulasi-${row.guru_id}">${statusHtml}</td>
                    </tr>
                `);

            $('#table-honor tbody').append($row);
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

        const current = Number(meta.page);
        const last = Number(meta.lastPage);
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
<?php if ($gaji->status != 'kunci'): ?>
    <script>
        $('#table-honor').on('change', '.form-input', function() {

            var $input = $(this); // 🔥 simpan reference element

            var newValue = $input.val();
            var id = $input.data('id');
            var honor_id = $input.data('honor_id');
            var guru_id = $input.data('guru_id');
            var satminkal = $input.data('satminkal');
            var ket = $input.data('ket');
            var satminkal_id = $input.data('satminkal_id');

            $.ajax({
                url: '<?= base_url("honor/updateJam") ?>',
                type: 'POST',
                data: {
                    id: id,
                    value: newValue,
                    guru_id: guru_id,
                    satminkal: satminkal,
                    satminkal_id: satminkal_id,
                    honor_id: honor_id,
                    ket: ket
                },
                dataType: 'json',
                success: function(response) {

                    if (response.status == 'ok') {

                        // 🔥 REPLACE data-id dengan ID baru dari backend
                        $input.attr('data-id', response.newId);
                        $input.data('id', response.newId); // penting agar cache jQuery ikut berubah

                        $(`#hasil-honor-${guru_id}`).text(response.besaran + ` jam`);
                        $(`#ket-bulan-${guru_id}`).text(response.ket_bulan);

                        // 🔥 Update status akumulasi & alert row secara real-time
                        const $tr = $(`#row-guru-${guru_id}`);
                        const $statusCell = $(`#status-akumulasi-${guru_id}`);
                        const $inputEl = $(`#input-jam-${guru_id}`);

                        if (response.is_over_limit) {
                            $tr.addClass('table-warning');
                            $inputEl.addClass('is-invalid');
                            $statusCell.html(`
                                <div class="d-flex flex-column gap-1">
                                    <div>
                                        <span class="badge bg-danger text-white fs-12 px-2 py-1 shadow-sm">
                                            <i class="bx bx-error-circle me-1"></i> Total: ${response.total_jam_semua} Jam (> 80 JP)
                                        </span>
                                    </div>
                                    <div class="text-danger small fw-bold">
                                        <i class="bx bx-error align-middle"></i> Melebihi batas maksimal (${response.kelebihan_jam} jam lebih)
                                    </div>
                                    ${response.rincian_lembaga ? `<div class="text-muted fs-11"><i class="bx bx-buildings align-middle"></i> ${response.rincian_lembaga}</div>` : ''}
                                </div>
                            `);
                        } else if (response.total_jam_semua > 0) {
                            $tr.removeClass('table-warning');
                            $inputEl.removeClass('is-invalid');
                            $statusCell.html(`
                                <div class="d-flex flex-column gap-1">
                                    <div>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fs-12">
                                            <i class="bx bx-check-circle me-1"></i> Total: ${response.total_jam_semua} Jam
                                        </span>
                                    </div>
                                    ${response.rincian_lembaga ? `<div class="text-muted fs-11"><i class="bx bx-buildings align-middle"></i> ${response.rincian_lembaga}</div>` : ''}
                                </div>
                            `);
                        } else {
                            $tr.removeClass('table-warning');
                            $inputEl.removeClass('is-invalid');
                            $statusCell.html(`<span class="text-muted fs-12">-</span>`);
                        }

                    } else {
                        alert('Gagal mengupdate data');
                        console.log(response);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan saat mengupdate data');
                    console.log(xhr.responseText);
                }
            });

        });
    </script>
<?php endif ?>