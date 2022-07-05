<?php
include 'atas.php';

$akun = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user WHERE id_user = '$id_user' "));
$lembaga = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$kol' AND tahun = '$tahun_ajaran' "));
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Settings
            <small>Update info akun dan info lembaga</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Info</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">

            <!-- right column -->
            <div class="col-md-6">
                <!-- Horizontal Form -->
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Informasi akun saya</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form class="form-horizontal" action="" method="post">
                        <input type="hidden" name="pass_lama" value="<?= $akun['password'] ?>" required>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Nama Lengkap</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="nama" value="<?= $akun['nama'] ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label">Username</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="username" value="<?= $akun['username'] ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label">Level</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" disabled value="Operator <?= $akun['level'] ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label">Lembaga</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="<?= $lembaga['nama'] ?>" disabled required>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label">Password baru</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" name="newpass" placeholder="Password Baru">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label">Confirm password</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" name="confir_newpass" placeholder="Konfirmasi Password Baru">
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" name="save_akun" class="btn btn-warning pull-right">Update Info Akun</button>
                        </div><!-- /.box-footer -->
                    </form>
                </div><!-- /.box -->
            </div>
            <!--/.col (right) -->

            <!-- right column -->
            <div class="col-md-6">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Informasi Akun Lembaga</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form class="form-horizontal" method="post" action="">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Nama Lembaga</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="<?= $lembaga['nama'] ?>" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Penanggungjawab</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="Email" name="pj" value="<?= $lembaga['pj'] ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">No. HP (PJ)</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="hp" placeholder="No HP" required value="<?= $lembaga['hp'] ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Waktu</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" placeholder="Waktu belanja" name="waktu" value="<?= $lembaga['waktu'] ?>">
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" name="save_lm" class="btn btn-info pull-right">Update Info Akun Lembaga</button>
                        </div><!-- /.box-footer -->
                    </form>
                </div><!-- /.box -->
            </div>
            <!--/.col (right) -->
        </div> <!-- /.row -->
    </section>
</div>
<?php
include 'bawah.php';

if (isset($_POST['save_akun'])) {
    $id = $id_user;
    $nama = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($_POST['nama'])));
    $username = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['username']));
    $password = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['newpass']));
    $password2 = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['confir_newpass']));
    $pass_lama = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['pass_lama']));
    $pass_baru = password_hash($password, PASSWORD_DEFAULT);

    if ($password == '' && $password2 = '') {
        $sql = mysqli_query($conn, "UPDATE user SET nama = '$nama', username = '$username' WHERE id_user = '$id'  ");
        if ($sql) {
            echo "
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Update akun berhasil'
            });
            var millisecondsToWait = 1000;
            setTimeout(function() {
                document.location.href = 'setting.php'
            }, millisecondsToWait);
        </script>
        ";
        }
    } else {
        if ($password != $password2) {
            echo "
            <script>
                Swal.fire({
                icon: 'error',
                title: 'Maaf',
                text: 'Konfimasi password tidak sama'
                });
            </script>
            ";
        } else {
            $sql = mysqli_query($conn, "UPDATE user SET nama = '$nama', username = '$username', password = '$pass_baru' WHERE id_user = '$id' ");
            if ($sql) {
                echo "
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Update akun berhasil'
                    });
                    var millisecondsToWait = 1000;
                    setTimeout(function() {
                        document.location.href = 'setting.php'
                    }, millisecondsToWait);
                </script>
                ";
            }
        }
    }
}

if (isset($_POST['save_lm'])) {
    $id_lm = $kol;
    $pj = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($_POST['pj'])));
    $hp = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['hp']));
    $waktu = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['waktu']));


    $sql = mysqli_query($conn, "UPDATE lembaga SET pj = '$pj', hp = '$hp', waktu = '$waktu' WHERE kode = '$id_lm' AND tahun = '$tahun_ajaran' ");
    if ($sql) {
        echo "
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Update akun berhasil'
            });
            var millisecondsToWait = 1000;
            setTimeout(function() {
                document.location.href = 'setting.php'
            }, millisecondsToWait);
        </script>
        ";
    }
}
?>