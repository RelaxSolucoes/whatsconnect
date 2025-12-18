<?php
define('WHATSCONNECT', true);
require_once __DIR__ . '/config/config.php';

// Helper function for WhatsApp link
function getWhatsAppLink() {
    if (empty(ADMIN_WHATSAPP)) return '#';
    $message = urlencode(defined('CONTACT_MESSAGE') ? CONTACT_MESSAGE : 'Olá! Tenho interesse na ferramenta.');
    return 'https://wa.me/' . ADMIN_WHATSAPP . '?text=' . $message;
}
$whatsappLink = getWhatsAppLink();
$hasWhatsApp = !empty(ADMIN_WHATSAPP);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Integração WhatsApp + Chatwoot</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <i class="fab fa-whatsapp logo-icon"></i>
                <span class="brand-text"><?php echo APP_NAME; ?></span>
            </div>
            <div class="navbar-menu">
                <a href="#features" class="nav-link">Recursos</a>
                <a href="#how-it-works" class="nav-link">Como Funciona</a>
                <a href="#pricing" class="nav-link">Preços</a>
                <button class="btn btn-outline" onclick="openModal('login')">Entrar</button>
                <button class="btn btn-primary" onclick="openModal('register')">
                    <i class="fas fa-rocket"></i> Teste Grátis
                </button>
            </div>
            <div class="navbar-toggle" onclick="toggleMobileMenu()">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <a href="#features" class="mobile-link" onclick="toggleMobileMenu()">Recursos</a>
        <a href="#how-it-works" class="mobile-link" onclick="toggleMobileMenu()">Como Funciona</a>
        <a href="#pricing" class="mobile-link" onclick="toggleMobileMenu()">Preços</a>
        <button class="btn btn-outline btn-block" onclick="openModal('login'); toggleMobileMenu();">Entrar</button>
        <button class="btn btn-primary btn-block" onclick="openModal('register'); toggleMobileMenu();">
            <i class="fas fa-rocket"></i> Teste Grátis
        </button>
    </div>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-bg"></div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-bolt"></i> Comece em menos de 5 minutos
                </div>
                <h1 class="hero-title">
                    Atendimento via <span class="highlight">WhatsApp</span> 
                    <br>de forma profissional
                </h1>
                <p class="hero-subtitle">
                    Integre seu WhatsApp ao Chatwoot e tenha um sistema completo de atendimento 
                    ao cliente. Múltiplos atendentes, filas, relatórios e muito mais.
                </p>
                <div class="hero-buttons">
                    <button class="btn btn-primary btn-lg" onclick="openModal('register')">
                        <i class="fab fa-whatsapp"></i> Começar Teste Grátis
                    </button>
                    <a href="#how-it-works" class="btn btn-ghost btn-lg">
                        <i class="fas fa-play-circle"></i> Ver demonstração
                    </a>
                </div>
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number">10k+</span>
                        <span class="stat-label">Empresas</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">1M+</span>
                        <span class="stat-label">Mensagens/dia</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">99.9%</span>
                        <span class="stat-label">Uptime</span>
                    </div>
                </div>
            </div>
            <div class="hero-image">
                <div class="phone-mockup">
                    <div class="phone-screen">
                        <div class="chat-preview">
                            <div class="chat-header-preview">
                                <i class="fab fa-whatsapp"></i>
                                <span><?php echo APP_NAME; ?></span>
                            </div>
                            <div class="chat-messages-preview">
                                <div class="msg msg-received">Olá! Como posso ajudar? 👋</div>
                                <div class="msg msg-sent">Quero saber sobre os planos</div>
                                <div class="msg msg-received">Claro! Temos teste grátis por 7 dias!</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-wave">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#0a0a0a"/>
            </svg>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Recursos</span>
                <h2 class="section-title">Tudo que você precisa para atender pelo WhatsApp</h2>
                <p class="section-subtitle">Recursos poderosos para transformar seu atendimento</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <h3>Integração WhatsApp</h3>
                    <p>Conecte seu WhatsApp Business ou pessoal em segundos. Sem complicações.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Múltiplos Atendentes</h3>
                    <p>Adicione quantos atendentes precisar. Todos no mesmo número de WhatsApp.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h3>Automações</h3>
                    <p>Crie fluxos automáticos, respostas rápidas e chatbots inteligentes.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Relatórios Completos</h3>
                    <p>Acompanhe métricas de atendimento, tempo de resposta e satisfação.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <h3>Etiquetas e Filas</h3>
                    <p>Organize conversas com etiquetas e distribua entre equipes.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Seguro e Confiável</h3>
                    <p>Seus dados protegidos com criptografia de ponta a ponta.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How it Works Section -->
    <section class="how-it-works" id="how-it-works">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Como Funciona</span>
                <h2 class="section-title">Comece em 3 passos simples</h2>
                <p class="section-subtitle">Setup rápido e sem complicações</p>
            </div>
            <div class="steps-container">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h3>Crie sua conta</h3>
                        <p>Cadastre-se gratuitamente e tenha acesso imediato ao painel.</p>
                    </div>
                    <div class="step-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                </div>
                <div class="step-connector"></div>
                <div class="step-card">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h3>Conecte o WhatsApp</h3>
                        <p>Escaneie o QR Code e pronto! Seu WhatsApp está integrado.</p>
                    </div>
                    <div class="step-icon">
                        <i class="fas fa-qrcode"></i>
                    </div>
                </div>
                <div class="step-connector"></div>
                <div class="step-card">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h3>Comece a atender</h3>
                        <p>Convide sua equipe e comece a atender seus clientes.</p>
                    </div>
                    <div class="step-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                </div>
            </div>
            <div class="cta-center">
                <button class="btn btn-primary btn-lg pulse" onclick="openModal('register')">
                    <i class="fas fa-rocket"></i> Criar Conta Grátis Agora
                </button>
                <p class="cta-note">Não precisa de cartão de crédito • Cancele quando quiser</p>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="pricing" id="pricing">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Preços</span>
                <h2 class="section-title">Planos para todos os tamanhos</h2>
                <p class="section-subtitle">Comece grátis e escale conforme cresce</p>
            </div>
            <div class="pricing-grid">
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3>Teste Grátis</h3>
                        <div class="price">
                            <span class="currency">R$</span>
                            <span class="amount"><?php echo PRICE_FREE; ?></span>
                            <span class="period">/7 dias</span>
                        </div>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="fas fa-check"></i> 1 Instância WhatsApp</li>
                        <li><i class="fas fa-check"></i> Até 3 atendentes</li>
                        <li><i class="fas fa-check"></i> Mensagens ilimitadas</li>
                        <li><i class="fas fa-check"></i> Suporte por email</li>
                    </ul>
                    <button class="btn btn-outline btn-block" onclick="openModal('register')">
                        Começar Grátis
                    </button>
                </div>
                <div class="pricing-card featured">
                    <div class="pricing-badge">Mais Popular</div>
                    <div class="pricing-header">
                        <h3>Profissional</h3>
                        <div class="price">
                            <span class="currency">R$</span>
                            <span class="amount"><?php echo PRICE_PRO; ?></span>
                            <span class="period">/mês</span>
                        </div>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="fas fa-check"></i> 3 Instâncias WhatsApp</li>
                        <li><i class="fas fa-check"></i> Até 10 atendentes</li>
                        <li><i class="fas fa-check"></i> Mensagens ilimitadas</li>
                        <li><i class="fas fa-check"></i> Automações básicas</li>
                        <li><i class="fas fa-check"></i> Relatórios avançados</li>
                        <li><i class="fas fa-check"></i> Suporte prioritário</li>
                    </ul>
                    <button class="btn btn-primary btn-block" onclick="openModal('register')">
                        <i class="fas fa-rocket"></i> Teste Grátis 7 Dias
                    </button>
                </div>
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3>Enterprise</h3>
                        <div class="price">
                            <span class="currency">R$</span>
                            <span class="amount"><?php echo PRICE_ENTERPRISE; ?></span>
                            <span class="period">/mês</span>
                        </div>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="fas fa-check"></i> Instâncias ilimitadas</li>
                        <li><i class="fas fa-check"></i> Atendentes ilimitados</li>
                        <li><i class="fas fa-check"></i> API completa</li>
                        <li><i class="fas fa-check"></i> Automações avançadas</li>
                        <li><i class="fas fa-check"></i> Integrações customizadas</li>
                        <li><i class="fas fa-check"></i> Suporte 24/7</li>
                    </ul>
                    <?php if ($hasWhatsApp): ?>
                    <a href="<?php echo $whatsappLink; ?>" target="_blank" class="btn btn-outline btn-block">
                        <i class="fab fa-whatsapp"></i> Falar com Vendas
                    </a>
                    <?php else: ?>
                    <button class="btn btn-outline btn-block" onclick="openModal('register')">
                        Falar com Vendas
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Pronto para revolucionar seu atendimento?</h2>
                <p>Junte-se a milhares de empresas que já transformaram seu atendimento com WhatsConnect</p>
                <button class="btn btn-light btn-lg" onclick="openModal('register')">
                    <i class="fab fa-whatsapp"></i> Começar Teste Grátis
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="navbar-brand">
                        <i class="fab fa-whatsapp logo-icon"></i>
                        <span class="brand-text"><?php echo APP_NAME; ?></span>
                    </div>
                    <p>A melhor solução para atendimento via WhatsApp integrado ao Chatwoot.</p>
                    <?php if ($hasWhatsApp): ?>
                    <a href="<?php echo $whatsappLink; ?>" target="_blank" class="btn btn-primary btn-sm" style="margin-top: 15px;">
                        <i class="fab fa-whatsapp"></i> Fale Conosco
                    </a>
                    <?php endif; ?>
                </div>
                <div class="footer-links">
                    <h4>Produto</h4>
                    <a href="#features">Recursos</a>
                    <a href="#pricing">Preços</a>
                    <a href="#how-it-works">Como Funciona</a>
                </div>
                <div class="footer-links">
                    <h4>Começar</h4>
                    <a href="#" onclick="openModal('register'); return false;">Criar Conta</a>
                    <a href="#" onclick="openModal('login'); return false;">Fazer Login</a>
                    <?php if ($hasWhatsApp): ?>
                    <a href="<?php echo $whatsappLink; ?>" target="_blank">Falar com Vendas</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Float Button -->
    <?php if ($hasWhatsApp): ?>
    <a href="<?php echo $whatsappLink; ?>" target="_blank" class="whatsapp-float" title="Fale conosco pelo WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
    <?php endif; ?>

    <!-- Register Modal -->
    <div class="modal-overlay" id="registerModal">
        <div class="modal">
            <button class="modal-close" onclick="closeModal('register')">
                <i class="fas fa-times"></i>
            </button>
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <h2>Criar Conta Grátis</h2>
                <p>Comece seu teste grátis de 7 dias agora mesmo</p>
            </div>
            <form class="modal-form" id="registerForm" onsubmit="handleRegister(event)">
                <div class="form-group">
                    <label for="fullName">Nome Completo</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" id="fullName" name="fullName" placeholder="Seu nome completo" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="whatsapp">WhatsApp</label>
                    <div class="input-wrapper">
                        <i class="fab fa-whatsapp"></i>
                        <input type="tel" id="whatsapp" name="whatsapp" placeholder="(00) 00000-0000" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="seu@email.com" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="companyName">Nome da Empresa</label>
                    <div class="input-wrapper">
                        <i class="fas fa-building"></i>
                        <input type="text" id="companyName" name="companyName" placeholder="Nome da sua empresa" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Senha</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Crie uma senha forte" required>
                        <button type="button" class="toggle-password" onclick="togglePassword('password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-requirements">
                        <span id="req-upper" class="req"><i class="fas fa-circle"></i> 1 letra maiúscula</span>
                        <span id="req-special" class="req"><i class="fas fa-circle"></i> 1 caractere especial</span>
                        <span id="req-number" class="req"><i class="fas fa-circle"></i> 1 número</span>
                        <span id="req-length" class="req"><i class="fas fa-circle"></i> Mínimo 8 caracteres</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirmar Senha</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirme sua senha" required>
                        <button type="button" class="toggle-password" onclick="togglePassword('confirmPassword')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <span class="error-message" id="passwordMatchError"></span>
                </div>
                <button type="submit" class="btn btn-primary btn-block btn-lg" id="registerBtn">
                    <i class="fas fa-rocket"></i> Criar Conta Grátis
                </button>
            </form>
            <div class="modal-footer">
                <p>Já tem uma conta? <a href="#" onclick="switchModal('login')">Fazer login</a></p>
            </div>
        </div>
    </div>

    <!-- Login Modal -->
    <div class="modal-overlay" id="loginModal">
        <div class="modal modal-sm">
            <button class="modal-close" onclick="closeModal('login')">
                <i class="fas fa-times"></i>
            </button>
            <div class="modal-header">
                <div class="modal-icon">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <h2>Entrar</h2>
                <p>Acesse sua conta</p>
            </div>
            <form class="modal-form" id="loginForm" onsubmit="handleLogin(event)">
                <div class="form-group">
                    <label for="loginEmail">Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="loginEmail" name="email" placeholder="seu@email.com" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="loginPassword">Senha</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="loginPassword" name="password" placeholder="Sua senha" required>
                        <button type="button" class="toggle-password" onclick="togglePassword('loginPassword')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="form-group flex-between">
                    <label class="checkbox-label">
                        <input type="checkbox" id="remember">
                        <span class="checkmark"></span>
                        Lembrar-me
                    </label>
                    <a href="#" class="forgot-link">Esqueci a senha</a>
                </div>
                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    <i class="fas fa-sign-in-alt"></i> Entrar
                </button>
            </form>
            <div class="modal-footer">
                <p>Não tem uma conta? <a href="#" onclick="switchModal('register')">Criar conta grátis</a></p>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal-overlay" id="successModal">
        <div class="modal modal-sm">
            <div class="modal-header success">
                <div class="modal-icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2>Conta Criada com Sucesso!</h2>
                <p id="successMessage">Seus dados de acesso foram enviados para seu WhatsApp.</p>
            </div>
            <div class="success-details" id="successDetails">
                <!-- Will be populated dynamically -->
            </div>
            <button class="btn btn-primary btn-block btn-lg" onclick="redirectToChatwoot()">
                <i class="fas fa-external-link-alt"></i> Acessar Chatwoot
            </button>
            <button class="btn btn-ghost btn-block" onclick="closeModal('success')">
                Fechar
            </button>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="loading-spinner">
                <i class="fab fa-whatsapp"></i>
            </div>
            <h3>Criando sua conta...</h3>
            <p id="loadingMessage">Configurando empresa no Chatwoot</p>
            <div class="loading-steps">
                <div class="loading-step" id="step1">
                    <i class="fas fa-circle"></i>
                    <span>Criando empresa</span>
                </div>
                <div class="loading-step" id="step2">
                    <i class="fas fa-circle"></i>
                    <span>Criando usuário</span>
                </div>
                <div class="loading-step" id="step3">
                    <i class="fas fa-circle"></i>
                    <span>Configurando WhatsApp</span>
                </div>
                <div class="loading-step" id="step4">
                    <i class="fas fa-circle"></i>
                    <span>Finalizando</span>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/app.js"></script>
</body>
</html>
