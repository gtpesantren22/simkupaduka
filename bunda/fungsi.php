<?php
session_start();

include '../koneksi.php';
require '../institution/libs/vendor/autoload.php';

use Ramsey\Uuid\Uuid;

if (!isset($_SESSION['lvl_bundut_qwertyuiop'])) {
    echo "
    <script>
    window.location = '../login.php';
    </script>
    ";
}

$uuid = Uuid::uuid4()->toString();

$id = $_SESSION['id'];
$lmb = $_SESSION['lmb'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user WHERE id_user = '$id' "));
$lm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$lmb' "));

$no = 1;
$id_user = $user['id_user'];
$kol = $lm['kode'];
$nama_user = $user['nama'];
$level = $user['level'];
