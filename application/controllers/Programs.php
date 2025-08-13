<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Programs extends CI_Controller
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

		if (!$this->Auth_model->current_user() || $user->lembaga != '03') {
			redirect('login/logout');
		}
	}

	public function index()
	{
		$kode = $this->lembaga;
		$data['data'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $kode, 'tahun', $this->tahun)->row();

		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;

		$this->load->view('lembaga/head', $data);
		$this->load->view('lembaga/program', $data);
		$this->load->view('lembaga/foot');
	}

	public function detail($id)
	{
		$kode = $this->lembaga;
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $kode, 'tahun', $this->tahun)->row();
		$data['lm'] = $this->model->getBy2('lembaga', 'kode', $id, 'tahun', $this->tahun)->row();
		$data['data'] = $this->model->getBy2('dppk', 'lembaga', $id, 'tahun', $this->tahun)->result();

		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;

		$this->load->view('lembaga/head', $data);
		$this->load->view('lembaga/programDtl', $data);
		$this->load->view('lembaga/foot');
	}

	public function edit($id)
	{
		$kode = $this->lembaga;
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $kode, 'tahun', $this->tahun)->row();
		$data['dt'] = $this->model->getBy('dppk', 'id', $id)->row();

		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;

		$this->load->view('lembaga/head', $data);
		$this->load->view('lembaga/programEdit', $data);
		$this->load->view('lembaga/foot');
	}
	public function ubah()
	{
		$id = $this->input->post('id', true);
		$program = $this->input->post('program', true);
		$bulan = $this->input->post('bulan', true);
		$bulanString = implode(',', $bulan);

		$dt = $this->model->getBy('dppk', 'id', $id)->row();
		$data = [
			'program' => $program,
			'bulan' => $bulanString,
		];
		$this->model->update('dppk', $data, 'id', $id);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Update Program Berhasil');
			redirect('programs/detail/' . $dt->lembaga);
		} else {
			$this->session->set_flashdata('error', 'Update Program Gagal');
			redirect('programs/detail/' . $dt->lembaga);
		}
	}

	public function del($id)
	{
		$dtl = $this->model->getBy('dppk', 'id', $id)->row();
		$cek = $this->model->getBy('realis_detail', 'kode_program', $dtl->kode)->row();
		if ($cek) {
			$this->session->set_flashdata('error', 'Program sudah digunakan. tidak bisa dihapus');
			redirect('programs/detail/' . $dtl->lembaga);
			die();
		} else {
			$this->model->delete('dppk', 'id', $id);
			if ($this->db->affected_rows() > 0) {
				$this->session->set_flashdata('ok', 'Hapus Program Berhasil');
				redirect('programs/detail/' . $dtl->lembaga);
			} else {
				$this->session->set_flashdata('error', 'Hapus Program Gagal');
				redirect('programs/detail/' . $dtl->lembaga);
			}
		}
	}
}
