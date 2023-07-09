<?php

if ($_POST['apiKey'] != '') {

    $apiKey = $_POST['apiKey'];
    $phone = $_POST['phone'];
    $urlFile = $_POST['url_file'];
    $asDocument = $_POST['as_document'];
    $caption = $_POST['caption'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://191.101.3.115:3000/api/sendMediaFromUrl");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'apiKey=' . $apiKey . '&phone=' . $phone . '&url_file=' . $urlFile . '&as_document=' . $asDocument . '&caption=' . $caption);
    $hasil = curl_exec($ch);

    echo $hasil;
    curl_close($ch);
}
