<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

$pdo = getConnection();
$method = $_SERVER['REQUEST_METHOD'];

// GET - Listar todas as configurações
if ($method === 'GET') {
    $stmt = $pdo->query("SELECT * FROM configuracoes");
    $configs = $stmt->fetchAll();
    
    // Converter para formato chave => valor
    $result = [];
    foreach ($configs as $config) {
        $result[$config['chave']] = $config['valor'];
    }
    
    echo json_encode(['success' => true, 'data' => $result]);
    exit;
}

// Verificar autenticação para operações de modificação
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Não autorizado']);
    exit;
}

// PUT - Atualizar configuração
if ($method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $chave = $data['chave'] ?? '';
    $valor = $data['valor'] ?? '';
    
    $stmt = $pdo->prepare("UPDATE configuracoes SET valor = ? WHERE chave = ?");
    $stmt->execute([$valor, $chave]);
    
    echo json_encode(['success' => true]);
    exit;
}

// POST - Atualizar múltiplas configurações
if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    foreach ($data as $chave => $valor) {
        $stmt = $pdo->prepare("UPDATE configuracoes SET valor = ? WHERE chave = ?");
        $stmt->execute([$valor, $chave]);
    }
    
    echo json_encode(['success' => true]);
    exit;
}
?>

