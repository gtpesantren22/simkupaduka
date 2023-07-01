<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Kepala extends CI_Controller
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

		if (!$this->Auth_model->current_user() || $user->level != 'kepala') {
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
		$dekos = $this->model->getDekosSum($this->tahun)->row();
		$nikmus = $this->model->getNikmusSum($this->tahun)->row();
		$sumPinjam = $this->model->getBySum('peminjaman', 'tahun', $this->tahun, 'nominal')->row();
		$sumCicil = $this->model->getBySum('cicilan', 'tahun', $this->tahun, 'nominal')->row();

		$data['masuk'] = $bos->jml + $pembayaran->jml + $pesantren->jml + $sumCicil->jml;
		$data['keluar'] = $kebijakan->jml + $realis->jml + $dekos->nominal + $nikmus->nom_kriteria + $nikmus->transport + $nikmus->sopir + $keluar->jml + $sumPinjam->jml;

		$data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
		$data['saldo'] = $this->model->getBy('saldo', 'name', 'bank');
		$data['cash'] = $this->model->getBy('saldo', 'name', 'cash');

		$this->load->view('kepala/head', $data);
		$this->load->view('kepala/index', $data);
		$this->load->view('kepala/foot');
	}

	public function santri()
	{
		$data['santri'] = $this->model->dataSantri()->result();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;

		$this->load->view('kepala/head', $data);
		$this->load->view('kepala/santri', $data);
		$this->load->view('kepala/foot');
	}

	public function bp()
	{
		$data['bp'] = $this->model->dataBp()->result();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('kepala/head', $data);
		$this->load->view('kepala/bp', $data);
		$this->load->view('kepala/foot');
	}

	public function kode()
	{
		$data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
		$data['bidang'] = $this->model->getBy('bidang', 'tahun', $this->tahun)->result();
		$data['bidangMax'] = $this->model->selectMax('bidang', 'kode')->row();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;

		$this->load->view('kepala/head', $data);
		$this->load->view('kepala/kode', $data);
		$this->load->view('kepala/foot');
	}

	public function pesantren()
	{
		$data['pes'] = $this->model->getBy('pesantren', 'tahun', $this->tahun)->result();
		$data['sumPes'] = $this->model->selectSum('pesantren', 'nominal', 'tahun', $this->tahun)->row();
		$data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
		$data['tahun'] = $this->model->getAll('tahun')->result();
		$data['bidang'] = $this->model->getBy('bidang', 'tahun', $this->tahun)->result();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('kepala/head', $data);
		$this->load->view('kepala/masukPes', $data);
		$this->load->view('kepala/foot');
	}

	public function bos()
	{
		$data['bos'] = $this->model->getBy('bos', 'tahun', $this->tahun)->result();
		$data['sumBos'] = $this->model->selectSum('bos', 'nominal', 'tahun', $this->tahun)->row();
		$data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
		$data['tahun'] = $this->model->getAll('tahun')->result();
		$data['bidang'] = $this->model->getBy('bidang', 'tahun', $this->tahun)->result();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('kepala/head', $data);
		$this->load->view('kepala/masukBos', $data);
		$this->load->view('kepala/foot');
	}

	public function bpMasuk()
	{
		$data['data'] = $this->model->getBy('pembayaran', 'tahun', $this->tahun)->result();
		$data['sumBp'] = $this->model->selectSum('pembayaran', 'nominal', 'tahun', $this->tahun)->row();
		$data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
		$data['tahun'] = $this->model->getAll('tahun')->result();
		$data['bidang'] = $this->model->getBy('bidang', 'tahun', $this->tahun)->result();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('kepala/head', $data);
		$this->load->view('kepala/masukBp', $data);
		$this->load->view('kepala/foot');
	}

	public function rab()
	{
		$data['data'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
		// $data['sumBp'] = $this->model->selectSum('pembayaran', 'nominal', 'tahun', $this->tahun)->row();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('kepala/head', $data);
		$this->load->view('kepala/rab', $data);
		$this->load->view('kepala/foot');
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
		$data['tahun'] = $this->tahun;
		$this->load->view('kepala/head', $data);
		$this->load->view('kepala/rabDetail', $data);
		$this->load->view('kepala/foot');
	}



	public function rab_kbj()
	{
		$data['data'] = $this->model->getByJoin2('kebijakan', 'lembaga', 'lembaga', 'kode', 'tahun', $this->tahun)->result();
		$data['pakai'] = $this->model->getBySum('kebijakan', 'tahun', $this->tahun, 'nominal')->row();
		// $data['lembaga'] = $this->model->getBy('lembaga', 'kode', $data['data']->lembaga)->row();
		$data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
		$data['tahun'] = $this->model->getAll('tahun')->result();
		$data['bidang'] = $this->model->getBy('bidang', 'tahun', $this->tahun)->result();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;

		$this->load->view('kepala/head', $data);
		$this->load->view('kepala/kbj', $data);
		$this->load->view('kepala/foot');
	}

	public function pak()
	{
		$data['data'] = $this->model->getBy2('pak', 'status', 'disetujui', 'tahun', $this->tahun)->result();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('kepala/head', $data);
		$this->load->view('kepala/pak', $data);
		$this->load->view('kepala/foot');
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
		$data['tahun'] = $this->tahun;
		$this->load->view('kepala/head', $data);
		$this->load->view('kepala/pakDetail', $data);
		$this->load->view('kepala/foot');
	}

	public function realis()
	{
		$data['data'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
		$data['tahun_ajaran'] = $this->tahun;
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('kepala/head', $data);
		$this->load->view('kepala/data', $data);
		$this->load->view('kepala/foot');
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
		$data['tahun'] = $this->tahun;
		$this->load->view('kepala/head', $data);
		$this->load->view('kepala/realDetail', $data);
		$this->load->view('kepala/foot');
	}

	public function cekRealis($kode)
	{
		$data['rab'] = $this->model->getBy('rab', 'kode', $kode)->row();
		$data['lem'] = $this->model->getBy2('lembaga', 'kode', $data['rab']->lembaga, 'tahun', $this->tahun)->row();
		$data['tahun_ajaran'] = $this->tahun;
		$data['rel'] = $this->model->getBySum('realis', 'kode', $kode, 'nominal')->row();
		$data['relData'] = $this->model->getBy('realis', 'kode', $kode)->result();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('kepala/head', $data);
		$this->load->view('kepala/cekRab', $data);
		$this->load->view('kepala/foot');
	}

	public function pengajuan()
	{
		$data['data'] = $this->db->query("SELECT * FROM pengajuan JOIN lembaga ON pengajuan.lembaga=lembaga.kode WHERE pengajuan.tahun = '$this->tahun' AND lembaga.tahun = '$this->tahun' AND spj != 3")->result();
		$data['bulan'] = $this->bulan;
		$data['tahun'] = $this->tahun;
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('kepala/head', $data);
		$this->load->view('kepala/pengajuan', $data);
		$this->load->view('kepala/foot');
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
		$data['tahun'] = $this->tahun;

		$this->load->view('kepala/head', $data);
		$this->load->view('kepala/pengajuanDetail', $data);
		$this->load->view('kepala/foot');
	}

	public function spj()
	{
		$data['data'] = $this->model->getSPJ($this->tahun)->result();
		$data['bulan'] = $this->bulan;
		$data['tahun'] = $this->tahun;
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('kepala/head', $data);
		$this->load->view('kepala/spj', $data);
		$this->load->view('kepala/foot');
	}


	public function disposisi()
	{

		$data['data'] = $this->model->getDispo($this->tahun)->result();
		$data['pakai'] = $this->model->dispPakai($this->tahun)->row();
		$data['bulan'] = $this->bulan;
		$data['tahun'] = $this->tahun;

		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('kepala/head', $data);
		$this->load->view('kepala/disp', $data);
		$this->load->view('kepala/foot');
	}
	public function akun()
	{

		$data['data'] = $this->model->getUser($this->tahun)->result();
		$data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
		$data['bulan'] = $this->bulan;
		$data['tahun'] = $this->tahun;

		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('kepala/head', $data);
		$this->load->view('kepala/akun', $data);
		$this->load->view('kepala/foot');
	}

	public function editUser()
	{
		$id_user =  $this->input->post('id_user', true);
		$lembaga =  $this->input->post('lembaga', true);
		$aktif =  $this->input->post('aktif', true);
		$level =  $this->input->post('level', true);
		$hp =  $this->input->post('hp', true);
		$hp_kep =  $this->input->post('hp_kep', true);
		$kd_lem =  $this->input->post('kd_lem', true);

		$data = [
			'level' => $level,
			'aktif' => $aktif,
			'lembaga' => $lembaga,
		];
		$data2 = [
			'hp' => $hp,
			'hp_kep' => $hp_kep,
		];

		$this->model->update('user',  $data, 'id_user', $id_user);
		$this->model->update('lembaga',  $data2, 'kode', $kd_lem);

		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Akun berhasil diupdate');
			redirect('kepala/akun');
		} else {
			$this->session->set_flashdata('error', 'Akun tidak bisa diupdate');
			redirect('kepala/akun');
		}
	}

	public function delUser($id)
	{
		$this->model->delete('user', 'id_user', $id);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Akun berhasil dihapus');
			redirect('kepala/akun');
		} else {
			$this->session->set_flashdata('error', 'Akun tidak bisa dihapus');
			redirect('kepala/akun');
		}
	}

	public function info()
	{

		$data['data'] = $this->model->getBy('info', 'tahun', $this->tahun)->result();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('kepala/head', $data);
		$this->load->view('kepala/info', $data);
		$this->load->view('kepala/foot');
	}


	public function history()
	{
		$data['data'] = $this->model->getPengajuanAll($this->tahun)->result();
		$data['bulan'] = $this->bulan;
		$data['tahun'] = $this->tahun;
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('kepala/head', $data);
		$this->load->view('kepala/history', $data);
		$this->load->view('kepala/foot');
	}

	public function historyDtl($kode)
	{
		$sql = $this->model->getBy('pengajuan', 'kode_pengajuan', $kode);
		$data['data'] = $sql->row();
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $data['data']->lembaga, 'tahun', $this->tahun)->row();
		$data['bulan'] = $this->bulan;
		$data['tahun'] = $this->tahun;

		$data['real'] = $this->model->getBySum('realis', 'kode_pengajuan', $kode, 'nominal')->row();
		$data['real_sm'] = $this->model->getBySum('real_sm', 'kode_pengajuan', $kode, 'nominal')->row();
		$data['a'] = $this->model->getByJoin3('pengajuan', 'lembaga', 'lembaga', 'kode', 'kode_pengajuan', 'kode', $kode, $data['lembaga']->kode)->row();
		$data['spj'] = $this->model->getBy('spj', 'kode_pengajuan', $kode)->row();
		$data['veral'] = $this->model->getBy('verifikasi', 'kode_pengajuan', $kode)->result();
		$data['apr'] = $this->model->getBy('approv', 'kode_pengajuan', $kode)->result();
		$data['cair'] = $this->model->getBy('pencairan', 'kode_pengajuan', $kode)->result();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('kepala/head', $data);
		$this->load->view('kepala/historyDtl', $data);
		$this->load->view('kepala/foot');
	}

	public function settingUser()
	{

		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;

		$this->load->view('kepala/head', $data);
		$this->load->view('kepala/settingAkun', $data);
		$this->load->view('kepala/foot');
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
				redirect('kepala/setting');
			} else {
				$this->session->set_flashdata('error', 'User akun tidak berhasil diperbarui');
				redirect('kepala/setting');
			}
		} else {
			if ($password != $password2) {
				$this->session->set_flashdata('error', 'Konfimasi password tidak sama');
				redirect('kepala/setting');
			} else {

				$data = [
					'nama' => $nama,
					'username' => $username,
					'password' => $pass_baru
				];
				$this->model->update('user', $data, 'id_user', $id_user);
				if ($this->db->affected_rows() > 0) {
					$this->session->set_flashdata('ok', 'User akun berhasil diperbarui');
					redirect('kepala/setting');
				} else {
					$this->session->set_flashdata('error', 'User akun tidak berhasil diperbarui');
					redirect('kepala/setting');
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
			redirect('kepala/setting');
		} else {
			$this->session->set_flashdata('error', 'User akun tidak berhasil diperbarui');
			redirect('kepala/setting');
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
				redirect('kepala/setting');
			} else {
				$this->session->set_flashdata('error', 'Upload foto sukses');
				redirect('kepala/setting');
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

		$this->load->view('kepala/head', $data);
		$this->load->view('kepala/keluar', $data);
		$this->load->view('kepala/foot');
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

		$this->load->view('kepala/head', $data);
		$this->load->view('kepala/pinjam', $data);
		$this->load->view('kepala/foot');
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

		$this->load->view('kepala/head', $data);
		$this->load->view('kepala/infoPinjam', $data);
		$this->load->view('kepala/foot');
	}
}
