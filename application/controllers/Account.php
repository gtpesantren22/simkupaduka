<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \Mpdf\Mpdf;

class Account extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('AdminModel', 'model');
		$this->load->model('AppModel', 'modelAll');
		$this->load->model('Auth_model');

		$this->db5 = $this->load->database('nikmus', true);
		$this->db2 = $this->load->database('dekos', true);
		$this->db6 = $this->load->database('psb', true);

		$user = $this->Auth_model->current_user();
		$this->tahun = $this->session->userdata('tahun');
		// $this->jenis = ['A. Belanja Barang', 'B. Langganan & Jasa', 'Belanja Kegiatan', 'D. Umum'];
		$this->bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

		$api = $this->model->apiKey()->row();
		$this->apiKey = $api->nama_key;
		$this->user = $user->nama;
		$this->lembaga = $user->lembaga;

		if ((!$this->Auth_model->current_user() && $user->level != 'account') || (!$this->Auth_model->current_user() && $user->level != 'admin')) {
			redirect('login/logout');
		}
	}

	public function index()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;

		$data['dekos'] = $this->model->getDekosSum($this->tahun)->row();
		$data['nikmus'] = $this->model->getNikmusSum($this->tahun)->row();

		$data['cadangan'] = $this->modelAll->cadangan($this->tahun);

		$data['masuk'] = $this->modelAll->masuk($this->tahun);
		$data['keluar'] = $this->modelAll->keluar($this->tahun);

		$data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");
		$data['pakData'] = $this->model->getBy2('pak', 'tahun', $this->tahun, 'status', 'proses');

		$data['saldo'] = $this->model->getBy2('saldo', 'name', 'bank', 'tahun', $data['tahun']);
		$data['cash'] = $this->model->getBy2('saldo', 'name', 'cash', 'tahun', $data['tahun']);

		$this->load->view('account/head', $data);
		$this->load->view('account/index', $data);
		$this->load->view('account/foot');
	}

	public function upload_config($path)
	{
		if (!is_dir($path))
			mkdir($path, 0777, TRUE);
		$config['upload_path'] 		= './' . $path;
		$config['allowed_types'] 	= 'csv|CSV|xlsx|XLSX|xls|XLS';
		$config['max_filename']	 	= '255';
		$config['encrypt_name'] 	= TRUE;
		$config['max_size'] 		= 4096;
		$this->load->library('upload', $config);
	}

	public function pesantren()
	{
		$data['pes'] = $this->model->getBy('pesantren', 'tahun', $this->tahun)->result();
		$data['sumPes'] = $this->model->selectSum('pesantren', 'nominal', 'tahun', $this->tahun)->row();
		$data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
		$data['tahunData'] = $this->model->getAll('tahun')->result();
		$data['bidang'] = $this->model->getBy('bidang', 'tahun', $this->tahun)->result();
		$data['user'] = $this->Auth_model->current_user();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");
		$data['tahun'] = $this->tahun;
		$this->load->view('account/head', $data);
		$this->load->view('account/masukPes', $data);
		$this->load->view('account/foot');
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
			redirect('account/pesantren');
		} else {
			$this->session->set_flashdata('error', 'Input Pemasukan Pesantren Gagal');
			redirect('account/pesantren');
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
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");
		$data['tahun'] = $this->tahun;
		$this->load->view('account/head', $data);
		$this->load->view('account/editPes', $data);
		$this->load->view('account/foot');
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
			redirect('account/pesantren');
		} else {
			$this->session->set_flashdata('error', 'Edit Pemasukan Pesantren Gagal');
			redirect('account/pesantren');
		}
	}

	public function delPes($id)
	{
		$this->model->delete('pesantren', 'id_pes', $id);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Hapus Pemasukan Pesantren Berhasil');
			redirect('account/pesantren');
		} else {
			$this->session->set_flashdata('error', 'Hapus Pemasukan Pesantren Gagal');
			redirect('account/pesantren');
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
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");
		$data['tahun'] = $this->tahun;
		$this->load->view('account/head', $data);
		$this->load->view('account/masukBos', $data);
		$this->load->view('account/foot');
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
			redirect('account/bos');
		} else {
			$this->session->set_flashdata('error', 'Input Pemasukan BOS Gagal');
			redirect('account/bos');
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
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");
		$data['tahun'] = $this->tahun;

		$this->load->view('account/head', $data);
		$this->load->view('account/editBos', $data);
		$this->load->view('account/foot');
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
			redirect('account/bos');
		} else {
			$this->session->set_flashdata('error', 'Edit Pemasukan Bos Gagal');
			redirect('account/bos');
		}
	}

	public function delBos($id)
	{
		$this->model->delete('bos', 'id_bos', $id);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Hapus Pemasukan Bos Berhasil');
			redirect('account/bos');
		} else {
			$this->session->set_flashdata('error', 'Hapus Pemasukan Bos Gagal');
			redirect('account/bos');
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
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");
		$data['tahun'] = $this->tahun;
		$this->load->view('account/head', $data);
		$this->load->view('account/masukBp', $data);
		$this->load->view('account/foot');
	}

	public function rab()
	{
		$data['data'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
		// $data['sumBp'] = $this->model->selectSum('pembayaran', 'nominal', 'tahun', $this->tahun)->row();
		$data['user'] = $this->Auth_model->current_user();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");
		$data['tahun'] = $this->tahun;
		$this->load->view('account/head', $data);
		$this->load->view('account/rab', $data);
		$this->load->view('account/foot');
	}

	public function rabDetail($kode)
	{
		// $data['data'] = $this->model->getBy2('rab', 'lembaga', $kode, 'tahun', $this->tahun)->result();
		$data['data'] = $this->db->query("SELECT rab.*, rab_sm24.kegiatan, dppk.program FROM rab JOIN rab_sm24 ON rab.kode=rab_sm24.kode JOIN dppk ON rab_sm24.kode_pak=dppk.id_dppk WHERE rab.lembaga = '$kode' AND rab.tahun = '$this->tahun' ")->result();

		$data['lembaga'] = $this->model->getBy('lembaga', 'kode', $kode)->row();

		// $data['sumA'] = $this->model->getTotalRabJenis('A', $kode, $this->tahun)->row();
		// $data['sumB'] = $this->model->getTotalRabJenis('B', $kode, $this->tahun)->row();
		// $data['sumC'] = $this->model->getTotalRabJenis('C', $kode, $this->tahun)->row();
		// $data['sumD'] = $this->model->getTotalRabJenis('D', $kode, $this->tahun)->row();


		$data['totalRab'] = $this->model->getBySum2('rab', 'lembaga', $kode, 'tahun', $this->tahun, 'total')->row();
		$data['totalReal'] = $this->model->getBySum2('realis', 'lembaga', $kode, 'tahun', $this->tahun, 'nominal')->row();


		$data['jenis'] = $this->model->getBy('jenis', 'tahun', $this->tahun)->result();
		foreach ($data['jenis'] as $jns) {
			$kodeJenis = $jns->kode_jns;
			$data['rabJml'][$kodeJenis] = $this->model->getBySum3('rab', 'lembaga', $kode, 'tahun', $this->tahun, 'jenis', $kodeJenis, 'total')->row();
		}

		$data['user'] = $this->Auth_model->current_user();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");
		$data['tahun'] = $this->tahun;
		$data['bulan'] = $this->bulan;

		$this->load->view('account/head', $data);
		$this->load->view('account/rabDetail', $data);
		$this->load->view('account/foot');
	}

	public function rabEdit($kode)
	{
		$data['data'] = $this->model->getBy('rab', 'id_rab', $kode)->row();
		$data['lembaga'] = $this->model->getBy('lembaga', 'kode', $data['data']->lembaga)->row();
		$data['rel'] = $this->model->getBySum('realis', 'kode', $data['data']->kode, 'nominal')->row();
		$data['user'] = $this->Auth_model->current_user();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");
		$data['tahun'] = $this->tahun;
		$this->load->view('account/head', $data);
		$this->load->view('account/rabEdit', $data);
		$this->load->view('account/foot');
	}


	public function rabDel($id)
	{
		$data = $this->model->getBy('rab', 'id_rab', $id)->row();
		$cek = $this->model->getBy('realis', 'kode', $data->kode)->num_rows();
		$cek2 = $this->model->getBy('real_sm', 'kode', $data->kode)->num_rows();

		if ($cek > 0 || $cek2 > 0) {
			$this->session->set_flashdata('error', 'Maaf. RAB ini sudah atau sedang diajukan');
			redirect('account/rabDetail/' . $data->lembaga);
		} else {
			$this->model->delete('rab', $id);
			if ($this->db->affected_rows() > 0) {
				$this->session->set_flashdata('ok', 'Item RAB berhasil dihapus');
				redirect('account/rabDetail/' . $data->lembaga);
			} else {
				$this->session->set_flashdata('error', 'Item RAB tidak bisa dihapus');
				redirect('account/rabDetail/' . $data->lembaga);
			}
		}
	}

	public function saveEditRab()
	{
		$id = $this->input->post('id', true);
		$jml = $this->input->post('jml', true);

		$data = $this->model->getBy('rab', 'id_rab', $id)->row();
		$cek = $this->model->getBySum('realis', 'kode', $data->kode, 'vol')->row();
		$cek2 = $this->model->getBy('real_sm', 'kode', $data->kode)->num_rows();
		$sisa = $data->qty - $cek->jml;

		$data = [
			'qty' => $jml,
			'total' => $jml * $data->harga_satuan
		];
		if ($cek2 > 0) {
			$this->session->set_flashdata('error', 'Maaf. RAB ini sedang dalam proses pengajuan');
			redirect('account/rabEdit/' . $id);
		} elseif ($jml > $sisa) {
			$this->session->set_flashdata('error', 'Maaf. Jumlah baru melebihi sisa');
			redirect('account/rabEdit/' . $id);
		} else {
			$this->model->update('rab', $data, 'id_rab', $id);
			if ($this->db->affected_rows() > 0) {
				$this->session->set_flashdata('ok', 'Update QTY berhasil');
				redirect('account/rabEdit/' . $id);
			} else {
				$this->session->set_flashdata('error', 'Update QTY tidak bisa');
				redirect('account/rabEdit/' . $id);
			}
		}
	}

	public function rab_kbj()
	{
		$data['data'] = $this->model->getByJoin2('kebijakan', 'lembaga', 'lembaga', 'kode', 'tahun', $this->tahun)->result();
		$data['pakai'] = $this->model->getBySum('kebijakan', 'tahun', $this->tahun, 'nominal')->row();
		// $data['lembaga'] = $this->model->getBy('lembaga', 'kode', $data['data']->lembaga)->row();
		$data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
		$data['tahunData'] = $this->model->getAll('tahun')->result();
		$data['bidang'] = $this->model->getBy('bidang', 'tahun', $this->tahun)->result();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$this->load->view('account/head', $data);
		$this->load->view('account/kbj', $data);
		$this->load->view('account/foot');
	}

	public function saveKbj()
	{
		$data = [
			'id_kebijakan' => $this->uuid->v4(),
			'kode_kbj' => 'KBJ.' . $this->input->post('lembaga', true) . rand(0, 99999),
			'lembaga' => $this->input->post('lembaga', true),
			'bidang' => $this->input->post('bidang', true),
			'jenis' => $this->input->post('jenis', true),
			'nominal' => rmRp($this->input->post('nominal', true)),
			'tgl' => $this->input->post('tgl', true),
			'pj' => $this->input->post('pj', true),
			'ket' => $this->input->post('ket', true),
			'tahun' => $this->input->post('tahun', true),
			'at' => date('Y-m-d H:i:s')
		];

		$this->model->input('kebijakan', $data);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Input RAB Kebijakan berhasil');
			redirect('account/rab_kbj');
		} else {
			$this->session->set_flashdata('error', 'Input RAB Kebijakan tidak bisa');
			redirect('account/rab_kbj');
		}
	}

	public function delKbj($id)
	{
		// $where = ['id_kebijakan' => $id];

		$this->model->delete('kebijakan', 'id_kebijakan', $id);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'RAB Kebijakan berhasil dihapus');
			redirect('account/rab_kbj');
		} else {
			$this->session->set_flashdata('error', 'RAB Kebijakan tidak bisa dihapus');
			redirect('account/rab_kbj');
		}
	}

	public function pak()
	{
		$data['data'] = $this->db->query("SELECT * FROM pak WHERE tahun = '$this->tahun' ORDER BY id_pak DESC ")->result();
		$data['user'] = $this->Auth_model->current_user();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");
		$data['tahun'] = $this->tahun;
		$this->load->view('account/head', $data);
		$this->load->view('account/pak', $data);
		$this->load->view('account/foot');
	}
	public function pakDetail($kode)
	{
		$data['data'] = $this->model->getBy('pak', 'kode_pak', $kode)->row();
		$data['ttl'] = $this->model->getTotalRab($data['data']->lembaga, $this->tahun)->row();
		$data['rpak'] = $this->model->rabPak($kode)->result();
		$data['rabnew'] = $this->model->getBy3('rab_sm', 'lembaga', $data['data']->lembaga, 'tahun', $this->tahun, 'kode_pak', $kode)->result();
		$data['rpakSum'] = $this->model->selectSum('pak_detail', 'total', 'kode_pak', $kode)->row();
		$data['rabnewSum'] = $this->model->selectSum('rab_sm', 'total', 'kode_pak', $kode)->row();
		$data['user'] = $this->Auth_model->current_user();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $data['data']->lembaga, 'tahun', $this->tahun)->row();
		$data['tahun'] = $this->tahun;

		$this->load->view('account/head', $data);
		$this->load->view('account/pakDetail', $data);
		$this->load->view('account/foot');
	}

	public function tolakPAK()
	{
		$kode = $this->input->post('kode', true);
		$lembaga = $this->input->post('lembaga', true);
		$pesan = $this->input->post('pesan', true);
		$tgl = $this->input->post('tgl', true);

		$data2 = ['status' => 'ditolak'];

		$psn = '*INFORMASI PENOLAKAN PAK*

pengajuan dari :
    
Lembaga : ' . $lembaga . '
Kode PAK : ' . $kode . '
*DITOLAK Oleh Sub Bagian Accounting pada ' . $tgl . '*
dengan catatan : _*' . $pesan . '*_

*_dimohon kepada KPA lembaga terkait untuk segera melakukan revisi sesuai dengan catatan yang ada di https://simkupaduka.ppdwk.com/_*

Terimakasih';

		$this->model->update('pak', $data2, 'kode_pak', $kode);

		if ($this->db->affected_rows() > 0) {
			kirim_group($this->apiKey, '120363040973404347@g.us', $psn);
			kirim_group($this->apiKey, '120363042148360147@g.us', $psn);
			kirim_person($this->apiKey, '085235583647', $psn);
			// kirim_person($this->apiKey, '085236924510', $psn);

			$this->session->set_flashdata('ok', 'Pengajuan PAK berhasil ditolak');
			redirect('account/pakDetail/' . $kode);
		} else {
			$this->session->set_flashdata('error', 'Pengajuan PAK tidak bisa ditolak');
			redirect('account/pakDetail/' . $kode);
		}
	}

	public function setujuiPAK($kode)
	{
		$data = $this->model->getBy('pak', 'kode_pak', $kode)->row();
		$lembaga = $this->model->getBy('lembaga', 'kode', $data->lembaga)->row();

		$tgl = date('d M Y');

		$data2 = ['status' => 'disetujui'];

		$psn = '*INFORMASI PERSETUJUAN PAK*

pengajuan dari :
    
Lembaga : ' . $lembaga->nama . '
Kode PAK : ' . $kode . '
*DISETUJUI Oleh Sub Bagian Accounting pada ' . $tgl . '*

*_PAK akan segera di Upload oleh Bendahara. Selanjutnya RAB baru akan bisa digunakan_*

Terimakasih';

		$this->model->update('pak', $data2, 'kode_pak', $kode);

		if ($this->db->affected_rows() > 0) {
			kirim_group($this->apiKey, '120363040973404347@g.us', $psn);
			kirim_group($this->apiKey, '120363042148360147@g.us', $psn);
			kirim_person($this->apiKey, '085235583647', $psn);
			kirim_person($this->apiKey, '085236924510', $psn);

			$this->session->set_flashdata('ok', 'Pengajuan PAK berhasil disetujui');
			redirect('account/pakDetail/' . $kode);
		} else {
			$this->session->set_flashdata('error', 'Pengajuan PAK tidak bisa disetujui');
			redirect('account/pakDetail/' . $kode);
		}
	}

	public function realis()
	{
		$data['data'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
		$data['tahun_ajaran'] = $this->tahun;
		$data['user'] = $this->Auth_model->current_user();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");
		$data['tahun'] = $this->tahun;

		$this->load->view('account/head', $data);
		$this->load->view('account/data', $data);
		$this->load->view('account/foot');
	}

	public function realisDetail($lembaga)
	{
		$data['data'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
		$data['tahun_ajaran'] = $this->tahun;
		$data['lembaga'] = $this->model->getBy('lembaga', 'kode', $lembaga)->row();

		$data['totalRab'] = $this->model->getBySum2('rab', 'lembaga', $lembaga, 'tahun', $this->tahun, 'total')->row();
		$data['totalReal'] = $this->model->getBySum2('realis', 'lembaga', $lembaga, 'tahun', $this->tahun, 'nominal')->row();


		$data['jenis'] = $this->model->getBy('jenis', 'tahun', $this->tahun)->result();
		foreach ($data['jenis'] as $jns) {
			$kodeJenis = $jns->kode_jns;

			$data['rabJml'][$kodeJenis] = $this->model->getBySum3('rab', 'lembaga', $lembaga, 'tahun', $this->tahun, 'jenis', $kodeJenis, 'total')->row();

			$data['pakaiJml'][$kodeJenis] = $this->model->getTotalRealJenis($kodeJenis, $lembaga, $this->tahun)->row();
		}

		// $data['sumA'] = $this->model->getTotalRabJenis('A', $lembaga, $this->tahun)->row();
		// $data['sumB'] = $this->model->getTotalRabJenis('B', $lembaga, $this->tahun)->row();
		// $data['sumC'] = $this->model->getTotalRabJenis('C', $lembaga, $this->tahun)->row();
		// $data['sumD'] = $this->model->getTotalRabJenis('D', $lembaga, $this->tahun)->row();

		// $data['pakaiA'] = $this->model->getTotalRealJenis('A', $lembaga, $this->tahun)->row();
		// $data['pakaiB'] = $this->model->getTotalRealJenis('B', $lembaga, $this->tahun)->row();
		// $data['pakaiC'] = $this->model->getTotalRealJenis('C', $lembaga, $this->tahun)->row();
		// $data['pakaiD'] = $this->model->getTotalRealJenis('D', $lembaga, $this->tahun)->row();

		$data['rabA'] = $this->model->getBy2('rab', 'lembaga', $lembaga, 'tahun', $this->tahun)->result();
		$data['user'] = $this->Auth_model->current_user();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");
		$data['tahun'] = $this->tahun;

		$this->load->view('account/head', $data);
		$this->load->view('account/realDetail', $data);
		$this->load->view('account/foot');
	}

	public function cekRealis($kode)
	{
		$data['rab'] = $this->model->getBy('rab', 'kode', $kode)->row();
		$data['lem'] = $this->model->getBy2('lembaga', 'kode', $data['rab']->lembaga, 'tahun', $this->tahun)->row();
		$data['tahun_ajaran'] = $this->tahun;
		$data['rel'] = $this->model->getBySum('realis', 'kode', $kode, 'nominal')->row();
		$data['relData'] = $this->model->getBy('realis', 'kode', $kode)->result();
		$data['user'] = $this->Auth_model->current_user();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");
		$data['tahun'] = $this->tahun;
		$this->load->view('account/head', $data);
		$this->load->view('account/cekRab', $data);
		$this->load->view('account/foot');
	}

	public function pengajuan()
	{
		$data['data'] = $this->model->getPengajuan($this->tahun)->result();
		$data['bulan'] = $this->bulan;
		// $data['tahun'] = $this->tahun;
		$data['user'] = $this->Auth_model->current_user();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");
		$data['tahun'] = $this->tahun;
		$this->load->view('account/head', $data);
		$this->load->view('account/pengajuan', $data);
		$this->load->view('account/foot');
	}

	public function pengajuanDtl($kode)
	{
		$sql = $this->model->getBy('pengajuan', 'kode_pengajuan', $kode);
		$data['rinci'] = $sql->row();
		$data['bulan'] = $this->bulan;
		$data['tahun'] = $this->tahun;
		$lembaga = $sql->row('lembaga');
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $lembaga, 'tahun', $this->tahun)->row();

		if ($data['rinci']->cair == 1) {

			if (preg_match("/DISP./i", $kode)) {
				$data_cek = $this->db->query("SELECT realis.*, 0 as rencana, 'DISPOSISI' as kegiatan, 'DISPOSISI' as program FROM realis WHERE realis.kode_pengajuan = '$kode' ")->result();
			} else {
				$data_cek = $this->db->query("SELECT * FROM realis WHERE kode_pengajuan = '$kode' ")->result();
			}

			$data['nom'] = $this->model->getBySum('realis', 'kode_pengajuan', $kode, 'nominal')->row();
			$data['nomCair'] = $this->model->getBySum('realis', 'kode_pengajuan', $kode, 'nom_cair')->row();
		} else {
			if (preg_match("/DISP./i", $kode)) {
				$data_cek = $this->db->query("SELECT real_sm.*, 0 as rencana, 'DISPOSISI' as kegiatan, 'DISPOSISI' as program FROM real_sm WHERE real_sm.kode_pengajuan = '$kode' ")->result();
			} else {
				$data_cek = $this->db->query("SELECT * FROM real_sm WHERE kode_pengajuan = '$kode' ")->result();
			}

			$data['nom'] = $this->model->getBySum('real_sm', 'kode_pengajuan', $kode, 'nominal')->row();
			$data['nomCair'] = $this->model->getBySum('real_sm', 'kode_pengajuan', $kode, 'nom_cair')->row();
		}

		$data['honor'] = $this->model->getBy('honor_file', 'kode_pengajuan', $kode);

		$data['totalRab'] = $this->model->getBySum2('rab', 'lembaga', $lembaga, 'tahun', $this->tahun, 'total')->row();
		$data['totalReal'] = $this->model->getBySum2('realis', 'lembaga', $lembaga, 'tahun', $this->tahun, 'nominal')->row();
		$data['totalAjukan'] = $this->model->getBySum2('real_sm', 'kode_pengajuan', $kode, 'tahun', $this->tahun, 'nominal')->row();

		$data['bulanData'] = $this->bulan;

		$data['jenis'] = $this->model->getBy('jenis', 'tahun', $this->tahun)->result();
		foreach ($data['jenis'] as $jns) {
			$kodeJenis = $jns->kode_jns;

			$data['rabJml'][$kodeJenis] = $this->model->getBySum3('rab', 'lembaga', $lembaga, 'tahun', $this->tahun, 'jenis', $kodeJenis, 'total')->row();

			$data['pakaiJml'][$kodeJenis] = $this->model->getTotalRealJenis($kodeJenis, $lembaga, $this->tahun)->row();

			$data['nomJml'][$kodeJenis] = $this->model->getBySum2('real_sm', 'kode_pengajuan', $kode, 'jenis', $kodeJenis, 'nominal')->row();
		}

		$data_all = [];
		foreach ($data_cek as $dts) {
			$kode_rinci = explode('-', $dts->kode);
			$program = $this->model->getBy2('dppk', 'id_dppk', $kode_rinci[1], 'tahun', $this->tahun)->row();
			$coa = $this->model->getBy2('coa', 'kode', $kode_rinci[2], 'tahun', $this->tahun)->row();
			$ssh = $this->model->getBy('ssh', 'kode', $kode_rinci[3])->row();
			$data_all[] = [
				'id' => $dts->id_realis,
				'kode_item' => $dts->kode,
				'nama_item' => $dts->ket,
				'stas' => $dts->stas,
				'nominal' => $dts->nominal,
				'harga' => $dts->harga,
				'satuan' => $ssh ? $ssh->satuan : '',
				'qty' => $dts->vol,
				'program' => $program->program,
				'ssh' => $ssh ? $ssh->nama : '',
				'coa' => $coa->nama,
			];
		}


		$data['data'] = $data_all;
		$data['user'] = $this->Auth_model->current_user();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");
		// $data['tahun'] = $this->tahun;

		$this->load->view('account/head', $data);
		$this->load->view('account/pengajuanDetail', $data);
		$this->load->view('account/foot');
	}

	public function pengajuanDel($id)
	{
		$cek = $this->db->query("SELECT * FROM pengajuan WHERE kode_pengajuan = '$id' AND verval = 0 AND apr = 0 AND cair = 0 AND spj = 0 ")->row();
		if (!$cek) {
			$this->session->set_flashdata('error', 'Pengajuan sudah diproses');
			redirect('account/pengajuan');
		} else {
			$this->model->delete('pengajuan', 'kode_pengajuan', $id);
			$this->model->delete('spj', 'kode_pengajuan', $id);
			if ($this->db->affected_rows() > 0) {
				$this->session->set_flashdata('ok', 'Hapus Pemasukan Pesantren Berhasil');
				redirect('account/pengajuan');
			} else {
				$this->session->set_flashdata('error', 'Hapus Pemasukan Pesantren Gagal');
				redirect('account/pengajuan');
			}
		}
	}

	public function editRealSm()
	{
		$where = $this->input->post('id_rsm', true);
		$data = [
			'ket' => $this->input->post('ket', true),
			'nom_cair' => rmRp($this->input->post('nom_cair', true)),
			'stas' => $this->input->post('stas', true)
		];

		$pjData = $this->model->getBy('real_sm', 'id_realis', $where)->row();
		$this->model->update('real_sm', $data, 'id_realis', $where);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Pengajuan berhasil diedit');
			redirect('account/pengajuanDtl/' . $pjData->kode_pengajuan);
		} else {
			$this->session->set_flashdata('error', 'Pengajuan tidak bisa diedit');
			redirect('account/pengajuanDtl/' . $pjData->kode_pengajuan);
		}
	}

	public function delRealSm($id)
	{
		$where = $id;

		$pjData = $this->model->getBy('real_sm', 'id_realis', $where)->row();
		$this->model->delete('real_sm', 'id_realis', $where);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Pengajuan berhasil dihapus');
			redirect('account/pengajuanDtl/' . $pjData->kode_pengajuan);
		} else {
			$this->session->set_flashdata('error', 'Pengajuan tidak bisa dihapus');
			redirect('account/pengajuanDtl/' . $pjData->kode_pengajuan);
		}
	}

	public function vervalPengajuan($kode)
	{
		$pjData = $this->model->getBy('pengajuan', 'kode_pengajuan', $kode)->row();
		$lembaga = $this->model->getBy2('lembaga', 'kode', $pjData->lembaga, 'tahun', $this->tahun)->row();
		$total = $this->model->getBySum('real_sm', 'kode_pengajuan', $kode, 'nominal')->row();

		$where = $kode;
		$data = [
			'id_verval' => $this->uuid->v4(),
			'kode_pengajuan' => $kode,
			'lembaga' => $pjData->lembaga,
			'tgl_verval' => date('Y-m-d'),
			'user' => $this->user,
			'stts' => 1,
			'tahun' => $this->tahun
		];
		// $data2 = ['verval' => '1', 'apr' => '1'];
		$data2 = ['verval' => '1'];

		if (preg_match("/DISP./i", $kode)) {
			$rt = '*(DISPOSISI)*';
		} else {
			$rt = '';
		}

		$psn = '*INFORMASI PENCAIRAN PENGAJUAN* ' . $rt . '

pengajuan dari :

Lembaga : ' . $lembaga->nama . '
Kode Pengajuan : ' . $kode . '
Nominal : ' . rupiah($total->jml) . '
*Telah di Verifikasi dan Validasi Oleh Sub Bagian Accounting pada ' . date('Y-m-d') . '*

*_Pengajuan sudah bisa dicairkan. Dimohon kepada KPA Lembaga Terkait untuk menghubungi Admin Pencairan._*
Terimakasih';

		$this->model->input('verifikasi', $data);
		$this->model->update('pengajuan', $data2, 'kode_pengajuan', $kode);

		if ($this->db->affected_rows() > 0) {
			// kirim_group($this->apiKey, '120363040973404347@g.us', $psn);
			// kirim_group($this->apiKey, '120363042148360147@g.us', $psn);
			// kirim_person($this->apiKey, '082264061060', $psn);
			kirim_person($this->apiKey, '085236924510', $psn);

			$this->session->set_flashdata('ok', 'Pengajuan berhasil diverval');
			redirect('account/pengajuanDtl/' . $pjData->kode_pengajuan);
		} else {
			$this->session->set_flashdata('error', 'Pengajuan tidak bisa diverval');
			redirect('account/pengajuanDtl/' . $pjData->kode_pengajuan);
		}
	}

	public function tolakPengajuan()
	{
		$kode = $this->input->post('kode', true);
		$pjData = $this->model->getBy('pengajuan', 'kode_pengajuan', $kode)->row();
		$lembaga = $this->model->getBy2('lembaga', 'kode', $pjData->lembaga, 'tahun', $this->tahun)->row();
		$total = $this->model->getBySum('real_sm', 'kode_pengajuan', $kode, 'nominal')->row();

		$pesan = $this->input->post('pesan', true);
		$tgl = $this->input->post('tgl', true);

		$data = [
			'id_verval' => $this->uuid->v4(),
			'kode_pengajuan' => $kode,
			'lembaga' => $pjData->lembaga,
			'tgl_verval' => $tgl,
			'user' => $this->user,
			'stts' => 0,
			'pesan' => $pesan,
			'tahun' => $this->tahun
		];
		$data2 = ['stts' => 'no'];

		if (preg_match("/DISP./i", $kode)) {
			$rt = '*(DISPOSISI)*';
		} else {
			$rt = '';
		}



		$psn = '
*INFORMASI PENOLAKAN PENGAJUAN* ' . $rt . '

pengajuan dari :

Lembaga : ' . $lembaga->nama . '
Kode Pengajuan : ' . $kode . '
Nominal : ' . rupiah($total->jml) . '
*DITOLAK Oleh Sub Bagian Accounting pada ' . $tgl . '*
dengan catatan : _*' . $pesan . '*_

*_dimohon kepada KPA lembaga terkait untuk segera melakukan revisi sesuai dengan catatan yang ada di https://simkupaduka.ppdwk.com/_*

Terimakasih';

		$this->model->input('verifikasi', $data);
		$this->model->update('pengajuan', $data2, 'kode_pengajuan', $kode);

		if ($this->db->affected_rows() > 0) {
			kirim_group($this->apiKey, '120363040973404347@g.us', $psn);
			kirim_group($this->apiKey, '120363042148360147@g.us', $psn);
			// kirim_person($this->apiKey, '082264061060', $psn);
			// kirim_person($this->apiKey, '085236924510', $psn);

			$this->session->set_flashdata('ok', 'Pengajuan berhasil ditolak');
			redirect('account/pengajuanDtl/' . $pjData->kode_pengajuan);
		} else {
			$this->session->set_flashdata('error', 'Pengajuan tidak bisa ditolak');
			redirect('account/pengajuanDtl/' . $pjData->kode_pengajuan);
		}
	}

	public function spj()
	{
		$data['data'] = $this->model->getSPJ($this->tahun)->result();
		$data['dataSr'] = $this->db->query("SELECT * FROM spj JOIN lembaga ON spj.lembaga=lembaga.kode JOIN sarpras ON sarpras.kode_pengajuan=spj.kode_pengajuan WHERE spj.kode_pengajuan LIKE '%.SRPS.%' AND spj.tahun = '$this->tahun' AND lembaga.tahun = '$this->tahun' AND file_spj != '' ")->result();
		$data['bulan'] = $this->bulan;
		$data['tahun'] = $this->tahun;
		$data['user'] = $this->Auth_model->current_user();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");
		// $data['tahun'] = $this->tahun;
		$this->load->view('account/head', $data);
		$this->load->view('account/spj', $data);
		$this->load->view('account/foot');
	}

	public function tolakSpj()
	{
		$id = $this->input->post('id', true);
		$kode = $this->input->post('kode', true);
		$nm_lm = $this->input->post('nm_lm', true);
		$hp = $this->input->post('hp', true);
		$isi =  $this->input->post('isi', true);
		$at = date('d-m-Y H:i');

		if (preg_match("/DISP./i", $kode)) {
			$rt = "*(DISPOSISI)*";
		} else {
			$rt = '';
		}

		$psn = '
*INFORMASI VERIFIKASI SPJ* ' . $rt . '

Ada Penolakan SPJ dari :
    
Lembaga : ' . $nm_lm . '
Kode Pengajuan : ' . $kode . '
Pada : ' . $at . '

*_SPJ DITOLAK oleh TIM ACCOUNTING. dengan catatan ' . $isi . '_*
Mohon kepada lembaga terkait untuk segera memperbaikinya dan mengupload ulang SPJ yang sudah diperbaiki di https://simkupaduka.ppdwk.com/.
Terimakasih';

		$data1 = ['stts' => '0'];
		$data2 = ['spj' => '0'];

		$this->model->update('spj', $data1, 'id_spj', $id);
		$this->model->update('pengajuan', $data2, 'kode_pengajuan', $kode);

		if ($this->db->affected_rows() > 0) {
			kirim_group($this->apiKey, '120363040973404347@g.us', $psn);
			kirim_group($this->apiKey, '120363042148360147@g.us', $psn);
			kirim_person($this->apiKey, $hp, $psn);
			// kirim_person($this->apiKey, '085236924510', $psn);

			$this->session->set_flashdata('ok', 'SPJ berhasil ditolak');
			redirect('account/spj');
		} else {
			$this->session->set_flashdata('error', 'SPJ tidak bisa ditolak');
			redirect('account/spj');
		}
	}

	public function setujuiSpj()
	{
		$id = $this->input->post('id', true);
		$kode = $this->input->post('kode', true);
		$nm_lm = $this->input->post('nm_lm', true);
		$hp = $this->input->post('hp', true);

		$cair = rmRp($this->input->post('cair', true));
		$serap = rmRp($this->input->post('serap', true));
		$sisa = $cair - $serap;

		$data3 = [
			'id_sisa' => $id,
			'kode_pengajuan' => $kode,
			'dana_cair' => $cair,
			'dana_serap' => $serap,
			'sisa' => $sisa,
			'tgl_setor' => '-',
			'kasir' => $this->user,
			'tahun' => $this->tahun,
		];

		$at = date('d-m-Y H:i');

		if (preg_match("/DISP./i", $kode)) {
			$rt = "*(DISPOSISI)*";
		} else {
			$rt = '';
		}

		$psn = '
*INFORMASI VERIFIKASI SPJ* ' . $rt . '

Ada pelaporan SPJ dari :
    
Lembaga : ' . $nm_lm . '
Kode Pengajuan : ' . $kode . '
Pada : ' . $at . '

*_SPJ telah disetujui oleh SUB BAGIAN ACCOUNTING. Dimohon kepada KPA untuk segera menyerahkan hard copy SPJ dan sisa belanja anggaran kepada KASIR. Untuk bisa melakukan pengajuan berikutnya._*

Terimakasih';

		$data1 = ['stts' => '2'];
		$data2 = ['spj' => '2'];

		$this->model->update('spj', $data1, 'id_spj', $id);
		$this->model->update('pengajuan', $data2, 'kode_pengajuan', $kode);
		$this->model->input('real_sisasm', $data3);

		if ($this->db->affected_rows() > 0) {
			kirim_group($this->apiKey, '120363040973404347@g.us', $psn);
			kirim_group($this->apiKey, '120363042148360147@g.us', $psn);
			// kirim_person($this->apiKey, $hp, $psn);
			kirim_person($this->apiKey, '085236924510', $psn);

			$this->session->set_flashdata('ok', 'SPJ berhasil disetujui');
			redirect('account/spj');
		} else {
			$this->session->set_flashdata('error', 'SPJ tidak bisa disetujui');
			redirect('account/spj');
		}
	}

	public function viewSpj($kode)
	{
		$data['bulan'] = $this->bulan;
		$data['tahun'] = $this->tahun;
		$data['user'] = $this->Auth_model->current_user();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$data['spj'] = $this->model->getBy('spj', 'kode_pengajuan', $kode)->row();
		$this->load->view('account/head', $data);
		$this->load->view('account/viewSpj', $data);
		$this->load->view('account/foot');
	}

	public function uploadSisa()
	{
		$id = $this->input->post('id', true);
		$kode = $this->input->post('kode', true);
		$nm_lm = $this->input->post('nm_lm', true);
		$hp = $this->input->post('hp', true);
		$at = date('d-m-Y H:i');
		$cair = rmRp($this->input->post('cair', true));
		$serap = rmRp($this->input->post('serap', true));
		$sisa = $cair - $serap;
		$tgl_setor = $this->input->post('tgl_setor', true);

		if (preg_match("/DISP./i", $kode)) {
			$rt = "*(DISPOSISI)*";
		} else {
			$rt = '';
		}

		$psn = '
*INFORMASI VERIFIKASI SPJ* ' . $rt . '

Ada pelaporan SPJ dari :
    
Lembaga : ' . $nm_lm . '
Kode Pengajuan : ' . $kode . '
Pada : ' . $at . '

*_Hard copy SPJ dan sisa belanja anggaran telah disetor kepada SUB BAGIAN ACCOUNTING. Untuk pengajuan berikutnya sudah bisa dilakukan._*

Terimakasih
https://simkupaduka.ppdwk.com/';

		$data1 = ['stts' => '3'];
		$data2 = ['spj' => '3'];
		$data3 = [
			'id_sisa' => $id,
			'kode_pengajuan' => $kode,
			'dana_cair' => $cair,
			'dana_serap' => $serap,
			'sisa' => $sisa,
			'tgl_setor' => $tgl_setor,
			'tahun' => $this->tahun,
		];

		$this->model->update('spj', $data1, 'id_spj', $id);
		$this->model->update('pengajuan', $data2, 'kode_pengajuan', $kode);
		$this->model->input('real_sisa', $data3);

		if ($this->db->affected_rows() > 0) {
			kirim_group($this->apiKey, '120363040973404347@g.us', $psn);
			kirim_group($this->apiKey, '120363042148360147@g.us', $psn);
			kirim_person($this->apiKey, $hp, $psn);
			// kirim_person($this->apiKey, '085236924510', $psn);

			$this->session->set_flashdata('ok', 'SPJ berhasil disetujui');
			redirect('account/spj');
		} else {
			$this->session->set_flashdata('error', 'SPJ tidak bisa disetujui');
			redirect('account/spj');
		}
	}

	public function disposisi()
	{

		$data['data'] = $this->model->getDispo($this->tahun)->result();
		$data['pakai'] = $this->model->dispPakai($this->tahun)->row();
		$data['bulan'] = $this->bulan;

		$data['user'] = $this->Auth_model->current_user();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");
		$data['tahun'] = $this->tahun;
		$this->load->view('account/head', $data);
		$this->load->view('account/disp', $data);
		$this->load->view('account/foot');
	}

	public function info()
	{

		$data['data'] = $this->model->getBy('info', 'tahun', $this->tahun)->result();
		$data['user'] = $this->Auth_model->current_user();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");
		$data['tahun'] = $this->tahun;
		$this->load->view('account/head', $data);
		$this->load->view('account/info', $data);
		$this->load->view('account/foot');
	}

	public function infoAdd()
	{
		$data['bulan'] = $this->bulan;
		$data['tahun'] = $this->tahun;

		$data['user'] = $this->Auth_model->current_user();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$this->load->view('account/head', $data);
		$this->load->view('account/infoAdd', $data);
		$this->load->view('account/foot');
	}

	public function saveInfo()
	{
		$data = [
			'id_info' =>  $this->uuid->v4(),
			'judul' =>  $this->input->post('judul'),
			'tgl' =>  $this->input->post('tgl'),
			'uploader' =>  $this->input->post('uploader'),
			'isi' =>  $this->input->post('isi'),
			'tujuan' =>  $this->input->post('tujuan'),
			'tahun' =>  $this->tahun,
		];

		$this->model->input('info', $data);

		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Informasi baru berhasil ditambahkan');
			redirect('account/info');
		} else {
			$this->session->set_flashdata('error', 'Informasi baru tidak berhasil ditambahkan');
			redirect('account/info');
		}
	}

	public function infoEdit($id)
	{
		$data['data'] = $this->model->getBy('info', 'id_info', $id)->row();
		$data['bulan'] = $this->bulan;

		$data['user'] = $this->Auth_model->current_user();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");
		$data['tahun'] = $this->tahun;
		$this->load->view('account/head', $data);
		$this->load->view('account/infoEdit', $data);
		$this->load->view('account/foot');
	}

	public function saveEditInfo()
	{
		$id =  $this->input->post('id', true);
		$data = [
			'judul' =>  $this->input->post('judul'),
			'tgl' =>  $this->input->post('tgl'),
			'uploader' =>  $this->input->post('uploader'),
			'isi' =>  $this->input->post('isi'),
			'tujuan' =>  $this->input->post('tujuan'),
			'tahun' =>  $this->tahun,
		];

		$this->model->update('info',  $data, 'id_info', $id);

		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Informasi baru berhasil diupdate');
			redirect('account/info');
		} else {
			$this->session->set_flashdata('error', 'Informasi baru tidak berhasil diupdate');
			redirect('account/info');
		}
	}


	public function delInfo($id)
	{
		$this->model->delete('info', 'id_info', $id);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Informasi berhasil dihapus');
			redirect('account/info');
		} else {
			$this->session->set_flashdata('error', 'Informasi tidak bisa dihapus');
			redirect('account/info');
		}
	}

	public function history()
	{
		$data['data'] = $this->model->getPengajuanAll($this->tahun)->result();
		$data['bulan'] = $this->bulan;
		$data['user'] = $this->Auth_model->current_user();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");
		$data['tahun'] = $this->tahun;
		$this->load->view('account/head', $data);
		$this->load->view('account/history', $data);
		$this->load->view('account/foot');
	}

	public function historyDtl($kode)
	{
		$sql = $this->model->getBy('pengajuan', 'kode_pengajuan', $kode);
		$data['data'] = $sql->row();
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $data['data']->lembaga, 'tahun', $this->tahun)->row();
		$data['bulan'] = $this->bulan;

		$data['real'] = $this->model->getBySum('realis', 'kode_pengajuan', $kode, 'nominal')->row();
		$data['real_sm'] = $this->model->getBySum('real_sm', 'kode_pengajuan', $kode, 'nominal')->row();
		$data['a'] = $this->model->getByJoin3('pengajuan', 'lembaga', 'lembaga', 'kode', 'kode_pengajuan', 'kode', $kode, $data['lembaga']->kode)->row();
		$data['spj'] = $this->model->getBy('spj', 'kode_pengajuan', $kode)->row();
		$data['veral'] = $this->model->getBy('verifikasi', 'kode_pengajuan', $kode)->result();
		$data['apr'] = $this->model->getBy('approv', 'kode_pengajuan', $kode)->result();
		$data['cair'] = $this->model->getBy('pencairan', 'kode_pengajuan', $kode)->result();
		$data['user'] = $this->Auth_model->current_user();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");
		$data['tahun'] = $this->tahun;
		$this->load->view('account/head', $data);
		$this->load->view('account/historyDtl', $data);
		$this->load->view('account/foot');
	}

	public function savePAK()
	{
		$id =  $this->input->post('id_akses', true);
		$data = [
			'login' =>  $this->input->post('dari'),
			'disposisi' =>  $this->input->post('sampai')
		];

		$this->model->update('akses',  $data, 'lembaga', 'umum');

		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Akses PAK berhasil diupdate');
			redirect('account/setting');
		} else {
			$this->session->set_flashdata('error', 'Akses PAK tidak bisa diupdate');
			redirect('account/setting');
		}
	}

	public function downHonor($nama)
	{
		// $file = $this->model->getFile($nis)->row();
		force_download('vertical/assets/uploads/honor/' . $nama, NULL);
		// redirect('berkas/detail/');
	}

	public function setting()
	{

		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$this->load->view('account/head', $data);
		$this->load->view('account/setting', $data);
		$this->load->view('account/foot');
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
				redirect('account/setting');
			} else {
				$this->session->set_flashdata('error', 'User akun tidak berhasil diperbarui');
				redirect('account/setting');
			}
		} else {
			if ($password != $password2) {
				$this->session->set_flashdata('error', 'Konfimasi password tidak sama');
				redirect('account/setting');
			} else {

				$data = [
					'nama' => $nama,
					'username' => $username,
					'password' => $pass_baru
				];
				$this->model->update('user', $data, 'id_user', $id_user);
				if ($this->db->affected_rows() > 0) {
					$this->session->set_flashdata('ok', 'User akun berhasil diperbarui');
					redirect('account/setting');
				} else {
					$this->session->set_flashdata('error', 'User akun tidak berhasil diperbarui');
					redirect('account/setting');
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
			redirect('account/setting');
		} else {
			$this->session->set_flashdata('error', 'User akun tidak berhasil diperbarui');
			redirect('account/setting');
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
				redirect('account/setting');
			} else {
				$this->session->set_flashdata('error', 'Upload foto sukses');
				redirect('account/setting');
			}
		}
	}

	public function lain()
	{
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['keluar'] = $this->model->getBy('keluar', 'tahun', $this->tahun)->result();
		$data['sumKeluar'] = $this->model->getBySum('keluar', 'tahun', $this->tahun, 'nominal')->row();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$this->load->view('account/head', $data);
		$this->load->view('account/keluar', $data);
		$this->load->view('account/foot');
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
			redirect('account/lain');
		} else {
			$this->session->set_flashdata('error', 'Input data gagal');
			redirect('account/lain');
		}
	}

	public function delLain($id)
	{
		$this->model->delete('keluar', 'id_keluar', $id);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Hapus data sukses');
			redirect('account/lain');
		} else {
			$this->session->set_flashdata('error', 'Hapus data gagal');
			redirect('account/lain');
		}
	}

	public function pinjam()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		// $data['pinjam'] = $this->model->getBy('peminjaman', 'tahun', $this->tahun)->result();
		$data['pinjam'] = $this->db->query("SELECT * FROM peminjaman ORDER BY tahun DESC")->result();
		$data['sumPinjam'] = $this->model->getBySum('peminjaman', 'tahun', $this->tahun, 'nominal')->row();
		$data['sumCicil'] = $this->model->getBySum('cicilan', 'tahun', $this->tahun, 'nominal')->row();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$this->load->view('account/head', $data);
		$this->load->view('account/pinjam', $data);
		$this->load->view('account/foot');
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
			redirect('account/pinjam');
		} else {
			$this->session->set_flashdata('error', 'Input data gagal');
			redirect('account/pinjam');
		}
	}

	public function delPinjam($id)
	{
		$data = $this->model->getBy('peminjaman', 'id_pinjam', $id)->row();

		$this->model->delete('peminjaman', 'id_pinjam', $id);
		$this->model->delete('cicilan', 'kode_pinjam', $data->kode_pinjam);

		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Hapus data sukses');
			redirect('account/pinjam');
		} else {
			$this->session->set_flashdata('error', 'Hapus data gagal');
			redirect('account/pinjam');
		}
	}

	public function infoPinjam($id)
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$data['dataPinjam'] = $this->model->getBy('peminjaman', 'id_pinjam', $id)->row();
		$data['cicil'] = $this->model->getBy('cicilan', 'kode_pinjam', $data['dataPinjam']->kode_pinjam)->result();
		$data['sumPinjam'] = $this->model->getBySum('peminjaman', 'tahun', $this->tahun, 'nominal')->row();

		$data['sumCicil'] = $this->model->getBySum('cicilan', 'kode_pinjam', $data['dataPinjam']->kode_pinjam, 'nominal')->row();

		$this->load->view('account/head', $data);
		$this->load->view('account/infoPinjam', $data);
		$this->load->view('account/foot');
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
			redirect('account/infoPinjam/' . $dataPinjam->id_pinjam);
		} else {
			$this->session->set_flashdata('error', 'Input data gagal');
			redirect('account/infoPinjam/' . $dataPinjam->id_pinjam);
		}
	}

	public function delCicil($id)
	{
		$data = $this->model->getBy('cicilan', 'id_cicilan', $id)->row();
		$dataPinjam = $this->model->getBy('peminjaman', 'kode_pinjam', $data->kode_pinjam)->row();

		$this->model->delete('cicilan', 'id_cicilan', $id);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Hapus data sukses');
			redirect('account/infoPinjam/' . $dataPinjam->id_pinjam);
		} else {
			$this->session->set_flashdata('error', 'Hapus data gagal');
			redirect('account/infoPinjam/' . $dataPinjam->id_pinjam);
		}
	}

	public function setor()
	{
		$data['bulan'] = $this->bulan;
		$data['list'] = $this->model->getSetor($this->tahun)->result();
		$this->load->view('account/setor', $data);
	}

	public function sisa()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['bulan'] = $this->bulan;
		$data['sumSisa'] = $this->model->selectSum('real_sisa', 'sisa', 'tahun', $this->tahun)->row();
		$data['sisa'] = $this->model->getSisaOrder($this->tahun)->result();
		// $data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$this->load->view('account/head', $data);
		$this->load->view('account/masukSisa', $data);
		$this->load->view('account/foot');
	}

	public function delSisa($id)
	{
		$this->model->delete('real_sisa', 'id_sisa', $id);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Saldo sudah dihapus');
			redirect('account/sisa');
		} else {
			$this->session->set_flashdata('error', 'Hapus data gagal');
			redirect('account/sisa');
		}
	}

	public function rab24()
	{
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['sumKeluar'] = $this->model->getBySum('keluar', 'tahun', $this->tahun, 'nominal')->row();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$data['data'] = $this->db->query("SELECT * FROM rab_list JOIN lembaga ON lembaga.kode=rab_list.lembaga WHERE rab_list.tahun = '$this->tahun' AND lembaga.tahun = '$this->tahun'  AND rab_list.status = 'proses' ")->result();

		$this->load->view('account/head', $data);
		$this->load->view('account/rab24', $data);
		$this->load->view('account/foot');
	}

	public function rab24detail($lembaga)
	{
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['sumKeluar'] = $this->model->getBySum('keluar', 'tahun', $this->tahun, 'nominal')->row();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $lembaga, 'tahun', $this->tahun)->row();
		$data['dppk'] = $this->model->getBy2('dppk', 'lembaga', $lembaga, 'tahun', $this->tahun)->result();
		$data['rab24Total'] = $this->model->getBySum2('rab_sm24', 'lembaga', $lembaga, 'tahun', $this->tahun, 'total');
		$data['bidang'] = $this->model->getBy('bidang', 'tahun', $this->tahun)->result();

		$data['data'] = $this->model->getBy2('rab_sm24', 'lembaga', $lembaga, 'tahun', $this->tahun)->result();
		$data['cekData'] = $this->db->query("SELECT * FROM rab_list WHERE lembaga = '$lembaga' AND tahun = '$this->tahun' AND status = 'disetujui' OR status = 'selesai' OR status = 'proses' ")->num_rows();

		$dppk = $this->model->getRabByDppk($lembaga, $this->tahun)->result();
		$data['rab'] = array();
		foreach ($dppk as $dts) :
			$dppk = $dts->kode_pak;
			$dppkData = $this->model->getBy('dppk', 'id_dppk', $dppk)->row(); // Mengambil data dari tabel DPPK
			$dataDppk = $this->model->getBy('rab_sm24', 'kode_pak', $dppk);

			$list = $dataDppk->result();
			$totalItem = count($list);

			foreach ($list as &$item) {
				$item->nama_dppk = $dppkData->program;
			}

			$data['rab'][$dppk] = $list;
		// $data['rab'][$dppk]['total_item'] = $totalItem;
		endforeach;

		$this->load->view('account/head', $data);
		$this->load->view('account/rab24detail', $data);
		$this->load->view('account/foot');
	}

	public function stujuiRab24($lembaga)
	{
		$data = ['status' => 'disetujui'];
		$lm = $this->model->getBy2('lembaga', 'kode', $lembaga, 'tahun', $this->tahun)->row();

		$this->model->update('rab_list', $data, 'lembaga', $lembaga);

		$psn = '*INFORMASI VERIFIKASI RAB 23/24*

Ada pengajuan RAB Tahun Ajaran 23/24  :
    
Lembaga : ' . $lm->nama . '
Tahun : ' . $this->tahun . '
Pada : ' .  date('Y-m-d H:i') . '

*_RAB sudah disetujui. Selanjutnya akan di upload oleh Admin bendahara di https://simkupaduka.ppdwk.com/_*
Terimakasih';

		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'RAB sudah di setujui');
			kirim_group($this->apiKey, '120363040973404347@g.us', $psn);
			kirim_group($this->apiKey, '120363042148360147@g.us', $psn);
			// kirim_person($this->apiKey, '082302301003', $psn);
			// kirim_person($this->apiKey, '085236924510', $psn);
			redirect('account/rab24');
		} else {
			$this->session->set_flashdata('error', 'Hapus data gagal');
			redirect('account/rab24');
		}
	}

	public function tolakRab24()
	{
		$kode = $this->input->post('kode', true);
		$lembaga = $this->input->post('lembaga', true);
		$pesan = $this->input->post('pesan', true);
		$tgl = $this->input->post('tgl', true);

		$data2 = ['status' => 'ditolak'];

		$psn = '*INFORMASI PENOLAKAN RAB 23/24*

pengajuan dari :
    
Lembaga : ' . $lembaga . '
Kode PAK : ' . $kode . '
*DITOLAK Oleh Sub Bagian Accounting pada ' . $tgl . '*
dengan catatan : _*' . $pesan . '*_

*_dimohon kepada KPA lembaga terkait untuk segera melakukan revisi sesuai dengan catatan yang ada di https://simkupaduka.ppdwk.com/_*

Terimakasih';

		$this->model->update('rab_list', $data2, 'lembaga', $kode);

		if ($this->db->affected_rows() > 0) {
			kirim_group($this->apiKey, '120363040973404347@g.us', $psn);
			kirim_group($this->apiKey, '120363042148360147@g.us', $psn);
			// kirim_person($this->apiKey, '085235583647', $psn);
			kirim_person($this->apiKey, '085236924510', $psn);

			$this->session->set_flashdata('ok', 'Pengajuan RAB berhasil ditolak');
			redirect('account/rab24');
		} else {
			$this->session->set_flashdata('error', 'Pengajuan RAB tidak bisa ditolak');
			redirect('account/rab24');
		}
	}

	function analisis()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$data['dtasal'] = $this->db->query("SELECT *
FROM rab
WHERE tahun = '$this->tahun'
GROUP BY rencana
ORDER BY CAST(rencana AS UNSIGNED) ASC ")->result();

		$data['bulan'] = $this->bulan;

		$this->load->view('account/head', $data);
		$this->load->view('account/analisis', $data);
		$this->load->view('account/foot');
	}

	function sarpras()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$data['data'] = $this->model->getBy('sarpras', 'tahun', $this->tahun)->result();
		$data['pakai'] = $this->db->query("SELECT SUM(qty*harga_satuan) AS jml FROM sarpras_detail WHERE tahun = '$this->tahun' ")->row();
		$data['dataSpj'] = $this->model->getSPJSarpras($this->tahun)->result();
		$data['dataSr'] = $this->db->query("SELECT * FROM spj JOIN lembaga ON spj.lembaga=lembaga.kode JOIN sarpras ON sarpras.kode_pengajuan=spj.kode_pengajuan WHERE spj.kode_pengajuan LIKE '%.SRPS.%' AND spj.tahun = '$this->tahun' AND lembaga.tahun = '$this->tahun' AND file_spj != '' ")->result();

		$data['pagu'] = 0;
		$data['bulan'] = $this->bulan;

		$this->load->view('account/head', $data);
		$this->load->view('account/sarpras', $data);
		$this->load->view('account/foot');
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

		$this->load->view('account/head', $data);
		$this->load->view('account/sarprasInput', $data);
		$this->load->view('account/foot');
	}

	function tolakSarpras()
	{
		$data = ['status' => 'ditolak'];
		$kode = $this->input->post('kode', true);
		$tgl = $this->input->post('tgl', true);
		$pesan = $this->input->post('pesan', true);

		$this->model->update('sarpras', $data, 'kode_pengajuan', $kode);

		$psn = '*INFORMASI PENGAJUAN SARPRAS*

pengajuan dari :
    
Lembaga : Biro Umum - Sarpras
Kode Pengajuan : ' . $kode . '
*DITOLAK Oleh Sub Bagian Accounting pada ' . $tgl . '*
dengan catatan : _*' . $pesan . '*_

*_dimohon kepada KPA lembaga terkait untuk segera melakukan revisi sesuai dengan catatan yang ada di https://simkupaduka.ppdwk.com/_*

Terimakasih';

		if ($this->db->affected_rows() > 0) {
			kirim_group($this->apiKey, '120363040973404347@g.us', $psn);
			kirim_group($this->apiKey, '120363042148360147@g.us', $psn);
			// kirim_person($this->apiKey, '085235583647', $psn);
			kirim_person($this->apiKey, '085236924510', $psn);

			$this->session->set_flashdata('ok', 'Pengajuan RAB berhasil ditolak');
			redirect('account/sarpras');
		} else {
			$this->session->set_flashdata('error', 'Pengajuan RAB tidak bisa ditolak');
			redirect('account/sarpras');
		}
	}

	function vervalSarpras($kode)
	{
		$data = ['status' => 'disetujui'];

		$this->model->update('sarpras', $data, 'kode_pengajuan', $kode);
		$tgl = date('d-m-Y H:i');
		$dataSum = $this->db->query("SELECT SUM(qty * harga_satuan) AS jml FROM sarpras_detail WHERE kode_pengajuan = '$kode' ")->row();

		$psn = '*INFORMASI PENGAJUAN SARPRAS*

pengajuan dari :
    
Lembaga : Biro Umum - Sarpras
Kode Pengajuan : ' . $kode . '
Nominal : _*' . rupiah($dataSum->jml) . '*_
*DISETUJUI Oleh Sub Bagian Accounting pada ' . $tgl . '*

*_dimohon kepada KPA lembaga terkait untuk segera melakukan Pencairan kepada Kasir di Sekretariat kantor bendahara_*

Terimakasih';

		if ($this->db->affected_rows() > 0) {
			kirim_group($this->apiKey, '120363040973404347@g.us', $psn);
			kirim_group($this->apiKey, '120363042148360147@g.us', $psn);
			// kirim_person($this->apiKey, '085235583647', $psn);
			kirim_person($this->apiKey, '085236924510', $psn);

			$this->session->set_flashdata('ok', 'Pengajuan RAB berhasil ditolak');
			redirect('account/sarpras');
		} else {
			$this->session->set_flashdata('error', 'Pengajuan RAB tidak bisa ditolak');
			redirect('account/sarpras');
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
	public function outRutin()
	{

		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$data['data'] = $this->db->query("SELECT pengeluaran_rutin.*, lembaga.nama AS nmLembaga, bidang.nama AS nmBidang FROM lembaga JOIN pengeluaran_rutin ON pengeluaran_rutin.lembaga=lembaga.kode JOIN bidang ON pengeluaran_rutin.lembaga=bidang.kode WHERE pengeluaran_rutin.tahun = '$this->tahun' AND lembaga.tahun = '$this->tahun' AND bidang.tahun = '$this->tahun' ORDER BY pengeluaran_rutin.tanggal DESC ")->result();

		$data['sumData'] = $this->model->getBySum('pengeluaran_rutin', 'tahun', $data['tahun'], 'nominal')->row();

		$data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $data['tahun'])->result();
		$data['bidang'] = $this->model->getBy('bidang', 'tahun', $data['tahun'])->result();


		$this->load->view('account/head', $data);
		$this->load->view('account/outRutin', $data);
		$this->load->view('account/foot');
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
			redirect('account/outRutin');
		} else {
			$this->session->set_flashdata('error', 'Input data gagal');
			redirect('account/outRutin');
		}
	}

	public function delOutRutin($id)
	{
		$this->model->delete('pengeluaran_rutin', 'id_pengeluaran_rutin', $id);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Hapus data sukses');
			redirect('account/outRutin');
		} else {
			$this->session->set_flashdata('error', 'Hapus data gagal');
			redirect('account/outRutin');
		}
	}

	public function kasHarian()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$kas1 = $this->db->query("SELECT tgl_bayar AS tanggal, 'PESANTREN' AS jenis , SUM(nominal) as debit, 0 AS kredit FROM `pesantren` WHERE tahun = '$this->tahun' GROUP BY tgl_bayar 
UNION
SELECT tgl AS tanggal, 'REALISASI' AS jenis, 0 AS debit, SUM(nominal) AS kredit FROM realis WHERE tahun = '$this->tahun' GROUP BY tgl 

UNION
SELECT tgl_setor AS tanggal, 'REALISASI SISA' AS jenis, SUM(sisa) AS debit, 0 AS kredit FROM real_sisa WHERE tahun = '$this->tahun' GROUP BY tgl_setor 

UNION
SELECT tanggal AS tanggal, 'PENGELUARAN LAIN' AS jenis, 0 AS debit, SUM(nominal) AS kredit FROM keluar WHERE tahun = '$this->tahun' GROUP BY tanggal

ORDER BY tanggal DESC")->result();

		$kas2 = $this->db5->query("SELECT tgl_jalan AS tanggal, 'NIKMUS' AS jenis , 0 as debit, SUM(nom_kriteria + transport + sopir) AS kredit FROM pengajuan WHERE tahun = '$this->tahun' GROUP BY tgl_jalan ORDER BY tgl_jalan ")->result();

		$data['kas'] = array_merge($kas1, $kas2);

		$this->load->view('account/head', $data);
		$this->load->view('account/kasHarian', $data);
		$this->load->view('account/foot');
	}

	public function kasBank()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$kas1 = $this->db->query("SELECT tgl_setor AS tanggal, 'BOS/BPOPP' AS jenis , SUM(nominal) as debit, 0 AS kredit FROM bos WHERE tahun = '$this->tahun' GROUP BY tgl_setor 

		UNION
		SELECT tanggal AS tanggal, 'HONOR' AS jenis , 0 as debit, SUM(nominal)  AS kredit FROM pengeluaran_rutin WHERE tahun = '$this->tahun' AND langganan = 'HONOR' GROUP BY tanggal
		
		UNION
		SELECT tgl AS tanggal, 'BP' AS jenis, SUM(nominal) AS debit, 0 AS kredit FROM pembayaran WHERE tahun = '$this->tahun' GROUP BY tgl 

		ORDER BY tanggal DESC")->result();

		$kas2 = $this->db6->query("SELECT tgl_bayar AS tanggal, 'PSB' AS jenis , SUM(nominal) as debit, 0 AS kredit FROM bp_daftar GROUP BY tgl_bayar 
		UNION 
		SELECT tgl_bayar AS tanggal, 'PSB' AS jenis , SUM(nominal) as debit, 0 AS kredit FROM regist GROUP BY tgl_bayar 
		ORDER BY tanggal DESC ")->result();

		$data['kas'] = array_merge($kas1, $kas2);

		$this->load->view('account/head', $data);
		$this->load->view('account/kasBank', $data);
		$this->load->view('account/foot');
	}

	public function kasPajak()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$data['kas'] = $this->db->query("SELECT tanggal AS tanggal, 'PAJAK' AS jenis , 0 as debit, SUM(nominal) AS kredit FROM pajak WHERE tahun = '$this->tahun' GROUP BY tanggal ORDER BY tanggal DESC")->result();

		$this->load->view('account/head', $data);
		$this->load->view('account/kasPajak', $data);
		$this->load->view('account/foot');
	}

	public function kasPanjar()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$kas1 = $this->db->query("SELECT sarpras.tanggal AS tanggal, 'SARPRAS' AS jenis , 0 as debit, SUM(sarpras_detail.qty * sarpras_detail.harga_satuan) AS kredit FROM sarpras JOIN sarpras_detail ON sarpras.kode_pengajuan = sarpras_detail.kode_pengajuan WHERE sarpras_detail.tahun = '$this->tahun' AND sarpras.tahun = '$this->tahun' GROUP BY sarpras.tanggal ORDER BY sarpras.tanggal DESC")->result();

		$kas2 = $this->db6->query("SELECT tanggal AS tanggal, 'PSB' AS jenis , 0 as debit, SUM(qty * harga_satuan) AS kredit FROM pengajuan JOIN pengajuan_detail ON pengajuan.kode_pengajuan=pengajuan_detail.kode_pengajuan WHERE status = 'dicairkan' OR status = 'selesai' GROUP BY tanggal ORDER BY tanggal ")->result();

		$data['kas'] = array_merge($kas1, $kas2);

		$this->load->view('account/head', $data);
		$this->load->view('account/kasPanjar', $data);
		$this->load->view('account/foot');
	}

	public function kasHutang()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$data['kas'] = $this->db->query("SELECT tanggal AS tanggal, 'LISTRIK/WIFI/HONOR' AS jenis , 0 as debit, SUM(nominal)  AS kredit FROM pengeluaran_rutin WHERE tahun = '$this->tahun' GROUP BY tanggal 
UNION
SELECT tgl_pinjam AS tanggal, 'PEMINJAMAN/BON' AS jenis, 0 AS debit, SUM(nominal) AS kredit FROM peminjaman WHERE tahun = '$this->tahun' GROUP BY tgl_pinjam 

UNION
SELECT tgl_setor AS tanggal, 'CICILAN PEMINJAMAN' AS jenis, SUM(nominal) AS debit, 0 AS kredit FROM cicilan WHERE tahun = '$this->tahun' GROUP BY tgl_setor 

ORDER BY tanggal DESC")->result();

		$this->load->view('account/head', $data);
		$this->load->view('account/kasHutang', $data);
		$this->load->view('account/foot');
	}

	public function kasDekosan()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$data['kas'] = $this->db2->query("SELECT tgl AS tanggal, 'DEKOSAN' AS jenis , 0 as debit, SUM(nominal) AS kredit FROM setor WHERE tahun = '$this->tahun' GROUP BY tgl ORDER BY tgl DESC")->result();

		$this->load->view('account/head', $data);
		$this->load->view('account/kasDekosan', $data);
		$this->load->view('account/foot');
	}

	public function kasAll()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		// KAS HARIAN
		$kas1 = $this->db->query("SELECT tgl_bayar AS tanggal, 'KAS HARIAN' AS jenis , SUM(nominal) as debit, 0 AS kredit FROM `pesantren` WHERE tahun = '$this->tahun' GROUP BY tgl_bayar 
UNION
SELECT tgl AS tanggal, 'KAS HARIAN' AS jenis, 0 AS debit, SUM(nominal) AS kredit FROM realis WHERE tahun = '$this->tahun' GROUP BY tgl 

UNION
SELECT tgl_setor AS tanggal, 'KAS HARIAN' AS jenis, SUM(sisa) AS debit, 0 AS kredit FROM real_sisa WHERE tahun = '$this->tahun' GROUP BY tgl_setor 

UNION
SELECT tanggal AS tanggal, 'KAS HARIAN' AS jenis, 0 AS debit, SUM(nominal) AS kredit FROM keluar WHERE tahun = '$this->tahun' GROUP BY tanggal

-- KAS BANK
UNION
SELECT tgl_setor AS tanggal, 'KAS BANK' AS jenis , SUM(nominal) as debit, 0 AS kredit FROM bos WHERE tahun = '$this->tahun' GROUP BY tgl_setor 

UNION
SELECT tgl AS tanggal, 'KAS BANK' AS jenis, SUM(nominal) AS debit, 0 AS kredit FROM pembayaran WHERE tahun = '$this->tahun' GROUP BY tgl 

-- KAS HUTANG
UNION
SELECT tanggal AS tanggal, 'KAS HUTANG' AS jenis , 0 as debit, SUM(nominal)  AS kredit FROM pengeluaran_rutin WHERE tahun = '$this->tahun' GROUP BY tanggal 


UNION
SELECT tgl_pinjam AS tanggal, 'KAS HUTANG' AS jenis, 0 AS debit, SUM(nominal) AS kredit FROM peminjaman WHERE tahun = '$this->tahun' GROUP BY tgl_pinjam 

UNION
SELECT tgl_setor AS tanggal, 'KAS HUTANG' AS jenis, SUM(nominal) AS debit, 0 AS kredit FROM cicilan WHERE tahun = '$this->tahun' GROUP BY tgl_setor 

-- KAS PAJAK
UNION
SELECT tanggal AS tanggal, 'KAS PAJAK' AS jenis , 0 as debit, SUM(nominal) AS kredit FROM pajak WHERE tahun = '$this->tahun' GROUP BY tanggal 

-- KAS PANJAR
UNION
SELECT sarpras.tanggal AS tanggal, 'KAS PANJAR' AS jenis , 0 as debit, SUM(sarpras_detail.qty * sarpras_detail.harga_satuan) AS kredit FROM sarpras JOIN sarpras_detail ON sarpras.kode_pengajuan = sarpras_detail.kode_pengajuan WHERE sarpras_detail.tahun = '$this->tahun' AND sarpras.tahun = '$this->tahun' GROUP BY sarpras.tanggal

ORDER BY tanggal DESC")->result();

		$kas2 = $this->db5->query("SELECT tgl_jalan AS tanggal, 'KAS HARIAN' AS jenis , 0 as debit, SUM(nom_kriteria + transport + sopir) AS kredit FROM pengajuan WHERE tahun = '$this->tahun' GROUP BY tgl_jalan ORDER BY tgl_jalan ")->result();

		$kas3 = $this->db2->query("SELECT tgl AS tanggal, 'KAS DEKOSAN' AS jenis , 0 as debit, SUM(nominal) AS kredit FROM setor WHERE tahun = '$this->tahun' GROUP BY tgl ORDER BY tgl DESC")->result();

		$data['kas'] = array_merge($kas1, $kas2, $kas3);

		$this->load->view('account/head', $data);
		$this->load->view('account/kasBesar', $data);
		$this->load->view('account/foot');
	}

	public function kasCadangan()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$data['kas'] = $this->db->query("SELECT tanggal AS tanggal, 'CADANGAN' AS jenis , SUM(nominal) as debit, 0 AS kredit FROM cadangan WHERE tahun = '$this->tahun' GROUP BY tanggal ORDER BY tanggal DESC")->result();

		$this->load->view('account/head', $data);
		$this->load->view('account/kasCadangan', $data);
		$this->load->view('account/foot');
	}

	public function panjar()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$data['panjar'] = $this->model->getBy('panjar', 'tahun', $this->tahun)->result();
		$data['total'] = $this->db->query("SELECT SUM(nominal) AS total FROM panjar WHERE tahun = '$this->tahun' ")->row();

		$this->load->view('account/head', $data);
		$this->load->view('account/panjar', $data);
		$this->load->view('account/foot');
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
		$config['allowed_types']        = 'pdf|jpg|jpeg';
		$config['file_name']            = $file_name;
		$config['overwrite']            = true;
		$config['max_size']             = 10240; // 10MB
		$config['max_width']            = 1080;
		$config['max_height']           = 1080;

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('berkas')) {
			// $data['error'] = $this->upload->display_errors();
			$this->session->set_flashdata('error', 'Gagal diupload. pastikan file berupa PDF dan tidak melebihi 5 Mb');
			redirect('account/panjar');
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
				redirect('account/panjar');
			} else {
				$this->session->set_flashdata('error', 'Input data baru gagal');
				redirect('account/panjar');
			}
		}
	}

	public function cadangan()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$data['cadangan'] = $this->model->getBy2('cadangan', 'tahun',  $this->tahun, 'jenis', 'masuk')->result();

		$this->load->view('account/head', $data);
		$this->load->view('account/cadangan', $data);
		$this->load->view('account/foot');
	}

	public function saveCadangan()
	{
		$id = $this->uuid->v4();
		$ket = $this->input->post('ket', true);
		$tanggal = $this->input->post('tanggal', true);
		$nominal = rmRp($this->input->post('nominal', true));
		$jenis = $this->input->post('jenis', true);
		$berkas = $this->input->post('berkas', true);

		$rdrc = $jenis == 'masuk' ? 'account/cadangan' : 'account/cadanganKeluar';

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
				$this->session->set_flashdata('error', 'Gagal diupload. pastikan file berupa PDF dan tidak melebihi 5 Mb');
				redirect($rdrc);
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
					redirect($rdrc);
				} else {
					$this->session->set_flashdata('error', 'Input data baru gagal');
					redirect($rdrc);
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
				redirect($rdrc);
			} else {
				$this->session->set_flashdata('error', 'Input data baru gagal');
				redirect($rdrc);
			}
		}
	}

	public function talangan()
	{
		$data['pes'] = $this->model->getBy('talangan', 'tahun', $this->tahun)->result();
		$data['sumPes'] = $this->model->selectSum('talangan', 'nominal', 'tahun', $this->tahun)->row();
		$data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
		$data['tahunData'] = $this->model->getAll('tahun')->result();
		$data['bidang'] = $this->model->getBy('bidang', 'tahun', $this->tahun)->result();
		$data['user'] = $this->Auth_model->current_user();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");
		$data['tahun'] = $this->tahun;
		$this->load->view('account/head', $data);
		$this->load->view('account/masukTalangan', $data);
		$this->load->view('account/foot');
	}

	public function talanganAdd()
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

		$this->model->input('talangan', $data);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Input Pemasukan talangan Berhasil');
			redirect('account/talangan');
		} else {
			$this->session->set_flashdata('error', 'Input Pemasukan talangan Gagal');
			redirect('account/talangan');
		}
	}

	public function delTalangan($id)
	{
		$this->model->delete('talangan', 'id_pes', $id);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Hapus Pemasukan talangan Berhasil');
			redirect('account/talangan');
		} else {
			$this->session->set_flashdata('error', 'Hapus Pemasukan talangan Gagal');
			redirect('account/talangan');
		}
	}

	public function ppdb()
	{
		$data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
		$data['tahunData'] = $this->model->getAll('tahun')->result();
		$data['bidang'] = $this->model->getBy('bidang', 'tahun', $this->tahun)->result();
		$data['user'] = $this->Auth_model->current_user();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");
		$data['tahun'] = $this->tahun;

		$data['masuk'] = $this->model->pemsukanPsb();
		$data['keluar'] = $this->model->pengeluaranPsb();

		$data['data'] = $this->model->dataPengeluaranPsb()->result();

		$this->load->view('account/head', $data);
		$this->load->view('account/ppdb', $data);
		$this->load->view('account/foot');
	}

	function haflah()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$data['data'] = $this->model->getBy('haflah', 'tahun', $this->tahun)->result();
		$data['pagu'] = $this->model->getBySum('haflah_bidang', 'tahun', $this->tahun, 'pagu')->row();
		$data['pakai'] = $this->db->query("SELECT SUM(harga_satuan*qty) AS jml FROM haflah_detail JOIN haflah ON haflah_detail.kode_pengajuan=haflah.kode_pengajuan WHERE haflah_detail.tahun = '$this->tahun' AND haflah.tahun = '$this->tahun' ")->row();

		$data['dataSpj'] = $this->model->getSPJHaflah($this->tahun)->result();
		$data['dataSr'] = $this->db->query("SELECT * FROM spj JOIN lembaga ON spj.lembaga=lembaga.kode JOIN haflah ON haflah.kode_pengajuan=spj.kode_pengajuan WHERE spj.kode_pengajuan LIKE '%.HFL.%' AND spj.tahun = '$this->tahun' AND lembaga.tahun = '$this->tahun' AND file_spj != '' ")->result();

		$data['bulan'] = $this->bulan;

		$this->load->view('account/head', $data);
		$this->load->view('account/haflah', $data);
		$this->load->view('account/foot');
	}

	function haflahDetail($kode)
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$data['data'] = $this->db->query("SELECT haflah_detail.*, haflah_bidang.nama FROM haflah_detail JOIN haflah_bidang ON haflah_detail.bidang=haflah_bidang.kode_bidang WHERE kode_pengajuan = '$kode' AND haflah_bidang.tahun = '$this->tahun' AND haflah_detail.tahun = '$this->tahun' ORDER BY bidang ASC ")->result();

		$data['program'] = $this->db->query("SELECT bidang, SUM(qty * harga_satuan) AS jumlah, COUNT(*) AS items FROM haflah_detail WHERE kode_pengajuan = '$kode' GROUP BY bidang ")->result();

		$data['dataSum'] = $this->db->query("SELECT SUM(qty * harga_satuan) AS jml FROM haflah_detail WHERE kode_pengajuan = '$kode' ")->row();


		$data['pj'] = $this->db->query("SELECT * FROM haflah WHERE kode_pengajuan = '$kode'")->row();

		$data['lembagaData'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
		$data['jenis'] = $this->model->getBy('jenis', 'tahun', $this->tahun)->result();

		$this->load->view('account/head', $data);
		$this->load->view('account/haflahInput', $data);
		$this->load->view('account/foot');
	}

	function tolakHaflah()
	{
		$data = ['status' => 'ditolak'];
		$kode = $this->input->post('kode', true);
		$tgl = $this->input->post('tgl', true);
		$pesan = $this->input->post('pesan', true);

		$this->model->update('haflah', $data, 'kode_pengajuan', $kode);

		$psn = '*INFORMASI PENGAJUAN HAFLAH*

pengajuan dari :
    
Lembaga : Haflah Pesantren
Kode Pengajuan : ' . $kode . '
*DITOLAK Oleh Sub Bagian Accounting pada ' . $tgl . '*
dengan catatan : _*' . $pesan . '*_

*_dimohon kepada KPA lembaga terkait untuk segera melakukan revisi sesuai dengan catatan yang ada di https://simkupaduka.ppdwk.com/_*

Terimakasih';

		if ($this->db->affected_rows() > 0) {
			kirim_group($this->apiKey, '120363040973404347@g.us', $psn);
			kirim_group($this->apiKey, '120363042148360147@g.us', $psn);
			// kirim_person($this->apiKey, '085235583647', $psn);
			kirim_person($this->apiKey, '085236924510', $psn);

			$this->session->set_flashdata('ok', 'Pengajuan RAB berhasil ditolak');
			redirect('account/haflah');
		} else {
			$this->session->set_flashdata('error', 'Pengajuan RAB tidak bisa ditolak');
			redirect('account/haflah');
		}
	}

	function vervalHaflah($kode)
	{
		$data = ['status' => 'disetujui'];

		$this->model->update('haflah', $data, 'kode_pengajuan', $kode);
		$tgl = date('d-m-Y H:i');
		$dataSum = $this->db->query("SELECT SUM(qty * harga_satuan) AS jml FROM haflah_detail WHERE kode_pengajuan = '$kode' ")->row();

		$psn = '*INFORMASI PENGAJUAN HAFLAH*

pengajuan dari :
    
Lembaga : Haflah Pesantren
Kode Pengajuan : ' . $kode . '
Nominal : _*' . rupiah($dataSum->jml) . '*_
*DISETUJUI Oleh Sub Bagian Accounting pada ' . $tgl . '*

*_dimohon kepada KPA lembaga terkait untuk segera melakukan Pencairan kepada Kasir di Sekretariat kantor bendahara_*

Terimakasih';

		if ($this->db->affected_rows() > 0) {
			kirim_group($this->apiKey, '120363040973404347@g.us', $psn);
			kirim_group($this->apiKey, '120363042148360147@g.us', $psn);
			// kirim_person($this->apiKey, '085235583647', $psn);
			kirim_person($this->apiKey, '085236924510', $psn);

			$this->session->set_flashdata('ok', 'Pengajuan RAB berhasil ditolak');
			redirect('account/haflah');
		} else {
			$this->session->set_flashdata('error', 'Pengajuan RAB tidak bisa ditolak');
			redirect('account/haflah');
		}
	}

	public function haflahEditInput()
	{
		$id = $this->input->post('id', true);
		$kode = $this->input->post('kode', true);
		$jns = explode('-', $this->input->post('jenis', true));
		$data = [
			'qty' => $this->input->post('qty', true),
			'satuan' => $this->input->post('satuan', true),
			'harga_satuan' => rmRp($this->input->post('harga_satuan', true)),
			'jenis' => $jns[0],
			'stas' => $jns[1],
		];

		$this->model->update('haflah_detail', $data, 'id_detail', $id);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Pengajuan Haflah berhasil diupdate');
			redirect('account/haflahDetail/' . $kode);
		} else {
			$this->session->set_flashdata('error', 'Pengajuan Haflah tidak bisa diupdate');
			redirect('account/haflahDetail/' . $kode);
		}
	}

	public function haflahEs()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$data['program'] = $this->model->getByOrd('haflah_bidang', 'tahun', $this->tahun, 'kode_bidang', 'ASC')->result();


		$this->load->view('account/head', $data);
		$this->load->view('account/haflahEs', $data);
		$this->load->view('account/foot');
	}

	public function analisisKeluar()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$data['keluar'] = $this->db->query("SELECT lembaga.nama AS ket, SUM(rab.qty * rab.harga_satuan) AS total_rab, (SELECT SUM(nominal) FROM realis WHERE tahun = '$this->tahun' AND lembaga = rab.lembaga) AS pakai FROM rab JOIN lembaga ON rab.lembaga=lembaga.kode WHERE rab.tahun = '$this->tahun' AND lembaga.tahun = '$this->tahun' GROUP BY rab.lembaga
UNION 
SELECT 'Kebijakan Kepala' AS ket, 50000000 AS total_rab, SUM(nominal) as pakai FROM kebijakan WHERE tahun = '$this->tahun'
UNION 
SELECT 'Pengeluaran' AS ket, 0 AS total_rab, SUM(nominal) as pakai FROM keluar WHERE tahun = '$this->tahun'
UNION 
SELECT 'Peminjaman' AS ket, 0 AS total_rab, SUM(nominal) as pakai FROM peminjaman WHERE tahun = '$this->tahun'
UNION 
SELECT 'Panjar' AS ket, 0 AS total_rab, SUM(nominal) as pakai FROM panjar WHERE tahun = '$this->tahun'
UNION 
SELECT 'HONOR (PR)' AS ket, 2736846000 AS total_rab, SUM(nominal) as pakai FROM pengeluaran_rutin WHERE tahun = '$this->tahun' AND langganan = 'HONOR'
UNION 
SELECT 'LISTRIK (PR)' AS ket, 179628000 AS total_rab, SUM(nominal) as pakai FROM pengeluaran_rutin WHERE tahun = '$this->tahun' AND langganan = 'LISTRIK'
UNION 
SELECT 'INTERNET (PR)' AS ket, 62229000 AS total_rab, SUM(nominal) as pakai FROM pengeluaran_rutin WHERE tahun = '$this->tahun' AND langganan = 'INTERNET'
UNION 
SELECT 'Sarpras' AS ket,150000000 AS total_rab, SUM(qty*harga_satuan) as pakai FROM sarpras_detail JOIN sarpras ON sarpras_detail.kode_pengajuan=sarpras.kode_pengajuan WHERE sarpras_detail.tahun = '$this->tahun' AND sarpras.status = 'dicairkan'
UNION 
SELECT 'Haflah' AS ket,0 AS total_rab, SUM(qty*harga_satuan) as pakai FROM haflah_detail JOIN haflah ON haflah_detail.kode_pengajuan=haflah.kode_pengajuan WHERE haflah_detail.tahun = '$this->tahun' AND (haflah.status = 'dicairkan' OR haflah.status = 'selesai') ")->result();

		$data['dekos'] = $this->model->getDekosSum($this->tahun)->result();
		$data['nikmus'] = $this->model->getNikmusSum($this->tahun)->result();
		$data['pengajuanPsb'] = $this->model->pengajuanPsb()->result();

		$this->load->view('account/head', $data);
		$this->load->view('account/analisisKeluar', $data);
		$this->load->view('account/foot');
	}

	public function analisisMasuk()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$data['keluar'] = $this->db->query("SELECT 'Pemasukan BP' AS ket, SUM(nominal) AS nominal FROM pembayaran WHERE tahun = '$this->tahun'
UNION 
SELECT 'BOS/BPOPP' AS ket, SUM(nominal) AS nominal FROM bos WHERE tahun = '$this->tahun'
UNION 
SELECT 'Talangan' AS ket, SUM(nominal) AS nominal FROM talangan WHERE tahun = '$this->tahun'
UNION 
SELECT 'Cicilan' AS ket, SUM(nominal) AS nominal FROM cicilan WHERE tahun = '$this->tahun' ")->result();

		$data['tabungan'] = $this->modelAll->tabuganSantri($this->tahun);

		$data['daftar'] = $this->modelAll->getBySumPsb('bp_daftar', 'nominal <>', '', 'nominal')->result();
		$data['regist'] = $this->modelAll->getBySumPsb('regist', 'nominal <>', '', 'nominal')->result();

		$this->load->view('account/head', $data);
		$this->load->view('account/analisisMasuk', $data);
		$this->load->view('account/foot');
	}


	public function cadanganKeluar()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");

		$data['cadangan'] = $this->model->getBy2('cadangan', 'tahun',  $this->tahun, 'jenis', 'keluar')->result();

		$this->load->view('account/head', $data);
		$this->load->view('account/cadanganOut', $data);
		$this->load->view('account/foot');
	}

	public function delCadangan($id)
	{
		$data = $this->model->getBy('cadangan', 'id_cadangan', $id)->row();
		$rdrc = $data->jenis == 'masuk' ? 'account/cadangan' : 'account/cadanganKeluar';

		$this->model->delete('cadangan', 'id_cadangan', $id);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Input data baru berhasil');
			redirect($rdrc);
		} else {
			$this->session->set_flashdata('error', 'Input data baru gagal');
			redirect($rdrc);
		}
	}

	public function bungaBank()
	{
		$data['data'] = $this->model->getBy('bunga_bank', 'tahun', $this->tahun)->result();
		$data['sum'] = $this->model->selectSum('bunga_bank', 'nominal', 'tahun', $this->tahun)->row();
		$data['tahunData'] = $this->model->getAll('tahun')->result();

		$data['user'] = $this->Auth_model->current_user();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");
		$data['tahun'] = $this->tahun;

		$this->load->view('account/head', $data);
		$this->load->view('account/bungaBank', $data);
		$this->load->view('account/foot');
	}

	public function bungaAdd()
	{
		$data = [
			'tanggal' => $this->input->post('tanggal', true),
			'nominal' => rmRp($this->input->post('nominal', true)),
			'ket' => $this->input->post('ket', true),
			'bulan' => $this->input->post('bulan', true),
			'tahun' => $this->input->post('tahun', true),
			'kasir' => $this->user,
			'at' => date('Y-m-d H:i:s')
		];

		$this->model->input('bunga_bank', $data);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Input Pemasukan Bunga Bank Berhasil');
			redirect('account/bungaBank');
		} else {
			$this->session->set_flashdata('error', 'Input Pemasukan Bunga Bank Gagal');
			redirect('account/bungaBank');
		}
	}
	public function bungaEdit()
	{
		$data = [
			'tanggal' => $this->input->post('tanggal', true),
			'nominal' => rmRp($this->input->post('nominal', true)),
			'ket' => $this->input->post('ket', true),
			'bulan' => $this->input->post('bulan', true),
			'tahun' => $this->input->post('tahun', true),
		];
		$id = $this->input->post('id', true);

		$this->model->update('bunga_bank', $data, 'id_bunga', $id);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Update Pemasukan Bunga Bank Berhasil');
			redirect('account/bungaBank');
		} else {
			$this->session->set_flashdata('error', 'Update Pemasukan Bunga Bank Gagal');
			redirect('account/bungaBank');
		}
	}

	public function delBunga($id)
	{
		$this->model->delete('bunga_bank', 'id_bunga', $id);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Hapus Pemasukan Bunga Bank Berhasil');
			redirect('account/bungaBank');
		} else {
			$this->session->set_flashdata('error', 'Hapus Pemasukan Bunga Bank Gagal');
			redirect('account/bungaBank');
		}
	}
}
