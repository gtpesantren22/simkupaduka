<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
        $this->load->library('upload');
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
        unset($spreadsheet); // Hapus dari memori setelah dipakai
    }

    public function save()
    {
        $config['upload_path']   = './vertical/assets/uploads/';
        $config['allowed_types'] = 'xls|xlsx';
        $config['max_size']      = 2048;
        $config['encrypt_name']  = TRUE;

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file_excel')) {
            http_response_code(400);
            echo 'Upload gagal: ' . strip_tags($this->upload->display_errors());
            return;
        }

        $fileData = $this->upload->data();
        $filePath = './vertical/assets/uploads/' . $fileData['file_name'];

        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            $data = [];
            for ($i = 1; $i < count($sheetData); $i++) { // baris 0 = header
                if (!empty($sheetData[$i][0])) {
                    $nis = $sheetData[$i][1];
                    $nama  = $this->db->query("SELECT nama FROM tb_santri WHERE nis = '$nis' ")->row();
                    $data[] = [
                        'nis'   => $nis,
                        'nama'  => $nama ? $nama->nama : '',
                        'tgl' => $sheetData[$i][2],
                        'nominal' => $sheetData[$i][3],
                        'bulan' => $sheetData[$i][4],
                        'tahun' => $sheetData[$i][5],
                        'kasir' => $this->user,
                    ];
                }
            }

            if (!empty($data)) {
                $this->model->insert_batch('pembayaran', $data);
                echo "✅ Import berhasil! " . count($data) . " data ditambahkan.";
            } else {
                echo "⚠️ Tidak ada data yang valid di file Excel.";
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo 'Gagal memproses file Excel: ' . $e->getMessage();
        }

        unlink($filePath);
    }
}
