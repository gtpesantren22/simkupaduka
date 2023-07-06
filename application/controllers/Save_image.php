<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Save_image extends CI_Controller
{

    public function index()
    {
        // Pastikan request adalah POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Periksa apakah ada file gambar yang dikirim
            if (isset($_FILES['image'])) {
                $uploadPath = './vertical/assets/nota/'; // Ganti dengan direktori tujuan penyimpanan gambar
                $filename = rand() . '.jpg'; // Nama file yang diinginkan

                // Pindahkan file gambar ke direktori tujuan
                move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath . $filename);

                // Respon berhasil
                echo 'Gambar berhasil disimpan di server.';
            } else {
                // Respon jika tidak ada file gambar yang dikirim
                echo 'Tidak ada file gambar yang dikirim.';
            }
        } else {
            // Respon jika bukan permintaan POST
            echo 'Permintaan tidak valid.';
        }
    }
}
