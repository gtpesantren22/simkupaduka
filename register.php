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
  <div class="register-box">
    <div class="register-logo">
      <a href="#"><img src="institution/dist/img/logo simku paduka.png" width="300" alt=""></a>
    </div>

    <div class="register-box-body">
      <p class="login-box-msg">Daftar akun baru</p>
      <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group has-feedback">
          <input type="text" class="form-control" placeholder="Nama lengkap" name="nama" required>
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="text" class="form-control" placeholder="Username" name="username" required>
          <span class="glyphicon glyphicon-list form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="password" class="form-control" placeholder="Password" name="password" required>
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="password" class="form-control" placeholder="Ulangi password" name="password2" required>
          <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="file" class="form-control" placeholder="Surat tugas" name="surat" required>
          <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          <span><b style="color: red;">*Upload surat tugas dari lembaga. (pdf)</b></span>
        </div>
        <div class="row">
          <div class="col-xs-8">
            <div class="checkbox icheck">
              <label>
                <input type="checkbox"> I agree
              </label>
            </div>
          </div><!-- /.col -->
          <div class="col-xs-4">
            <button type="submit" name="daftar" class="btn btn-primary btn-block btn-flat"><i class="fa fa-save"></i> Daftar</button>
          </div><!-- /.col -->
        </div>
      </form>
      <hr>
      <center> <a href="login.php" class="text-center"><i class="fa fa-arrow-left"></i> Kembali ke halaman login</a>
      </center>
    </div><!-- /.form-box -->
  </div><!-- /.register-box -->

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
include 'koneksi.php';

require 'institution/libs/vendor/autoload.php';

use Ramsey\Uuid\Uuid;

$uuid = Uuid::uuid4()->toString();
if (isset($_POST['daftar'])) {

  $id = $uuid;
  $nama = htmlspecialchars(mysqli_real_escape_string($conn, strtoupper($_POST['nama'])));
  $username = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['username']));
  $password = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['password']));
  $password2 = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['password2']));

  $ekstensi =  array('pdf');
  $filename = $_FILES['surat']['name'];
  $ukuran = $_FILES['surat']['size'];
  $ext = pathinfo($filename, PATHINFO_EXTENSION);
  $extensi = explode('.', $filename);

  $cek_user = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM user WHERE username = '$username' "));
  if ($cek_user >= 1) {
    echo "
      <script>
        Swal.fire({
          icon: 'error',
          title: 'Maaf',
          text: 'Username ini sudah digunakan'
        });
      </script>
      ";
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
      if (!in_array($ext, $ekstensi)) {
        echo "
      <script>
        Swal.fire({
          icon: 'error',
          title: 'Maaf',
          text: 'File yang anda harus PDF'
        });
      </script>
      ";
      } else {
        $xx = $nama . '.' . end($extensi);
        $ps = password_hash($password, PASSWORD_DEFAULT);
        $file_lama = 'institution/spj_file/' . $xx;
        if (file_exists($file_lama)) {
          unlink($file_lama);
        }

        $sql = mysqli_query($conn, "INSERT INTO user(id_user, nama, username, password, aktif, surat) VALUES ('$id', '$nama','$username','$ps', 'T', '$xx') ");

        $psn = '
*INFORMASI PEMBUATAN AKUN BARU*

Ada permintaan baru dari :
    
Nama : ' . $nama . '

*_dimohon kepada ADMIN SIMKUPADUKA untuk segera mengecek nya di https://simkupaduka.ppdwk.com/_*
Terimakasih';

        if ($sql) {

          $curl3 = curl_init();
          curl_setopt_array(
            $curl3,
            array(
              CURLOPT_URL => 'http://8.215.26.187:3000/api/sendMessageGroup',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => 'apiKey=fb209be1f23625e43cbf285e57c0c0f2&id_group=FbXW8kqR5ik6w6iCB49GZK&message=' . $psn,
            )
          );
          $response = curl_exec($curl3);
          curl_close($curl3);


          echo "
        <script>
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: 'Akun anda berhasil ditambahkan. Silahkan menghubungi admin untuk aktivasi akun anda'
        });
        var millisecondsToWait = 2500;
          setTimeout(function() {
          document.location.href = 'login.php'
        }, millisecondsToWait);
        </script>
        ";
        }
        move_uploaded_file($_FILES['surat']['tmp_name'], 'institution/spj_file/' . $xx);
      }
    }
  }
}
?>