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

function kirim_person($key, $no_hp, $pesan)
{
    $curl2 = curl_init();
    curl_setopt_array(
        $curl2,
        array(
            CURLOPT_URL => 'http://31.97.179.141:3000/api/sendMessage',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'apiKey=' . $key . '&phone=' . $no_hp . '&message=' . $pesan,
        )
    );
    $response = curl_exec($curl2);
    curl_close($curl2);
}

function kirim_group($key, $id_group, $pesan)
{
    $curl2 = curl_init();
    curl_setopt_array(
        $curl2,
        array(
            CURLOPT_URL => 'http://31.97.179.141:3000/api/sendMessageGroup',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'apiKey=' . $key . '&id_group=' . $id_group . '&message=' . $pesan,
        )
    );
    $response = curl_exec($curl2);
    curl_close($curl2);
}

function kirim_tmp($key, $no_hp, $pesan, $tmp, $link_logo)
{
    $curl2 = curl_init();
    curl_setopt_array(
        $curl2,
        array(
            CURLOPT_URL => 'http://31.97.179.141:3000/api/sendTemplateMessage',
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
            CURLOPT_URL => 'http://31.97.179.141:3000/api/sendMediaFromUrl',
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
