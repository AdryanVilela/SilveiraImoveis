<?php
/**
 * Teste de Login - Diagnóstico
 * Acesse: http://localhost/andre2/test-login.php
 */

echo "<h1>🔐 Teste de Login - Diagnóstico</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
    .success { color: green; padding: 10px; background: #d4edda; border-radius: 5px; margin: 10px 0; }
    .error { color: red; padding: 10px; background: #f8d7da; border-radius: 5px; margin: 10px 0; }
    .info { color: blue; padding: 10px; background: #d1ecf1; border-radius: 5px; margin: 10px 0; }
    .code { background: #2d2d2d; color: #f8f8f2; padding: 15px; border-radius: 5px; margin: 10px 0; font-family: monospace; }
    h2 { color: #333; border-bottom: 2px solid #667eea; padding-bottom: 10px; }
</style>";

// Teste 1: Conexão com banco
echo "<h2>1. Teste de Conexão com Banco</h2>";
try {
    require_once __DIR__ . '/config/database.php';
    $pdo = getConnection();
    echo "<div class='success'>✅ Conexão com banco estabelecida!</div>";
} catch (Exception $e) {
    echo "<div class='error'>❌ Erro na conexão: " . $e->getMessage() . "</div>";
    echo "<div class='info'>💡 Execute o arquivo database.sql no phpMyAdmin primeiro!</div>";
    exit;
}

// Teste 2: Verificar se a tabela usuarios existe
echo "<h2>2. Verificar Tabela de Usuários</h2>";
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'usuarios'");
    if ($stmt->rowCount() > 0) {
        echo "<div class='success'>✅ Tabela 'usuarios' existe</div>";
    } else {
        echo "<div class='error'>❌ Tabela 'usuarios' NÃO existe</div>";
        echo "<div class='info'>💡 Execute o arquivo database.sql no phpMyAdmin!</div>";
        exit;
    }
} catch (Exception $e) {
    echo "<div class='error'>❌ Erro: " . $e->getMessage() . "</div>";
    exit;
}

// Teste 3: Verificar se o usuário admin existe
echo "<h2>3. Verificar Usuário Admin</h2>";
try {
    $stmt = $pdo->query("SELECT * FROM usuarios WHERE usuario = 'admin'");
    $user = $stmt->fetch();
    
    if ($user) {
        echo "<div class='success'>✅ Usuário 'admin' encontrado no banco!</div>";
        echo "<div class='info'>";
        echo "<strong>Dados do usuário:</strong><br>";
        echo "ID: " . $user['id'] . "<br>";
        echo "Usuário: " . $user['usuario'] . "<br>";
        echo "Nome: " . $user['nome'] . "<br>";
        echo "Email: " . $user['email'] . "<br>";
        echo "Hash da senha: " . substr($user['senha'], 0, 30) . "...<br>";
        echo "</div>";
    } else {
        echo "<div class='error'>❌ Usuário 'admin' NÃO encontrado!</div>";
        echo "<div class='info'>💡 Vou criar o usuário agora...</div>";
        
        // Criar usuário admin
        $senha_hash = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, senha, nome, email) VALUES (?, ?, ?, ?)");
        $stmt->execute(['admin', $senha_hash, 'Administrador', 'admin@silveiraimoveis.com.br']);
        
        echo "<div class='success'>✅ Usuário 'admin' criado com sucesso!</div>";
        echo "<div class='info'>Senha: <strong>admin123</strong></div>";
        
        // Buscar novamente
        $stmt = $pdo->query("SELECT * FROM usuarios WHERE usuario = 'admin'");
        $user = $stmt->fetch();
    }
} catch (Exception $e) {
    echo "<div class='error'>❌ Erro: " . $e->getMessage() . "</div>";
    exit;
}

// Teste 4: Testar verificação de senha
echo "<h2>4. Teste de Verificação de Senha</h2>";
$senha_teste = 'admin123';
if (password_verify($senha_teste, $user['senha'])) {
    echo "<div class='success'>✅ Senha 'admin123' está CORRETA!</div>";
} else {
    echo "<div class='error'>❌ Senha 'admin123' está INCORRETA!</div>";
    echo "<div class='info'>💡 Atualizando a senha agora...</div>";

    // Atualizar senha
    $senha_hash = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE usuario = 'admin'");
    $stmt->execute([$senha_hash]);

    echo "<div class='success'>✅ Senha atualizada para 'admin123'</div>";
    echo "<div class='code'>Novo hash: " . $senha_hash . "</div>";

    // Buscar usuário novamente com a senha atualizada
    $stmt = $pdo->query("SELECT * FROM usuarios WHERE usuario = 'admin'");
    $user = $stmt->fetch();

    // Verificar novamente
    if (password_verify($senha_teste, $user['senha'])) {
        echo "<div class='success'>✅ Verificação confirmada! Senha agora está correta!</div>";
    }
}

// Teste 5: Testar função de login
echo "<h2>5. Teste da Função de Login</h2>";
require_once __DIR__ . '/config/auth.php';

if (login('admin', 'admin123')) {
    echo "<div class='success'>✅ Função login() funcionou corretamente!</div>";
    echo "<div class='success'>✅ Sessão criada com sucesso!</div>";
    
    $logged_user = getLoggedUser();
    echo "<div class='info'>";
    echo "<strong>Dados da sessão:</strong><br>";
    echo "ID: " . $logged_user['id'] . "<br>";
    echo "Usuário: " . $logged_user['usuario'] . "<br>";
    echo "Nome: " . $logged_user['nome'] . "<br>";
    echo "</div>";
} else {
    echo "<div class='error'>❌ Função login() FALHOU!</div>";
}

// Teste 6: Verificar sessões PHP
echo "<h2>6. Verificar Sessões PHP</h2>";
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "<div class='success'>✅ Sessões PHP estão funcionando</div>";
    echo "<div class='info'>";
    echo "<strong>Variáveis de sessão:</strong><br>";
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
    echo "</div>";
} else {
    echo "<div class='error'>❌ Problema com sessões PHP</div>";
}

// Resumo final
echo "<h2>📊 Resumo e Próximos Passos</h2>";
echo "<div class='success'>";
echo "<strong>✅ Todos os testes passaram!</strong><br><br>";
echo "<strong>Agora você pode fazer login:</strong><br>";
echo "1. Acesse: <a href='admin/login.php'>admin/login.php</a><br>";
echo "2. Usuário: <strong>admin</strong><br>";
echo "3. Senha: <strong>admin123</strong><br>";
echo "</div>";

echo "<div class='info'>";
echo "<strong>⚠️ Importante:</strong><br>";
echo "- Delete este arquivo (test-login.php) após os testes<br>";
echo "- Se ainda não funcionar, limpe os cookies do navegador (Ctrl+Shift+Delete)<br>";
echo "- Tente em uma aba anônima/privada do navegador<br>";
echo "</div>";

// Botão para testar login
echo "<div style='margin-top: 20px;'>";
echo "<a href='admin/login.php' style='display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 30px; border-radius: 10px; text-decoration: none; font-weight: bold;'>🔐 Ir para Login</a>";
echo "</div>";
?>

