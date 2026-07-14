<!--select2 css-->
<link href="<?= base_url('vertical/'); ?>assets/plugins/select2/css/select2.min.css" rel="stylesheet" />
<link href="<?= base_url('vertical/'); ?>assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet" />

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Dekosan</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-folder-open"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Master Data</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-12 col-lg-4">
                <!-- Card Pilih Santri -->
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="mb-3">
                            <h5 class="mb-3">Pilih Santri</h5>
                            <select id="select-santri-dekos" class="form-select w-100 select2-ajax" style="width: 100%;">
                                <option value="">Ketik nama atau NIS santri...</option>
                            </select>
                        </div>
                        <hr>
                        <!-- Placeholder Alert -->
                        <div id="santri-placeholder" class="alert alert-info border-0 bg-info alert-dismissible fade show py-2">
                            <div class="d-flex align-items-center">
                                <div class="font-35 text-dark"><i class='bx bx-info-circle'></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0 text-dark">Informasi</h6>
                                    <div class="text-dark small">Silakan pilih santri terlebih dahulu untuk mengelola riwayat dekosan.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Info Area (Initially Hidden) -->
                        <div id="santri-profile" style="display: none;">
                            <div class="d-flex align-items-center">
                                <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white me-3">
                                    <i class='bx bxs-user'></i>
                                </div>
                                <div>
                                    <h6 class="mb-0" id="profile-nama">-</h6>
                                    <p class="mb-0 text-secondary" id="profile-nis">-</p>
                                </div>
                            </div>
                            <table class="table table-sm no-margin mt-3 mb-0">
                                <tr>
                                    <th style="width: 35%;">Kelas Formal</th>
                                    <td id="profile-kelas">-</td>
                                </tr>
                                <tr>
                                    <th>Madin</th>
                                    <td id="profile-madin">-</td>
                                </tr>
                                <tr>
                                    <th>Kamar</th>
                                    <td id="profile-kamar">-</td>
                                </tr>
                                <tr>
                                    <th>Komplek</th>
                                    <td id="profile-komplek">-</td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td>
                                        <select id="profile-edit-ket" class="form-select py-0 h-auto">
                                            <option value="-">-</option>
                                            <option value="0">Bayar</option>
                                            <option value="1">Ust/Usdtz</option>
                                            <option value="2">Khaddam</option>
                                            <option value="3">Gratis</option>
                                            <option value="4">Berhenti</option>
                                            <option value="5">Sakit</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <!-- Card Riwayat Dekosan -->
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <h5 class="mb-0">Riwayat Dekosan</h5>
                            <button type="button" id="btn-tambah-dekos" class="btn btn-warning btn-sm ms-auto text-dark" disabled>
                                <i class="bx bx-transfer"></i> Pindah
                            </button>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table id="table-history-dekos" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tempat Kos</th>
                                        <th>Bulan Masuk</th>
                                        <th>Bulan Keluar</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="history-dekos-body">
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">Belum ada data santri terpilih</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rekap Jumlah Santri per Tempat Kos -->
        <div class="card radius-10 mb-3 border-top border-3 border-primary">
            <div class="card-body py-3">
                <div class="d-flex align-items-center mb-2">
                    <h6 class="mb-0 text-uppercase text-primary font-weight-bold" style="font-size: 0.85rem; letter-spacing: 0.5px;"><i class="bx bx-stats"></i> Rekap Santri Per Tempat Dekos</h6>
                </div>
                <div class="row g-2">
                    <?php foreach ($tmpKos as $index => $kosName) : ?>
                        <?php if ($index > 0) : ?>
                            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                <div class="p-2 border rounded text-center bg-light">
                                    <div class="text-truncate text-secondary font-weight-bold" style="font-size: 0.75rem;"><?= htmlspecialchars($kosName) ?></div>
                                    <h5 class="mb-0 font-weight-bold text-dark mt-1"><span id="rekap-count-<?= $index ?>"><?= number_format($rekap[$index] ?? 0) ?></span> <span class="small font-12 text-secondary" style="font-size: 0.75rem;">anak</span></h5>
                                    <button type="button" class="btn btn-xs btn-outline-primary mt-2 btn-cek-kos" data-tkos="<?= $index ?>" data-nama="<?= htmlspecialchars($kosName) ?>" style="font-size: 0.7rem; padding: 2px 8px;">
                                        <i class="bx bx-show"></i> Cek
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Modal Rekap Siswa per Tempat Kos -->
        <div class="modal fade" id="modalRekapSiswa" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rekapSiswaTitle">Siswa Terdata</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-sm" style="width:100%" id="table-rekap-siswa">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">No</th>
                                        <th style="width: 15%;">NIS</th>
                                        <th style="width: 35%;">Nama Santri</th>
                                        <th style="width: 25%;">Lembaga</th>
                                        <th style="width: 10%;">Keterangan</th>
                                        <th style="width: 10%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="rekap-siswa-body">
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Memuat data...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Tambah/Edit Dekos -->
<div class="modal fade" id="modalDekosForm" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-dekos" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="dekosFormTitle">Pindah Tempat Dekos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_dekos" id="form-id-dekos">
                    <input type="hidden" name="nis" id="form-nis">

                    <div class="mb-3">
                        <label class="form-label font-weight-bold" id="field-t_kos_label">Tempat Kos Baru</label>
                        <select name="t_kos" id="form-t-kos" class="form-select form-select-sm" required>
                            <option value="">-- Pilih Tempat Kos --</option>
                            <?php foreach ($tmpKos as $index => $kosName) : ?>
                                <?php if ($index > 0) : ?>
                                    <option value="<?= $index ?>"><?= htmlspecialchars($kosName) ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Tanggal Pindah (Only visible in Add/Pindah mode) -->
                    <div class="mb-3" id="field-tanggal_pindah_container">
                        <label class="form-label font-weight-bold">Tanggal Pindah</label>
                        <input type="date" name="tanggal_pindah" id="form-tanggal-pindah" class="form-control form-control-sm">
                    </div>

                    <!-- Tanggal Masuk (Only visible in Edit mode) -->
                    <div class="mb-3" id="field-masuk_container" style="display: none;">
                        <label class="form-label font-weight-bold">Tanggal Masuk</label>
                        <input type="date" name="masuk" id="form-masuk" class="form-control form-control-sm">
                    </div>

                    <!-- Tanggal Keluar (Only visible in Edit mode) -->
                    <div class="mb-3" id="field-keluar_container" style="display: none;">
                        <label class="form-label font-weight-bold">Tanggal Keluar</label>
                        <input type="date" name="keluar" id="form-keluar" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="btn-submit-dekos" class="btn btn-primary btn-sm"><i class="bx bx-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        // Dynamically load select2 script since jQuery is now loaded
        if (typeof $.fn.select2 === 'undefined') {
            var script = document.createElement('script');
            script.src = "<?= base_url('vertical/') ?>assets/plugins/select2/js/select2.min.js";
            script.onload = initDekosPage;
            document.head.appendChild(script);
        } else {
            initDekosPage();
        }

        function initDekosPage() {
            var formModal = new bootstrap.Modal(document.getElementById('modalDekosForm'));
            var selectedNis = null;

            // Initialize Select2 with AJAX
            $('#select-santri-dekos').select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: 'Ketik nama atau NIS santri...',
                allowClear: true,
                ajax: {
                    url: '<?= base_url(($controller ?? "admin") . "/select2_santri_ajax") ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3
            });

            // Initialize Rekap Drilldown Modal
            var rekapModal = new bootstrap.Modal(document.getElementById('modalRekapSiswa'));

            // Handle Select2 Selection Event
            $('#select-santri-dekos').on('select2:select', function(e) {
                var selectData = e.params.data;
                selectedNis = selectData.id;

                // Fetch complete student info from server to ensure profile details are complete
                $.ajax({
                    url: '<?= base_url(($controller ?? "admin") . "/get_student_info_ajax/") ?>' + selectedNis,
                    type: 'GET',
                    dataType: 'json',
                    success: function(student) {
                        if (student) {
                            $('#profile-nama').text(student.nama);
                            $('#profile-nis').text('NIS: ' + student.nis);
                            $('#profile-kelas').text((student.k_formal || '') + ' ' + (student.t_formal || ''));
                            $('#profile-madin').text((student.k_madin || '') + ' ' + (student.r_madin || ''));
                            $('#profile-kamar').text(student.kamar || '-');
                            $('#profile-komplek').text(student.komplek || '-');

                            var ketVal = (student.ket !== null && student.ket !== '' && ['0', '1', '2', '3', '4', '5'].includes(String(student.ket))) ? student.ket : '-';
                            $('#profile-edit-ket').val(ketVal);
                        }
                    }
                });

                // Query history
                $.ajax({
                    url: '<?= base_url(($controller ?? "admin") . "/dekos_history_ajax/") ?>' + selectedNis,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        renderHistoryTable(response.data);
                    }
                });

                // Toggle Views
                $('#santri-placeholder').hide();
                $('#santri-profile').show();
                $('#btn-tambah-dekos').prop('disabled', false);
            });

            // Handle changing student status (keterangan) dropdown
            $('#profile-edit-ket').on('change', function() {
                var newKet = $(this).val();
                if (selectedNis) {
                    $.ajax({
                        url: '<?= base_url(($controller ?? "admin") . "/update_student_ket_ajax") ?>',
                        type: 'POST',
                        data: {
                            nis: selectedNis,
                            ket: newKet
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                lobiboxSuccess(response.message);
                                // Reload rekap counters
                                updateRekap();
                            } else {
                                lobiboxError(response.message);
                            }
                        },
                        error: function() {
                            lobiboxError('Terjadi kesalahan server.');
                        }
                    });
                }
            });

            // Reset when selection is cleared
            $('#select-santri-dekos').on('select2:clear', function() {
                selectedNis = null;
                $('#santri-placeholder').show();
                $('#santri-profile').hide();
                $('#btn-tambah-dekos').prop('disabled', true);
                $('#profile-edit-ket').val('0');

                // Reset history table
                $('#history-dekos-body').html('<tr><td colspan="5" class="text-center text-muted py-4">Belum ada data santri terpilih</td></tr>');
            });

            // Helper to render boarding history table rows
            function renderHistoryTable(data) {
                var tbody = $('#history-dekos-body');
                tbody.empty();

                if (data.length === 0) {
                    tbody.append('<tr><td colspan="5" class="text-center text-muted py-4">Siswa ini belum memiliki riwayat dekosan</td></tr>');
                    return;
                }

                data.forEach(function(row) {
                    tbody.append('<tr>' +
                        '<td>' + row.no + '</td>' +
                        '<td>' + row.tempat_kos + '</td>' +
                        '<td>' + row.masuk + '</td>' +
                        '<td>' + row.keluar + '</td>' +
                        '<td>' + row.aksi + '</td>' +
                        '</tr>');
                });
            }

            // Refresh history helper
            function refreshHistory() {
                if (selectedNis) {
                    $.ajax({
                        url: '<?= base_url(($controller ?? "admin") . "/dekos_history_ajax/") ?>' + selectedNis,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            renderHistoryTable(response.data);
                        }
                    });
                }
            }

            // Handle clicking "Cek" on rekap cards
            $(document).on('click', '.btn-cek-kos', function() {
                var tKos = $(this).data('tkos');
                var namaKos = $(this).data('nama');

                $('#rekapSiswaTitle').text('Siswa Terdata di ' + namaKos);
                $('#rekap-siswa-body').html('<tr><td colspan="6" class="text-center text-muted py-4"><div class="spinner-border spinner-border-sm text-primary" role="status"></div> Memuat data...</td></tr>');
                rekapModal.show();

                $.ajax({
                    url: '<?= base_url(($controller ?? "admin") . "/get_kos_students_ajax/") ?>' + tKos,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        var tbody = $('#rekap-siswa-body');
                        tbody.empty();

                        if (!response.data || response.data.length === 0) {
                            tbody.append('<tr><td colspan="6" class="text-center text-muted py-4">Tidak ada siswa terdata di tempat kos ini</td></tr>');
                            return;
                        }

                        response.data.forEach(function(row) {
                            tbody.append('<tr>' +
                                '<td>' + row.no + '</td>' +
                                '<td>' + row.nis + '</td>' +
                                '<td>' + row.nama + '</td>' +
                                '<td>' + row.lembaga + '</td>' +
                                '<td>' + row.status_ket + '</td>' +
                                '<td>' + row.aksi + '</td>' +
                                '</tr>');
                        });
                    },
                    error: function() {
                        $('#rekap-siswa-body').html('<tr><td colspan="6" class="text-center text-danger py-4">Gagal memuat data santri.</td></tr>');
                    }
                });
            });

            // Handle selecting student from rekap drilldown list
            $(document).on('click', '.btn-pilih-dari-rekap', function() {
                var nis = $(this).data('nis');
                var nama = $(this).data('nama');

                // Hide modal first
                rekapModal.hide();

                // Set select2 value programmatically
                var newOption = new Option(nis + ' - ' + nama, nis, true, true);
                $('#select-santri-dekos').append(newOption).trigger('change');

                // Trigger selection event manually to run AJAX history query and show profile card
                $('#select-santri-dekos').trigger({
                    type: 'select2:select',
                    params: {
                        data: {
                            id: nis,
                            nama: nama
                        }
                    }
                });

                // Scroll back up to the top profile section
                $('html, body').animate({
                    scrollTop: $("#select-santri-dekos").offset().top - 100
                }, 300);
            });

            // Live Rekap Counters updater
            function updateRekap() {
                $.ajax({
                    url: '<?= base_url(($controller ?? "admin") . "/get_rekap_dekos_ajax") ?>',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response && response.rekap) {
                            response.rekap.forEach(function(count, index) {
                                $('#rekap-count-' + index).text(count.toLocaleString());
                            });
                        }
                    }
                });
            }

            // Open Tambah/Pindah Riwayat Modal
            $('#btn-tambah-dekos').on('click', function() {
                $('#dekosFormTitle').text('Pindah Tempat Dekos');
                $('#field-t_kos_label').text('Tempat Kos Baru');
                $('#form-id-dekos').val('');
                $('#form-nis').val(selectedNis);
                $('#form-t-kos').val('');

                // Toggle form containers
                $('#field-tanggal_pindah_container').show();
                $('#form-tanggal-pindah').val('').prop('required', true);

                $('#field-masuk_container').hide();
                $('#form-masuk').val('').prop('required', false);

                $('#field-keluar_container').hide();
                $('#form-keluar').val('').prop('required', false);

                $('#btn-submit-dekos').html('<i class="bx bx-transfer"></i> Simpan Perpindahan');
                formModal.show();
            });

            // Open Edit Riwayat Modal
            $(document).on('click', '.btn-edit-dekos', function() {
                var id = $(this).data('id');
                var tkos = $(this).data('tkos');
                var masuk = $(this).data('masuk');
                var keluar = $(this).data('keluar');

                $('#dekosFormTitle').text('Edit Riwayat Dekosan');
                $('#field-t_kos_label').text('Tempat Kos');
                $('#form-id-dekos').val(id);
                $('#form-nis').val(selectedNis);
                $('#form-t-kos').val(tkos);

                var parseDate = function(d) {
                    if (!d || d === '-' || d === '0000-00-00') return '';
                    return d;
                };

                // Toggle form containers
                $('#field-tanggal_pindah_container').hide();
                $('#form-tanggal-pindah').val('').prop('required', false);

                $('#field-masuk_container').show();
                $('#form-masuk').val(parseDate(masuk)).prop('required', true);

                $('#field-keluar_container').show();
                $('#form-keluar').val(parseDate(keluar)).prop('required', false);

                $('#btn-submit-dekos').html('<i class="bx bx-save"></i> Simpan Perubahan');
                formModal.show();
            });

            // Submit Add/Edit Form
            $('#form-dekos').on('submit', function(e) {
                e.preventDefault();
                var id = $('#form-id-dekos').val();
                var url = id ? '<?= base_url(($controller ?? "admin") . "/edit_dekos") ?>' : '<?= base_url(($controller ?? "admin") . "/add_dekos") ?>';

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            lobiboxSuccess(response.message);
                            formModal.hide();
                            refreshHistory();

                            // Synchronize counters
                            updateRekap();
                        } else {
                            lobiboxError(response.message);
                        }
                    },
                    error: function() {
                        lobiboxError('Terjadi kesalahan server.');
                    }
                });
            });

            // Delete Riwayat Action
            $(document).on('click', '.btn-delete-dekos', function() {
                var id = $(this).data('id');

                if (confirm('Apakah Anda yakin ingin menghapus riwayat dekosan ini?')) {
                    $.ajax({
                        url: '<?= base_url(($controller ?? "admin") . "/delete_dekos/") ?>' + id,
                        type: 'POST',
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                lobiboxSuccess(response.message);
                                refreshHistory();

                                // Synchronize counters
                                updateRekap();
                            } else {
                                lobiboxError(response.message);
                            }
                        },
                        error: function() {
                            lobiboxError('Terjadi kesalahan server.');
                        }
                    });
                }
            });

            // Notification helpers (LobiBox fallback)
            function lobiboxSuccess(msg) {
                if (typeof Lobibox !== 'undefined') {
                    Lobibox.notify('success', {
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top right',
                        icon: 'bx bx-check-circle',
                        msg: msg
                    });
                } else {
                    alert(msg);
                }
            }

            // Notification helpers (LobiBox fallback)
            function lobiboxError(msg) {
                if (typeof Lobibox !== 'undefined') {
                    Lobibox.notify('error', {
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'top right',
                        icon: 'bx bx-x-circle',
                        msg: msg
                    });
                } else {
                    alert(msg);
                }
            }
        }
    });
</script>