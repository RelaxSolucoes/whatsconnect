<?php
/**
 * WhatsConnect - Registration API
 * Handles user registration and Chatwoot/Evolution integration
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

define('WHATSCONNECT', true);
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/classes/ChatwootAPI.php';
require_once __DIR__ . '/classes/EvolutionAPI.php';
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/WhatsAppSender.php';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
    exit;
}

// Validate required fields
$requiredFields = ['fullName', 'whatsapp', 'email', 'companyName', 'password'];
foreach ($requiredFields as $field) {
    if (empty($input[$field])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => "Campo obrigatório: $field"]);
        exit;
    }
}

// Validate password requirements
$password = $input['password'];
if (!preg_match('/[A-Z]/', $password) || 
    !preg_match('/[0-9]/', $password) || 
    !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password) ||
    strlen($password) < 8) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'A senha não atende aos requisitos mínimos']);
    exit;
}

// Validate email
if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email inválido']);
    exit;
}

// Sanitize company name for Evolution API (no spaces, accents, ç)
$companyNameClean = sanitizeInstanceName($input['companyName']);

// Check if using external webhook (N8N)
if (USE_EXTERNAL_WEBHOOK && !empty(WEBHOOK_URL)) {
    // Send to N8N webhook and wait for response
    $webhookResult = sendToWebhook($input, $companyNameClean);
    
    if ($webhookResult['success']) {
        // Save to database if enabled (after N8N processing)
        if (USE_DATABASE) {
            $db = Database::getInstance();
            $db->saveRegistration([
                'full_name' => $input['fullName'],
                'email' => $input['email'],
                'whatsapp' => $input['whatsapp'],
                'company_name' => $input['companyName'],
                'chatwoot_account_id' => $webhookResult['data']['account_id'] ?? null,
                'chatwoot_user_id' => $webhookResult['data']['user_id'] ?? null,
                'evolution_instance' => $companyNameClean
            ]);
        }
        
        // Send WhatsApp welcome message if enabled (after N8N processing)
        $whatsappSent = false;
        if (SEND_WELCOME_WHATSAPP) {
            $sender = new WhatsAppSender();
            $whatsappSent = $sender->sendWelcomeMessage(
                $input['whatsapp'],
                $input['fullName'],
                $input['companyName'],
                $input['email'],
                $webhookResult['data']['password'] ?? $input['password'],
                $webhookResult['data']['chatwoot_url'] ?? CHATWOOT_URL
            );
            $webhookResult['data']['whatsapp_sent'] = $whatsappSent;
        }
    }
    
    echo json_encode($webhookResult);
    exit;
}

// Process locally with PHP
try {
    $chatwoot = new ChatwootAPI(CHATWOOT_URL, CHATWOOT_TOKEN);
    $evolution = new EvolutionAPI(EVOLUTION_URL, EVOLUTION_APIKEY);
    
    // Step 1: Create company in Chatwoot
    $company = $chatwoot->createAccount($input['companyName']);
    if (!$company || !isset($company['id'])) {
        throw new Exception('Erro ao criar empresa no Chatwoot');
    }
    $accountId = $company['id'];
    
    // Step 2: Create user in Chatwoot
    $user = $chatwoot->createUser($input['fullName'], $input['email'], $input['password']);
    if (!$user || !isset($user['id'])) {
        throw new Exception('Erro ao criar usuário no Chatwoot');
    }
    $userId = $user['id'];
    $accessToken = $user['access_token'] ?? '';
    
    // Step 3: Set user as admin of the account
    $accountUser = $chatwoot->addUserToAccount($accountId, $userId, 'administrator');
    if (!$accountUser) {
        throw new Exception('Erro ao definir usuário como administrador');
    }
    
    // Step 4: Create instance in Evolution API
    $instance = $evolution->createInstance($companyNameClean);
    if (!$instance) {
        throw new Exception('Erro ao criar instância na Evolution API: resposta vazia');
    }
    if (isset($instance['error'])) {
        throw new Exception('Erro ao criar instância na Evolution API: ' . ($instance['response'] ?? 'desconhecido'));
    }
    if (!isset($instance['instance'])) {
        throw new Exception('Erro ao criar instância na Evolution API: formato inesperado - ' . json_encode($instance));
    }
    
    // Step 5: Integrate Evolution with Chatwoot
    $integration = $evolution->setChatwootIntegration(
        $companyNameClean,
        $accountId,
        $accessToken,
        CHATWOOT_URL
    );
    
    // Save to database if enabled
    if (USE_DATABASE) {
        $db = Database::getInstance();
        $db->saveRegistration([
            'full_name' => $input['fullName'],
            'email' => $input['email'],
            'whatsapp' => $input['whatsapp'],
            'company_name' => $input['companyName'],
            'chatwoot_account_id' => $accountId,
            'chatwoot_user_id' => $userId,
            'evolution_instance' => $companyNameClean
        ]);
    }
    
    // Send WhatsApp welcome message if enabled
    $whatsappSent = false;
    if (SEND_WELCOME_WHATSAPP) {
        $sender = new WhatsAppSender();
        $whatsappSent = $sender->sendWelcomeMessage(
            $input['whatsapp'],
            $input['fullName'],
            $input['companyName'],
            $input['email'],
            $input['password'],
            CHATWOOT_URL
        );
    }
    
    // Return success
    echo json_encode([
        'success' => true,
        'message' => 'Conta criada com sucesso!',
        'data' => [
            'chatwoot_url' => CHATWOOT_URL,
            'email' => $input['email'],
            'password' => $input['password'],
            'company_name' => $input['companyName'],
            'instance_name' => $companyNameClean,
            'whatsapp_sent' => $whatsappSent
        ]
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    error_log('Registration error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

/**
 * Sanitize company name for Evolution API instance name
 * Removes spaces, accents, and special characters
 */
