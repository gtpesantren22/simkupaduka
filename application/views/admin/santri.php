<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Santri</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-folder-open"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Master Data</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <button type="button" id="btn-sinkron" class="btn btn-primary btn-sm me-2"><i class="bx bx-sync"></i> Sinkron Data</button>
                    <button type="button" id="btn-sinkron-lembaga" class="btn btn-success btn-sm me-2"><i class="bx bx-sync"></i> Sinkron Lembaga</button>
                    <button type="button" id="btn-kirim-santri" class="btn btn-info btn-sm text-white"><i class="bx bx-send"></i> Kirim Data Santri</button>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <!-- Filters -->
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6 col-lg-3">
                                <label class="form-label font-weight-bold">Filter Lembaga</label>
                                <select id="filter-lembaga" class="form-select form-select-sm">
                                    <option value="">Semua Lembaga</option>
                                    <?php foreach ($lembaga_list as $l) : ?>
                                        <option value="<?= htmlspecialchars($l->t_formal) ?>"><?= htmlspecialchars($l->t_formal) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12 col-md-6 col-lg-2">
                                <label class="form-label font-weight-bold">Filter Customer ID</label>
                                <select id="filter-cost" class="form-select form-select-sm">
                                    <option value="">Semua Status</option>
                                    <option value="ada">Ada Customer ID</option>
                                    <option value="tidak">Tidak Ada Customer ID</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <label class="form-label font-weight-bold">Filter Keterangan</label>
                                <select id="filter-keterangan" class="form-select form-select-sm">
                                    <option value="">Semua Status</option>
                                    <option value="0">Bayar</option>
                                    <option value="1">Ust/Usdtz</option>
                                    <option value="2">Khaddam</option>
                                    <option value="3">Gratis</option>
                                    <option value="4">Berhenti</option>
                                    <option value="5">Sakit</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6 col-lg-2">
                                <label class="form-label font-weight-bold">Filter Keaktifan</label>
                                <select id="filter-status" class="form-select form-select-sm">
                                    <option value="Y">Aktif</option>
                                    <option value="N">Non-Aktif</option>
                                    <option value="all">Semua</option>
                                </select>
                            </div>
                        </div>

                        <style>
                            .cursor-pointer {
                                cursor: pointer;
                            }
                            .sortable:hover {
                                background-color: rgba(0, 0, 0, 0.05);
                            }
                        </style>

                        <!-- Top Controls: Per Page and Search -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-12 col-md-6 d-flex align-items-center gap-2 mb-2 mb-md-0">
                                <span>Tampilkan</span>
                                <select id="custom-per-page" class="form-select form-select-sm" style="width: 80px;">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <span>data</span>
                            </div>
                            <div class="col-12 col-md-6 d-flex justify-content-md-end mb-2 mb-md-0">
                                <div class="input-group input-group-sm" style="max-width: 300px;">
                                    <span class="input-group-text bg-transparent"><i class="bx bx-search"></i></span>
                                    <input type="text" id="custom-search" class="form-control" placeholder="Cari santri...">
                                </div>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover align-middle" style="width:100%" id="custom-santri-table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">No</th>
                                        <th class="sortable cursor-pointer" data-column="1">NIS <i class="bx bx-sort text-muted"></i></th>
                                        <th class="sortable cursor-pointer" data-column="2">Customer ID <i class="bx bx-sort text-muted"></i></th>
                                        <th class="sortable cursor-pointer" data-column="3">Nama <i class="bx bx-sort-up text-dark"></i></th>
                                        <th class="sortable cursor-pointer" data-column="4">Kelas Formal <i class="bx bx-sort text-muted"></i></th>
                                        <th>Tempat Kos</th>
                                        <th>Keterangan</th>
                                        <th style="width: 250px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="custom-santri-tbody">
                                    <!-- Dynamic rows loaded here -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Bottom Controls: Info and Pagination -->
                        <div class="row mt-3 align-items-center">
                            <div class="col-12 col-md-6 text-center text-md-start mb-2 mb-md-0 text-muted small" id="custom-table-info">
                                Menampilkan 0 sampai 0 dari 0 data
                            </div>
                            <div class="col-12 col-md-6 d-flex justify-content-center justify-content-md-end">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination pagination-sm mb-0" id="custom-pagination">
                                        <!-- Dynamic pagination links loaded here -->
                                    </ul>
                                </nav>
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

