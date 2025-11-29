<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

$pdo = getConnection();
$method = $_SERVER['REQUEST_METHOD'];

// GET - Listar todos os serviços
if ($method === 'GET') {
    $stmt = $pdo->query("SELECT * FROM servicos WHERE ativo = 1 ORDER BY ordem ASC");
    $servicos = $stmt->fetchAll();
    echo json_encode(['success' => true, 'data' => $servicos]);
    exit;
}

// Verificar autenticação para operações de modificação
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Não autorizado']);
    exit;
}

// POST - Criar novo serviço
if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $stmt = $pdo->prepare("
        INSERT INTO servicos (titulo, descricao, imagem_url, posicao, ordem)
        VALUES (?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $data['titulo'] ?? '',
        $data['descricao'] ?? '',
        $data['imagem_url'] ?? '',
        $data['posicao'] ?? 'left',
        $data['ordem'] ?? 0
    ]);
    
    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    exit;
}

// PUT - Atualizar serviço
if ($method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? 0;
    
    $stmt = $pdo->prepare("
        UPDATE servicos SET 
            titulo = ?, descricao = ?, imagem_url = ?, posicao = ?, ordem = ?
        WHERE id = ?
    ");
    
    $stmt->execute([
        $data['titulo'] ?? '',
        $data['descricao'] ?? '',
        $data['imagem_url'] ?? '',
        $data['posicao'] ?? 'left',
        $data['ordem'] ?? 0,
        $id
    ]);
    
    echo json_encode(['success' => true]);
    exit;
}

// DELETE - Deletar serviço
if ($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? 0;
    
    $stmt = $pdo->prepare("UPDATE servicos SET ativo = 0 WHERE id = ?");
    $stmt->execute([$id]);
    
    echo json_encode(['success' => true]);
    exit;
}
?>

