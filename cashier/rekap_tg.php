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
                            <!-- Date range -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Jenjang</label>
                                    <select name="t_formal" id="t_formal" class="form-control" required>
                                        <option value="">Pilih Lembaga</option>
                                        <?php
                                        $sq = mysqli_query($conn_santri, "SELECT nama FROM lembaga ");
                                        while ($kl = mysqli_fetch_assoc($sq)) {
                                        ?>
                                        <option value="<?= $kl['nama'] ?>"><?= $kl['nama'] ?>
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
                                    <select name="tahun" id="" class="form-control" required>
                                        <option value=""> --pilih tahun-- </option>
                                        <?php
                                        $th = mysqli_query($conn, "SELECT * FROM tahun ");
                                        $no = 0;
                                        while ($thn = mysqli_fetch_array($th)) {
                                            $no++;
                                        ?>
                                        <option value="<?= $thn['nama_tahun'] ?>"><?= $thn['nama_tahun'] ?>
                                        </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div><!-- /.input group -->
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">&nbsp;</label><br>
                                    <button type="submit" name="cari" class="btn btn-block btn-success"><span
                                            class="fa fa-search">
                                            Cek</span></button>
                                </div>
                            </div>
                        </form>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
            <?php
            if (isset($_POST['cari'])) {
                $kelas = $_POST['k_formal'];
                $tingkat = $_POST['t_formal'];
                $tahun = $_POST['tahun'];

                $kls_pch = explode('-', $kelas);
                $kls = $kls_pch[0];
                $jur = $kls_pch[1];
                $rmb = $kls_pch[2];
            ?>
            <form action="" method="post">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">
                                Data dari :
                                <br />
                                <br />
                                <p class="label label-primary"> KELAS : <?= $kelas; ?> </p>
                                <p class="label label-danger"> TAHUN : <?= $tahun; ?></p>
                            </h3>
                            <!-- <a href="<?= 'pages/rekap/excel_kos.php?dari=' . $dari . '&sampai=' . $sampai ?>"
                                target="_blank" type="button" class="btn btn-success pull-right"><span
                                    class="fa fa-download">
                                </span>
                                Download excel</a> -->
                        </div>
                        <hr>
                        <div class="box-body">
                            <?php

                                $dt1 = mysqli_query($conn, "SELECT * FROM pembayaran a JOIN tb_santri b ON a.nis=b.nis WHERE b.k_formal = '$kls' AND b.t_formal = '$tingkat' AND b.r_formal = '$rmb' AND b.jurusan = '$jur' AND a.tahun = '$tahun' AND b.aktif = 'Y' GROUP BY a.nis ORDER BY b.nama");

                                $dt_null = mysqli_query($conn, "SELECT * FROM tb_santri WHERE k_formal = '$kls' AND t_formal = '$tingkat' AND r_formal = '$rmb' AND jurusan = '$jur' AND aktif = 'Y'  AND  NOT EXISTS (SELECT * FROM pembayaran WHERE tb_santri.nis = pembayaran.nis AND tahun = '$tahun') ");

                                //$bl = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"];

                                ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="">
                                    <thead>
                                        <tr style="background-color: purple; color: white; font-weight: bold;">
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Kelas</th>
                                            <th>Jul</th>
                                            <th>Ags</th>
                                            <th>Sep</th>
                                            <th>Okt</th>
                                            <th>Nov</th>
                                            <th>Des</th>
                                            <th>Jan</th>
                                            <th>Feb</th>
                                            <th>Mar</th>
                                            <th>Apr</th>
                                            <th>Mei</th>
                                            <th>Jun</th>
                                            <th>Total</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $i = 1;
                                            ?>
                                        <?php foreach ($dt1 as $r) :
                                                $nis = $r['nis'];
                                                $dt2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT 
                                            SUM(IF( bulan = 7, nominal, '-')) as jul, 
                                            SUM(IF( bulan = 8, nominal, '-')) as ags, 
                                            SUM(IF( bulan = 9, nominal, '-')) as sep, 
                                            SUM(IF( bulan = 10, nominal, '-')) as okt, 
                                            SUM(IF( bulan = 11, nominal, '-')) as nov, 
                                            SUM(IF( bulan = 12, nominal, '-')) as des, 
                                            SUM(IF( bulan = 1, nominal, '-')) as jan, 
                                            SUM(IF( bulan = 2, nominal, '-')) as feb, 
                                            SUM(IF( bulan = 3, nominal, '-')) as mar, 
                                            SUM(IF( bulan = 4, nominal, '-')) as apr, 
                                            SUM(IF( bulan = 5, nominal, '-')) as mei, 
                                            SUM(IF( bulan = 6, nominal, '-')) as jun,
                                            SUM(nominal) as total 
                                            FROM pembayaran WHERE nis = '$nis' AND tahun = '$tahun' "));
                                                $tggn = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tangg WHERE nis = $nis AND tahun = '$tahun' "));
                                            ?>
                                        <tr>
                                            <td><?= $i; ?></td>
                                            <td><?= $r['nama']; ?></td>
                                            <td><?= $r['k_formal']; ?> <?= $r['t_formal']; ?></td>

                                            <!-- Juli -->
                                            <?php if ($dt2['jul'] >= $tggn['ju_ap']) { ?>
                                            <td style="background-color: green; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['jul']); ?></td>
                                            <?php } elseif ($dt2['jul'] < $tggn['ju_ap'] && $dt2['jul'] != 0) { ?>
                                            <td style="background-color: orange; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['jul']); ?></td>
                                            <?php } else { ?>
                                            <td style="background-color: red; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['jul']); ?></td>
                                            <?php } ?>

                                            <!-- ags -->
                                            <?php if ($dt2['ags'] >= $tggn['ju_ap']) { ?>
                                            <td style="background-color: green; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['ags']); ?></td>
                                            <?php } elseif ($dt2['ags'] < $tggn['ju_ap'] && $dt2['ags'] != 0) { ?>
                                            <td style="background-color: orange; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['ags']); ?></td>
                                            <?php } else { ?>
                                            <td style="background-color: red; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['ags']); ?></td>
                                            <?php } ?>

                                            <!-- sep -->
                                            <?php if ($dt2['sep'] >= $tggn['ju_ap']) { ?>
                                            <td style="background-color: green; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['sep']); ?></td>
                                            <?php } elseif ($dt2['sep'] < $tggn['ju_ap'] && $dt2['sep'] != 0) { ?>
                                            <td style="background-color: orange; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['sep']); ?></td>
                                            <?php } else { ?>
                                            <td style="background-color: red; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['sep']); ?></td>
                                            <?php } ?>

                                            <!-- okt -->
                                            <?php if ($dt2['okt'] >= $tggn['ju_ap']) { ?>
                                            <td style="background-color: green; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['okt']); ?></td>
                                            <?php } elseif ($dt2['okt'] < $tggn['ju_ap'] && $dt2['okt'] != 0) { ?>
                                            <td style="background-color: orange; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['okt']); ?></td>
                                            <?php } else { ?>
                                            <td style="background-color: red; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['okt']); ?></td>
                                            <?php } ?>

                                            <!-- nov -->
                                            <?php if ($dt2['nov'] >= $tggn['ju_ap']) { ?>
                                            <td style="background-color: green; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['nov']); ?></td>
                                            <?php } elseif ($dt2['nov'] < $tggn['ju_ap'] && $dt2['nov'] != 0) { ?>
                                            <td style="background-color: orange; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['nov']); ?></td>
                                            <?php } else { ?>
                                            <td style="background-color: red; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['nov']); ?></td>
                                            <?php } ?>

                                            <!-- des -->
                                            <?php if ($dt2['des'] >= $tggn['ju_ap']) { ?>
                                            <td style="background-color: green; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['des']); ?></td>
                                            <?php } elseif ($dt2['des'] < $tggn['ju_ap'] && $dt2['des'] != 0) { ?>
                                            <td style="background-color: orange; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['des']); ?></td>
                                            <?php } else { ?>
                                            <td style="background-color: red; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['des']); ?></td>
                                            <?php } ?>

                                            <!-- jan -->
                                            <?php if ($dt2['jan'] >= $tggn['ju_ap']) { ?>
                                            <td style="background-color: green; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['jan']); ?></td>
                                            <?php } elseif ($dt2['jan'] < $tggn['ju_ap'] && $dt2['jan'] != 0) { ?>
                                            <td style="background-color: orange; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['jan']); ?></td>
                                            <?php } else { ?>
                                            <td style="background-color: red; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['jan']); ?></td>
                                            <?php } ?>

                                            <!-- feb -->
                                            <?php if ($dt2['feb'] >= $tggn['ju_ap']) { ?>
                                            <td style="background-color: green; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['feb']); ?></td>
                                            <?php } elseif ($dt2['feb'] < $tggn['ju_ap'] && $dt2['feb'] != 0) { ?>
                                            <td style="background-color: orange; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['feb']); ?></td>
                                            <?php } else { ?>
                                            <td style="background-color: red; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['feb']); ?></td>
                                            <?php } ?>

                                            <!-- mar -->
                                            <?php if ($dt2['mar'] >= $tggn['ju_ap']) { ?>
                                            <td style="background-color: green; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['mar']); ?></td>
                                            <?php } elseif ($dt2['mar'] < $tggn['ju_ap'] && $dt2['mar'] != 0) { ?>
                                            <td style="background-color: orange; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['mar']); ?></td>
                                            <?php } else { ?>
                                            <td style="background-color: red; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['mar']); ?></td>
                                            <?php } ?>

                                            <!-- apr -->
                                            <?php if ($dt2['apr'] >= $tggn['ju_ap']) { ?>
                                            <td style="background-color: green; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['apr']); ?></td>
                                            <?php } elseif ($dt2['apr'] < $tggn['ju_ap'] && $dt2['apr'] != 0) { ?>
                                            <td style="background-color: orange; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['apr']); ?></td>
                                            <?php } else { ?>
                                            <td style="background-color: red; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['apr']); ?></td>
                                            <?php } ?>

                                            <!-- mei -->
                                            <?php if ($dt2['mei'] >= $tggn['me_ju']) { ?>
                                            <td style="background-color: green; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['mei']); ?></td>
                                            <?php } elseif ($dt2['mei'] < $tggn['me_ju'] && $dt2['mei'] != 0) { ?>
                                            <td style="background-color: orange; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['mei']); ?></td>
                                            <?php } else { ?>
                                            <td style="background-color: red; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['mei']); ?></td>
                                            <?php } ?>

                                            <!-- jun -->
                                            <?php if ($dt2['jun'] >= $tggn['me_ju']) { ?>
                                            <td style="background-color: green; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['jun']); ?></td>
                                            <?php } elseif ($dt2['jun'] < $tggn['me_ju'] && $dt2['jun'] != 0) { ?>
                                            <td style="background-color: orange; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['jun']); ?></td>
                                            <?php } else { ?>
                                            <td style="background-color: red; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['jun']); ?></td>
                                            <?php } ?>

                                            <td style="background-color: grey; color: white; font-weight: bold;">
                                                <?= rupiah2($dt2['total']); ?></td>
                                        </tr>
                                        <?php $i++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <center>
                                <h3><span class="label label-danger">Data Dak Bayar Sama Sekali</span></h3>
                            </center>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="example3_bst">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Kelas</th>
                                            <th>Jul</th>
                                            <th>Ags</th>
                                            <th>Sep</th>
                                            <th>Okt</th>
                                            <th>Nov</th>
                                            <th>Des</th>
                                            <th>Jan</th>
                                            <th>Feb</th>
                                            <th>Mar</th>
                                            <th>Apr</th>
                                            <th>Mei</th>
                                            <th>Jun</th>
                                            <th>Total</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $i = 1;
                                            ?>
                                        <?php foreach ($dt_null as $r) : ?>
                                        <tr>
                                            <td><?= $i; ?></td>
                                            <td><?= $r['nama']; ?></td>
                                            <td><?= $kelas; ?> <?= $tingkat; ?></td>
                                            <td colspan="13" style="font-weight: bold; color: red; text-align: center;">
                                                <span class="fa fa-close"></span> Tak
                                                Bayar Sama Sekali
                                            </td>
                                        </tr>
                                        <?php $i++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- /.box-body -->

                        <!-- /.row -->
                    </div>
                </div>
            </form>
            <?php
            }
            ?>
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
<script>
$(document).ready(function() {
    $("#t_formal").change(function() {
        var t_formal = $("#t_formal").val();
        $.ajax({
            type: 'POST',
            url: "get_kelas.php",
            data: {
                t_formal: t_formal
            },
            cache: false,
            success: function(msg) {
                $("#k_formal").html(msg);
            }
        });
    });
});
</script>

<?php
include 'bawah.php';