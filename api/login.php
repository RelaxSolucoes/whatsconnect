<?php
/**
 * WhatsConnect - Login API
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

define('WHATSCONNECT', true);
require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || empty($input['email']) || empty($input['password'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email e senha são obrigatórios']);
    exit;
}

$email = trim($input['email']);
$password = $input['password'];

// Check if admin login
if ($email === ADMIN_EMAIL) {
    // Verify admin password
    if (!empty(ADMIN_PASSWORD_HASH) && password_verify($password, ADMIN_PASSWORD_HASH)) {
        // Generate session token
        $token = bin2hex(random_bytes(32));
        
        // Start session
        session_start();
        $_SESSION['admin'] = true;
        $_SESSION['token'] = $token;
        
        echo json_encode([
            'success' => true,
            'message' => 'Login realizado com sucesso',
            'data' => [
                'is_admin' => true,
                'token' => $token
            ]
        ]);
        exit;
    } else {
        // For first time setup, check if password is raw (no hash set yet)
        if (empty(ADMIN_PASSWORD_HASH) && $password === 'admin123') {
            session_start();
            $_SESSION['admin'] = true;
            $_SESSION['first_login'] = true;
            
            echo json_encode([
                'success' => true,
                'message' => 'Primeiro acesso - configure sua senha',
                'data' => [
                    'is_admin' => true,
                    'first_login' => true
                ]
            ]);
            exit;
        }
    }
}

// Regular user - redirect to Chatwoot
echo json_encode([
    'success' => true,
    'message' => 'Redirecionando para o Chatwoot',
    'data' => [
        'is_admin' => false,
        'chatwoot_url' => CHATWOOT_URL
    ]
]);
