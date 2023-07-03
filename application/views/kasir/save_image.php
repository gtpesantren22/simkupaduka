<?php
// Pastikan metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data gambar yang dikirim melalui POST
    $imageData = $_FILES['image']['tmp_name'];

    // Tentukan path dan nama file untuk menyimpan gambar
    $name = rand();
    $filename = 'foto/' . $name . '.jpg';

    // Pindahkan file gambar ke direktori dalam aplikasi
    if (move_uploaded_file($imageData, $filename)) {
        echo 'Gambar berhasil disimpan di server.';
    } else {
        echo 'Terjadi kesalahan saat menyimpan gambar.';
    }
} else {
    echo 'Metode request yang diterima harus POST.';
}
