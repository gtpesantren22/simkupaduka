<div class="table-responsive">
    <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode DPPK</th>
                <!-- <th>Lembaga</th> -->
                <th>Program</th>
                <th>Kegiatan</th>
                <th>Indikator</th>
                <th>Tahun</th>
                <th>#</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($list as $a) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $a->id_dppk; ?></td>
                    <!-- <td><?= $a->lembaga; ?></td> -->
                    <td><?= $a->program; ?></td>
                    <td><?= $a->kegiatan; ?></td>
                    <td><?= $a->indikator; ?></td>
                    <td><?= $a->tahun; ?></td>
                    <td>
                        <!-- <a href="<?= base_url('admin/pakDetail/' . $a->id_dppk) ?>"><button class="btn btn-primary btn-sm"><i class="bx bx-search"></i> Cek PAK</button></a> -->
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>