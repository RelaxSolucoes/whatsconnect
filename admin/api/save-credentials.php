<?php
/**
 * Save Credentials API
 */
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Não autorizado']);
    exit;
}

define('WHATSCONNECT', true);
require_once __DIR__ . '/../../config/config.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || empty($input['type'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    exit;
}

$configFile = __DIR__ . '/../../config/config.php';
$configContent = file_get_contents($configFile);

if (!$configContent) {
    echo json_encode(['success' => false, 'message' => 'Erro ao ler configuração']);
    exit;
}

if ($input['type'] === 'chatwoot') {
    $updates = [
        'CHATWOOT_URL' => "'" . addslashes($input['url'] ?? '') . "'",
        'CHATWOOT_TOKEN' => "'" . addslashes($input['token'] ?? '') . "'"
    ];
} elseif ($input['type'] === 'evolution') {
    $updates = [
        'EVOLUTION_URL' => "'" . addslashes($input['url'] ?? '') . "'",
        'EVOLUTION_APIKEY' => "'" . addslashes($input['apikey'] ?? '') . "'",
        'EVOLUTION_INSTANCE' => "'" . addslashes($input['instance'] ?? '') . "'"
    ];
} else {
    echo json_encode(['success' => false, 'message' => 'Tipo inválido']);
    exit;
}

foreach ($updates as $key => $value) {
    $pattern = "/define\('$key',\s*'[^']*'\)/";
    $replacement = "define('$key', $value)";
    $configContent = preg_replace($pattern, $replacement, $configContent);
}

if (file_put_contents($configFile, $configContent)) {
    echo json_encode(['success' => true, 'message' => 'Credenciais salvas']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar']);
}
