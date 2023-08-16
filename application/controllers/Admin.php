<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Admin extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->db5 = $this->load->database('nikmus', true);
		$this->db2 = $this->load->database('dekos', true);

		$this->load->model('AdminModel', 'model');
		$this->load->model('Auth_model');

		$user = $this->Auth_model->current_user();
		$this->tahun = $this->session->userdata('tahun');
		// $this->jenis = ['A. Belanja Barang', 'B. Langganan & Jasa', 'Belanja Kegiatan', 'D. Umum'];
		$this->bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

		$api = $this->model->apiKey()->row();
		$this->apiKey = $api->nama_key;
		$this->user = $user->nama;
		$this->lembaga = $user->lembaga;

		if (!$this->Auth_model->current_user() || $user->level != 'admin') {
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

		$data['saldo'] = $this->model->getBy2('saldo', 'name', 'bank', 'tahun', $data['tahun']);
		$data['cash'] = $this->model->getBy2('saldo', 'name', 'cash', 'tahun', $data['tahun']);

		$this->load->view('admin/head', $data);
		$this->load->view('admin/index', $data);
		$this->load->view('admin/foot');
	}

	public function santri()
	{
		$data['santri'] = $this->model->dataSantri()->result();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;

		$this->load->view('admin/head', $data);
		$this->load->view('admin/santri', $data);
		$this->load->view('admin/foot');
	}
	public function bp()
	{
		$data['bp'] = $this->model->dataBp($this->tahun)->result();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('admin/head', $data);
		$this->load->view('admin/bp', $data);
		$this->load->view('admin/foot');
	}

	public function bpDetail($id)
	{
		$data['bp'] = $this->model->getByJoin('tangg', 'tb_santri', 'nis', 'id_tangg', $id)->row();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;

		$this->load->view('admin/head', $data);
		$this->load->view('admin/bpDetail', $data);
		$this->load->view('admin/foot');
	}
	public function bpEdit()
	{
		$where =  $this->input->post('id', true);
		$data = [
			'ju_ap' => rmRp($this->input->post('ju_ap', true)),
			'me_ju' => rmRp($this->input->post('me_ju', true)),
			'total' => rmRp($this->input->post('me_ju', true)) + rmRp($this->input->post('ju_ap', true))
		];

		$this->model->update('tangg', $data, 'id_tangg', $where);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Tanggungan berhasil diedit');
			redirect('admin/bp');
		} else {
			$this->session->set_flashdata('error', 'Tanggungan tidak bisa diedit');
			redirect('admin/bp');
		}
	}
	public function delBp()
	{
		$where = ['id_tangg' => $this->input->post('id_tangg', true)];

		$this->model->delete('tangg', $where);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Tanggungan berhasil dihapus');
			redirect('admin/bp');
		} else {
			$this->session->set_flashdata('error', 'Tanggungan tidak bisa dihapus');
			redirect('admin/bp');
		}
	}

	public function downBpTmp()
	{
		// $file = $this->model->getFile($nis)->row();
		force_download('vertical/assets/images/Template-Upload-Tanggungan.xls', NULL);
		// redirect('berkas/detail/');
	}

	public function kode()
	{
		$data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
		$data['bidang'] = $this->model->getBy('bidang', 'tahun', $this->tahun)->result();
		$data['bidangMax'] = $this->model->selectMax('bidang', 'kode')->row();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;

		$this->load->view('admin/head', $data);
		$this->load->view('admin/kode', $data);
		$this->load->view('admin/foot');
	}

	public function lembagaAdd()
	{
		$kode = $this->input->post('kode', true);
		$data = [
			'kode' => $kode,
			'nama' => $this->input->post('nama', true),
			'pelaksana' => $this->input->post('kode', true),
			'pj' => $this->input->post('pj', true),
			'hp' => $this->input->post('hp', true),
			'hp_kep' => $this->input->post('hp_kep', true),
			'waktu' => $this->input->post('waktu', true),
			'lv' => $this->input->post('lv', true),
			'tahun' => $this->tahun
		];

		$cek = $this->model->getBy('lembaga', 'kode', $kode)->num_rows();
		if ($cek > 0) {
			$this->session->set_flashdata('error', 'Maaf Kode Sudah dipakai');
			redirect('admin/kode');
		} else {
			$this->model->input('lembaga', $data);
			if ($this->db->affected_rows() > 0) {
				$this->session->set_flashdata('ok', 'Input Lembaga Berhasil');
				redirect('admin/kode');
			} else {
				$this->session->set_flashdata('error', 'Input Lembaga Gagal');
				redirect('admin/kode');
			}
		}
	}

	public function bidangAdd()
	{
		$kode = $this->input->post('kode', true);
		$data = [
			'kode' => $kode,
			'nama' => $this->input->post('nama', true),
			'lv' => $this->input->post('lv', true),
			'tahun' => $this->tahun
		];

		$cek = $this->model->getBy('bidang', 'kode', $kode)->num_rows();
		if ($cek > 0) {
			$this->session->set_flashdata('error', 'Maaf Kode Sudah dipakai');
			redirect('admin/kode');
		} else {
			$this->model->input('bidang', $data);
			if ($this->db->affected_rows() > 0) {
				$this->session->set_flashdata('ok', 'Input Bidang Berhasil');
				redirect('admin/kode');
			} else {
				$this->session->set_flashdata('error', 'Input Bidang Gagal');
				redirect('admin/kode');
			}
		}
	}
	public function mitraAdd()
	{

		$data = [
			'id_mitra' => $this->uuid->v4(),
			'nama' => $this->input->post('nama', true),
			'pj' => $this->input->post('pj', true),
			'hp' => $this->input->post('hp', true),
		];


		$this->model->input('mitra', $data);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Input Mitra Berhasil');
			redirect('admin/kode');
		} else {
			$this->session->set_flashdata('error', 'Input Mitra Gagal');
			redirect('admin/kode');
		}
	}

	public function uploadBp()
	{
		$path         = 'vertical/assets/uploads/';
		$json         = [];
		$this->upload_config($path);
		if (!$this->upload->do_upload('uploadFile')) {
			$this->session->set_flashdata('error', 'Upload Gagal. Tidak bisa load file');
			redirect('admin/bp');
		} else {
			$file_data     = $this->upload->data();
			$file_name     = $path . $file_data['file_name'];
			$arr_file     = explode('.', $file_name);
			$extension     = end($arr_file);
			if ('csv' == $extension) {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
			} else if ('xls' == $extension) {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
			} else {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			}
			$spreadsheet     = $reader->load($file_name);
			$sheet_data     = $spreadsheet->getActiveSheet()->toArray();
			$list             = [];
			foreach ($sheet_data as $key => $val) {
				if ($key != 0) {
					$result     = $this->model->get(["nis" => $val[0]]);
					if ($result) {
						// $this->session->set_flashdata('error', 'Data Sudah di Upload');
						// redirect('admin/bp');
					} else {
						$list[] = [
							'nis' => $val[0],
							'id_cos' => $val[1],
							'briva' => $val[2],
							'ju_ap' => $val[3],
							'me_ju' => $val[4],
							'total' => ($val[3] * 10) + $val[4] * 2,
							'tahun' => $val[5]
						];
					}
				}
			}
			if (file_exists($file_name))
				unlink($file_name);
			if (count($list) > 0) {
				$result     = $this->model->add_batch($list);
				if ($result) {
					$this->session->set_flashdata('ok', 'Upload Selesai');
					redirect('admin/bp');
				} else {
					$this->session->set_flashdata('error', 'Upload Gagal. Gagal list data');
					redirect('admin/bp');
				}
			} else {
				$this->session->set_flashdata('error', 'Upload Gagal. Tidak ada data yang di upload');
				redirect('admin/bp');
			}
		}
	}

	public function process_upload()
	{
		// Load library dan helper
		$this->load->helper('file');

		// Konfigurasi upload file
		$config['upload_path'] = 'vertical/assets/uploads/'; // Direktori penyimpanan file
		$config['allowed_types'] = 'xls|xlsx'; // Jenis file yang diizinkan
		$config['max_size'] = 10240; // Ukuran maksimum file (dalam kilobytes)

		// Memuat library upload
		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('uploadFile')) {
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
			$highestColumn = $worksheet->getHighestColumn();

			// echo $highestRow;

			// Mulai dari baris kedua (untuk melewati header)
			for ($row = 2; $row <= $highestRow; $row++) {
				$data = [
					'nis' => $worksheet->getCell('A' . $row)->getValue(),
					'id_cos' => $worksheet->getCell('B' . $row)->getValue(),
					'briva' => $worksheet->getCell('C' . $row)->getValue(),
					'ju_ap' => $worksheet->getCell('D' . $row)->getValue(),
					'me_ju' => $worksheet->getCell('E' . $row)->getValue(),
					'total' => ($worksheet->getCell('D' . $row)->getValue() * 10) + ($worksheet->getCell('E' . $row)->getValue() * 2),
					'tahun' => $worksheet->getCell('F' . $row)->getValue(),
				];

				$this->model->input('tangg', $data);
			}

			// Hapus file setelah selesai mengimpor

			delete_files($file_path);

			// Tampilkan pesan sukses atau lakukan redirect ke halaman lain
			if ($this->db->affected_rows() > 0) {
				$this->session->set_flashdata('ok', 'Upload Selesai');
				redirect('admin/bp');
			}
		}
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
		$data['tahun'] = $this->tahun;
		$this->load->view('admin/head', $data);
		$this->load->view('admin/masukPes', $data);
		$this->load->view('admin/foot');
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
			redirect('admin/pesantren');
		} else {
			$this->session->set_flashdata('error', 'Input Pemasukan Pesantren Gagal');
			redirect('admin/pesantren');
		}
	}

	public function editPes($id)
	{
		$data['pes'] = $this->model->getBy('pesantren', 'id_pes', $id)->row();
		$data['sumPes'] = $this->model->selectSum('pesantren', 'nominal', 'tahun', $this->tahun)->row();
		$data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
		$data['tahun'] = $this->model->getAll('tahun')->result();
		$data['bidang'] = $this->model->getBy('bidang', 'tahun', $this->tahun)->result();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('admin/head', $data);
		$this->load->view('admin/editPes', $data);
		$this->load->view('admin/foot');
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
			redirect('admin/pesantren');
		} else {
			$this->session->set_flashdata('error', 'Edit Pemasukan Pesantren Gagal');
			redirect('admin/pesantren');
		}
	}

	public function delPes($id)
	{
		$this->model->delete('pesantren', 'id_pes', $id);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Hapus Pemasukan Pesantren Berhasil');
			redirect('admin/pesantren');
		} else {
			$this->session->set_flashdata('error', 'Hapus Pemasukan Pesantren Gagal');
			redirect('admin/pesantren');
		}
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
		$this->load->view('admin/head', $data);
		$this->load->view('admin/masukBos', $data);
		$this->load->view('admin/foot');
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
			redirect('admin/bos');
		} else {
			$this->session->set_flashdata('error', 'Input Pemasukan BOS Gagal');
			redirect('admin/bos');
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
		$this->load->view('admin/head', $data);
		$this->load->view('admin/editBos', $data);
		$this->load->view('admin/foot');
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
			redirect('admin/bos');
		} else {
			$this->session->set_flashdata('error', 'Edit Pemasukan Bos Gagal');
			redirect('admin/bos');
		}
	}

	public function delBos($id)
	{
		$this->model->delete('bos', 'id_bos', $id);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Hapus Pemasukan Bos Berhasil');
			redirect('admin/bos');
		} else {
			$this->session->set_flashdata('error', 'Hapus Pemasukan Bos Gagal');
			redirect('admin/bos');
		}
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
		$data['bulan_cal'] = $this->bulan;

		for ($i = 1; $i <= 12; $i++) {
			$i !== 5 && $i !== 6 ? $field = 'ju_ap' : $field = 'me_ju';

			$tangg_perbulan = $this->model->getBySum('tangg', 'tahun', $this->tahun, $field)->row();
			$bayar_perbulan = $this->model->getBySum2('pembayaran', 'tahun', $this->tahun, 'bulan', $i, 'nominal')->row();
			$jml_tangg[] = array(
				'bulan' => $i,
				'tangg' => $tangg_perbulan->jml,
				'bayar' => $bayar_perbulan->jml,
				'bayar_prsn' => $bayar_perbulan->jml / $tangg_perbulan->jml * 100,
				'kurang' => $tangg_perbulan->jml - $bayar_perbulan->jml,
				'kurang_prsn' => ($tangg_perbulan->jml - $bayar_perbulan->jml) / $tangg_perbulan->jml * 100,
			);
		}

		$data['jml_tangg'] = $jml_tangg;

		$this->load->view('admin/head', $data);
		$this->load->view('admin/masukBp', $data);
		$this->load->view('admin/foot');
	}

	public function rab()
	{
		$data['data'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
		// $data['sumBp'] = $this->model->selectSum('pembayaran', 'nominal', 'tahun', $this->tahun)->row();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('admin/head', $data);
		$this->load->view('admin/rab', $data);
		$this->load->view('admin/foot');
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
		$this->load->view('admin/head', $data);
		$this->load->view('admin/rabDetail', $data);
		$this->load->view('admin/foot');
	}

	public function rabEdit($kode)
	{
		$data['data'] = $this->model->getBy('rab', 'id_rab', $kode)->row();
		$data['lembaga'] = $this->model->getBy('lembaga', 'kode', $data['data']->lembaga)->row();
		$data['rel'] = $this->model->getBySum('realis', 'kode', $data['data']->kode, 'nominal')->row();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('admin/head', $data);
		$this->load->view('admin/rabEdit', $data);
		$this->load->view('admin/foot');
	}

	public function downRabTmp()
	{
		force_download('vertical/assets/images/Templetes RAB_realisasi_upload.xls', NULL);
	}

	public function uploadRab()
	{
		$path         = 'vertical/assets/uploads/';
		$json         = [];
		$this->upload_config($path);
		if (!$this->upload->do_upload('uploadFile')) {
			$this->session->set_flashdata('error', 'Upload Gagal. Tidak bisa load file');
			redirect('admin/bp');
		} else {
			$file_data     = $this->upload->data();
			$file_name     = $path . $file_data['file_name'];
			$arr_file     = explode('.', $file_name);
			$extension     = end($arr_file);
			if ('csv' == $extension) {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
			} else if ('xls' == $extension) {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
			} else {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			}
			$spreadsheet     = $reader->load($file_name);
			$sheet_data     = $spreadsheet->getActiveSheet()->toArray();
			$list             = [];
			foreach ($sheet_data as $key => $val) {
				if ($key != 0) {
					$result     = $this->model->get(["nis" => $val[0]]);
					if ($result) {
						// $this->session->set_flashdata('error', 'Data Sudah di Upload');
						// redirect('admin/bp');
					} else {
						$list[] = [
							'nis' => $val[0],
							'id_cos' => $val[1],
							'briva' => $val[2],
							'ju_ap' => $val[3],
							'me_ju' => $val[4],
							'total' => ($val[3] * 10) + $val[4] * 2,
							'tahun' => $val[5]
						];
					}
				}
			}
			if (file_exists($file_name))
				unlink($file_name);
			if (count($list) > 0) {
				$result     = $this->model->add_batch($list);
				if ($result) {
					$this->session->set_flashdata('ok', 'Upload Selesai');
					redirect('admin/bp');
				} else {
					$this->session->set_flashdata('error', 'Upload Gagal. Gagal list data');
					redirect('admin/bp');
				}
			} else {
				$this->session->set_flashdata('error', 'Upload Gagal. Tidak ada data yang di upload');
				redirect('admin/bp');
			}
		}
	}

	public function rabDel($id)
	{
		$data = $this->model->getBy('rab', 'id_rab', $id)->row();
		$cek = $this->model->getBy('realis', 'kode', $data->kode)->num_rows();
		$cek2 = $this->model->getBy('real_sm', 'kode', $data->kode)->num_rows();

		if ($cek > 0 || $cek2 > 0) {
			$this->session->set_flashdata('error', 'Maaf. RAB ini sudah atau sedang diajukan');
			redirect('admin/rabDetail/' . $data->lembaga);
		} else {
			$this->model->delete('rab', $id);
			if ($this->db->affected_rows() > 0) {
				$this->session->set_flashdata('ok', 'Item RAB berhasil dihapus');
				redirect('admin/rabDetail/' . $data->lembaga);
			} else {
				$this->session->set_flashdata('error', 'Item RAB tidak bisa dihapus');
				redirect('admin/rabDetail/' . $data->lembaga);
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
			redirect('admin/rabEdit/' . $id);
		} elseif ($jml > $sisa) {
			$this->session->set_flashdata('error', 'Maaf. Jumlah baru melebihi sisa');
			redirect('admin/rabEdit/' . $id);
		} else {
			$this->model->update('rab', $data, 'id_rab', $id);
			if ($this->db->affected_rows() > 0) {
				$this->session->set_flashdata('ok', 'Update QTY berhasil');
				redirect('admin/rabEdit/' . $id);
			} else {
				$this->session->set_flashdata('error', 'Update QTY tidak bisa');
				redirect('admin/rabEdit/' . $id);
			}
		}
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

		$this->load->view('admin/head', $data);
		$this->load->view('admin/kbj', $data);
		$this->load->view('admin/foot');
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
			redirect('admin/rab_kbj');
		} else {
			$this->session->set_flashdata('error', 'Input RAB Kebijakan tidak bisa');
			redirect('admin/rab_kbj');
		}
	}

	public function delKbj($id)
	{
		// $where = ['id_kebijakan' => $id];

		$this->model->delete('kebijakan', 'id_kebijakan', $id);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'RAB Kebijakan berhasil dihapus');
			redirect('admin/rab_kbj');
		} else {
			$this->session->set_flashdata('error', 'RAB Kebijakan tidak bisa dihapus');
			redirect('admin/rab_kbj');
		}
	}

	public function pak()
	{
		$data['data'] = $this->model->getBy2('pak', 'status', 'disetujui', 'tahun', $this->tahun)->result();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('admin/head', $data);
		$this->load->view('admin/pak', $data);
		$this->load->view('admin/foot');
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
		$this->load->view('admin/head', $data);
		$this->load->view('admin/pakDetail', $data);
		$this->load->view('admin/foot');
	}

	public function rabDelSnc($kode)
	{
		$data = $this->model->getBy2('pak_detail', 'kode_pak', $kode, 'ket', 'hapus', 'snc', 'belum')->result();
		foreach ($data as $r) {
			$kdrab = $r->kode_rab;
			$up = ['snc' => 'sudah'];

			$this->model->update('pak_detail', $up, 'kode_rab', $r->kode_rab);
			$this->model->delete('rab', 'kode', $kdrab);
		}
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'RAB PAK berhasil dihapus');
			redirect('admin/pakDetail/' . $kode);
		} else {
			$this->session->set_flashdata('error', 'RAB PAK tidak bisa dihapus');
			redirect('admin/pakDetail/' . $kode);
		}
	}

	public function rabEditSnc($kode)
	{
		$data = $this->model->getBy3('pak_detail', 'kode_pak',  $kode, 'ket', 'edit', 'snc', 'belum')->result();
		foreach ($data as $r) {
			$kdrab = $r->kode_rab;
			$rab = $this->model->getBy('rab', 'kode', $kdrab)->row();

			$qtyAjuan = $rab->qty - $r->qty;
			$data1 = [
				'qty' => $qtyAjuan,
				'total' => $qtyAjuan * $rab->harga_satuan
			];
			$up = ['snc' => 'sudah'];

			$this->model->update('pak_detail', $up, 'kode_rab', $r->kode_rab);
			$this->model->update('rab', $data1, 'kode', $kdrab);
		}
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'RAB PAK berhasil disinkron');
			redirect('admin/pakDetail/' . $kode);
		} else {
			$this->session->set_flashdata('error', 'RAB PAK tidak bisa disinkron/Sudah disinkron');
			redirect('admin/pakDetail/' . $kode);
		}
	}

	public function rabUploadSnc($kode)
	{
		$sql = $this->model->getBy2('rab_sm', 'kode_pak',  $kode, 'snc', 'belum');
		$data = $sql->result();
		$cek = $sql->num_rows();

		if ($cek < 1) {
			$this->session->set_flashdata('error', 'Maaf. Tidak ada RAB yang akan diuload / RAB sudah disinkronkan');
			redirect('admin/pakDetail/' . $kode);
		} else {
			foreach ($data as $key) {
				$ins = [
					'id_rab' => $key->id_rab,
					'lembaga' => $key->lembaga,
					'bidang' => $key->bidang,
					'jenis' => $key->jenis,
					'kode' => $key->kode,
					'nama' => $key->nama,
					'rencana' => $key->rencana,
					'qty' => $key->qty,
					'satuan' => $key->satuan,
					'harga_satuan' => $key->harga_satuan,
					'total' => $key->total,
					'tahun' => $key->tahun,
					'at' => $key->at
				];

				$up = ['snc' => 'sudah'];

				$this->model->input('rab', $ins);
				$this->model->update('rab_sm', $up, 'id_rab', $key->id_rab);
			}

			if ($this->db->affected_rows() > 0) {
				$this->session->set_flashdata('ok', 'Upload RAB Lembaga Berhasil');
				redirect('admin/pakDetail/' . $kode);
			} else {
				$this->session->set_flashdata('error', 'Upload RAB Lembaga Gagal');
				redirect('admin/pakDetail/' . $kode);
			}
		}
	}

	public function pakDone($kode)
	{
		$data = $this->model->getBy('pak', 'kode_pak', $kode)->row();
		$lembaga = $this->model->getBy('lembaga', 'kode', $data->lembaga)->row();

		$data2 = ['status' => 'selesai'];

		$psn = '*INFORMASI PAK*

pengajuan dari :
    
Lembaga : ' . $lembaga->nama . '
Kode PAK : ' . $kode . '
*_PAK telah selesai disinkronisasi. Selanjutnya RAB baru sudah bisa digunakan_*

Terimakasih';

		$this->model->update('pak', $data2, 'kode_pak', $kode);

		if ($this->db->affected_rows() > 0) {
			kirim_group($this->apiKey, '120363040973404347@g.us', $psn);
			kirim_group($this->apiKey, '120363042148360147@g.us', $psn);
			kirim_person($this->apiKey, $lembaga->hp, $psn);
			// kirim_person($this->apiKey, $lembaga->hp_kep, $psn);
			// kirim_person($this->apiKey, '085236924510', $psn);

			$this->session->set_flashdata('ok', 'Pengajuan PAK berhasil disetujui');
			redirect('admin/pak');
		} else {
			$this->session->set_flashdata('error', 'Pengajuan PAK tidak bisa disetujui');
			redirect('admin/pak');
		}
	}

	public function realis()
	{
		$data['data'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
		$data['tahun_ajaran'] = $this->tahun;
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('admin/head', $data);
		$this->load->view('admin/data', $data);
		$this->load->view('admin/foot');
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
		$this->load->view('admin/head', $data);
		$this->load->view('admin/realDetail', $data);
		$this->load->view('admin/foot');
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
		$this->load->view('admin/head', $data);
		$this->load->view('admin/cekRab', $data);
		$this->load->view('admin/foot');
	}

	public function pengajuan()
	{
		$data['data'] = $this->model->getPengajuan($this->tahun)->result();
		$data['bulan'] = $this->bulan;
		$data['tahun'] = $this->tahun;
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('admin/head', $data);
		$this->load->view('admin/pengajuan', $data);
		$this->load->view('admin/foot');
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
		$this->load->view('admin/head', $data);
		$this->load->view('admin/pengajuanDetail', $data);
		$this->load->view('admin/foot');
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
			redirect('admin/pengajuanDtl/' . $pjData->kode_pengajuan);
		} else {
			$this->session->set_flashdata('error', 'Pengajuan tidak bisa diedit');
			redirect('admin/pengajuanDtl/' . $pjData->kode_pengajuan);
		}
	}

	public function delRealSm($id)
	{
		$where = $id;

		$pjData = $this->model->getBy('real_sm', 'id_realis', $where)->row();
		$this->model->delete('real_sm', 'id_realis', $where);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Pengajuan berhasil dihapus');
			redirect('admin/pengajuanDtl/' . $pjData->kode_pengajuan);
		} else {
			$this->session->set_flashdata('error', 'Pengajuan tidak bisa dihapus');
			redirect('admin/pengajuanDtl/' . $pjData->kode_pengajuan);
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
*INFORMASI PERMOHONAN PERSETUJUAN* ' . $rt . '

pengajuan dari :

Lembaga : ' . $lembaga->nama . '
Kode Pengajuan : ' . $kode . '
Nominal : ' . rupiah($total->jml) . '
*Telah di Verifikasi dan Validasi Oleh Sub Bagian Accounting pada ' . date('Y-m-d') . '*

*_dimohon kepada KEPALA PESANTREN untuk segera mengecek dan menyetujui nya di https://simkupaduka.ppdwk.com/_*
Terimakasih';

		$this->model->input('verifikasi', $data);
		$this->model->update('pengajuan', $data2, 'kode_pengajuan', $kode);

		if ($this->db->affected_rows() > 0) {
			kirim_group($this->apiKey, '120363040973404347@g.us', $psn);
			kirim_group($this->apiKey, '120363042148360147@g.us', $psn);
			// kirim_person($this->apiKey, '082264061060', $psn);
			// kirim_person($this->apiKey, '085236924510', $psn);

			$this->session->set_flashdata('ok', 'Pengajuan berhasil diverval');
			redirect('admin/pengajuanDtl/' . $pjData->kode_pengajuan);
		} else {
			$this->session->set_flashdata('error', 'Pengajuan tidak bisa diverval');
			redirect('admin/pengajuanDtl/' . $pjData->kode_pengajuan);
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
			redirect('admin/pengajuanDtl/' . $pjData->kode_pengajuan);
		} else {
			$this->session->set_flashdata('error', 'Pengajuan tidak bisa ditolak');
			redirect('admin/pengajuanDtl/' . $pjData->kode_pengajuan);
		}
	}

	public function spj()
	{
		$data['data'] = $this->model->getSPJ($this->tahun)->result();
		$data['bulan'] = $this->bulan;
		$data['tahun'] = $this->tahun;
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('admin/head', $data);
		$this->load->view('admin/spj', $data);
		$this->load->view('admin/foot');
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
			redirect('admin/spj');
		} else {
			$this->session->set_flashdata('error', 'SPJ tidak bisa ditolak');
			redirect('admin/spj');
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
			redirect('admin/spj');
		} else {
			$this->session->set_flashdata('error', 'SPJ tidak bisa disetujui');
			redirect('admin/spj');
		}
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

