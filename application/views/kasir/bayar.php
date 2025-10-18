<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Pembayaran Tanggungan Santri</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-shopping-bag"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Tanggungan</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <button class="btn btn-primary float-right" data-bs-toggle="modal" data-bs-target="#uploadBp">Upload Pembayaran</button>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>JKL</th>
                                        <th>KelSas</th>
                                        <th>Tanggal Bayar</th>
                                        <th>Bulan</th>
                                        <th>Nominal</th>
                                        <th>Tahun Ajaran</th>
                                        <th>Penerima</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($rls as $ls_jns) { ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= $ls_jns->nama; ?></td>
                                            <td><?= $ls_jns->jkl; ?></td>
                                            <td><?= $ls_jns->k_formal . ' - ' . $ls_jns->t_formal; ?></td>
                                            <td><?= $ls_jns->tgl; ?></td>
                                            <td><?= $bulan[$ls_jns->bulan]; ?></td>
                                            <td><?= rupiah($ls_jns->nominal); ?></td>
                                            <td><?= $ls_jns->tahun; ?></td>
                                            <td><?= $ls_jns->kasir; ?></td>
                                            <td>
                                                <a href="<?= base_url('kasir/delBayar/' . $ls_jns->id); ?>" class="tbl-confirm" value="Data ini akan dihapus dan akan menghapus data dekosan nya juga"><span class="btn btn-danger btn-sm">Del</span></a>
                                            </td>
                                        </tr>
                                        <!-- Modal -->

                                    <?php } ?>
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
<div class="modal fade" id="uploadBp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Upload Pembayaran BP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open_multipart('import/save'); ?>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label for="">Pilih File</label>
                    <input type="file" name="file_excel" id="file_excel" accept=".xls,.xlsx" class="form-control" required></input>
                </div>
                <div class="progress mt-4" style="height: 25px; display: none;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                        role="progressbar" style="width: 0%">0%</div>
                </div>
                <div id="message" class="mt-2"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="btnImport" class="btn btn-primary"><i class="bx bx-save"></i> Upload</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#btnImport').click(function(e) {
            e.preventDefault();

            let file = $('#file_excel')[0].files[0];
            if (!file) {
                alert('Pilih file Excel terlebih dahulu!');
                return;
            }

            let formData = new FormData();
            formData.append('file_excel', file);

            $('.progress').show();
            $('.progress-bar').css('width', '0%').text('0%');

            $.ajax({
                xhr: function() {
                    let xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(e) {
                        if (e.lengthComputable) {
                            let percent = Math.round((e.loaded / e.total) * 100);
                            $('.progress-bar').css('width', percent + '%').text(percent + '%');
                        }
                    }, false);
                    return xhr;
                },
                url: '<?= site_url("import/save") ?>',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#message').html('');
                    $('#btnImport').prop('disabled', true);
                },
                success: function(response) {
                    $('#btnImport').prop('disabled', false);
                    $('.progress-bar').css('width', '100%').text('100%');
                    $('#message').html('<div class="alert alert-success mt-3">' + response + '</div>');
                    window.location.reload()
                },
                error: function(xhr) {
                    $('#btnImport').prop('disabled', false);
                    $('#message').html('<div class="alert alert-danger mt-3">Terjadi kesalahan: ' + xhr.responseText + '</div>');
                }
            });
        });
    });
</script>