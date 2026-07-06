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
                    <button type="button" id="btn-sinkron-lembaga" class="btn btn-success btn-sm"><i class="bx bx-sync"></i> Sinkron Lembaga</button>
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
                            <div class="col-12 col-md-4">
                                <label class="form-label font-weight-bold">Filter Lembaga</label>
                                <select id="filter-lembaga" class="form-select form-select-sm">
                                    <option value="">Semua Lembaga</option>
                                    <?php foreach ($lembaga_list as $l) : ?>
                                        <option value="<?= htmlspecialchars($l->t_formal) ?>"><?= htmlspecialchars($l->t_formal) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label font-weight-bold">Filter Customer ID</label>
                                <select id="filter-cost" class="form-select form-select-sm">
                                    <option value="">Semua Status</option>
                                    <option value="ada">Ada Customer ID</option>
                                    <option value="tidak">Tidak Ada Customer ID</option>
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="example-santri" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIS</th>
                                        <th>Customer ID</th>
                                        <th>Nama</th>
                                        <th>Kelas Formal</th>
                                        <th>Aksi</th>
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
            <form action="<?= base_url('admin/update_cost_id') ?>" method="post">
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

<script>
    window.addEventListener('load', function() {
        var myModal = new bootstrap.Modal(document.getElementById('modalSinkron'));
        var editModal = new bootstrap.Modal(document.getElementById('modalEditCost'));
        var myLembagaModal = new bootstrap.Modal(document.getElementById('modalSinkronLembaga'));

        // Initialize Server-Side DataTable
        $('#example-santri').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('admin/santri_ajax') ?>",
                "type": "POST",
                "data": function(d) {
                    d.filter_lembaga = $('#filter-lembaga').val();
                    d.filter_cost = $('#filter-cost').val();
                }
            },
            "columns": [
                { "data": "no", "orderable": false },
                { "data": "nis" },
                { "data": "cost_id" },
                { "data": "nama" },
                { "data": "kelas_formal" },
                { "data": "aksi", "orderable": false }
            ]
        });

        // Trigger redraw on filter change
        $('#filter-lembaga, #filter-cost').on('change', function() {
            $('#example-santri').DataTable().draw();
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
                url: '<?= base_url("admin/sinkron_siswa_single") ?>',
                type: 'GET',
                data: { id_santri: idSantri },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        $('#example-santri').DataTable().draw(false);
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
                url: '<?= base_url("admin/sinkron_batch") ?>',
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
                            // Completed!
                            $('#sync-status').text('Sinkronisasi selesai! Merefresh halaman...');
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);
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
                url: '<?= base_url("admin/sinkron_lembaga_batch") ?>',
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
    });
</script>