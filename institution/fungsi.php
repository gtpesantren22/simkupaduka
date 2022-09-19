<?php
session_start();
//$conn = mysqli_connect("localhost", "root", "", "db_sentral");
// $conn = mysqli_connect("localhost", "u9048253_dwk", "PesantrenDWKIT2021", "u9048253_sentral");

require 'libs/vendor/autoload.php';
require '../koneksi.php';
require '../func_wa.php';

use Ramsey\Uuid\Uuid;

if (!isset($_SESSION['lvl_lmbg_qwertyuiop'])) {
    echo "
    <script>
    window.location = 'login.php';
    </script>
    ";
}

$uuid = Uuid::uuid4()->toString();

$id = $_SESSION['id'];
$lmb = $_SESSION['lmb'];
$tahun_ajaran = $_SESSION['tahun'];

$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user WHERE id_user = '$id' "));
$lm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$lmb' "));

$no = 1;
$id_user = $user['id_user'];
$kol = $lm['kode'];
$nama_user = $user['nama'];
$level = $user['level'];
