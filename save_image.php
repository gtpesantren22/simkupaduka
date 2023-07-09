<?php
// Pastikan metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data gambar yang dikirim melalui POST
    $imageData = $_FILES['image']['tmp_name'];

    $kode_pengajuan = $_GET['kode_pengajuan'];
    $id_mitra = $_GET['id_mitra'];
    // var_dump($imageData);
    // Tentukan path dan nama file untuk menyimpan gambar
    $name = $kode_pengajuan . '_' . $id_mitra;
    $filename = 'vertical/assets/nota/' . $name . '.jpg';

    // Pindahkan file gambar ke direktori dalam aplikasi
    if (move_uploaded_file($imageData, $filename)) {
        // base_url('kasir/sendNota/' . $id_mitra . '/' . $kode_pengajuan);
    } else {
        echo 'Terjadi kesalahan saat menyimpan gambar.';
    }
} else {
    echo 'Metode request yang diterima harus POST.';
}

if ($_GET['tujuan'] == 'kirimWA') {

    $key = $_POST['apiKey'];
    $no_hp = $_POST['phone'];
    $url_file = $_POST['url_file'];
    $as_document = $_POST['as_document'];
    $caption = $_POST['caption'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://191.101.3.115:3000/api/sendMediaFromUrl");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'apiKey=' . $key . '&phone=' . $no_hp . '&url_file=' . $url_file . '&as_document=' . $as_document . '&caption=' . $caption);
    $hasil = curl_exec($ch);

    echo $hasil;
    curl_close($ch);
}
