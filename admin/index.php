<?php
/**
 * WhatsConnect - Admin Panel
 */
session_start();

define('WHATSCONNECT', true);
require_once __DIR__ . '/../config/config.php';

// Check authentication
$isAuthenticated = isset($_SESSION['admin']) && $_SESSION['admin'] === true;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - WhatsConnect</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/admin/css/admin.css">
</head>
<body>
    <?php if (!$isAuthenticated): ?>
    <!-- Login Screen -->
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="fab fa-whatsapp logo"></i>
                <h1>WhatsConnect</h1>
                <p>Painel Administrativo</p>
            </div>
            <form id="loginForm" onsubmit="handleAdminLogin(event)">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="email" placeholder="admin@whatsconnect.com" required>
                </div>
                <div class="form-group">
                    <label>Senha</label>
                    <input type="password" id="password" placeholder="Sua senha" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i> Entrar
                </button>
            </form>
            <div class="login-footer">
                <a href="../"><i class="fas fa-arrow-left"></i> Voltar ao site</a>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- Admin Dashboard -->
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <i class="fab fa-whatsapp"></i>
                <span>WhatsConnect</span>
            </div>
            <nav class="sidebar-nav">
                <a href="#" class="nav-item active" data-section="dashboard">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="#" class="nav-item" data-section="settings">
                    <i class="fas fa-cog"></i> Configurações
                </a>
                <a href="#" class="nav-item" data-section="credentials">
                    <i class="fas fa-key"></i> Credenciais
                </a>
                <a href="#" class="nav-item" data-section="registrations">
                    <i class="fas fa-users"></i> Cadastros
                </a>
                <a href="#" class="nav-item" data-section="messages">
                    <i class="fas fa-comment-dots"></i> Mensagens
                </a>
                <a href="#" class="nav-item" data-section="personalization">
                    <i class="fas fa-palette"></i> Personalização
                </a>
            </nav>
            <div class="sidebar-footer">
                <a href="/admin/logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="content-header">
                <h1 id="pageTitle">Dashboard</h1>
                <div class="header-actions">
                    <span class="status-badge online">
                        <i class="fas fa-circle"></i> Sistema Online
                    </span>
                </div>
            </header>

            <!-- Dashboard Section -->
            <section id="dashboardSection" class="content-section active">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-value" id="totalRegistrations">0</span>
                            <span class="stat-label">Cadastros</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-value" id="totalInstances">0</span>
                            <span class="stat-label">Instâncias</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-value" id="totalMessages">0</span>
                            <span class="stat-label">Mensagens Enviadas</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-value" id="systemStatus">OK</span>
                            <span class="stat-label">Status do Sistema</span>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Configuração Rápida</h3>
                    </div>
                    <div class="card-body">
                        <div class="quick-status">
                            <div class="status-item">
                                <span class="status-label">Chatwoot API</span>
                                <span class="status-value" id="chatwootStatus">
                                    <?php echo !empty(CHATWOOT_URL) ? '<i class="fas fa-check-circle text-success"></i> Configurado' : '<i class="fas fa-times-circle text-danger"></i> Não configurado'; ?>
                                </span>
                            </div>
                            <div class="status-item">
                                <span class="status-label">Evolution API</span>
                                <span class="status-value" id="evolutionStatus">
                                    <?php echo !empty(EVOLUTION_URL) ? '<i class="fas fa-check-circle text-success"></i> Configurado' : '<i class="fas fa-times-circle text-danger"></i> Não configurado'; ?>
                                </span>
                            </div>
                            <div class="status-item">
                                <span class="status-label">Webhook Externo</span>
                                <span class="status-value">
                                    <?php echo USE_EXTERNAL_WEBHOOK ? '<i class="fas fa-check-circle text-success"></i> Ativo (N8N)' : '<i class="fas fa-circle text-muted"></i> Inativo (PHP)'; ?>
                                </span>
                            </div>
                            <div class="status-item">
                                <span class="status-label">Banco de Dados</span>
                                <span class="status-value">
                                    <?php echo USE_DATABASE ? '<i class="fas fa-check-circle text-success"></i> Ativo' : '<i class="fas fa-circle text-muted"></i> Inativo'; ?>
                                </span>
                            </div>
                            <div class="status-item">
                                <span class="status-label">WhatsApp Boas-Vindas</span>
                                <span class="status-value">
                                    <?php echo SEND_WELCOME_WHATSAPP ? '<i class="fas fa-check-circle text-success"></i> Ativo' : '<i class="fas fa-circle text-muted"></i> Inativo'; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Settings Section -->
            <section id="settingsSection" class="content-section">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-cog"></i> Configurações do Sistema</h3>
                    </div>
                    <div class="card-body">
                        <form id="settingsForm">
                            <div class="settings-group">
                                <h4>Modo de Operação</h4>
                                
                                <div class="toggle-setting">
                                    <div class="toggle-info">
                                        <label>Utilizar Webhook Externo (N8N)</label>
                                        <p>Se ativo, os dados serão enviados para um webhook N8N para processamento.</p>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="useWebhook" <?php echo USE_EXTERNAL_WEBHOOK ? 'checked' : ''; ?>>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>

                                <div class="form-group webhook-url-group" style="<?php echo USE_EXTERNAL_WEBHOOK ? '' : 'display:none;'; ?>">
                                    <label>URL do Webhook (N8N)</label>
                                    <input type="url" id="webhookUrl" value="<?php echo htmlspecialchars(WEBHOOK_URL); ?>" placeholder="https://seu-n8n.com/webhook/...">
                                </div>

                                <div class="toggle-setting">
                                    <div class="toggle-info">
                                        <label>Utilizar Banco de Dados Externo</label>
                                        <p>Se ativo, os dados dos cadastros serão salvos no MySQL.</p>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="useDatabase" <?php echo USE_DATABASE ? 'checked' : ''; ?>>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>

                                <div class="toggle-setting">
                                    <div class="toggle-info">
                                        <label>Enviar WhatsApp de Boas-Vindas</label>
                                        <p>Se ativo, os dados de acesso serão enviados via WhatsApp.</p>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="sendWelcome" <?php echo SEND_WELCOME_WHATSAPP ? 'checked' : ''; ?>>
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Salvar Configurações
                            </button>
                        </form>
                        
                        <hr style="margin: 30px 0; border-color: var(--gray-dark);">
                        
                        <div class="danger-zone">
                            <h4 style="color: #e74c3c;"><i class="fas fa-exclamation-triangle"></i> Zona de Perigo</h4>
                            <p style="color: var(--gray); margin-bottom: 15px;">
                                Esta ação irá redefinir todas as configurações para o padrão de fábrica. 
                                Todas as credenciais e configurações serão apagadas.
                            </p>
                            <button type="button" class="btn btn-danger" onclick="factoryReset()">
                                <i class="fas fa-undo"></i> Redefinir Configurações de Fábrica
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Credentials Section -->
            <section id="credentialsSection" class="content-section">
                <div class="credentials-grid">
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fas fa-comments"></i> Credenciais Chatwoot</h3>
                        </div>
                        <div class="card-body">
                            <form id="chatwootForm">
                                <div class="form-group">
                                    <label>URL do Chatwoot</label>
                                    <input type="url" id="chatwootUrl" value="<?php echo htmlspecialchars(CHATWOOT_URL); ?>" placeholder="https://chat.seudominio.com">
                                    <small>URL completa do seu Chatwoot (sem barra no final)</small>
                                </div>
                                <div class="form-group">
                                    <label>Token da Platform API</label>
                                    <div class="input-with-btn">
                                        <input type="password" id="chatwootToken" value="<?php echo htmlspecialchars(CHATWOOT_TOKEN); ?>" placeholder="Seu token aqui">
                                        <button type="button" class="btn btn-icon" onclick="toggleVisibility('chatwootToken')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small>Token de acesso à Platform API do Chatwoot</small>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Salvar
                                </button>
                                <button type="button" class="btn btn-outline" onclick="testChatwoot()">
                                    <i class="fas fa-plug"></i> Testar Conexão
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fab fa-whatsapp"></i> Credenciais Evolution API</h3>
                        </div>
                        <div class="card-body">
                            <form id="evolutionForm">
                                <div class="form-group">
                                    <label>URL da Evolution API</label>
                                    <input type="url" id="evolutionUrl" value="<?php echo htmlspecialchars(EVOLUTION_URL); ?>" placeholder="https://api.seudominio.com">
                                    <small>URL completa da Evolution API (sem barra no final)</small>
                                </div>
                                <div class="form-group">
                                    <label>API Key</label>
                                    <div class="input-with-btn">
                                        <input type="password" id="evolutionApikey" value="<?php echo htmlspecialchars(EVOLUTION_APIKEY); ?>" placeholder="Sua API Key">
                                        <button type="button" class="btn btn-icon" onclick="toggleVisibility('evolutionApikey')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Nome da Instância (para envio de mensagens)</label>
                                    <input type="text" id="evolutionInstance" value="<?php echo htmlspecialchars(EVOLUTION_INSTANCE); ?>" placeholder="MinhaInstancia">
                                    <small>Instância usada para enviar mensagens de boas-vindas</small>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Salvar
                                </button>
                                <button type="button" class="btn btn-outline" onclick="testEvolution()">
                                    <i class="fas fa-plug"></i> Testar Conexão
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Registrations Section -->
            <section id="registrationsSection" class="content-section">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-users"></i> Cadastros Realizados</h3>
                        <button class="btn btn-sm btn-outline" onclick="loadRegistrations()">
                            <i class="fas fa-sync"></i> Atualizar
                        </button>
                    </div>
                    <div class="card-body">
                        <?php if (!USE_DATABASE): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            O banco de dados está desativado. Ative nas configurações para visualizar os cadastros.
                        </div>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="data-table" id="registrationsTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>WhatsApp</th>
                                        <th>Empresa</th>
                                        <th>Data</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Populated via JavaScript -->
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <!-- Messages Section -->
            <section id="messagesSection" class="content-section">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-comment-dots"></i> Mensagem de Boas-Vindas</h3>
                    </div>
                    <div class="card-body">
                        <form id="messageForm">
                            <div class="form-group">
                                <label>Template da Mensagem</label>
                                <textarea id="welcomeMessage" rows="12" placeholder="Digite a mensagem de boas-vindas..."><?php echo htmlspecialchars(WELCOME_MESSAGE_TEMPLATE); ?></textarea>
                            </div>
                            <div class="shortcodes-help">
                                <h4>Shortcodes Disponíveis:</h4>
                                <div class="shortcode-list">
                                    <span class="shortcode" onclick="insertShortcode('{nome}')">{nome}</span>
                                    <span class="shortcode" onclick="insertShortcode('{empresa}')">{empresa}</span>
                                    <span class="shortcode" onclick="insertShortcode('{email}')">{email}</span>
                                    <span class="shortcode" onclick="insertShortcode('{senha}')">{senha}</span>
                                    <span class="shortcode" onclick="insertShortcode('{url}')">{url}</span>
                                </div>
                                <small>Clique em um shortcode para inserir no cursor</small>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Salvar Template
                            </button>
                            <button type="button" class="btn btn-outline" onclick="previewMessage()">
                                <i class="fas fa-eye"></i> Pré-visualizar
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card mt-20">
                    <div class="card-header">
                        <h3><i class="fas fa-paper-plane"></i> Enviar Mensagem de Teste</h3>
                    </div>
                    <div class="card-body">
                        <form id="testMessageForm">
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Número WhatsApp</label>
                                    <input type="tel" id="testNumber" placeholder="5519999999999">
                                    <small>Formato: DDI + DDD + Número (ex: 5519999999999)</small>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fab fa-whatsapp"></i> Enviar Teste
                            </button>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Personalization Section -->
            <section id="personalizationSection" class="content-section">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-palette"></i> Personalização White Label</h3>
                    </div>
                    <div class="card-body">
                        <form id="personalizationForm">
                            <div class="settings-group">
                                <h4>Identidade da Marca</h4>
                                
                                <div class="form-group">
                                    <label>Nome do Aplicativo</label>
                                    <input type="text" id="appName" value="<?php echo htmlspecialchars(APP_NAME); ?>" placeholder="WhatsConnect">
                                    <small>Este nome aparecerá no cabeçalho, rodapé e em toda a landing page</small>
                                </div>
                            </div>

                            <div class="settings-group">
                                <h4>Contato WhatsApp (Vendas)</h4>
                                
                                <div class="form-group">
                                    <label>WhatsApp do Admin/Vendas</label>
                                    <input type="text" id="adminWhatsapp" value="<?php echo htmlspecialchars(defined('ADMIN_WHATSAPP') ? ADMIN_WHATSAPP : ''); ?>" placeholder="5511999999999">
                                    <small>Número com DDI+DDD sem espaços. Ex: 5511999999999. Usado no botão "Falar com Vendas" e no widget flutuante.</small>
                                </div>
                                
                                <div class="form-group">
                                    <label>Mensagem Padrão de Contato</label>
                                    <input type="text" id="contactMessage" value="<?php echo htmlspecialchars(defined('CONTACT_MESSAGE') ? CONTACT_MESSAGE : 'Olá! Tenho interesse na ferramenta e gostaria de mais informações.'); ?>">
                                    <small>Mensagem pré-preenchida quando o visitante clicar no WhatsApp</small>
                                </div>
                            </div>

                            <div class="settings-group">
                                <h4>Preços dos Planos</h4>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Plano Grátis (R$)</label>
                                        <input type="number" id="priceFree" value="<?php echo defined('PRICE_FREE') ? PRICE_FREE : '0'; ?>" min="0">
                                    </div>
                                    <div class="form-group">
                                        <label>Plano Profissional (R$)</label>
                                        <input type="number" id="pricePro" value="<?php echo defined('PRICE_PRO') ? PRICE_PRO : '97'; ?>" min="0">
                                    </div>
                                    <div class="form-group">
                                        <label>Plano Enterprise (R$)</label>
                                        <input type="number" id="priceEnterprise" value="<?php echo defined('PRICE_ENTERPRISE') ? PRICE_ENTERPRISE : '297'; ?>" min="0">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Salvar Personalização
                            </button>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    </div>
    <?php endif; ?>

    <script src="/admin/js/admin.js"></script>
</body>
</html>
