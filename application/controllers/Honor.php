<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Honor extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('LembagaModel', 'model');
        $this->load->model('Auth_model');
        $this->flat = $this->load->database('flat', true);

        $user = $this->Auth_model->current_user();
        $this->tahun = $this->session->userdata('tahun');
        // $this->jenis = ['A. Belanja Barang', 'B. Langganan & Jasa', 'Belanja Kegiatan', 'D. Umum'];
        $this->bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $api = $this->model->apiKey()->row();
        $this->apiKey = $api->nama_key;
        $this->lembaga = $user->lembaga;
        $this->flat = $this->load->database('flat', true);

        if ((!$this->Auth_model->current_user() && $user->level != 'lembaga') || (!$this->Auth_model->current_user() && $user->level != 'admin')) {
            redirect('login/logout');
        }

        $this->token = $this->model->getToken();
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
        $data['gaji'] = $this->model->flat_getBy2('gaji', 'bulan', $data['honor']->bulan, 'tahun', $data['honor']->tahun)->row();

        $datas = $this->model->getHonorRinci($id, $lembaga->satminkal)->result();
        $data['honor_id'] = $id;

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
        // $data['data'] = $this->model->getKehadiranRinci($id, $lembaga->satminkal);
        $data['data'] = $this->model->flat_getBy('kehadiran', 'kehadiran_id', $id);
        $data['gaji'] = $this->model->flat_getBy2('gaji', 'bulan', $data['data']->row('bulan'), 'tahun', $data['data']->row('tahun'))->row();
        $data['kehadiran_id'] = $id;

        // echo $lembaga->satminkal . '<br>' . $id;
        // var_dump($data['data']);
        $this->load->view('lembaga/head', $data);
        $this->load->view('lembaga/editjamkaryawan', $data);
        $this->load->view('lembaga/foot', $data);
    }

    public function updateJam()
    {
        $id = $this->input->post('id', true);
        $jam = $this->input->post('value', true);
        $guru_id = $this->input->post('guru_id', true);
        $satminkal = $this->input->post('satminkal', true);
        $satminkal_id = $this->input->post('satminkal_id', true);
        $honor_id = $this->input->post('honor_id', true);
        $ket = $this->input->post('ket', true);
        $honor = $this->model->flat_getBy('honor', 'honor_id', $honor_id)->row();

        $honor_rami = $this->model->flat_getBy('settings', 'nama', 'honor_rami')->row('isi');
        $honor_santri = $this->model->flat_getBy('settings', 'nama', 'honor_santri')->row('isi');
        $honor_non = $this->model->flat_getBy('settings', 'nama', 'honor_non')->row('isi');


        if (strpos($satminkal, 'MI') === 0 || strpos($satminkal, 'RA') === 0) {
            $nominal = $jam * $honor_rami;
        } else {
            $nominal = $ket === 'Santri' ? $jam * $honor_santri : $jam * $honor_non;
        }

        if ($id == 0) {
            $data = [
                'guru_id' => $guru_id,
                'lembaga' => $satminkal,
                'honor_id' => $honor_id,
                'bulan' => $honor->bulan,
                'tahun' => $honor->tahun,
                'kehadiran' => $jam,
                'nominal' => $nominal,
                'lembaga' => $satminkal_id,
                'created_at' => date('Y-m-d H:i'),
            ];
            $sql = $this->model->flat_input('honor', $data);
            if ($sql > 0) {
                $newId = $this->model->flat_getBy2('honor', 'guru_id', $guru_id, 'honor_id', $honor_id)->row('id');
            }
        } else {
            $sql = $this->model->flat_edit('honor', [
                'kehadiran' => $jam,
                'nominal' => $nominal,
                'bulan' => $honor->bulan,
                'tahun' => $honor->tahun,
            ], 'id', $id);

            $gaji = $this->model->flat_getBy2('gaji', 'bulan', $honor->bulan, 'tahun', $honor->tahun)->row();
            if ($gaji && $gaji->status != 'kunci') {
                $this->model->flat_query("UPDATE gaji_detail SET is_dirty = 1 WHERE guru_id = '$guru_id' AND gaji_id = '$gaji->gaji_id'");
            }

            $newId = $id;
        }

        if ($sql > 0) {
            echo json_encode([
                'status' => 'ok',
                'besaran' => $jam,
                'ket_bulan' => bulan($honor->bulan) . ' ' . $honor->tahun,
                'newId' => $newId
            ]);
        } else {
            echo json_encode(['status' => 'gagal']);
        }
    }
    public function updateJamKaryawan()
    {
        $id = $this->input->post('id', true);
        $value = $this->input->post('value', true);
        $guru_id = $this->input->post('guru_id', true);
        $kehadiran_id = $this->input->post('kehadiran_id', true);

        $kehadiran = $this->model->flat_getBy('kehadiran', 'kehadiran_id', $kehadiran_id)->row();

        if ($id == 0) {
            $data = [
                'guru_id' => $guru_id,
                'kehadiran_id' => $kehadiran_id,
                'bulan' => $kehadiran->bulan,
                'tahun' => $kehadiran->tahun,
                'kehadiran' => $value,
                'created_at' => date('Y-m-d H:i'),
            ];
            $sql = $this->model->flat_input('kehadiran', $data);
            if ($sql > 0) {
                $newId = $this->model->flat_getBy2('kehadiran', 'guru_id', $guru_id, 'kehadiran_id', $kehadiran_id)->row('id');
            }
        } else {
            $sql = $this->model->flat_edit('kehadiran', [
                'kehadiran' => $value,
                'bulan' => $kehadiran->bulan,
                'tahun' => $kehadiran->tahun,
            ], 'id', $id);

            $gaji = $this->model->flat_getBy2('gaji', 'bulan', $kehadiran->bulan, 'tahun', $kehadiran->tahun)->row();
            if ($gaji && $gaji->status != 'kunci') {
                $this->model->flat_query("UPDATE gaji_detail SET is_dirty = 1 WHERE guru_id = '$guru_id' AND gaji_id = '$gaji->gaji_id'");
            }

            $newId = $id;
        }

        if ($sql > 0) {
            echo json_encode([
                'status' => 'ok',
                'besaran' => $value,
                'ket_bulan' => bulan($kehadiran->bulan) . ' ' . $kehadiran->tahun,
                'newId' => $newId
            ]);
        } else {
            echo json_encode(['status' => 'gagal']);
        }
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
        // $data['data'] = $this->model->getPotonganRinci($id, $lembaga->satminkal);
        $data['potongan'] = $this->model->flat_getBy('potongan', 'potongan_id', $id)->row();
        $data['potongan_list'] = $this->model->getPotongan()->result();
        $data['gaji'] = $this->model->flat_getBy2('gaji', 'bulan', $data['potongan']->bulan, 'tahun', $data['potongan']->tahun)->row();
        $data['lembaga'] = $lembaga->nama;
        $data['potongan_id'] = $id;

        $this->load->view('lembaga/head', $data);
        $this->load->view('lembaga/editPotongan', $data);
        $this->load->view('lembaga/foot', $data);
    }

    public function ambil_data()
    {
        $potongan_id = $this->input->post('potongan_id', true);
        $guru_id = $this->input->post('guru_id', true);

        $data = $this->model->flat_getBy('potongan', 'potongan_id', $potongan_id)->row();
        $jml = $this->model->flat_getBy2('potongan', 'guru_id', $guru_id, 'potongan_id', $potongan_id);

        if (!$jml->row() || $jml->num_rows() < 2) {
            $jenis = ['Tabungan Wajib', 'SIMPOK', 'SIMWA', 'Koperasi/Cicilan', 'BPJS', 'Insijam', 'Infaq TPP', 'Pulsa', 'Verval TPP', 'Verval SIMPATIKA', 'Pinjaman Bank'];
            foreach ($jenis as $jns) {
                $simpandata = [
                    'potongan_id' => $data->potongan_id,
                    'guru_id' => $guru_id,
                    'bulan' => $data->bulan,
                    'tahun' => $data->tahun,
                    'ket' => $jns,
                    'nominal' => 0,
                ];
                $simpan = $this->model->flat_input('potongan', $simpandata);
            }
            if ($simpan > 0) {
                $this->model->flat_delete3('potongan', 'guru_id', $guru_id, 'potongan_id', $data->potongan_id, 'ket', '');
                $hasil = $this->model->flat_getBy2('potongan', 'guru_id', $guru_id, 'potongan_id', $data->potongan_id);
                echo json_encode(['status' => 'success', 'data' => $hasil->result(), 'id' => $hasil->result()[0]->id]);
            } else {
                echo '<b>Gagal ambil data</b>';
            }
        } else {
            $hasil = $this->model->flat_getBy2('potongan', 'guru_id', $guru_id, 'potongan_id', $data->potongan_id);
            echo json_encode(['status' => 'success', 'data' => $hasil->result(), 'id' => $hasil->result()[0]->id]);
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

            $gaji = $this->model->flat_getBy2('gaji', 'bulan', $data->bulan, 'tahun', $data->tahun)->row();
            if ($gaji && $gaji->status != 'kunci') {
                $this->model->flat_query("UPDATE gaji_detail SET is_dirty = 1 WHERE guru_id = '$data->guru_id' AND gaji_id = '$gaji->gaji_id'");
            }

            echo json_encode(['status' => 'success', 'data' => $hasil]);
        } else {
            echo json_encode(['status' => 'gagal']);
        }
        // echo json_encode(['status' => 'success', 'hasil' => $id]);
    }

    public function reload_total()
    {
        $id = $this->input->post('id', true);
        $data = $this->model->flat_getBy('potongan', 'id', $id)->row();
        $hasil = $this->model->flat_totoalPotongan($data->potongan_id, $data->guru_id)->row();
        echo json_encode(['status' => 'success', 'data' => $hasil->total, 'guru_id' => $data->guru_id]);
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
        $lembaga = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $dataGuru = $this->model->flat_getBy('guru', 'satminkal', $lembaga->satminkal)->result();
        echo json_encode($dataGuru);
    }

    public function rincian()
    {
        // ======================================================
        // 1. PARAMETER
        // ======================================================

        $satminkal_terpilih = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row('satminkal');

        $search  = $this->input->get('search') ?? '';
        $page    = max(1, (int) ($this->input->get('page') ?? 1));
        $perPage = max(1, (int) ($this->input->get('perPage') ?? 10));
        $sortBy  = $this->input->get('sortBy') ?? 'nama';
        $sortDir = strtoupper($this->input->get('sortDir') ?? 'ASC');

        $honorID  = $this->input->get('honor_id') ?? '';

        // ======================================================
        // 2. AMBIL DATA DARI DATABASE (FINAL FIX)
        // ======================================================

        $this->flat->select('
            g.guru_id as ptk_id,
            g.nama,
            g.sik as status_pegawai,
            g.kriteria as jenis_kesantrian,
            s.id as lembaga_id,
            s.nama as nama_lembaga
        ');

        $this->flat->from('guru g');
        $this->flat->join('registrasi r', 'r.id_guru = g.guru_id', 'left');
        $this->flat->join('satminkal s', 's.id = r.id_lembaga', 'left');

        // SEARCH
        if (!empty($search)) {
            $this->flat->like('g.nama', $search);
        }

        // SORT (antisipasi error field)
        $allowedSort = ['nama', 'guru_id', 'sik'];
        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'nama';
        }

        $this->flat->order_by('g.' . $sortBy, $sortDir);

        $query = $this->flat->get()->result_array();


        // ======================================================
        // BENTUKKAN SEPERTI FORMAT API
        // ======================================================

        $temp = [];

        foreach ($query as $q) {

            $ptk_id = $q['ptk_id'];

            if (!isset($temp[$ptk_id])) {
                $temp[$ptk_id] = [
                    'ptk_id' => $ptk_id,
                    'nama' => $q['nama'],
                    'status_pegawai' => $q['status_pegawai'], // dari sik
                    'jenis_kesantrian' => $q['jenis_kesantrian'],
                    'registrasi_ptk' => []
                ];
            }

            if (!empty($q['lembaga_id'])) {
                $temp[$ptk_id]['registrasi_ptk'][] = [
                    'lembaga' => [
                        'lembaga_id' => $q['lembaga_id'],
                        'nama' => $q['nama_lembaga'] ?? '-'
                    ]
                ];
            }
        }

        $rawData = array_values($temp);

        // ======================================================
        // 3. FILTER DATA (PTTY + SATMINKAL)
        // ======================================================

        $filtered = [];

        foreach ($rawData as $row) {

            if (($row['status_pegawai'] ?? '') !== 'PTTY') {
                continue;
            }

            $match = false;
            $satminkal = '-';
            $satminkalId = null;

            foreach ($row['registrasi_ptk'] ?? [] as $r) {

                $lembagaId = $r['lembaga']['lembaga_id'] ?? null;

                if ($satminkalId === null) {
                    $satminkal = $r['lembaga']['nama'] ?? '-';
                    $satminkalId = $lembagaId;
                }

                // FIX FILTER DISINI
                if ($lembagaId == $satminkal_terpilih) {
                    $match = true;
                    $satminkal = $r['lembaga']['nama'] ?? '-';
                    $satminkalId = $lembagaId;
                    break;
                }
            }

            if (!$match) continue;

            $row['_satminkal'] = $satminkal;
            $row['_satminkal_id'] = $satminkalId;

            $filtered[] = $row;
        }

        // ======================================================
        // 4. PAGINATION SETELAH FILTER
        // ======================================================

        $totalTendik = count($filtered);
        $lastPage = $perPage > 0 ? ceil($totalTendik / $perPage) : 0;

        $offset = ($page - 1) * $perPage;
        $pagedData = array_slice($filtered, $offset, $perPage);

        // ======================================================
        // 5. AMBIL HONOR SEKALI SAJA
        // ======================================================

        $guruIds = array_column($pagedData, 'ptk_id');
        $honorIndexed = [];

        if (!empty($guruIds)) {
            $honorRows = $this->flat
                ->where('honor_id', $honorID)
                ->where('lembaga', $satminkal_terpilih)
                ->where_in('guru_id', $guruIds)
                ->get('honor')
                ->result();

            foreach ($honorRows as $h) {
                $honorIndexed[$h->guru_id] = $h;
            }
        }

        // ======================================================
        // 6. BUILD OUTPUT
        // ======================================================

        $data = [];

        foreach ($pagedData as $row) {

            $guru_id = $row['ptk_id'] ?? '';
            $hadir = $honorIndexed[$guru_id] ?? null;

            $data[] = [
                'id' => $hadir->id ?? 0,
                'honor_id' => $honorID,
                'guru_id' => $guru_id,
                'nama' => $row['nama'] ?? '-',
                'bulan' => $hadir ? bulan($hadir->bulan) : '',
                'tahun' => $hadir->tahun ?? '',
                'satminkal' => $row['_satminkal'],
                'satminkal_id' => $row['_satminkal_id'],
                'hadir' => $hadir->kehadiran ?? 0,
                'nominal' => $hadir->nominal ?? 0,
                'ket' => $row['jenis_kesantrian'] ?? '-',
            ];
        }

        // ======================================================
        // 7. RESPONSE
        // ======================================================

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'data' => $data,
                'total' => $totalTendik,
                'page' => $page,
                'perPage' => $perPage,
                'lastPage' => $lastPage,
            ]));
    }

    public function rincian_hadir()
    {
        // ======================================================
        // 1. PARAMETER
        // ======================================================

        $satminkal_terpilih = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row('satminkal');

        $search   = $this->input->get('search') ?? '';
        $page     = max(1, (int) ($this->input->get('page') ?? 1));
        $perPage  = max(1, (int) ($this->input->get('perPage') ?? 10));
        $sortBy   = $this->input->get('sortBy') ?? 'nama';
        $sortDir  = strtoupper($this->input->get('sortDir') ?? 'ASC');
        $kehadiranID  = $this->input->get('kehadiran_id') ?? '';

        // ======================================================
        // 2. AMBIL DATA DARI DATABASE (FINAL FIX)
        // ======================================================

        // TAMBAHKAN is_satminkal dari registrasi
        $this->flat->select('
            g.guru_id as ptk_id,
            g.nama,
            g.kriteria ,
            s.id as lembaga_id,
            s.nama as nama_lembaga,
            r.satminkal as ptk_induk,
            g.santri as santri
        ');

        $this->flat->from('guru g');
        $this->flat->join('registrasi r', 'r.id_guru = g.guru_id', 'left');
        $this->flat->join('satminkal s', 's.id = r.id_lembaga', 'left');

        // SEARCH
        if (!empty($search)) {
            $this->flat->like('g.nama', $search);
        }

        // SORT (antisipasi error field)
        $allowedSort = ['nama', 'guru_id', 'sik'];
        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'nama';
        }

        $this->flat->order_by('g.' . $sortBy, $sortDir);

        $query = $this->flat->get()->result_array();


        // ======================================================
        // BENTUKKAN SEPERTI FORMAT API (FIX TOTAL)
        // ======================================================

        $temp = [];

        foreach ($query as $q) {

            $ptk_id = $q['ptk_id'];

            // ✅ SET DATA UTAMA (WAJIB)
            if (!isset($temp[$ptk_id])) {
                $temp[$ptk_id] = [
                    'ptk_id' => $ptk_id,
                    'nama' => $q['nama'],
                    'kriteria' => $q['kriteria'], // 🔥 penting untuk filter
                    'santri' => $q['santri'],
                    'registrasi' => []
                ];
            }

            // ✅ MASUKKAN REGISTRASI (HANYA SEKALI)
            if (!empty($q['lembaga_id'])) {
                $temp[$ptk_id]['registrasi'][] = [
                    'lembaga' => [
                        'lembaga_id' => $q['lembaga_id'],
                        'nama' => $q['nama_lembaga'] ?? '-'
                    ],
                    'ptk_induk' => (int) $q['ptk_induk'] // 🔥 WAJIB ADA
                ];
            }
        }

        $rawData = array_values($temp);

        // ======================================================
        // 3. FILTER DATA (Karyawan + SATMINKAL)
        // ======================================================

        $filtered = [];

        foreach ($rawData as $row) {

            // ✅ FILTER 1: status harus Karyawan
            if (($row['kriteria'] ?? '') !== 'Karyawan') {
                continue;
            }

            $satminkal   = '-';
            $satminkalId = null;
            $match       = false;

            foreach ($row['registrasi'] ?? [] as $r) {

                $lembagaId = $r['lembaga']['lembaga_id'] ?? null;
                $ptkInduk  = (int) ($r['ptk_induk'] ?? 0);

                // ❌ SKIP kalau bukan satminkal utama
                if ($ptkInduk !== 1) {
                    continue;
                }

                // simpan default (yang satminkal=1 pertama)
                if ($satminkalId === null) {
                    $satminkal   = $r['lembaga']['nama'] ?? '-';
                    $satminkalId = $lembagaId;
                }

                // ✅ kalau ada filter lembaga
                if (!empty($satminkal_terpilih)) {
                    if ($lembagaId == $satminkal_terpilih) {
                        $match = true;
                        break;
                    }
                } else {
                    // ✅ kalau tidak ada filter lembaga, cukup satminkal=1 saja
                    $match = true;
                    break;
                }
            }

            // ❌ tidak memenuhi syarat
            if (!$match) {
                continue;
            }

            $row['_satminkal']    = $satminkal;
            $row['_satminkal_id'] = $satminkalId;

            $filtered[] = $row;
        }

        // ======================================================
        // 4. PAGINATION SETELAH FILTER
        // ======================================================

        $totalTendik = count($filtered);
        $lastPage = $perPage > 0 ? ceil($totalTendik / $perPage) : 0;

        $offset = ($page - 1) * $perPage;
        $pagedData = array_slice($filtered, $offset, $perPage);

        // ======================================================
        // 5. AMBIL HONOR SEKALI SAJA
        // ======================================================

        $guruIds = array_column($pagedData, 'ptk_id');
        $kehadiranIndexed = [];

        if (!empty($guruIds)) {
            $kehadiranRows = $this->flat
                ->where('kehadiran_id', $kehadiranID)
                ->where_in('guru_id', $guruIds)
                ->get('kehadiran')
                ->result();

            foreach ($kehadiranRows as $h) {
                $kehadiranIndexed[$h->guru_id] = $h;
            }
        }

        // ======================================================
        // 6. BUILD OUTPUT
        // ======================================================

        $data = [];

        foreach ($pagedData as $row) {

            $guru_id = $row['ptk_id'] ?? '';
            $hadir = $kehadiranIndexed[$guru_id] ?? null;

            $data[] = [
                'id' => $hadir->id ?? 0,
                'kehadiran_id' => $kehadiranID,
                'guru_id' => $guru_id,
                'nama' => $row['nama'] ?? '-',
                'bulan' => $hadir ? bulan($hadir->bulan) : '',
                'tahun' => $hadir->tahun ?? '',
                'hadir' => $hadir->kehadiran ?? 0,
                'nominal' => $hadir->nominal ?? 0,
                'ket' => $row['santri'] ?? '-',
            ];
        }

        // ======================================================
        // 7. RESPONSE
        // ======================================================

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'data' => $data,
                'total' => $totalTendik,
                'page' => $page,
                'perPage' => $perPage,
                'lastPage' => $lastPage,
            ]));
    }

    public function rincian_potong()
    {
        // ======================================================
        // 1. PARAMETER
        // ======================================================

        $satminkal_terpilih = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row('satminkal');

        $search   = $this->input->get('search') ?? '';
        $page     = max(1, (int) ($this->input->get('page') ?? 1));
        $perPage  = max(1, (int) ($this->input->get('perPage') ?? 10));
        $sortBy   = $this->input->get('sortBy') ?? 'nama';
        $sortDir  = strtoupper($this->input->get('sortDir') ?? 'ASC');
        $potongan_id  = $this->input->get('potongan_id') ?? '';

        // ======================================================
        // 2. AMBIL DATA DARI DATABASE
        // ======================================================

        $this->flat->select('
            g.guru_id as ptk_id,
            g.nama,
            g.kriteria,
            s.id as lembaga_id,
            s.nama as nama_lembaga,
            r.satminkal as ptk_induk
        ');

        $this->flat->from('guru g');
        $this->flat->join('registrasi r', 'r.id_guru = g.guru_id', 'left');
        $this->flat->join('satminkal s', 's.id = r.id_lembaga', 'left');

        // SEARCH
        if (!empty($search)) {
            $this->flat->like('g.nama', $search);
        }

        // SORT
        $allowedSort = ['nama', 'guru_id'];
        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'nama';
        }

        $this->flat->order_by('g.' . $sortBy, $sortDir);

        $query = $this->flat->get()->result_array();

        // ======================================================
        // BENTUKKAN SEPERTI FORMAT API
        // ======================================================

        $temp = [];

        foreach ($query as $q) {

            $ptk_id = $q['ptk_id'];

            // ✅ SET DATA UTAMA
            if (!isset($temp[$ptk_id])) {
                $temp[$ptk_id] = [
                    'ptk_id' => $ptk_id,
                    'nama' => $q['nama'],
                    'kriteria' => $q['kriteria'],
                    'registrasi_ptk' => [] // ⬅️ samakan dengan API lama
                ];
            }

            // ✅ MASUKKAN REGISTRASI
            if (!empty($q['lembaga_id'])) {
                $temp[$ptk_id]['registrasi_ptk'][] = [
                    'lembaga' => [
                        'lembaga_id' => $q['lembaga_id'],
                        'nama' => $q['nama_lembaga'] ?? '-'
                    ],
                    'ptk_induk' => (int) $q['ptk_induk']
                ];
            }
        }

        $rawData = array_values($temp);

        // ======================================================
        // 3. FILTER DATA (PTTY + SATMINKAL)
        // ======================================================

        $filtered = [];

        foreach ($rawData as $row) {

            $satminkal   = '-';
            $satminkalId = null;
            $match       = false;

            foreach ($row['registrasi_ptk'] ?? [] as $r) {

                $lembagaId = $r['lembaga']['lembaga_id'] ?? null;
                $ptkInduk  = (int) ($r['ptk_induk'] ?? 0);

                // ✅ WAJIB satminkal utama
                if ($ptkInduk !== 1) {
                    continue;
                }

                if ($satminkalId === null) {
                    $satminkal   = $r['lembaga']['nama'] ?? '-';
                    $satminkalId = $lembagaId;
                }

                if (!empty($satminkal_terpilih)) {
                    if ($lembagaId == $satminkal_terpilih) {
                        $match = true;
                        break;
                    }
                } else {
                    $match = true;
                    break;
                }
            }

            if (!$match) continue;

            $row['_satminkal']    = $satminkal;
            $row['_satminkal_id'] = $satminkalId;

            $filtered[] = $row;
        }


        // ======================================================
        // 4. PAGINATION SETELAH FILTER
        // ======================================================

        $totalTendik = count($filtered);
        $lastPage = $perPage > 0 ? ceil($totalTendik / $perPage) : 0;

        $offset = ($page - 1) * $perPage;
        $pagedData = array_slice($filtered, $offset, $perPage);

        // ======================================================
        // 5. AMBIL HONOR SEKALI SAJA
        // ======================================================

        $guruIds = array_column($pagedData, 'ptk_id');
        $kehadiranIndexed = [];

        if (!empty($guruIds)) {

            $this->flat->select('guru_id, bulan, tahun, SUM(nominal) as total');
            $this->flat->from('potongan');
            $this->flat->where_in('guru_id', $guruIds);
            $this->flat->where('potongan_id', $potongan_id);
            $this->flat->group_by(['guru_id', 'bulan', 'tahun']);

            $jml_potongan = $this->flat->get()->result();

            foreach ($jml_potongan as $h) {
                $kehadiranIndexed[$h->guru_id] = $h;
            }
        }

        // ======================================================
        // 6. BUILD OUTPUT
        // ======================================================

        $data = [];

        foreach ($pagedData as $row) {

            $guru_id = $row['ptk_id'] ?? '';
            $hadir = $kehadiranIndexed[$guru_id] ?? null;

            $data[] = [
                'id' => $hadir->id ?? 0,
                'potongan_id' => $potongan_id,
                'guru_id' => $guru_id,
                'nama' => $row['nama'] ?? '-',
                'bulan' => $hadir ? bulan($hadir->bulan) : '',
                'tahun' => $hadir->tahun ?? '',
                'satminkal' => $row['_satminkal'],
                'satminkal_id' => $row['_satminkal_id'],
                'nominal' => $hadir->total ?? 0,
            ];
        }

        // ======================================================
        // 7. RESPONSE
        // ======================================================

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'data' => $data,
                'total' => $totalTendik,
                'page' => $page,
                'perPage' => $perPage,
                'lastPage' => $lastPage,
            ]));
    }
}
