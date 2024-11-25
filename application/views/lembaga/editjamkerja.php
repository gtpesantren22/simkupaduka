<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Input Jam Kerja Guru/Karyawan</div>
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
                        <div class="table-responsive mt-3">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr style="color: white; background-color: #008CFF; font-weight: bold;">
                                        <th>No</th>
                                        <th>Bulan</th>
                                        <th>Nama</th>
                                        <th>Ket</th>
                                        <th>Jml Jam</th>
                                        <th>Hasil</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($data as $ls_jns) :
                                        $nom = $ls_jns->santri == 'santri' ? $ls_jns->kehadiran * 7000 : $ls_jns->kehadiran * 12000;
                                    ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= bulan($ls_jns->bulan) . ' ' . $ls_jns->tahun; ?></td>
                                            <td><?= $ls_jns->nama; ?></td>
                                            <td><?= $ls_jns->santri; ?></td>
                                            <td><input type="text" class="form-control form-input" data-id="<?= $ls_jns->id ?>" value="<?= $ls_jns->kehadiran ?>"></td>
                                            <td><b id="hasil-honor-<?= $ls_jns->id ?>"><?= $ls_jns->kehadiran ?> jam</b></td>
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
<!--end page wrapper -->
<script src="<?= base_url('vertical/'); ?>assets/js/jquery.min.js"></script>
<script>
    $('#example').on('change', '.form-input', function() {
        var newValue = $(this).val(); // nilai baru dari input
        var id = $(this).data('id'); // id dari baris data

        $.ajax({
            url: '<?= base_url("honor/updateJam") ?>', // endpoint untuk update data
            type: 'POST',
            data: {
                id: id,
                value: newValue
            },
            dataType: 'json',
            success: function(response) {
                if (response.status == 'ok') {
                    $(this).val(newValue);
                    $(`#hasil-honor-${id}`).text(response.besaran + ` jam`);
                    // alert(response.isi)
                } else {
                    alert('Gagal mengupdate data');
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat mengupdate data');
            }
        });
    });
</script>