function sanitizeInstanceName($name) {
    // Remove accents
    $name = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $name);
    // Remove special characters except alphanumeric
    $name = preg_replace('/[^a-zA-Z0-9]/', '', $name);
    // Ensure not empty
    if (empty($name)) {
        $name = 'Instance' . time();
    }
    return $name;
}

/**
 * Send registration data to external webhook (N8N)
 */
function sendToWebhook($input, $instanceName) {
    $webhookData = [
        // Dados do cliente
        'Nome' => $input['fullName'],
        'Email' => $input['email'],
        'WhatsApp' => $input['whatsapp'],
        'Nome da Empresa' => $input['companyName'],
        'Senha' => $input['password'],
        'InstanceName' => $instanceName,
        
        // Credenciais Chatwoot (do config.php)
        'URL_CHATWOOT' => CHATWOOT_URL,
        'TOKEN_CHATWOOT' => CHATWOOT_TOKEN,
        
        // Credenciais Evolution API (do config.php)
        'URL_EVOLUTION' => EVOLUTION_URL,
        'APIKEY_EVOLUTION' => EVOLUTION_APIKEY
    ];
    
    $ch = curl_init(WEBHOOK_URL);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($webhookData),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 60
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode >= 200 && $httpCode < 300 && $response) {
        $result = json_decode($response, true);
        
        // N8N pode retornar array ou objeto
        if (is_array($result) && isset($result[0])) {
            $result = $result[0]; // Pegar primeiro item do array
        }
        
        if ($result && isset($result['success']) && $result['success']) {
            return [
                'success' => true,
                'message' => 'Conta criada com sucesso!',
                'data' => [
                    'chatwoot_url' => $result['chatwoot_url'] ?? CHATWOOT_URL,
                    'email' => $result['email'] ?? $input['email'],
                    'password' => $result['password'] ?? $input['password'],
                    'company_name' => $result['company_name'] ?? $input['companyName'],
                    'instance_name' => $result['instance_name'] ?? $instanceName,
                    'account_id' => $result['account_id'] ?? null,
                    'user_id' => $result['user_id'] ?? null,
                    'whatsapp_sent' => $result['whatsapp_sent'] ?? false
                ]
            ];
        }
    }
    
    return [
        'success' => false,
        'message' => 'Erro ao processar cadastro via webhook'
    ];
}
