<?php
/**
 * Save Personalization Settings API
 */
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Não autorizado']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    exit;
}

$configFile = __DIR__ . '/../../config/config.php';

if (!file_exists($configFile)) {
    echo json_encode(['success' => false, 'message' => 'Arquivo de configuração não encontrado']);
    exit;
}

$content = file_get_contents($configFile);

// Update APP_NAME
if (isset($input['appName'])) {
    $appName = addslashes($input['appName']);
    $content = preg_replace(
        "/define\('APP_NAME',\s*'[^']*'\)/",
        "define('APP_NAME', '$appName')",
        $content
    );
}

// Update ADMIN_WHATSAPP
if (isset($input['adminWhatsapp'])) {
    $whatsapp = preg_replace('/[^0-9]/', '', $input['adminWhatsapp']);
    if (preg_match("/define\('ADMIN_WHATSAPP',\s*'[^']*'\)/", $content)) {
        $content = preg_replace(
            "/define\('ADMIN_WHATSAPP',\s*'[^']*'\)/",
            "define('ADMIN_WHATSAPP', '$whatsapp')",
            $content
        );
    }
}

// Update CONTACT_MESSAGE
if (isset($input['contactMessage'])) {
    $message = addslashes($input['contactMessage']);
    if (preg_match("/define\('CONTACT_MESSAGE',\s*'[^']*'\)/", $content)) {
        $content = preg_replace(
            "/define\('CONTACT_MESSAGE',\s*'[^']*'\)/",
            "define('CONTACT_MESSAGE', '$message')",
            $content
        );
    }
}

// Update PRICE_FREE
if (isset($input['priceFree'])) {
    $price = intval($input['priceFree']);
    if (preg_match("/define\('PRICE_FREE',\s*'[^']*'\)/", $content)) {
        $content = preg_replace(
            "/define\('PRICE_FREE',\s*'[^']*'\)/",
            "define('PRICE_FREE', '$price')",
            $content
        );
    }
}

// Update PRICE_PRO
if (isset($input['pricePro'])) {
    $price = intval($input['pricePro']);
    if (preg_match("/define\('PRICE_PRO',\s*'[^']*'\)/", $content)) {
        $content = preg_replace(
            "/define\('PRICE_PRO',\s*'[^']*'\)/",
            "define('PRICE_PRO', '$price')",
            $content
        );
    }
}

// Update PRICE_ENTERPRISE
if (isset($input['priceEnterprise'])) {
    $price = intval($input['priceEnterprise']);
    if (preg_match("/define\('PRICE_ENTERPRISE',\s*'[^']*'\)/", $content)) {
        $content = preg_replace(
            "/define\('PRICE_ENTERPRISE',\s*'[^']*'\)/",
            "define('PRICE_ENTERPRISE', '$price')",
            $content
        );
    }
}

// Write updated config
if (file_put_contents($configFile, $content)) {
    echo json_encode(['success' => true, 'message' => 'Personalização salva com sucesso!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar. Verifique as permissões do arquivo.']);
}
