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
		$this->load->model('Auth_model');

		$user = $this->Auth_model->current_user();
		$this->tahun = $this->session->userdata('tahun');
		// $this->jenis = ['A. Belanja Barang', 'B. Langganan & Jasa', 'Belanja Kegiatan', 'D. Umum'];
		$this->bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juli', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

		$api = $this->model->apiKey()->row();
		$this->apiKey = $api->nama_key;
		$this->user = $user->nama;
		$this->lembaga = $user->lembaga;

		if (!$this->Auth_model->current_user() || $user->level != 'account') {
			redirect('login/logout');
		}
	}

	public function index()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;

		$bos = $this->model->getBySum('bos', 'tahun', $this->tahun, 'nominal')->row();
		$pembayaran = $this->model->getBySum('pembayaran', 'tahun', $this->tahun, 'nominal')->row();
		$pesantren = $this->model->getBySum('pesantren', 'tahun', $this->tahun, 'nominal')->row();
		$kebijakan = $this->model->getBySum('kebijakan', 'tahun', $this->tahun, 'nominal')->row();
		$realis = $this->model->getBySum('realis', 'tahun', $this->tahun, 'nom_serap')->row();
		$keluar = $this->model->getBySum('keluar', 'tahun', $this->tahun, 'nominal')->row();
		$data['dekos'] = $this->model->getDekosSum($this->tahun)->row();
		$data['nikmus'] = $this->model->getNikmusSum($this->tahun)->row();

		$sumPinjam = $this->model->getBySum('peminjaman', 'tahun', $this->tahun, 'nominal')->row();
		$sumCicil = $this->model->getBySum('cicilan', 'tahun', $this->tahun, 'nominal')->row();

		$realSisa = $this->model->getBySum('real_sisa', 'tahun', $this->tahun, 'sisa')->row();
		$data['masuk'] = $bos->jml + $pembayaran->jml + $pesantren->jml + $sumCicil->jml + $realSisa->jml;
		$data['keluar'] = $kebijakan->jml + $realis->jml + $data['dekos']->nominal + $data['nikmus']->nom_kriteria + $data['nikmus']->transport + $data['nikmus']->sopir + $keluar->jml + $sumPinjam->jml;

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
		$data['data'] = $this->model->getBy2('rab', 'lembaga', $kode, 'tahun', $this->tahun)->result();
		$data['lembaga'] = $this->model->getBy('lembaga', 'kode', $kode)->row();
		$data['sumA'] = $this->model->getTotalRabJenis('A', $kode, $this->tahun)->row();
		$data['sumB'] = $this->model->getTotalRabJenis('B', $kode, $this->tahun)->row();
		$data['sumC'] = $this->model->getTotalRabJenis('C', $kode, $this->tahun)->row();
		$data['sumD'] = $this->model->getTotalRabJenis('D', $kode, $this->tahun)->row();
		$data['user'] = $this->Auth_model->current_user();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");
		$data['tahun'] = $this->tahun;
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
		$data['data'] = $this->model->getBy2('pak', 'status', 'proses', 'tahun', $this->tahun)->result();
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
		$data['rabnew'] = $this->model->getBy2('rab_sm', 'lembaga', $data['data']->lembaga, 'tahun', $this->tahun)->result();
		$data['rpakSum'] = $this->model->selectSum('pak_detail', 'total', 'kode_pak', $kode)->row();
		$data['rabnewSum'] = $this->model->selectSum('rab_sm', 'total', 'lembaga', $data['data']->lembaga)->row();
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
			// kirim_person($this->apiKey, '085236924510', $psn);

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

		$data['sumA'] = $this->model->getTotalRabJenis('A', $lembaga, $this->tahun)->row();
		$data['sumB'] = $this->model->getTotalRabJenis('B', $lembaga, $this->tahun)->row();
		$data['sumC'] = $this->model->getTotalRabJenis('C', $lembaga, $this->tahun)->row();
		$data['sumD'] = $this->model->getTotalRabJenis('D', $lembaga, $this->tahun)->row();

		$data['pakaiA'] = $this->model->getTotalRealJenis('A', $lembaga, $this->tahun)->row();
		$data['pakaiB'] = $this->model->getTotalRealJenis('B', $lembaga, $this->tahun)->row();
		$data['pakaiC'] = $this->model->getTotalRealJenis('C', $lembaga, $this->tahun)->row();
		$data['pakaiD'] = $this->model->getTotalRealJenis('D', $lembaga, $this->tahun)->row();

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
		$data['lembaga'] = $this->model->getBy('lembaga', 'kode', $lembaga)->row();

		if ($data['rinci']->cair == 1) {
			$data['data'] = $this->model->getBy('realis', 'kode_pengajuan', $kode)->result();
			$data['nom'] = $this->model->getBySum('realis', 'kode_pengajuan', $kode, 'nominal')->row();
			$data['nomCair'] = $this->model->getBySum('realis', 'kode_pengajuan', $kode, 'nom_cair')->row();
		} else {
			$data['data'] = $this->model->getBy('real_sm', 'kode_pengajuan', $kode)->result();
			$data['nom'] = $this->model->getBySum('real_sm', 'kode_pengajuan', $kode, 'nominal')->row();
			$data['nomCair'] = $this->model->getBySum('real_sm', 'kode_pengajuan', $kode, 'nom_cair')->row();
		}

		$data['honor'] = $this->model->getBy('honor_file', 'kode_pengajuan', $kode);

		$sumA = $this->model->getTotalRabJenis('A', $lembaga, $this->tahun)->row();
		$sumB = $this->model->getTotalRabJenis('B', $lembaga, $this->tahun)->row();
		$sumC = $this->model->getTotalRabJenis('C', $lembaga, $this->tahun)->row();
		$sumD = $this->model->getTotalRabJenis('D', $lembaga, $this->tahun)->row();

		$pakaiA = $this->model->getTotalRealJenis('A', $lembaga, $this->tahun)->row();
		$pakaiB = $this->model->getTotalRealJenis('B', $lembaga, $this->tahun)->row();
		$pakaiC = $this->model->getTotalRealJenis('C', $lembaga, $this->tahun)->row();
		$pakaiD = $this->model->getTotalRealJenis('D', $lembaga, $this->tahun)->row();

		$data['sisaA'] = $sumA->total - $pakaiA->nominal;
		$data['sisaB'] = $sumB->total - $pakaiB->nominal;
		$data['sisaC'] = $sumC->total - $pakaiC->nominal;
		$data['sisaD'] = $sumD->total - $pakaiD->nominal;

		$data['nomA'] = $this->model->getBySum2('real_sm', 'kode_pengajuan', $kode, 'jenis', 'A', 'nominal')->row();
		$data['nomB'] = $this->model->getBySum2('real_sm', 'kode_pengajuan', $kode, 'jenis', 'B', 'nominal')->row();
		$data['nomC'] = $this->model->getBySum2('real_sm', 'kode_pengajuan', $kode, 'jenis', 'C', 'nominal')->row();
		$data['nomD'] = $this->model->getBySum2('real_sm', 'kode_pengajuan', $kode, 'jenis', 'D', 'nominal')->row();
		$data['user'] = $this->Auth_model->current_user();
		$data['pjnData'] = $this->model->getBy2('pengajuan', 'tahun', $this->tahun, 'verval', 0);
		$data['spjData'] = $this->db->query("SELECT * FROM spj WHERE stts = 1 OR stts = 2 AND tahun = '$this->tahun' ");
		$data['tahun'] = $this->tahun;
		$this->load->view('account/head', $data);
		$this->load->view('account/pengajuanDetail', $data);
		$this->load->view('account/foot');
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
		$data2 = ['verval' => '1', 'apr' => '1'];

		if (preg_match("/DISP./i", $kode)) {
			$rt = '*(DISPOSISI)*';
		} else {
			$rt = '';
		}

		$psn = '
