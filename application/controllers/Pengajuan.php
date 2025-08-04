<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengajuan extends CI_Controller
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
		$this->user = $user->nama;

		if (!$this->Auth_model->current_user()) {
			redirect('login/logout');
		}
	}

	public function index()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['bulan'] = $this->bulan;

		$data['dataPj'] = $this->model->getPengajuan($this->lembaga, $this->tahun)->result();
		$data['cekSPJ'] = $this->db->query("SELECT * FROM spj WHERE lembaga = '$this->lembaga' AND tahun = '$this->tahun' AND stts != 3 ")->num_rows();
		$data['cekPjn'] = $this->db->query("SELECT * FROM pengajuan WHERE lembaga = '$this->lembaga' AND tahun = '$this->tahun' AND spj != 3 ")->num_rows();
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['akses'] = $this->model->getBy2('akses', 'tahun', $this->tahun, 'lembaga', $data['user']->lembaga)->row();

		$this->load->view('pengajuan', $data);
	}
	public function detail($kode)
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['bulan'] = $this->bulan;

		$data['data'] = $this->model->getPengajuan($this->lembaga, $this->tahun)->result();
		$data['pj'] = $this->model->getBy('pengajuan', 'kode_pengajuan', $kode)->row();
		$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
		$data['bidang'] = $this->model->getBy('bidang', 'tahun', $this->tahun)->result();
		$data['jenis'] = $this->model->getBy('jenis', 'tahun', $this->tahun)->result();
		$data['coa'] = $this->model->getBy('coa', 'tahun', $this->tahun)->result();
		// $data['program'] = $this->model->getBy2('dppk', 'tahun', $this->tahun, 'lembaga', $this->lembaga)->result();
		$pejn = $data['pj']->bulan;
		$data['program'] = $this->db->query("SELECT * FROM dppk WHERE FIND_IN_SET($pejn, bulan) AND tahun = '$this->tahun' AND lembaga = '$this->lembaga' ")->result();
		$data['satuan'] = $this->model->getAll('satuan')->result();

		$data['dataBulan'] = $this->bulan;
		$data['bulan_ini'] = date('m');

		$data['dppk'] = $this->db->query("SELECT dppk.* FROM dppk JOIN rab ON dppk.id_dppk=rab.id_dppk WHERE dppk.tahun = '$this->tahun' AND dppk.lembaga = '$this->lembaga' AND rab.rencana = 7 ")->result();

		$this->load->view('pengajuanDetail', $data);
	}

	public function child_coa()
	{
		$kode = $this->input->post('kode', true);
		$hasil =  $this->model->getBy2('coa', 'parrent', $kode, 'tahun', $this->tahun)->result();
		$parent =  $this->model->getBy2('coa', 'kode', $kode, 'tahun', $this->tahun)->row();
		echo json_encode(['hasil' => $hasil, 'parent' => $parent]);
	}

	public function loadSSH()
	{
		$this->db->select('ssh.kode, ssh.nama, kategori_ssh.nama_kategori, kategori_ssh.kode_kategori');
		$this->db->from('ssh');
		$this->db->join('kategori_ssh', 'kategori_ssh.kode_kategori = ssh.kategori');
		$query = $this->db->get()->result();

		// Grupkan berdasarkan kategori
		$grouped = [];

		foreach ($query as $row) {
			$grouped[$row->kode_kategori . '. ' . $row->nama_kategori][] = [
				'value' => $row->kode,
				'text'  => $row->nama
			];
		}

		// Format untuk <optgroup>
		$result = [];
		foreach ($grouped as $kategori => $options) {
			$result[] = [
				'label' => $kategori,
				'options' => $options
			];
		}

		// Output sebagai JSON
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($result));
	}

	public function detilSsh()
	{
		$kode = $this->input->post('kode', true);
		$hasil =  $this->model->getBy('ssh', 'kode', $kode)->row();
		$kategori =  $this->model->getBy('kategori_ssh', 'kode_kategori', $hasil->kategori)->row();
		echo json_encode(['hasil' => $hasil, 'kategori' => $kategori]);
	}

	public function addItemBarang()
	{
		$id_realis = $this->uuid->v4();
		$kode_pengajuan = $this->input->post('kode_pengajuan', true);
		$program = $this->input->post('program', true);
		$coa = $this->input->post('coa', true);
		$ssh = $this->input->post('ssh', true);
		$vol = $this->input->post('qty', true);

		$dt = $this->model->getBy('pengajuan', 'kode_pengajuan', $kode_pengajuan)->row();
		if ($dt->stts === 'yes') {
			echo json_encode(['status' => 'error', 'message' => 'Tidak bisa tambah item baru. Pengajuan sudah diproses']);
			exit();
		}


		$cekRealis = $this->model->getBy2('realis', 'lembaga', $this->lembaga, 'tahun', $this->tahun)->num_rows();
		$cekRealisSm = $this->model->getBy2('real_sm', 'lembaga', $this->lembaga, 'tahun', $this->tahun)->num_rows();
		$urut = $cekRealis + $cekRealisSm == 0 ? str_pad(1, 3, '0', STR_PAD_LEFT) : str_pad(($cekRealis + $cekRealisSm + 1), 3, '0', STR_PAD_LEFT);

		$dataSsh = $this->model->getBy('ssh', 'kode', $ssh)->row();

		$nomProg = $this->db->query("SELECT SUM(total) AS total FROM rab WHERE id_dppk = '$program' AND tahun = '$this->tahun' ")->row();
		$nomPakai = $this->db->query("SELECT SUM(nominal) AS total FROM realis WHERE kode LIKE '%-$program-%' AND tahun = '$this->tahun' ")->row();
		$nomSm = $this->db->query("SELECT SUM(nominal) AS total FROM real_sm WHERE kode LIKE '%-$program-%' AND tahun = '$this->tahun' ")->row();
		$sisa = $nomProg->total - ($nomPakai->total + $nomSm->total);

		if (($vol * $dataSsh->harga) > $sisa) {
			echo json_encode(['status' => 'error', 'message' => 'Nominal melebihi batas']);
			die();
		}

		$kode = $this->lembaga . '-' . $program . '-' . $coa . '-' . $ssh . '-' . $urut;

		$data = [
			'id_realis' => $id_realis,
			'lembaga' => $this->lembaga,
			'bidang' => '-',
			'jenis' => $dataSsh->kategori,
			'kode' => $kode,
			'vol' => $vol,
			'harga' => $dataSsh->harga,
			'nominal' => $dataSsh->harga * $vol,
			'tgl' => date('Y-m-d'),
			'pj' => $this->user,
			'bulan' => date('m'),
			'tahun' => $this->tahun,
			'ket' => $dataSsh->nama . ' - @ ' . $vol . ' x ' . number_format($dataSsh->harga, 0, ',', '.'),
			'kode_pengajuan' => $kode_pengajuan,
			'nom_cair' => $dataSsh->harga * $vol,
			'nom_serap' => $dataSsh->harga * $vol,
			'stas' => 'non tunai'
		];
		$data2 = [
			'id_detail' => $id_realis,
			'kode_coa' => $coa,
			'kode_ssh' => $ssh,
			'kode_program' => $program,
			'created_at' => date('Y-m-d H:i:s'),
		];

		$this->model->input('real_sm', $data);
		$this->model->input('realis_detail', $data2);
		if ($this->db->affected_rows() > 0) {
			echo json_encode(['status' => 'success', 'message' => 'Tambah item data berhasil']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Tambah item data gagal']);
		}
	}
	public function addItemBarangModal()
	{
		$id_realis = $this->uuid->v4();
		$kode_pengajuan = $this->input->post('kode_pengajuan', true);
		$program = $this->input->post('program', true);
		$coa = $this->input->post('coa', true);
		$vol = $this->input->post('qty', true);
		$nama = $this->input->post('nama', true);
		$satuan = $this->input->post('satuan', true);
		$harga_satuan = rmRp($this->input->post('harga_satuan', true));

		$dt = $this->model->getBy('pengajuan', 'kode_pengajuan', $kode_pengajuan)->row();
		if ($dt->stts === 'yes') {
			$this->session->set_flashdata('error', 'Tidak bisa tambah item baru. Pengajuan sudah diproses');
			redirect('pengajuan/detail/' . $kode_pengajuan);
		}
		if ($program == '' || $coa == '') {
			$this->session->set_flashdata('error', 'Program atau Akun (COA) belum dipilih');
			redirect('pengajuan/detail/' . $kode_pengajuan);
		}

		$nomProg = $this->db->query("SELECT SUM(total) AS total FROM rab WHERE id_dppk = '$program' AND tahun = '$this->tahun' ")->row();
		$nomPakai = $this->db->query("SELECT SUM(nominal) AS total FROM realis WHERE kode LIKE '%-$program-%' AND tahun = '$this->tahun' ")->row();
		$nomSm = $this->db->query("SELECT SUM(nominal) AS total FROM real_sm WHERE kode LIKE '%-$program-%' AND tahun = '$this->tahun' ")->row();
		$sisa = $nomProg->total - ($nomPakai->total + $nomSm->total);

		if (($vol * $harga_satuan) > $sisa) {
			$this->session->set_flashdata('error', 'Nominal melebihi batas');
			redirect('pengajuan/detail/' . $kode_pengajuan);
		}

		$cekRealis = $this->model->getBy2('realis', 'lembaga', $this->lembaga, 'tahun', $this->tahun)->num_rows();
		$cekRealisSm = $this->model->getBy2('real_sm', 'lembaga', $this->lembaga, 'tahun', $this->tahun)->num_rows();
		$urut = $cekRealis + $cekRealisSm == 0 ? str_pad(1, 3, '0', STR_PAD_LEFT) : str_pad(($cekRealis + $cekRealisSm + 1), 3, '0', STR_PAD_LEFT);

		// $dataSsh = $this->model->getBy('ssh', 'kode', $ssh)->row();

		$kode = $this->lembaga . '-' . $program . '-' . $coa . '-BnS-' . $urut;

		$data = [
			'id_realis' => $id_realis,
			'lembaga' => $this->lembaga,
			'bidang' => '-',
			'jenis' => 'BnSHH',
			'kode' => $kode,
			'vol' => $vol,
			'harga' => $harga_satuan,
			'nominal' => $harga_satuan * $vol,
			'tgl' => date('Y-m-d'),
			'pj' => $this->user,
			'bulan' => date('m'),
			'tahun' => $this->tahun,
			'ket' => $nama . ' - @ ' . $vol . ' ' . $satuan . ' x ' . number_format($harga_satuan, 0, ',', '.'),
			'kode_pengajuan' => $kode_pengajuan,
			'nom_cair' => $harga_satuan * $vol,
			'nom_serap' => $harga_satuan * $vol,
			'stas' => 'non tunai'
		];
		$data2 = [
			'id_detail' => $id_realis,
			'kode_coa' => $coa,
			'kode_ssh' => 'BnS',
			'kode_program' => $program,
			'created_at' => date('Y-m-d H:i:s'),
		];

		$this->model->input('real_sm', $data);
		$this->model->input('realis_detail', $data2);
		if ($this->db->affected_rows() > 0) {
			$this->session->set_flashdata('success', 'Tambah Barang berhasil');
			redirect('pengajuan/detail/' . $kode_pengajuan);
		} else {
			$this->session->set_flashdata('error', 'Tambah barang gagal');
			redirect('pengajuan/detail/' . $kode_pengajuan);
		}
	}

	public function addItemTunai()
	{
		$id_realis = $this->uuid->v4();
		$kode_pengajuan = $this->input->post('kode_pengajuan', true);
		$program = $this->input->post('program-tunai', true);
		$coa = $this->input->post('coa', true);
		$barang = $this->input->post('barang', true);
		$satuan = $this->input->post('satuan', true);
		$harga = rmRp($this->input->post('harga', true));
		$vol = $this->input->post('qty', true);
		$ssh = 'TNI';

		$dt = $this->model->getBy('pengajuan', 'kode_pengajuan', $kode_pengajuan)->row();
		if ($dt->stts === 'yes') {
			echo json_encode(['status' => 'error', 'message' => 'Tidak bisa tambah item baru. Pengajuan sudah diproses']);
			exit();
		}

		$nomProg = $this->db->query("SELECT SUM(total) AS total FROM rab WHERE id_dppk = '$program' AND tahun = '$this->tahun' ")->row();
		$nomPakai = $this->db->query("SELECT SUM(nominal) AS total FROM realis WHERE kode LIKE '%-$program-%' AND tahun = '$this->tahun' ")->row();
		$nomSm = $this->db->query("SELECT SUM(nominal) AS total FROM real_sm WHERE kode LIKE '%-$program-%' AND tahun = '$this->tahun' ")->row();
		$sisa = $nomProg->total - ($nomPakai->total + $nomSm->total);

		if (($vol * $harga) > $sisa) {
			echo json_encode(['status' => 'error', 'message' => 'Nominal melebihi batas']);
			die();
		}

		$cekRealis = $this->model->getBy2('realis', 'lembaga', $this->lembaga, 'tahun', $this->tahun)->num_rows();
		$cekRealisSm = $this->model->getBy2('real_sm', 'lembaga', $this->lembaga, 'tahun', $this->tahun)->num_rows();
		$urut = $cekRealis + $cekRealisSm == 0 ? str_pad(1, 3, '0', STR_PAD_LEFT) : str_pad(($cekRealis + $cekRealisSm + 1), 3, '0', STR_PAD_LEFT);

		$kode = $this->lembaga . '-' . $program . '-' . $coa . '-' . $ssh . '-' . $urut;

		$data = [
			'id_realis' => $id_realis,
			'lembaga' => $this->lembaga,
			'bidang' => '-',
			'jenis' => 'T',
			'kode' => $kode,
			'vol' => $vol,
			'harga' => $harga,
			'nominal' => $harga * $vol,
			'tgl' => date('Y-m-d'),
			'pj' => $this->user,
			'bulan' => date('m'),
			'tahun' => $this->tahun,
			'ket' => $barang . ' - @ ' . $vol . ' ' . $satuan . ' x ' . number_format($harga, 0, ',', '.'),
			'kode_pengajuan' => $kode_pengajuan,
			'nom_cair' => $harga * $vol,
			'nom_serap' => $harga * $vol,
			'stas' => 'tunai'
		];
		$data2 = [
			'id_detail' => $id_realis,
			'kode_coa' => $coa,
			'kode_ssh' => $ssh,
			'kode_program' => $program,
			'created_at' => date('Y-m-d H:i:s'),
		];

		$this->model->input('real_sm', $data);
		$this->model->input('realis_detail', $data2);
		if ($this->db->affected_rows() > 0) {
			echo json_encode(['status' => 'success', 'message' => 'Tambah item data berhasil']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Tambah item data gagal']);
		}
	}


	public function loadTable()
	{
		$kode = $this->input->post('kode', true);
		$qr = $this->db->query("SELECT id_realis, a.kode as kode_item, d.nama as coa, c.nama as ssh, a.harga, a.vol, c.satuan, a.stas, a.ket FROM real_sm a JOIN realis_detail b ON a.id_realis=b.id_detail LEFT JOIN ssh c ON b.kode_ssh=c.kode JOIN coa d ON b.kode_coa=d.kode WHERE a.kode_pengajuan = '$kode' ORDER BY b.created_at DESC")->result();

		echo json_encode($qr);
	}

	public function delItem()
	{
		$id = $this->input->post('id', true);

		$sn = $this->model->getBy('real_sm', 'id_realis', $id)->row();
		$dt = $this->model->getBy('pengajuan', 'kode_pengajuan', $sn->kode_pengajuan)->row();
		if ($dt->stts === 'yes') {
			echo json_encode(['status' => 'error', 'message' => 'Pengajuan sudah diproses']);
		} else {
			$this->model->delete('real_sm', 'id_realis', $id);
			$this->model->delete('realis_detail', 'id_detail', $id);
			if ($this->db->affected_rows() > 0) {
				echo json_encode(['status' => 'success', 'message' => 'Hapus item data berhasil']);
			} else {
				echo json_encode(['status' => 'error', 'message' => 'Hapus item data gagal']);
			}
		}
	}

	public function totalPengjuan()
	{
		$kode = $this->input->post('kode', true);
		$total = $this->db->query("SELECT SUM(nominal) as total FROM real_sm WHERE kode_pengajuan = '$kode'")->row('total');
		echo json_encode($total);
	}

	public function pengajuanAdd()
	{
		$tahun = $this->tahun;
		$lembaga = $this->lembaga;
		$jenis = $this->input->post('jenis', true);
		$tahunInput = $this->input->post('tahun', true);
		$bln = $this->input->post('bulan', true);

		$cek = $this->db->query("SELECT * FROM pengajuan WHERE lembaga = '$lembaga' AND tahun = '$tahun' AND verval != 1 AND apr != 1 AND cair != 1 AND spj != 3 ")->row();
		if ($cek) {
			$this->session->set_flashdata('error', 'Ada pengajuan yang belum selesai');
			redirect('pengajuan');
		}

		$pj = $this->db->query("SELECT MAX(no_urut) as nu FROM pengajuan WHERE tahun = '$tahun'")->row();
		$urut = $pj->nu + 1;


		$dataKode = $this->db->query("SELECT COUNT(*) as jml FROM pengajuan WHERE lembaga = '$lembaga' AND tahun = '$tahun' ")->row();
		$kodeBarang = $dataKode->jml + 1;
		$noUrut = (int) substr($kodeBarang, 0, 3);
		if ($jenis === 'disposisi') {
			$kodeBarang = sprintf("%03s", $noUrut) . '.DISP.' . $lembaga . '.' . date('dd') . '.' . $bln . '.' . $tahunInput;
			$rdrc = 'disposisi';
		} else {
			$kodeBarang = sprintf("%03s", $noUrut) . '.' . $lembaga . '.' . date('dd') . '.' . $bln . '.' . $tahunInput;
			$rdrc = 'pengajuan';
		}
		$kd_pjn = htmlspecialchars($kodeBarang);

		$data = [
			'id_pn' => $this->uuid->v4(),
			'kode_pengajuan' => $kd_pjn,
			'lembaga' => $lembaga,
			'bulan' => $this->input->post('bulan', true),
			'tahun' => $this->tahun,
			'no_urut' => $urut,
			'at' => date('Y-m-d H:i'),
			'stts' => 'no'
		];
		$data2 = [
			'id_spj' => $this->uuid->v4(),
			'kode_pengajuan' => $kd_pjn,
			'lembaga' => $lembaga,
			'bulan' => $this->input->post('bulan', true),
			'tahun' => $this->tahun,
			'no_urut' => $urut
		];

		$cek = $this->db->query("SELECT * FROM pengajuan WHERE kode_pengajuan = '$kd_pjn' AND tahun = '$tahun' ")->num_rows();
		if ($cek < 1) {
			$this->model->input('pengajuan', $data);
			$this->model->input('spj', $data2);

			if ($this->db->affected_rows() > 0) {
				$this->session->set_flashdata('ok', 'Pengajuan baru berhasil dibuat');
				redirect($rdrc);
			} else {
				$this->session->set_flashdata('error', 'Pengajuan baru tidak bisa');
				redirect($rdrc);
			}
		} else {
			$this->session->set_flashdata('error', 'Maaf. Sudah ada pengajuan hari ini');
			redirect($rdrc);
		}
	}

	public function ajukan($kode)
	{
		$bulan = $this->bulan;
		$data = [
			'stts' => 'yes',
			// 'verval' => 1,
			// 'apr' => 1
		];

		$dt = $this->model->getBy('pengajuan', 'kode_pengajuan', $kode)->row();
		if ($dt->stts === 'yes') {
			$this->session->set_flashdata('warning', 'Pengajuan sudah diajukan');
			redirect('pengajuan/detail/' . $kode);
		}
		$cekPj = $this->model->getBy('real_sm', 'kode_pengajuan', $dt->kode_pengajuan)->row();
		$lm = $this->model->getBy2('lembaga', 'kode', $dt->lembaga, 'tahun', $this->tahun)->row();
		$jml = $this->model->getBySum('real_sm', 'kode_pengajuan', $dt->kode_pengajuan, 'nominal')->row();

		$perod = $bulan[$dt->bulan] . ' ' . $dt->tahun;
		// $ww = date('d-M-Y H:i:s');

		if (preg_match("/DISP./i", $dt->kode_pengajuan)) {
			$rt = '*(DISPOSISI)*';
		} else {
			$rt = '';
		}
		$history = [
			'kode_pengajuan' => $kode,
			'lembaga' => $dt->lembaga,
			'tgl_verval' => date('Y-m-d H:i:s'),
			'user' => $this->user,
			'stts' => 'diajukan',
			'tahun' => $this->tahun,
			'pesan' => 'Diajukan KPA'
		];

		$psn_old = '*INFORMASI PENGAJUAN* ' . $rt . '

Ada pengajuan baru dari :
    
Lembaga : ' . $lm->nama . '
Kode Pengajuan : ' . $dt->kode_pengajuan . '
Periode : ' . $perod . '
Pada : ' . $dt->at . '
Nominal : ' . rupiah($jml->jml) . '

*_dimohon kepada Bendahara untuk segera mengecek pengjuan tersebut di https://simkupaduka.ppdwk.com_*
Terimakasih';

		$psn = '🌟 [INFORMASI PENGAJUAN BARU]
Mohon perhatian, ada pengajuan terbaru dengan detail sebagai berikut:

━━━━━━━━━━━━━━━━━━━━
🔖 Kode Pengajuan
' . $dt->kode_pengajuan . '

🏫 Lembaga
' . $lm->nama . '

📆 Periode
' . $perod . '

🗓️ Tanggal Pengajuan
' . $dt->at . '

💵 Total Pengajuan
' . rupiah($jml->jml) . '
━━━━━━━━━━━━━━━━━━━━

📌 Akan segera ditindaklanjuti oleh Bendahara dan Perencanaan melalui link berikut:
🔗 https://simkupaduka.ppdwk.com

🙏 Terima kasih atas kerjasamanya.';

		if ($cekPj->pj == '' || $cekPj->tgl == '') {
			$this->session->set_flashdata('error', 'Maaf. Nama PJ dan Tanggal belum diisi. Silahkan klik tombol - Edit PJ - Berwarna Kuning');
			redirect('pengajuan/detail/' . $kode);
		} else {
			$this->model->update('pengajuan', $data, 'kode_pengajuan', $kode);
			$this->model->input('history', $history);
			if ($this->db->affected_rows() > 0) {

				kirim_group($this->apiKey, '120363040973404347@g.us', $psn);
				kirim_group($this->apiKey, '120363042148360147@g.us', $psn);
				// kirim_person($this->apiKey, '082302301003', $psn);
				// kirim_person($this->apiKey, '082264061060', $psn);
				kirim_person($this->apiKey, '085236924510', $psn);

				$this->session->set_flashdata('ok', 'Pengajuan berhasil diajukan kepada Bendahara');
				redirect('pengajuan/detail/' . $kode);
			} else {
				$this->session->set_flashdata('error', 'Pengajuan gagal diajukan kepada Bendahara');
				redirect('pengajuan/detail/' . $kode);
			}
		}
	}

	public function rencana()
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['bulan'] = $this->bulan;

		if ($data['user']->lembaga !== '03') {
			echo "Maaf Akun anda bukan perencanaan";
		} else {
			$data['dataPj'] = $this->db->query("SELECT pengajuan.*, lembaga.nama as nmlmb FROM pengajuan LEFT JOIN lembaga ON pengajuan.lembaga=lembaga.kode WHERE pengajuan.tahun = '$this->tahun' AND lembaga.tahun = '$this->tahun' AND pengajuan.stts = 'yes' ")->result();
			$data['cekSPJ'] = $this->db->query("SELECT * FROM spj WHERE lembaga = '$this->lembaga' AND tahun = '$this->tahun' AND stts != 3 ")->num_rows();
			$data['cekPjn'] = $this->db->query("SELECT * FROM pengajuan WHERE lembaga = '$this->lembaga' AND tahun = '$this->tahun' AND spj != 3 ")->num_rows();
			$data['lembaga'] = $this->model->getBy2('lembaga', 'kode', $this->lembaga, 'tahun', $this->tahun)->row();
			$data['akses'] = $this->model->getBy2('akses', 'tahun', $this->tahun, 'lembaga', $data['user']->lembaga)->row();

			$this->load->view('rencana', $data);
		}
	}

	public function rencanaDtl($kode)
	{
		$data['user'] = $this->Auth_model->current_user();
		$data['tahun'] = $this->tahun;
		$data['bulan'] = $this->bulan;

		$pejn = $this->model->getBy('pengajuan', 'kode_pengajuan', $kode)->row();
		$data['dppk'] = $this->db->query("SELECT * FROM dppk WHERE FIND_IN_SET($pejn->bulan, bulan) AND tahun = '$this->tahun' AND lembaga = '$pejn->lembaga' ")->result();

		$dataKirim = [];
		$dataSQL = $this->db->query("SELECT * FROM real_sm WHERE kode_pengajuan = '$kode' ")->result();
		foreach ($dataSQL as $key => $value) {
			$kodefull = explode('-', $value->kode);
			$program = $this->model->getBy2('dppk', 'id_dppk', $kodefull[1], 'tahun', $this->tahun)->row();
			$dataKirim[] = [
				'kode' => $program->id_dppk,
				'program' => $program->program,
				'bulan' => $program->bulan,
				'rincian' => $value->ket,
			];
		}
		$data['dataKirim'] = $dataKirim;
		$data['pengajuan'] = $pejn;

		$this->load->view('rencanaDtl', $data);
	}

	public function vervalRencana($kode)
	{
		$dtPj = $this->model->getBy('pengajuan', 'kode_pengajuan', $kode)->row();
		$lembaga = $this->model->getBy2('lembaga', 'kode', $dtPj->lembaga, 'tahun', $this->tahun)->row();
		if (preg_match("/DISP./i", $kode)) {
			$rt = '*(DISPOSISI)*';
		} else {
			$rt = '';
		}
		$bwh = $dtPj->verval == 1 ? 'Pengajuan sudah bisa dicairkan. Dimohon kepada KPA Lembaga Terkait untuk menghubungi Admin Pencairan.' : 'Selanjutnya Pengajuan menunggu verifikasi dari bendahara';

		$history = [
			'kode_pengajuan' => $kode,
			'lembaga' => $dtPj->lembaga,
			'tgl_verval' => date('Y-m-d H:i:s'),
			'user' => $this->user,
			'stts' => 'verifikasi',
			'tahun' => $this->tahun,
			'pesan' => 'Diverifikasi Perencanaan'
		];

		$this->model->update('pengajuan', ['apr' => 1], 'kode_pengajuan', $kode);
		$this->model->input('history', $history);
		if ($this->db->affected_rows() > 0) {
			$psn = '*INFORMASI VERIFIKASI PENGAJUAN* ' . $rt . '
			
pengajuan dari :

Lembaga : ' . $lembaga->nama . '
Kode Pengajuan : ' . $kode . '
Periode : ' . bulan($dtPj->bulan) . ' ' . $dtPj->tahun . '
*Telah di Verifikasi dan Validasi Oleh Bagian Perencanaan pada ' . date('Y-m-d') . '*

*_' . $bwh . '_*
Terimakasih';
			kirim_group($this->apiKey, '120363040973404347@g.us', $psn);
			kirim_group($this->apiKey, '120363042148360147@g.us', $psn);
			kirim_person($this->apiKey, '085236924510', $psn);
			$this->session->set_flashdata('ok', 'Verifikasi berhasil');
			redirect('pengajuan/rencana');
		} else {
			$this->session->set_flashdata('error', 'Verifikasi gagal');
			redirect('pengajuan/rencana');
		}
	}
	public function tolakRencana()
	{
		$kode = $this->input->post('kode_pengajuan', true);
		$alasan = $this->input->post('alasan', true);

		$dtPj = $this->model->getBy('pengajuan', 'kode_pengajuan', $kode)->row();
		$lembaga = $this->model->getBy2('lembaga', 'kode', $dtPj->lembaga, 'tahun', $this->tahun)->row();

		if (preg_match("/DISP./i", $kode)) {
			$rt = '*(DISPOSISI)*';
		} else {
			$rt = '';
		}
		$history = [
			'kode_pengajuan' => $kode,
			'lembaga' => $dtPj->lembaga,
			'tgl_verval' => date('Y-m-d H:i:s'),
			'user' => $this->user,
			'stts' => 'ditolak',
			'tahun' => $this->tahun,
			'pesan' => 'Ditolak Perencanaan. ' . $alasan
		];
		$this->model->input('history', $history);
		$this->model->update('pengajuan', ['apr' => 0, 'stts' => 'no'], 'kode_pengajuan', $kode);
		if ($this->db->affected_rows() > 0) {
			$psn = '*INFORMASI PENOLAKAN PENGAJUAN* ' . $rt . '
			
pengajuan dari :

Lembaga : ' . $lembaga->nama . '
Kode Pengajuan : ' . $kode . '
Periode : ' . bulan($dtPj->bulan) . ' ' . $dtPj->tahun . '
*Ditolak* Oleh Bagian Perencanaan pada ' . date('Y-m-d') . ', dengan catatan :

*_' . $alasan . '_*
Kepada KPA terkait diharapkan untuk merevisi ulang. Terimakasih';
			kirim_group($this->apiKey, '120363040973404347@g.us', $psn);
			kirim_group($this->apiKey, '120363042148360147@g.us', $psn);
			kirim_person($this->apiKey, '085236924510', $psn);
			$this->session->set_flashdata('ok', 'Penolakan berhasil');
			redirect('pengajuan/rencana');
		} else {
			$this->session->set_flashdata('error', 'Penolakan gagal');
			redirect('pengajuan/rencana');
		}
	}
}
