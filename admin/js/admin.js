// ========================================
// WhatsConnect Admin Panel JavaScript
// ========================================

// ========================================
// Navigation
// ========================================
document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', (e) => {
        e.preventDefault();
        const section = item.dataset.section;
        
        // Update active nav
        document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
        item.classList.add('active');
        
        // Update page title
        const titles = {
            dashboard: 'Dashboard',
            settings: 'Configurações',
            credentials: 'Credenciais',
            registrations: 'Cadastros',
            messages: 'Mensagens'
        };
        document.getElementById('pageTitle').textContent = titles[section] || 'Dashboard';
        
        // Show section
        document.querySelectorAll('.content-section').forEach(sec => sec.classList.remove('active'));
        document.getElementById(section + 'Section').classList.add('active');
        
        // Load data if needed
        if (section === 'registrations') {
            loadRegistrations();
        }
    });
});

// ========================================
// Admin Login
// ========================================
async function handleAdminLogin(event) {
    event.preventDefault();
    
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    
    try {
        const response = await fetch('/api/login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password })
        });
        
        const result = await response.json();
        
        if (result.success && result.data.is_admin) {
            window.location.reload();
        } else {
            showToast('Credenciais inválidas', 'error');
        }
    } catch (error) {
        showToast('Erro de conexão', 'error');
    }
}

// ========================================
// Toggle Password Visibility
// ========================================
function toggleVisibility(inputId) {
    const input = document.getElementById(inputId);
    input.type = input.type === 'password' ? 'text' : 'password';
}

// ========================================
// Webhook Toggle
// ========================================
const useWebhookToggle = document.getElementById('useWebhook');
if (useWebhookToggle) {
    useWebhookToggle.addEventListener('change', function() {
        const webhookUrlGroup = document.querySelector('.webhook-url-group');
        webhookUrlGroup.style.display = this.checked ? 'block' : 'none';
    });
}

// ========================================
// Settings Form
// ========================================
const settingsForm = document.getElementById('settingsForm');
if (settingsForm) {
    settingsForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const settings = {
            use_webhook: document.getElementById('useWebhook').checked,
            webhook_url: document.getElementById('webhookUrl')?.value || '',
            use_database: document.getElementById('useDatabase').checked,
            send_welcome: document.getElementById('sendWelcome').checked
        };
        
        try {
            const response = await fetch('/admin/api/save-settings.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(settings)
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast('Configurações salvas! Reinicie o servidor para aplicar.', 'success');
            } else {
                showToast(result.message || 'Erro ao salvar', 'error');
            }
        } catch (error) {
            showToast('Erro de conexão', 'error');
        }
    });
}

// ========================================
// Chatwoot Form
// ========================================
const chatwootForm = document.getElementById('chatwootForm');
if (chatwootForm) {
    chatwootForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const data = {
            type: 'chatwoot',
            url: document.getElementById('chatwootUrl').value,
            token: document.getElementById('chatwootToken').value
        };
        
        try {
            const response = await fetch('/admin/api/save-credentials.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast('Credenciais Chatwoot salvas!', 'success');
            } else {
                showToast(result.message || 'Erro ao salvar', 'error');
            }
        } catch (error) {
            showToast('Erro de conexão', 'error');
        }
    });
}

// ========================================
// Evolution Form
// ========================================
const evolutionForm = document.getElementById('evolutionForm');
if (evolutionForm) {
    evolutionForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const data = {
            type: 'evolution',
            url: document.getElementById('evolutionUrl').value,
            apikey: document.getElementById('evolutionApikey').value,
            instance: document.getElementById('evolutionInstance').value
        };
        
        try {
            const response = await fetch('/admin/api/save-credentials.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast('Credenciais Evolution salvas!', 'success');
            } else {
                showToast(result.message || 'Erro ao salvar', 'error');
            }
        } catch (error) {
            showToast('Erro de conexão', 'error');
        }
    });
}

// ========================================
// Test Connections
// ========================================
async function testChatwoot() {
    const url = document.getElementById('chatwootUrl').value;
    const token = document.getElementById('chatwootToken').value;
    
    if (!url || !token) {
        showToast('Preencha URL e Token', 'error');
        return;
    }
    
    try {
        const response = await fetch('/admin/api/test-connection.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ type: 'chatwoot', url, token })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast('Conexão Chatwoot OK!', 'success');
        } else {
            showToast('Falha na conexão: ' + (result.message || 'Verifique as credenciais'), 'error');
        }
    } catch (error) {
        showToast('Erro ao testar conexão', 'error');
    }
}

async function testEvolution() {
    const url = document.getElementById('evolutionUrl').value;
    const apikey = document.getElementById('evolutionApikey').value;
    
    if (!url || !apikey) {
        showToast('Preencha URL e API Key', 'error');
        return;
    }
    
    try {
        const response = await fetch('/admin/api/test-connection.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ type: 'evolution', url, apikey })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast('Conexão Evolution API OK!', 'success');
        } else {
            showToast('Falha na conexão: ' + (result.message || 'Verifique as credenciais'), 'error');
        }
    } catch (error) {
        showToast('Erro ao testar conexão', 'error');
    }
}

