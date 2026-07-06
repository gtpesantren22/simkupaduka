<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Daftar Biaya Pendidikan Santri</div>
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
            <div class="col-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <?= form_open('admin/bpEdit') ?>
                        <input type="hidden" name="id" value="<?= htmlspecialchars($bp->nis); ?>">
                        
                        <div class="row">
                            <!-- Student Metadata Panel -->
                            <div class="col-12 col-lg-4 border-end">
                                <h5 class="mb-4 font-weight-bold">Informasi Santri</h5>
                                
                                <label class="form-label font-weight-bold">NIS Santri</label>
                                <input class="form-control mb-3" type="text" value="<?= htmlspecialchars($bp->nis); ?>" readonly>
                                
                                <label class="form-label font-weight-bold">Nama Santri</label>
                                <input class="form-control mb-3" type="text" value="<?= htmlspecialchars($bp->nama); ?>" readonly>
                                
                                <label class="form-label font-weight-bold">No. Briva</label>
                                <input class="form-control mb-3" type="text" name="briva" value="<?= htmlspecialchars($bp->briva); ?>">
                                
                                <label class="form-label font-weight-bold">Total Tanggungan</label>
                                <input class="form-control mb-4 bg-light text-primary font-weight-bold" type="text" id="total-bp" readonly>
                                
                                <div class="d-grid gap-2">
                                    <button class="btn btn-success"><i class="bx bx-check-circle"></i> SIMPAN</button>
                                    <a href="<?= base_url('admin/bp'); ?>" class="btn btn-warning"><i class="bx bx-left-arrow-circle"></i> KEMBALI</a>
                                </div>
                            </div>
                            
                            <!-- Monthly Tanggungan Input Fields -->
                            <div class="col-12 col-lg-8 ps-lg-4">
                                <h5 class="mb-4 font-weight-bold">Rincian Tanggungan Bulanan</h5>
                                
                                <?php
                                $months_list = [
                                    7 => 'Juli',
                                    8 => 'Agustus',
                                    9 => 'September',
                                    10 => 'Oktober',
                                    11 => 'November',
                                    12 => 'Desember',
                                    1 => 'Januari',
                                    2 => 'Februari',
                                    3 => 'Maret',
                                    4 => 'April',
                                    5 => 'Mei',
                                    6 => 'Juni'
                                ];
                                ?>
                                
                                <div class="row">
                                    <?php foreach ($months_list as $num => $name) : ?>
                                        <div class="col-12 col-md-6 col-xxl-4">
                                            <label class="form-label font-weight-bold"><?= $name ?></label>
                                            <input class="form-control mb-3 uang month-input" type="text" name="nominal_<?= $num ?>" value="<?= isset($months_map[$num]) ? $months_map[$num] : 0; ?>" required>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        const inputs = document.querySelectorAll('.month-input');
        const totalInput = document.getElementById('total-bp');

        function calculateTotal() {
            let total = 0;
            inputs.forEach(input => {
                let valStr = input.value.replace(/[^0-9]/g, '');
                let val = parseInt(valStr) || 0;
                total += val;
            });
            totalInput.value = 'Rp. ' + total.toLocaleString('id-ID');
        }

        inputs.forEach(input => {
            input.addEventListener('input', calculateTotal);
            // Trigger focusout for formatting
            input.addEventListener('blur', function() {
                let valStr = this.value.replace(/[^0-9]/g, '');
                let val = parseInt(valStr) || 0;
                this.value = val.toLocaleString('id-ID');
            });
        });

        // Initialize display formatting on load
        inputs.forEach(input => {
            let valStr = input.value.replace(/[^0-9]/g, '');
            let val = parseInt(valStr) || 0;
            input.value = val.toLocaleString('id-ID');
        });

        calculateTotal();
    });
</script>