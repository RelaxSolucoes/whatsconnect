<?php
/**
 * Factory Reset API
 * Resets all configurations to default values
 */
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Não autorizado']);
    exit;
}

define('WHATSCONNECT', true);

$configFile = __DIR__ . '/../../config/config.php';

// Default configuration template
$defaultConfig = <<<'PHP'
<?php
/**
 * WhatsConnect Configuration File
 * 
 * IMPORTANT: Keep this file secure and never expose it publicly
 */

if (!defined('WHATSCONNECT')) {
    die('Direct access not allowed');
}

// Timezone
date_default_timezone_set('America/Sao_Paulo');

// ========================================
// Application Settings
// ========================================
define('APP_NAME', 'WhatsConnect');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/ChatWoot-Evolution API/projeto');

// ========================================
// Chatwoot API Credentials
// ========================================
define('CHATWOOT_URL', ''); // Ex: https://chat.seudominio.com
define('CHATWOOT_TOKEN', ''); // Platform API Token

// ========================================
// Evolution API Credentials
// ========================================
define('EVOLUTION_URL', ''); // Ex: https://api.seudominio.com
define('EVOLUTION_APIKEY', '');
define('EVOLUTION_INSTANCE', ''); // Instance name for sending welcome messages

// ========================================
// Webhook Settings
// ========================================
define('USE_EXTERNAL_WEBHOOK', false); // true = N8N, false = PHP backend
define('WEBHOOK_URL', ''); // N8N Webhook URL (only if USE_EXTERNAL_WEBHOOK = true)

// ========================================
// Database Settings (Optional)
// ========================================
define('USE_DATABASE', false); // true = save to MySQL, false = no database

// MySQL credentials (only used if USE_DATABASE = true)
define('DB_HOST', 'localhost');
define('DB_NAME', 'whatsconnect');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// ========================================
// WhatsApp Welcome Message Settings
// ========================================
define('SEND_WELCOME_WHATSAPP', true); // true = send welcome message via WhatsApp

// Welcome message template
// Available shortcodes: {nome}, {empresa}, {email}, {senha}, {url}
define('WELCOME_MESSAGE_TEMPLATE', 
'🎉 *Bem-vindo ao WhatsConnect!*

Olá {nome}! Sua conta foi criada com sucesso.

📋 *Dados de Acesso:*
🔗 URL: {url}
📧 Email: {email}
🔑 Senha: {senha}

🏢 Empresa: {empresa}

Acesse o painel e escaneie o QR Code para conectar seu WhatsApp!

_Equipe WhatsConnect_');

// ========================================
// Security Settings
// ========================================
define('ADMIN_EMAIL', 'admin@whatsconnect.com');
define('ADMIN_PASSWORD_HASH', ''); // Set via admin panel or manually with password_hash()

// JWT Secret for session tokens (change this!)
define('JWT_SECRET', 'your-super-secret-key-change-this-in-production');

// ========================================
// Rate Limiting
// ========================================
define('RATE_LIMIT_REQUESTS', 10); // Max requests
define('RATE_LIMIT_WINDOW', 60); // Per X seconds

// ========================================
// White Label / Personalization
// ========================================
define('ADMIN_WHATSAPP', ''); // WhatsApp for "Talk to Sales" button (with country code, e.g., 5511999999999)
define('CONTACT_MESSAGE', 'Olá! Tenho interesse na ferramenta e gostaria de mais informações.'); // Default message for WhatsApp contact

// Pricing (values in BRL)
define('PRICE_FREE', '0');
define('PRICE_PRO', '97');
define('PRICE_ENTERPRISE', '297');
PHP;

// Write default config
if (file_put_contents($configFile, $defaultConfig)) {
    echo json_encode([
        'success' => true, 
        'message' => 'Configurações redefinidas para o padrão de fábrica!'
    ]);
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Erro ao redefinir configurações. Verifique as permissões do arquivo.'
    ]);
}
