<?php
/**
 * Save Settings API
 */
session_start();
header('Content-Type: application/json');

// Check authentication
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Não autorizado']);
    exit;
}

define('WHATSCONNECT', true);
require_once __DIR__ . '/../../config/config.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    exit;
}

// Read current config
$configFile = __DIR__ . '/../../config/config.php';
$configContent = file_get_contents($configFile);

if (!$configContent) {
    echo json_encode(['success' => false, 'message' => 'Erro ao ler configuração']);
    exit;
}

// Update settings
$updates = [
    'USE_EXTERNAL_WEBHOOK' => isset($input['use_webhook']) && $input['use_webhook'] ? 'true' : 'false',
    'USE_DATABASE' => isset($input['use_database']) && $input['use_database'] ? 'true' : 'false',
    'SEND_WELCOME_WHATSAPP' => isset($input['send_welcome']) && $input['send_welcome'] ? 'true' : 'false'
];

if (!empty($input['webhook_url'])) {
    $updates['WEBHOOK_URL'] = "'" . addslashes($input['webhook_url']) . "'";
}

foreach ($updates as $key => $value) {
    $pattern = "/define\('$key',\s*[^)]+\)/";
    $replacement = "define('$key', $value)";
    $configContent = preg_replace($pattern, $replacement, $configContent);
}

// Save config
if (file_put_contents($configFile, $configContent)) {
    echo json_encode(['success' => true, 'message' => 'Configurações salvas']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar configuração']);
}
