<div class="table-responsive">
    <table id="example3" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>No</th>
                <!-- <th>NIS</th> -->
                <th>Nama</th>
                <th>Kelas</th>
                <th>#</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($data as $a) : ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <!-- <td><?= $a->nis ?></td> -->
                    <td><?= $a->nama ?></td>
                    <td><?= $a->k_formal . ' ' . $a->t_formal ?></td>
                    <td>
                        <button class="btn btn-sm btn-danger del_siswa" data-id="<?= $a->nis ?>" id="submitBtn"><i class="bx bx-trash"></i></button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('#example').DataTable();
        $('#example3').DataTable();
    })
</script>