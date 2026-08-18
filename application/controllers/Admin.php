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
		$this->load->model('AppModel', 'modelAll');

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

		$data['dekos'] = $this->model->getDekosSum($this->tahun)->row();
		$data['nikmus'] = $this->model->getNikmusSum($this->tahun)->row();

		$data['pesantren'] = $this->model->getBySum('pesantren', 'tahun', $this->tahun, 'nominal')->row();
		$data['realSisa'] = $this->model->getBySum('real_sisa', 'tahun', $this->tahun, 'sisa')->row();

		$data['masuk'] = $this->modelAll->masuk($this->tahun);
		$data['keluar'] = $this->modelAll->keluar($this->tahun);
		$data['cadangan'] = $this->modelAll->cadangan($this->tahun);

		$data['lembaga'] = $this->model->getBy('lembaga', 'tahun', $this->tahun)->result();
		$data['saldo'] = $this->model->getBy2('saldo', 'name', 'bank', 'tahun', $data['tahun']);
		$data['cash'] = $this->model->getBy2('saldo', 'name', 'cash', 'tahun', $data['tahun']);

		$this->load->view('admin/head', $data);
		$this->load->view('admin/index', $data);
		$this->load->view('admin/foot');
	}

	public function santri()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;

		// Fetch list of active institutions for filtering
		$data['lembaga_list'] = $this->db->select('t_formal')
			->from('tb_santri')
			->where('t_formal !=', '')
			->group_by('t_formal')
			->order_by('t_formal', 'asc')
			->get()
			->result();

		$this->load->view('admin/head', $data);
		$this->load->view('admin/santri', $data);
		$this->load->view('admin/foot');
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
				<a href="' . base_url('admin/delete_santri/' . $row->id_santri) . '" 
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

	public function dekos()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['bulan_cal'] = $this->bulan;

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

		$this->load->view('admin/head', $data);
		$this->load->view('admin/dekos', $data);
		$this->load->view('admin/foot');
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

	public function sinkron_batch()
	{
		// Fetch bearer token from gaji_flat settings table

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
				'cost_name' => $nama,
				'stas' => '',
				'group_id' => '',
				'group_name' => '',
				'bill_id' => '',
				'bill_name' => '',
				'dekos' => 0,
				'tgn' => 0,
				'nominal' => 0
			]);
		}

		if ($this->db->affected_rows() > 0 || $existing) {
			$this->session->set_flashdata('ok', 'Customer ID berhasil diperbarui');
		} else {
			$this->session->set_flashdata('error', 'Gagal memperbarui Customer ID');
		}

		redirect('admin/santri');
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
		redirect('admin/santri');
	}

	public function sinkron_lembaga_batch()
	{
		// Fetch bearer token from settings
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
			} elseif ($httpCode === 404) {
				$this->db->where('id_santri', $id_santri);
				$this->db->update('tb_santri', ['aktif' => 'N']);
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

	public function bp()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$this->load->view('admin/head', $data);
		$this->load->view('admin/bp', $data);
		$this->load->view('admin/foot');
	}

	public function bp_ajax()
	{
		$draw = intval($this->input->post('draw'));
		$start = intval($this->input->post('start'));
		$length = intval($this->input->post('length'));
		$search_value = $this->input->post('search')['value'] ?? '';
		$order = $this->input->post('order');

		// Column mapping for ordering
		$columns = [
			0 => 'tanggungan.id_tanggungan', // not sorted
			1 => 'tb_santri.nama',
			2 => 'tanggungan.briva',
			3 => 'total_nominal',
			4 => 'tanggungan.tahun',
			5 => 'tanggungan.id_tanggungan' // Act
		];

		// Base query configuration
		$this->db->select('tb_santri.nama, tanggungan.nis, tanggungan.briva, SUM(tanggungan.nominal) AS total_nominal, tanggungan.tahun');
		$this->db->from('tanggungan');
		$this->db->join('tb_santri', 'tanggungan.nis = tb_santri.nis');
		$this->db->where('tanggungan.tahun', $this->tahun);
		$this->db->group_by(['tanggungan.nis', 'tb_santri.nama', 'tanggungan.briva', 'tanggungan.tahun']);

		// Handle search filter
		if (!empty($search_value)) {
			$this->db->group_start();
			$this->db->like('tb_santri.nama', $search_value);
			$this->db->or_like('tanggungan.briva', $search_value);
			$this->db->or_like('tanggungan.nis', $search_value);
			$this->db->group_end();
		}

		// Handle ordering
		if (isset($order[0]['column']) && isset($columns[$order[0]['column']])) {
			$col_idx = intval($order[0]['column']);
			$dir = ($order[0]['dir'] === 'desc') ? 'desc' : 'asc';
			if ($col_idx > 0 && $col_idx < 5) {
				if ($col_idx == 3) {
					$this->db->order_by('total_nominal', $dir);
				} else {
					$this->db->order_by($columns[$col_idx], $dir);
				}
			} else {
				$this->db->order_by('tb_santri.nama', 'asc');
			}
		} else {
			$this->db->order_by('tb_santri.nama', 'asc');
		}

		// Save query state for data count
		$temp_db = clone $this->db;
		$filtered_query = $temp_db->get();
		$recordsFiltered = $filtered_query ? $filtered_query->num_rows() : 0;

		// Apply pagination
		$this->db->limit($length, $start);
		$query = $this->db->get();
		$data = $query->result();

		// Total records count (unfiltered)
		$recordsTotalRow = $this->db->query("SELECT COUNT(DISTINCT nis) AS cnt FROM tanggungan WHERE tahun = ?", [$this->tahun])->row();
		$recordsTotal = $recordsTotalRow ? intval($recordsTotalRow->cnt) : 0;

		// Format output for DataTables
		$output = [];
		$no = $start + 1;
		foreach ($data as $row) {
			$edit_url = base_url('admin/bpDetail/') . $row->nis;
			$del_url = base_url('admin/delBp/') . $row->nis;

			$aksi = "<a href='{$edit_url}'><i class='bx bx-message-square-edit mr-1'></i></a> | " .
				"<a href='{$del_url}' class='tombol-hapus'><i class='bx bx-trash mr-1'></i></a>";

			$output[] = [
				'no' => $no++,
				'nama' => $row->nama,
				'briva' => $row->briva ?? '-',
				'nominal' => 'Rp. ' . number_format($row->total_nominal, 0, '.', '.'),
				'tahun' => $row->tahun,
				'aksi' => $aksi
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

	public function bpDetail($nis)
	{
		$student = $this->db->get_where('tb_santri', ['nis' => $nis])->row();
		if (!$student) {
			$this->session->set_flashdata('error', 'Siswa tidak ditemukan');
			redirect('admin/bp');
		}

		$tanggungan_list = $this->db->get_where('tanggungan', [
			'nis' => $nis,
			'tahun' => $this->tahun
		])->result();

		$tanggungan_map = [];
		$briva = '';
		foreach ($tanggungan_list as $t) {
			$tanggungan_map[$t->bulan] = $t->nominal;
			if (!empty($t->briva)) {
				$briva = $t->briva;
			}
		}

		$bp_mock = (object)[
			'nis' => $student->nis,
			'nama' => $student->nama,
			'briva' => $briva,
			'id_tangg' => $student->nis
		];

		$data['bp'] = $bp_mock;
		$data['months_map'] = $tanggungan_map;
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;

		$this->load->view('admin/head', $data);
		$this->load->view('admin/bpDetail', $data);
		$this->load->view('admin/foot');
	}

	public function bpEdit()
	{
		$nis = $this->input->post('id', true);
		$briva = $this->input->post('briva', true);
		if (empty($briva)) {
			$briva = null;
		}

		$months = [7, 8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6];

		foreach ($months as $m) {
			$raw_nominal = $this->input->post('nominal_' . $m, true) ?? '0';
			$nominal = rmRp($raw_nominal);

			$existing = $this->db->get_where('tanggungan', [
				'nis' => $nis,
				'bulan' => $m,
				'tahun' => $this->tahun
			])->row();

			if ($existing) {
				if ($nominal > 0) {
					$this->db->where('id_tanggungan', $existing->id_tanggungan)->update('tanggungan', [
						'nominal' => $nominal,
						'briva' => $briva
					]);
				} else {
					$this->db->where('id_tanggungan', $existing->id_tanggungan)->delete('tanggungan');
				}
			} else {
				if ($nominal > 0) {
					// UUID generator
					$uuid = sprintf(
						'%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
						mt_rand(0, 0xffff),
						mt_rand(0, 0xffff),
						mt_rand(0, 0xffff),
						mt_rand(0, 0x0fff) | 0x4000,
						mt_rand(0, 0x3fff) | 0x8000,
						mt_rand(0, 0xffff),
						mt_rand(0, 0xffff),
						mt_rand(0, 0xffff)
					);
					$this->db->insert('tanggungan', [
						'id_tanggungan' => $uuid,
						'nis' => $nis,
						'briva' => $briva,
						'nominal' => $nominal,
						'bulan' => $m,
						'tahun' => $this->tahun,
						'tgl_upload' => date('Y-m-d'),
						'kasir' => $this->user
					]);
				}
			}
		}

		// Update briva globally for remaining student records for this year
		$this->db->where('nis', $nis);
		$this->db->where('tahun', $this->tahun);
		$this->db->update('tanggungan', ['briva' => $briva]);

		$this->session->set_flashdata('ok', 'Tanggungan berhasil diperbarui');
		redirect('admin/bp');
	}

	public function delBp($nis)
	{
		$this->db->where('nis', $nis);
		$this->db->where('tahun', $this->tahun);
		$this->db->delete('tanggungan');

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
	public function downloadSpj($idSpj)
	{
		$data = $this->model->getBy('spj', 'id_spj', $idSpj)->row();
		force_download('vertical/assets/uploads/' . $data->file_spj, NULL);
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

		$cek = $this->model->getBy2('lembaga', 'kode', $kode, 'tahun', $this->tahun)->num_rows();
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

	public function lembagaEdit()
	{
		$id_lembaga = $this->input->post('id_lembaga', true);
		$data = [
			'kode' => $this->input->post('kode', true),
			'nama' => $this->input->post('nama', true),
			'pj' => $this->input->post('pj', true),
			'hp' => $this->input->post('hp', true),
			'hp_kep' => $this->input->post('hp_kep', true),
			'waktu' => $this->input->post('waktu', true),
			'lv' => $this->input->post('lv', true)
		];

		$this->model->update('lembaga', $data, 'id_lembaga', $id_lembaga);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Data Lembaga berhasil diperbarui');
		} else {
			$this->session->set_flashdata('error', 'Tidak ada perubahan data');
		}
		redirect('admin/kode');
	}

	public function lembagaDelete($id_lembaga)
	{
		$this->db->where('id_lembaga', $id_lembaga);
		$this->db->delete('lembaga');

		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Data Lembaga berhasil dihapus');
		} else {
			$this->session->set_flashdata('error', 'Data Lembaga gagal dihapus');
		}
		redirect('admin/kode');
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
							// 'total' => ($val[3] * 10) + $val[4] * 2,
							'total' => ($val[3] * 11) + $val[4],
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

	public function process_uploadDppk()
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
			// $highestColumn = $worksheet->getHighestColumn();

			// echo $highestRow;

			// Mulai dari baris kedua (untuk melewati header)
			for ($row = 2; $row <= $highestRow; $row++) {
				$lembaga = preg_replace('/[^\x20-\x7E]/', '', $worksheet->getCell('B' . $row)->getValue());
				$kegiatan = preg_replace('/[^\x20-\x7E]/', '', $worksheet->getCell('C' . $row)->getValue());
				$tahun = preg_replace('/[^\x20-\x7E]/', '', $worksheet->getCell('D' . $row)->getValue());

				$cek = $this->db->query("SELECT id_dppk FROM dppk WHERE lembaga = '$lembaga' AND kegiatan = '$kegiatan' AND tahun = '$tahun' ")->num_rows();
				if ($cek < 1) {
					// echo $lembaga . '-' . random_int(1000, 9999) . ' ' . $kegiatan . '<br>';
					$data = [
						'id_dppk' => $lembaga . '-' . random_int(1000, 9999),
						'lembaga' => $lembaga,
						'program' => $kegiatan,
						'kegiatan' => $kegiatan,
						'indikator' => $kegiatan,
						'tahun' => $tahun,
					];

					$this->model->input('dppk', $data);
				}
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
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $kode, 'tahun', $this->tahun)->row();
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
			$this->model->delete('rab', 'id_rab', $id);
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
		$data['rabnew'] = $this->model->getBy3('rab_sm', 'lembaga', $data['data']->lembaga, 'tahun', $this->tahun, 'kode_pak', $kode)->result();
		$data['rpakSum'] = $this->model->selectSum('pak_detail', 'total', 'kode_pak', $kode)->row();
		$data['rabnewSum'] = $this->model->selectSum('rab_sm', 'total', 'kode_pak', $kode)->row();
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
			$this->model->delete('rab_sm24', 'kode', $kdrab);
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
			$this->model->update('rab_sm24', $data1, 'kode', $kdrab);
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
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $lembaga, 'tahun', $this->tahun)->row();

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
		$data['rab'] = $this->model->getBy2('rab', 'kode', $kode, 'tahun', $this->tahun)->row();
		$data['lem'] = $this->model->getBy2('lembaga', 'kode', $data['rab']->lembaga, 'tahun', $this->tahun)->row();
		$data['tahun_ajaran'] = $this->tahun;
		$data['rel'] = $this->model->getBySum2('realis', 'kode', $kode, 'tahun', $this->tahun, 'nominal')->row();
		$data['relData'] = $this->model->getBy2('realis', 'kode', $kode, 'tahun', $this->tahun)->result();
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

	public function addUser()
	{
		$nama = strtoupper($this->input->post('nama', true));
		$username = $this->input->post('username', true);
		$password = $this->input->post('password', true);
		$lembaga = $this->input->post('lembaga', true);
		$aktif = $this->input->post('aktif', true);
		$level = $this->input->post('level', true);

		// Check if username already exists
		$existing = $this->db->get_where('user', ['username' => $username])->row();
		if ($existing) {
			$this->session->set_flashdata('error', 'Username sudah terdaftar! Gunakan username lain.');
			redirect('admin/akun');
		}

		$data = [
			'id_user' => $this->uuid->v4(),
			'nama' => $nama,
			'username' => $username,
			'password' => password_hash($password, PASSWORD_BCRYPT),
			'level' => $level,
			'aktif' => $aktif,
			'lembaga' => $lembaga,
			'surat' => '',
			'foto' => '',
		];

		$this->db->insert('user', $data);

		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Akun berhasil ditambahkan');
			redirect('admin/akun');
		} else {
			$this->session->set_flashdata('error', 'Gagal menambahkan akun');
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

	public function ssh()
	{
		$this->db->select('ssh.*, kategori_ssh.nama_kategori');
		$this->db->from('ssh');
		$this->db->join('kategori_ssh', 'kategori_ssh.kode_kategori = ssh.kategori', 'left');
		$data['ssh'] = $this->db->get()->result();

		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;

		$this->load->view('admin/head', $data);
		$this->load->view('admin/ssh', $data);
		$this->load->view('admin/foot');
	}

	public function sshAdd()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['ssh'] = null;
		$data['kategori'] = $this->model->getAll('kategori_ssh')->result();

		$this->load->view('admin/head', $data);
		$this->load->view('admin/ssh_form', $data);
		$this->load->view('admin/foot');
	}

	public function saveSsh()
	{
		$data = [
			'kode' => $this->input->post('kode', true),
			'nama' => $this->input->post('nama', true),
			'satuan' => $this->input->post('satuan', true),
			'harga' => rmRp($this->input->post('harga', true)),
			'ket' => $this->input->post('ket', true),
			'kategori' => $this->input->post('kategori', true)
		];
		$this->model->input('ssh', $data);

		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Data SSH berhasil ditambahkan');
		} else {
			$this->session->set_flashdata('error', 'Data SSH gagal ditambahkan');
		}
		redirect('admin/ssh');
	}

	public function sshEdit($id)
	{
		$data['ssh'] = $this->model->getBy('ssh', 'kode', $id)->row();
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['kategori'] = $this->model->getAll('kategori_ssh')->result();

		$this->load->view('admin/head', $data);
		$this->load->view('admin/ssh_form', $data);
		$this->load->view('admin/foot');
	}

	public function updateSsh()
	{
		$id = $this->input->post('id', true); // original kode
		$data = [
			'kode' => $this->input->post('kode', true),
			'nama' => $this->input->post('nama', true),
			'satuan' => $this->input->post('satuan', true),
			'harga' => rmRp($this->input->post('harga', true)),
			'ket' => $this->input->post('ket', true),
			'kategori' => $this->input->post('kategori', true)
		];
		$this->model->update('ssh', $data, 'kode', $id);

		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Data SSH berhasil diperbarui');
		} else {
			$this->session->set_flashdata('error', 'Data SSH tidak ada perubahan');
		}
		redirect('admin/ssh');
	}

	public function delSsh($id)
	{
		$this->model->delete('ssh', 'kode', $id);

		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Data SSH berhasil dihapus');
		} else {
			$this->session->set_flashdata('error', 'Data SSH gagal dihapus');
		}
		redirect('admin/ssh');
	}

	public function saveEditAkses()
	{
		$id =  $this->input->post('id_akses', true);
		$data = [
			'login' =>  $this->input->post('login'),
			'disposisi' =>  $this->input->post('disp'),
			'pengajuan' =>  $this->input->post('pengajuan')
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
			'pengajuan' =>  $this->input->post('pengajuan'),
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

	public function mutasi() {}

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

		$data['data'] = $this->db->query("
			SELECT 
				l.kode AS lembaga,
				l.nama AS nama,
				l.pagu AS pagu,
				l.tahun AS tahun,
				rl.status AS status
			FROM lembaga l
			LEFT JOIN rab_list rl ON l.kode = rl.lembaga AND rl.tahun = l.tahun
			WHERE l.tahun = '$this->tahun'
			ORDER BY l.kode ASC
		")->result();

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

		$dppkList = $this->model->getRabByDppk($lembaga, $this->tahun)->result();
		$data['rab'] = array();
		foreach ($dppkList as $dts) :
			$dppk = $dts->kode_pak;
			$dppkData = $this->model->getBy('dppk', 'id_dppk', $dppk)->row(); // Mengambil data dari tabel DPPK
			$dataDppk = $this->model->getBy('rab_sm24', 'kode_pak', $dppk);

			$list = $dataDppk->result();
			$totalItem = count($list);

			foreach ($list as &$item) {
				$item->nama_dppk = $dppkData ? $dppkData->program : '';
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
			// Ensure coa column exists in main rab table
			if (!$this->db->field_exists('coa', 'rab')) {
				$this->db->query("ALTER TABLE rab ADD COLUMN coa VARCHAR(50) DEFAULT NULL");
			}

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
					'id_dppk' => $key->id_dppk,
					'at' => $key->at,
					'coa' => isset($key->coa) ? $key->coa : NULL
				];

				$up = ['snc' => 'sudah'];

				$this->model->input('rab', $ins);
				$this->model->update('rab_sm24', $up, 'id_rab', $key->id_rab);
			}

			// Automatically set the status in rab_list to 'selesai'
			$check_list = $this->db->get_where('rab_list', ['lembaga' => $kode, 'tahun' => $this->tahun])->row();
			if (!$check_list) {
				$this->db->insert('rab_list', [
					'lembaga' => $kode,
					'tahun' => $this->tahun,
					'status' => 'selesai'
				]);
			} else {
				$this->db->where('lembaga', $kode);
				$this->db->where('tahun', $this->tahun);
				$this->db->update('rab_list', ['status' => 'selesai']);
			}

			if ($this->db->affected_rows() > 0) {
				$this->session->set_flashdata('ok', 'Upload RAB Lembaga Berhasil dan Status Pengajuan Selesai');
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

	public function downRabTmp24()
	{
		force_download('vertical/assets/templates/Template_Upload_RAB_24.xls', NULL);
	}

	public function kosongiRab24($kode)
	{
		$this->db->where('lembaga', $kode);
		$this->db->where('tahun', $this->tahun);
		$this->db->delete('rab_sm24');

		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Draf RAB berhasil dikosongkan.');
		} else {
			$this->session->set_flashdata('error', 'Gagal atau draf sudah kosong.');
		}
		redirect('admin/rab24detail/' . $kode);
	}

	public function uploadDppk24($kode)
	{
		// Load library and helper
		$this->load->helper('file');

		// Upload config
		$config['upload_path'] = 'vertical/assets/uploads/';
		$config['allowed_types'] = 'xls|xlsx';
		$config['max_size'] = 10240;

		// Ensure directory exists
		if (!is_dir($config['upload_path'])) {
			mkdir($config['upload_path'], 0777, TRUE);
		}

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('uploadFile')) {
			$error = $this->upload->display_errors('', '');
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode([
					'status' => 'error',
					'message' => 'Upload Gagal: ' . $error
				]));
		} else {
			$upload_data = $this->upload->data();
			$file_path = $upload_data['full_path'];

			// Detect Excel extension and use proper reader
			$ext = pathinfo($file_path, PATHINFO_EXTENSION);
			if (strtolower($ext) === 'xls') {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
			} else {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			}

			try {
				$objPHPExcel = $reader->load($file_path);
				$worksheet = $objPHPExcel->getActiveSheet();
				$highestRow = $worksheet->getHighestDataRow();

				$dppk_count = 0;
				$inserted_in_memory = [];

				for ($row = 2; $row <= $highestRow; $row++) {
					$raw_prog = $worksheet->getCell('B' . $row)->getValue();
					$raw_keg = $worksheet->getCell('D' . $row)->getValue();

					// Skip empty rows
					if ($raw_prog === null || $raw_prog === '' || $raw_keg === null || $raw_keg === '') {
						continue;
					}

					// Remove leading zeros
					$kode_program = ltrim(trim(strval($raw_prog)), '0');
					if ($kode_program === '') {
						$kode_program = '0';
					}

					$kode_kegiatan = ltrim(trim(strval($raw_keg)), '0');
					if ($kode_kegiatan === '') {
						$kode_kegiatan = '0';
					}

					$program_name = preg_replace('/[^\x20-\x7E]/', '', strval($worksheet->getCell('C' . $row)->getValue()));
					$kegiatan_name = preg_replace('/[^\x20-\x7E]/', '', strval($worksheet->getCell('E' . $row)->getValue()));
					$bulan = preg_replace('/[^\x20-\x7E]/', '', strval($worksheet->getCell('F' . $row)->getValue()));

					$id_dppk = $kode_kegiatan; // id_dppk is activity code

					// Check in-memory duplicates first
					$cache_key = $id_dppk . '|' . $kode . '|' . $this->tahun;
					if (in_array($cache_key, $inserted_in_memory)) {
						continue;
					}

					$cek = $this->db->query("SELECT id_dppk FROM dppk WHERE id_dppk = '$id_dppk' AND lembaga = '$kode' AND tahun = '$this->tahun' ")->num_rows();
					if ($cek < 1) {
						$data_dppk = [
							'id_dppk' => $id_dppk,
							'lembaga' => $kode,
							'program' => $program_name,
							'kegiatan' => $kegiatan_name,
							'indikator' => '',
							'tahun' => $this->tahun,
							'bulan' => $bulan,
							'kode_program' => $kode_program,
							'kode_kegiatan' => $kode_kegiatan
						];
						$this->model->input('dppk', $data_dppk);
						$dppk_count++;
						$inserted_in_memory[] = $cache_key;
					}
				}

				delete_files($file_path);
				$this->output
					->set_content_type('application/json')
					->set_output(json_encode([
						'status' => 'success',
						'message' => 'Import DPPK Berhasil. Memproses ' . $dppk_count . ' program baru.'
					]));
			} catch (\Exception $e) {
				$this->output
					->set_content_type('application/json')
					->set_output(json_encode([
						'status' => 'error',
						'message' => 'Error membaca Excel: ' . $e->getMessage()
					]));
			}
		}
	}

	public function deleteDppk24($id_dppk, $lembaga_kode)
	{
		$this->db->where('id_dppk', $id_dppk);
		$this->db->where('lembaga', $lembaga_kode);
		$this->db->where('tahun', $this->tahun);
		$this->db->delete('dppk');

		$this->session->set_flashdata('ok', 'Data DPPK berhasil dihapus.');
		redirect('admin/rab24detail/' . $lembaga_kode);
	}

	public function clearDppk24($lembaga_kode)
	{
		$this->db->where('lembaga', $lembaga_kode);
		$this->db->where('tahun', $this->tahun);
		$this->db->delete('dppk');

		$this->session->set_flashdata('ok', 'Seluruh DPPK untuk lembaga ini berhasil dikosongkan.');
		redirect('admin/rab24detail/' . $lembaga_kode);
	}

	public function uploadRab24($kode)
	{
		// Load library and helper
		$this->load->helper('file');

		// Upload config
		$config['upload_path'] = 'vertical/assets/uploads/';
		$config['allowed_types'] = 'xls|xlsx';
		$config['max_size'] = 10240;

		// Ensure directory exists
		if (!is_dir($config['upload_path'])) {
			mkdir($config['upload_path'], 0777, TRUE);
		}

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('uploadFile')) {
			$error = $this->upload->display_errors('', '');
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode([
					'status' => 'error',
					'message' => 'Upload Gagal: ' . $error
				]));
		} else {
			$upload_data = $this->upload->data();
			$file_path = $upload_data['full_path'];

			// Detect Excel extension and use proper reader
			$ext = pathinfo($file_path, PATHINFO_EXTENSION);
			if (strtolower($ext) === 'xls') {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
			} else {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			}

			try {
				$objPHPExcel = $reader->load($file_path);
				$worksheet = $objPHPExcel->getActiveSheet();
				$highestRow = $worksheet->getHighestDataRow();

				// Check and add COA column if not exists
				if (!$this->db->field_exists('coa', 'rab_sm24')) {
					$this->db->query("ALTER TABLE rab_sm24 ADD COLUMN coa VARCHAR(50) DEFAULT NULL");
				}
				if (!$this->db->field_exists('coa', 'rab')) {
					$this->db->query("ALTER TABLE rab ADD COLUMN coa VARCHAR(50) DEFAULT NULL");
				}

				// Check and add id_dppk column if not exists
				if (!$this->db->field_exists('id_dppk', 'rab_sm24')) {
					$this->db->query("ALTER TABLE rab_sm24 ADD COLUMN id_dppk VARCHAR(50) DEFAULT NULL");
				}
				if (!$this->db->field_exists('id_dppk', 'rab')) {
					$this->db->query("ALTER TABLE rab ADD COLUMN id_dppk VARCHAR(50) DEFAULT NULL");
				}

				// Pre-fetch all DPPK records for this institution and year to avoid query in loop
				$dppk_list = $this->db->get_where('dppk', ['lembaga' => $kode, 'tahun' => $this->tahun])->result();
				$dppk_map = [];
				foreach ($dppk_list as $d) {
					$dppk_map[$d->id_dppk] = $d->kegiatan;
				}

				// Delete existing draft (rab_sm24) for this institution & year to overwrite
				$this->db->where('lembaga', $kode);
				$this->db->where('tahun', $this->tahun);
				$this->db->delete('rab_sm24');

				$insert_batch = [];
				$max_codes = [];
				$items_count = 0;

				for ($row = 2; $row <= $highestRow; $row++) {
					$raw_prog = $worksheet->getCell('B' . $row)->getValue();
					$raw_keg = $worksheet->getCell('D' . $row)->getValue();
					$raw_nama = $worksheet->getCell('I' . $row)->getValue();

					// Skip empty rows (if B, D, or I is empty)
					if ($raw_prog === null || $raw_prog === '' || $raw_keg === null || $raw_keg === '' || $raw_nama === null || $raw_nama === '') {
						continue;
					}

					// Remove leading zeros for id_dppk lookup
					$id_dppk = ltrim(trim(strval($raw_keg)), '0');
					if ($id_dppk === '') {
						$id_dppk = '0';
					}

					// Get kegiatan name from memory map or Excel fallback
					$kegiatan = isset($dppk_map[$id_dppk]) ? $dppk_map[$id_dppk] : preg_replace('/[^\x20-\x7E]/', '', strval($worksheet->getCell('E' . $row)->getValue()));

					// Auto-generate the item code in memory
					if (!isset($max_codes[$id_dppk])) {
						$max_codes[$id_dppk] = 0;
					}
					$max_codes[$id_dppk]++;
					$kodeBarang = sprintf("%03s", $max_codes[$id_dppk]);

					$coa_val = preg_replace('/[^\x20-\x7E]/', '', strval($worksheet->getCell('G' . $row)->getValue()));
					$nama_val = preg_replace('/[^\x20-\x7E]/', '', strval($raw_nama));

					// Sanitize numeric inputs (remove thousands separators if any)
					$raw_qty = strval($worksheet->getCell('J' . $row)->getValue());
					$qty_val = (float) preg_replace('/[^\x20-\x7E]/', '', $raw_qty);

					$satuan_val = preg_replace('/[^\x20-\x7E]/', '', strval($worksheet->getCell('K' . $row)->getValue()));

					$raw_harga = strval($worksheet->getCell('L' . $row)->getValue());
					$harga_satuan_val = (float) preg_replace('/[^\x20-\x7E]/', '', $raw_harga);

					$insert_batch[] = [
						'id_rab' => $this->uuid->v4(),
						'lembaga' => $kode,
						'bidang' => '',
						'jenis' => '',
						'kode' => $kode . '...' . $id_dppk . '-' . $kodeBarang,
						'nama' => $nama_val,
						'rencana' => '',
						'qty' => $qty_val,
						'satuan' => $satuan_val,
						'total' => $qty_val * $harga_satuan_val,
						'harga_satuan' => $harga_satuan_val,
						'tahun' => $this->tahun,
						'at' => date('Y-m-d H:i'),
						'snc' => 'belum',
						'kode_pak' => $id_dppk,
						'kegiatan' => $kegiatan,
						'coa' => $coa_val,
						'id_dppk' => $id_dppk
					];

					$items_count++;
				}

				if (count($insert_batch) > 0) {
					$this->db->insert_batch('rab_sm24', $insert_batch);
				}

				// Also ensure the status in rab_list is set to 'proses' so it shows up in the workflow
				$check_list = $this->db->get_where('rab_list', ['lembaga' => $kode, 'tahun' => $this->tahun])->row();
				if (!$check_list) {
					$this->db->insert('rab_list', [
						'lembaga' => $kode,
						'tahun' => $this->tahun,
						'status' => 'proses'
					]);
				} else {
					$this->db->where('lembaga', $kode);
					$this->db->where('tahun', $this->tahun);
					$this->db->update('rab_list', ['status' => 'proses']);
				}

				delete_files($file_path);
				$this->output
					->set_content_type('application/json')
					->set_output(json_encode([
						'status' => 'success',
						'message' => 'Import Excel Berhasil. Berhasil memproses ' . $items_count . ' item.'
					]));
			} catch (\Exception $e) {
				$this->output
					->set_content_type('application/json')
					->set_output(json_encode([
						'status' => 'error',
						'message' => 'Error membaca Excel: ' . $e->getMessage()
					]));
			}
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
				redirect('lembaga');
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
SELECT sarpras.tanggal AS tanggal, 'SARPRAS' AS jenis , 0 as debit, SUM(sarpras_detail.qty * sarpras_detail.harga_satuan) AS kredit FROM sarpras JOIN sarpras_detail ON sarpras.kode_pengajuan = sarpras_detail.kode_pengajuan WHERE sarpras_detail.tahun = '$this->tahun' AND sarpras.tahun = '$this->tahun' GROUP BY sarpras.tanggal

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

		$kas1 = $this->db->query("SELECT sarpras.tanggal AS tanggal, 'SARPRAS' AS jenis , 0 as debit, SUM(sarpras_detail.qty * sarpras_detail.harga_satuan) AS kredit FROM sarpras JOIN sarpras_detail ON sarpras.kode_pengajuan = sarpras_detail.kode_pengajuan WHERE sarpras_detail.tahun = '$this->tahun' AND sarpras.tahun = '$this->tahun' GROUP BY sarpras.tanggal ORDER BY sarpras.tanggal DESC")->result();

		$kas2 = $this->db6->query("SELECT tanggal AS tanggal, 'PSB' AS jenis , 0 as debit, SUM(qty * harga_satuan) AS kredit FROM pengajuan JOIN pengajuan_detail ON pengajuan.kode_pengajuan=pengajuan_detail.kode_pengajuan WHERE status = 'dicairkan' OR status = 'selesai' GROUP BY tanggal ORDER BY tanggal ")->result();

		$data['kas'] = array_merge($kas1, $kas2);

		$this->load->view('admin/head', $data);
		$this->load->view('admin/kasPanjar', $data);
		$this->load->view('admin/foot');
	}

	public function kasHutang()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['bulan'] = $this->bulan;

		$data['kas'] = $this->db->query("SELECT tanggal AS tanggal, 'LISTRIK' AS jenis , 0 as debit, SUM(nominal)  AS kredit FROM pengeluaran_rutin WHERE tahun = '$this->tahun' AND langganan = 'LISTRIK' GROUP BY tanggal 

		UNION
		SELECT tanggal AS tanggal, 'INTERNET/WIFI' AS jenis , 0 as debit, SUM(nominal)  AS kredit FROM pengeluaran_rutin WHERE tahun = '$this->tahun' AND langganan = 'INTERNET' GROUP BY tanggal

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

	public function kirimApp()
	{

		$masuk = $this->modelAll->masuk($this->tahun);
		$keluar = $this->modelAll->keluar($this->tahun);

		$pesan = '*LAPORAN KEUANGAN*
		
Laporan keadaan Keuangan saat ini pada Aplikasi SIMKUPADUKA

*Pemasukan : ' . rupiah($masuk) . '*
*Pengeluaran : ' . rupiah($keluar) . '*
*Saldo : ' . rupiah($masuk - $keluar) . '*

Update data pertanggal
*' . date('d-M-Y H:i') . '* ';

		kirim_person($this->apiKey, '085236924510', $pesan);
		kirim_person($this->apiKey, '085235583647', $pesan);
		kirim_person($this->apiKey, '085258222376', $pesan);
		redirect('admin');
	}

	public function kirimSaldo()
	{

		$bank = $this->model->getBy2('saldo', 'name', 'bank', 'tahun', $this->tahun)->row();
		$cash = $this->model->getBy2('saldo', 'name', 'cash', 'tahun', $this->tahun)->row();
		$cadangan = $this->model->getBySum('cadangan', 'tahun', $this->tahun, 'nominal')->row();

		$pesantren = $this->model->getBySum('pesantren', 'tahun', $this->tahun, 'nominal')->row();
		$realSisa = $this->model->getBySum('real_sisa', 'tahun', $this->tahun, 'sisa')->row();

		$jumlah = ($bank->nominal + $cash->nominal + ($cadangan->jml + $pesantren->jml + $realSisa->jml));

		$pesan = '*LAPORAN KEUANGAN RIIL*
		
Laporan keadaan Keuangan Riil Pesantren

*Saldo Bank : ' . rupiah($bank->nominal) . '*
*Saldo Cash di Kasir : ' . rupiah($cash->nominal) . '*
*Dana Cadangan : ' . rupiah($cadangan->jml + $pesantren->jml + $realSisa->jml) . '*
*Jumlah : ' . rupiah($jumlah) . '*

Update data pertanggal
*' . date('d-M-Y H:i') . '* ';

		kirim_person($this->apiKey, '085236924510', $pesan);
		kirim_person($this->apiKey, '085258222376', $pesan);
		kirim_person($this->apiKey, '085235583647', $pesan);
		kirim_person($this->apiKey, '082264061060', $pesan);
		redirect('admin');
	}

	public function process_upload()
	{
		$this->load->helper('file');

		// Upload configuration
		$config['upload_path'] = 'vertical/assets/uploads/';
		$config['allowed_types'] = 'xls|xlsx';
		$config['max_size'] = 10240;

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('uploadFile')) {
			$error = $this->upload->display_errors();
			$this->session->set_flashdata('error', 'Upload gagal: ' . $error);
			redirect('admin/bp');
		} else {
			$data = $this->upload->data();
			$file_path = $data['full_path'];

			try {
				$objPHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_path);
				$worksheet = $objPHPExcel->getActiveSheet();
				$highestRow = $worksheet->getHighestDataRow();

				// Month mapping
				$months_map = [
					'jan' => 1,
					'feb' => 2,
					'mar' => 3,
					'apr' => 4,
					'may' => 5,
					'mei' => 5,
					'jun' => 6,
					'jul' => 7,
					'aug' => 8,
					'agu' => 8,
					'sep' => 9,
					'oct' => 10,
					'okt' => 10,
					'nov' => 11,
					'dec' => 12,
					'des' => 12
				];

				// Helper UUID generator
				$uuid_generator = function () {
					return sprintf(
						'%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
						mt_rand(0, 0xffff),
						mt_rand(0, 0xffff),
						mt_rand(0, 0xffff),
						mt_rand(0, 0x0fff) | 0x4000,
						mt_rand(0, 0x3fff) | 0x8000,
						mt_rand(0, 0xffff),
						mt_rand(0, 0xffff),
						mt_rand(0, 0xffff)
					);
				};

				$inserted_count = 0;
				$skipped_count = 0;

				// Start from row 2 (skip header)
				for ($row = 2; $row <= $highestRow; $row++) {
					$customer_id = trim($worksheet->getCell('B' . $row)->getValue() ?? '');
					if (empty($customer_id)) {
						continue;
					}

					// Find matching nis from cost table
					$cost_row = $this->db->get_where('cost', ['cost_id' => $customer_id])->row();
					$nis = $cost_row ? $cost_row->nis : '';

					// Get amount
					$amount = intval($worksheet->getCell('H' . $row)->getValue() ?? 0);

					// Parse month from BillType (Column G)
					$billtype = $worksheet->getCell('G' . $row)->getValue() ?? '';
					$bulan_angka = 0;
					if (preg_match('/\[([A-Za-z]+)\]/', $billtype, $matches)) {
						$month_str = strtolower($matches[1] ?? '');
						$bulan_angka = $months_map[$month_str] ?? 0;
					}

					// Reject / skip duplicate entries for same month and student
					$existing = $this->db->get_where('tanggungan', [
						'nis' => $nis,
						'bulan' => $bulan_angka,
						'tahun' => $this->tahun
					])->row();

					if ($existing) {
						$skipped_count++;
						continue;
					}

					$insert_data = [
						'id_tanggungan' => $uuid_generator(),
						'nis' => $nis,
						'briva' => null,
						'nominal' => $amount,
						'bulan' => $bulan_angka,
						'tahun' => $this->tahun,
						'tgl_upload' => date('Y-m-d'),
						'kasir' => $this->user
					];

					$this->db->insert('tanggungan', $insert_data);
					$inserted_count++;
				}

				$msg = $inserted_count . ' Data tanggungan berhasil diupload';
				if ($skipped_count > 0) {
					$msg .= ' (' . $skipped_count . ' data dilewati karena sudah ada)';
				}
				$this->session->set_flashdata('ok', $msg);
			} catch (\Exception $e) {
				$this->session->set_flashdata('error', 'Terjadi kesalahan membaca file Excel: ' . $e->getMessage());
			}

			// Clean up upload file
			if (file_exists($file_path)) {
				unlink($file_path);
			}

			redirect('admin/bp');
		}
	}

	public function buatAksesAll()
	{
		$login = $this->input->post('login', true);
		$disp = $this->input->post('disp', true);
		$pengajuan = $this->input->post('pengajuan', true);
		$kpa = $this->model->getBy('lembaga', 'tahun', $this->tahun);

		foreach ($kpa->result() as $kpa) {
			$cek = $this->model->getBy2('akses', 'lembaga', $kpa->kode, 'tahun', $this->tahun)->num_rows();
			if ($cek < 1) {
				$data = [
					'lembaga' => $kpa->kode,
					'login' => $login,
					'disposisi' => $disp,
					'pengajuan' => $pengajuan,
					'tahun' => $this->tahun,
				];
				$this->model->input('akses', $data);
			}
		}

		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Generate akses berhasil');
			redirect('admin/setting');
		}
	}
	public function editAksesAll()
	{
		$login = $this->input->post('login', true);
		$disp = $this->input->post('disp', true);
		$pengajuan = $this->input->post('pengajuan', true);

		$data = [
			'login' => $login,
			'disposisi' => $disp,
			'pengajuan' => $pengajuan,
		];
		$this->model->update('akses', $data, 'tahun', $this->tahun);

		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Update akses berhasil');
			redirect('admin/setting');
		}
	}

	public function truncAkses()
	{
		$this->model->delete('akses', 'tahun', $this->tahun);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Delete akses berhasil');
			redirect('admin/setting');
		}
	}
	public function coa()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['bulan'] = $this->bulan;

		$datakirim = [];
		$dataParrent = $this->db->query("SELECT * FROM coa WHERE (parrent = '' OR parrent IS NULL OR parrent = '-') AND tahun = '{$this->tahun}' ORDER BY kode")->result();
		foreach ($dataParrent as $parent) {
			$child = $this->model->getBy2('coa', 'tahun', $this->tahun, 'parrent', $parent->kode)->result();
			$datakirim[] = [
				'parrent' => $parent,
				'child' => $child,
			];
		}
		$data['data'] = $datakirim;

		$data['coa'] = $this->db->query("SELECT * FROM coa WHERE (parrent = '' OR parrent IS NULL OR parrent = '-') AND tahun = '{$this->tahun}' ORDER BY kode")->result();
		$data['ta'] = $this->model->getAll('tahun')->result();

		$this->load->view('admin/head', $data);
		$this->load->view('admin/coa', $data);
		$this->load->view('admin/foot');
	}

	public function addCoa()
	{
		$data = [
			'kode' => $this->input->post('kode', true),
			'nama' => $this->input->post('nama', true),
			'tipe' => $this->input->post('tipe', true),
			'keterangan' => $this->input->post('keterangan', true),
			'uraian' => $this->input->post('uraian', true),
			'tahun' => $this->input->post('tahun', true),
			'parrent' => ''
		];
		$this->model->input('coa', $data);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Tambah data berhasil');
			redirect('admin/coa');
		} else {
			$this->session->set_flashdata('error', 'Tambah data gagal');
			redirect('admin/coa');
		}
	}

	public function addCoaNext()
	{
		$data = [
			'kode' => $this->input->post('kode', true),
			'nama' => $this->input->post('nama', true),
			'tipe' => $this->input->post('tipe', true),
			'keterangan' => $this->input->post('keterangan', true),
			'uraian' => $this->input->post('uraian', true),
			'tahun' => $this->input->post('tahun', true) ? $this->input->post('tahun', true) : $this->tahun,
			'parrent' => $this->input->post('parrent', true),
			'cair' => $this->input->post('cair', true)
		];
		$this->model->input('coa', $data);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Tambah data turunan berhasil');
			redirect('admin/coa');
		} else {
			$this->session->set_flashdata('error', 'Tambah data turunan gagal');
			redirect('admin/coa');
		}
	}

	public function delCoa($id)
	{
		$cek_cao = $this->model->getBy2('coa', 'kode', $id, 'tahun', $this->tahun)->row();
		if ($cek_cao && $cek_cao->parrent != '' && $cek_cao->parrent !== null && $cek_cao->parrent != '-') {
			$this->model->delete('coa', 'kode', $id);
		} else {
			$this->model->delete('coa', 'parrent', $id);
			$this->model->delete('coa', 'kode', $id);
		}
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('ok', 'Hapus data berhasil');
			redirect('admin/coa');
		} else {
			$this->session->set_flashdata('error', 'Hapus data gagal');
			redirect('admin/coa');
		}
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
		$upsert_to_db = function ($db_conn, $data_chunk, $target_fields) {
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
}
