<?php

function rupiah($rp)
{
    if ($rp != null) {
        return 'Rp. ' . number_format($rp, 0, ',', '.');
    } else {
        return 'Rp. ' . number_format(0, 0, ',', '.');
    }
}

function bulan($bulan)
{
    switch ($bulan) {
        case 0:
            $bulan = "";
            break;
        case 1:
            $bulan = "Januari";
            break;
        case 2:
            $bulan = "Februari";
            break;
        case 3:
            $bulan = "Maret";
            break;
        case 4:
            $bulan = "April";
            break;
        case 5:
            $bulan = "Mei";
            break;
        case 6:
            $bulan = "Juni";
            break;
        case 7:
            $bulan = "Juli";
            break;
        case 8:
            $bulan = "Agustus";
            break;
        case 9:
            $bulan = "September";
            break;
        case 10:
            $bulan = "Oktober";
            break;
        case 11:
            $bulan = "November";
            break;
        case 12:
            $bulan = "Desember";
            break;
        default:
            $bulan = Date('F');
            break;
    }
    return $bulan;
}

function tanggalIndo($tanggal)
{
    $a = explode('-', $tanggal);
    $tanggal = $a['2'] . " " . bulan($a['1']) . " " . $a['0'];
    return $tanggal;
}

function get_wa_config()
{
    $CI = &get_instance();
    // 1. Ambil apiKey (nama = 'apiKey' atau fallback nama = 'Bendahara')
    $apiKeyRow = $CI->db->where('nama', 'apiKey')->get('api')->row();
    if (!$apiKeyRow) {
        $apiKeyRow = $CI->db->where('nama', 'Bendahara')->get('api')->row();
    }
    $apiKey = $apiKeyRow ? $apiKeyRow->nama_key : '';

    // 2. Ambil sessionId (nama = 'sessionId' atau 'session_id')
    $sessionRow = $CI->db->where('nama', 'sessionId')->get('api')->row();
    if (!$sessionRow) {
        $sessionRow = $CI->db->where('nama', 'session_id')->get('api')->row();
    }
    $sessionId = ($sessionRow && !empty($sessionRow->nama_key)) ? $sessionRow->nama_key : 'default';

    return [
        'apiKey' => $apiKey,
        'sessionId' => $sessionId
    ];
}

function kirim_person($key = null, $no_hp = '', $pesan = '', $sessionId = null)
{
    $config = get_wa_config();
    if (empty($key)) {
        $key = $config['apiKey'];
    }
    if (empty($sessionId)) {
        $sessionId = $config['sessionId'];
    }

    $payload = json_encode([
        'apiKey' => (string)$key,
        'number' => (string)$no_hp,
        'message' => (string)$pesan,
        'sessionId' => (string)$sessionId
    ]);

    $ch = curl_init('https://wadwk.ppdwk.site/send-personal');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

function kirim_group($key = null, $id_group = '', $pesan = '', $sessionId = null)
{
    $config = get_wa_config();
    if (empty($key)) {
        $key = $config['apiKey'];
    }
    if (empty($sessionId)) {
        $sessionId = $config['sessionId'];
    }

    $payload = json_encode([
        'apiKey' => (string)$key,
        'groupId' => (string)$id_group,
        'message' => (string)$pesan,
        'sessionId' => (string)$sessionId
    ]);

    $ch = curl_init('https://wadwk.ppdwk.site/send-group');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

function kirim_tmp($key, $no_hp, $pesan, $tmp, $link_logo)
{
    $curl2 = curl_init();
    curl_setopt_array(
        $curl2,
        array(
            CURLOPT_URL => 'http://103.49.238.29:3000/api/sendTemplateMessage',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'apiKey=' . $key . '&phone=' . $no_hp . '&body_message=' . $pesan . '&footer=&template=' . json_encode($tmp) . '&url_file=' . $link_logo,
        )
    );
    $response = curl_exec($curl2);
    curl_close($curl2);
}

function kirim_nota($key, $no_hp, $url_file, $as_document, $caption)
{
    $curl2 = curl_init();
    curl_setopt_array(
        $curl2,
        array(
            CURLOPT_URL => 'http://103.49.238.29:3000/api/sendMediaFromUrl',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'apiKey=' . $key . '&phone=' . $no_hp . '&url_file=' . $url_file . '&as_document=' . $as_document . '&caption=' . $caption,
        )
    );
    $response = curl_exec($curl2);
    curl_close($curl2);
}

function cekStatusWA($sessionId = null)
{
    if (empty($sessionId) || strlen($sessionId) > 30) {
        $config = get_wa_config();
        $sessionId = $config['sessionId'];
    }

    $ch = curl_init('https://wadwk.ppdwk.site/sessions/' . urlencode($sessionId) . '/status');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        return [
            'status' => false,
            'message' => $error,
            'data' => [
                'connected' => false
            ]
        ];
    }

    curl_close($ch);
    $decoded = json_decode($response, true);
    return is_array($decoded) ? $decoded : ['status' => false, 'data' => ['connected' => false]];
}

function gel($gel)
{
    $nm = array(0, 70000, 120000, 170000);
    return $nm[$gel];
}

function rmRp($string)
{
    return preg_replace("/[^0-9]/", "", $string);
}

function check($arr)
{
    $ok = "
    <i class='text-success'><svg xmlns='http://www.w3.org/2000/svg'
                                            class='icon icon-tabler icon-tabler-check' width='24' height='24'
                                            viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' fill='none'
                                            stroke-linecap='round' stroke-linejoin='round'>
                                            <path stroke='none' d='M0 0h24v24H0z' fill='none'></path>
                                            <path d='M5 12l5 5l10 -10'></path>
                                        </svg>
                                    </i>
    ";
    $no = "
    <i class='text-danger'><svg xmlns='http://www.w3.org/2000/svg' class='icon icon-tabler icon-tabler-square-x' width='24' height='24' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' fill='none' stroke-linecap='round' stroke-linejoin='round'>
   <path stroke='none' d='M0 0h24v24H0z' fill='none'></path>
   <rect x='4' y='4' width='16' height='16' rx='2'></rect>
   <path d='M10 10l4 4m0 -4l-4 4'></path>
</svg>
                                    </i>
    ";

    $isi = array($no, $ok);
    return $isi[$arr];
}

function random($panjang)
{
    $karakter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $string = '';
    for ($i = 0; $i < $panjang; $i++) {
        $pos = rand(0, strlen($karakter) - 1);
        $string .= $karakter[$pos];
    }
    return $string;
}

function gabung2Kolom($kolom1, $kolom2, $lebarKolom1, $lebarKolom2)
{
    // Mengatur lebar setiap kolom (dalam satuan karakter)
    $kolom1 = str_pad($kolom1, $lebarKolom1); // Menyusun teks kolom 1 dengan padding
    $kolom2 = str_pad($kolom2, $lebarKolom2); // Menyusun teks kolom 2 dengan padding

    // Menggabungkan kedua kolom menjadi satu baris
    return $kolom1 . $kolom2;
}
