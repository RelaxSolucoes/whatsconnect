// ========================================
// WhatsConnect - Main Application JS
// ========================================

// Global state
let chatwootUrl = '';

// ========================================
// Mobile Menu Toggle
// ========================================
function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    menu.classList.toggle('active');
}

// ========================================
// Modal Functions
// ========================================
function openModal(type) {
    const modalId = type + 'Modal';
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(type) {
    const modalId = type + 'Modal';
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

function switchModal(type) {
    closeModal('login');
    closeModal('register');
    setTimeout(() => openModal(type), 100);
}

// Close modal on overlay click
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
});

// Close modal on ESC key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active').forEach(modal => {
            modal.classList.remove('active');
        });
        document.body.style.overflow = '';
    }
});

// ========================================
// Password Toggle
// ========================================
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const btn = input.nextElementSibling;
    const icon = btn.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// ========================================
// Password Validation
// ========================================
const passwordInput = document.getElementById('password');
const confirmPasswordInput = document.getElementById('confirmPassword');

if (passwordInput) {
    passwordInput.addEventListener('input', validatePassword);
}

if (confirmPasswordInput) {
    confirmPasswordInput.addEventListener('input', checkPasswordMatch);
}

function validatePassword() {
    const password = passwordInput.value;
    
    // Check requirements
    const hasUpper = /[A-Z]/.test(password);
    const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    const hasLength = password.length >= 8;
    
    // Update UI
    updateRequirement('req-upper', hasUpper);
    updateRequirement('req-special', hasSpecial);
    updateRequirement('req-number', hasNumber);
    updateRequirement('req-length', hasLength);
    
    return hasUpper && hasSpecial && hasNumber && hasLength;
}

function updateRequirement(id, isValid) {
    const element = document.getElementById(id);
    if (element) {
        if (isValid) {
            element.classList.add('valid');
        } else {
            element.classList.remove('valid');
        }
    }
}

function checkPasswordMatch() {
    const password = passwordInput.value;
    const confirmPassword = confirmPasswordInput.value;
    const errorElement = document.getElementById('passwordMatchError');
    
    if (confirmPassword && password !== confirmPassword) {
        errorElement.textContent = 'As senhas não coincidem';
        return false;
    } else {
        errorElement.textContent = '';
        return true;
    }
}

// ========================================
// WhatsApp Mask
// ========================================
const whatsappInput = document.getElementById('whatsapp');
if (whatsappInput) {
    whatsappInput.addEventListener('input', (e) => {
        let value = e.target.value.replace(/\D/g, '');
        
        if (value.length > 11) {
            value = value.slice(0, 11);
        }
        
        if (value.length > 0) {
            if (value.length <= 2) {
                value = `(${value}`;
            } else if (value.length <= 7) {
                value = `(${value.slice(0, 2)}) ${value.slice(2)}`;
            } else {
                value = `(${value.slice(0, 2)}) ${value.slice(2, 7)}-${value.slice(7)}`;
            }
        }
        
        e.target.value = value;
    });
}

// ========================================
// Form Submission - Register
// ========================================
async function handleRegister(event) {
    event.preventDefault();
    
    const form = event.target;
    const submitBtn = document.getElementById('registerBtn');
    
    // Get form data
    const formData = {
        fullName: form.fullName.value.trim(),
        whatsapp: form.whatsapp.value.replace(/\D/g, ''),
        email: form.email.value.trim(),
        companyName: form.companyName.value.trim(),
        password: form.password.value,
        confirmPassword: form.confirmPassword.value
    };
    
    // Validate password
    if (!validatePassword()) {
        showNotification('A senha não atende aos requisitos mínimos', 'error');
        return;
    }
    
    // Check password match
    if (formData.password !== formData.confirmPassword) {
        showNotification('As senhas não coincidem', 'error');
        return;
    }
    
    // Validate WhatsApp
    if (formData.whatsapp.length < 10 || formData.whatsapp.length > 11) {
        showNotification('WhatsApp inválido', 'error');
        return;
    }
    
    // Close register modal and show loading
    closeModal('register');
    showLoading();
    
    try {
        // Send registration request
        const response = await fetch('api/register.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        });
        
        const result = await response.json();
        
        hideLoading();
        
        if (result.success) {
            // Store chatwoot URL for redirect
            chatwootUrl = result.data.chatwoot_url;
            
            // Show success modal
            showSuccessModal(result.data);
        } else {
            showNotification(result.message || 'Erro ao criar conta', 'error');
        }
    } catch (error) {
        hideLoading();
        console.error('Registration error:', error);
        showNotification('Erro de conexão. Tente novamente.', 'error');
    }
}

// ========================================
// Form Submission - Login
// ========================================
async function handleLogin(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = {
        email: form.email.value.trim(),
        password: form.password.value
    };
    
    try {
        const response = await fetch('api/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            if (result.data.is_admin) {
                window.location.href = 'admin/';
            } else {
                window.location.href = result.data.chatwoot_url;
            }
        } else {
            showNotification(result.message || 'Credenciais inválidas', 'error');
        }
    } catch (error) {
        console.error('Login error:', error);
        showNotification('Erro de conexão. Tente novamente.', 'error');
    }
}

