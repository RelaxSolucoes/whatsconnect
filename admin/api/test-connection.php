<?php
/**
 * Test Connection API
 */
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Não autorizado']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || empty($input['type'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    exit;
}

if ($input['type'] === 'chatwoot') {
    $url = rtrim($input['url'], '/') . '/platform/api/v1/accounts';
    $headers = [
        'Content-Type: application/json',
        'api_access_token: ' . $input['token']
    ];
} elseif ($input['type'] === 'evolution') {
    $url = rtrim($input['url'], '/') . '/instance/fetchInstances';
    $headers = [
        'Content-Type: application/json',
        'apikey: ' . $input['apikey']
    ];
} else {
    echo json_encode(['success' => false, 'message' => 'Tipo inválido']);
    exit;
}

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_TIMEOUT => 15,
    CURLOPT_SSL_VERIFYPEER => false
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo json_encode(['success' => false, 'message' => 'Erro de conexão: ' . $error]);
    exit;
}

if ($httpCode >= 200 && $httpCode < 300) {
    echo json_encode(['success' => true, 'message' => 'Conexão bem sucedida']);
} else {
    echo json_encode(['success' => false, 'message' => "Erro HTTP $httpCode"]);
}
