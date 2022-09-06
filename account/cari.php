<?php
session_start();

include '../koneksi.php';
$bb = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "July", "Agustus", "September", "Oktober", "November", "Desember");

$tahun_ajaran = $_SESSION['tahun'];

$judul = strip_tags($_GET['judul']);
if ($judul == "")
  echo "Masukkan judul arikel";
else {
  $query = "SELECT * FROM realis where kode_pengajuan = '$judul' AND tahun = '$tahun_ajaran' GROUP BY kode_pengajuan";
  $result = $conn->query($query) or die($conn->error . __LINE__);
  if ($result->num_rows > 0) {
    while ($rows = $result->fetch_assoc()) {
      extract($rows);
      $kd = $rows['kode_pengajuan'];
      $kdl = $rows['lembaga'];
      $cair = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(nominal) as nom FROM realis WHERE kode_pengajuan = '$kd' AND tahun = '$tahun_ajaran' "));
      $lm = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM lembaga WHERE kode = '$kdl' AND tahun = '$tahun_ajaran' "));
      //$sisa = $rows['total'] - $pakai['nom'];
      echo "
      <input type='hidden' name='cair' value='" . $cair['nom'] . "'>
                                                    <div class='item form-group'>
                                                        <label class='col-form-label col-md-3 col-sm-3 label-align' for='last-name'>Lembaga <span class='required'>*</span>
                                                        </label>
                                                        <div class='col-md-6 col-sm-6 '>
                                                            <input type='text' id='last-name' class='form-control' value='" . $lm['nama'] . "' disabled>
                                                        </div>
                                                    </div>
                                                    <div class='item form-group'>
                                                        <label for='middle-name' class='col-form-label col-md-3 col-sm-3 label-align'>Periode <span class='required'>*</span></label>
                                                        <div class='col-md-6 col-sm-6 '>
                                                            <input id='middle-name' class='form-control' type='text' value='" . $bb[$rows['bulan']] . ' ' . $rows['tahun'] . "' disabled>
                                                        </div>
                                                    </div>
                                                    <div class='item form-group'>
                                                        <label class='col-form-label col-md-3 col-sm-3 label-align' for='first-name'>Dana cair <span class='required'>*</span>
                                                        </label>
                                                        <div class='col-md-6 col-sm-6  form-group has-feedback'>
                                                            <input type='text' class='form-control has-feedback-left ' value='" . rupiah2($cair['nom']) . "' disabled>
                                                            <span class='form-control-feedback left' aria-hidden='true'>Rp.</span>
                                                        </div>
                                                    </div>
  ";
    }
  } else {
    $hasil = " <h4 style='color:red'>Artikel tidak ditemukan!</h4>";
  }
}
