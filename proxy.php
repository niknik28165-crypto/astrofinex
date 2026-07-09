<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// 1. Пробуем Exchange-Rate
$url = 'https://api.exchangerate.host/convert?from=XAU&to=USD';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200 && $response) {
    $data = json_decode($response, true);
    if (isset($data['result']) && $data['result'] > 0) {
        echo json_encode(['price' => round($data['result']), 'source' => 'Exchange-Rate']);
        exit;
    }
}

// 2. Если не сработало — пробуем Gold-API
$url2 = 'https://www.gold-api.com/price/XAU';
$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_URL, $url2);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_TIMEOUT, 5);
curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
$response2 = curl_exec($ch2);
$httpCode2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
curl_close($ch2);

if ($httpCode2 === 200 && $response2) {
    $data2 = json_decode($response2, true);
    if (isset($data2['price']) && $data2['price'] > 0) {
        echo json_encode(['price' => round($data2['price']), 'source' => 'Gold-API']);
        exit;
    }
}

// 3. Запасное значение
echo json_encode(['price' => 4080, 'source' => 'local']);
?>
?>
