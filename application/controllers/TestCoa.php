
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Testcoa extends CI_Controller
{

    private $api_key;

    public function __construct()
    {
        parent::__construct();
        $this->api_key = $this->db->where('name', 'google_key')->get('settings')->row()->val;
        $this->model_ai = $this->db->where('name', 'model_ai')->get('settings')->row()->val;
    }

    public function index()
    {
        $this->load->view('test_coa');
    }

    public function get_parent_coa()
    {
        $data = $this->db
            ->where('tahun', '2026/2027')
            ->where('parrent IS NULL', null, false)
            ->get('coa')
            ->result_array();

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function get_child_coa()
    {
        $parent = $this->input->get('parent');

        $data = $this->db
            ->where('tahun', '2026/2027')
            ->where('parrent', $parent) // 🔥 ini yang benar
            ->order_by('kode')
            ->get('coa')
            ->result_array(); // 🔥 pakai array

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function simpan()
    {
        // 1. Ambil data dari input post (View)
        $barang = $this->input->post('kebutuhan');
        $coa_user = $this->input->post('kode');

        $data_coa = $this->db->where('tahun', '2026/2027')->get('coa')->result();

        // 2. Definisi Aturan COA (System Instruction)
        $context = "Anda adalah auditor keuangan. Referensi COA kami:";

        foreach ($data_coa as $row) {
            $context .= "\n" . $row->kode . " : " . $row->nama;
        }

        $prompt = "User menginput barang: '$barang' dengan kode COA: '$coa_user'. 
                   Apakah ini sesuai? Jika tidak sesuai, berikan alasan dan saran COA yang tersedia dan paling mendekati menurut kamu, dan jika kurang tepat berikan saran COA baru. 
                   Kembalikan dalam JSON: 
                   {'status': 'Sesuai'|'Tidak Sesuai', 'alasan': '...', 'saran_coa': '...'}";


        // 3. Panggil fungsi untuk hit API Gemini
        $response_ai = $this->call_gemini($context, $prompt);
        $result = json_decode($response_ai, TRUE);

        // echo json_encode([
        //     "status" => "Tidak Sesuai",
        //     "alasan" => $response_ai,
        //     "saran_coa" => ''
        // ]);
        // exit;

        // 4. Logika Bisnis
        if ($result['status'] === "Tidak Sesuai") {
            echo json_encode([
                "status" => "Tidak Sesuai",
                "alasan" => $result['alasan'],
                "saran_coa" => $result['saran_coa']
            ]);
        } else {
            echo json_encode([
                "status" => "Sesuai",
                "alasan" => "Data COA sesuai!"
            ]);
        }
    }

    private function call_gemini($system_instruction, $prompt)
    {
        // 1. Gunakan v1beta dan model gemini-pro (paling stabil)
        $url = "https://generativelanguage.googleapis.com/v1beta/models/" . $this->model_ai . ":generateContent?key=" . $this->api_key;

        $data = [
            "contents" => [
                [
                    "role" => "user",
                    "parts" => [
                        ["text" => $system_instruction . "\n\n" . $prompt]
                    ]
                ]
            ],
            "generationConfig" => [
                "responseMimeType" => "application/json",
                "temperature" => 0.1
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $response_data = json_decode($response, true);

        if ($httpCode !== 200) {
            return json_encode([
                "status" => "Error",
                "alasan" => "API Error: " . ($response_data['error']['message'] ?? 'Unknown Error'),
                "saran_coa" => "-"
            ]);
        }

        $raw_text = $response_data['candidates'][0]['content']['parts'][0]['text'];

        // 2. Fungsi pembersih jika AI memberikan format ```json ...
        $clean_json = preg_replace('/^```json\s*|```\s*$/', '', trim($raw_text));

        return $clean_json;
    }
}
