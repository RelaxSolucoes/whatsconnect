<?php
/**
 * Save Message Template API
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

if (!$input || !isset($input['message'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Mensagem inválida']);
    exit;
}

$configFile = __DIR__ . '/../../config/config.php';
$configContent = file_get_contents($configFile);

if (!$configContent) {
    echo json_encode(['success' => false, 'message' => 'Erro ao ler configuração']);
    exit;
}

// Escape the message for PHP string
$message = str_replace("'", "\\'", $input['message']);
$message = str_replace("\n", "\\n", $message);

// Update the template
$pattern = "/define\('WELCOME_MESSAGE_TEMPLATE',\s*\n'[^']*'\)/s";
$replacement = "define('WELCOME_MESSAGE_TEMPLATE', \n'$message')";

// Try to match multiline first
if (!preg_match($pattern, $configContent)) {
    // Try simpler pattern
    $pattern = "/define\('WELCOME_MESSAGE_TEMPLATE',[^;]+;/s";
    $replacement = "define('WELCOME_MESSAGE_TEMPLATE', \n'$message');";
}

$newContent = preg_replace($pattern, $replacement, $configContent, 1);

if ($newContent && file_put_contents($configFile, $newContent)) {
    echo json_encode(['success' => true, 'message' => 'Template salvo']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar template']);
}
