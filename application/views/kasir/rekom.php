<div class="flash-data" data-flashdata="<?= $this->session->flashdata('ok') ?>"></div>
<div class="flash-data-error" data-flashdata="<?= $this->session->flashdata('error') ?>"></div>

<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Rekomendasi Liburan</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-note"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Rekomendasi Liburan</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-6 col-lg-6">
                <div class="card radius-10">
                    <div class="card-body">
                        <!-- <form id="tambah_siswa"> -->
                        <div id="loadSantri"></div>
                        <!-- </form> -->
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-6">
                <div class="card radius-10">
                    <div class="card-body">
                        <div id="loadRekom"></div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
<!--end page wrapper -->
<!--end page wrapper -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $("body").on("click", ".tambah_siswa", function() {
            var nis = $(this).data('id');
            $.ajax({
                type: "POST",
                url: "<?= base_url('kasir/addRekom'); ?>",
                data: {
                    nis: nis
                },
                success: function(response) {
                    loadSantriData();
                    loadRekomData();
                }
            });
        });

        $("body").on("click", ".del_siswa", function() {
            var nis = $(this).data('id');
            $.ajax({
                type: "POST",
                url: "<?= base_url('kasir/delRekom'); ?>",
                data: {
                    nis: nis
                },
                success: function(response) {
                    loadSantriData();
                    loadRekomData();
                }
            });
        });

        // Fungsi untuk mereload tabel data siswa
        function loadSantriData() {
            $.ajax({
                type: "GET",
                url: "<?= base_url('kasir/loadSantri'); ?>", // Gantilah dengan URL yang sesuai
                success: function(response) {
                    // Update tabel HTML dengan data siswa yang baru
                    $("#loadSantri").html(response);
                    // console.log('Muat data berhasil')
                }
            });
        }

        function loadRekomData() {
            $.ajax({
                type: "GET",
                url: "<?= base_url('kasir/loadRekom'); ?>", // Gantilah dengan URL yang sesuai
                success: function(response) {
                    // Update tabel HTML dengan data siswa yang baru
                    $("#loadRekom").html(response);
                    // console.log('Muat data berhasil')
                }
            });
        }

        // Panggil fungsi untuk mereload data siswa saat halaman pertama kali dimuat
        loadSantriData();
        loadRekomData();
    });
</script>