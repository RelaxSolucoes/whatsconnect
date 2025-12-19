# WhatsConnect - Integração Chatwoot + Evolution API

Sistema completo para criação automatizada de contas no Chatwoot com integração WhatsApp via Evolution API.

## 🚀 O que esse sistema faz?

Quando um visitante se cadastra na sua landing page, o sistema automaticamente:

1. ✅ Cria uma empresa no Chatwoot
2. ✅ Cria um usuário administrador
3. ✅ Cria uma instância WhatsApp na Evolution API
4. ✅ Integra tudo automaticamente
5. ✅ Envia os dados de acesso via WhatsApp para o cliente

**Você recebe uma notificação no seu WhatsApp a cada novo cadastro!**

---

## 📋 Requisitos

- **Chatwoot** instalado e funcionando
- **Evolution API** instalada e funcionando
- **Servidor Web** com PHP 7.4+ (Apache ou Nginx)
- **MySQL** (opcional, para salvar cadastros)

---

## 🖥️ INSTALAÇÃO - Teste Local (XAMPP/Laragon)

Para testar no seu computador antes de colocar em produção:

### Passo 1: Copie os arquivos
1. Baixe/extraia o projeto
2. Copie a pasta `projeto` para dentro do `htdocs` (XAMPP) ou `www` (Laragon)
3. Renomeie para `whatsconnect` ou o nome que preferir

### Passo 2: Acesse o sistema
1. Inicie o Apache e MySQL (se for usar banco)
2. Acesse: `http://localhost/whatsconnect`
3. Para o painel admin: `http://localhost/whatsconnect/admin`

### Passo 3: Configure pelo painel admin
1. Faça login com:
   - **Email:** admin@whatsconnect.com
   - **Senha:** admin123
2. Vá em **Credenciais** e preencha os dados do Chatwoot e Evolution API
3. Pronto! Já pode testar o cadastro

---

## 🌐 INSTALAÇÃO - Produção (aaPanel)

### Passo 1: Preparar o domínio

Antes de começar, aponte seu domínio/subdomínio para o servidor:

1. Acesse seu gerenciador de DNS (Cloudflare, Registro.br, etc)
2. Crie um registro **A** apontando para o IP do seu servidor
   - Exemplo: `whatsconnect.seudominio.com` → `123.456.789.0`
3. Aguarde a propagação (geralmente alguns minutos)

### Passo 2: Criar o site no aaPanel

1. No aaPanel, clique em **Site** (ou Website)
2. Clique em **Adicionar Site**
3. Preencha:
   - **Domínio:** whatsconnect.seudominio.com
   - **Banco de dados:** MySQL (se quiser usar banco de dados)
   - **PHP Version:** 7.4 ou superior
4. Clique em **Enviar/Submit**

> 💡 **Dica:** Se criar o banco de dados aqui, anote o **nome do banco** e a **senha** gerada. Geralmente o nome do banco e o usuário são iguais.

### Passo 3: Fazer upload dos arquivos

1. No aaPanel, vá em **Arquivos** (Files)
2. Navegue até: `/www/wwwroot/whatsconnect.seudominio.com`
3. Delete os arquivos padrão (index.html, .htaccess, etc)
4. Clique em **Upload**
5. Selecione todos os arquivos de **dentro da pasta `projeto`** e faça upload

> ⚠️ **Importante:** Faça upload do **conteúdo** da pasta projeto, não a pasta em si!

### Passo 4: Ativar SSL (HTTPS)

1. No aaPanel, vá em **Site**
2. Clique no seu site
3. Vá na aba **SSL**
4. Selecione **Let's Encrypt** (gratuito)
5. Clique em **Aplicar/Apply**

### Passo 5: Verificar permissões

1. No aaPanel, vá em **Arquivos**
2. Navegue até a pasta do seu site
3. Selecione todos os arquivos
4. Clique em **Permissão** (Permission)
5. Confirme que está **755** e o proprietário é **www**

### Passo 6: Acessar e configurar

1. Acesse: `https://whatsconnect.seudominio.com/admin`
2. Faça login:
   - **Email:** admin@whatsconnect.com
   - **Senha:** admin123
3. Configure suas credenciais em **Credenciais**

---

## 🔐 Alterar senha do admin

Por segurança, altere a senha padrão do admin:

1. Faça login no painel admin com a senha padrão (`admin123`)
2. Vá em **Configurações**
3. Na seção **Alterar Senha do Admin**, digite:
   - Senha atual
   - Nova senha
4. Clique em **Alterar Senha**

Pronto! Na próxima vez, use a nova senha para entrar.

---

## 💾 Banco de Dados (Opcional)

