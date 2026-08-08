<?php

use SebastianBergmann\Environment\Console;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

defined('BASEPATH') or exit('No direct script access allowed');

class Kasir extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('datatables');
        $this->load->database();

        $this->load->model('KasirModel', 'model');
        $this->load->model('Auth_model');
        $this->load->model('AppModel', 'modelAll');

        $this->db2 = $this->load->database('dekos', true);
        $this->db3 = $this->load->database('sekretaris', true);
        $this->db4 = $this->load->database('santri', true);

        $user = $this->Auth_model->current_user();
        $this->tahun = $this->session->userdata('tahun');
        // $this->jenis = ['A. Belanja Barang', 'B. Langganan & Jasa', 'Belanja Kegiatan', 'D. Umum'];
        $this->bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $api = $this->model->apiKey()->row();
        $this->apiKey = $api->nama_key;
        $this->lembaga = $user->lembaga;
        $this->user = $user->nama;

        if ((!$this->Auth_model->current_user() && $user->level != 'kasir') || (!$this->Auth_model->current_user() && $user->level != 'admin')) {
            redirect('login/logout');
        }
    }

    public function index()
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;

        $data['pesantren'] = $this->model->getBySum('pesantren', 'tahun', $this->tahun, 'nominal')->row();
        $data['dekos'] = $this->model->getDekosSum($this->tahun)->row();
        $data['nikmus'] = $this->model->getNikmusSum($this->tahun)->row();

        $data['realSisa'] = $this->model->getBySum('real_sisa', 'tahun', $this->tahun, 'sisa')->row();

        $data['cadangan'] = $this->modelAll->cadangan($this->tahun);
        $data['masuk'] = $this->modelAll->masuk($this->tahun);
        $data['keluar'] = $this->modelAll->keluar($this->tahun);
        $data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();

        $data['saldo'] = $this->model->getBy2('saldo', 'name', 'bank', 'tahun', $data['tahun']);
        $data['cash'] = $this->model->getBy2('saldo', 'name', 'cash', 'tahun', $data['tahun']);

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/index', $data);
        $this->load->view('kasir/foot');
    }

    public function pengajuan()
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;

        $data['data'] = $this->model->getPengajuan($this->tahun)->result();
        // $data['lembaga'] = $this->model->getBy2('lembaga', 'kode'$this->tahun)->result();
        // $data['pj'] = $this->model->getPjn('pengajuan', $this->lembaga, $this->tahun)->row();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/pengajuan', $data);
        $this->load->view('kasir/foot');
    }

    public function cairProses($kode)
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;

        $data['pjn'] = $this->model->getBy('pengajuan', 'kode_pengajuan', $kode)->row();
        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $data['pjn']->lembaga, 'tahun', $this->tahun)->row();


        // $crr = $this->model->getBySum('pencairan', 'kode_pengajuan', $kode, 'nominal_cair')->row();
        $tblseselct = $data['pjn']->cair == 1 ? 'realis' : 'real_sm';
        $dt2 = $this->model->getBySum($tblseselct, 'kode_pengajuan', $kode, 'nominal')->row();

        $data['mitra'] = $this->model->getAll('mitra')->result();

        if ($data['pjn']->cair == 1) {
            $data['tbl_slct'] = 'realis';
            $sts_tmbl = 'disabled';
            $data['dcair'] = $dt2->jml;
            $data['dblm'] = 0;
        } else {
            $data['tbl_slct'] = 'real_sm';
            $sts_tmbl = '';
            $data['dcair'] = 0;
            $data['dblm'] = $dt2->jml;
        }

        $data['rls'] = $this->model->getBy2($tblseselct, 'kode_pengajuan', $kode, 'stas', 'tunai')->result();
        $data['rls2'] = $this->model->getBy2($tblseselct, 'kode_pengajuan', $kode, 'stas', 'non tunai')->result();
        foreach ($data['rls2'] as $key => $ls_jns) {
            $data['rls2'][$key]->pjnDataMitra = $this->model->getByJoin2('order_mitra', 'mitra', 'id_mitra', 'id_mitra', 'order_mitra.kode', $ls_jns->kode, 'order_mitra.kode_pengajuan', $ls_jns->kode_pengajuan)->row();
        }

        $dtMitras = [];
        $mitraDatas = $this->model->getByGroup('order_mitra', 'kode_pengajuan', $kode, 'id_mitra')->result();
        foreach ($mitraDatas as $key) {
            // $id_mitra = $key->id_mitra;
            // $data['isiMitra'][$id_mitra] = $this->model->getBy2('order_mitra', 'id_mitra', $id_mitra, 'kode_pengajuan', $kode)->num_rows();
            // $data['infoMitra'][$id_mitra] = $this->model->getBy('mitra', 'id_mitra', $id_mitra)->row();
            $dtMitras[] = [
                'mitra_info' => $this->model->getBy('mitra', 'id_mitra', $key->id_mitra)->row(),
                'mitra_jml' => $this->model->getBy2('order_mitra', 'id_mitra', $key->id_mitra, 'kode_pengajuan', $kode)->num_rows(),
            ];
        }
        $data['mitraHasil'] = $dtMitras;

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/cair', $data);
        $this->load->view('kasir/foot');
        // echo $crr->jml;
    }

    public function editSerap()
    {
        $id = $this->input->post('id', true);
        $kode_pengajuan = $this->input->post('kode_pengajuan', true);
        $serap = rmRp($this->input->post('serap', true));
        $nom_cair = rmRp($this->input->post('nom_cair', true));
        $table = $this->input->post('table', true);

        if ($serap > $nom_cair) {
            $this->session->set_flashdata('error', 'Maaf. Nominal terserapnyanya lebih dari disetujui');
            redirect('kasir/cairProses/' . $kode_pengajuan);
        } else {
            $this->model->update($table, ['nom_serap' => $serap], 'id_realis', $id);
            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('ok', 'Nominal serap berhasil diperbarui');
                redirect('kasir/cairProses/' . $kode_pengajuan);
            } else {
                $this->session->set_flashdata('error', 'Nominal serap tidak berhasil diperbarui');
                redirect('kasir/cairProses/' . $kode_pengajuan);
            }
        }
    }

    public function cairkan()
    {
        $id = $this->uuid->v4();

        $kd_pnj = $this->input->post('kode_pengajuan', true);
        $dataPj = $this->model->getBy('pengajuan', 'kode_pengajuan', $kd_pnj)->row();
        $jml = $this->db->query("SELECT SUM(nom_cair) as nom_cair, SUM(nom_serap) as nom_serap FROM real_sm WHERE kode_pengajuan = '$kd_pnj' ")->row();
        $dataReal = $this->model->getBy('real_sm', 'kode_pengajuan', $kd_pnj)->result();

        $lembaga =  $this->model->getBy2('lembaga', 'tahun', $this->tahun, 'kode', $dataPj->lembaga)->row();
        $tgl_cair = $this->input->post('tgl_cair', true);
        $kasir = $this->input->post('kasir', true);
        $penerima = $this->input->post('penerima', true);

        $history = [
            'kode_pengajuan' => $kd_pnj,
            'lembaga' => $dataPj->lembaga,
            'tgl_verval' => date('Y-m-d H:i:s'),
            'user' => $this->user,
            'stts' => 'pencairan',
            'tahun' => $this->tahun,
            'pesan' => 'Pengajuan dicairkan'
        ];
        $data2 = ['cair' => 1];
        $this->model->input('history', $history);
        // $this->model->input('pencairan', $data);
        $this->model->update('pengajuan', $data2, 'kode_pengajuan', $kd_pnj);

        foreach ($dataReal as $x) {
            $id_pnj = $x->id_realis;
            $dt = [
                'id_realis' => $id_pnj,
                'lembaga' => $x->lembaga,
                'bidang' => $x->bidang,
                'jenis' => $x->jenis,
                'kode' => $x->kode,
                'vol' => $x->vol,
                'nominal' => $x->nominal,
                'tgl' => $x->tgl,
                'pj' => $x->pj,
                'bulan' => $x->bulan,
                'tahun' => $x->tahun,
                'ket' => $x->ket,
                'kode_pengajuan' => $x->kode_pengajuan,
                'nom_cair' => $x->nom_cair,
                'nom_serap' => $x->nom_serap,
                'stas' => $x->stas,
                'harga' => $x->harga
            ];

            $this->model->input('realis', $dt);
            $this->model->delete('real_sm', 'id_realis', $id_pnj);
        }

        $psn = '💵 *[PENCAIRAN PENGAJUAN]*

Informasi pencairan dana pengajuan sebagai berikut:

━━━━━━━━━━━━━━━━━━━━
🏫 *Lembaga*     : ' . $lembaga->nama . '
🔖 *Kode Peng.*  : ' . $kd_pnj . '
📅 *Tanggal*     : ' . $tgl_cair . '
💰 *Nominal*     : ' . rupiah($jml->nom_serap) . '
👤 *Penerima*    : ' . $penerima . '
━━━━━━━━━━━━━━━━━━━━

_*Dana telah dicairkan oleh Bendahara Bag. Admin Pencairan._*

Terima kasih.';

        if ($this->db->affected_rows() > 0) {
            // kirim_group($this->apiKey, '120363040973404347@g.us', $psn);
            // kirim_group($this->apiKey, '120363042148360147@g.us', $psn);

            kirim_person($this->apiKey, '085236924510', $psn);

            $this->session->set_flashdata('ok', 'Pengajuan sudah dicairkan');
            redirect('kasir/cairProses/' . $kd_pnj);
        } else {
            $this->session->set_flashdata('error', 'Pengajuan tidak bisa dicairkan');
            redirect('kasir/cairProses/' . $kd_pnj);
        }
    }

    public function pengajuanDisp()
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;

        $data['data'] = $this->model->getPengajuanDisp($this->tahun)->result();
        // $data['lembaga'] = $this->model->getBy2('lembaga', 'kode'$this->tahun)->result();
        // $data['pj'] = $this->model->getPjn('pengajuan', $this->lembaga, $this->tahun)->row();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/pengajuan', $data);
        $this->load->view('kasir/foot');
    }

    public function tanggungan_new()
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;
        $bulanIni = date('m');

        $data['data'] = $this->model->getByJoin2('tanggungan', 'tb_santri', 'nis', 'nis', 'tanggungan.tahun', $this->tahun, 'tanggungan.bulan', date('m'))->result();
        $data['jmltagihan'] = $this->db->query("SELECT COUNT(*) as total FROM tanggungan WHERE tahun = '$this->tahun' AND bulan = $bulanIni ")->row();
        $data['jmlbayar'] = $this->db->query("SELECT COUNT(*) as total FROM pembayaran WHERE tahun = '$this->tahun' AND bulan = $bulanIni ")->row();

        $tanggungan_bulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $tanggungan = $this->db->query("SELECT bulan, COUNT(*) AS jml FROM tanggungan WHERE tahun = '$this->tahun' AND bulan = $i")->row();
            $pembayaran = $this->db->query("SELECT COUNT(*) AS jml FROM pembayaran WHERE tahun = '$this->tahun' AND bulan = $i")->row();
            $tanggungan_bulan[] = array(
                'bulan' => $i,
                'jmltanggungan' => $tanggungan->jml,
                'jmlbayar' => $pembayaran->jml
            );
        }
        $data['tanggungan_bulan'] = $tanggungan_bulan;

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/tanggungan', $data);
        $this->load->view('kasir/foot');
    }

    public function tanggunganBulan($bulanIni)
    {
        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $search_value = isset($this->input->post('search')['value']) ? $this->input->post('search')['value'] : '';

        $length = $length > 0 ? $length : 10;
        $start = $start >= 0 ? $start : 0;
        // $bulanIni = date('m');
        $this->db->select('tanggungan.*, tb_santri.nama');
        $this->db->from('tanggungan');
        $this->db->join('tb_santri', 'tanggungan.nis=tb_santri.nis');
        $this->db->where('tanggungan.bulan', $bulanIni);

        // Filter search
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('tanggungan.nis', $search_value);
            $this->db->or_like('tb_santri.nama', $search_value);
            $this->db->or_like('tanggungan.nominal', $search_value);
            $this->db->group_end();
        }

        $total_records = $this->db->count_all_results('', false); // Count total records without limit

        $this->db->limit($length, $start);
        $query = $this->db->get();
        $data = [];
        $row_number = $start + 1;

        foreach ($query->result() as $row) {
            $cekBayar = $this->model->getBy3('pembayaran', 'nis', $row->nis, 'bulan', $row->bulan, 'tahun', $row->tahun);
            $data[] = [
                $row_number++,
                $row->id_tanggungan,
                $row->nis,
                $row->nama,
                $row->nominal,
                $row->bulan,
                $row->tahun,
                $cekBayar->num_rows()
            ];
        }

        $output = [
            "draw" => $draw,
            "recordsTotal" => $total_records,
            "recordsFiltered" => $total_records,
            "data" => $data
        ];

        // Set content-type header and return JSON data
        header('Content-Type: application/json');
        echo json_encode($output);
        // var_dump($output);
    }
    public function tanggungan()
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;

        $data['data'] = $this->db->select('tb_santri.nama, tb_santri.nis, tanggungan.briva, SUM(tanggungan.nominal) AS total, tanggungan.tahun, tanggungan.nis AS id_tangg')
            ->from('tanggungan')
            ->join('tb_santri', 'tanggungan.nis = tb_santri.nis')
            ->where('tanggungan.tahun', $this->tahun)
            ->group_by(['tanggungan.nis', 'tb_santri.nama', 'tanggungan.briva', 'tanggungan.tahun'])
            ->get()
            ->result();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/tanggungan', $data);
        $this->load->view('kasir/foot');
    }

    public function santri_ajax()
    {
        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $search_value = isset($this->input->post('search')['value']) ? $this->input->post('search')['value'] : '';

        $length = $length > 0 ? $length : 10;
        $start = $start >= 0 ? $start : 0;

        $this->db->select('nis, nama, k_formal, t_formal, k_madin, r_madin');
        $this->db->from('tb_santri');
        $this->db->where('aktif', 'Y');

        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('nis', $search_value);
            $this->db->or_like('nama', $search_value);
            $this->db->or_like('t_formal', $search_value);
            $this->db->group_end();
        }

        $total_records = $this->db->count_all_results('', false);

        $this->db->limit($length, $start);
        $query = $this->db->get();
        $data = [];

        foreach ($query->result() as $row) {
            $data[] = [
                'nis' => $row->nis,
                'nama' => $row->nama,
                'kelas' => $row->k_formal . ' ' . $row->t_formal,
                'madin' => $row->k_madin . ' ' . $row->r_madin,
                'action' => '<a href="' . base_url('kasir/discrb/' . $row->nis) . '" class="btn btn-primary btn-sm">Pilih</a>'
            ];
        }

        $output = [
            "draw" => $draw,
            "recordsTotal" => $total_records,
            "recordsFiltered" => $total_records,
            "data" => $data
        ];

        header('Content-Type: application/json');
        echo json_encode($output);
    }

    public function delTanggungan($nis)
    {
        $this->db->where('nis', $nis);
        $this->db->where('tahun', $this->tahun);
        $this->db->delete('tanggungan');

        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Tanggungan berhasil dihapus');
            redirect('kasir/tanggungan');
        } else {
            $this->session->set_flashdata('error', 'Tanggungan gagal dihapus');
            redirect('kasir/tanggungan');
        }
    }

    public function discrb_new($nis)
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;

        $data['sn'] = $this->model->getBy('tb_santri', 'nis', $nis)->row();
        $data['tgn'] = $this->db->query("SELECT * FROM tanggungan WHERE nis = $nis AND tahun = '$this->tahun' ORDER BY bulan ASC")->result();
        $data['masuk'] = $this->db->query("SELECT SUM(nominal) AS jml FROM pembayaran WHERE nis = '$nis' AND tahun = '$this->tahun' GROUP BY nis ")->row();
        $data['tanggungan'] = $this->db->query("SELECT SUM(nominal) AS jml FROM tanggungan WHERE nis = '$nis' AND tahun = '$this->tahun' GROUP BY nis ")->row();
        $data['bayar'] = $this->model->getBy2('pembayaran', 'nis', $nis, 'tahun', $this->tahun)->result();

        $data['tmpKos'] = array("", "Ny. Jamilah", "Gus Zaini", "Ny. Farihah", "Ny. Zahro", "Ny. Sa'adah", "Ny. Mamjudah", "Ny. Naily Z.", "Ny. Lathifah", "Ny. Ummi Kultsum", "K. Abdul Mukti");
        $data['kter'] = ["Bayar", "Ust/Usdtz", "Khaddam", "Gratis", "Berhenti"];


        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/discrb-new', $data);
        $this->load->view('kasir/foot');
    }

    public function discrb($nis)
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;

        $data['sn'] = $this->model->getBy('tb_santri', 'nis', $nis)->row();

        // Get total tanggungan sum for this student
        $tgn_row = $this->db->select('SUM(nominal) AS total')
            ->from('tanggungan')
            ->where('nis', $nis)
            ->where('tahun', $this->tahun)
            ->get()
            ->row();
        if (!$tgn_row) {
            $tgn_row = (object)['total' => 0];
        }
        $data['tgn'] = $tgn_row;

        // Get total pembayaran sum for this student
        $masuk_row = $this->db->select('SUM(nominal) AS jml')
            ->from('pembayaran')
            ->where('nis', $nis)
            ->where('tahun', $this->tahun)
            ->get()
            ->row();
        if (!$masuk_row) {
            $masuk_row = (object)['jml' => 0];
        }
        if (empty($masuk_row->jml)) {
            $masuk_row->jml = 0;
        }
        $data['masuk'] = $masuk_row;

        $data['bayar'] = $this->model->getBy2('pembayaran', 'nis', $nis, 'tahun', $this->tahun)->result();

        // Get monthly breakdown map from tanggungan
        $tanggungan_list = $this->db->get_where('tanggungan', [
            'nis' => $nis,
            'tahun' => $this->tahun
        ])->result();

        $tanggungan_map = [];
        foreach ($tanggungan_list as $t) {
            $tanggungan_map[$t->bulan] = $t->nominal;
        }
        $data['months_map'] = $tanggungan_map;

        $data['tmpKos'] = array("", "Ny. Jamilah", "Gus Zaini", "Ny. Farihah", "Ny. Zahro", "Ny. Sa'adah", "Ny. Mamjudah", "Ny. Naily Z.", "Ny. Lathifah", "Ny. Ummi Kultsum", "K. Abdul Mukti");
        $data['kter'] = ["Bayar", "Ust/Usdtz", "Khaddam", "Gratis", "Berhenti"];

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/discrb', $data);
        $this->load->view('kasir/foot');
    }

    public function addbayar()
    {
        $user = $this->Auth_model->current_user();

        $nominal = rmRp($this->input->post('nominal', true));
        $tgl = $this->input->post('tgl', true);
        $kasir = $user ? $user->nama : 'Testing';
        $nama = $this->input->post('nama', true);
        $nis = $this->input->post('nis', true);
        $tahun = $this->tahun;
        $bulan_bayar = $this->input->post('bulan', true);

        $dp = $this->model->getBy('tb_santri', 'nis', $nis)->row();
        $dpBr = $this->db->get_where('tanggungan', ['nis' => $nis, 'tahun' => $this->tahun])->row();
        $briva_code = $dpBr ? $dpBr->briva : '-';

        $alm = $dp ? ($dp->desa . '-' . $dp->kec . '-' . $dp->kab) : '-';
        $hpNo = $dp ? $dp->hp : '';
        $hpNo2 = '085236924510';

        $data = [
            'nis' => $nis,
            'nama' => $nama,
            'tgl' => $tgl,
            'nominal' => $nominal,
            'bulan' => $bulan_bayar,
            'tahun' => $tahun,
            'kasir' => $kasir,
        ];

        $pesan = '
*KWITANSI PEMBAYARAN ELEKTRONIK*
*PP DARUL LUGHAH WAL KAROMAH*
Bendahara Pondok Pesantren Darul Lughah Wal Karomah telah menerima pembayaran BP dari wali santri berikut :
    
No. BRIVA : *' . $briva_code . '*
Nama : *' . $nama . '*
Alamat : *' . $alm . '* 
Nominal Pembayaran: *' . rupiah($nominal) . '*
Tanggal Bayar : *' . $tgl . '*
Pembayaran Untuk: *BP (Biaya Pendidikan) bulan ' . $this->bulan[$bulan_bayar] . '*
Penerima: *' . $kasir . '*

Bukti Penerimaan ini *DISIMPAN* oleh wali santri sebagai bukti pembayaran Biaya Pendidikan PP Darul Lughah Wal Karomah Tahun Pelajaran ' . $tahun . '.
*Hal – hal yang berkaitan dengan Teknis keuangan dapat menghubungi Contact Person Bendahara berikut :*
*https://wa.me/6282329641926*

Terimakasih';

        // Check student billing for this specific month
        $tanggungan_row = $this->db->get_where('tanggungan', [
            'nis' => $nis,
            'bulan' => $bulan_bayar,
            'tahun' => $tahun
        ])->row();
        $billing_amount = $tanggungan_row ? floatval($tanggungan_row->nominal) : 0;

        // Check total payment amount already paid for this month
        $paid_row = $this->db->select('SUM(nominal) AS total_paid')
            ->from('pembayaran')
            ->where([
                'nis' => $nis,
                'bulan' => $bulan_bayar,
                'tahun' => $tahun
            ])
            ->get()
            ->row();
        $already_paid = $paid_row ? floatval($paid_row->total_paid) : 0;

        $max_allowed = $billing_amount - $already_paid;

        if ($nominal > $max_allowed) {
            $this->session->set_flashdata('error', 'Maaf pembayaran melebihi! Sisa tanggungan bulan ini adalah ' . rupiah($max_allowed));
            redirect('kasir/discrb/' . $nis);
        } else {
            $this->model->input('pembayaran', $data);

            if ($this->db->affected_rows() > 0) {
                // kirim_person($this->apiKey, $hpNo, $pesan);
                // kirim_person($this->apiKey, $hpNo2, $pesan);
                $this->session->set_flashdata('ok', 'Tanggungan berhasil diinput');
                redirect('kasir/discrb/' . $nis);
            } else {
                $this->session->set_flashdata('error', 'Tanggungan tidak berhasil diinput');
                redirect('kasir/discrb/' . $nis);
            }
        }
    }

    public function addbayar_new()
    {
        $user = $this->Auth_model->current_user();

        $nominal = rmRp($this->input->post('nominal', true));
        $tgl = $this->input->post('tgl', true);
        $kasir = $user->nama;
        $nama = $this->input->post('nama', true);
        $nis = $this->input->post('nis', true);
        $tahun = $this->tahun;
        $dekos = $this->input->post('dekos', true);
        $bulan_bayar = $this->input->post('bulan', true);

        $dp = $this->model->getBy('tb_santri', 'nis', $nis)->row();
        $dpBr = $this->model->getBy3('tanggungan', 'nis', $nis, 'tahun', $this->tahun, 'bulan', $bulan_bayar)->row();

        $alm = $dp->desa . '-' . $dp->kec . '-' . $dp->kab;
        $hpNo = '089682351413';
        $hpNo2 = '085236924510';

        $data = [
            'nis' => $nis,
            'nama' => $nama,
            'tgl' => $tgl,
            'nominal' => $nominal,
            'bulan' => $bulan_bayar,
            'tahun' => $tahun,
            'kasir' => $kasir,
        ];
        $data2 = [
            'nis' => $nis,
            'nominal' => 300000,
            'bulan' => $bulan_bayar,
            'tahun' => $tahun,
            'tgl' => $tgl,
            'penerima' => $kasir,
            'stts' => 1,
            'waktu' => date('Y-m-d H:i'),
        ];

        $pesan = '
*KWITANSI PEMBAYARAN ELEKTRONIK*
*PP DARUL LUGHAH WAL KAROMAH*
Bendahara Pondok Pesantren Darul Lughah Wal Karomah telah menerima pembayaran BP dari wali santri berikut :
    
No. BRIVA : *' . $dpBr->briva . '*
Nama : *' . $nama . '*
Alamat : *' . $alm . '* 
Nominal Pembayaran: *' . rupiah($nominal) . '*
Tanggal Bayar : *' . $tgl . '*
Pembayaran Untuk: *BP (Biaya Pendidikan) bulan ' . $this->bulan[$bulan_bayar] . '*
Penerima: *' . $kasir . '*

Bukti Penerimaan ini *DISIMPAN* oleh wali santri sebagai bukti pembayaran Biaya Pendidikan PP Darul Lughah Wal Karomah Tahun Pelajaran ' . $tahun . '.
*Hal – hal yang berkaitan dengan Teknis keuangan dapat menghubungi Contact Person Bendahara berikut :*
*https://wa.me/6282329641926*

Terimakasih';


        $cek = $this->db->query("SELECT * FROM pembayaran WHERE nis = '$nis' AND bulan = '$bulan_bayar' AND tahun = '$tahun' ")->num_rows();
        if ($cek < 1) {
            if ($dekos == 'Y') {
                $this->model->inputDb2('kos', $data2);
                $this->model->input('pembayaran', $data);

                if ($this->db->affected_rows() > 0) {
                    // kirim_person($this->apiKey, $hpNo, $pesan);
                    // kirim_person($this->apiKey, $hpNo2, $pesan);
                    $this->session->set_flashdata('ok', 'Tanggungan berhasil diinput');
                    redirect('kasir/discrb/' . $nis);
                } else {
                    $this->session->set_flashdata('error', 'Tanggungan tidak berhasil diinput');
                    redirect('kasir/discrb/' . $nis);
                }
            } else {
                $this->model->input('pembayaran', $data);

                if ($this->db->affected_rows() > 0) {
                    // kirim_person($this->apiKey, $hpNo, $pesan);
                    // kirim_person($this->apiKey, $hpNo2, $pesan);
                    $this->session->set_flashdata('ok', 'Tanggungan berhasil diinput');
                    redirect('kasir/discrb/' . $nis);
                } else {
                    $this->session->set_flashdata('error', 'Tanggungan tidak berhasil diinput');
                    redirect('kasir/discrb/' . $nis);
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Maaf pembayaran bulan ini sudah ada');
            redirect('kasir/discrb/' . $nis);
        }
    }

    public function delBayar($id)
    {
        $data = $this->model->getBy('pembayaran', 'id', $id)->row();

        // $sql = mysqli_query($conn, "DELETE FROM pembayaran WHERE id = '$id' AND tahun = '$tahun_ajaran' ");
        // $sql2 = mysqli_query($conn_dekos, "DELETE FROM kos WHERE nis = '$nis' AND bulan = '$buln' AND tahun = '$tahun' ");

        $this->model->deleteBayar($data->nis, $data->bulan, $data->tahun);
        $this->model->delete('pembayaran', 'id', $id);

        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Tanggungan berhasil dihapus');
            redirect('kasir/discrb/' . $data->nis);
        } else {
            $this->session->set_flashdata('error', 'Tanggungan tidak berhasil dihapus');
            redirect('kasir/discrb/' . $data->nis);
        }
    }

    public function editBayar($id)
    {
        $data['data'] = $this->model->getBy('pembayaran', 'id', $id)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;
        $nis = $data['data']->nis;

        $data['sn'] = $this->model->getBy('tb_santri', 'nis', $nis)->row();
        $data['tmpKos'] = array("", "Ny. Jamilah", "Gus Zaini", "Ny. Farihah", "Ny. Zahro", "Ny. Sa'adah", "Ny. Mamjudah", "Ny. Naily Z.", "Ny. Lathifah", "Ny. Ummi Kultsum", "K. Abdul Mukti");
        $data['kter'] = ["Bayar", "Ust/Usdtz", "Khaddam", "Gratis", "Berhenti"];

        $data['tgn'] = $this->model->getBy2('tangg', 'nis', $nis, 'tahun', $this->tahun)->row();
        $data['masuk'] = $this->db->query("SELECT SUM(nominal) AS jml FROM pembayaran WHERE nis = '$nis' AND tahun = '$this->tahun' GROUP BY nis ")->row();
        $data['bayar'] = $this->model->getBy2('pembayaran', 'nis', $nis, 'tahun', $this->tahun)->result();



        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/editBayar', $data);
        $this->load->view('kasir/foot');
    }

    public function saveEditBayar()
    {

        $id = $this->input->post('id', true);
        $nis = $this->input->post('nis', true);
        $data = [
            'nominal' => rmRp($this->input->post('nominal', true)),
            'tgl' => $this->input->post('tgl', true),
            'bulan' => $this->input->post('bulan', true),
        ];

        $this->model->update('pembayaran', $data, 'id', $id);
        if ($this->db->affected_rows() > 0) {

            $this->session->set_flashdata('ok', 'Pembayaran berhasil update');
            redirect('kasir/discrb/' . $nis);
        } else {
            $this->session->set_flashdata('error', 'Pembayaran tidak berhasil update');
            redirect('kasir/discrb/' . $nis);
        }
    }

    public function bayar()
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;

        $data['rls'] = $this->model->getBayarAll()->result();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/bayar', $data);
        $this->load->view('kasir/foot');
    }

    public function mutasi()
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;

        $data['data'] = $this->model->getMutasi()->result();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/mutasi', $data);
        $this->load->view('kasir/foot');
    }

    public function mutasiDtl($nis)
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;

        $data['sn'] = $this->model->getBy('tb_santri', 'nis', $nis)->row();
        $data['tgn'] = $this->model->getBy2('tangg', 'nis', $nis, 'tahun', $this->tahun)->row();
        $data['masuk'] = $this->db->query("SELECT SUM(nominal) AS jml FROM pembayaran WHERE nis = '$nis' AND tahun = '$this->tahun' GROUP BY nis ")->row();
        $data['bayar'] = $this->model->getBy2('pembayaran', 'nis', $nis, 'tahun', $this->tahun)->result();

        $data['mts'] = $this->model->getByDb3('mutasi', 'nis', $nis)->row();
        $data['rc_byar'] = $this->model->getBy2('tangg', 'nis', $nis, 'tahun', $this->tahun)->row();

        if (date('m', strtotime($data['mts']->tgl_mutasi)) == 6) {
            $data['tgbyr'] = $data['rc_byar']->me_ju;
            $data['dekos'] = 0;
        } else {
            if ($data['rc_byar']->me_ju == $data['rc_byar']->ju_ap) {
                $data['dekos'] = 0;
            } else {
                $data['dekos'] = 300000;
            }
            $data['tgbyr'] = $data['rc_byar']->ju_ap - $data['dekos'];
        }

        $data['tglbr'] = date('d', strtotime($data['mts']->tgl_mutasi));

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/mutasi_dtl', $data);
        $this->load->view('kasir/foot');
    }

    public function bayarMutasi()
    {
        $user = $this->Auth_model->current_user();

        $nominal = rmRp($this->input->post('nominal_bp', true));
        $tgl = $this->input->post('tgl', true);
        $kasir = $user->nama;
        $nama = $this->input->post('nama', true);
        $nis = $this->input->post('nis', true);
        $tahun = $this->tahun;
        $dekos = rmRp($this->input->post('nominal_dks', true));
        $bulan_bayar = $this->input->post('bulan', true);

        $dp = $this->model->getBy('tb_santri', 'nis', $nis)->row();
        $dpBr = $this->model->getBy2('tangg', 'nis', $nis, 'tahun', $this->tahun)->row();

        $by = $nominal + $this->input->post('masuk', true);
        $ttl = $this->input->post('ttl', true);
        $alm = $dp->desa . '-' . $dp->kec . '-' . $dp->kab;
        // $hpNo = $dp->hp;
        $hpNo = '085236924510';

        $data = [
            'nis' => $nis,
            'nama' => $nama,
            'tgl' => $tgl,
            'nominal' => $nominal,
            'bulan' => $bulan_bayar,
            'tahun' => $tahun,
            'kasir' => $kasir,
        ];
        $data2 = [
            'nis' => $nis,
            'nominal' => $dekos,
            'bulan' => $bulan_bayar,
            'tahun' => $tahun,
            'tgl' => $tgl,
            'penerima' => $kasir,
            'stts' => 1,
            'waktu' => date('Y-m-d H:i'),
        ];

        $pesan = '
*KWITANSI PEMBAYARAN ELEKTRONIK*
*PP DARUL LUGHAH WAL KAROMAH*
Bendahara Pondok Pesantren Darul Lughah Wal Karomah telah menerima pembayaran BP dari wali santri berikut :
    
No. BRIVA : *' . $dpBr->briva . '*
Nama : *' . $nama . '*
Alamat : *' . $alm . '* 
Nominal Pembayaran: *' . rupiah($nominal) . '*
Tanggal Bayar : *' . $tgl . '*
Pembayaran Untuk: *BP (Biaya Pendidikan) bulan ' . $this->bulan[$bulan_bayar] . '*
Penerima: *' . $kasir . '*

Bukti Penerimaan ini *DISIMPAN* oleh wali santri sebagai bukti pembayaran Biaya Pendidikan PP Darul Lughah Wal Karomah Tahun Pelajaran ' . $tahun . '.
*Hal – hal yang berkaitan dengan Teknis keuangan dapat menghubungi Contact Person Bendahara berikut :*
*https://wa.me/6287757777273*
*https://wa.me/6285235583647*

Terimakasih';

        if ($by > $ttl) {
            $this->session->set_flashdata('error', 'Maaf pembayaran melebihi');
            redirect('kasir/mutasiDtl/' . $nis);
        } else {
            $cek = $this->db->query("SELECT * FROM pembayaran WHERE nis = '$nis' AND bulan = '$bulan_bayar' AND tahun = '$tahun' ")->num_rows();
            if ($cek < 1) {

                $this->model->inputDb2('kos', $data2);
                $this->model->input('pembayaran', $data);

                if ($this->db->affected_rows() > 0) {
                    // kirim_person($this->apiKey, $hpNo, $pesan);
                    $this->session->set_flashdata('ok', 'Tanggungan berhasil diinput');
                    redirect('kasir/mutasiDtl/' . $nis);
                } else {
                    $this->session->set_flashdata('error', 'Tanggungan tidak berhasil diinput');
                    redirect('kasir/mutasiDtl/' . $nis);
                }
            } else {
                $this->session->set_flashdata('error', 'Maaf pembayaran ini bulan ini sudah ada');
                redirect('kasir/mutasiDtl/' . $nis);
            }
        }
    }

    public function vervalMutasi($id)
    {
        $mutasi = $this->model->getByDb3('mutasi', 'id_mutasi', $id)->row();
        $dts = $this->model->getBy('tb_santri', 'nis', $mutasi->nis)->row();

        // $sql = mysqli_query($conn_santri, "UPDATE mutasi SET status = 1 WHERE id_mutasi = '$id_mutasi' ");
        // $sql2 = mysqli_query($conn_sekretaris, "UPDATE mutasi SET status = 1 WHERE id_mutasi = '$id_mutasi' ");

        $this->model->updateDb3('mutasi', ['status' => 1], 'nis', $mutasi->nis);
        $this->model->updateDb4('mutasi', ['status' => 1], 'nis', $mutasi->nis);
        $hpNo = '085236924510';
        $psn = '✅ *[VERIFIKASI MUTASI - SURAT BERHENTI]*

Informasi verifikasi mutasi santri sebagai berikut:

━━━━━━━━━━━━━━━━━━━━
👤 *Nama*       : ' . $dts->nama . '
📍 *Alamat*     : ' . $dts->desa . ', ' . $dts->kec . ', ' . $dts->kab . '
🏫 *Sekolah*    : ' . $dts->k_formal . ' ' . $dts->t_formal . '
📅 *Tgl Mutasi* : ' . $mutasi->tgl_mutasi . '
━━━━━━━━━━━━━━━━━━━━

_*Telah diverifikasi oleh Bendahara Pesantren. Surat mutasi selanjutnya dapat diterbitkan oleh Sekretariat._*

Terima kasih.';

        if ($this->db->affected_rows() > 0) {
            kirim_group($this->apiKey, '120363028015516743@g.us', $psn);
            // // kirim_person($this->apiKey, $hpNo, $psn);
            $this->session->set_flashdata('ok', 'Mutasi berhasil diverval');
            redirect('kasir/mutasiDtl/' . $mutasi->nis);
        } else {
            $this->session->set_flashdata('error', 'Mutasi gagal diverval');
            redirect('kasir/mutasiDtl/' . $mutasi->nis);
        }
    }

    public function info()
    {
        $data['data'] = $this->model->getBy('info', 'tahun', $this->tahun)->result();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/info', $data);
        $this->load->view('kasir/foot');
    }

    public function setting()
    {

        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/setting', $data);
        $this->load->view('kasir/foot');
    }

    public function updateAkun()
    {
        $id = $this->Auth_model->current_user('id_user');
        $id_user = $id->id_user;

        $nama = $this->input->post('nama', true);
        $username = $this->input->post('username', true);
        $password = $this->input->post('newpass', true);
        $password2 = $this->input->post('confir_newpass', true);
        $pass_lama = $this->input->post('pass_lama', true);
        $pass_baru = password_hash($password, PASSWORD_DEFAULT);

        if ($password == '' && $password2 = '') {

            $data = [
                'nama' => strtoupper($nama),
                'username' => $username
            ];
            $this->model->update('user', $data, 'id_user', $id_user);
            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('ok', 'User akun berhasil diperbarui');
                redirect('kasir/setting');
            } else {
                $this->session->set_flashdata('error', 'User akun tidak berhasil diperbarui');
                redirect('kasir/setting');
            }
        } else {
            if ($password != $password2) {
                $this->session->set_flashdata('error', 'Konfimasi password tidak sama');
                redirect('kasir/setting');
            } else {

                $data = [
                    'nama' => $nama,
                    'username' => $username,
                    'password' => $pass_baru
                ];
                $this->model->update('user', $data, 'id_user', $id_user);
                if ($this->db->affected_rows() > 0) {
                    $this->session->set_flashdata('ok', 'User akun berhasil diperbarui');
                    redirect('kasir/setting');
                } else {
                    $this->session->set_flashdata('error', 'User akun tidak berhasil diperbarui');
                    redirect('kasir/setting');
                }
            }
        }
    }

    public function updateLembaga()
    {
        $id_lm = $this->lembaga;
        $tahun = $this->tahun;

        $data = [
            'pj' => $this->input->post('pj', true),
            'hp' => $this->input->post('hp', true),
            'hp_kep' => $this->input->post('hp_kep', true),
            'waktu' => $this->input->post('waktu', true)
        ];

        $this->model->update2('lembaga', $data, 'kode', $id_lm, 'tahun', $tahun);

        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'User akun berhasil diperbarui');
            redirect('kasir/setting');
        } else {
            $this->session->set_flashdata('error', 'User akun tidak berhasil diperbarui');
            redirect('kasir/setting');
        }
    }

    public function uploadFoto()
    {

        $user = $this->Auth_model->current_user();

        $file_name = 'PROFILE-' . rand(0, 99999999);
        $config['upload_path']          = FCPATH . '/vertical/assets/uploads/profile/';
        $config['allowed_types']        = 'jpg|jpeg|png';
        $config['file_name']            = $file_name;
        $config['overwrite']            = true;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file')) {
            $data['error'] = $this->upload->display_errors();
        } else {
            $uploaded_data = $this->upload->data();

            $new_data = [
                'foto' =>  $uploaded_data['file_name']
            ];
            $this->model->update('user', $new_data, 'id_user', $user->id_user);
            // unlink('./vertical/assets/uploads/honor/' . $file->files);

            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('ok', 'Upload foto sukses');
                redirect('kasir/setting');
            } else {
                $this->session->set_flashdata('error', 'Upload foto sukses');
                redirect('kasir/setting');
            }
        }
    }

    public function rekap()
    {

        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;
        $data['hasil'] = $this->model->getByrGroup($this->tahun)->result();
        $data['total'] = $this->model->getBySum('pembayaran', 'tahun', $this->tahun, 'nominal')->row();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/rekap', $data);
        $this->load->view('kasir/foot');
    }

    public function dispen()
    {
        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;
        $data['data'] = $this->model->getByJoin('dispensasi', 'tb_santri', 'nis', 'nis', 'dispensasi.tahun', $this->tahun)->result();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/dispen', $data);
        $this->load->view('kasir/foot');
    }

    public function dispenAdd()
    {
        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;
        $data['santri'] = $this->model->getBy('tb_santri', 'aktif', 'Y')->result();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/dispenAdd', $data);
        $this->load->view('kasir/foot');
    }

    public function saveDispen()
    {
        $nis = $this->input->post('nis', true);
        $sandal = rmRp($this->input->post('sandal', true));
        $lomba = rmRp($this->input->post('lomba', true));
        $wilayah = rmRp($this->input->post('wilayah', true));

        $cek = $this->db->query("SELECT * FROM dispensasi WHERE nis = '$nis' ")->num_rows();
        $tangg = $this->db->query("SELECT ((ju_ap * 8) + (me_ju * 2)) AS tgnApr FROM tangg WHERE nis = '$nis' AND tahun = '$this->tahun' ")->row();
        $masuk = $this->db->query("SELECT SUM(nominal) AS byr FROM pembayaran WHERE nis = '$nis' AND tahun = '$this->tahun' ")->row();
        $bp = $tangg->tgnApr < $masuk->byr ? 0 : $tangg->tgnApr - $masuk->byr;

        $data = [
            'id_dispensasi' => $this->uuid->v4(),
            'nis' => $nis,
            'bp' => $bp,
            'sandal' => $sandal,
            'lomba' => $lomba,
            'wilayah' => $wilayah,
            'tahun' => $this->tahun,
        ];

        if ($cek > 0) {
            $this->session->set_flashdata('error', 'Maaf data sudah ada');
            redirect('kasir/dispenAdd');
        } else {
            $this->model->input('dispensasi', $data);

            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('ok', 'Data berhasil diinput');
                redirect('kasir/dispen');
            } else {
                $this->session->set_flashdata('error', 'Data tidak berhasil diinput');
                redirect('kasir/dispen');
            }
        }
    }

    public function delDispen($id)
    {
        $this->model->delete('dispensasi', 'id_dispensasi', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Data berhasil dihapus');
            redirect('kasir/dispen');
        } else {
            $this->session->set_flashdata('error', 'Data tidak berhasil dihapus');
            redirect('kasir/dispen');
        }
    }

    public function cetakDispen($nis)
    {
        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;
        $data['santri'] = $this->model->getBy2('tb_santri', 'nis', $nis, 'aktif', 'Y')->row();
        $data['dispn'] = $this->model->getBy2('dispensasi', 'nis', $nis, 'tahun', $this->tahun)->row();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/cetakDispen', $data);
        $this->load->view('kasir/foot');
    }

    public function printDispen()
    {
        $nis = $this->input->post('nis', true);
        $data['santri'] = $this->model->getBy2('tb_santri', 'nis', $nis, 'aktif', 'Y')->row();
        $data['dispn'] = $this->model->getBy2('dispensasi', 'nis', $nis, 'tahun', $this->tahun)->row();

        $data['bp'] = rmRp($this->input->post('bp', true));
        $data['bayar'] = rmRp($this->input->post('bayar', true));
        $data['janji'] = $this->input->post('janji', true);

        $datas = [
            'bayar' => $data['bayar'],
            'tgl_bayar' => date('Y-m-d'),
            'janji' => $data['janji'],
        ];

        $this->model->update('dispensasi', $datas, 'nis', $nis);

        if ($this->db->affected_rows() > 0) {
            $this->load->view('kasir/printDispen', $data);
        } else {
            $this->session->set_flashdata('error', 'Data tidak berhasil disipan');
            redirect('kasir/dispen');
        }
    }

    public function ifoDispen($nis)
    {
        $santri = $this->model->getBy('tb_santri', 'nis', $nis)->row();
        $dspn = $this->model->getBy('dispensasi', 'nis', $nis)->row();
        $tgn = $dspn->bp + $dspn->sandal + $dspn->wilayah + $dspn->lomba;
        $bln = $this->bulan;

        $pesan = '*Notifikasi Tagihan Perjanjian Pembayaran*
Yth *' . $santri->nama . '*, 
Dengan ini kami sampaikan Anda memiliki Tagihan Perjanjian Pembayaran di PP DARUL LUGHAH WAL KAROMAH sebesar *' . rupiah($tgn - $dspn->bayar) . '* 
dengan perjanjian pelunasan pada *Bulan ' . $bln[$dspn->janji] . '*

Terima kasih. 

Bendahara Pesantren 

_Jika sudah melakukan pelunasan abaikan pesan ini_';

        // kirim_person($this->apiKey, $santri->hp, $pesan);
    }

    public function addOrderMitra()
    {
        $id_mitra = $this->input->post('id_mitra', true);
        $kode = $this->input->post('kode', true);
        $kode_pengajuan = $this->input->post('kode_pengajuan', true);

        $cekPjn = $this->model->getBy('pengajuan', 'kode_pengajuan', $kode_pengajuan)->row();
        $cekPjn->cair == 1 ? $tblSelect = 'realis' : $tblSelect = 'real_sm';

        $pjnData = $this->model->getBy2($tblSelect, 'kode', $kode, 'kode_pengajuan', $kode_pengajuan)->row();

        $data = [
            'id_mitra' => $id_mitra,
            'kode' => $kode,
            'kode_pengajuan' => $kode_pengajuan,
            'tgl_order' => $pjnData->tgl,
            'tahun' => $pjnData->tahun,
            'status' => 'belum',
        ];

        $this->model->input('order_mitra', $data);
        if ($this->db->affected_rows() > 0) {
            // $this->session->set_flashdata('ok', 'Add Mitra Berhasil');
            // redirect('kasir/cairProses/' . $kode_pengajuan);
            echo '';
        } else {
            // $this->session->set_flashdata('error', 'Add Mitra Gagal');
            // redirect('kasir/cairProses/' . $kode_pengajuan);
            echo '';
        }
    }

    function delOrderMitra()
    {
        $id = $this->input->post('id_order', true);
        $kode_pengajuan = $this->model->getBy('order_mitra', 'id_order', $id)->row('kode_pengajuan');

        $this->model->delete('order_mitra', 'id_order', $id);
        if ($this->db->affected_rows() > 0) {
            // $this->session->set_flashdata('ok', 'Add Mitra Berhasil');
            // redirect('kasir/cairProses/' . $kode_pengajuan);
            echo '';
        } else {
            // $this->session->set_flashdata('error', 'Add Mitra Gagal');
            // redirect('kasir/cairProses/' . $kode_pengajuan);
            echo '';
        }
    }

    function notaMitra()
    {
        $data['kode_pj'] = $this->uri->segment(3);
        $id_mitra = $this->uri->segment(4);
        $kode_lj = $this->uri->segment(3);

        $sttsPj = $this->model->getBy('pengajuan', 'kode_pengajuan', $kode_lj)->row();
        $sttsPj->cair == 1 ? $tblSelect = 'realis' : $tblSelect = 'real_sm';

        $data['mitra'] = $this->model->getBy('mitra', 'id_mitra', $id_mitra)->row();

        // $data['order_mitra'] = $this->db->query("SELECT order_mitra.*, $tblSelect.*, rab.nama, rab.satuan, rab.harga_satuan FROM order_mitra JOIN $tblSelect ON order_mitra.kode=$tblSelect.kode JOIN rab ON order_mitra.kode=rab.kode WHERE order_mitra.kode_pengajuan = '$kode_lj' AND order_mitra.id_mitra = '$id_mitra' ");
        $data['order_mitra'] = $this->db->query("SELECT order_mitra.*, $tblSelect.* FROM order_mitra JOIN $tblSelect ON order_mitra.kode=$tblSelect.kode WHERE order_mitra.kode_pengajuan = '$kode_lj' AND order_mitra.id_mitra = '$id_mitra' ");

        $data['order_mitraTotal'] = $this->db->query("SELECT SUM($tblSelect.nominal) AS total FROM order_mitra JOIN $tblSelect ON order_mitra.kode=$tblSelect.kode WHERE order_mitra.kode_pengajuan = '$kode_lj' AND order_mitra.id_mitra = '$id_mitra' ")->row();


        $data['lembaga'] = $this->model->getBy2('lembaga', 'tahun', $this->tahun, 'kode', $data['order_mitra']->row('lembaga'));
        $data['kasir'] = $this->user;

        $this->load->view('kasir/cetakNota', $data);
    }

    public function pinjam()
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['pinjam'] = $this->model->getBy('peminjaman', 'tahun', $this->tahun)->result();
        $data['sumPinjam'] = $this->model->getBySum('peminjaman', 'tahun', $this->tahun, 'nominal')->row();
        $data['sumCicil'] = $this->model->getBySum('cicilan', 'tahun', $this->tahun, 'nominal')->row();
        $data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/pinjam', $data);
        $this->load->view('kasir/foot');
    }

    public function savePinjam()
    {
        $data = [
            'id_pinjam' => $this->uuid->v4(),
            'kode_pinjam' => 'PINJAM-' . rand(0, 99999999),
            'nominal' => rmRp($this->input->post('nominal', true)),
            'jml_cicil' => $this->input->post('jml_cicil', true),
            'peminjam' => $this->input->post('peminjam', true),
            'tgl_pinjam' => $this->input->post('tgl_pinjam', true),
            'tahun' => $this->tahun,
            'at' => date('Y-m-d H:i:s')
        ];

        $this->model->input('peminjaman', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Input data sukses');
            redirect('kasir/pinjam');
        } else {
            $this->session->set_flashdata('error', 'Input data gagal');
            redirect('kasir/pinjam');
        }
    }

    public function delPinjam($id)
    {
        $data = $this->model->getBy('peminjaman', 'id_pinjam', $id)->row();

        $this->model->delete('peminjaman', 'id_pinjam', $id);
        $this->model->delete('cicilan', 'kode_pinjam', $data->kode_pinjam);

        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Hapus data sukses');
            redirect('kasir/pinjam');
        } else {
            $this->session->set_flashdata('error', 'Hapus data gagal');
            redirect('kasir/pinjam');
        }
    }

    public function infoPinjam($id)
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);

        $data['dataPinjam'] = $this->model->getBy('peminjaman', 'id_pinjam', $id)->row();
        $data['cicil'] = $this->model->getBy('cicilan', 'kode_pinjam', $data['dataPinjam']->kode_pinjam)->result();
        $data['sumPinjam'] = $this->model->getBySum('peminjaman', 'tahun', $this->tahun, 'nominal')->row();

        $data['sumCicil'] = $this->model->getBySum('cicilan', 'kode_pinjam', $data['dataPinjam']->kode_pinjam, 'nominal')->row();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/infoPinjam', $data);
        $this->load->view('kasir/foot');
    }

    public function addCicil()
    {
        $dataPinjam = $this->model->getBy('peminjaman', 'kode_pinjam', $this->input->post('kode_pinjam', true))->row();
        $data = [
            'id_cicilan' => $this->uuid->v4(),
            'kode_pinjam' =>  $this->input->post('kode_pinjam', true),
            'ket ' =>  $this->input->post('ket', true),
            'tgl_setor ' =>  $this->input->post('tgl_setor', true),
            'nominal ' =>  $dataPinjam->nominal / $dataPinjam->jml_cicil,
            'tahun' => $this->tahun,
            'at' => date('Y-m-d H:i:s')
        ];

        $this->model->input('cicilan', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Input data sukses');
            redirect('kasir/infoPinjam/' . $dataPinjam->id_pinjam);
        } else {
            $this->session->set_flashdata('error', 'Input data gagal');
            redirect('kasir/infoPinjam/' . $dataPinjam->id_pinjam);
        }
    }

    public function delCicil($id)
    {
        $data = $this->model->getBy('cicilan', 'id_cicilan', $id)->row();
        $dataPinjam = $this->model->getBy('peminjaman', 'kode_pinjam', $data->kode_pinjam)->row();

        $this->model->delete('cicilan', 'id_cicilan', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Hapus data sukses');
            redirect('kasir/infoPinjam/' . $dataPinjam->id_pinjam);
        } else {
            $this->session->set_flashdata('error', 'Hapus data gagal');
            redirect('kasir/infoPinjam/' . $dataPinjam->id_pinjam);
        }
    }

    public function pesantren()
    {
        $data['pes'] = $this->model->getBy('pesantren', 'tahun', $this->tahun)->result();
        $data['sumPes'] = $this->model->selectSum('pesantren', 'nominal', 'tahun', $this->tahun)->row();
        $data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
        $data['tahunData'] = $this->model->getAll('tahun')->result();
        $data['bidang'] = $this->model->getBy('bidang', 'tahun', $this->tahun)->result();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/masukPes', $data);
        $this->load->view('kasir/foot');
    }

    public function pesAdd()
    {
        $data = [
            'id_pes' => $this->uuid->v4(),
            'lembaga' => $this->input->post('lembaga', true),
            'bidang' => $this->input->post('bidang', true),
            'kode' => $this->input->post('lembaga', true) . '.' . $this->input->post('bidang', true),
            'uraian' => $this->input->post('uraian', true),
            'periode' => $this->input->post('periode', true),
            'nominal' => rmRp($this->input->post('nominal', true)),
            'tgl_bayar' => $this->input->post('tgl_bayar', true),
            'tahun' => $this->input->post('tahun', true),
            'at' => date('Y-m-d H:i:s')
        ];

        $this->model->input('pesantren', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Input Pemasukan Pesantren Berhasil');
            redirect('kasir/pesantren');
        } else {
            $this->session->set_flashdata('error', 'Input Pemasukan Pesantren Gagal');
            redirect('kasir/pesantren');
        }
    }

    public function editPes($id)
    {
        $data['pes'] = $this->model->getBy('pesantren', 'id_pes', $id)->row();
        $data['sumPes'] = $this->model->selectSum('pesantren', 'nominal', 'tahun', $this->tahun)->row();
        $data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
        $data['tahunData'] = $this->model->getAll('tahun')->result();
        $data['bidang'] = $this->model->getBy('bidang', 'tahun', $this->tahun)->result();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/editPes', $data);
        $this->load->view('kasir/foot');
    }

    public function saveEditPes()
    {
        $id = $this->input->post('id_pes', true);
        $data = [
            'lembaga' => $this->input->post('lembaga', true),
            'bidang' => $this->input->post('bidang', true),
            'kode' => $this->input->post('lembaga', true) . '.' . $this->input->post('bidang', true),
            'uraian' => $this->input->post('uraian', true),
            'periode' => $this->input->post('periode', true),
            'nominal' => rmRp($this->input->post('nominal', true)),
            'tgl_bayar' => $this->input->post('tgl_bayar', true),
            'tahun' => $this->input->post('tahun', true),
            'at' => date('Y-m-d H:i:s')
        ];

        $this->model->update('pesantren', $data, 'id_pes', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Edit Pemasukan Pesantren Berhasil');
            redirect('kasir/pesantren');
        } else {
            $this->session->set_flashdata('error', 'Edit Pemasukan Pesantren Gagal');
            redirect('kasir/pesantren');
        }
    }

    public function delPes($id)
    {
        $this->model->delete('pesantren', 'id_pes', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Hapus Pemasukan Pesantren Berhasil');
            redirect('kasir/pesantren');
        } else {
            $this->session->set_flashdata('error', 'Hapus Pemasukan Pesantren Gagal');
            redirect('kasir/pesantren');
        }
    }

    public function bos()
    {
        $data['bos'] = $this->model->getBy('bos', 'tahun', $this->tahun)->result();
        $data['sumBos'] = $this->model->selectSum('bos', 'nominal', 'tahun', $this->tahun)->row();
        $data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
        $data['tahunData'] = $this->model->getAll('tahun')->result();
        $data['bidang'] = $this->model->getBy('bidang', 'tahun', $this->tahun)->result();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/masukBos', $data);
        $this->load->view('kasir/foot');
    }

    public function bosAdd()
    {
        $data = [
            'id_bos' => $this->uuid->v4(),
            'lembaga' => $this->input->post('lembaga', true),
            'kode' => $this->input->post('lembaga', true) . '.' . $this->input->post('bidang', true),
            'uraian' => $this->input->post('uraian', true),
            'periode' => $this->input->post('periode', true),
            'nominal' => rmRp($this->input->post('nominal', true)),
            'tgl_setor' => $this->input->post('tgl_setor', true),
            'tahun' => $this->input->post('tahun', true),
            'kasir' => $this->input->post('kasir', true),
            'at' => date('Y-m-d H:i:s')
        ];

        $this->model->input('bos', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Input Pemasukan BOS Berhasil');
            redirect('kasir/bos');
        } else {
            $this->session->set_flashdata('error', 'Input Pemasukan BOS Gagal');
            redirect('kasir/bos');
        }
    }

    public function editBos($id)
    {
        $data['pes'] = $this->model->getBy('bos', 'id_bos', $id)->row();
        $data['sumBos'] = $this->model->selectSum('bos', 'nominal', 'tahun', $this->tahun)->row();
        $data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
        $data['tahunData'] = $this->model->getAll('tahun')->result();
        $data['bidang'] = $this->model->getBy('bidang', 'tahun', $this->tahun)->result();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/editBos', $data);
        $this->load->view('kasir/foot');
    }

    public function saveEditBos()
    {
        $id = $this->input->post('id_bos', true);
        $data = [
            'lembaga' => $this->input->post('lembaga', true),
            'kode' => $this->input->post('lembaga', true),
            'uraian' => $this->input->post('uraian', true),
            'periode' => $this->input->post('periode', true),
            'nominal' => rmRp($this->input->post('nominal', true)),
            'tgl_setor' => $this->input->post('tgl_setor', true),
            'tahun' => $this->input->post('tahun', true),
        ];

        $this->model->update('bos', $data, 'id_bos', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Edit Pemasukan Bos Berhasil');
            redirect('kasir/bos');
        } else {
            $this->session->set_flashdata('error', 'Edit Pemasukan Bos Gagal');
            redirect('kasir/bos');
        }
    }

    public function delBos($id)
    {
        $this->model->delete('bos', 'id_bos', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Hapus Pemasukan Bos Berhasil');
            redirect('kasir/bos');
        } else {
            $this->session->set_flashdata('error', 'Hapus Pemasukan Bos Gagal');
            redirect('kasir/bos');
        }
    }

    public function bpMasuk()
    {
        $data['data'] = $this->model->getBy('pembayaran', 'tahun', $this->tahun)->result();
        $data['sumBp'] = $this->model->selectSum('pembayaran', 'nominal', 'tahun', $this->tahun)->row();
        $data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
        $data['tahunData'] = $this->model->getAll('tahun')->result();
        $data['bidang'] = $this->model->getBy('bidang', 'tahun', $this->tahun)->result();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan_cal'] = $this->bulan;

        for ($i = 1; $i <= 12; $i++) {
            $tangg_perbulan = $this->db->select('SUM(nominal) AS jml')
                ->from('tanggungan')
                ->where('tahun', $this->tahun)
                ->where('bulan', $i)
                ->get()
                ->row();

            $bayar_perbulan = $this->model->getBySum2('pembayaran', 'tahun', $this->tahun, 'bulan', $i, 'nominal')->row();

            $tangg_jml = !empty($tangg_perbulan->jml) ? floatval($tangg_perbulan->jml) : 0;
            $bayar_jml = !empty($bayar_perbulan->jml) ? floatval($bayar_perbulan->jml) : 0;

            $bayar_prsn = 0;
            $kurang_prsn = 0;
            if ($tangg_jml > 0) {
                $bayar_prsn = ($bayar_jml / $tangg_jml) * 100;
                $kurang_prsn = (($tangg_jml - $bayar_jml) / $tangg_jml) * 100;
            }

            $jml_tangg[] = array(
                'bulan' => $i,
                'tangg' => $tangg_jml,
                'bayar' => $bayar_jml,
                'bayar_prsn' => $bayar_prsn,
                'kurang' => $tangg_jml - $bayar_jml,
                'kurang_prsn' => $kurang_prsn,
            );
        }

        $data['jml_tangg'] = $jml_tangg;

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/masukBp', $data);
        $this->load->view('kasir/foot');
    }

    public function lain()
    {
        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['keluar'] = $this->model->getBy('keluar', 'tahun', $this->tahun)->result();
        $data['sumKeluar'] = $this->model->getBySum('keluar', 'tahun', $this->tahun, 'nominal')->row();
        $data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/keluar', $data);
        $this->load->view('kasir/foot');
    }

    public function saveOut()
    {
        $data = [
            'id_keluar' => $this->uuid->v4(),
            'nominal' => rmRp($this->input->post('nominal', true)),
            'tanggal' => $this->input->post('tanggal', true),
            'pj' => $this->input->post('pj', true),
            'ket' => $this->input->post('ket', true),
            'tahun' => $this->tahun,
            'kasir' => $this->user,
            'at' => date('Y-m-d H:i:s')
        ];

        $this->model->input('keluar', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Input data sukses');
            redirect('kasir/lain');
        } else {
            $this->session->set_flashdata('error', 'Input data gagal');
            redirect('kasir/lain');
        }
    }

    public function delLain($id)
    {
        $this->model->delete('keluar', 'id_keluar', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Hapus data sukses');
            redirect('kasir/lain');
        } else {
            $this->session->set_flashdata('error', 'Hapus data gagal');
            redirect('kasir/lain');
        }
    }

    function sendNota()
    {
        $kode_pengajuan = $this->uri->segment(3);
        $id_mitra = $this->uri->segment(4);

        $mitra = $this->model->getBy('mitra', 'id_mitra', $id_mitra)->row();
        $url_file = base_url('vertical/assets/nota/' . $kode_pengajuan . '_' . $id_mitra . '.jpg');
        $url_file = base_url('246708021.jpg');
        $caption = 'Kpd, Yth.

' . $mitra->nama . '
Berikut ada order baru dengan Kode Order ' . $kode_pengajuan . '

Terimkasih
TTD


Bendahara PPDWK
';

        // echo $url_file;
        // kirim_nota($this->apiKey, $mitra->hp, $url_file, '0', $caption);
    }

    function notaKPA($kodePj)
    {
        $data['kode_pj'] = $kodePj;

        $data['ajuanData'] = $this->db->query("SELECT order_mitra.*, real_sm.*, mitra.nama AS namaMitra FROM order_mitra JOIN real_sm ON order_mitra.kode=real_sm.kode JOIN mitra ON order_mitra.id_mitra=mitra.id_mitra WHERE order_mitra.kode_pengajuan = '$kodePj' ORDER BY order_mitra.id_mitra ");

        $data['lembaga'] = $this->model->getBy2('lembaga', 'tahun', $this->tahun, 'kode', $data['ajuanData']->row('lembaga'));
        $data['kasir'] = $this->user;

        $this->load->view('kasir/cetakNotaKPA', $data);
    }

    public function outRutin()
    {

        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;

        $data['data'] = $this->db->query("SELECT DISTINCT pengeluaran_rutin.*, 
       lembaga.nama AS nmLembaga, 
       bidang.nama AS nmBidang 
FROM lembaga 
JOIN pengeluaran_rutin ON pengeluaran_rutin.lembaga = lembaga.kode 
JOIN bidang ON pengeluaran_rutin.bidang = bidang.kode
WHERE pengeluaran_rutin.tahun = '$this->tahun' 
AND lembaga.tahun = '$this->tahun' 
AND bidang.tahun = '$this->tahun' 
ORDER BY pengeluaran_rutin.tanggal DESC;")->result();

        $data['sumData'] = $this->model->getBySum('pengeluaran_rutin', 'tahun', $this->tahun, 'nominal')->row();

        $data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
        $data['lembaga2'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
        $data['bidang'] = $this->model->getBy('bidang', 'tahun', $this->tahun)->result();


        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/outRutin', $data);
        $this->load->view('kasir/foot');
    }

    public function saveOutRutin()
    {
        $data = [
            "id_pengeluaran_rutin" => $this->uuid->v4(),
            "langganan" => $this->input->post('langganan', true),
            "lembaga" => $this->input->post('lembaga', true),
            "bidang" => $this->input->post('bidang', true),
            "ket" => $this->input->post('ket', true),
            "nominal" => rmRp($this->input->post('nominal', true)),
            "tanggal" => $this->input->post('tanggal', true),
            "kasir" => $this->user,
            "tahun" => $this->tahun,
            "at" => date('Y-m-d H:i:s')
        ];

        $this->model->input('pengeluaran_rutin', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Input data sukses');
            redirect('kasir/outRutin');
        } else {
            $this->session->set_flashdata('error', 'Input data gagal');
            redirect('kasir/outRutin');
        }
    }

    public function editOutRutin()
    {
        $id = $this->input->post('id_out', 'true');
        $data = [
            "langganan" => $this->input->post('langganan', true),
            "lembaga" => $this->input->post('lembaga', true),
            "bidang" => $this->input->post('bidang', true),
            "ket" => $this->input->post('ket', true),
            "nominal" => rmRp($this->input->post('nominal', true)),
            "tanggal" => $this->input->post('tanggal', true),
        ];

        $this->model->update('pengeluaran_rutin', $data, 'id_pengeluaran_rutin', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Update data sukses');
            redirect('kasir/outRutin');
        } else {
            $this->session->set_flashdata('error', 'Update data gagal');
            redirect('kasir/outRutin');
        }
    }

    public function delOutRutin($id)
    {
        $this->model->delete('pengeluaran_rutin', 'id_pengeluaran_rutin', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Hapus data sukses');
            redirect('kasir/outRutin');
        } else {
            $this->session->set_flashdata('error', 'Hapus data gagal');
            redirect('kasir/outRutin');
        }
    }

    public function outHarian()
    {

        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;

        $data['data'] = $this->db->query("SELECT pengeluaran_harian.*, lembaga.nama AS nmLembaga, bidang.nama AS nmBidang FROM lembaga JOIN pengeluaran_harian ON pengeluaran_harian.lembaga=lembaga.kode JOIN bidang ON pengeluaran_harian.lembaga=bidang.kode WHERE pengeluaran_harian.tahun = '$this->tahun' AND lembaga.tahun = '$this->tahun' AND bidang.tahun = '$this->tahun' ")->result();

        $data['sumData'] = $this->model->getBySum('pengeluaran_harian', 'tahun', $data['tahun'], 'nominal')->row();

        $data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $data['tahun'])->result();
        $data['bidang'] = $this->model->getBy('bidang', 'tahun', $data['tahun'])->result();
        $data['pagu'] = $this->model->getBy('pagu', 'tahun', $data['tahun'])->result();


        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/outHarian', $data);
        $this->load->view('kasir/foot');
    }

    public function saveOutHarian()
    {
        $data = [
            "id_harian" => $this->uuid->v4(),
            "lembaga" => $this->input->post('lembaga', true),
            "bidang" => $this->input->post('bidang', true),
            "pagu" => $this->input->post('pagu', true),
            "jenis" => $this->input->post('jenis', true),
            "nominal" => rmRp($this->input->post('nominal', true)),
            "tanggal" => $this->input->post('tanggal', true),
            "kasir" => $this->user,
            "tahun" => $this->tahun,
            "at" => date('Y-m-d H:i:s')
        ];

        $this->model->input('pengeluaran_harian', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Input data sukses');
            redirect('kasir/outHarian');
        } else {
            $this->session->set_flashdata('error', 'Input data gagal');
            redirect('kasir/outHarian');
        }
    }

    public function delOutHarian($id)
    {
        $this->model->delete('pengeluaran_harian', 'id_harian', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Hapus data sukses');
            redirect('kasir/outHarian');
        } else {
            $this->session->set_flashdata('error', 'Hapus data gagal');
            redirect('kasir/outHarian');
        }
    }

    public function sarpras()
    {
        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;

        $data['data'] = $this->model->getBy2('sarpras', 'tahun', $this->tahun, 'status', 'disetujui')->result();

        $data['bulan'] = $this->bulan;

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/sarpras', $data);
        $this->load->view('kasir/foot');
    }

    function sarprasDetail($kode)
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
        $data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

        $data['data'] = $this->db->query("SELECT sarpras_detail.*, lembaga.nama FROM sarpras_detail JOIN lembaga ON sarpras_detail.lembaga=lembaga.kode WHERE kode_pengajuan = '$kode' AND lembaga.tahun = '$this->tahun' AND sarpras_detail.tahun = '$this->tahun' ")->result();

        $data['dataSum'] = $this->db->query("SELECT SUM(qty * harga_satuan) AS jml FROM sarpras_detail WHERE kode_pengajuan = '$kode' ")->row();

        $data['pj'] = $this->db->query("SELECT * FROM sarpras WHERE kode_pengajuan = '$kode'")->row();

        $data['lembagaData'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/sarprasInput', $data);
        $this->load->view('kasir/foot');
    }

    function cairSarpras()
    {
        $kd_pnj = $this->input->post('kode_pengajuan', true);
        $penerima = $this->input->post('penerima', true);
        $tgl_cair = $this->input->post('tgl_cair', true);
        $total = $this->input->post('total', true);
        $dataPj = $this->model->getBy('sarpras', 'kode_pengajuan', $kd_pnj)->row();
        $dataSum = $this->db->query("SELECT SUM(qty * harga_satuan) AS jml FROM sarpras_detail WHERE kode_pengajuan = '$kd_pnj' ")->row();

        $psn = '💵 *[PENCAIRAN PENGAJUAN SARPRAS]*

Informasi pencairan dana pengajuan sarpras sebagai berikut:

━━━━━━━━━━━━━━━━━━━━
🏫 *Lembaga*     : Biro Umum - Sarpras
🔖 *Kode Peng.*  : ' . $kd_pnj . '
💰 *Nominal*     : ' . rupiah($dataSum->jml) . '
👤 *Penerima*    : ' . $penerima . '
📅 *Tgl Cair*    : ' . $tgl_cair . '
━━━━━━━━━━━━━━━━━━━━

_*Dana telah dicairkan. Dimohon kepada KPA untuk segera menyelesaikan SPJ sebelum melakukan pengajuan berikutnya._*

Terima kasih.';

        $data = ['status' => 'dicairkan'];

        $this->model->update('sarpras', $data, 'kode_pengajuan', $kd_pnj);

        if ($this->db->affected_rows() > 0) {
            // kirim_group($this->apiKey, '120363040973404347@g.us', $psn);
            // kirim_group($this->apiKey, '120363042148360147@g.us', $psn);

            kirim_person($this->apiKey, '085236924510', $psn);

            $this->session->set_flashdata('ok', 'Pencairan Pengajuan berhasil');
            redirect('kasir/sarpras');
        } else {
            $this->session->set_flashdata('error', 'Pencairan Pengajuan tidak bisa');
            redirect('kasir/sarpras');
        }
    }

    public function inHarian()
    {

        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;

        $data['data'] = $this->db->query("SELECT pemasukan_harian.*, lembaga.nama AS nmLembaga, bidang.nama AS nmBidang FROM lembaga JOIN pemasukan_harian ON pemasukan_harian.lembaga=lembaga.kode JOIN bidang ON pemasukan_harian.lembaga=bidang.kode WHERE pemasukan_harian.tahun = '$this->tahun' AND lembaga.tahun = '$this->tahun' AND bidang.tahun = '$this->tahun' ")->result();

        $data['sumData'] = $this->model->getBySum('pemasukan_harian', 'tahun', $data['tahun'], 'nominal')->row();

        $data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $data['tahun'])->result();
        $data['bidang'] = $this->model->getBy('bidang', 'tahun', $data['tahun'])->result();
        $data['pagu'] = $this->model->getBy('pagu', 'tahun', $data['tahun'])->result();


        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/inHarian', $data);
        $this->load->view('kasir/foot');
    }

    public function saveInHarian()
    {
        $data = [
            "id_masukan" => $this->uuid->v4(),
            "lembaga" => $this->input->post('lembaga', true),
            "bidang" => $this->input->post('bidang', true),
            "jenis" => $this->input->post('jenis', true),
            "nominal" => rmRp($this->input->post('nominal', true)),
            "tanggal" => $this->input->post('tanggal', true),
            "kasir" => $this->user,
            "tahun" => $this->tahun,
            "at" => date('Y-m-d H:i:s')
        ];

        $this->model->input('pemasukan_harian', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Input data sukses');
            redirect('kasir/inHarian');
        } else {
            $this->session->set_flashdata('error', 'Input data gagal');
            redirect('kasir/inHarian');
        }
    }

    public function delInHarian($id)
    {
        $this->model->delete('pemasukan_harian', 'id_masukan', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Hapus data sukses');
            redirect('kasir/inHarian');
        } else {
            $this->session->set_flashdata('error', 'Hapus data gagal');
            redirect('kasir/inHarian');
        }
    }

    public function tabungan()
    {

        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;


        $data['sumData'] = $this->model->getBySum2('tabungan', 'tahun', $data['tahun'], 'jenis', 'masuk', 'nominal')->row();
        $data['sumkeluar'] = $this->model->getBySum2('tabungan', 'tahun', $data['tahun'], 'jenis', 'keluar', 'nominal')->row();

        $data['santri'] = $this->model->getBy('tb_santri', 'aktif', 'Y')->result();


        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/tabungan', $data);
        $this->load->view('kasir/foot');
    }

    public function tabunganData()
    {

        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $search_value = isset($this->input->post('search')['value']) ? $this->input->post('search')['value'] : '';

        $length = $length > 0 ? $length : 10;
        $start = $start >= 0 ? $start : 0;
        // $bulanIni = date('m');
        $this->db->select("id_tabungan, tabungan.nis, tb_santri.nama, SUM(CASE WHEN jenis = 'masuk' THEN nominal ELSE 0 END) AS total, SUM(CASE WHEN jenis = 'keluar' THEN nominal ELSE 0 END) AS pakai ");
        $this->db->from('tabungan');
        $this->db->join('tb_santri', 'tabungan.nis=tb_santri.nis');
        $this->db->where('tabungan.tahun', $this->tahun);
        $this->db->where('tb_santri.aktif', 'Y');
        $this->db->group_by('tabungan.nis');
        $this->db->order_by('tb_santri.nama', 'ASC');

        // Filter search
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('tabungan.nis', $search_value);
            $this->db->or_like('tb_santri.nama', $search_value);
            $this->db->group_end();
        }

        $total_records = $this->db->count_all_results('', false); // Count total records without limit

        $this->db->limit($length, $start);
        $query = $this->db->get();
        $data = [];
        $row_number = $start + 1;

        foreach ($query->result() as $row) {
            $data[] = [
                $row_number++,
                $row->id_tabungan,
                $row->nis,
                $row->nama,
                $row->total,
                $row->pakai,
                $row->total - $row->pakai
            ];
        }

        $output = [
            "draw" => $draw,
            "recordsTotal" => $total_records,
            "recordsFiltered" => $total_records,
            "data" => $data
        ];

        // Set content-type header and return JSON data
        header('Content-Type: application/json');
        echo json_encode($output);
        // var_dump($output);
    }
    public function rincianTabungan($nis)
    {

        $draw = intval($this->input->post('draw'));
        $start = intval($this->input->post('start'));
        $length = intval($this->input->post('length'));
        $search_value = isset($this->input->post('search')['value']) ? $this->input->post('search')['value'] : '';

        $length = $length > 0 ? $length : 10;
        $start = $start >= 0 ? $start : 0;
        // $bulanIni = date('m');
        $this->db->select("*");
        $this->db->from('tabungan');
        $this->db->where('nis', $nis);
        $this->db->where('tahun', $this->tahun);
        $this->db->order_by('tanggal', 'DESC');

        // Filter search
        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('tanggal', $search_value);
            $this->db->or_like('nominal', $search_value);
            $this->db->or_like('jenis', $search_value);
            $this->db->or_like('ket', $search_value);
            $this->db->group_end();
        }

        $total_records = $this->db->count_all_results('', false); // Count total records without limit

        $this->db->limit($length, $start);
        $query = $this->db->get();
        $data = [];
        $row_number = $start + 1;

        foreach ($query->result() as $row) {
            $data[] = [
                $row_number++, // 0
                $row->id_tabungan, // 1
                $row->nis, // 2
                $row->nominal, // 3
                $row->tanggal, // 4
                $row->ket, // 5
                $row->jenis, // 6
                $row->kasir // 7
            ];
        }

        $output = [
            "draw" => $draw,
            "recordsTotal" => $total_records,
            "recordsFiltered" => $total_records,
            "data" => $data
        ];

        // Set content-type header and return JSON data
        header('Content-Type: application/json');
        echo json_encode($output);
        // var_dump($output);
    }

    public function saveTabungan()
    {
        $data = [
            "id_tabungan" => $this->uuid->v4(),
            "nis" => $this->input->post('nis', true),
            "nominal" => rmRp($this->input->post('jumlah', true)),
            "tanggal" => $this->input->post('tanggal', true),
            "ket" => $this->input->post('ket', true),
            "jenis" => 'masuk',
            "kasir" => $this->user,
            "tahun" => $this->tahun,
            "created" => date('Y-m-d H:i:s')
        ];

        $this->model->input('tabungan', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Input data sukses');
            redirect('kasir/tabungan');
        } else {
            $this->session->set_flashdata('error', 'Input data gagal');
            redirect('kasir/tabungan');
        }
    }

    public function delTabungan($id)
    {
        $this->model->delete('tabungan', 'id_tabungan', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Hapus data sukses');
            redirect('kasir/tabungan');
        } else {
            $this->session->set_flashdata('error', 'Hapus data gagal');
            redirect('kasir/tabungan');
        }
    }

    public function outTabungan()
    {
        $user = $this->Auth_model->current_user();

        $nominal = rmRp($this->input->post('nominal', true));
        $tgl = $this->input->post('tgl', true);
        $kasir = $user->nama;
        $nama = $this->input->post('nama', true);
        $nis = $this->input->post('nis', true);
        $tahun = $this->tahun;
        $dekos = $this->input->post('dekos', true);
        $bulan_bayar = $this->input->post('bulan', true);
        $ket = $this->input->post('ket', true);
        $admin = $this->input->post('admin', true);

        $dp = $this->model->getBy('tb_santri', 'nis', $nis)->row();
        $dpBr = $this->model->getBy3('tanggungan', 'nis', $nis, 'tahun', $this->tahun, 'bulan', $bulan_bayar)->row();

        // $by = $nominal + $this->input->post('masuk', true);
        // $ttl = $this->input->post('ttl', true);
        $alm = $dp->desa . '-' . $dp->kec . '-' . $dp->kab;
        // $hpNo = $dp->hp;
        $hpNo = '089682351413';
        $hpNo2 = '085236924510';

        $data = [
            'nis' => $nis,
            'nama' => $nama,
            'tgl' => $tgl,
            'nominal' => $nominal,
            'bulan' => $bulan_bayar,
            'tahun' => $tahun,
            'kasir' => $kasir,
        ];
        $data2 = [
            'nis' => $nis,
            'nominal' => 300000,
            'bulan' => $bulan_bayar,
            'tahun' => $tahun,
            'tgl' => $tgl,
            'penerima' => $kasir,
            'stts' => 1,
            'waktu' => date('Y-m-d H:i'),
        ];
        $dataTabungan = [
            "id_tabungan" => $this->uuid->v4(),
            "nis" => $nis,
            "nominal" => $nominal,
            "tanggal" => $tgl,
            "ket" => $ket,
            "jenis" => 'keluar',
            "kasir" => $this->user,
            "tahun" => $this->tahun,
            "created" => date('Y-m-d H:i:s')
        ];
        $dataAdmin = [
            "id_tabungan" => $this->uuid->v4(),
            "nis" => $nis,
            "nominal" => rmRp($admin),
            "tanggal" => $tgl,
            "ket" => 'Biaya admin',
            "jenis" => 'keluar',
            "kasir" => $this->user,
            "tahun" => $this->tahun,
            "created" => date('Y-m-d H:i:s')
        ];

        // No. BRIVA : *' . $dpBr->briva . '*
        $pesan = '
*KWITANSI PEMBAYARAN ELEKTRONIK*
*PP DARUL LUGHAH WAL KAROMAH*
Bendahara Pondok Pesantren Darul Lughah Wal Karomah telah menerima pembayaran BP dari wali santri berikut :
    
Nama : *' . $nama . '*
Alamat : *' . $alm . '* 
Nominal Pembayaran: *' . rupiah($nominal) . '*
Tanggal Bayar : *' . $tgl . '*
Pembayaran Untuk: *BP (Biaya Pendidikan) bulan ' . $this->bulan[$bulan_bayar] . '*
Penerima: *' . $kasir . '*

Bukti Penerimaan ini *DISIMPAN* oleh wali santri sebagai bukti pembayaran Biaya Pendidikan PP Darul Lughah Wal Karomah Tahun Pelajaran ' . $tahun . '.
*Hal – hal yang berkaitan dengan Teknis keuangan dapat menghubungi Contact Person Bendahara berikut :*
*https://wa.me/6282329641926*

Terimakasih';

        $cek = $this->db->query("SELECT * FROM pembayaran WHERE nis = '$nis' AND bulan = '$bulan_bayar' AND tahun = '$tahun' ")->num_rows();
        $cekTabungan = $this->db->query("SELECT SUM(CASE WHEN jenis = 'masuk' THEN nominal ELSE 0 END) AS total, SUM(CASE WHEN jenis = 'keluar' THEN nominal ELSE 0 END) AS pakai FROM tabungan WHERE nis = '$nis' AND tahun = '$tahun' ")->row();
        $saldo = $cekTabungan->total - $cekTabungan->pakai;
        if ($cek < 1) {
            if ($saldo < $nominal) {
                $this->session->set_flashdata('error', 'Saldo tidak cukup');
                redirect('kasir/tabungan');
            } else {
                if ($dekos == 'Y') {
                    $this->model->inputDb2('kos', $data2);
                    $this->model->input('pembayaran', $data);
                    $this->model->input('tabungan', $dataTabungan);
                    if ($admin != 0 || $admin != '') {
                        $this->model->input('tabungan', $dataAdmin);
                    }

                    if ($this->db->affected_rows() > 0) {
                        // kirim_person($this->apiKey, $hpNo, $pesan);
                        // kirim_person($this->apiKey, $hpNo2, $pesan);
                        $this->session->set_flashdata('ok', 'Tabungan berhasil diinput');
                        redirect('kasir/tabungan');
                    } else {
                        $this->session->set_flashdata('error', 'Tabungan tidak berhasil diinput');
                        redirect('kasir/tabungan');
                    }
                } else {
                    $this->model->input('pembayaran', $data);
                    $this->model->input('tabungan', $dataTabungan);
                    if ($admin != 0 || $admin != '') {
                        $this->model->input('tabungan', $dataAdmin);
                    }

                    if ($this->db->affected_rows() > 0) {
                        // kirim_person($this->apiKey, $hpNo, $pesan);
                        // kirim_person($this->apiKey, $hpNo2, $pesan);
                        $this->session->set_flashdata('ok', 'Tabungan berhasil diinput');
                        redirect('kasir/tabungan');
                    } else {
                        $this->session->set_flashdata('error', 'Tabungan tidak berhasil diinput');
                        redirect('kasir/tabungan');
                    }
                }
            }
        } else {
            $this->session->set_flashdata('error', 'Maaf pembayaran bulan ini sudah ada');
            redirect('kasir/tabungan');
        }
    }

    public function pajak()
    {

        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;

        $data['data'] = $this->model->getBy('pajak', 'tahun', $this->tahun)->result();

        $data['sumData'] = $this->model->getBySum('pajak', 'tahun', $data['tahun'], 'nominal')->row();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/pajak', $data);
        $this->load->view('kasir/foot');
    }

    function savePajak()
    {
        $data = [
            'id_pajak' => $this->uuid->v4(),
            'jenis' => $this->input->post('jenis', true),
            "ket" => $this->input->post('ket', true),
            "nominal" => rmRp($this->input->post('nominal', true)),
            "tanggal" => $this->input->post('tanggal', true),
            "kasir" => $this->user,
            "tahun" => $this->tahun,
            "at" => date('Y-m-d H:i:s')
        ];

        $this->model->input('pajak', $data);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Input data sukses');
            redirect('kasir/pajak');
        } else {
            $this->session->set_flashdata('error', 'Input data gagal');
            redirect('kasir/pajak');
        }
    }

    public function delPajak($id)
    {
        $this->model->delete('pajak', 'id_pajak', $id);
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata('ok', 'Hapus data sukses');
            redirect('kasir/pajak');
        } else {
            $this->session->set_flashdata('error', 'Hapus data gagal');
            redirect('kasir/pajak');
        }
    }

    public function changeAkses()
    {
        $id = $this->input->post('id', true);
        $lembaga = $this->input->post('lembaga', true);
        $level = $this->input->post('level', true);

        $cek = $this->model->getBy('user', 'id_user', $id)->row();
        if ($cek->level != 'admin') {
            redirect('login/logout');
        } else {
            if ($level === 'lembaga') {
                $this->model->update('user', ['lembaga' => $lembaga], 'id_user', $id);
                if ($this->db->affected_rows() > 0) {
                    redirect('lembaga');
                }
            } else {
                redirect($level);
            }
        }
    }


    public function cekTanggungan()
    {
        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;

        $data['lmbFr'] = $this->db->query("SELECT t_formal as nama FROM tb_santri WHERE aktif = 'Y' AND t_formal IS NOT NULL AND t_formal != '' GROUP BY t_formal ORDER BY t_formal")->result();
        $data['tahunData'] = $this->model->getAll('tahun')->result();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/cekTgn', $data);
        $this->load->view('kasir/foot');
    }

    function getKelas()
    {
        $t_formal = $this->input->post('t_formal', true);

        echo "<option value=''>Pilih Kelas</option>";

        $short_lembaga = $t_formal;
        if (strpos($t_formal, ' ') !== false) {
            $parts = explode(' ', $t_formal);
            $short_lembaga = $parts[0];
        }

        $this->db4->where('lembaga', $short_lembaga);
        $this->db4->group_by('nm_kelas');
        $kls = $this->db4->get('kl_formal')->result();

        foreach ($kls as $row) {
            echo "<option value='" . $row->nm_kelas . "'>" . $row->nm_kelas . "</option>";
        }
    }

    function cekKelas()
    {
        $t_formal = $this->input->post('t_formal', true);
        $tahun = $this->input->post('tahun', true);

        $data['tahun'] = $tahun;

        $data['dt1'] = $this->db->query("SELECT * FROM pembayaran a JOIN tb_santri b ON a.nis=b.nis WHERE b.t_formal = '$t_formal' AND a.tahun = '$tahun' AND b.aktif = 'Y' GROUP BY a.nis ORDER BY b.nama")->result();

        $data['dt_null'] = $this->db->query("SELECT * FROM tb_santri WHERE t_formal = '$t_formal' AND aktif = 'Y' AND  NOT EXISTS (SELECT * FROM pembayaran WHERE tb_santri.nis = pembayaran.nis AND tahun = '$tahun') ")->result();
        
        $this->load->view('kasir/hasilCekKelas', $data);
    }

    public function sisa()
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;

        $data['data'] = $this->model->getBy('real_sisasm', 'tahun', $this->tahun)->result();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/sisaReal', $data);
        $this->load->view('kasir/foot');
    }

    public function tarikSisa($id)
    {
        $sisa = $this->model->getBy('real_sisasm', 'id_sisa', $id)->row();
        $data = [
            'id_sisa' => $sisa->id_sisa,
            'kode_pengajuan' => $sisa->kode_pengajuan,
            'dana_cair' => $sisa->dana_cair,
            'dana_serap' => $sisa->dana_serap,
            'sisa' => $sisa->sisa,
            'tgl_setor' => date('Y-m-d'),
            'kasir' => $sisa->kasir,
            'tahun' => $sisa->tahun,
        ];

        $kode = $sisa->kode_pengajuan;
        $lmb = $this->db->query("SELECT pengajuan.*, lembaga.nama FROM pengajuan JOIN lembaga ON pengajuan.lembaga=lembaga.kode WHERE kode_pengajuan = '$kode' AND lembaga.tahun = '$this->tahun' ")->row();

        if (preg_match("/DISP./i", $kode)) {
            $rt = "*(DISPOSISI)*";
        } else {
            $rt = '';
        }

        $psn = '✅ *[VERIFIKASI BERKAS SPJ]* ' . $rt . '

Informasi pelaporan SPJ dari lembaga sebagai berikut:

━━━━━━━━━━━━━━━━━━━━
🏫 *Lembaga*     : ' . $lmb->nama . '
🔖 *Kode Peng.*  : ' . $kode . '
📅 *Waktu*       : ' . date('d-m-Y H:i:s') . '
━━━━━━━━━━━━━━━━━━━━

_*Hard copy SPJ dan sisa belanja anggaran telah disetor kepada KASIR. Untuk pengajuan berikutnya sudah bisa dilakukan._*

🔗 https://simkupaduka.ppdwk.com

Terima kasih.';

        $data1 = ['stts' => '3'];
        $data2 = ['spj' => '3'];

        $this->model->input('real_sisa', $data);

        if ($this->db->affected_rows() > 0) {
            $this->model->delete('real_sisasm', 'id_sisa', $id);
            $this->model->update('spj', $data1, 'kode_pengajuan', $kode);
            $this->model->update('pengajuan', $data2, 'kode_pengajuan', $kode);

            // kirim_group($this->apiKey, '120363040973404347@g.us', $psn);
            // kirim_group($this->apiKey, '120363042148360147@g.us', $psn);
            // // kirim_person($this->apiKey, $hp, $psn);

            kirim_person($this->apiKey, '085236924510', $psn);
            $this->session->set_flashdata('ok', 'Update data sukses');
            redirect('kasir/sisa');
        } else {
            $this->session->set_flashdata('error', 'Update data gagal');
            redirect('kasir/sisa');
        }
    }

    public function rekom()
    {
        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['bulan'] = $this->bulan;

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/rekom', $data);
        $this->load->view('kasir/foot');
    }

    public function loadSantri()
    {
        // $data['santri'] = $this->db->query("SELECT * FROM tb_santri WHERE NOT EXISTS (SELECT * FROM rekom WHERE tb_santri.nis=rekom.nis AND rekom.tahun = '$this->tahun' AND rekom.ket = 'ramadhan') AND aktif = 'Y' ORDER BY t_formal DESC, k_formal ASC, nama ASC ")->result();

        $data['santri'] = $this->db->query("SELECT * FROM tb_santri WHERE NOT EXISTS (SELECT * FROM rekom WHERE tb_santri.nis=rekom.nis AND rekom.tahun = '$this->tahun' AND rekom.ket = 'ramadhan') AND aktif = 'Y' ORDER BY t_formal DESC, k_formal ASC, nama ASC ")->result();

        $this->load->view('kasir/loadSantri', $data);
    }
    public function loadRekom()
    {
        // $data['data'] = $this->db->query("SELECT * FROM rekom JOIN tb_santri ON rekom.nis=tb_santri.nis WHERE rekom.ket = 'ramadhan' AND rekom.tahun = '$this->tahun' AND aktif = 'Y' ORDER BY t_formal DESC, k_formal ASC, nama ASC ")->result();

        $data['data'] = $this->db->query("SELECT * FROM rekom JOIN tb_santri ON rekom.nis=tb_santri.nis WHERE rekom.ket = 'ramadhan' AND rekom.tahun = '$this->tahun' AND aktif = 'Y' ORDER BY t_formal DESC, k_formal ASC, nama ASC ")->result();

        $this->load->view('kasir/loadRekom', $data);
    }

    public function addRekom()
    {
        $data = array(
            'nis' => $this->input->post('nis'),
            'ket' => 'ramadhan',
            // 'ket' => 'ramadhan',
            'tahun' => $this->tahun,
        );

        $result = $this->model->input('rekom', $data);

        if ($result) {
            echo "Data berhasil ditambahkan.";
        } else {
            echo "Terjadi kesalahan saat menambahkan data.";
        }
    }
    public function delRekom()
    {
        $nis = $this->input->post('nis');

        $result = $this->model->delete('rekom', 'nis', $nis);

        if ($result) {
            echo "Data berhasil dihapus.";
        } else {
            echo "Terjadi kesalahan saat menghapus data.";
        }
    }

    public function cadangan()
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
        $data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

        $data['cadangan'] = $this->model->getBy('cadangan', 'tahun', $this->tahun)->result();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/cadangan', $data);
        $this->load->view('kasir/foot');
    }

    public function saveCadangan()
    {
        $id = $this->uuid->v4();
        $ket = $this->input->post('ket', true);
        $tanggal = $this->input->post('tanggal', true);
        $nominal = rmRp($this->input->post('nominal', true));
        $jenis = $this->input->post('jenis', true);
        $berkas = $this->input->post('berkas', true);

        if ($berkas != '') {
            $file_name = 'cadangan-' . rand(0, 99999999);
            $config['upload_path']          = FCPATH . '/vertical/assets/uploads/';
            $config['allowed_types']        = 'pdf';
            $config['file_name']            = $file_name;
            $config['overwrite']            = true;
            $config['max_size']             = 10240; // 10MB
            $config['max_width']            = 1080;
            $config['max_height']           = 1080;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('berkas')) {
                // $data['error'] = $this->upload->display_errors();
                $this->session->set_flashdata('error', 'Gagal diupload. pastikan file berupa PDF dan tidak melebihi 10 Mb');
                redirect('cadangan');
            } else {
                $uploaded_data = $this->upload->data();

                $data3 = [
                    'id_cadangan' => $id,
                    'tanggal' => $tanggal,
                    'nominal' => $nominal,
                    'ket' => $ket,
                    'berkas' => $uploaded_data['file_name'],
                    'jenis' => $jenis,
                    'kasir' => $this->user,
                    'tahun' => $this->tahun,
                ];

                $this->model->input('cadangan', $data3);

                if ($this->db->affected_rows() > 0) {
                    $this->session->set_flashdata('ok', 'Input data baru berhasil');
                    redirect('cadangan');
                } else {
                    $this->session->set_flashdata('error', 'Input data baru gagal');
                    redirect('cadangan');
                }
            }
        } else {
            $data3 = [
                'id_cadangan' => $id,
                'tanggal' => $tanggal,
                'nominal' => $nominal,
                'ket' => $ket,
                'berkas' => '-',
                'jenis' => $jenis,
                'kasir' => $this->user,
                'tahun' => $this->tahun,
            ];

            $this->model->input('cadangan', $data3);

            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('ok', 'Input data baru berhasil');
                redirect('cadangan');
            } else {
                $this->session->set_flashdata('error', 'Input data baru gagal');
                redirect('cadangan');
            }
        }
    }

    public function haflah()
    {
        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;

        $data['data'] = $this->model->getBy2('haflah', 'tahun', $this->tahun, 'status', 'disetujui')->result();

        $data['bulan'] = $this->bulan;

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/haflah', $data);
        $this->load->view('kasir/foot');
    }

    function haflahDetail($kode)
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
        $data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
        $data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

        $data['data'] = $this->db->query("SELECT haflah_detail.*, lembaga.nama FROM haflah_detail JOIN lembaga ON haflah_detail.lembaga=lembaga.kode WHERE kode_pengajuan = '$kode' AND lembaga.tahun = '$this->tahun' AND haflah_detail.tahun = '$this->tahun' ")->result();

        $data['dataSum'] = $this->db->query("SELECT SUM(qty * harga_satuan) AS jml FROM haflah_detail WHERE kode_pengajuan = '$kode' ")->row();

        $data['pj'] = $this->db->query("SELECT * FROM haflah WHERE kode_pengajuan = '$kode'")->row();

        $data['lembagaData'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/haflahInput', $data);
        $this->load->view('kasir/foot');
    }

    function cairHaflah()
    {
        $kd_pnj = $this->input->post('kode_pengajuan', true);
        $penerima = $this->input->post('penerima', true);
        $tgl_cair = $this->input->post('tgl_cair', true);
        $total = $this->input->post('total', true);
        $dataPj = $this->model->getBy('haflah', 'kode_pengajuan', $kd_pnj)->row();
        $dataSum = $this->db->query("SELECT SUM(qty * harga_satuan) AS jml FROM haflah_detail WHERE kode_pengajuan = '$kd_pnj' ")->row();

        $psn = '💵 *[PENCAIRAN PENGAJUAN HAFLAH]*

Informasi pencairan dana pengajuan haflah sebagai berikut:

━━━━━━━━━━━━━━━━━━━━
🏫 *Lembaga*     : Haflah Pesantren
🔖 *Kode Peng.*  : ' . $kd_pnj . '
💰 *Nominal*     : ' . rupiah($dataSum->jml) . '
👤 *Penerima*    : ' . $penerima . '
📅 *Tgl Cair*    : ' . $tgl_cair . '
━━━━━━━━━━━━━━━━━━━━

_*Dana telah dicairkan. Dimohon kepada KPA untuk segera menyelesaikan SPJ sebelum melakukan pengajuan berikutnya._*

Terima kasih.';

        $data = ['status' => 'dicairkan'];

        $this->model->update('haflah', $data, 'kode_pengajuan', $kd_pnj);

        if ($this->db->affected_rows() > 0) {
            // kirim_group($this->apiKey, '120363040973404347@g.us', $psn);
            // kirim_group($this->apiKey, '120363042148360147@g.us', $psn);

            kirim_person($this->apiKey, '085236924510', $psn);

            $this->session->set_flashdata('ok', 'Pencairan Pengajuan berhasil');
            redirect('kasir/haflah');
        } else {
            $this->session->set_flashdata('error', 'Pencairan Pengajuan tidak bisa');
            redirect('kasir/haflah');
        }
    }

    public function panjar()
    {
        $data['user'] = $this->Auth_model->current_user();
        $data['tahun'] = $this->tahun;
        $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();

        $data['panjar'] = $this->model->getBy('panjar', 'tahun', $this->tahun)->result();
        $data['total'] = $this->db->query("SELECT SUM(nominal) AS total FROM panjar WHERE tahun = '$this->tahun' ")->row();

        $this->load->view('kasir/head', $data);
        $this->load->view('kasir/panjar', $data);
        $this->load->view('kasir/foot');
    }

    public function savePanjar()
    {
        $id = $this->uuid->v4();
        $jenis = $this->input->post('jenis', true);
        $kegiatan = $this->input->post('kegiatan', true);
        $tanggal = $this->input->post('tanggal', true);
        $nominal = rmRp($this->input->post('nominal', true));
        $pj = $this->input->post('pj', true);

        $file_name = 'PANJAR-' . rand(0, 99999999);
        $config['upload_path']          = FCPATH . '/vertical/assets/uploads/';
        $config['allowed_types']        = 'pdf';
        $config['file_name']            = $file_name;
        $config['overwrite']            = true;
        $config['max_size']             = 10240; // 10MB
        $config['max_width']            = 1080;
        $config['max_height']           = 1080;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('berkas')) {
            // $data['error'] = $this->upload->display_errors();
            $this->session->set_flashdata('error', 'Gagal diupload. pastikan file berupa PDF dan tidak melebihi 5 Mb');
            redirect('kasir/panjar');
        } else {
            $uploaded_data = $this->upload->data();

            $data3 = [
                'id_panjar' => $id,
                'jenis' => $jenis,
                'kegiatan' => $kegiatan,
                'tanggal' => $tanggal,
                'nominal' => $nominal,
                'berkas' => $uploaded_data['file_name'],
                'pj' => $pj,
                'tahun' => $this->tahun,
            ];

            $this->model->input('panjar', $data3);

            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('ok', 'Input data baru berhasil');
                redirect('kasir/panjar');
            } else {
                $this->session->set_flashdata('error', 'Input data baru gagal');
                redirect('kasir/panjar');
            }
        }
    }

    public function editSaldoCash()
    {
        $saldo = [
            'nominal' => rmRp($this->input->post('nominal', true)),
            'last' => date('Y-m-d H:i:s')
        ];

        $psn = '💵 *[UPDATE SALDO CASH PESANTREN]*

Informasi pembaruan saldo cash pesantren sebagai berikut:

━━━━━━━━━━━━━━━━━━━━
💰 *Nominal*    : Rp. ' . $this->input->post('nominal', true) . '
📅 *Tgl Update* : ' . date('d-m-Y H:i:s') . '
👤 *Updater*    : ' . $this->user . '
━━━━━━━━━━━━━━━━━━━━

Terima kasih.';

        $this->model->update('saldo', $saldo, 'name', 'cash', 'tahun', $this->tahun);
        if ($this->db->affected_rows() > 0) {
            // kirim_person($this->apiKey, '082264061060', $psn);
            // kirim_person($this->apiKey, '085258222376', $psn);
            // kirim_person($this->apiKey, '085236924510', $psn);
            $this->session->set_flashdata('ok', 'Saldo sudah diperbarui');
            redirect('kasir');
        } else {
            $this->session->set_flashdata('error', 'Hapus data gagal');
            redirect('kasir');
        }
    }

    // public function nikmus()
    // {
    //     $data['user'] = $this->Auth_model->current_user();
    //     $data['tahun'] = $this->tahun;
    //     $data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
    //     $data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
    //     $data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

    //     $data['panjar'] = $this->model->getBy('panjar', 'tahun', $this->tahun)->result();

    //     $this->load->view('kasir/head', $data);
    //     $this->load->view('kasir/nikmus', $data);
    //     $this->load->view('kasir/foot');
    // }

    public function downloadFormatTagihan()
    {
        force_download('vertical/assets/templates/FORM_UPLOAD_TAGIHAN_SANTRI.xlsx', null);
    }

    public function uploadTagihan()
    {
        // Load library dan helper
        $this->load->helper('file');

        // Konfigurasi upload file
        $config['upload_path'] = 'vertical/assets/uploads/'; // Direktori penyimpanan file
        $config['allowed_types'] = 'xls|xlsx'; // Jenis file yang diizinkan
        $config['max_size'] = 10240; // Ukuran maksimum file (dalam kilobytes)

        // Memuat library upload
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file')) {
            // Jika upload gagal, tampilkan pesan error
            $error = $this->upload->display_errors();
            echo $error;
        } else {
            // Jika upload berhasil, dapatkan informasi file
            $data = $this->upload->data();
            $file_path = $data['full_path'];
            // Load file Excel menggunakan library PHPExcel
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            $objPHPExcel = $reader->load($file_path);

            // Mendapatkan data dari worksheet pertama
            $worksheet = $objPHPExcel->getActiveSheet();
            $highestRow = $worksheet->getHighestDataRow();
            // $highestColumn = $worksheet->getHighestColumn();

            // echo $highestRow;

            // Mulai dari baris kedua (untuk melewati header)
            for ($row = 2; $row <= $highestRow; $row++) {
                $nis = preg_replace('/[^\x20-\x7E]/', '', $worksheet->getCell('L' . $row)->getValue());
                // $briva = preg_replace('/[^\x20-\x7E]/', '', $worksheet->getCell('D' . $row)->getValue());
                $nominal = preg_replace('/[^\x20-\x7E]/', '', $worksheet->getCell('H' . $row)->getValue());
                $bulan = preg_replace('/[^\x20-\x7E]/', '', $worksheet->getCell('M' . $row)->getValue());
                // $tahun = preg_replace('/[^\x20-\x7E]/', '', $worksheet->getCell('G' . $row)->getValue());


                // echo $lembaga . '-' . random_int(1000, 9999) . ' ' . $kegiatan . '<br>';
                $data = [
                    'id_tanggungan' => $this->uuid->v4(),
                    'nis' => $nis,
                    'briva' => '',
                    'nominal' => $nominal,
                    'bulan' => $bulan,
                    'tahun' => $this->tahun,
                    'tgl_upload' => date('Y-m-d'),
                    'kasir' => $this->user,
                ];

                $this->model->input('tanggungan', $data);
            }

            // Hapus file setelah selesai mengimpor
            delete_files($file_path);

            // Tampilkan pesan sukses atau lakukan redirect ke halaman lain
            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('ok', 'Upload Selesai');
                redirect('kasir/tanggungan');
            }
        }
    }

    public function cetakNotaTabungan($id)
    {
        // Ambil data nota berdasarkan ID
        $dataNota = $this->model->getBy('tabungan', 'id_tabungan', $id)->row(); // Model yang Anda gunakan untuk mengambil data nota
        $dataSantri = $this->model->getBy('tb_santri', 'nis', $dataNota->nis)->row(); // Model yang Anda gunakan untuk mengambil data nota

        try {
            // Tentukan IP dan port printer POS
            $connector = new NetworkPrintConnector("192.168.0.100", 9100);

            // Inisialisasi printer
            $printer = new Printer($connector);
            $columnLeft = 10;
            // Mulai mencetak
            $printer->setFont(Printer::FONT_B);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(2, 2);
            $printer->text("KWITANSI PEMBAYARAN BP\n");
            $printer->text("\n");
            $printer->text("Ponpes Darul Lughah Wal Karomah\n");
            $printer->feed();
            $printer->setTextSize(1, 1);
            $printer->text("Jl. Mayjend Pandjaitan No.12 Kel. Sidomukti - Kraksaan - Probolinggo - Jawa Timur\n");
            $printer->text("───────────────────────────────────────────────────────────────\n");
            // $printer->feed();
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH | Printer::MODE_DOUBLE_HEIGHT);

            $printer->setTextSize(1, 1);
            $printer->text("Tanggal : " . date('d-m-Y H:i:s') . "\n");
            $printer->text("Kasir : $this->user\n");
            $printer->text("Ket : Tabungan Santri\n");
            $printer->feed();

            $printer->selectPrintMode(Printer::MODE_UNDERLINE);
            $printer->text("Diterima dari:\n");
            $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH | Printer::MODE_DOUBLE_HEIGHT);
            $printer->setTextSize(1, 1);
            $printer->text(gabung2Kolom('No. Briva', ' : 112009488820084', 10, 38) . "\n");
            $printer->text(gabung2Kolom('Nama', " : $dataSantri->nama", 10, 38) . "\n");
            $printer->text(gabung2Kolom('Alamat', " : $dataSantri->desa-$dataSantri->kec-$dataSantri->kab", 10, 38) . "\n");
            $printer->text(gabung2Kolom('Kelas', " : $dataSantri->k_formal $dataSantri->jurusan $dataSantri->t_formal", 10, 38) . "\n");
            $printer->feed();
            $printer->selectPrintMode(Printer::MODE_UNDERLINE);
            $printer->text("Rincian:\n");
            $printer->selectPrintMode(Printer::MODE_EMPHASIZED);
            $printer->setTextSize(1, 1);
            $printer->text(gabung2Kolom('Tgl Bayar', " : $dataNota->tanggal", 10, 38) . "\n");
            $printer->text(gabung2Kolom('Nominal', ' : ' . rupiah($dataNota->nominal), 10, 38) . "\n");
            $printer->text(gabung2Kolom('Penerima', " : $dataNota->kasir", 10, 38) . "\n");
            $printer->text(gabung2Kolom('Ket', " : $dataNota->ket", 10, 38) . "\n");
            $printer->feed();
            $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH | Printer::MODE_DOUBLE_HEIGHT);
            $printer->setTextSize(1, 1);
            $printer->text("Catatan:\n");
            $printer->text("Bukti pembayaran ini DISIMPAN oleh wali santri sebagai bukti pembayaran Biaya Pendidikan (BP) PonPesa Darul Lughah Wal Karomah tahun pelajaran $this->tahun\n");
            $printer->feed();
            $printer->text("Hal-hal yang berkaitan dengan teknis keuangan dapat menghubungi Contact Person berikut\n");
            $printer->selectPrintMode(Printer::MODE_UNDERLINE | Printer::MODE_EMPHASIZED);
            $printer->setTextSize(1, 1);
            $printer->text("0823-2964-1926\n");
            $printer->feed();
            // $printer->selectPrintMode(Printer::JUSTIFY_RIGHT);
            $printer->text("Kraksaan, " . date('d-m-Y') . "\n");
            $printer->feed();
            $printer->feed();
            $printer->feed();
            $printer->text("Benahara Pesantren\n");
            $printer->feed();

            // $printer->setTextSize(4, 4);
            // $printer->text("$nomor_antrian\n");
            // $printer->setTextSize(1, 1);
            // $printer->text("$tanggal $waktu\n");
            // $printer->text("Harap menunggu panggilan\n");
            // $printer->text("TERIMAKASIH\n");

            // Potong kertas
            $printer->cut();

            // Tutup koneksi ke printer
            $printer->close();

            // Berikan respon sukses ke client
            echo json_encode(['status' => 'success', 'message' => 'Nota berhasil dicetak.']);
        } catch (Exception $e) {
            // Jika ada kesalahan
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function getDetailSantri()
    {
        $nis = $_POST['nis'];
        $data = $this->model->getBy('tb_santri', 'nis', $nis)->row();
        echo json_encode([
            'nama' => $data->nama,
        ]);
    }

	public function santri()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['controller'] = 'kasir';

		// Get unique formal class/lembaga values for filtering
		$data['lembaga_list'] = $this->db->select('t_formal')
			->from('tb_santri')
			->where('t_formal IS NOT NULL')
			->where('t_formal !=', '')
			->group_by('t_formal')
			->get()
			->result();

		$this->load->view('kasir/head', $data);
		$this->load->view('admin/santri', $data);
		$this->load->view('kasir/foot');
	}

	public function santri_list_ajax()
	{
		$draw = intval($this->input->post('draw'));
		$start = intval($this->input->post('start'));
		$length = intval($this->input->post('length'));
		$search_value = $this->input->post('search')['value'] ?? '';
		$order = $this->input->post('order');
		$filter_lembaga = $this->input->post('filter_lembaga');
		$filter_cost = $this->input->post('filter_cost');
		$filter_keterangan = $this->input->post('filter_keterangan');
		$filter_status = $this->input->post('filter_status') ?? 'Y';

		// Column mapping for ordering
		$columns = [
			0 => 'id_santri', // not sorted
			1 => 'tb_santri.nis',
			2 => 'cost.cost_id',
			3 => 'tb_santri.nama',
			4 => 'tb_santri.t_formal',
			5 => 'id_santri' // Aksi
		];

		// Base query configuration
		$this->db->select('tb_santri.*, cost.cost_id');
		$this->db->from('tb_santri');
		$this->db->join('cost', 'tb_santri.nis = cost.nis', 'left');
		
		if ($filter_status !== 'all') {
			$this->db->where('tb_santri.aktif', $filter_status);
		}

		// Apply Lembaga filter
		if (!empty($filter_lembaga)) {
			$this->db->where('tb_santri.t_formal', $filter_lembaga);
		}

		// Apply Keterangan filter
		if ($filter_keterangan !== null && $filter_keterangan !== '') {
			$this->db->where('tb_santri.ket', $filter_keterangan);
		}

		// Apply Customer ID filter
		if ($filter_cost === 'ada') {
			$this->db->where('cost.cost_id IS NOT NULL');
			$this->db->where('cost.cost_id !=', '');
		} elseif ($filter_cost === 'tidak') {
			$this->db->group_start();
			$this->db->where('cost.cost_id IS NULL');
			$this->db->or_where('cost.cost_id', '');
			$this->db->group_end();
		}

		// Handle search filter
		if (!empty($search_value)) {
			$this->db->group_start();
			$this->db->like('tb_santri.nis', $search_value);
			$this->db->or_like('tb_santri.nama', $search_value);
			$this->db->or_like('cost.cost_id', $search_value);
			$this->db->or_like('tb_santri.t_formal', $search_value);
			$this->db->or_like('tb_santri.k_formal', $search_value);
			$this->db->group_end();
		}

		// Handle ordering
		if (isset($order[0]['column']) && isset($columns[$order[0]['column']])) {
			$col_idx = intval($order[0]['column']);
			$dir = ($order[0]['dir'] === 'desc') ? 'desc' : 'asc';
			if ($col_idx > 0 && $col_idx < 5) {
				$this->db->order_by($columns[$col_idx], $dir);
			} else {
				$this->db->order_by('tb_santri.nama', 'asc');
			}
		} else {
			$this->db->order_by('tb_santri.nama', 'asc');
		}

		// Save query state for data count
		$temp_db = clone $this->db;
		$recordsFiltered = $temp_db->count_all_results();

		// Apply pagination
		$this->db->limit($length, $start);
		$query = $this->db->get();
		$data = $query->result();

		// Total records count (unfiltered)
		$recordsTotal = $this->db->count_all_results('tb_santri');

		// Format output for DataTables
		$tmpKos = array("", "Ny. Jamilah", "Gus Zaini", "Ny. Farihah", "Ny. Zahro", "Ny. Sa'adah", "Ny. Mamjudah", "Ny. Naily Z.", "Ny. Lathifah", "Ny. Ummi Kultsum", "K. Abdul Mukti");
		$ket_map = array("Bayar", "Ust/Usdtz", "Khaddam", "Gratis", "Berhenti", "Sakit");

		$output = [];
		$no = $start + 1;
		foreach ($data as $row) {
			$escaped_nama_pure = htmlspecialchars($row->nama, ENT_QUOTES);
			$status_badge = ($row->aktif === 'Y') 
				? ' <span class="badge bg-success small">Aktif</span>' 
				: ' <span class="badge bg-secondary small">Non-Aktif</span>';
			$display_nama = $escaped_nama_pure . $status_badge;

			if ($row->aktif === 'Y') {
				$toggle_btn = '
				<button type="button" class="btn btn-sm btn-outline-warning btn-toggle-status" 
						data-id="' . $row->id_santri . '" data-status="N" data-nama="' . $escaped_nama_pure . '">
					<i class="bx bx-power-off"></i> Nonaktifkan
				</button>';
			} else {
				$toggle_btn = '
				<button type="button" class="btn btn-sm btn-outline-success btn-toggle-status" 
						data-id="' . $row->id_santri . '" data-status="Y" data-nama="' . $escaped_nama_pure . '">
					<i class="bx bx-check-circle"></i> Aktifkan
				</button>';
			}

			$aksi_buttons = '
			<div class="d-flex align-items-center gap-2">
				<button type="button" class="btn btn-sm btn-outline-info btn-detail-siswa" 
						data-id="' . $row->id_santri . '">
					<i class="bx bx-info-circle"></i> Detail
				</button>
				<button type="button" class="btn btn-sm btn-outline-primary btn-edit-cost" 
						data-nis="' . $row->nis . '" 
						data-nama="' . $escaped_nama_pure . '" 
						data-costid="' . ($row->cost_id ?? '') . '">
					<i class="bx bx-edit-alt"></i> Edit Cust
				</button>
				<button type="button" class="btn btn-sm btn-outline-success btn-sync-siswa" 
						data-id="' . $row->id_santri . '">
					<i class="bx bx-sync"></i> Sync
				</button>
				' . $toggle_btn . '
				<a href="' . base_url('kasir/delete_santri/' . $row->id_santri) . '" 
				   class="btn btn-sm btn-outline-danger btn-delete-santri" 
				   data-nama="' . $escaped_nama_pure . '">
					<i class="bx bx-trash"></i> Hapus
				</a>
			</div>';

			$output[] = [
				'no' => $no++,
				'nis' => $row->nis ?? '-',
				'cost_id' => $row->cost_id ?? '-',
				'nama' => $display_nama,
				'kelas_formal' => ($row->k_formal ?? '') . ' ' . ($row->t_formal ?? ''),
				'tempat_kos' => $tmpKos[$row->t_kos] ?? '-',
				'status_ket' => (isset($row->ket) && is_numeric($row->ket) && isset($ket_map[$row->ket])) ? $ket_map[$row->ket] : '-',
				'aksi' => $aksi_buttons
			];
		}

		header('Content-Type: application/json');
		echo json_encode([
			'draw' => $draw,
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'data' => $output
		]);
		exit;
	}

	public function update_cost_id()
	{
		$nis = $this->input->post('nis', true);
		$cost_id = $this->input->post('cost_id', true);

		// Fetch student name to keep cost_name consistent
		$santri = $this->db->get_where('tb_santri', ['nis' => $nis])->row();
		$nama = $santri ? $santri->nama : '';

		// Check if record exists in cost table
		$existing = $this->db->get_where('cost', ['nis' => $nis])->row();

		if ($existing) {
			$this->db->where('nis', $nis);
			$this->db->update('cost', [
				'cost_id' => $cost_id,
				'cost_name' => $nama
			]);
		} else {
			$this->db->insert('cost', [
				'nis' => $nis,
				'cost_id' => $cost_id,
				'cost_name' => $nama
			]);
		}

		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Customer ID berhasil diperbarui');
		} else {
			$this->session->set_flashdata('error', 'Gagal memperbarui Customer ID');
		}

		redirect('kasir/santri');
	}

	public function sinkron_batch()
	{
		$token_row = $this->db->where('name', 'token_bearer')->get('settings')->row();
		$token = $token_row ? $token_row->val : '';

		$page = intval($this->input->get('page') ?? 1);
		if ($page === 1) {
			$this->session->unset_userdata('synced_uuids');
		}
		$per_page = 500; // Batch size

		$url = "https://data.ppdwk.com/api/datatables?data=referensi-peserta-didik"
			. "&page=" . $page
			. "&per_page=" . $per_page
			. "&sortby=nama"
			. "&sortbydesc=ASC";

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Authorization: Bearer ' . $token,
			'Accept: application/json'
		]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($httpCode !== 200) {
			header('Content-Type: application/json');
			echo json_encode([
				'status' => 'error',
				'message' => 'API request failed with HTTP code ' . $httpCode
			]);
			exit;
		}

		$result = json_decode($response, true);
		if (!$result || !isset($result['data']['data'])) {
			header('Content-Type: application/json');
			echo json_encode([
				'status' => 'error',
				'message' => 'Invalid response structure from API'
			]);
			exit;
		}

		$items = $result['data']['data'];
		$total_records = intval($result['data']['total'] ?? 0);
		$last_page = intval($result['data']['last_page'] ?? 1);

		// Store processed UUIDs in session for final cleanup
		$synced_uuids = $this->session->userdata('synced_uuids') ?: [];
		foreach ($items as $item) {
			if (!empty($item['peserta_didik_id'])) {
				$synced_uuids[] = $item['peserta_didik_id'];
			}
		}
		$this->session->set_userdata('synced_uuids', $synced_uuids);

		$processed = 0;
		foreach ($items as $item) {
			$data = [
				'santri_id' => $item['peserta_didik_id'] ?? null,
				'nama' => $item['nama'] ?? null,
				'nisn' => $item['nisn'] ?? null,
				'nik' => $item['nik'] ?? null,
				'no_kk' => $item['no_kk'] ?? null,
				'jkl' => $item['jenis_kelamin'] ?? null,
				'tempat' => $item['tempat_lahir'] ?? null,
				'tanggal' => $item['tanggal_lahir'] ?? null,
				'anak_ke' => !empty($item['anak_ke']) ? intval($item['anak_ke']) : null,
				'jml_sdr' => !empty($item['jml_sdr']) ? intval($item['jml_sdr']) : null,
				'jln' => $item['alamat'] ?? null,
				'rt' => $item['rt'] ?? null,
				'rw' => $item['rw'] ?? null,
				'desa' => $item['desa'] ?? null,
				'kec' => $item['kec'] ?? null,
				'kab' => $item['kab'] ?? null,
				'prov' => $item['prov'] ?? null,
				'kd_pos' => !empty($item['kode_pos']) ? intval($item['kode_pos']) : null,
				'nis' => $item['nis'] ?? null,
				'aktif' => 'Y'
			];

			$existing = null;
			if (!empty($item['peserta_didik_id'])) {
				$existing = $this->db->get_where('tb_santri', ['santri_id' => $item['peserta_didik_id']])->row();
			}
			if (!$existing && !empty($item['nis'])) {
				$existing = $this->db->get_where('tb_santri', ['nis' => $item['nis']])->row();
			}

			if ($existing) {
				$this->db->where('id_santri', $existing->id_santri);
				$this->db->update('tb_santri', $data);
			} else {
				$this->db->insert('tb_santri', $data);
			}
			$processed++;
		}

		header('Content-Type: application/json');
		echo json_encode([
			'status' => 'success',
			'page' => $page,
			'last_page' => $last_page,
			'processed' => $processed,
			'total' => $total_records
		]);
		exit;
	}

	public function clean_up_local_database()
	{
		$synced_uuids = $this->session->userdata('synced_uuids');
		if (!empty($synced_uuids)) {
			// Mark students as inactive if they have a UUID (santri_id) but are not in the synced list
			$this->db->where('santri_id IS NOT NULL');
			$this->db->where('santri_id !=', '');
			$this->db->where_not_in('santri_id', $synced_uuids);
			$this->db->update('tb_santri', ['aktif' => 'N']);
		}
		$this->session->unset_userdata('synced_uuids');

		header('Content-Type: application/json');
		echo json_encode(['status' => 'success', 'message' => 'Pembersihan data lokal berhasil.']);
		exit;
	}

	public function sinkron_lembaga_batch()
	{
		$token_row = $this->db->where('name', 'token_bearer')->get('settings')->row();
		$token = $token_row ? $token_row->val : '';

		$offset = intval($this->input->get('offset') ?? 0);
		$limit = 50; // Parallel request batch limit

		$students = $this->db->select('id_santri, santri_id')
			->from('tb_santri')
			->where('aktif', 'Y')
			->where('santri_id IS NOT NULL')
			->where('santri_id !=', '')
			->limit($limit, $offset)
			->get()
			->result();

		$total_students = $this->db->where('aktif', 'Y')
			->where('santri_id IS NOT NULL')
			->where('santri_id !=', '')
			->count_all_results('tb_santri');

		if (empty($students)) {
			header('Content-Type: application/json');
			echo json_encode([
				'status' => 'success',
				'offset' => $offset,
				'processed' => 0,
				'total' => $total_students
			]);
			exit;
		}

		$mh = curl_multi_init();
		$curls = [];

		foreach ($students as $student) {
			$uuid = $student->santri_id;
			$url = "https://data.ppdwk.com/api/pd/show/" . $uuid;

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, [
				'Authorization: Bearer ' . $token,
				'Accept: application/json'
			]);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);

			curl_multi_add_handle($mh, $ch);
			$curls[$student->id_santri] = $ch;
		}

		$running = null;
		do {
			curl_multi_exec($mh, $running);
		} while ($running);

		$processed = 0;
		foreach ($curls as $id_santri => $ch) {
			$response = curl_multi_getcontent($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_multi_remove_handle($mh, $ch);
			curl_close($ch);

			if ($httpCode === 200) {
				$result = json_decode($response, true);
				if ($result && isset($result['registrasi_pd'])) {
					$lembaga_nama = '';
					foreach ($result['registrasi_pd'] as $reg) {
						if (empty($reg['tanggal_keluar'])) {
							if (isset($reg['lembaga']['nama'])) {
								$lembaga_nama = $reg['lembaga']['nama'];
								break;
							}
						}
					}

					if (empty($lembaga_nama) && !empty($result['registrasi_pd'])) {
						$first_reg = $result['registrasi_pd'][0];
						if (isset($first_reg['lembaga']['nama'])) {
							$lembaga_nama = $first_reg['lembaga']['nama'];
						}
					}

					if (!empty($lembaga_nama)) {
						$desa = null;
						$kec = null;
						$kab = null;
						$prov = null;

						if (!empty($result['wilayah'])) {
							$w = $result['wilayah'];
							if ($w['level_wilayah'] == 4) {
								$desa = $w['nama'];
								$w = $w['parrent_recursive'] ?? null;
							}
							if ($w && $w['level_wilayah'] == 3) {
								$kec = $w['nama'];
								$w = $w['parrent_recursive'] ?? null;
							}
							if ($w && $w['level_wilayah'] == 2) {
								$kab = $w['nama'];
								$w = $w['parrent_recursive'] ?? null;
							}
							if ($w && $w['level_wilayah'] == 1) {
								$prov = $w['nama'];
							}
						}

						$pend_a = is_array($result['pendidikan_ayah'] ?? null) ? ($result['pendidikan_ayah']['nama'] ?? '') : ($result['jenjang_pendidikan_ayah'] ?? '');
						$pkj_a  = is_array($result['pekerjaan_ayah'] ?? null) ? ($result['pekerjaan_ayah']['nama'] ?? '') : ($result['pekerjaan_id_ayah'] ?? '');
						
						$pend_i = is_array($result['pendidikan_ibu'] ?? null) ? ($result['pendidikan_ibu']['nama'] ?? '') : ($result['jenjang_pendidikan_ibu'] ?? '');
						$pkj_i  = is_array($result['pekerjaan_ibu'] ?? null) ? ($result['pekerjaan_ibu']['nama'] ?? '') : ($result['pekerjaan_id_ibu'] ?? '');
						
						$pend_w = is_array($result['pendidikan_wali'] ?? null) ? ($result['pendidikan_wali']['nama'] ?? '') : ($result['jenjang_pendidikan_wali'] ?? '');
						$pkj_w  = is_array($result['pekerjaan_wali'] ?? null) ? ($result['pekerjaan_wali']['nama'] ?? '') : ($result['pekerjaan_id_wali'] ?? '');

						$update_data = [
							't_formal'  => $lembaga_nama,
							'jln'       => $result['alamat'] ?? null,
							'rt'        => $result['rt'] ?? null,
							'rw'        => $result['rw'] ?? null,
							'desa'      => $desa,
							'kec'       => $kec,
							'kab'       => $kab,
							'prov'      => $prov,
							'kd_pos'    => !empty($result['kodepos']) ? intval($result['kodepos']) : null,
							'hp'        => $result['telpon'] ?? null,
							'email'     => $result['email'] ?? null,
							'bapak'     => $result['nama_ayah'] ?? null,
							'nik_a'     => $result['nik_a'] ?? ($result['nik_ayah'] ?? null),
							'tempat_a'  => $result['tempat_lahir_ayah'] ?? null,
							'tanggal_a' => $result['tanggal_lahir_ayah'] ?? null,
							'pend_a'    => $pend_a,
							'pkj_a'     => $pkj_a,
							'ibu'       => $result['nama_ibu'] ?? null,
							'nik_i'     => $result['nik_i'] ?? ($result['nik_ibu'] ?? null),
							'tempat_i'  => $result['tempat_lahir_ibu'] ?? null,
							'tanggal_i' => $result['tanggal_lahir_ibu'] ?? null,
							'pend_i'    => $pend_i,
							'pkj_i'     => $pkj_i,
							'wali'      => $result['nama_wali'] ?? null,
							'nik_w'     => $result['nik_w'] ?? ($result['nik_wali'] ?? null),
							'tempat_w'  => $result['tempat_lahir_wali'] ?? null,
							'tanggal_w' => $result['tanggal_lahir_wali'] ?? null,
							'pend_w'    => $pend_w,
							'pkj_w'     => $pkj_w
						];

						$this->db->where('id_santri', $id_santri);
						$this->db->update('tb_santri', $update_data);
					}
				}
			}
			$processed++;
		}
		curl_multi_close($mh);

		header('Content-Type: application/json');
		echo json_encode([
			'status' => 'success',
			'offset' => $offset + $processed,
			'processed' => $processed,
			'total' => $total_students
		]);
		exit;
	}

	public function sinkron_siswa_single()
	{
		$id_santri = intval($this->input->get('id_santri'));
		$santri = $this->db->get_where('tb_santri', ['id_santri' => $id_santri])->row();
		if (!$santri || empty($santri->santri_id)) {
			header('Content-Type: application/json');
			echo json_encode(['status' => 'error', 'message' => 'Siswa tidak ditemukan atau UUID kosong']);
			exit;
		}

		$token_row = $this->db->where('name', 'token_bearer')->get('settings')->row();
		$token = $token_row ? $token_row->val : '';

		$url = "https://data.ppdwk.com/api/pd/show/" . $santri->santri_id;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Authorization: Bearer ' . $token,
			'Accept: application/json'
		]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($httpCode === 200) {
			$result = json_decode($response, true);
			if ($result && isset($result['registrasi_pd'])) {
				$lembaga_nama = '';
				foreach ($result['registrasi_pd'] as $reg) {
					if (empty($reg['tanggal_keluar'])) {
						if (isset($reg['lembaga']['nama'])) {
							$lembaga_nama = $reg['lembaga']['nama'];
							break;
						}
					}
				}
				if (empty($lembaga_nama) && !empty($result['registrasi_pd'])) {
					$first_reg = $result['registrasi_pd'][0];
					if (isset($first_reg['lembaga']['nama'])) {
						$lembaga_nama = $first_reg['lembaga']['nama'];
					}
				}

				if (!empty($lembaga_nama)) {
					$desa = null;
					$kec = null;
					$kab = null;
					$prov = null;

					if (!empty($result['wilayah'])) {
						$w = $result['wilayah'];
						if ($w['level_wilayah'] == 4) {
							$desa = $w['nama'];
							$w = $w['parrent_recursive'] ?? null;
						}
						if ($w && $w['level_wilayah'] == 3) {
							$kec = $w['nama'];
							$w = $w['parrent_recursive'] ?? null;
						}
						if ($w && $w['level_wilayah'] == 2) {
							$kab = $w['nama'];
							$w = $w['parrent_recursive'] ?? null;
						}
						if ($w && $w['level_wilayah'] == 1) {
							$prov = $w['nama'];
						}
					}

					$pend_a = is_array($result['pendidikan_ayah'] ?? null) ? ($result['pendidikan_ayah']['nama'] ?? '') : ($result['jenjang_pendidikan_ayah'] ?? '');
					$pkj_a  = is_array($result['pekerjaan_ayah'] ?? null) ? ($result['pekerjaan_ayah']['nama'] ?? '') : ($result['pekerjaan_id_ayah'] ?? '');
					
					$pend_i = is_array($result['pendidikan_ibu'] ?? null) ? ($result['pendidikan_ibu']['nama'] ?? '') : ($result['jenjang_pendidikan_ibu'] ?? '');
					$pkj_i  = is_array($result['pekerjaan_ibu'] ?? null) ? ($result['pekerjaan_ibu']['nama'] ?? '') : ($result['pekerjaan_id_ibu'] ?? '');
					
					$pend_w = is_array($result['pendidikan_wali'] ?? null) ? ($result['pendidikan_wali']['nama'] ?? '') : ($result['jenjang_pendidikan_wali'] ?? '');
					$pkj_w  = is_array($result['pekerjaan_wali'] ?? null) ? ($result['pekerjaan_wali']['nama'] ?? '') : ($result['pekerjaan_id_wali'] ?? '');

					$update_data = [
						'nama'      => $result['nama'] ?? null,
						'nisn'      => $result['nisn'] ?? null,
						'nik'       => $result['nik'] ?? null,
						'no_kk'     => $result['no_kk'] ?? null,
						'jkl'       => $result['jenis_kelamin'] ?? null,
						'tempat'    => $result['tempat_lahir'] ?? null,
						'tanggal'   => $result['tanggal_lahir'] ?? null,
						'anak_ke'   => !empty($result['anak_ke']) ? intval($result['anak_ke']) : null,
						'jml_sdr'   => !empty($result['jml_sdr']) ? intval($result['jml_sdr']) : null,
						'nis'       => $result['nis'] ?? null,
						't_formal'  => $lembaga_nama,
						'jln'       => $result['alamat'] ?? null,
						'rt'        => $result['rt'] ?? null,
						'rw'        => $result['rw'] ?? null,
						'desa'      => $desa,
						'kec'       => $kec,
						'kab'       => $kab,
						'prov'      => $prov,
						'kd_pos'    => !empty($result['kodepos']) ? intval($result['kodepos']) : null,
						'hp'        => $result['telpon'] ?? null,
						'email'     => $result['email'] ?? null,
						'bapak'     => $result['nama_ayah'] ?? null,
						'nik_a'     => $result['nik_a'] ?? ($result['nik_ayah'] ?? null),
						'tempat_a'  => $result['tempat_lahir_ayah'] ?? null,
						'tanggal_a' => $result['tanggal_lahir_ayah'] ?? null,
						'pend_a'    => $pend_a,
						'pkj_a'     => $pkj_a,
						'ibu'       => $result['nama_ibu'] ?? null,
						'nik_i'     => $result['nik_i'] ?? ($result['nik_ibu'] ?? null),
						'tempat_i'  => $result['tempat_lahir_ibu'] ?? null,
						'tanggal_i' => $result['tanggal_lahir_ibu'] ?? null,
						'pend_i'    => $pend_i,
						'pkj_i'     => $pkj_i,
						'wali'      => $result['nama_wali'] ?? null,
						'nik_w'     => $result['nik_w'] ?? ($result['nik_wali'] ?? null),
						'tempat_w'  => $result['tempat_lahir_wali'] ?? null,
						'tanggal_w' => $result['tanggal_lahir_wali'] ?? null,
						'pend_w'    => $pend_w,
						'pkj_w'     => $pkj_w
					];

					$this->db->where('id_santri', $id_santri);
					$this->db->update('tb_santri', $update_data);
					header('Content-Type: application/json');
					echo json_encode(['status' => 'success', 'message' => 'Lembaga dan profil berhasil disinkronkan', 'lembaga' => $lembaga_nama]);
					exit;
				}
			}
		} elseif ($httpCode === 404) {
			$this->db->where('id_santri', $id_santri);
			$this->db->update('tb_santri', ['aktif' => 'N']);
			header('Content-Type: application/json');
			echo json_encode(['status' => 'success', 'message' => 'Santri tidak ditemukan di pusat (404), dinonaktifkan secara lokal']);
			exit;
		}

		header('Content-Type: application/json');
		echo json_encode(['status' => 'error', 'message' => 'Gagal mengambil data dari API (HTTP ' . $httpCode . ')']);
		exit;
	}

	public function toggle_santri_status()
	{
		$id_santri = intval($this->input->post('id_santri'));
		$status = $this->input->post('status');

		if (!$id_santri || !in_array($status, ['Y', 'N'])) {
			header('Content-Type: application/json');
			echo json_encode(['status' => 'error', 'message' => 'Data input tidak valid.']);
			exit;
		}

		$this->db->where('id_santri', $id_santri);
		$this->db->update('tb_santri', ['aktif' => $status]);

		header('Content-Type: application/json');
		if ($this->db->affected_rows() > 0) {
			echo json_encode(['status' => 'success', 'message' => 'Status santri berhasil diubah.']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Gagal mengubah status santri atau status tidak berubah.']);
		}
		exit;
	}

	public function delete_santri($id_santri)
	{
		$this->db->where('id_santri', $id_santri);
		$this->db->delete('tb_santri');

		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Data Santri berhasil dihapus');
		} else {
			$this->session->set_flashdata('error', 'Gagal menghapus data santri');
		}
		redirect('kasir/santri');
	}

	public function get_student_detail_ajax($id_santri)
	{
		$id_santri = intval($id_santri);
		$santri = $this->db->get_where('tb_santri', ['id_santri' => $id_santri])->row_array();
		if (!$santri) {
			header('Content-Type: application/json');
			echo json_encode(['status' => 'error', 'message' => 'Santri tidak ditemukan']);
			exit;
		}

		// Map kos and keterangan names
		$tmpKos = array("", "Ny. Jamilah", "Gus Zaini", "Ny. Farihah", "Ny. Zahro", "Ny. Sa'adah", "Ny. Mamjudah", "Ny. Naily Z.", "Ny. Lathifah", "Ny. Ummi Kultsum", "K. Abdul Mukti");
		$ket_map = array("Bayar", "Ust/Usdtz", "Khaddam", "Gratis", "Berhenti", "Sakit");

		$santri['tempat_kos_name'] = $tmpKos[$santri['t_kos']] ?? '-';
		$santri['status_ket_name'] = (isset($santri['ket']) && is_numeric($santri['ket']) && isset($ket_map[$santri['ket']])) ? $ket_map[$santri['ket']] : '-';

		header('Content-Type: application/json');
		echo json_encode(['status' => 'success', 'data' => $santri]);
		exit;
	}

	public function get_total_active_santri()
	{
		$total = $this->db->count_all_results('tb_santri');
		header('Content-Type: application/json');
		echo json_encode(['total' => $total]);
		exit;
	}

	public function kirim_data_santri_batch()
	{
		$offset = intval($this->input->get('offset') ?? 0);
		$limit = 200;

		// Fetch a batch of all students from local database (db_sentral)
		$this->db->select('*');
		$this->db->from('tb_santri');
		$this->db->order_by('id_santri', 'ASC');
		$this->db->limit($limit, $offset);
		$query = $this->db->get();
		$chunk = $query->result_array();

		if (empty($chunk)) {
			header('Content-Type: application/json');
			echo json_encode([
				'status' => 'success',
				'processed' => 0,
				'offset' => $offset,
				'message' => 'Selesai.'
			]);
			exit;
		}

		// Load both target databases
		$db_kasir = $this->load->database('kasir', TRUE);
		$db_dekos = $this->load->database('dekos', TRUE);

		// Get target columns dynamically to prevent "unknown column" errors
		$fields_kasir = $db_kasir->list_fields('tb_santri');
		$fields_dekos = $db_dekos->list_fields('tb_santri');

		// Helper function to upsert a chunk into a target database object with column filtering
		$upsert_to_db = function($db_conn, $data_chunk, $target_fields) {
			$db_conn->trans_start();

			// Filter columns
			$filtered_chunk = [];
			foreach ($data_chunk as $row) {
				$filtered_row = [];
				foreach ($target_fields as $field) {
					if ($field === 'id_santri') {
						continue; // skip auto_increment primary key for both target databases
					}
					if (array_key_exists($field, $row)) {
						$filtered_row[$field] = $row[$field];
					}
				}
				if (!empty($filtered_row)) {
					$filtered_chunk[] = $filtered_row;
				}
			}

			if (empty($filtered_chunk)) {
				$db_conn->trans_complete();
				return TRUE;
			}

			// For both, match by nis
			$nis_list = array_column($filtered_chunk, 'nis');
			$existing_nis = [];
			if (!empty($nis_list)) {
				$existing = $db_conn->select('nis')
					->where_in('nis', $nis_list)
					->get('tb_santri')
					->result_array();
				$existing_nis = array_column($existing, 'nis');
			}

			$inserts = [];
			$updates = [];
			foreach ($filtered_chunk as $row) {
				if (empty($row['nis'])) {
					continue; // Skip student record if NIS is empty
				}
				if (in_array($row['nis'], $existing_nis)) {
					$updates[] = $row;
				} else {
					$inserts[] = $row;
				}
			}

			if (!empty($inserts)) {
				$res = $db_conn->insert_batch('tb_santri', $inserts);
				if ($res === FALSE || $db_conn->trans_status() === FALSE) {
					$err = $db_conn->error();
					$db_conn->trans_complete();
					return $err;
				}
			}
			if (!empty($updates)) {
				$res = $db_conn->update_batch('tb_santri', $updates, 'nis');
				if ($res === FALSE || $db_conn->trans_status() === FALSE) {
					$err = $db_conn->error();
					$db_conn->trans_complete();
					return $err;
				}
			}

			$db_conn->trans_complete();
			if ($db_conn->trans_status() === FALSE) {
				return $db_conn->error();
			}
			return TRUE;
		};

		// Run upsert for Kasir
		$status_kasir = $upsert_to_db($db_kasir, $chunk, $fields_kasir);
		// Run upsert for Dekos
		$status_dekos = $upsert_to_db($db_dekos, $chunk, $fields_dekos);

		if ($status_kasir !== TRUE || $status_dekos !== TRUE) {
			$details = '';
			if ($status_kasir !== TRUE) {
				$details .= 'Kasir DB Error: [' . ($status_kasir['code'] ?? '') . '] ' . ($status_kasir['message'] ?? '') . '; ';
			}
			if ($status_dekos !== TRUE) {
				$details .= 'Dekos DB Error: [' . ($status_dekos['code'] ?? '') . '] ' . ($status_dekos['message'] ?? '') . '; ';
			}

			header('Content-Type: application/json');
			echo json_encode([
				'status' => 'error',
				'message' => 'Gagal menulis data ke database Kasir atau Dekos. Detail: ' . trim($details)
			]);
			exit;
		}

		header('Content-Type: application/json');
		echo json_encode([
			'status' => 'success',
			'processed' => count($chunk),
			'offset' => $offset + count($chunk)
		]);
		exit;
	}

	public function clean_up_target_databases()
	{
		// Fetch all active NIS from local database
		$all_active_local = $this->db->select('nis')
			->where('aktif', 'Y')
			->get('tb_santri')
			->result_array();
		$local_nis_list = array_column($all_active_local, 'nis');

		if (empty($local_nis_list)) {
			header('Content-Type: application/json');
			echo json_encode(['status' => 'success', 'message' => 'Tidak ada data dibersihkan.']);
			exit;
		}

		// Load databases
		$db_kasir = $this->load->database('kasir', TRUE);
		$db_dekos = $this->load->database('dekos', TRUE);

		// Safe soft delete using chunks of where_not_in
		$db_kasir->trans_start();
		$db_kasir->where_not_in('nis', $local_nis_list)->update('tb_santri', ['aktif' => 'N']);
		$db_kasir->trans_complete();

		$db_dekos->trans_start();
		$db_dekos->where_not_in('nis', $local_nis_list)->update('tb_santri', ['aktif' => 'N']);
		$db_dekos->trans_complete();

		header('Content-Type: application/json');
		echo json_encode(['status' => 'success', 'message' => 'Pembersihan data selesai.']);
		exit;
	}

	public function dekos()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['bulan_cal'] = $this->bulan;
		$data['controller'] = 'kasir';

		// Boarding houses array
		$data['tmpKos'] = array("", "Ny. Jamilah", "Gus Zaini", "Ny. Farihah", "Ny. Zahro", "Ny. Sa'adah", "Ny. Mamjudah", "Ny. Naily Z.", "Ny. Lathifah", "Ny. Ummi Kultsum", "K. Abdul Mukti");

		// Get lembaga list
		$data['lembaga'] = $this->db->get_where('lembaga', ['tahun' => $this->tahun])->result();

		// Query count of active students for each t_kos index
		$counts = $this->db->select('t_kos, COUNT(*) as total')
			->from('tb_santri')
			->where('aktif', 'Y')
			->group_by('t_kos')
			->get()
			->result_array();

		$rekap = array_fill(0, count($data['tmpKos']), 0);
		foreach ($counts as $row) {
			$t_kos = (int)$row['t_kos'];
			if (isset($rekap[$t_kos])) {
				$rekap[$t_kos] = (int)$row['total'];
			}
		}
		$data['rekap'] = $rekap;

		$this->load->view('kasir/head', $data);
		$this->load->view('admin/dekos', $data);
		$this->load->view('kasir/foot');
	}

	public function dekos_history_ajax($nis)
	{
		$this->db->select('dekos.*, tb_santri.nama');
		$this->db->from('dekos');
		$this->db->join('tb_santri', 'dekos.nis = tb_santri.nis', 'left');
		$this->db->where('dekos.nis', $nis);
		$this->db->order_by('dekos.masuk', 'asc');
		$query = $this->db->get();
		$data = $query->result();

		$tmpKos = array("", "Ny. Jamilah", "Gus Zaini", "Ny. Farihah", "Ny. Zahro", "Ny. Sa'adah", "Ny. Mamjudah", "Ny. Naily Z.", "Ny. Lathifah", "Ny. Ummi Kultsum", "K. Abdul Mukti");

		$output = [];
		$no = 1;
		foreach ($data as $row) {
			$nama_kos = $tmpKos[$row->t_kos] ?? ('Kos ' . $row->t_kos);
			$output[] = [
				'no' => $no++,
				'id_dekos' => $row->id_dekos,
				'nis' => $row->nis,
				'masuk' => $row->masuk ?? '-',
				'keluar' => $row->keluar ?? '-',
				't_kos' => $row->t_kos,
				'tempat_kos' => $nama_kos,
				'aksi' => '
				<div class="d-flex align-items-center gap-2">
					<button type="button" class="btn btn-sm btn-outline-warning btn-edit-dekos" 
							data-id="' . $row->id_dekos . '" 
							data-tkos="' . $row->t_kos . '" 
							data-masuk="' . htmlspecialchars($row->masuk ?? '', ENT_QUOTES) . '" 
							data-keluar="' . htmlspecialchars($row->keluar ?? '', ENT_QUOTES) . '">
						<i class="bx bx-edit-alt"></i> Edit
					</button>
					<button type="button" class="btn btn-sm btn-outline-danger btn-delete-dekos" data-id="' . $row->id_dekos . '">
						<i class="bx bx-trash"></i> Hapus
					</button>
				</div>'
			];
		}

		header('Content-Type: application/json');
		echo json_encode(['data' => $output]);
		exit;
	}

	public function get_student_info_ajax($nis)
	{
		$student = $this->db->get_where('tb_santri', ['nis' => $nis])->row();
		header('Content-Type: application/json');
		echo json_encode($student);
		exit;
	}

	public function add_dekos()
	{
		$nis = $this->input->post('nis', true);
		$t_kos = $this->input->post('t_kos', true);
		$tanggal_pindah = $this->input->post('tanggal_pindah', true);

		if (empty($nis) || empty($t_kos) || empty($tanggal_pindah)) {
			echo json_encode(['status' => 'error', 'message' => 'NIS, Tempat Kos Baru, dan Tanggal Pindah tidak boleh kosong']);
			exit;
		}

		$this->db->trans_start();

		// Find the latest boarding record for this student
		$latest = $this->db->select('*')
			->from('dekos')
			->where('nis', $nis)
			->order_by('id_dekos', 'DESC')
			->limit(1)
			->get()
			->row();

		if ($latest) {
			// Update previous record's exit date to the relocation date
			$this->db->where('id_dekos', $latest->id_dekos);
			$this->db->update('dekos', ['keluar' => $tanggal_pindah]);
		}

		// Insert new record
		$data = [
			'nis' => $nis,
			't_kos' => $t_kos,
			'masuk' => $tanggal_pindah,
			'keluar' => '0000-00-00'
		];
		$this->db->insert('dekos', $data);

		// Update current boarding house in student master table
		$this->db->where('nis', $nis);
		$this->db->update('tb_santri', ['t_kos' => $t_kos]);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			echo json_encode(['status' => 'error', 'message' => 'Gagal memproses pemindahan tempat dekos']);
		} else {
			echo json_encode(['status' => 'success', 'message' => 'Proses pemindahan tempat dekos berhasil disimpan']);
		}
		exit;
	}

	public function edit_dekos()
	{
		$id_dekos = $this->input->post('id_dekos', true);
		$t_kos = $this->input->post('t_kos', true);
		$masuk = $this->input->post('masuk', true);
		$keluar = $this->input->post('keluar', true);

		if (empty($id_dekos) || empty($t_kos)) {
			echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
			exit;
		}

		$data = [
			't_kos' => $t_kos,
			'masuk' => $masuk ?: '0000-00-00',
			'keluar' => $keluar ?: '0000-00-00'
		];

		$this->db->where('id_dekos', $id_dekos);
		$this->db->update('dekos', $data);

		echo json_encode(['status' => 'success', 'message' => 'Riwayat dekosan berhasil diperbaharui']);
		exit;
	}

	public function delete_dekos($id_dekos)
	{
		$this->db->where('id_dekos', $id_dekos);
		$this->db->delete('dekos');

		if ($this->db->affected_rows() > 0) {
			echo json_encode(['status' => 'success', 'message' => 'Riwayat dekosan berhasil dihapus']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus riwayat dekosan']);
		}
		exit;
	}

	public function select2_santri_ajax()
	{
		$search = $this->input->get('q', true) ?? '';

		$this->db->select('nis, nama, k_formal, t_formal, k_madin, r_madin, komplek, kamar, ket');
		$this->db->from('tb_santri');
		$this->db->where('aktif', 'Y');

		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('nis', $search);
			$this->db->or_like('nama', $search);
			$this->db->group_end();
		}

		$this->db->limit(30);
		$query = $this->db->get();
		$results = [];

		foreach ($query->result() as $row) {
			$results[] = [
				'id' => $row->nis,
				'text' => $row->nis . ' - ' . $row->nama,
				'nama' => $row->nama,
				'kelas_formal' => ($row->k_formal ?? '') . ' ' . ($row->t_formal ?? ''),
				'k_madin' => $row->k_madin ?? '',
				'r_madin' => $row->r_madin ?? '',
				'kamar' => $row->kamar ?? '-',
				'komplek' => $row->komplek ?? '-',
				'ket' => $row->ket ?? '0'
			];
		}

		header('Content-Type: application/json');
		echo json_encode(['results' => $results]);
		exit;
	}

	public function update_student_ket_ajax()
	{
		$nis = $this->input->post('nis', true);
		$ket = $this->input->post('ket', true);

		if (empty($nis) || $ket === null || $ket === '') {
			echo json_encode(['status' => 'error', 'message' => 'NIS dan Keterangan tidak boleh kosong']);
			exit;
		}

		$this->db->where('nis', $nis);
		$this->db->update('tb_santri', ['ket' => $ket]);

		if ($this->db->affected_rows() >= 0) {
			echo json_encode(['status' => 'success', 'message' => 'Status keterangan santri berhasil diperbaharui']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Gagal memperbaharui status keterangan santri']);
		}
		exit;
	}

	public function dekos_santri_ajax()
	{
		$is_loaded = intval($this->input->post('is_loaded', true));
		$draw = intval($this->input->post('draw', true));

		if ($is_loaded !== 1) {
			header('Content-Type: application/json');
			echo json_encode([
				'draw' => $draw,
				'recordsTotal' => 0,
				'recordsFiltered' => 0,
				'data' => []
			]);
			exit;
		}

		$start = intval($this->input->post('start', true));
		$length = intval($this->input->post('length', true));
		$search = $this->input->post('search', true)['value'] ?? '';

		$filter_t_kos = $this->input->post('t_kos', true);
		$filter_keterangan = $this->input->post('keterangan', true);

		$this->db->select('tb_santri.*');
		$this->db->from('tb_santri');
		$this->db->where('tb_santri.aktif', 'Y');

		// Apply filters
		if ($filter_t_kos !== null && $filter_t_kos !== '') {
			$this->db->where('tb_santri.t_kos', $filter_t_kos);
		}
		if ($filter_keterangan !== null && $filter_keterangan !== '') {
			$this->db->where('tb_santri.ket', $filter_keterangan);
		}

		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('tb_santri.nis', $search);
			$this->db->or_like('tb_santri.nama', $search);
			$this->db->group_end();
		}

		// Count filtered records
		$temp_db = clone $this->db;
		$recordsFiltered = $temp_db->count_all_results();

		// Apply pagination
		$this->db->limit($length, $start);
		$query = $this->db->get();
		$data = $query->result();

		// Total records count (unfiltered)
		$recordsTotal = $this->db->where('aktif', 'Y')->count_all_results('tb_santri');

		// Mappings
		$tmpKos = array("", "Ny. Jamilah", "Gus Zaini", "Ny. Farihah", "Ny. Zahro", "Ny. Sa'adah", "Ny. Mamjudah", "Ny. Naily Z.", "Ny. Lathifah", "Ny. Ummi Kultsum", "K. Abdul Mukti");
		$ket_map = array("Bayar", "Ust/Usdtz", "Khaddam", "Gratis", "Berhenti", "Sakit");

		$output = [];
		$no = $start + 1;
		foreach ($data as $row) {
			$escaped_nama = htmlspecialchars($row->nama, ENT_QUOTES);
			$output[] = [
				'no' => $no++,
				'nis' => $row->nis ?? '-',
				'nama' => $row->nama,
				'lembaga' => ($row->k_formal ?? '') . ' ' . ($row->t_formal ?? ''),
				'tempat_kos' => $tmpKos[$row->t_kos] ?? '-',
				'status_ket' => (isset($row->ket) && is_numeric($row->ket) && isset($ket_map[$row->ket])) ? $ket_map[$row->ket] : '-',
				'aksi' => '<button type="button" class="btn btn-xs btn-primary btn-pilih-santri-dekos" data-nis="' . $row->nis . '" data-nama="' . $escaped_nama . '"><i class="bx bx-check"></i> Pilih</button>'
			];
		}

		header('Content-Type: application/json');
		echo json_encode([
			'draw' => $draw,
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'data' => $output
		]);
		exit;
	}

	public function get_rekap_dekos_ajax()
	{
		$tmpKos = array("", "Ny. Jamilah", "Gus Zaini", "Ny. Farihah", "Ny. Zahro", "Ny. Sa'adah", "Ny. Mamjudah", "Ny. Naily Z.", "Ny. Lathifah", "Ny. Ummi Kultsum", "K. Abdul Mukti");
		$counts = $this->db->select('t_kos, COUNT(*) as total')
			->from('tb_santri')
			->where('aktif', 'Y')
			->group_by('t_kos')
			->get()
			->result_array();

		$rekap = array_fill(0, count($tmpKos), 0);
		foreach ($counts as $row) {
			$t_kos = (int)$row['t_kos'];
			if (isset($rekap[$t_kos])) {
				$rekap[$t_kos] = (int)$row['total'];
			}
		}

		header('Content-Type: application/json');
		echo json_encode(['rekap' => $rekap]);
		exit;
	}

	public function get_kos_students_ajax($t_kos)
	{
		$t_kos = intval($t_kos);
		$this->db->select('nis, nama, t_kos, ket, k_formal, t_formal');
		$this->db->from('tb_santri');
		$this->db->where('aktif', 'Y');
		$this->db->where('t_kos', $t_kos);
		$query = $this->db->get();
		$data = $query->result();

		$ket_map = array("Bayar", "Ust/Usdtz", "Khaddam", "Gratis", "Berhenti", "Sakit");

		$output = [];
		$no = 1;
		foreach ($data as $row) {
			$escaped_nama = htmlspecialchars($row->nama, ENT_QUOTES);
			$output[] = [
				'no' => $no++,
				'nis' => $row->nis ?? '-',
				'nama' => htmlspecialchars($row->nama),
				'lembaga' => htmlspecialchars(($row->k_formal ?? '') . ' ' . ($row->t_formal ?? '')),
				'status_ket' => (isset($row->ket) && is_numeric($row->ket) && isset($ket_map[$row->ket])) ? $ket_map[$row->ket] : '-',
				'aksi' => '<button type="button" class="btn btn-xs btn-primary btn-pilih-dari-rekap" data-nis="' . $row->nis . '" data-nama="' . $escaped_nama . '"><i class="bx bx-check"></i> Pilih</button>'
			];
		}

		header('Content-Type: application/json');
		echo json_encode(['data' => $output]);
		exit;
	}
}