*_Hard copy SPJ dan sisa belanja anggaran telah disetor kepada SUB BAGIAN ING. Untuk pengajuan berikutnya sudah bisa dilakukan._*

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
			redirect('admin/spj');
		} else {
			$this->session->set_flashdata('error', 'SPJ tidak bisa disetujui');
			redirect('admin/spj');
		}
	}

	public function disposisi()
	{

		$data['data'] = $this->model->getDispo($this->tahun)->result();
		$data['pakai'] = $this->model->dispPakai($this->tahun)->row();
		$data['bulan'] = $this->bulan;
		$data['tahun'] = $this->tahun;

		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['dispLimit'] = $this->model->getBy2('pagu', 'tahun', $this->tahun, 'nama', 'DISPOSISI')->row();

		$this->load->view('admin/head', $data);
		$this->load->view('admin/disp', $data);
		$this->load->view('admin/foot');
	}
	public function akun()
	{

		$data['data'] = $this->model->getUser($this->tahun)->result();
		$data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
		$data['bulan'] = $this->bulan;
		$data['tahun'] = $this->tahun;

		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('admin/head', $data);
		$this->load->view('admin/akun', $data);
		$this->load->view('admin/foot');
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
			redirect('admin/akun');
		} else {
			$this->session->set_flashdata('error', 'Akun tidak bisa diupdate');
			redirect('admin/akun');
		}
	}

	public function delUser($id)
	{
		$this->model->delete('user', 'id_user', $id);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Akun berhasil dihapus');
			redirect('admin/akun');
		} else {
			$this->session->set_flashdata('error', 'Akun tidak bisa dihapus');
			redirect('admin/akun');
		}
	}

	public function info()
	{

		$data['data'] = $this->model->getBy('info', 'tahun', $this->tahun)->result();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('admin/head', $data);
		$this->load->view('admin/info', $data);
		$this->load->view('admin/foot');
	}

	public function infoAdd()
	{
		$data['bulan'] = $this->bulan;
		$data['tahun'] = $this->tahun;

		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('admin/head', $data);
		$this->load->view('admin/infoAdd', $data);
		$this->load->view('admin/foot');
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
			redirect('admin/info');
		} else {
			$this->session->set_flashdata('error', 'Informasi baru tidak berhasil ditambahkan');
			redirect('admin/info');
		}
	}

	public function infoEdit($id)
	{
		$data['data'] = $this->model->getBy('info', 'id_info', $id)->row();
		$data['bulan'] = $this->bulan;
		$data['tahun'] = $this->tahun;

		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('admin/head', $data);
		$this->load->view('admin/infoEdit', $data);
		$this->load->view('admin/foot');
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
			redirect('admin/info');
		} else {
			$this->session->set_flashdata('error', 'Informasi baru tidak berhasil diupdate');
			redirect('admin/info');
		}
	}


	public function delInfo($id)
	{
		$this->model->delete('info', 'id_info', $id);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Informasi berhasil dihapus');
			redirect('admin/info');
		} else {
			$this->session->set_flashdata('error', 'Informasi tidak bisa dihapus');
			redirect('admin/info');
		}
	}

	public function history()
	{
		$data['data'] = $this->model->getPengajuanAll($this->tahun)->result();
		$data['bulan'] = $this->bulan;
		$data['tahun'] = $this->tahun;
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('admin/head', $data);
		$this->load->view('admin/history', $data);
		$this->load->view('admin/foot');
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
		$this->load->view('admin/head', $data);
		$this->load->view('admin/historyDtl', $data);
		$this->load->view('admin/foot');
	}

	public function setting()
	{
		// $dt_bos = mysqli_query($conn, "SELECT a.*, b.nama FROM akses a JOIN lembaga b ON a.lembaga=b.kode WHERE a.tahun = '$tahun_ajaran' AND b.tahun = '$tahun_ajaran' ");

		$data['data'] = $this->model->getByJoin3('akses', 'lembaga', 'lembaga', 'kode', 'akses.tahun', 'lembaga.tahun', $this->tahun, $this->tahun)->result();
		$data['bulan'] = $this->bulan;
		$data['tahun'] = $this->tahun;
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('admin/head', $data);
		$this->load->view('admin/setting', $data);
		$this->load->view('admin/foot');
	}

	public function delAkses($id)
	{
		$this->model->delete('akses', 'id_akses', $id);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Hak Akses Lembaga berhasil dihapus');
			redirect('admin/setting');
		} else {
			$this->session->set_flashdata('error', 'Hak Akses Lembaga tidak bisa dihapus');
			redirect('admin/setting');
		}
	}

	public function saveEditAkses()
	{
		$id =  $this->input->post('id_akses', true);
		$data = [
			'login' =>  $this->input->post('login'),
			'disposisi' =>  $this->input->post('disp')
		];

		$this->model->update('akses',  $data, 'id_akses', $id);

		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Hak Akses Lembaga berhasil diupdate');
			redirect('admin/setting');
		} else {
			$this->session->set_flashdata('error', 'Hak Akses Lembaga tidak bisa diupdate');
			redirect('admin/setting');
		}
	}

	public function saveAkses()
	{
		$data = [
			'login' =>  $this->input->post('login'),
			'disposisi' =>  $this->input->post('disp'),
			'lembaga' =>  $this->input->post('lembaga'),
			'tahun' =>  $this->input->post('tahun')
		];

		$this->model->input('akses',  $data);

		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Hak Akses Lembaga berhasil ditambahkan');
			redirect('admin/setting');
		} else {
			$this->session->set_flashdata('error', 'Hak Akses Lembaga tidak bisa ditambahkan');
			redirect('admin/setting');
		}
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
			redirect('admin/setting');
		} else {
			$this->session->set_flashdata('error', 'Akses PAK tidak bisa diupdate');
			redirect('admin/setting');
		}
	}

	public function settingUser()
	{

		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;

		$this->load->view('admin/head', $data);
		$this->load->view('admin/settingAkun', $data);
		$this->load->view('admin/foot');
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
				redirect('admin/setting');
			} else {
				$this->session->set_flashdata('error', 'User akun tidak berhasil diperbarui');
				redirect('admin/setting');
			}
		} else {
			if ($password != $password2) {
				$this->session->set_flashdata('error', 'Konfimasi password tidak sama');
				redirect('admin/setting');
			} else {

				$data = [
					'nama' => $nama,
					'username' => $username,
					'password' => $pass_baru
				];
				$this->model->update('user', $data, 'id_user', $id_user);
				if ($this->db->affected_rows() > 0) {
					$this->session->set_flashdata('ok', 'User akun berhasil diperbarui');
					redirect('admin/setting');
				} else {
					$this->session->set_flashdata('error', 'User akun tidak berhasil diperbarui');
					redirect('admin/setting');
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
			redirect('admin/setting');
		} else {
			$this->session->set_flashdata('error', 'User akun tidak berhasil diperbarui');
			redirect('admin/setting');
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
				redirect('admin/setting');
			} else {
				$this->session->set_flashdata('error', 'Upload foto sukses');
				redirect('admin/setting');
			}
		}
	}

	public function cairItem()
	{
		$id = $this->uuid->v4();
		$kode = $this->input->post('kode');
		$vol = $this->input->post('vol');

		$l = $this->model->getBy2('rab', 'kode', $kode, 'tahun', $this->tahun)->row();
		$kd_rab = $l->kode;
		$lembaga = $l->lembaga;
		$bidang = $l->bidang;
		$jenis = $l->jenis;
		$tgl = date('Y-m-d');
		$qty = $vol;
		$pj = 'Manual';
		$bulan = date('m');
		$tahun = $this->tahun;
		$nominal = $l->harga_satuan * $qty;
		$nm_rab =  $l->nama;
		$ket = $nm_rab . ' - @ ' . $qty . ' x ' . number_format($l->harga_satuan, 0, ',', '.');
		$kd_pjn = 'Manual.' . $tgl;
		$sisa_jml = $this->input->post('sisa_jml', true);

		if ($jenis === 'A') {
			$stas = 'barang';
		} else {
			$stas = 'tunai';
		}

		if ($qty > $sisa_jml) {
			$this->session->set_flashdata('error', 'Maaf. Jumlah Pencairan anda melebihi dari yang tersisa');
			redirect('admin/rabEdit/' . $l->id_rab);
		} elseif ($qty < 1) {
			$this->session->set_flashdata('error', 'Jumlah item 0. Jumlah item harus diisi');
			redirect('admin/rabEdit/' . $l->id_rab);
		} else {

			$data = [
				'id_realis' => $id,
				'lembaga' => $lembaga,
				'bidang' => $bidang,
				'jenis' => $jenis,
				'kode' => $kd_rab,
				'vol' => $qty,
				'nominal' => $nominal,
				'tgl' => $tgl,
				'pj' => $pj,
				'bulan' => $bulan,
				'tahun' => $tahun,
				'ket' => $ket,
				'kode_pengajuan' => $kd_pjn,
				'nom_cair' => $nominal,
				'nom_serap' => $nominal,
				'stas' => $stas
			];

			$this->model->input('realis', $data);
			if ($this->db->affected_rows() > 0) {
				$this->session->set_flashdata('ok', 'Item pengajuan berhasil ditambahkan');
				redirect('admin/rabEdit/' . $l->id_rab);
			} else {
				$this->session->set_flashdata('error', 'Item pengajuan tidak ditambahkan');
				redirect('admin/rabEdit/' . $l->id_rab);
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

		$this->load->view('admin/head', $data);
		$this->load->view('admin/keluar', $data);
		$this->load->view('admin/foot');
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
			redirect('admin/lain');
		} else {
			$this->session->set_flashdata('error', 'Input data gagal');
			redirect('admin/lain');
		}
	}

	public function delLain($id)
	{
		$this->model->delete('keluar', 'id_keluar', $id);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Hapus data sukses');
			redirect('admin/lain');
		} else {
			$this->session->set_flashdata('error', 'Hapus data gagal');
			redirect('admin/lain');
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

		$this->load->view('admin/head', $data);
		$this->load->view('admin/pinjam', $data);
		$this->load->view('admin/foot');
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
			redirect('admin/pinjam');
		} else {
			$this->session->set_flashdata('error', 'Input data gagal');
			redirect('admin/pinjam');
		}
	}

	public function delPinjam($id)
	{
		$data = $this->model->getBy('peminjaman', 'id_pinjam', $id)->row();

		$this->model->delete('peminjaman', 'id_pinjam', $id);
		$this->model->delete('cicilan', 'kode_pinjam', $data->kode_pinjam);

		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Hapus data sukses');
			redirect('admin/pinjam');
		} else {
			$this->session->set_flashdata('error', 'Hapus data gagal');
			redirect('admin/pinjam');
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

		$this->load->view('admin/head', $data);
		$this->load->view('admin/infoPinjam', $data);
		$this->load->view('admin/foot');
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
			redirect('admin/infoPinjam/' . $dataPinjam->id_pinjam);
		} else {
			$this->session->set_flashdata('error', 'Input data gagal');
			redirect('admin/infoPinjam/' . $dataPinjam->id_pinjam);
		}
	}

	public function delCicil($id)
	{
		$data = $this->model->getBy('cicilan', 'id_cicilan', $id)->row();
		$dataPinjam = $this->model->getBy('peminjaman', 'kode_pinjam', $data->kode_pinjam)->row();

		$this->model->delete('cicilan', 'id_cicilan', $id);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Hapus data sukses');
			redirect('admin/infoPinjam/' . $dataPinjam->id_pinjam);
		} else {
			$this->session->set_flashdata('error', 'Hapus data gagal');
			redirect('admin/infoPinjam/' . $dataPinjam->id_pinjam);
		}
	}

	public function editSaldo()
	{
		$saldo = [
			'nominal' => rmRp($this->input->post('nominal', true)),
			'last' => date('Y-m-d H:i:s')
		];

		$psn = '*Update Saldo Bank Rek. Pesantren*

Nominal : RP. ' . $this->input->post('nominal', true) . '
Tgl Update : ' . date('Y-m-d H:i:s') . '
Updater : ' . $this->user . '

*Terimkasih*';

		$this->model->update2('saldo', $saldo, 'name', 'bank', 'tahun', $this->tahun);
		if ($this->db->affected_rows() > 0) {
			kirim_person($this->apiKey, '082264061060', $psn);
			kirim_person($this->apiKey, '085258222376', $psn);
			kirim_person($this->apiKey, '085236924510', $psn);
			$this->session->set_flashdata('ok', 'Saldo sudah diperbarui');
			redirect('admin');
		} else {
			$this->session->set_flashdata('error', 'Hapus data gagal');
			redirect('admin');
		}
	}

	public function editSaldoCash()
	{
		$saldo = [
			'nominal' => rmRp($this->input->post('nominal', true)),
			'last' => date('Y-m-d H:i:s')
		];

		$psn = '*Update Saldo Cash Pesantren*

Nominal : RP. ' . $this->input->post('nominal', true) . '
Tgl Update : ' . date('Y-m-d H:i:s') . '
Updater : ' . $this->user . '

*Terimkasih*';

		$this->model->update('saldo', $saldo, 'name', 'cash', 'tahun', $this->tahun);
		if ($this->db->affected_rows() > 0) {
			kirim_person($this->apiKey, '082264061060', $psn);
			kirim_person($this->apiKey, '085258222376', $psn);
			kirim_person($this->apiKey, '085236924510', $psn);
			$this->session->set_flashdata('ok', 'Saldo sudah diperbarui');
			redirect('admin');
		} else {
			$this->session->set_flashdata('error', 'Hapus data gagal');
			redirect('admin');
		}
	}

	public function setor()
	{
		$data['bulan'] = $this->bulan;
		$data['list'] = $this->model->getSetor($this->tahun)->result();
		$this->load->view('admin/setor', $data);
	}

	public function sisa()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['bulan'] = $this->bulan;
		$data['sumSisa'] = $this->model->selectSum('real_sisa', 'sisa', 'tahun', $this->tahun)->row();
		$data['sisa'] = $this->model->getSisaOrder($this->tahun)->result();

		$this->load->view('admin/head', $data);
		$this->load->view('admin/masukSisa', $data);
		$this->load->view('admin/foot');
	}

	public function delSisa($id)
	{
		$this->model->delete('real_sisa', 'id_sisa', $id);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Saldo sudah dihapus');
			redirect('admin/sisa');
		} else {
			$this->session->set_flashdata('error', 'Hapus data gagal');
			redirect('admin/sisa');
		}
	}

	public function mutasi()
	{
	}

	public function honor()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['bulan'] = $this->bulan;
		$data['data'] = $this->model->getBy('kas', 'tahun', $this->tahun)->result();

		$this->load->view('admin/head', $data);
		$this->load->view('admin/kembaliHonor', $data);
		$this->load->view('admin/foot');
	}

	public function saveHonorBack()
	{
		$data = [
			'id_kas' => $this->uuid->v4(),
			'uraian' => $this->input->post('uraian'),
			'tgl' => $this->input->post('tgl'),
			'nominal' => rmRp($this->input->post('nominal')),
			'penyetor' => $this->input->post('penyetor'),
			'ket' => $this->input->post('ket'),
			'tahun' => $this->tahun,
			'at' => date('Y-m-d H:i:s'),
		];

		$this->model->input('kas', $data);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Data berhasil ditambahkan');
			redirect('admin/honor');
		} else {
			$this->session->set_flashdata('error', 'Data gagal ditambahkan');
			redirect('admin/honor');
		}
	}

	public function delHonor($id)
	{
		$this->model->delete('kas', 'id_kas', $id);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Kas sudah dihapus');
			redirect('admin/honor');
		} else {
			$this->session->set_flashdata('error', 'Hapus data gagal');
			redirect('admin/honor');
		}
	}

	public function dppk()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['bulan'] = $this->bulan;
		$data['data'] = $this->model->getBy('dppk', 'tahun', $this->tahun)->result();

		$this->load->view('admin/head', $data);
		$this->load->view('admin/dppk', $data);
		$this->load->view('admin/foot');
	}

	public function rab24()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['bulan'] = $this->bulan;

		$data['data'] = $this->db->query("SELECT * FROM rab_list JOIN lembaga ON lembaga.kode=rab_list.lembaga WHERE rab_list.tahun = '$this->tahun' AND lembaga.tahun = '$this->tahun'  AND rab_list.status = 'disetujui' ")->result();

		$this->load->view('admin/head', $data);
		$this->load->view('admin/rab24', $data);
		$this->load->view('admin/foot');
	}

	public function rab24detail($lembaga)
	{
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $lembaga, 'tahun', $this->tahun)->row();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;

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

		$this->load->view('admin/head', $data);
		$this->load->view('admin/rab24detail', $data);
		$this->load->view('admin/foot');
	}

	public function rabUploadSnc24($kode)
	{
		$sql = $this->model->getBy3('rab_sm24', 'lembaga',  $kode, 'snc', 'belum', 'tahun', $this->tahun);
		$data = $sql->result();
		$cek = $sql->num_rows();

		if ($cek < 1) {
			$this->session->set_flashdata('error', 'Maaf. Tidak ada RAB yang akan diuload / RAB sudah disinkronkan');
			redirect('admin/rab24detail/' . $kode);
		} else {
			foreach ($data as $key) {
				$ins = [
					'id_rab' => $key->id_rab,
					'lembaga' => $key->lembaga,
					'bidang' => $key->bidang,
					'jenis' => $key->jenis,
					'kode' => $key->kode,
					'nama' => $key->nama,
					'rencana' => $key->rencana,
					'qty' => $key->qty,
					'satuan' => $key->satuan,
					'harga_satuan' => $key->harga_satuan,
					'total' => $key->total,
					'tahun' => $key->tahun,
					'at' => $key->at
				];

				$up = ['snc' => 'sudah'];

				$this->model->input('rab', $ins);
				$this->model->update('rab_sm24', $up, 'id_rab', $key->id_rab);
			}

			if ($this->db->affected_rows() > 0) {
				$this->session->set_flashdata('ok', 'Upload RAB Lembaga Berhasil');
				redirect('admin/rab24detail/' . $kode);
			} else {
				$this->session->set_flashdata('error', 'Upload RAB Lembaga Gagal');
				redirect('admin/rab24detail/' . $kode);
			}
		}
	}

	public function rabDone24($kode)
	{
		$data = $this->model->getBy2('rab_list', 'lembaga', $kode, 'tahun', $this->tahun)->row();
		$lembaga = $this->model->getBy('lembaga', 'kode', $data->lembaga)->row();

		$data2 = ['status' => 'selesai'];

		$psn = '*INFORMASI RAB 23/24*

pengajuan dari :
    
Lembaga : ' . $lembaga->nama . '
Tahun : ' . $this->tahun . '
Pada : ' .  date('Y-m-d H:i') . '

*_RAB telah selesai disinkronisasi. Selanjutnya RAB baru sudah bisa digunakan_*
Terimakasih';

		$this->model->update('rab_list', $data2, 'lembaga', $kode);

		if ($this->db->affected_rows() > 0) {
			kirim_group($this->apiKey, '120363040973404347@g.us', $psn);
			kirim_group($this->apiKey, '120363042148360147@g.us', $psn);
			// kirim_person($this->apiKey, $lembaga->hp, $psn);
			// kirim_person($this->apiKey, $lembaga->hp_kep, $psn);
			// kirim_person($this->apiKey, '085236924510', $psn);

			$this->session->set_flashdata('ok', 'Pengajuan RAB berhasil disetujui');
			redirect('admin/rab24');
		} else {
			$this->session->set_flashdata('error', 'Pengajuan RAB tidak bisa disetujui');
			redirect('admin/rab24');
		}
	}

	public function analistOut()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['bulan'] = $this->bulan;

		$this->load->view('admin/head', $data);
		$this->load->view('admin/analistOut', $data);
		$this->load->view('admin/foot');
	}

	public function pagu()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['bulan'] = $this->bulan;

		$data['data'] = $this->model->getBy('pagu', 'tahun', $data['tahun'])->result();
		$data['ta'] = $this->model->getAll('tahun')->result();

		$this->load->view('admin/head', $data);
		$this->load->view('admin/pagu', $data);
		$this->load->view('admin/foot');
	}

	public function addPagu()
	{
		$data = [
			'kode_pagu' => 'PAGU-' . rand(0, 9999),
			'nama' => $this->input->post('nama', true),
			'nominal' => rmRp($this->input->post('nominal', true)),
			'tahun' => $this->input->post('tahun', true),
			'at' => date('Y-m-d H:i:s'),
		];

		$this->model->input('pagu', $data);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Add Pagu Berhasil');
			redirect('admin/pagu');
		} else {
			$this->session->set_flashdata('error', 'Add Pagu Gagal');
			redirect('admin/pagu');
		}
	}

	public function delPagu($id)
	{

		$this->model->delete('pagu', 'id_pagu', $id);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Pagu berhasil dihapus');
			redirect('admin/pagu');
		} else {
			$this->session->set_flashdata('error', 'Pagu tidak bisa dihapus');
			redirect('admin/pagu');
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

	public function kasHarian()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['bulan'] = $this->bulan;

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

		$this->load->view('admin/head', $data);
		$this->load->view('admin/kasHarian', $data);
		$this->load->view('admin/foot');
	}

	public function kasBank()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['bulan'] = $this->bulan;

		$data['kas'] = $this->db->query("SELECT tgl_setor AS tanggal, 'BOS/BPOPP' AS jenis , SUM(nominal) as debit, 0 AS kredit FROM bos WHERE tahun = '$this->tahun' GROUP BY tgl_setor 
UNION
SELECT tgl AS tanggal, 'BP' AS jenis, SUM(nominal) AS debit, 0 AS kredit FROM pembayaran WHERE tahun = '$this->tahun' GROUP BY tgl 
ORDER BY tanggal DESC")->result();

		$this->load->view('admin/head', $data);
		$this->load->view('admin/kasBank', $data);
		$this->load->view('admin/foot');
	}

	public function kasPajak()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['bulan'] = $this->bulan;

		$data['kas'] = $this->db->query("SELECT tanggal AS tanggal, 'PAJAK' AS jenis , 0 as debit, SUM(nominal) AS kredit FROM pajak WHERE tahun = '$this->tahun' GROUP BY tanggal ORDER BY tanggal DESC")->result();

		$this->load->view('admin/head', $data);
		$this->load->view('admin/kasPajak', $data);
		$this->load->view('admin/foot');
	}

	public function kasPanjar()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['bulan'] = $this->bulan;

		$data['kas'] = $this->db->query("SELECT sarpras.tanggal AS tanggal, 'SARPRAS' AS jenis , 0 as debit, SUM(sarpras_detail.qty * sarpras_detail.harga_satuan) AS kredit FROM sarpras JOIN sarpras_detail ON sarpras.kode_pengajuan = sarpras_detail.kode_pengajuan WHERE sarpras_detail.tahun = '$this->tahun' AND sarpras.tahun = '$this->tahun' GROUP BY sarpras.tanggal ORDER BY sarpras.tanggal DESC")->result();

		$this->load->view('admin/head', $data);
		$this->load->view('admin/kasPanjar', $data);
		$this->load->view('admin/foot');
	}

	public function kasHutang()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['bulan'] = $this->bulan;

		$data['kas'] = $this->db->query("SELECT tanggal AS tanggal, 'LISTRIK/WIFI/HONOR' AS jenis , 0 as debit, SUM(nominal)  AS kredit FROM pengeluaran_rutin WHERE tahun = '$this->tahun' GROUP BY tanggal 
UNION
SELECT tgl_pinjam AS tanggal, 'PEMINJAMAN/BON' AS jenis, 0 AS debit, SUM(nominal) AS kredit FROM peminjaman WHERE tahun = '$this->tahun' GROUP BY tgl_pinjam 

UNION
SELECT tgl_setor AS tanggal, 'CICILAN PEMINJAMAN' AS jenis, SUM(nominal) AS debit, 0 AS kredit FROM cicilan WHERE tahun = '$this->tahun' GROUP BY tgl_setor 

ORDER BY tanggal DESC")->result();

		$this->load->view('admin/head', $data);
		$this->load->view('admin/kasHutang', $data);
		$this->load->view('admin/foot');
	}

	public function kasDekosan()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['bulan'] = $this->bulan;

		$data['kas'] = $this->db2->query("SELECT tgl AS tanggal, 'DEKOSAN' AS jenis , 0 as debit, SUM(nominal) AS kredit FROM setor WHERE tahun = '$this->tahun' GROUP BY tgl ORDER BY tgl DESC")->result();

		$this->load->view('admin/head', $data);
		$this->load->view('admin/kasDekosan', $data);
		$this->load->view('admin/foot');
	}

	public function kasAll()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['bulan'] = $this->bulan;

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

		$this->load->view('admin/head', $data);
		$this->load->view('admin/kasBesar', $data);
		$this->load->view('admin/foot');
	}
}
