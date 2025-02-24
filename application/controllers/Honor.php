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
    public function jamkaryawan()
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;

        $data['data'] = $this->model->getKehadiran()->result();

        $this->load->view('lembaga/head', $data);
        $this->load->view('lembaga/jamkaryawan', $data);
        $this->load->view('lembaga/foot', $data);
    }

    public function editJam($id)
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['lembaga'] = $this->lembaga;

        $lembaga = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['ptty'] = $this->model->allPtty($lembaga->satminkal)->result();
        $data['honor'] = $this->model->flat_getBy('honor', 'honor_id', $id)->row();

        $datas = $this->model->getHonorRinci($id, $lembaga->satminkal)->result();
        $datakirim = [];
        foreach ($datas as $datai) {
            $guru = $this->model->flat_getBy('guru', 'guru_id', $datai->guru_id)->row();
            $datakirim[] = [
                'bulan' => $datai->bulan,
                'tahun' => $datai->tahun,
                'nama' => $datai->nama,
                'santri' => $datai->santri,
                'id' => $datai->id,
                'kehadiran' => $datai->kehadiran,
                'lembaga' => $lembaga->satminkal,
                'asal' => $guru->satminkal,
            ];
        }

        $data['data'] = $datakirim;

        // echo "<pre>";
        // print_r($datakirim);
        // echo "</pre>";
        // die();


        $this->load->view('lembaga/head', $data);
        $this->load->view('lembaga/editjamkerja', $data);
        $this->load->view('lembaga/foot', $data);
    }
    public function editJamKehadiran($id)
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;

        $lembaga = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['data'] = $this->model->getKehadiranRinci($id, $lembaga->satminkal)->result();

        // echo $lembaga->satminkal . '<br>' . $id;
        // var_dump($data['data']);
        $this->load->view('lembaga/head', $data);
        $this->load->view('lembaga/editjamkaryawan', $data);
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
    }
    public function updateJamKaryawan()
    {
        $id = $this->input->post('id', true);
        $value = $this->input->post('value', true);

        $dtlHonor = $this->model->flat_getBy('kehadiran', 'id', $id)->row();
        // $guru = $this->model->flat_getBy('guru', 'guru_id', $dtlHonor->guru_id)->row();

        $this->model->flat_edit('kehadiran', ['kehadiran' => $value], 'id', $id);
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
        $data['data'] = $this->model->getPotonganRinci($id, $lembaga->satminkal);
        $data['potongan'] = $this->model->getPotongan()->result();
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

    public  function addPtty()
    {
        $honor_id = $this->input->post('honor_id', true);
        // $lembaga = $this->input->post('lembaga', true);
        $guru_id = $this->input->post('guru_id', true);
        $lembaga = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();

        $cek = $this->model->flat_getBy3('honor', 'honor_id', $honor_id, 'lembaga', $lembaga->satminkal, 'guru_id', $guru_id)->row();
        if ($cek) {
            $this->session->set_flashdata('error', 'Data sudah ada');
            redirect('honor/editJam/' . $this->input->post('honor_id', true));
        } else {
            $datas = [
                'honor_id' => $honor_id,
                'lembaga' => $lembaga->satminkal,
                'guru_id' => $guru_id,
                'bulan' => $this->input->post('bulan', true),
                'tahun' => $this->input->post('tahun', true),
                'created_at' => date('Y-m-d H:i:s')
            ];

            $this->model->flat_input('honor', $datas);
            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('ok', 'Data berhasil ditambahkan');
                redirect('honor/editJam/' . $this->input->post('honor_id', true));
            } else {
                $this->session->set_flashdata('error', 'Data gagal ditambahkan');
                redirect('honor/editJam/' . $this->input->post('honor_id', true));
            }
        }
    }

    public function delPtty($id)
    {
        $data = $this->model->flat_getBy('honor', 'id', $id)->row();
        $this->model->flat_delete('honor', 'id', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Data berhasil dihapus');
            redirect('honor/editJam/' . $data->honor_id);
        } else {
            $this->session->set_flashdata('error', 'Data gagal dihapus');
            redirect('honor/editJam/' . $data->honor_id);
        }
    }

    public function clonePotongan()
    {
        $dipilih = $this->input->post('dipilih', true);
        $id_asal = $this->input->post('id_asal', true);
        $guru_id = $this->input->post('guru_id', true);

        $dataAsal = $this->model->flat_getBy('potongan', 'potongan_id', $id_asal)->row();
        // Hapus data potongan lama sesuai guru_id dan potongan_id
        $this->model->flat_delete2('potongan', 'guru_id', $guru_id, 'potongan_id', $id_asal);

        // Ambil data pilihan yang akan diinput untuk guru tersebut
        $dataPilihan = $this->model->flat_getBy2('potongan', 'potongan_id', $dipilih, 'guru_id', $guru_id)->result();

        // Jika data pilihan tidak kosong, lakukan insert untuk setiap pilihan
        if (!empty($dataPilihan)) {
            foreach ($dataPilihan as $p) {
                $input = [
                    'potongan_id' => $id_asal,
                    'guru_id'     => $guru_id,
                    'bulan'       => $dataAsal->bulan,
                    'tahun'       => $dataAsal->tahun,
                    'ket'         => $p->ket,
                    'nominal'     => $p->nominal,
                ];
                $this->model->flat_input('potongan', $input);
            }
        }

        if ($this->db->affected_rows() > 0) {
            echo json_encode(['message' => 'success']);
        } else {
            echo json_encode(['message' => 'gagal']);
        }
    }

    public function getDataguru()
    {
        $dataGuru = $this->model->flat_getBy('guru', 'satminkal', $this->lembaga)->result();
        echo json_encode($dataGuru);
    }
}
