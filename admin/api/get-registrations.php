<?php
/**
 * Get Registrations API
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
require_once __DIR__ . '/../../api/classes/Database.php';

if (!USE_DATABASE) {
    echo json_encode(['success' => false, 'message' => 'Banco de dados desativado', 'data' => []]);
    exit;
}

try {
    $db = Database::getInstance();
    $registrations = $db->getAllRegistrations();
    
    echo json_encode([
        'success' => true,
        'data' => $registrations,
        'total' => count($registrations)
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage(), 'data' => []]);
}
