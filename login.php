<?php
session_start();
include 'koneksi.php';

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SIMKU-PADUKA App</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="institution/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="institution/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="institution/plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
  <div class="login-box">
    <div class="login-logo">
      <a href="login.php"><img src="institution/dist/img/logo simku paduka.png" width="300" alt=""></a>
    </div><!-- /.login-logo -->
    <div class="login-box-body">
      <p class="login-box-msg">Login disini !</p>
      <form action="" method="post">
        <div class="form-group has-feedback">
          <input type="text" class="form-control" name="namamu" placeholder="Username" required>
          <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="password" class="form-control" placeholder="Password" name="fiil" required>
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <select name="tahun" id="" class="form-control" required>
            <!-- <option value=""> -pilih tahun ajaran- </option> -->
            <?php
            $sq = mysqli_query($conn, "SELECT * FROM tahun");
            while ($r = mysqli_fetch_assoc($sq)) { ?>
              <option value="<?= $r['nama_tahun']; ?>">Tahun Pelajaran - <?= $r['nama_tahun']; ?></option>
            <?php } ?>
          </select>
          <!-- <span class="glyphicon glyphicon-lock form-control-feedback"></span> -->
        </div>
        <div class="row">
          <div class="col-xs-8">
            <div class="checkbox icheck">
              <label>
                <input type="checkbox"> Remember Me
              </label>
            </div>
          </div><!-- /.col -->
          <div class="col-xs-4">
            <button type="submit" name="masuk" class="btn btn-primary btn-block btn-flat"><i class="fa fa-lock"></i> Sign In</button>
          </div><!-- /.col -->
        </div>
      </form>

      <div class="social-auth-links text-center">
        <p>- atau -</p>
        <a href="register.php" class="btn btn-success btn-block btn-flat"><i class="fa fa-user-plus"></i> Daftar Akun Baru</a>
      </div><!-- /.social-auth-links -->

    </div><!-- /.login-box-body -->
  </div><!-- /.login-box -->

  <!-- jQuery 2.1.4 -->
  <script src="institution/plugins/jQuery/jQuery-2.1.4.min.js"></script>
  <!-- Bootstrap 3.3.5 -->
  <script src="institution/bootstrap/js/bootstrap.min.js"></script>
  <!-- iCheck -->
  <script src="institution/plugins/iCheck/icheck.min.js"></script>
  <!-- sweetalert2 -->
  <script src="institution/dist/sw/sweetalert2.all.min.js"></script>
  <script>
    $(function() {
      $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
      });
    });
  </script>
</body>

</html>

<?php

if (isset($_POST['masuk'])) {

  $isim = htmlspecialchars(addslashes(mysqli_real_escape_string($conn, $_POST['namamu'])));
  $fiil = htmlspecialchars(addslashes(mysqli_real_escape_string($conn, $_POST['fiil'])));
  $tahun = htmlspecialchars(addslashes(mysqli_real_escape_string($conn, $_POST['tahun'])));

  $sql = mysqli_query($conn, "SELECT * FROM user WHERE username = '$isim' ");
  $cek = mysqli_num_rows($sql);
  $hasil = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user WHERE username = '$isim' "));

  if ($hasil['aktif'] == 'T') {
    echo "
        <script>
            Swal.fire({
              icon: 'error',
              title: 'Maaf',
              text: 'Akun anda belum aktif. Silahkan menghubungi admin'
            });
        </script>
      ";
  } else {
    if ($cek != 1) {
      echo "
    <script>
        Swal.fire({
          icon: 'error',
          title: 'Maaf',
          text: 'Username anda salah'
        });
    </script>
    ";
    } else {
      $dt = mysqli_fetch_assoc($sql);
      $pass = $dt['password'];
      $lvl = $dt['level'];
      $llmb = $dt['lembaga'];

      if (password_verify($fiil, $pass)) {

        $sssq = mysqli_query($conn, "SELECT * FROM akses WHERE lembaga = '$llmb' AND tahun = '$tahun' ");
        $cck = mysqli_fetch_assoc($sssq);
        $jmcck = mysqli_num_rows($sssq);

        $_SESSION['id'] = $dt['id_user'];
        $_SESSION['lmb'] = $dt['lembaga'];
        $_SESSION['tahun'] = $tahun;

        if ($cck['login'] === 'T' || $jmcck < 1) {
          echo "
          <script>
              Swal.fire({
                icon: 'error',
                title: 'Maaf',
                text: 'Anda tidak dapat mengakses Tahun Pelajaran ini. Silahkan hubungi admin'
              });
          </script>
          ";
        } else {
          $agent = @$_SERVER['HTTP_USER_AGENT'];
          $ip = @$_SERVER['REMOTE_ADDR'];
          $rinci = $dt['nama'] . ', ' . $dt['level'] . ', ' . $dt['lembaga'];
          mysqli_query($conn, "INSERT INTO user_log(rinci,ip,agent,waktu) VALUES('$rinci','$ip','$agent',now())");

          if ($lvl == 'admin') {
            $_SESSION['lvl_adm_qwertyuiop'] = true;
            $link = 'main/index.php';
          } elseif ($lvl == 'lembaga') {
            $_SESSION['lvl_lmbg_qwertyuiop'] = true;
            $link = 'institution/index.php';
          } elseif ($lvl == 'kasir') {
            $_SESSION['lvl_kasir_qwertyuiop'] = true;
            $link = 'cashier/index.php';
          } elseif ($lvl == 'kepala') {
            $_SESSION['lvl_super_qwertyuiop'] = true;
            $link = 'superior/index.php';
          } elseif ($lvl == 'account') {
            $_SESSION['lvl_account_qwertyuiop'] = true;
            $link = 'account/index.php';
          } elseif ($lvl == 'bunda') {
            $_SESSION['lvl_bundut_qwertyuiop'] = true;
            $link = 'bunda/index.php';
          }

          echo "
          <script>
              Swal.fire({
                  title: 'Berhasil',
                  text: 'Login berhasil. Anda akan dialihkan',
                  icon: 'success',
                  showConfirmButton: false
              });
              var millisecondsToWait = 2000;
              setTimeout(function() {
                  document.location.href = '" . $link . "'
              }, millisecondsToWait);
          </script>
        ";
        }
      } else {
        echo "
        <script>
            Swal.fire({
              icon: 'error',
              title: 'Maaf',
              text: 'Password anda salah'
            });
        </script>
        ";
      }
    }
  }
}
?>