// ========================================
// Loading Overlay
// ========================================
function showLoading() {
    const overlay = document.getElementById('loadingOverlay');
    overlay.classList.add('active');
    
    // Animate steps
    const steps = ['step1', 'step2', 'step3', 'step4'];
    const messages = [
        'Criando empresa no Chatwoot',
        'Configurando usuário administrador',
        'Integrando WhatsApp',
        'Finalizando configurações'
    ];
    
    let currentStep = 0;
    
    const interval = setInterval(() => {
        if (currentStep > 0) {
            document.getElementById(steps[currentStep - 1]).classList.remove('active');
            document.getElementById(steps[currentStep - 1]).classList.add('completed');
        }
        
        if (currentStep < steps.length) {
            document.getElementById(steps[currentStep]).classList.add('active');
            document.getElementById('loadingMessage').textContent = messages[currentStep];
            currentStep++;
        }
    }, 1500);
    
    // Store interval for cleanup
    overlay.dataset.interval = interval;
}

function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    
    // Clear interval
    if (overlay.dataset.interval) {
        clearInterval(overlay.dataset.interval);
    }
    
    // Reset steps
    ['step1', 'step2', 'step3', 'step4'].forEach(id => {
        const el = document.getElementById(id);
        el.classList.remove('active', 'completed');
    });
    
    overlay.classList.remove('active');
}

// ========================================
// Success Modal
// ========================================
function showSuccessModal(data) {
    const detailsContainer = document.getElementById('successDetails');
    const messageEl = document.getElementById('successMessage');
    
    // Update message based on whatsapp sent
    if (data.whatsapp_sent) {
        messageEl.textContent = 'Seus dados de acesso foram enviados para seu WhatsApp!';
    } else {
        messageEl.textContent = 'Sua conta foi criada com sucesso!';
    }
    
    // Populate details
    detailsContainer.innerHTML = `
        <div class="detail-item">
            <span class="detail-label">URL de Acesso</span>
            <span class="detail-value">${data.chatwoot_url}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Email</span>
            <span class="detail-value">${data.email}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Senha</span>
            <span class="detail-value">${data.password || '(a que você cadastrou)'}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Empresa</span>
            <span class="detail-value">${data.company_name}</span>
        </div>
    `;
    
    openModal('success');
}

function redirectToChatwoot() {
    if (chatwootUrl) {
        window.open(chatwootUrl, '_blank');
    }
    // Não fecha o modal para o usuário poder ver os dados
}

// ========================================
// Notification System
// ========================================
function showNotification(message, type = 'info') {
    // Remove existing notification
    const existing = document.querySelector('.notification');
    if (existing) {
        existing.remove();
    }
    
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="fas ${type === 'error' ? 'fa-exclamation-circle' : type === 'success' ? 'fa-check-circle' : 'fa-info-circle'}"></i>
        <span>${message}</span>
        <button onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
    `;
    
    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'error' ? '#ff4757' : type === 'success' ? '#2ed573' : '#3742fa'};
        color: white;
        padding: 15px 20px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        z-index: 9999;
        animation: slideIn 0.3s ease;
        box-shadow: 0 5px 20px rgba(0,0,0,0.3);
    `;
    
    // Add animation keyframes
    if (!document.querySelector('#notification-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-styles';
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            .notification button {
                background: none;
                border: none;
                color: white;
                cursor: pointer;
                padding: 5px;
                margin-left: 10px;
            }
        `;
        document.head.appendChild(style);
    }
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

// ========================================
// Smooth Scroll for Anchor Links
// ========================================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// ========================================
// Navbar Scroll Effect
// ========================================
let lastScroll = 0;
window.addEventListener('scroll', () => {
    const navbar = document.querySelector('.navbar');
    const currentScroll = window.pageYOffset;
    
    if (currentScroll > 50) {
        navbar.style.background = 'rgba(10, 10, 10, 0.98)';
    } else {
        navbar.style.background = 'rgba(10, 10, 10, 0.9)';
    }
    
    lastScroll = currentScroll;
});

// ========================================
// Initialize
// ========================================
document.addEventListener('DOMContentLoaded', () => {
    console.log('WhatsConnect initialized');
});
