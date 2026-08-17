<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rab extends CI_Controller
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

		if (!$this->Auth_model->current_user()) {
			redirect('login/logout');
		}
	}

	public function index()
	{
		$kode = $this->lembaga;
		$data['data'] = $this->model->getBy2('rab', 'lembaga', $kode, 'tahun', $this->tahun)->result();
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $kode, 'tahun', $this->tahun)->row();

		$data['totalRab'] = $this->model->getBySum2('rab', 'lembaga', $this->lembaga, 'tahun', $this->tahun, 'total')->row();
		$data['totalReal'] = $this->model->getBySum2('realis', 'lembaga', $this->lembaga, 'tahun', $this->tahun, 'nominal')->row();

		$data['jenis'] = $this->model->getBy('jenis', 'tahun', $this->tahun)->result();
		foreach ($data['jenis'] as $jns) {
			$kodeJenis = $jns->kode_jns;
			$data['rabJml'][$kodeJenis] = $this->model->getBySum3('rab', 'lembaga', $kode, 'tahun', $this->tahun, 'jenis', $kodeJenis, 'total')->row();
		}

		$selected_bulan = $this->input->get('bulan', true);
		$data['selected_bulan'] = $selected_bulan;

		$program = [];
		$this->db->where('lembaga', $kode);
		$this->db->where('tahun', $this->tahun);
		if (!empty($selected_bulan)) {
			$this->db->where("FIND_IN_SET(" . intval($selected_bulan) . ", REPLACE(bulan, ' ', '')) >", 0);
		}
		$dataprogram = $this->db->get('dppk')->result();

		foreach ($dataprogram as $dtpr) {
			$total = $this->db->query("SELECT SUM(total) as total FROM rab WHERE lembaga = '$kode' AND tahun = '$this->tahun' AND id_dppk = '$dtpr->id_dppk' GROUP BY id_dppk ")->row();
			$kdprog = $dtpr->id_dppk;
			$nomProg = $this->db->query("SELECT SUM(total) AS total FROM rab WHERE id_dppk = '$kdprog' AND tahun = '$this->tahun' AND lembaga = '$kode' ")->row();
			$nomPakai = $this->db->query("SELECT SUM(nominal) AS total FROM realis WHERE kode LIKE '%-$kdprog-%' AND tahun = '$this->tahun' AND lembaga = '$kode' ")->row();
			$nomSm = $this->db->query("SELECT SUM(nominal) AS total FROM real_sm WHERE kode LIKE '%-$kdprog-%' AND tahun = '$this->tahun' AND lembaga = '$kode' ")->row();
			$sisa = ($nomProg->total ?? 0) - (($nomPakai->total ?? 0) + ($nomSm->total ?? 0));

			$rab_items = $this->model->getBy3('rab', 'id_dppk', $dtpr->id_dppk, 'tahun', $this->tahun, 'lembaga', $kode)->result();

			$program[] = [
				'program' => $dtpr->program,
				'kode_program' => $dtpr->kode_program,
				'kegiatan' => $dtpr->kegiatan,
				'bulan' => $dtpr->bulan,
				'total' => $total ? $total->total : 0,
				'sisa' => $sisa ? $sisa : 0,
				'id_dppk' => $dtpr->id_dppk,
				'rab_items' => $rab_items
			];
		}
		$data['program'] = $program;

		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;

		$this->load->view('lembaga/head', $data);
		$this->load->view('lembaga/rabDetail', $data);
		$this->load->view('lembaga/foot');
	}

	public function realis()
	{
		$lembaga = $this->lembaga;
		$data['data'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
		$data['tahun'] = $this->tahun;
		$data['lembaga'] = $this->model->getBy('lembaga', 'kode', $lembaga)->row();

		$datajenis = $this->model->getBy('jenis', 'tahun', $this->tahun)->result();
		$datakirim = [];
		foreach ($datajenis as $jns) {
			$kodeJenis = $jns->kode_jns;
			$rabJml = $this->model->getBySum3('rab', 'lembaga', $lembaga, 'tahun', $this->tahun, 'jenis', $kodeJenis, 'total')->row();
			$pakaiJml = $this->model->getBySum3('realis', 'lembaga', $lembaga, 'tahun', $this->tahun, 'jenis', $kodeJenis, 'nominal')->row();
			$datakirim[] = [
				'kode_jns' => $jns->kode_jns,
				'nama' => $jns->nama,
				'rabJml' => $rabJml->jml3,
				'pakaiJml' => $pakaiJml->jml3,
			];
		}
		$data['jenis'] = $datakirim;
		$data['rabA'] = $this->model->getBy2('rab', 'lembaga', $lembaga, 'tahun', $this->tahun)->result();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;

		$this->load->view('realDetail', $data);
	}
	public function cekRealis($kode)
	{
		$data['rab'] = $this->model->getBy('rab', 'kode', $kode)->row();
		$data['lem'] = $this->model->getBy2('lembaga', 'kode', $data['rab']->lembaga, 'tahun', $this->tahun)->row();
		$data['rel'] = $this->model->getBySum('realis', 'kode', $kode, 'nominal')->row();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['relData'] = $this->model->getBy('realis', 'kode', $kode)->result();

		$this->load->view('cekRab', $data);
	}

	public function kegiatanDetail($id_dppk)
	{
		$lembaga = $this->lembaga;
		$tahun = $this->tahun;

		// 1. Program details
		$data['dppk'] = $this->model->getBy3('dppk', 'id_dppk', $id_dppk, 'tahun', $tahun, 'lembaga', $lembaga)->row();

		// 2. RAB details
		$data['rab'] = $this->model->getBy3('rab', 'id_dppk', $id_dppk, 'tahun', $tahun, 'lembaga', $lembaga)->result();
		$data['totalRab'] = $this->db->query("SELECT SUM(total) as total FROM rab WHERE id_dppk = '$id_dppk' AND tahun = '$tahun' AND lembaga = '$lembaga' ")->row();

		// 3. Usage details (pemakaian)
		$data['realis'] = $this->db->query("SELECT * FROM realis WHERE kode LIKE '%-$id_dppk-%' AND tahun = '$tahun' AND lembaga = '$lembaga'")->result();
		$data['real_sm'] = $this->db->query("SELECT * FROM real_sm WHERE kode LIKE '%-$id_dppk-%' AND tahun = '$tahun' AND lembaga = '$lembaga'")->result();

		$nomPakai = $this->db->query("SELECT SUM(nominal) AS total FROM realis WHERE kode LIKE '%-$id_dppk-%' AND tahun = '$tahun' AND lembaga = '$lembaga' ")->row();
		$nomSm = $this->db->query("SELECT SUM(nominal) AS total FROM real_sm WHERE kode LIKE '%-$id_dppk-%' AND tahun = '$tahun' AND lembaga = '$lembaga' ")->row();

		$data['totalPakai'] = ($nomPakai->total ?? 0) + ($nomSm->total ?? 0);

		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;

		$this->load->view('lembaga/head', $data);
		$this->load->view('lembaga/kegiatanDetail', $data);
		$this->load->view('lembaga/foot');
	}
}
