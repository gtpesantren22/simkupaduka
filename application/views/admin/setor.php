<div class="table-responsive">
    <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Ket</th>
                <th>Bulan</th>
                <th>Nominal</th>
                <th>Tgl Setor</th>
                <th>Penyetor</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($list as $a) : ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $a->dari ?></td>
                    <td><?= $bulan[$a->bulan] ?></td>
                    <td><?= rupiah($a->nominal) ?></td>
                    <td><?= date('d-M-Y', strtotime($a->tgl)) ?></td>
                    <td><?= $a->penyetor ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>