O banco de dados é **opcional**. Sem ele, o sistema funciona normalmente, mas não salva histórico de cadastros.

**Com banco de dados você pode:**
- Ver todos os cadastros no painel admin
- Exportar dados dos clientes
- Ter backup dos cadastros

### Configurar banco de dados no aaPanel:

1. Se você criou o banco junto com o site, vá em **Banco de Dados** no aaPanel
2. Localize seu banco e clique em **Importar**
3. Clique em **Carregar do Local** (Upload)
4. Selecione o arquivo `database/schema.sql` do projeto
5. Clique em **Importar**

### Conectar o sistema ao banco:

1. Abra o arquivo `config/config.php`
2. Altere:
```php
define('USE_DATABASE', true);
define('DB_HOST', 'localhost');
define('DB_NAME', 'nome_do_banco');     // Nome que você criou
define('DB_USER', 'usuario_do_banco');   // Geralmente igual ao nome
define('DB_PASS', 'senha_do_banco');     // Senha que foi gerada
```
3. Salve o arquivo

---

## 🤖 Webhook N8N (Opcional)

Se você preferir que o N8N faça o processamento ao invés do PHP:

### Passo 1: Importar o fluxo no N8N

1. No N8N, clique em **Create Workflow** (ou Criar Fluxo)
2. Clique nos **3 pontinhos** (menu)
3. Selecione **Import from File**
4. Escolha o arquivo: `Cria Empresa Chatwoot + Instância Evolution.json` (está na raiz do projeto)

### Passo 2: Configurar o Webhook

Após importar, você verá um nó **Webhook**. Ele tem duas URLs:

| URL | Quando usar |
|-----|-------------|
| **URL de Teste** | Só funciona quando você clica em "Execute Workflow" para testar |
| **URL de Produção** | Só funciona quando o fluxo está **ATIVO** (toggle verde) |

### Passo 3: Ativar no WhatsConnect

1. No N8N, **ative o fluxo** (toggle no canto superior direito)
2. Copie a **URL de Produção** do nó Webhook
3. No admin do WhatsConnect, vá em **Configurações**
4. Ative **Utilizar Webhook Externo**
5. Cole a URL do webhook
6. Salve

### Exemplo de código para o nó "Mapeia Resposta":

Se você precisar ajustar o mapeamento, use este código no nó Code:

```javascript
const webhook = $('Webhook').item.json.body;
const criaEmpresa = $('Cria Empresa').item.json;
const criaUsuario = $('Cria Usuário').item.json;

return {
  json: {
    success: true,
    chatwoot_url: webhook.URL_CHATWOOT,
    email: webhook.Email,
    password: webhook.Senha,
    account_id: criaEmpresa.id,
    user_id: criaUsuario.id
  }
};
```

---

## 🎨 Personalização (White Label)

No painel admin, vá em **Personalização** para:

- **Alterar o nome** do sistema (aparece em toda a landing page)
- **Definir seu WhatsApp** para o botão "Falar com Vendas"
- **Ajustar os preços** dos planos exibidos

### Reset de Fábrica

Se quiser voltar tudo ao padrão original:
1. Vá em **Configurações**
2. Role até **Zona de Perigo**
3. Clique em **Redefinir Configurações de Fábrica**

---

## 📝 Mensagem de Boas-Vindas

A mensagem enviada por WhatsApp pode ser personalizada em **Mensagens** no admin.

**Shortcodes disponíveis:**

| Código | Substitui por |
|--------|---------------|
| `{nome}` | Nome do cliente |
| `{empresa}` | Nome da empresa |
| `{email}` | Email cadastrado |
| `{senha}` | Senha gerada |
| `{url}` | URL do Chatwoot |

---

## ❓ Problemas Comuns

### "Erro ao criar empresa no Chatwoot"
- Verifique se a URL do Chatwoot está correta (sem barra no final)
- Confirme que você está usando o **Platform API Token**, não o token de agente

### "Erro ao criar instância na Evolution"
- Verifique se a API Key está correta
- Confirme que a Evolution API está online

### "Mensagem de WhatsApp não enviou"
- Verifique se a instância está conectada (QR Code escaneado)
- Confirme que o número do cliente está no formato correto (5511999999999)

### "Não consigo fazer login no admin"
- Login padrão: admin@whatsconnect.com / admin123
- Se alterou a senha e esqueceu, edite o `config/config.php` e deixe `ADMIN_PASSWORD_HASH` vazio para resetar

---

## 📄 Licença

MIT License - Use livremente em projetos pessoais e comerciais.

---

Desenvolvido com ❤️ para integração WhatsApp + Chatwoot
