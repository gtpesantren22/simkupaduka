<?php
include 'atas.php';
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Rekap Tanggungan Santri
            <small>Tanggungan Pertahun</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Tanggunagn</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Tanggungan Pertahun</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <form action="" method="post">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Pilih Lembaga</label>
                                    <select name="lembaga" class="form-control" id="" required>
                                        <option value=""> -pilih lembaga- </option>
                                        <?php
                                        $sql = mysqli_query($conn, "SELECT * FROM tb_santri GROUP BY t_formal");
                                        while ($r = mysqli_fetch_assoc($sql)) {
                                        ?>
                                            <option value="<?= $r['t_formal']; ?>"><?= $r['t_formal']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Jenis Kelamin</label>
                                    <select name="jkl" class="form-control" id="" required>
                                        <option value=""> -pilih jkl- </option>
                                        <option value="Laki-laki">Putra</option>
                                        <option value="Perempuan">Putri</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">&nbsp;</label><br>
                                    <button type="submit" name="lait" class="btn btn-success btn-sm"><i class="fa fa-search"></i> Tampilkan</button>
                                </div>
                            </div>
                        </form>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
            <?php
            if (isset($_POST['lait'])) {
                $lembaga = $_POST['lembaga'];
                $jkl = $_POST['jkl'];
            ?>
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Tanggungan Pertahun</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">

                            <div class="table-responsive">
                                <table id="example1_bst" class="table table-bordered table-striped">
                                    <thead>
                                        <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Alamat</th>
                                            <th>Kelas Formal</th>
                                            <th>Tanggungan</th>
                                            <th>Bayar</th>
                                            <th>Sisa</th>
                                            <th>Ket</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $rls = mysqli_query($conn, "SELECT a.*, b.* FROM tanggungan a JOIN tb_santri b ON a.nis=b.nis WHERE b.t_formal = '$lembaga' AND b.jkl = '$jkl' AND tahun = '$tahun_ajaran' ");
                                        while ($ls_jns = mysqli_fetch_assoc($rls)) {
                                            $tg = mysqli_query($conn, "SELECT * FROM tg_lembaga WHERE tahun = '$tahun_ajaran'");
                                            while ($tgr = mysqli_fetch_assoc($tg)) {
                                                $tgn = $ls_jns[$tgr['kode']];
                                            }
                                            $jml = $ls_jns['syahriah'] + $tgn;
                                            $nis = $ls_jns['nis'];
                                            $bayar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as jml FROM pembayaran WHERE nis = $nis AND tahun = '$tahun_ajaran' "));
                                            if ($jml < $bayar['jml']) {
                                                $kt = "<span class='label bg-purple'><i class='fa fa-warning'></i> Lebih</span>";
                                            } elseif ($jml == $bayar['jml']) {
                                                $kt = "<span class='label label-success'><i class='fa fa-check'></i> Lunas</span>";
                                            } else {
                                                $kt = "<span class='label label-danger'><i class='fa fa-times'></i> Kurang</span>";
                                            }
                                        ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= $ls_jns['nama']; ?></td>
                                                <td><?= $ls_jns['desa'] . ' - ' . $ls_jns['kec']; ?></td>
                                                <td><?= $ls_jns['k_formal'] . ' ' . $ls_jns['t_formal']; ?></td>
                                                <td><?= rupiah($jml); ?></td>
                                                <td><?= rupiah($bayar['jml']); ?></td>
                                                <td><?= rupiah($jml - $bayar['jml']); ?></td>
                                                <td><?= $kt; ?></td>
                                            </tr>
                                            <!-- Modal -->
                                        <?php } ?>
                                    </tbody>
                                    <!-- <tfoot>
                                        <tr style="color: white; background-color: #17A2B8; font-weight: bold;">
                                            <th colspan="4">Total Pemasukan dari Syahrian Santri</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot> -->
                                </table>
                            </div>

                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div>
            <?php } ?>
        </div>
    </section><!-- /.content -->
</div>



<!-- DataTables -->
<link rel="stylesheet" href="../institution/plugins/datatables/dataTables.bootstrap.css">
<!-- DataTables -->
<link rel="stylesheet" href="../institution/plugins/datatables/dataTables.bootstrap.css">
<!-- Select2 -->
<link rel="stylesheet" href="../institution/plugins/select2/select2.min.css">
<!-- jQuery 2.1.4 -->
<script src="../institution/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="../institution/bootstrap/js/bootstrap.min.js"></script>

<!-- DataTables -->
<script src="../institution/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../institution/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script>
    function masuk(txt, data) {
        document.getElementById('nis').value = data; // ini berfungsi mengisi value yang ber id textbox
        $("#tambah").modal('hide'); // ini berfungsi untuk menyembunyikan modal
    }
    $(function() {
        $(".select2").select2();
        $("#example1_bst").DataTable();
        $("#example2_bst").DataTable();
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
    });
</script>

<?php
include 'bawah.php';