<!-- Modal Sinkronisasi -->
<div class="modal fade" id="modalSinkron" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalSinkronLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSinkronLabel">Sinkronisasi Data Santri</h5>
            </div>
            <div class="modal-body">
                <p id="sync-status">Menghubungi API, mohon tunggu...</p>
                <div class="progress mb-3" style="height: 20px;">
                    <div id="sync-progress" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>
                <div class="text-end text-muted small" id="sync-details">Halaman 0 dari 0 (0 / 0 Data)</div>
            </div>
            <div class="modal-footer" id="modal-footer-failed" style="display: none;">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Cost ID -->
<div class="modal fade" id="modalEditCost" tabindex="-1" aria-labelledby="modalEditCostLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= base_url(($controller ?? 'admin') . '/update_cost_id') ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditCostLabel">Update Customer ID</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Santri</label>
                        <input type="text" id="edit-nama" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIS</label>
                        <input type="text" id="edit-nis" name="nis" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Customer ID</label>
                        <input type="text" id="edit-cost-id" name="cost_id" class="form-control" required placeholder="Masukkan Customer ID">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sinkronisasi Lembaga -->
<div class="modal fade" id="modalSinkronLembaga" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalSinkronLembagaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSinkronLembagaLabel">Sinkronisasi Lembaga Santri</h5>
            </div>
            <div class="modal-body">
                <p id="sync-lembaga-status">Menghubungi API, mohon tunggu...</p>
                <div class="progress mb-3" style="height: 20px;">
                    <div id="sync-lembaga-progress" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>
                <div class="text-end text-muted small" id="sync-lembaga-details">Memproses 0 dari 0 Santri</div>
            </div>
            <div class="modal-footer" id="modal-lembaga-footer-failed" style="display: none;">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Kirim Data Santri -->
<div class="modal fade" id="modalKirimSantri" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalKirimSantriLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalKirimSantriLabel">Kirim Data Santri</h5>
            </div>
            <div class="modal-body">
                <p id="send-status">Menghubungkan database, mohon tunggu...</p>
                <div class="progress mb-3" style="height: 20px;">
                    <div id="send-progress" class="progress-bar progress-bar-striped progress-bar-animated bg-info text-white" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>
                <div class="text-end text-muted small" id="send-details">Memproses 0 dari 0 Data</div>
            </div>
            <div class="modal-footer" id="modal-send-footer-failed" style="display: none;">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Santri -->
