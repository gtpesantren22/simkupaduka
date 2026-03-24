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
                            <table id="table-honor" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr style="color: white; background-color: #008CFF; font-weight: bold;">
                                        <th>No</th>
                                        <th>Bulan</th>
                                        <th>Nama</th>
                                        <th>Ket</th>
                                        <th>Jml Jam</th>
                                        <th>Hasil</th>
                                        <th></th>
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
        // console.log(data);
        data.forEach((row, index) => {
            // console.log(row.lembaga_id + ' <> ' + row.lembaga_terpilih + ' || ' + row.lembaga_user);
            let wrn = 'black'
            const $row = $(`
                    <tr style="color: ${wrn}">
                        <td>${start + index + 1}</td>
                        <td id="ket-bulan-${row.guru_id}">${row.bulan+' '+row.tahun}</td>
                        <td>${row.nama}</td>
                        <td>${row.ket}</td>
                        <td><input type="text" class="form-control form-input" data-id="${row.id}" data-honor_id="${row.honor_id}" data-guru_id="${row.guru_id}" data-satminkal="${row.satminkal}" data-ket="${row.ket}" data-satminkal_id="${row.satminkal_id}" value="${row.hadir}"></td>
                        <td id="hasil-honor-${row.guru_id}">${row.hadir} jam</td>
                        <td></td>
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