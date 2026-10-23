<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{
    private $bulan_list = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Auth_model');

        // Set default CORS headers for API accessibility
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-API-KEY, X-Token');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }
    }

    /**
     * Helper: Validate Token from Authorization header or parameters via data.ppdwk.com
     */
    private function _authenticate()
    {
        // 1. Ambil token dari Header
        $headers = $this->input->get_request_header('Authorization');
        if (empty($headers)) {
            $headers = $this->input->get_request_header('authorization');
        }
        if (empty($headers) && isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = $_SERVER['HTTP_AUTHORIZATION'];
        }
        if (empty($headers) && isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $headers = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }
        if (empty($headers)) {
            $headers = $this->input->get_request_header('X-API-KEY') ?? $this->input->get_request_header('x-api-key');
        }

        $token = '';
        if (!empty($headers)) {
            $token = trim(str_ireplace('Bearer ', '', $headers));
        }

        // 2. Ambil token dari GET / POST / JSON body jika tidak ada di header
        if (empty($token)) {
            $raw_input = file_get_contents('php://input');
            $json_data = json_decode($raw_input, true);
            if (!empty($json_data['token'])) {
                $token = trim($json_data['token']);
            } elseif ($this->input->post('token', true)) {
                $token = trim($this->input->post('token', true));
            } elseif ($this->input->get('token', true)) {
                $token = trim($this->input->get('token', true));
            }
        }

        if (empty($token)) {
            $this->_jsonResponse([
                'status' => 'error',
                'message' => 'Unauthorized: Token autentikasi wajib disertakan'
            ], 401);
            exit;
        }

        // 3. Validasi token ke API data.ppdwk.com (Profile Auth)
        $ch = curl_init('https://data.ppdwk.com/api/auth/profile');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $curl_response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $is_valid = false;
        if ($http_code === 200 && !empty($curl_response)) {
            $api_res = json_decode($curl_response, true);
            if (
                !(isset($api_res['status']) && ($api_res['status'] === 401 || $api_res['status'] === 'unauthorized')) &&
                !(isset($api_res['message']) && stripos($api_res['message'], 'unauthorized') !== false)
            ) {
                $is_valid = true;
            }
        }

        // Fallback: Cek jika token cocok dengan pengaturan lokal (settings / api_key)
        if (!$is_valid) {
            $setting_rows = $this->db->where_in('name', ['api_key_rekom', 'token_bearer', 'api_key', 'api_token'])
                ->get('settings')
                ->result();

            foreach ($setting_rows as $row) {
                if (!empty($row->val) && hash_equals($row->val, $token)) {
                    $is_valid = true;
                    break;
                }
            }

            if (!$is_valid) {
                $cek_any = $this->db->where('val', $token)->get('settings')->row();
                if ($cek_any) {
                    $is_valid = true;
                }
            }

            if (!$is_valid && $this->db->table_exists('api_key')) {
                $cek_ak = $this->db->where('nama_key', $token)->get('api_key')->row();
                if ($cek_ak) {
                    $is_valid = true;
                }
            }
        }

        if (!$is_valid) {
            $this->_jsonResponse([
                'status' => 'error',
                'message' => 'Unauthorized: Token tidak valid atau sesi telah berakhir'
            ], 401);
            exit;
        }

        return true;
    }

    /**
     * Helper: Resolve Tahun Ajaran aktif
     */
    private function _getTahun()
    {
        $tahun = $this->input->get('tahun', true) ?? $this->input->post('tahun', true);
        if (empty($tahun)) {
            $raw_input = file_get_contents('php://input');
            $json_data = json_decode($raw_input, true);
            if (!empty($json_data['tahun'])) {
                $tahun = trim($json_data['tahun']);
            }
        }

        if (empty($tahun)) {
            $latest = $this->db->order_by('nama_tahun', 'DESC')->get('tahun')->row();
            $tahun = $latest ? $latest->nama_tahun : date('Y') . '/' . (date('Y') + 1);
        }

        return $tahun;
    }

    /**
     * Helper: Response JSON
     */
    private function _jsonResponse($data, $status_code = 200)
    {
        $this->output
            ->set_status_header($status_code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }

    /**
     * 1. GET /api/pengajuan/summary
     * Menyajikan ringkasan metrik KPI pengajuan untuk monitoring dashboard
     */
    public function pengajuanSummary()
    {
        $this->_authenticate();
        $tahun = $this->_getTahun();

        // Total count & status breakdown
        $pengajuan_list = $this->db->where('tahun', $tahun)->get('pengajuan')->result();
        $total_pengajuan = count($pengajuan_list);

        $menunggu_verval = 0;
        $menunggu_persetujuan = 0;
        $siap_cair = 0;
        $sudah_cair = 0;
        $ditolak = 0;
        $spj_belum = 0;
        $spj_proses = 0;
        $spj_selesai = 0;
        $disposisi_count = 0;

        foreach ($pengajuan_list as $p) {
            if (stripos($p->kode_pengajuan, 'DISP.') !== false) {
                $disposisi_count++;
            }

            if ($p->verval == 2 || $p->apr == 2) {
                $ditolak++;
            } elseif ($p->cair == 1) {
                $sudah_cair++;
                if ($p->spj == 0) {
                    $spj_belum++;
                } elseif ($p->spj == 1) {
                    $spj_proses++;
                } elseif ($p->spj == 2) {
                    $spj_selesai++;
                }
            } elseif ($p->verval == 1 && $p->apr == 1) {
                $siap_cair++;
            } elseif ($p->verval == 1 && $p->apr == 0) {
                $menunggu_persetujuan++;
            } else {
                $menunggu_verval++;
            }
        }

        // Total nominal diajukan (gabungan real_sm dan realis)
        $q_rsm = $this->db->select('SUM(nominal) as total, SUM(nom_cair) as total_cair')
            ->from('real_sm')
            ->where('tahun', $tahun)
            ->get()->row();

        $q_rls = $this->db->select('SUM(nominal) as total, SUM(nom_cair) as total_cair')
            ->from('realis')
            ->where('tahun', $tahun)
            ->get()->row();

        $nominal_diajukan = (float)($q_rsm ? $q_rsm->total : 0) + (float)($q_rls ? $q_rls->total : 0);
        $nominal_cair = (float)($q_rsm ? $q_rsm->total_cair : 0) + (float)($q_rls ? $q_rls->total_cair : 0);

        // Pagu Anggaran Total RAB tahun ini
        $pagu_row = $this->db->select('SUM(total) as total_pagu')
            ->from('rab')
            ->where('tahun', $tahun)
            ->get()->row();
        $pagu_total = $pagu_row ? (float)$pagu_row->total_pagu : 0;
        $sisa_pagu_total = max(0, $pagu_total - $nominal_cair);
        $persen_serapan = $pagu_total > 0 ? round(($nominal_cair / $pagu_total) * 100, 2) : 0;

        $response = [
            'status' => 'success',
            'tahun_ajaran' => $tahun,
            'summary' => [
                'pagu_anggaran_rab' => [
                    'total' => $pagu_total,
                    'total_formatted' => 'Rp ' . number_format($pagu_total, 0, ',', '.'),
                    'sisa' => $sisa_pagu_total,
                    'sisa_formatted' => 'Rp ' . number_format($sisa_pagu_total, 0, ',', '.'),
                    'persen_serapan' => $persen_serapan
                ],
                'nominal' => [
                    'diajukan' => $nominal_diajukan,
                    'diajukan_formatted' => 'Rp ' . number_format($nominal_diajukan, 0, ',', '.'),
                    'cair' => $nominal_cair,
                    'cair_formatted' => 'Rp ' . number_format($nominal_cair, 0, ',', '.'),
                ],
                'pengajuan_counts' => [
                    'total' => $total_pengajuan,
                    'menunggu_verval' => $menunggu_verval,
                    'menunggu_persetujuan' => $menunggu_persetujuan,
                    'siap_cair' => $siap_cair,
                    'sudah_cair' => $sudah_cair,
                    'ditolak' => $ditolak,
                    'disposisi' => $disposisi_count
                ],
                'spj_monitoring' => [
                    'total_cair' => $sudah_cair,
                    'belum_upload' => $spj_belum,
                    'proses_verifikasi' => $spj_proses,
                    'selesai' => $spj_selesai
                ]
            ]
        ];

        $this->_jsonResponse($response);
    }

    /**
     * 2. GET /api/pengajuan/list
     * Daftar pengajuan dengan filter (status, lembaga, jenis, bulan, pencarian, pagination)
     */
    public function pengajuanList()
    {
        $this->_authenticate();
        $tahun = $this->_getTahun();

        $lembaga = $this->input->get('lembaga', true);
        $status = $this->input->get('status', true);
        $jenis = $this->input->get('jenis', true);
        $bulan = $this->input->get('bulan', true);
        $search = $this->input->get('search', true);
        $page = max(1, (int)$this->input->get('page', true));
        $limit = min(200, max(1, (int)($this->input->get('limit', true) ?: 20)));
        $offset = ($page - 1) * $limit;

        $this->db->from('pengajuan');
        $this->db->join('lembaga', 'pengajuan.lembaga = lembaga.kode AND lembaga.tahun = pengajuan.tahun', 'left');
        $this->db->where('pengajuan.tahun', $tahun);

        if (!empty($lembaga)) {
            $this->db->where('pengajuan.lembaga', $lembaga);
        }

        if (!empty($bulan) && is_numeric($bulan)) {
            $this->db->where('pengajuan.bulan', (int)$bulan);
        }

        if ($jenis === 'disposisi') {
            $this->db->like('pengajuan.kode_pengajuan', 'DISP.');
        } elseif ($jenis === 'reguler') {
            $this->db->not_like('pengajuan.kode_pengajuan', 'DISP.');
        }

        if (!empty($status)) {
            switch ($status) {
                case 'menunggu_verval':
                    $this->db->where('pengajuan.verval', '0')->where('pengajuan.cair', '0');
                    break;
                case 'menunggu_persetujuan':
                    $this->db->where('pengajuan.verval', '1')->where('pengajuan.apr', '0')->where('pengajuan.cair', '0');
                    break;
                case 'siap_cair':
                    $this->db->where('pengajuan.verval', '1')->where('pengajuan.apr', '1')->where('pengajuan.cair', '0');
                    break;
                case 'cair':
                case 'sudah_cair':
                    $this->db->where('pengajuan.cair', '1');
                    break;
                case 'belum_cair':
                    $this->db->where('pengajuan.cair', '0');
                    break;
                case 'ditolak':
                    $this->db->group_start()
                        ->where('pengajuan.verval', '2')
                        ->or_where('pengajuan.apr', '2')
                    ->group_end();
                    break;
                case 'belum_spj':
                    $this->db->where('pengajuan.cair', '1')->where('pengajuan.spj', '0');
                    break;
                case 'spj_proses':
                    $this->db->where('pengajuan.cair', '1')->where('pengajuan.spj', '1');
                    break;
                case 'spj_selesai':
                    $this->db->where('pengajuan.cair', '1')->where('pengajuan.spj', '2');
                    break;
            }
        }

        if (!empty($search)) {
            $this->db->group_start()
                ->like('pengajuan.kode_pengajuan', $search)
                ->or_like('lembaga.nama', $search)
            ->group_end();
        }

        // Hitung total untuk pagination
        $total_rows = $this->db->count_all_results('', false);

        $this->db->select('pengajuan.*, lembaga.nama AS nama_lembaga, lembaga.hp AS hp_lembaga');
        $this->db->order_by('pengajuan.at', 'DESC');
        $this->db->limit($limit, $offset);
        $query_results = $this->db->get()->result();

        $items = [];
        foreach ($query_results as $row) {
            $kd = $row->kode_pengajuan;

            // Hitung nominal diajukan & nominal cair
            $sum_rsm = $this->db->select('SUM(nominal) as nom, SUM(nom_cair) as cair')
                ->where('kode_pengajuan', $kd)
                ->where('tahun', $tahun)
                ->get('real_sm')->row();

            $sum_rls = $this->db->select('SUM(nominal) as nom, SUM(nom_cair) as cair')
                ->where('kode_pengajuan', $kd)
                ->where('tahun', $tahun)
                ->get('realis')->row();

            $nom_diajukan = (float)($sum_rsm ? $sum_rsm->nom : 0) + (float)($sum_rls ? $sum_rls->nom : 0);
            $nom_cair = (float)($sum_rsm ? $sum_rsm->cair : 0) + (float)($sum_rls ? $sum_rls->cair : 0);

            $is_disp = stripos($kd, 'DISP.') !== false;

            // Tentukan status teks & badge
            if ($row->verval == 2 || $row->apr == 2) {
                $status_code = 'ditolak';
                $status_label = 'Ditolak';
            } elseif ($row->cair == 1) {
                $status_code = 'cair';
                $status_label = 'Sudah Cair';
            } elseif ($row->verval == 1 && $row->apr == 1) {
                $status_code = 'siap_cair';
                $status_label = 'Siap Dicairkan';
            } elseif ($row->verval == 1 && $row->apr == 0) {
                $status_code = 'menunggu_persetujuan';
                $status_label = 'Menunggu Persetujuan';
            } else {
                $status_code = 'menunggu_verval';
                $status_label = 'Menunggu Verifikasi';
            }

            $spj_status = 'belum_upload';
            if ($row->spj == 1) $spj_status = 'proses_verifikasi';
            if ($row->spj == 2) $spj_status = 'selesai';

            $items[] = [
                'kode_pengajuan' => $kd,
                'lembaga' => [
                    'kode' => $row->lembaga,
                    'nama' => $row->nama_lembaga,
                    'hp' => $row->hp_lembaga
                ],
                'periode' => [
                    'bulan' => (int)$row->bulan,
                    'nama_bulan' => $this->bulan_list[(int)$row->bulan] ?? '-',
                    'tahun' => $row->tahun
                ],
                'nominal' => [
                    'diajukan' => $nom_diajukan,
                    'diajukan_formatted' => 'Rp ' . number_format($nom_diajukan, 0, ',', '.'),
                    'cair' => $nom_cair,
                    'cair_formatted' => 'Rp ' . number_format($nom_cair, 0, ',', '.')
                ],
                'status' => [
                    'code' => $status_code,
                    'label' => $status_label,
                    'verval' => (int)$row->verval,
                    'apr' => (int)$row->apr,
                    'cair' => (int)$row->cair,
                    'spj' => (int)$row->spj,
                    'spj_label' => $spj_status
                ],
                'is_disposisi' => $is_disp,
                'created_at' => $row->at
            ];
        }

        $this->_jsonResponse([
            'status' => 'success',
            'tahun_ajaran' => $tahun,
            'pagination' => [
                'total_records' => $total_rows,
                'page' => $page,
                'limit' => $limit,
                'total_pages' => ceil($total_rows / $limit)
            ],
            'data' => $items
        ]);
    }

    /**
     * 3. GET /api/pengajuan/detail/{kode}
     * Detail item belanja, honor, status pagu, dan SPJ pengajuan
     */
    public function pengajuanDetail($kode = null)
    {
        $this->_authenticate();
        if (empty($kode)) {
            $kode = $this->input->get('kode', true);
        }

        if (empty($kode)) {
            $this->_jsonResponse(['status' => 'error', 'message' => 'Parameter kode_pengajuan wajib diisi'], 400);
            return;
        }

        $pengajuan = $this->db->where('kode_pengajuan', $kode)->get('pengajuan')->row();
        if (!$pengajuan) {
            $this->_jsonResponse(['status' => 'error', 'message' => 'Data pengajuan tidak ditemukan'], 404);
            return;
        }

        $tahun = $pengajuan->tahun;
        $lembaga_row = $this->db->where('kode', $pengajuan->lembaga)
            ->where('tahun', $tahun)
            ->get('lembaga')->row();

        // Rincian item belanja (ambil dari realis jika sudah cair, atau real_sm jika belum cair)
        if ($pengajuan->cair == 1) {
            $items_db = $this->db->where('kode_pengajuan', $kode)->get('realis')->result();
        } else {
            $items_db = $this->db->where('kode_pengajuan', $kode)->get('real_sm')->result();
        }

        $items = [];
        $total_nominal = 0;
        $total_cair = 0;
        foreach ($items_db as $item) {
            $nom = (float)($item->nominal ?? 0);
            $cair = (float)($item->nom_cair ?? 0);
            $total_nominal += $nom;
            $total_cair += $cair;

            $items[] = [
                'id' => $item->id_realis ?? $item->id_rsm ?? null,
                'kode_rab' => $item->kode_rab ?? null,
                'nama_item' => $item->nama ?? $item->uraian ?? '-',
                'jenis' => $item->jenis ?? '-',
                'nominal' => $nom,
                'nominal_formatted' => 'Rp ' . number_format($nom, 0, ',', '.'),
                'nom_cair' => $cair,
                'nom_cair_formatted' => 'Rp ' . number_format($cair, 0, ',', '.'),
                'keterangan' => $item->ket ?? null
            ];
        }

        // Lampiran / file honor
        $honor_files = $this->db->where('kode_pengajuan', $kode)->get('honor_file')->result();

        // Data SPJ jika ada
        $spj_data = $this->db->where('kode_pengajuan', $kode)->get('spj')->result();

        // Status pagu lembaga per jenis
        $pagu_summary = [];
        $jenis_list = ['A' => 'Belanja Barang', 'B' => 'Langganan & Jasa', 'C' => 'Belanja Kegiatan', 'D' => 'Umum'];
        foreach ($jenis_list as $jk => $jname) {
            $rab_sum = $this->db->select_sum('total')
                ->where('jenis', $jk)
                ->where('lembaga', $pengajuan->lembaga)
                ->where('tahun', $tahun)
                ->get('rab')->row();

            $real_sum = $this->db->select_sum('nominal')
                ->where('jenis', $jk)
                ->where('lembaga', $pengajuan->lembaga)
                ->where('tahun', $tahun)
                ->not_like('kode_pengajuan', 'DISP.')
                ->get('realis')->row();

            $pagu_j = $rab_sum ? (float)$rab_sum->total : 0;
            $pakai_j = $real_sum ? (float)$real_sum->nominal : 0;
            $sisa_j = max(0, $pagu_j - $pakai_j);

            $pagu_summary[$jk] = [
                'jenis' => $jk,
                'nama' => $jname,
                'pagu' => $pagu_j,
                'pagu_formatted' => 'Rp ' . number_format($pagu_j, 0, ',', '.'),
                'terpakai' => $pakai_j,
                'terpakai_formatted' => 'Rp ' . number_format($pakai_j, 0, ',', '.'),
                'sisa' => $sisa_j,
                'sisa_formatted' => 'Rp ' . number_format($sisa_j, 0, ',', '.')
            ];
        }

        $response = [
            'status' => 'success',
            'data' => [
                'kode_pengajuan' => $kode,
                'lembaga' => [
                    'kode' => $pengajuan->lembaga,
                    'nama' => $lembaga_row ? $lembaga_row->nama : '-',
                    'hp' => $lembaga_row ? $lembaga_row->hp : '-'
                ],
                'periode' => [
                    'bulan' => (int)$pengajuan->bulan,
                    'nama_bulan' => $this->bulan_list[(int)$pengajuan->bulan] ?? '-',
                    'tahun' => $pengajuan->tahun
                ],
                'status' => [
                    'verval' => (int)$pengajuan->verval,
                    'apr' => (int)$pengajuan->apr,
                    'cair' => (int)$pengajuan->cair,
                    'spj' => (int)$pengajuan->spj
                ],
                'is_disposisi' => stripos($kode, 'DISP.') !== false,
                'total_nominal' => $total_nominal,
                'total_nominal_formatted' => 'Rp ' . number_format($total_nominal, 0, ',', '.'),
                'total_cair' => $total_cair,
                'total_cair_formatted' => 'Rp ' . number_format($total_cair, 0, ',', '.'),
                'rincian_belanja' => $items,
                'lampiran_honor' => $honor_files,
                'spj_files' => $spj_data,
                'monitoring_pagu_lembaga' => $pagu_summary,
                'created_at' => $pengajuan->at
            ]
        ];

        $this->_jsonResponse($response);
    }

    /**
     * 4. GET /api/pengajuan/by-lembaga
     * Monitoring rekap serapan & pengajuan per lembaga / unit kerja
     */
    public function pengajuanByLembaga()
    {
        $this->_authenticate();
        $tahun = $this->_getTahun();

        $lembaga_list = $this->db->where('tahun', $tahun)->order_by('nama', 'ASC')->get('lembaga')->result();
        $result = [];

        foreach ($lembaga_list as $l) {
            $kdl = $l->kode;

            // Total pagu RAB
            $pagu_row = $this->db->select_sum('total')->where('lembaga', $kdl)->where('tahun', $tahun)->get('rab')->row();
            $pagu = $pagu_row ? (float)$pagu_row->total : 0;

            // Total pengajuan count
            $pj_rows = $this->db->where('lembaga', $kdl)->where('tahun', $tahun)->get('pengajuan')->result();
            $pj_count = count($pj_rows);

            // Total nominal diajukan & dicairkan
            $rsm_sum = $this->db->select('SUM(nominal) as nom, SUM(nom_cair) as cair')
                ->where('lembaga', $kdl)->where('tahun', $tahun)->get('real_sm')->row();
            $rls_sum = $this->db->select('SUM(nominal) as nom, SUM(nom_cair) as cair')
                ->where('lembaga', $kdl)->where('tahun', $tahun)->get('realis')->row();

            $nom_diajukan = (float)($rsm_sum ? $rsm_sum->nom : 0) + (float)($rls_sum ? $rls_sum->nom : 0);
            $nom_cair = (float)($rsm_sum ? $rsm_sum->cair : 0) + (float)($rls_sum ? $rls_sum->cair : 0);
            $sisa_pagu = max(0, $pagu - $nom_cair);
            $persen_serapan = $pagu > 0 ? round(($nom_cair / $pagu) * 100, 2) : 0;

            // Tunggakan SPJ
            $belum_spj = 0;
            foreach ($pj_rows as $p) {
                if ($p->cair == 1 && $p->spj == 0) {
                    $belum_spj++;
                }
            }

            $result[] = [
                'kode_lembaga' => $kdl,
                'nama_lembaga' => $l->nama,
                'hp' => $l->hp,
                'total_pengajuan' => $pj_count,
                'pagu_rab' => $pagu,
                'pagu_rab_formatted' => 'Rp ' . number_format($pagu, 0, ',', '.'),
                'nominal_diajukan' => $nom_diajukan,
                'nominal_diajukan_formatted' => 'Rp ' . number_format($nom_diajukan, 0, ',', '.'),
                'nominal_cair' => $nom_cair,
                'nominal_cair_formatted' => 'Rp ' . number_format($nom_cair, 0, ',', '.'),
                'sisa_pagu' => $sisa_pagu,
                'sisa_pagu_formatted' => 'Rp ' . number_format($sisa_pagu, 0, ',', '.'),
                'persentase_serapan' => $persen_serapan,
                'tunggakan_spj_count' => $belum_spj
            ];
        }

        $this->_jsonResponse([
            'status' => 'success',
            'tahun_ajaran' => $tahun,
            'total_lembaga' => count($result),
            'data' => $result
        ]);
    }

    /**
     * 5. GET /api/pengajuan/monitoring-spj
     * Monitoring kepatuhan SPJ untuk pengajuan yang sudah cair
     */
    public function pengajuanMonitoringSpj()
    {
        $this->_authenticate();
        $tahun = $this->_getTahun();
        $filter_status = $this->input->get('status_spj', true); // '0', '1', '2', atau 'all'

        $this->db->from('pengajuan');
        $this->db->join('lembaga', 'pengajuan.lembaga = lembaga.kode AND lembaga.tahun = pengajuan.tahun', 'left');
        $this->db->where('pengajuan.tahun', $tahun);
        $this->db->where('pengajuan.cair', '1');

        if ($filter_status !== null && $filter_status !== 'all' && $filter_status !== '') {
            $this->db->where('pengajuan.spj', (int)$filter_status);
        }

        $this->db->select('pengajuan.*, lembaga.nama AS nama_lembaga, lembaga.hp AS hp_lembaga');
        $this->db->order_by('pengajuan.at', 'DESC');
        $list = $this->db->get()->result();

        $items = [];
        $now = time();

        foreach ($list as $row) {
            $kd = $row->kode_pengajuan;

            // Hitung nominal cair
            $sum_cair = $this->db->select('SUM(nom_cair) as cair')->where('kode_pengajuan', $kd)->where('tahun', $tahun)->get('realis')->row();
            $nom_cair = $sum_cair ? (float)$sum_cair->cair : 0;
            if ($nom_cair <= 0) {
                $sum_rsm = $this->db->select('SUM(nom_cair) as cair')->where('kode_pengajuan', $kd)->where('tahun', $tahun)->get('real_sm')->row();
                $nom_cair = $sum_rsm ? (float)$sum_rsm->cair : 0;
            }

            // Durasi hari sejak pengajuan dibuat / cair
            $tgl_pengajuan_ts = !empty($row->at) ? strtotime($row->at) : $now;
            $hari_berlalu = floor(($now - $tgl_pengajuan_ts) / (60 * 60 * 24));
            $is_overdue = ($row->spj == 0 && $hari_berlalu > 14);

            $spj_status_label = 'Belum Upload SPJ';
            if ($row->spj == 1) $spj_status_label = 'Proses Verifikasi SPJ';
            if ($row->spj == 2) $spj_status_label = 'SPJ Selesai';

            // Ambil info detail SPJ jika ada
            $spj_files = $this->db->where('kode_pengajuan', $kd)->get('spj')->result();

            $items[] = [
                'kode_pengajuan' => $kd,
                'lembaga' => [
                    'kode' => $row->lembaga,
                    'nama' => $row->nama_lembaga,
                    'hp' => $row->hp_lembaga
                ],
                'nominal_cair' => $nom_cair,
                'nominal_cair_formatted' => 'Rp ' . number_format($nom_cair, 0, ',', '.'),
                'status_spj' => (int)$row->spj,
                'status_spj_label' => $spj_status_label,
                'hari_sejak_pengajuan' => $hari_berlalu,
                'is_overdue' => $is_overdue,
                'warning_badge' => $is_overdue ? 'Terlambat (> 14 hari)' : 'Normal',
                'tgl_pengajuan' => $row->at,
                'spj_files' => $spj_files
            ];
        }

        $this->_jsonResponse([
            'status' => 'success',
            'tahun_ajaran' => $tahun,
            'total_records' => count($items),
            'data' => $items
        ]);
    }

    /**
     * 6. GET /api/pengajuan/chart-trend
     * Data grafik tren pengajuan bulanan (Januari - Desember)
     */
    public function pengajuanChartTrend()
    {
        $this->_authenticate();
        $tahun = $this->_getTahun();

        $monthly_data = [];
        for ($m = 1; $m <= 12; $m++) {
            $p_count = $this->db->where('tahun', $tahun)->where('bulan', $m)->count_all_results('pengajuan');

            // Hitung sum nominal diajukan & cair pada bulan ini
            $this->db->select('SUM(real_sm.nominal) as nom, SUM(real_sm.nom_cair) as cair');
            $this->db->from('real_sm');
            $this->db->join('pengajuan', 'real_sm.kode_pengajuan = pengajuan.kode_pengajuan AND pengajuan.tahun = real_sm.tahun');
            $this->db->where('pengajuan.tahun', $tahun);
            $this->db->where('pengajuan.bulan', $m);
            $q1 = $this->db->get()->row();

            $this->db->select('SUM(realis.nominal) as nom, SUM(realis.nom_cair) as cair');
            $this->db->from('realis');
            $this->db->join('pengajuan', 'realis.kode_pengajuan = pengajuan.kode_pengajuan AND pengajuan.tahun = realis.tahun');
            $this->db->where('pengajuan.tahun', $tahun);
            $this->db->where('pengajuan.bulan', $m);
            $q2 = $this->db->get()->row();

            $nom_diajukan = (float)($q1 ? $q1->nom : 0) + (float)($q2 ? $q2->nom : 0);
            $nom_cair = (float)($q1 ? $q1->cair : 0) + (float)($q2 ? $q2->cair : 0);

            $monthly_data[] = [
                'bulan' => $m,
                'nama_bulan' => $this->bulan_list[$m],
                'jumlah_pengajuan' => $p_count,
                'nominal_diajukan' => $nom_diajukan,
                'nominal_diajukan_formatted' => 'Rp ' . number_format($nom_diajukan, 0, ',', '.'),
                'nominal_cair' => $nom_cair,
                'nominal_cair_formatted' => 'Rp ' . number_format($nom_cair, 0, ',', '.')
            ];
        }

        $this->_jsonResponse([
            'status' => 'success',
            'tahun_ajaran' => $tahun,
            'data' => $monthly_data
        ]);
    }
}