// ========================================
// Load Registrations
// ========================================
async function loadRegistrations() {
    try {
        const response = await fetch('/admin/api/get-registrations.php');
        const result = await response.json();
        
        if (result.success) {
            const tbody = document.querySelector('#registrationsTable tbody');
            if (!tbody) return;
            
            tbody.innerHTML = '';
            
            if (result.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;color:var(--gray);">Nenhum cadastro encontrado</td></tr>';
                return;
            }
            
            result.data.forEach(reg => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${reg.id}</td>
                    <td>${escapeHtml(reg.full_name)}</td>
                    <td>${escapeHtml(reg.email)}</td>
                    <td>${escapeHtml(reg.whatsapp)}</td>
                    <td>${escapeHtml(reg.company_name)}</td>
                    <td>${formatDate(reg.created_at)}</td>
                    <td>
                        <button class="btn btn-sm btn-outline" onclick="viewRegistration(${reg.id})">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
            
            // Update stats
            document.getElementById('totalRegistrations').textContent = result.data.length;
        }
    } catch (error) {
        console.error('Error loading registrations:', error);
    }
}

// ========================================
// Message Template
// ========================================
const messageForm = document.getElementById('messageForm');
if (messageForm) {
    messageForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const message = document.getElementById('welcomeMessage').value;
        
        try {
            const response = await fetch('/admin/api/save-message-template.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ message })
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast('Template salvo com sucesso!', 'success');
            } else {
                showToast(result.message || 'Erro ao salvar', 'error');
            }
        } catch (error) {
            showToast('Erro de conexão', 'error');
        }
    });
}

function insertShortcode(code) {
    const textarea = document.getElementById('welcomeMessage');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;
    textarea.value = text.substring(0, start) + code + text.substring(end);
    textarea.selectionStart = textarea.selectionEnd = start + code.length;
    textarea.focus();
}

function previewMessage() {
    const template = document.getElementById('welcomeMessage').value;
    const preview = template
        .replace('{nome}', 'João Silva')
        .replace('{empresa}', 'Empresa Teste')
        .replace('{email}', 'joao@teste.com')
        .replace('{senha}', 'Teste@123')
        .replace('{url}', 'https://chat.exemplo.com');
    
    alert('Pré-visualização:\n\n' + preview);
}

// ========================================
// Test Message
// ========================================
const testMessageForm = document.getElementById('testMessageForm');
if (testMessageForm) {
    testMessageForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const number = document.getElementById('testNumber').value;
        
        if (!number || number.length < 12) {
            showToast('Digite um número válido (ex: 5519999999999)', 'error');
            return;
        }
        
        try {
            const response = await fetch('/admin/api/send-test-message.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ number })
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast('Mensagem de teste enviada!', 'success');
            } else {
                showToast(result.message || 'Erro ao enviar', 'error');
            }
        } catch (error) {
            showToast('Erro de conexão', 'error');
        }
    });
}

// ========================================
// Personalization Form
// ========================================
const personalizationForm = document.getElementById('personalizationForm');
if (personalizationForm) {
    personalizationForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const data = {
            appName: document.getElementById('appName').value,
            adminWhatsapp: document.getElementById('adminWhatsapp').value,
            contactMessage: document.getElementById('contactMessage').value,
            priceFree: document.getElementById('priceFree').value,
            pricePro: document.getElementById('pricePro').value,
            priceEnterprise: document.getElementById('priceEnterprise').value
        };
        
        try {
            const response = await fetch('/admin/api/save-personalization.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast('Personalização salva com sucesso!', 'success');
            } else {
                showToast(result.message || 'Erro ao salvar', 'error');
            }
        } catch (error) {
            showToast('Erro de conexão', 'error');
        }
    });
}

// ========================================
// Factory Reset
// ========================================
async function factoryReset() {
    const confirmed = confirm(
        '⚠️ ATENÇÃO!\n\n' +
        'Esta ação irá:\n' +
        '• Apagar todas as credenciais (Chatwoot e Evolution)\n' +
        '• Desativar webhook externo\n' +
        '• Desativar envio de WhatsApp\n' +
        '• Desativar banco de dados\n\n' +
        'Tem certeza que deseja redefinir para o padrão de fábrica?'
    );
    
    if (!confirmed) return;
    
    const doubleConfirm = confirm('Confirme novamente para prosseguir com o reset de fábrica.');
    if (!doubleConfirm) return;
    
    try {
        const response = await fetch('/admin/api/factory-reset.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast('Configurações redefinidas com sucesso!', 'success');
            setTimeout(() => window.location.reload(), 2000);
        } else {
            showToast(result.message || 'Erro ao redefinir', 'error');
        }
    } catch (error) {
        showToast('Erro de conexão', 'error');
    }
}

// ========================================
// Utility Functions
// ========================================
function showToast(message, type = 'info') {
    // Remove existing toasts
    document.querySelectorAll('.toast').forEach(t => t.remove());
    
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => toast.remove(), 4000);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatDate(dateStr) {
    if (!dateStr) return '-';
    const date = new Date(dateStr);
    return date.toLocaleDateString('pt-BR') + ' ' + date.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
}

function viewRegistration(id) {
    // Could open a modal with full details
    alert('Detalhes do cadastro #' + id);
}

// ========================================
// Initialize
// ========================================
document.addEventListener('DOMContentLoaded', () => {
    console.log('Admin panel initialized');
    
    // Load initial stats if on dashboard
    if (document.getElementById('dashboardSection')?.classList.contains('active')) {
        loadRegistrations();
    }
});
