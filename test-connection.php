<?php
/**
 * Arquivo de Teste - Silveira Imóveis
 * Use este arquivo para testar se tudo está configurado corretamente
 * Acesse: http://localhost/andre2/test-connection.php
 */

echo "<h1>🏠 Teste de Configuração - Silveira Imóveis</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
    .success { color: green; padding: 10px; background: #d4edda; border-radius: 5px; margin: 10px 0; }
    .error { color: red; padding: 10px; background: #f8d7da; border-radius: 5px; margin: 10px 0; }
    .info { color: blue; padding: 10px; background: #d1ecf1; border-radius: 5px; margin: 10px 0; }
    h2 { color: #333; border-bottom: 2px solid #667eea; padding-bottom: 10px; }
</style>";

// Teste 1: PHP Version
echo "<h2>1. Versão do PHP</h2>";
if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
    echo "<div class='success'>✅ PHP " . PHP_VERSION . " (OK)</div>";
} else {
    echo "<div class='error'>❌ PHP " . PHP_VERSION . " (Requer PHP 7.0 ou superior)</div>";
}

// Teste 2: Extensões necessárias
echo "<h2>2. Extensões PHP</h2>";
$extensions = ['pdo', 'pdo_mysql', 'session'];
foreach ($extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<div class='success'>✅ Extensão '$ext' está instalada</div>";
    } else {
        echo "<div class='error'>❌ Extensão '$ext' NÃO está instalada</div>";
    }
}

// Teste 3: Conexão com banco de dados
echo "<h2>3. Conexão com Banco de Dados</h2>";
try {
    require_once __DIR__ . '/config/database.php';
    $pdo = getConnection();
    echo "<div class='success'>✅ Conexão com banco de dados estabelecida com sucesso!</div>";
    
    // Testar se as tabelas existem
    $tables = ['usuarios', 'imoveis', 'servicos', 'configuracoes'];
    echo "<h3>Tabelas do Banco:</h3>";
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
            echo "<div class='success'>✅ Tabela '$table' existe ($count registros)</div>";
        } else {
            echo "<div class='error'>❌ Tabela '$table' NÃO existe</div>";
        }
    }
} catch (Exception $e) {
    echo "<div class='error'>❌ Erro na conexão: " . $e->getMessage() . "</div>";
    echo "<div class='info'>💡 Verifique se o MySQL está rodando e se o banco 'silveira_imoveis' foi criado</div>";
}

// Teste 4: Arquivos importantes
echo "<h2>4. Arquivos do Sistema</h2>";
$files = [
    'config/database.php' => 'Configuração do banco',
    'config/auth.php' => 'Sistema de autenticação',
    'admin/login.php' => 'Página de login',
    'api/imoveis.php' => 'API de imóveis',
    'api/servicos.php' => 'API de serviços',
    'api/configuracoes.php' => 'API de configurações',
    'js/admin-editor.js' => 'Editor administrativo',
    'index.php' => 'Página principal'
];

foreach ($files as $file => $desc) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "<div class='success'>✅ $desc ($file)</div>";
    } else {
        echo "<div class='error'>❌ $desc ($file) NÃO encontrado</div>";
    }
}

// Teste 5: Permissões de escrita
echo "<h2>5. Permissões</h2>";
if (is_writable(__DIR__)) {
    echo "<div class='success'>✅ Diretório tem permissão de escrita</div>";
} else {
    echo "<div class='error'>❌ Diretório NÃO tem permissão de escrita</div>";
}

// Teste 6: Sessões
echo "<h2>6. Sessões PHP</h2>";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "<div class='success'>✅ Sessões PHP funcionando</div>";
} else {
    echo "<div class='error'>❌ Problema com sessões PHP</div>";
}

// Resumo
echo "<h2>📊 Resumo</h2>";
echo "<div class='info'>";
echo "<strong>Próximos passos:</strong><br>";
echo "1. Se todos os testes passaram, acesse: <a href='index.php'>index.php</a><br>";
echo "2. Para fazer login como admin: <a href='admin/login.php'>admin/login.php</a><br>";
echo "3. Credenciais padrão: <strong>admin</strong> / <strong>admin123</strong><br>";
echo "4. Após o login, você verá o botão flutuante de engrenagem no site<br>";
echo "5. Delete este arquivo (test-connection.php) após os testes<br>";
echo "</div>";

echo "<hr>";
echo "<p style='text-align: center; color: #666;'>Desenvolvido para Silveira Imóveis | " . date('Y') . "</p>";
?>

