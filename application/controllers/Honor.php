<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Honor extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('LembagaModel', 'model');
        $this->load->model('Auth_model');

        $user = $this->Auth_model->current_user();
        $this->tahun = $this->session->userdata('tahun');
        // $this->jenis = ['A. Belanja Barang', 'B. Langganan & Jasa', 'Belanja Kegiatan', 'D. Umum'];
        $this->bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $api = $this->model->apiKey()->row();
        $this->apiKey = $api->nama_key;
        $this->lembaga = $user->lembaga;

        if ((!$this->Auth_model->current_user() && $user->level != 'lembaga') || (!$this->Auth_model->current_user() && $user->level != 'admin')) {
            redirect('login/logout');
        }
        $this->honor_santri = 7000;
        $this->honor_non = 14000;
    }

    public function jamkerja()
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;

        $data['data'] = $this->model->getHonor()->result();

        $this->load->view('lembaga/head', $data);
        $this->load->view('lembaga/jamkerja', $data);
        $this->load->view('lembaga/foot', $data);
    }

    public function editJam($id)
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;

        $lembaga = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['data'] = $this->model->getHonorRinci($id, $lembaga->satminkal)->result();

        // echo $lembaga->satminkal . '<br>' . $id;
        // var_dump($data['data']);
        $this->load->view('lembaga/head', $data);
        $this->load->view('lembaga/editjamkerja', $data);
        $this->load->view('lembaga/foot', $data);
    }

    public function updateJam()
    {
        $id = $this->input->post('id', true);
        $value = $this->input->post('value', true);

        $dtlHonor = $this->model->flat_getBy('honor', 'id', $id)->row();
        $guru = $this->model->flat_getBy('guru', 'guru_id', $dtlHonor->guru_id)->row();

        $this->model->flat_edit('honor', ['kehadiran' => $value], 'id', $id);
        if ($this->db->affected_rows() > 0) {
            echo json_encode(['status' => 'ok', 'besaran' => $value]);
        } else {
            echo json_encode(['status' => 'gagal']);
        }
        // echo json_encode(['status' => 'ok', 'isi' => $guru->santri]);
    }

    public function potongan()
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;

        $data['data'] = $this->model->getPotongan()->result();

        $this->load->view('lembaga/head', $data);
        $this->load->view('lembaga/potongan', $data);
        $this->load->view('lembaga/foot', $data);
    }

    public function editPotongan($id)
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;

        $lembaga = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['data'] = $this->model->getPotonganRinci($id, $lembaga->satminkal)->result();
        $data['lembaga'] = $lembaga->nama;

        $this->load->view('lembaga/head', $data);
        $this->load->view('lembaga/editPotongan', $data);
        $this->load->view('lembaga/foot', $data);
    }

    public function ambil_data($id)
    {
        $data = $this->model->flat_getBy('potongan', 'id', $id)->row();
        $jml = $this->model->flat_getBy2('potongan', 'guru_id', $data->guru_id, 'potongan_id', $data->potongan_id)->num_rows();
        if ($jml < 2) {
            $jenis = ['Tabungan Wajib', 'SIMPOK', 'SIMWA', 'Koperasi/Cicilan', 'BPJS', 'Insijam', 'Infaq TPP', 'Pulsa', 'Verval TPP', 'Verval SIMPATIKA', 'Pinjaman Bank'];
            foreach ($jenis as $jns) {
                $simpandata = [
                    'potongan_id' => $data->potongan_id,
                    'guru_id' => $data->guru_id,
                    'bulan' => $data->bulan,
                    'tahun' => $data->tahun,
                    'ket' => $jns,
                ];
                $simpan = $this->model->flat_input('potongan', $simpandata);
            }
            if ($simpan > 0) {
                $this->model->flat_edit('potongan', ['ket' => 'Lain-lain'], 'id', $id);
                $hasil = $this->model->flat_getBy2('potongan', 'guru_id', $data->guru_id, 'potongan_id', $data->potongan_id)->result();
                echo json_encode(['status' => 'success', 'data' => $hasil]);
            } else {
                echo '<b>Gagal ambil data</b>';
            }
        } else {
            $hasil = $this->model->flat_getBy2('potongan', 'guru_id', $data->guru_id, 'potongan_id', $data->potongan_id)->result();
            echo json_encode(['status' => 'success', 'data' => $hasil]);
        }
    }

    public function add_row()
    {
        $id = $this->input->post('id', 'true');
        $data = $this->model->flat_getBy('potongan', 'id', $id)->row();
        $simpandata = [
            'potongan_id' => $data->potongan_id,
            'guru_id' => $data->guru_id,
            'bulan' => $data->bulan,
            'tahun' => $data->tahun,
            'ket' => '',
        ];
        $simpan = $this->model->flat_input('potongan', $simpandata);
        if ($simpan > 0) {
            $hasil = $this->model->flat_getBy2('potongan', 'guru_id', $data->guru_id, 'potongan_id', $data->potongan_id)->result();
            echo json_encode(['status' => 'success', 'data' => $hasil]);
        } else {
            echo json_encode(['status' => 'gagal']);
        }
    }
    public function del_row()
    {
        $id = $this->input->post('id', 'true');
        $data = $this->model->flat_getBy('potongan', 'id', $id)->row();

        $hps = $this->model->flat_delete('potongan', 'id', $id);
        if ($hps > 0) {
            $hasil = $this->model->flat_getBy2('potongan', 'guru_id', $data->guru_id, 'potongan_id', $data->potongan_id)->result();
            echo json_encode(['status' => 'success', 'data' => $hasil]);
        } else {
            echo json_encode(['status' => 'gagal']);
        }
    }

    public function updatePotongan()
    {
        $id = $this->input->post('id', 'true');
        $value = $this->input->post('value', 'true');
        $varname = $this->input->post('inputName', 'true');
        if ($varname == 'nominal') {
            $valueOk = rmRp($value);
        } else {
            $valueOk = $value;
        }


        $edit = $this->model->flat_edit('potongan', [$varname => $valueOk], 'id', $id);
        if ($edit > 0) {
            $data = $this->model->flat_getBy('potongan', 'id', $id)->row();
            $hasil = $this->model->flat_getBy2('potongan', 'guru_id', $data->guru_id, 'potongan_id', $data->potongan_id)->result();
            echo json_encode(['status' => 'success', 'data' => $hasil]);
        } else {
            echo json_encode(['status' => 'gagal']);
        }
        // echo json_encode(['status' => 'success', 'hasil' => $id]);
    }

    public function reload_total()
    {
        $id = $this->input->post('id', 'true');
        $data = $this->model->flat_getBy('potongan', 'id', $id)->row();
        $hasil = $this->model->flat_totoalPotongan($data->potongan_id, $data->guru_id)->row();
        echo json_encode(['status' => 'success', 'data' => $hasil->total, 'id' => $id]);
        // echo json_encode(['status' => 'success', 'id' => $id]);
    }
}
