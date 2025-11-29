# 🏠 Sistema Administrativo - Silveira Imóveis

## 📋 Instruções de Instalação

### 1️⃣ Configurar o Banco de Dados

1. Abra o **phpMyAdmin** (http://localhost/phpmyadmin)
2. Clique em **"Importar"** ou **"SQL"**
3. Execute o arquivo `database.sql` que está na raiz do projeto
4. Isso criará o banco de dados `silveira_imoveis` com todas as tabelas e dados iniciais

**OU** execute manualmente:
```sql
-- Copie e cole o conteúdo do arquivo database.sql no phpMyAdmin
```

### 2️⃣ Configurar a Conexão com o Banco

Abra o arquivo `config/database.php` e ajuste as credenciais se necessário:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'silveira_imoveis');
define('DB_USER', 'root');
define('DB_PASS', ''); // Senha do MySQL (geralmente vazio no XAMPP)
```

### 3️⃣ Acessar o Sistema

#### 🌐 Site Principal
- Acesse: `http://localhost/andre2/index.php`
- O site mostrará os imóveis, serviços e configurações do banco de dados

#### 🔐 Área Administrativa
1. Acesse: `http://localhost/andre2/admin/login.php`
2. Use as credenciais padrão:
   - **Usuário:** `admin`
   - **Senha:** `admin123`
3. Após o login, você será redirecionado para o site
4. Verá um **botão flutuante de engrenagem** no canto inferior direito

### 4️⃣ Como Usar o Sistema Admin

#### 🎛️ Botão Flutuante (Engrenagem)
Clique no botão de engrenagem para abrir o menu com as opções:

1. **🏠 Gerenciar Imóveis**
   - Adicionar novos imóveis
   - Editar imóveis existentes
   - Deletar imóveis
   - Campos: título, localização, área, quartos, suítes, vagas, status, tipo, imagem

2. **💼 Gerenciar Serviços**
   - Adicionar novos serviços
   - Editar serviços existentes
   - Deletar serviços
   - Campos: título, descrição, imagem, posição (esquerda/direita)

3. **⚙️ Configurações do Site**
   - Editar informações gerais (título, descrição)
   - Editar texto "Sobre a Empresa"
   - Editar informações de contato (endereço, telefone, email, horário)
   - Editar links de redes sociais (Facebook, Instagram, YouTube, LinkedIn)
   - Editar textos do carousel da página inicial

4. **🚪 Sair**
   - Fazer logout do sistema

### 5️⃣ Estrutura de Arquivos

```
andre2/
├── admin/
│   ├── login.php          # Página de login
│   └── logout.php         # Logout
├── api/
│   ├── imoveis.php        # API REST para imóveis
│   ├── servicos.php       # API REST para serviços
│   └── configuracoes.php  # API REST para configurações
├── config/
│   ├── database.php       # Conexão com banco de dados
│   └── auth.php           # Sistema de autenticação
├── js/
│   └── admin-editor.js    # Sistema de edição inline
├── database.sql           # Script SQL para criar o banco
├── index.php              # Página principal (dinâmica)
└── index.html             # Página original (backup)
```

### 6️⃣ Recursos do Sistema

✅ **Login Seguro** - Sistema de autenticação com sessões PHP
✅ **Edição Inline** - Edite tudo diretamente no site sem painel separado
✅ **Interface Moderna** - Botão flutuante com animações suaves
✅ **CRUD Completo** - Criar, Ler, Atualizar e Deletar para todos os recursos
✅ **Responsivo** - Funciona em desktop, tablet e mobile
✅ **API REST** - Endpoints organizados para todas as operações
✅ **Banco de Dados** - MySQL com dados de exemplo incluídos

### 7️⃣ Alterar Senha do Admin

Para alterar a senha padrão, execute no phpMyAdmin:

```sql
UPDATE usuarios 
SET senha = '$2y$10$NOVA_SENHA_HASH_AQUI' 
WHERE usuario = 'admin';
```

Para gerar um novo hash de senha em PHP:
```php
echo password_hash('sua_nova_senha', PASSWORD_DEFAULT);
```

### 8️⃣ Solução de Problemas

**Erro de conexão com banco de dados:**
- Verifique se o XAMPP está rodando (Apache + MySQL)
- Verifique as credenciais em `config/database.php`
- Certifique-se de que o banco `silveira_imoveis` foi criado

**Botão flutuante não aparece:**
- Certifique-se de estar logado em `/admin/login.php`
- Verifique se o arquivo `js/admin-editor.js` está carregando
- Abra o Console do navegador (F12) para ver erros

**Imagens não aparecem:**
- Verifique se as URLs das imagens estão corretas
- Use URLs completas (https://...) ou caminhos relativos (img/...)

### 9️⃣ Próximos Passos

- 📸 Adicione suas próprias imagens de imóveis
- 🎨 Personalize as cores e estilos no CSS
- 📧 Configure formulário de contato funcional
- 🔒 Adicione mais usuários administradores
- 📱 Teste em diferentes dispositivos

---

## 🆘 Suporte

Se tiver dúvidas ou problemas, verifique:
1. Console do navegador (F12 → Console)
2. Logs de erro do PHP (em `xampp/apache/logs/error.log`)
3. Configurações do banco de dados

**Desenvolvido com ❤️ para Silveira Imóveis**

