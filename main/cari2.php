<?php
include '../koneksi.php';
$tahun_ajaran = $_SESSION['tahun'];

$judul = strip_tags($_GET['judul']);
if ($judul == "")
  echo "Masukkan judul arikel";
else {
  $query = "SELECT a.*, b.nama FROM disposisi a JOIN lembaga b ON a.lembaga=b.kode where a.kode = '$judul' AND tahun = '$tahun_ajaran' ";
  $result = $conn->query($query) or die($conn->error . __LINE__);
  if ($result->num_rows > 0) {
    while ($rows = $result->fetch_assoc()) {
      extract($rows);
?>

      <div class="item form-group">
        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Kode <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 ">
          <input type="text" name="kode" readonly class="form-control" value="<?= $judul; ?>">
        </div>
      </div>
      <div class="item form-group">
        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Lembaga <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 ">
          <input type="text" disabled class="form-control" value="<?= $rows['nama']; ?>">
          <input type="hidden" name="lembaga" class="form-control" value="<?= $rows['lembaga']; ?>">
        </div>
      </div>
      <div class="item form-group">
        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Nominal <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 ">
          <input type="text" name="nominal" readonly class="form-control" value="<?= rupiah($rows['nominal']); ?>">
        </div>
      </div>
      <div class="item form-group">
        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Terserap <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 ">
          <input type="text" name="serap" class="form-control" id="uang" required>
        </div>
      </div>
      <div class="item form-group">
        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Tanggal setor <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 ">
          <input type="date" name="tgl" class="form-control" required>
        </div>
      </div>
      <div class="item form-group">
        <label class="col-form-label col-md-3 col-sm-3 label-align" for="last-name">Catatan <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 ">
          <textarea name="catatan" required="required" class="form-control"> </textarea>
        </div>
      </div>

<?php
    }
  } else {
    $hasil = " <h4 style='color:red'>Artikel tidak ditemukan!</h4>";
  }
}
?>
<!-- jQuery 2.1.4 -->
<!-- <script src=" vendors/jquery/jQuery-2.1.4.min.js"></script> -->
<script type="text/javascript">
  var rupiah = document.getElementById('uang');

  rupiah.addEventListener('keyup', function(e) {
    rupiah.value = formatRupiah(this.value);
  });

  /* Fungsi formatRupiah */
  function formatRupiah(angka, prefix) {
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
      split = number_string.split(','),
      sisa = split[0].length % 3,
      rupiah = split[0].substr(0, sisa),
      ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if (ribuan) {
      separator = sisa ? '.' : '';
      rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? rupiah : '');
  }
</script>