<div class="modal fade" id="modalDetailSantri" tabindex="-1" aria-labelledby="modalDetailSantriLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalDetailSantriLabel"><i class="bx bx-user"></i> Detail Santri</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <!-- Section 1: Identitas Diri -->
                    <div class="col-12">
                        <h6 class="border-bottom pb-2 text-info"><i class="bx bx-id-card"></i> Identitas Diri</h6>
                    </div>
                    <div class="col-12 col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr><th width="35%">Nama</th><td>: <span id="det-nama">-</span></td></tr>
                            <tr><th>NIS</th><td>: <span id="det-nis">-</span></td></tr>
                            <tr><th>NISN</th><td>: <span id="det-nisn">-</span></td></tr>
                            <tr><th>NIK</th><td>: <span id="det-nik">-</span></td></tr>
                            <tr><th>No. KK</th><td>: <span id="det-nokk">-</span></td></tr>
                        </table>
                    </div>
                    <div class="col-12 col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr><th width="35%">Jenis Kelamin</th><td>: <span id="det-jkl">-</span></td></tr>
                            <tr><th>Tempat Lahir</th><td>: <span id="det-tempat">-</span></td></tr>
                            <tr><th>Tanggal Lahir</th><td>: <span id="det-tanggal">-</span></td></tr>
                            <tr><th>Pendidikan Formal</th><td>: <span id="det-formal">-</span></td></tr>
                            <tr><th>Tempat Kos</th><td>: <span id="det-kos">-</span></td></tr>
                            <tr><th>Keterangan</th><td>: <span id="det-ket">-</span></td></tr>
                        </table>
                    </div>

                    <!-- Section 2: Alamat Lengkap -->
                    <div class="col-12">
                        <h6 class="border-bottom pb-2 text-info"><i class="bx bx-home"></i> Alamat Lengkap</h6>
                    </div>
                    <div class="col-12 col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr><th width="35%">Jalan/Dusun</th><td>: <span id="det-jln">-</span></td></tr>
                            <tr><th>RT / RW</th><td>: <span id="det-rtrw">-</span></td></tr>
                            <tr><th>Desa</th><td>: <span id="det-desa">-</span></td></tr>
                            <tr><th>Kecamatan</th><td>: <span id="det-kec">-</span></td></tr>
                        </table>
                    </div>
                    <div class="col-12 col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr><th width="35%">Kabupaten</th><td>: <span id="det-kab">-</span></td></tr>
                            <tr><th>Provinsi</th><td>: <span id="det-prov">-</span></td></tr>
                            <tr><th>Kode Pos</th><td>: <span id="det-kodepos">-</span></td></tr>
                        </table>
                    </div>

                    <!-- Section 3: Kontak & Orang Tua -->
                    <div class="col-12">
                        <h6 class="border-bottom pb-2 text-info"><i class="bx bx-group"></i> Kontak & Identitas Orang Tua / Wali</h6>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card bg-light border-0 shadow-none p-2 mb-0">
                            <h6 class="text-secondary small font-weight-bold">Ayah Kandung</h6>
                            <table class="table table-sm table-borderless mb-0">
                                <tr><th>Nama</th><td>: <span id="det-ayah-nama">-</span></td></tr>
                                <tr><th>NIK</th><td>: <span id="det-ayah-nik">-</span></td></tr>
                                <tr><th>Pendidikan</th><td>: <span id="det-ayah-pend">-</span></td></tr>
                                <tr><th>Pekerjaan</th><td>: <span id="det-ayah-pkj">-</span></td></tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card bg-light border-0 shadow-none p-2 mb-0">
                            <h6 class="text-secondary small font-weight-bold">Ibu Kandung</h6>
                            <table class="table table-sm table-borderless mb-0">
                                <tr><th>Nama</th><td>: <span id="det-ibu-nama">-</span></td></tr>
                                <tr><th>NIK</th><td>: <span id="det-ibu-nik">-</span></td></tr>
                                <tr><th>Pendidikan</th><td>: <span id="det-ibu-pend">-</span></td></tr>
                                <tr><th>Pekerjaan</th><td>: <span id="det-ibu-pkj">-</span></td></tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card bg-light border-0 shadow-none p-2 mb-0">
                            <h6 class="text-secondary small font-weight-bold">Wali</h6>
                            <table class="table table-sm table-borderless mb-0">
                                <tr><th>Nama</th><td>: <span id="det-wali-nama">-</span></td></tr>
                                <tr><th>NIK</th><td>: <span id="det-wali-nik">-</span></td></tr>
                                <tr><th>Pendidikan</th><td>: <span id="det-wali-pend">-</span></td></tr>
                                <tr><th>Pekerjaan</th><td>: <span id="det-wali-pkj">-</span></td></tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mt-3">
                        <table class="table table-sm table-borderless">
                            <tr><th width="35%">No. Handphone</th><td>: <span id="det-hp" class="font-weight-bold text-dark">-</span></td></tr>
                        </table>
                    </div>
                    <div class="col-12 col-md-6 mt-3">
                        <table class="table table-sm table-borderless">
                            <tr><th width="35%">Email</th><td>: <span id="det-email">-</span></td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        var myModal = new bootstrap.Modal(document.getElementById('modalSinkron'));
        var editModal = new bootstrap.Modal(document.getElementById('modalEditCost'));
        var myLembagaModal = new bootstrap.Modal(document.getElementById('modalSinkronLembaga'));
        var myKirimModal = new bootstrap.Modal(document.getElementById('modalKirimSantri'));
        var detailModal = new bootstrap.Modal(document.getElementById('modalDetailSantri'));

        var currentPage = 1;
        var perPage = 10;
        var sortColumn = 3; // Default Sort: Nama
        var sortDir = 'asc'; // Default Sort Dir: asc
        var searchQuery = '';

        function loadSantriData() {
            var filterLembaga = $('#filter-lembaga').val();
            var filterCost = $('#filter-cost').val();
            var filterKeterangan = $('#filter-keterangan').val();
            var filterStatus = $('#filter-status').val();
            
            var start = (currentPage - 1) * perPage;

            var postData = {
                draw: 1,
                start: start,
                length: perPage,
                search: {
                    value: searchQuery
                },
                order: [
                    {
                        column: sortColumn,
                        dir: sortDir
                    }
                ],
                filter_lembaga: filterLembaga,
                filter_cost: filterCost,
                filter_keterangan: filterKeterangan,
                filter_status: filterStatus
            };

            $('#custom-santri-tbody').html(`
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>
                        <span class="ms-2">Memuat data...</span>
                    </td>
                </tr>
            `);

            $.ajax({
                url: "<?= base_url(($controller ?? 'admin') . '/santri_list_ajax') ?>",
                type: "POST",
                data: postData,
                dataType: "json",
                success: function(response) {
                    var tbody = $('#custom-santri-tbody');
                    tbody.empty();

                    if (!response.data || response.data.length === 0) {
                        tbody.html(`
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Tidak ada data santri yang ditemukan.</td>
                            </tr>
                        `);
                        $('#custom-table-info').text('Menampilkan 0 sampai 0 dari 0 data');
                        $('#custom-pagination').empty();
                        return;
                    }

                    response.data.forEach(function(row) {
                        tbody.append(`
                            <tr>
                                <td>${row.no}</td>
                                <td class="font-monospace">${row.nis}</td>
                                <td class="font-monospace">${row.cost_id}</td>
                                <td class="fw-bold">${row.nama}</td>
                                <td>${row.kelas_formal}</td>
                                <td>${row.tempat_kos}</td>
                                <td>${row.status_ket}</td>
                                <td>${row.aksi}</td>
                            </tr>
                        `);
                    });

                    var from = start + 1;
                    var to = start + response.data.length;
                    var total = response.recordsFiltered;
                    $('#custom-table-info').text(`Menampilkan ${from} sampai ${to} dari ${total} data (disaring dari ${response.recordsTotal} total data)`);

                    renderPagination(total);
                },
                error: function() {
                    $('#custom-santri-tbody').html(`
                        <tr>
                            <td colspan="8" class="text-center text-danger py-4">
                                <i class="bx bx-error-circle fs-4"></i><br>
                                Gagal memuat data dari server.
                            </td>
                        </tr>
                    `);
                }
            });
        }

        function renderPagination(totalRecords) {
            var totalPages = Math.ceil(totalRecords / perPage);
            var pagination = $('#custom-pagination');
            pagination.empty();

            if (totalPages <= 1) return;

            var prevClass = (currentPage === 1) ? 'disabled' : '';
            pagination.append(`
                <li class="page-item ${prevClass}">
                    <a class="page-link" href="javascript:void(0)" onclick="changePage(${currentPage - 1})">Prev</a>
                </li>
            `);

            var startPage = Math.max(1, currentPage - 2);
            var endPage = Math.min(totalPages, currentPage + 2);

            if (startPage > 1) {
                pagination.append(`
                    <li class="page-item">
                        <a class="page-link" href="javascript:void(0)" onclick="changePage(1)">1</a>
                    </li>
                `);
                if (startPage > 2) {
                    pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
                }
            }

            for (var i = startPage; i <= endPage; i++) {
                var activeClass = (i === currentPage) ? 'active' : '';
                pagination.append(`
                    <li class="page-item ${activeClass}">
                        <a class="page-link" href="javascript:void(0)" onclick="changePage(${i})">${i}</a>
                    </li>
                `);
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
                }
                pagination.append(`
                    <li class="page-item">
                        <a class="page-link" href="javascript:void(0)" onclick="changePage(${totalPages})">${totalPages}</a>
                    </li>
                `);
            }

            var nextClass = (currentPage === totalPages) ? 'disabled' : '';
            pagination.append(`
                <li class="page-item ${nextClass}">
                    <a class="page-link" href="javascript:void(0)" onclick="changePage(${currentPage + 1})">Next</a>
                </li>
            `);
        }

        window.changePage = function(page) {
            currentPage = page;
            loadSantriData();
        };

        // Load initially
        loadSantriData();

        // Sort behavior
        $('#custom-santri-table th.sortable').on('click', function() {
            var col = $(this).data('column');
            if (sortColumn === col) {
                sortDir = (sortDir === 'asc') ? 'desc' : 'asc';
            } else {
                sortColumn = col;
                sortDir = 'asc';
            }

            $('#custom-santri-table th.sortable').each(function() {
                var c = $(this).data('column');
                var icon = $(this).find('i');
                icon.removeClass('bx-sort-up bx-sort-down text-dark').addClass('bx-sort text-muted');
                if (c === sortColumn) {
                    icon.removeClass('bx-sort text-muted').addClass(sortDir === 'asc' ? 'bx-sort-up text-dark' : 'bx-sort-down text-dark');
                }
            });

            currentPage = 1;
            loadSantriData();
        });

        // Search Input (with debounce)
        var searchTimeout = null;
        $('#custom-search').on('input', function() {
            clearTimeout(searchTimeout);
            searchQuery = $(this).val();
            searchTimeout = setTimeout(function() {
                currentPage = 1;
                loadSantriData();
            }, 400);
        });

        // Per Page Change
        $('#custom-per-page').on('change', function() {
            perPage = parseInt($(this).val());
            currentPage = 1;
            loadSantriData();
        });

        // Filters
        $('#filter-lembaga, #filter-cost, #filter-keterangan, #filter-status').on('change', function() {
            currentPage = 1;
            loadSantriData();
        });

        // Event delegation to handle click edit cost_id dynamically loaded
        $(document).on('click', '.btn-edit-cost', function() {
            var nis = $(this).data('nis');
            var nama = $(this).data('nama');
            var costId = $(this).data('costid');

            $('#edit-nis').val(nis);
            $('#edit-nama').val(nama);
            $('#edit-cost-id').val(costId);

            editModal.show();
        });

        // Event delegation to handle click btn-sync-siswa dynamically loaded
        $(document).on('click', '.btn-sync-siswa', function() {
            var btn = $(this);
            var idSantri = btn.data('id');

            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>...');
            $.ajax({
                url: '<?= base_url(($controller ?? "admin") . "/sinkron_siswa_single") ?>',
                type: 'GET',
                data: { id_santri: idSantri },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        loadSantriData();
                    } else {
                        alert('Gagal: ' + response.message);
                        btn.prop('disabled', false).html('<i class="bx bx-sync"></i> Sync');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan server.');
                    btn.prop('disabled', false).html('<i class="bx bx-sync"></i> Sync');
                }
            });
        });

        // Event delegation to handle click btn-toggle-status dynamically loaded
        $(document).on('click', '.btn-toggle-status', function() {
            var btn = $(this);
            var idSantri = btn.data('id');
            var status = btn.data('status');
            var nama = btn.data('nama');
            var label = (status === 'Y') ? 'mengaktifkan kembali' : 'menonaktifkan';

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Anda akan ' + label + ' santri "' + nama + '".',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed || result.value) {
                    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
                    $.ajax({
                        url: '<?= base_url(($controller ?? "admin") . "/toggle_santri_status") ?>',
                        type: 'POST',
                        data: { id_santri: idSantri, status: status },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire(
                                    'Berhasil!',
                                    response.message,
                                    'success'
                                );
                                loadSantriData();
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    response.message,
                                    'error'
                                );
                                loadSantriData();
                            }
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'Terjadi kesalahan server.',
                                'error'
                            );
                            loadSantriData();
                        }
                    });
                }
            });
        });

        // Event delegation to handle delete confirmation safely
        $(document).on('click', '.btn-delete-santri', function(e) {
            e.preventDefault();
            var href = $(this).attr('href');
            var name = $(this).data('nama');
            Swal.fire({
                title: 'Yakin ingin menghapus ?',
                text: 'Data santri "' + name + '" akan dihapus secara permanen dari sistem.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    window.location.href = href;
                }
            });
        });

        // Event delegation to handle click btn-detail-siswa dynamically loaded
        $(document).on('click', '.btn-detail-siswa', function() {
            var btn = $(this);
            var idSantri = btn.data('id');

            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

            $.ajax({
                url: '<?= base_url(($controller ?? "admin") . "/get_student_detail_ajax") ?>/' + idSantri,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    btn.prop('disabled', false).html('<i class="bx bx-info-circle"></i> Detail');
                    if (response.status === 'success') {
                        var s = response.data;
                        
                        // Set text values
                        $('#det-nama').text(s.nama || '-');
                        $('#det-nis').text(s.nis || '-');
                        $('#det-nisn').text(s.nisn || '-');
                        $('#det-nik').text(s.nik || '-');
                        $('#det-nokk').text(s.no_kk || '-');
                        $('#det-jkl').text(s.jkl || '-');
                        $('#det-tempat').text(s.tempat || '-');
                        $('#det-tanggal').text(s.tanggal || '-');
                        $('#det-formal').text((s.k_formal || '') + ' ' + (s.t_formal || ''));
                        $('#det-kos').text(s.tempat_kos_name || '-');
                        $('#det-ket').text(s.status_ket_name || '-');

                        // Address values
                        $('#det-jln').text(s.jln || '-');
                        $('#det-rtrw').text((s.rt || '-') + ' / ' + (s.rw || '-'));
                        $('#det-desa').text(s.desa || '-');
                        $('#det-kec').text(s.kec || '-');
                        $('#det-kab').text(s.kab || '-');
                        $('#det-prov').text(s.prov || '-');
                        $('#det-kodepos').text(s.kd_pos || '-');

                        // Parents
                        $('#det-ayah-nama').text(s.bapak || '-');
                        $('#det-ayah-nik').text(s.nik_a || '-');
                        $('#det-ayah-pend').text(s.pend_a || '-');
                        $('#det-ayah-pkj').text(s.pkj_a || '-');

                        $('#det-ibu-nama').text(s.ibu || '-');
                        $('#det-ibu-nik').text(s.nik_i || '-');
                        $('#det-ibu-pend').text(s.pend_i || '-');
                        $('#det-ibu-pkj').text(s.pkj_i || '-');

                        $('#det-wali-nama').text(s.wali || '-');
                        $('#det-wali-nik').text(s.nik_w || '-');
                        $('#det-wali-pend').text(s.pend_w || '-');
                        $('#det-wali-pkj').text(s.pkj_w || '-');

                        $('#det-hp').text(s.hp || '-');
                        $('#det-email').text(s.email || '-');

                        detailModal.show();
                    } else {
                        alert('Gagal mengambil data detail: ' + response.message);
                    }
                },
                error: function() {
                    btn.prop('disabled', false).html('<i class="bx bx-info-circle"></i> Detail');
                    alert('Terjadi kesalahan koneksi server.');
                }
            });
        });

        $('#btn-sinkron').on('click', function() {
            // Reset modal states
            $('#modal-footer-failed').hide();
            $('#sync-status').text('Memulai sinkronisasi data...');
            $('#sync-progress').css('width', '0%').attr('aria-valuenow', 0).text('0%');
            $('#sync-details').text('Memulai...');

            // Show modal
            myModal.show();

            // Start sync from page 1
            syncPage(1);
        });

        function syncPage(page) {
            $.ajax({
                url: '<?= base_url(($controller ?? "admin") . "/sinkron_batch") ?>',
                type: 'GET',
                data: {
                    page: page
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        var percent = Math.round((response.page / response.last_page) * 100);
                        $('#sync-progress').css('width', percent + '%').attr('aria-valuenow', percent).text(percent + '%');
                        $('#sync-status').text('Sedang memproses data santri...');
                        $('#sync-details').text('Halaman ' + response.page + ' dari ' + response.last_page + ' (' + (response.page * response.processed) + ' / ' + response.total + ' Data)');

                        if (response.page < response.last_page) {
                            // Fetch next page
                            syncPage(response.page + 1);
                        } else {
                            // Clean up local database (soft delete removed central records)
                            $('#sync-status').text('Melakukan pembersihan data lokal...');
                            $.ajax({
                                url: '<?= base_url(($controller ?? "admin") . "/clean_up_local_database") ?>',
                                type: 'GET',
                                dataType: 'json',
                                success: function(cleanRes) {
                                    $('#sync-status').text('Sinkronisasi selesai! Merefresh halaman...');
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 1500);
                                },
                                error: function() {
                                    $('#sync-status').text('Gagal membersihkan data lokal, tetapi sinkron selesai.');
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 2000);
                                }
                            });
                        }
                    } else {
                        // Error returned by API/server
                        $('#sync-status').html('<span class="text-danger">Gagal: ' + response.message + '</span>');
                        $('#modal-footer-failed').show();
                    }
                },
                error: function(xhr, status, error) {
                    $('#sync-status').html('<span class="text-danger">Koneksi terputus atau terjadi kesalahan server.</span>');
                    $('#modal-footer-failed').show();
                }
            });
        }

        $('#btn-sinkron-lembaga').on('click', function() {
            // Reset modal states
            $('#modal-lembaga-footer-failed').hide();
            $('#sync-lembaga-status').text('Memulai sinkronisasi lembaga...');
            $('#sync-lembaga-progress').css('width', '0%').attr('aria-valuenow', 0).text('0%');
            $('#sync-lembaga-details').text('Memulai...');

            // Show modal
            myLembagaModal.show();

            // Start sync from offset 0
            syncLembaga(0);
        });

        function syncLembaga(offset) {
            $.ajax({
                url: '<?= base_url(($controller ?? "admin") . "/sinkron_lembaga_batch") ?>',
                type: 'GET',
                data: {
                    offset: offset
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        if (response.total > 0) {
                            var percent = Math.round((response.offset / response.total) * 100);
                            if (percent > 100) percent = 100;
                            $('#sync-lembaga-progress').css('width', percent + '%').attr('aria-valuenow', percent).text(percent + '%');
                            $('#sync-lembaga-status').text('Sedang memproses lembaga santri...');
                            $('#sync-lembaga-details').text('Memproses ' + response.offset + ' dari ' + response.total + ' Santri');
                        }

                        if (response.processed > 0 && response.offset < response.total) {
                            // Fetch next batch
                            syncLembaga(response.offset);
                        } else {
                            // Completed!
                            $('#sync-lembaga-status').text('Sinkronisasi selesai! Merefresh halaman...');
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);
                        }
                    } else {
                        // Error returned by API/server
                        $('#sync-lembaga-status').html('<span class="text-danger">Gagal: ' + response.message + '</span>');
                        $('#modal-lembaga-footer-failed').show();
                    }
                },
                error: function(xhr, status, error) {
                    $('#sync-lembaga-status').html('<span class="text-danger">Koneksi terputus atau terjadi kesalahan server.</span>');
                    $('#modal-lembaga-footer-failed').show();
                }
            });
        }

        // Handle Kirim Data Santri Button click
        $('#btn-kirim-santri').on('click', function() {
            if (confirm('Apakah Anda yakin ingin mengirim/sinkronisasi semua data santri aktif ke database Kasir dan Dekos?')) {
                // Reset modal states
                $('#modal-send-footer-failed').hide();
                $('#send-status').text('Mengambil total data santri...');
                $('#send-progress').css('width', '0%').attr('aria-valuenow', 0).text('0%');
                $('#send-details').text('Memulai...');

                // Show modal
                myKirimModal.show();

                // Get total records first
                $.ajax({
                    url: '<?= base_url(($controller ?? "admin") . "/get_total_active_santri") ?>',
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        var total = res.total;
                        if (total === 0) {
                            $('#send-status').text('Tidak ada data santri aktif untuk dikirim.');
                            $('#modal-send-footer-failed').show();
                            return;
                        }
                        // Start sending from offset 0
                        sendBatch(0, total);
                    },
                    error: function() {
                        $('#send-status').html('<span class="text-danger">Gagal mengambil total data santri dari database.</span>');
                        $('#modal-send-footer-failed').show();
                    }
                });
            }
        });

        // Recursive batch sending logic
        function sendBatch(offset, total) {
            $.ajax({
                url: '<?= base_url(($controller ?? "admin") . "/kirim_data_santri_batch") ?>',
                type: 'GET',
                data: { offset: offset },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        var currentOffset = response.offset;
                        var percent = Math.round((currentOffset / total) * 100);
                        if (percent > 100) percent = 100;

                        $('#send-progress').css('width', percent + '%').attr('aria-valuenow', percent).text(percent + '%');
                        $('#send-status').text('Sedang mengirim data santri...');
                        $('#send-details').text('Memproses ' + currentOffset + ' dari ' + total + ' Santri');

                        if (response.processed > 0 && currentOffset < total) {
                            // Process next batch
                            sendBatch(currentOffset, total);
                        } else {
                            // Sync completed, now perform target db deactivation/deletion cleanup
                            $('#send-status').text('Membersihkan data terhapus di database tujuan...');
                            $.ajax({
                                url: '<?= base_url(($controller ?? "admin") . "/clean_up_target_databases") ?>',
                                type: 'GET',
                                dataType: 'json',
                                success: function() {
                                    $('#send-status').text('Pengiriman data santri selesai! Merefresh...');
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 1500);
                                },
                                error: function() {
                                    // Not blocking, finish sync
                                    $('#send-status').text('Pengiriman data santri selesai dengan beberapa peringatan cleanup.');
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 2000);
                                }
                            });
                        }
                    } else {
                        $('#send-status').html('<span class="text-danger">Gagal: ' + response.message + '</span>');
                        $('#modal-send-footer-failed').show();
                    }
                },
                error: function() {
                    $('#send-status').html('<span class="text-danger">Koneksi terputus atau terjadi kesalahan server.</span>');
                    $('#modal-send-footer-failed').show();
                }
            });
        }
    });
</script>