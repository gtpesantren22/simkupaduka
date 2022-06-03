<?php
include 'atas.php';
$no = 1;
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Informasi
            <small>Info seputar Keuangan Pesantren</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Info</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- row -->
        <div class="row">
            <div class="col-md-9">
                <!-- The time line -->
                <ul class="timeline">
                    <!-- timeline time label -->
                    <li class="time-label">
                        <span class="bg-red">
                            Informasi
                        </span>
                    </li>
                    <!-- /.timeline-label -->
                    <!-- timeline item -->
                    <?php
                    $dt = mysqli_query($conn, "SELECT * FROM info");
                    while ($row = mysqli_fetch_assoc($dt)) {
                        $tg = $row['tgl']; ?>
                        <li>
                            <i class="fa fa-envelope bg-blue"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fa fa-clock-o"></i> <?= date('H:i', strtotime($tg)) ?></span>
                                <h3 class="timeline-header"><a href="#"><?= $row['judul'] ?></a></h3>
                                <div class="timeline-body">
                                    <?= $row['isi'] ?>
                                </div>
                                <div class="timeline-footer">
                                    <span class="label label-success">by : <?= $row['uploader'] ?></span>
                                    <span class="label label-warning"><?= date('d F Y', strtotime($tg)) ?></span>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                    <!-- END timeline item -->
                </ul>
            </div><!-- /.col -->
            <div class="col-md-3">
                <br><br>
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-download"></i> Unduh Berkas</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <p>Format SPJ (.xlsx) <a href="files/Templetes-SPJ-SimkuPaduka.xlsx"> <i class="fa fa-download"></i> Download</a> </p>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
        </div><!-- /.row -->
    </section><!-- /.content -->
</div>

<?php
include 'bawah.php';
?>