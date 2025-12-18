<?php
/**
 * Send Test Message API
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
require_once __DIR__ . '/../../api/classes/WhatsAppSender.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || empty($input['number'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Número inválido']);
    exit;
}

if (empty(EVOLUTION_URL) || empty(EVOLUTION_APIKEY) || empty(EVOLUTION_INSTANCE)) {
    echo json_encode(['success' => false, 'message' => 'Credenciais Evolution não configuradas']);
    exit;
}

try {
    $sender = new WhatsAppSender();
    
    // Send test message with sample data
    $testMessage = str_replace(
        ['{nome}', '{empresa}', '{email}', '{senha}', '{url}'],
        ['Usuário Teste', 'Empresa Teste', 'teste@exemplo.com', 'Teste@123', CHATWOOT_URL ?: 'https://chat.exemplo.com'],
        WELCOME_MESSAGE_TEMPLATE
    );
    
    $result = $sender->sendCustomMessage($input['number'], $testMessage);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Mensagem enviada']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Falha ao enviar mensagem']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
