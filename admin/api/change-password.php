<?php
/**
 * Change Admin Password API
 */
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Não autorizado']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || empty($input['currentPassword']) || empty($input['newPassword'])) {
    echo json_encode(['success' => false, 'message' => 'Preencha todos os campos']);
    exit;
}

$configFile = __DIR__ . '/../../config/config.php';

if (!file_exists($configFile)) {
    echo json_encode(['success' => false, 'message' => 'Arquivo de configuração não encontrado']);
    exit;
}

define('WHATSCONNECT', true);
require_once $configFile;

// Verify current password
if (!password_verify($input['currentPassword'], ADMIN_PASSWORD_HASH)) {
    echo json_encode(['success' => false, 'message' => 'Senha atual incorreta']);
    exit;
}

// Validate new password
$newPassword = $input['newPassword'];
if (strlen($newPassword) < 6) {
    echo json_encode(['success' => false, 'message' => 'A nova senha deve ter pelo menos 6 caracteres']);
    exit;
}

// Generate new hash
$newHash = password_hash($newPassword, PASSWORD_DEFAULT);

// Read config file
$content = file_get_contents($configFile);

// Replace password hash
$content = preg_replace(
    "/define\('ADMIN_PASSWORD_HASH',\s*'[^']*'\)/",
    "define('ADMIN_PASSWORD_HASH', '$newHash')",
    $content
);

// Write updated config
if (file_put_contents($configFile, $content)) {
    echo json_encode(['success' => true, 'message' => 'Senha alterada com sucesso!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar. Verifique as permissões do arquivo.']);
}
