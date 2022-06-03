<?php
include 'head.php';

$id = $_GET['id'];
$r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM kas WHERE id_kas = '$id' "));
?>

<!-- [ Main Content ] start -->
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Pemasukan </h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Pemasukan </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Tambah Data Pemasukan Baru</h5>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Tanggal Setor</label>
                                        <input type="date" name="tgl" class="form-control" id="exampleInputEmail1" placeholder="Masukan Tanggal penyetoran" required value="<?= $r['tgl']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Uraian</label>
                                        <textarea name="uraian" class="form-control" cols="30" rows="3" required><?= $r['uraian']; ?></textarea>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Penyetor</label>
                                        <input type="text" name="penyetor" class="form-control" id="exampleInputEmail1" placeholder="Masukan Nama penyetoran" required value="<?= $r['penyetor']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Nominal</label>
                                        <input type="text" name="nominal" class="form-control" id="uang" placeholder="Masukan Jumlah Setoran" required value="<?= $r['nominal']; ?>">
                                    </div>
                                    <button type="submit" name="save" class="btn btn-primary float-right">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>
<!-- [ Main Content ] end -->
<?php
include 'foot.php';

if (isset($_POST['save'])) {

    $tgl = addslashes(htmlspecialchars(mysqli_real_escape_string($conn, $_POST['tgl'])));
    $uraian = addslashes(htmlspecialchars(mysqli_real_escape_string($conn, $_POST['uraian'])));
    $penyetor = addslashes(htmlspecialchars(mysqli_real_escape_string($conn, $_POST['penyetor'])));
    $nominal = addslashes(htmlspecialchars(mysqli_real_escape_string($conn, preg_replace("/[^0-9]/", "", $_POST['nominal']))));

    $sql = mysqli_query($conn, "UPDATE kas SET tgl = '$tgl', uraian = '$uraian', penyetor = '$penyetor', nominal = '$nominal' WHERE id_kas = '$id' ");

    if ($sql) { ?>
        <script>
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Data berhasil diedit',
                showConfirmButton: false
            });
            var millisecondsToWait = 1000;
            setTimeout(function() {
                document.location.href = "<?= 'masuk.php' ?>"
            }, millisecondsToWait);
        </script>

<?php    }
}
?>