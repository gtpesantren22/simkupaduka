<style>
    /* Gaya untuk indikator loading */
    #loading-indicator {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999;
    }

    .spinner {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 4px solid #fff;
        border-top-color: #007bff;
        animation: spin 1s infinite linear;
    }

    @keyframes spin {
        0% {
            transform: translate(-50%, -50%) rotate(0deg);
        }

        100% {
            transform: translate(-50%, -50%) rotate(360deg);
        }
    }
</style>

<div id="loading-indicator">
    <div class="spinner"></div>
</div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Cek Pembayaran Tanggungan Santri</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-shopping-bag"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Tanggungan</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <form action="" id="search-form">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Jenjang</label>
                                        <select name="t_formal" id="t_formal" class="form-control" required>
                                            <option value="">Pilih Lembaga</option>
                                            <?php
                                            foreach ($lmbFr as $kl) {
                                            ?>
                                                <option value="<?= $kl->lembaga ?>"><?= $kl->lembaga ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div><!-- /.input group -->
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Kelas</label>
                                        <select name="k_formal" id="k_formal" class="form-control" required>
                                            <option value="">- pilih kelas -</option>
                                        </select>
                                    </div><!-- /.input group -->
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Tahun</label>
                                        <select name="tahun" id="tahun" class="form-control" required>
                                            <option value=""> --pilih tahun-- </option>
                                            <?php
                                            foreach ($tahunData as $thn) {
                                            ?>
                                                <option value="<?= $thn->nama_tahun ?>"><?= $thn->nama_tahun ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div><!-- /.input group -->
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">&nbsp;</label><br>
                                        <button type="submit" class="btn btn-block btn-success"><span class="fa fa-search">Cek</span></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div id="search-results"></div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<!--end page wrapper -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    function showLoadingIndicator() {
        document.getElementById('loading-indicator').style.display = 'block';
    }

    // Menyembunyikan indikator loading setelah proses Ajax selesai
    function hideLoadingIndicator() {
        document.getElementById('loading-indicator').style.display = 'none';
    }

    $(document).ready(function() {
        $('#search-form').submit(function(e) {
            e.preventDefault();
            showLoadingIndicator();
            var k_formal = $('#k_formal').val();
            var tahun = $('#tahun').val();

            $.ajax({
                url: "<?= base_url('kasir/cekKelas'); ?>",
                type: "POST",
                data: {
                    k_formal: k_formal,
                    tahun: tahun
                },
                dataType: "html",
                success: function(response) {
                    $('#search-results').html(response);
                    hideLoadingIndicator()
                }
            });
        });
    });
</script>