*INFORMASI PENCAIRAN PENGAJUAN* ' . $rt . '

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
			kirim_group($this->apiKey, '120363040973404347@g.us', $psn);
			kirim_group($this->apiKey, '120363042148360147@g.us', $psn);
			// kirim_person($this->apiKey, '082264061060', $psn);
			// kirim_person($this->apiKey, '085236924510', $psn);

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
		// $isi =  $this->input->post('isi', true);
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

*_SPJ telah disetujui oleh SUB BAGIAN ACCOUNTING. Dimohon kepada KPA untuk segera menyerahkan hard copy SPJ dan sisa belanja anggaran  kepada SUB BAGIAN ACCOUNTING. Untuk bisa melakukan pengajuan berikutnya._*

Terimakasih';

		$data1 = ['stts' => '2'];
		$data2 = ['spj' => '2'];

		$this->model->update('spj', $data1, 'id_spj', $id);
		$this->model->update('pengajuan', $data2, 'kode_pengajuan', $kode);

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
		$idrls = rand(0, 999999999);
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
		$data['pinjam'] = $this->model->getBy('peminjaman', 'tahun', $this->tahun)->result();
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
WHERE tahun = '2023/2024'
GROUP BY rencana
ORDER BY CAST(rencana AS UNSIGNED) ASC ")->result();
		$data['bulan'] = $this->bulan;

		$this->load->view('account/head', $data);
		$this->load->view('account/analisis', $data);
		$this->load->view('account/foot');
	}
}
