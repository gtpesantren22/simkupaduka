<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Import extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('KasirModel', 'model');
        $this->load->model('Auth_model');

        $user = $this->Auth_model->current_user();
        $this->user = $user->nama;
        $this->tahun = $this->session->userdata('tahun');
    }

    public function index()
    {
        // $this->load->view('import_form');
    }

    // Step 1: Upload dan preview data
    public function preview()
    {
        $file_tmp = $_FILES['file_excel']['tmp_name'];

        if (!$file_tmp) {
            $this->session->set_flashdata('error', 'Pilih file Excel terlebih dahulu.');
            redirect('kasir/bayar');
        }

        $reader = new Xlsx();
        $spreadsheet = $reader->load($file_tmp);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        // Simpan sementara ke session agar bisa disimpan nanti
        $this->session->set_userdata('preview_data', $sheetData);
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['sheetData'] = $sheetData;
        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/import_review', $data);
        $this->load->view('kasir/foot');
    }

    // Step 2: Simpan ke database
    public function save()
    {
        $sheetData = $this->session->userdata('preview_data');
        if (!$sheetData) {
            $this->session->set_flashdata('error', 'Tidak ada data untuk disimpan.');
            redirect('kasir/bayar');
        }

        $pegawai = [];
        $no = 0;
        foreach ($sheetData as $row) {
            $no++;
            if ($no == 1) continue; // skip header

            $data[] = [
                'nis' => $row['B'],
                'nama' => $this->model->getBy('tb_santri', 'nis', $row['B'])->row('nama'),
                'tgl' => $row['C'],
                'nominal' => $row['D'],
                'bulan' => $row['E'],
                'tahun' => $row['F'],
                'kasir' => $this->user,
            ];
        }

        $this->model->insert_batch('pembayaran', $data);
        $this->session->unset_userdata('preview_data');
        $this->session->set_flashdata('ok', 'Data pembayaran berhasil diimport!');
        redirect('kasir/bayar');
    }
}
