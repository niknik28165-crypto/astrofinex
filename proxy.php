<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Пробуем несколько источников
$sources = [
    'https://api.exchangerate.host/convert?from=XAU&to=USD',
    'https://www.gold-api.com/price/XAU'
];

foreach ($sources as $url) {
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
        
        // Exchange-Rate format
        if (isset($data['result']) && $data['result'] > 0) {
            echo json_encode(['price' => $data['result'], 'source' => 'Exchange-Rate']);
            exit;
        }
        // Gold-API format
        if (isset($data['price']) && $data['price'] > 0) {
            echo json_encode(['price' => $data['price'], 'source' => 'Gold-API']);
            exit;
        }
    }
}

// Запасное значение
echo json_encode(['price' => 4080, 'source' => 'local']);
?>