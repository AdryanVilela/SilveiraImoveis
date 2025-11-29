<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

$pdo = getConnection();
$method = $_SERVER['REQUEST_METHOD'];

// GET - Listar todos os imóveis
if ($method === 'GET') {
    $stmt = $pdo->query("SELECT * FROM imoveis WHERE ativo = 1 ORDER BY ordem ASC, id DESC");
    $imoveis = $stmt->fetchAll();

    // Buscar imagens adicionais para cada imóvel
    foreach ($imoveis as &$imovel) {
        $stmt_imgs = $pdo->prepare("SELECT imagem_url FROM imovel_imagens WHERE imovel_id = ? ORDER BY ordem ASC");
        $stmt_imgs->execute([$imovel['id']]);
        $imagens = $stmt_imgs->fetchAll(PDO::FETCH_COLUMN);
        $imovel['imagens_adicionais'] = implode("\n", $imagens);
    }

    echo json_encode(['success' => true, 'data' => $imoveis]);
    exit;
}

// Verificar autenticação para operações de modificação
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Não autorizado']);
    exit;
}

// POST - Criar novo imóvel
if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $stmt = $pdo->prepare("
        INSERT INTO imoveis (titulo, descricao, localizacao, area, quartos, suites, banheiros, vagas, tipo, status, imagem_url, ordem)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $data['titulo'] ?? '',
        $data['descricao'] ?? '',
        $data['localizacao'] ?? '',
        $data['area'] ?? '',
        $data['quartos'] ?? 0,
        $data['suites'] ?? 0,
        $data['banheiros'] ?? 0,
        $data['vagas'] ?? 0,
        $data['tipo'] ?? 'Residencial',
        $data['status'] ?? 'Lançamento',
        $data['imagem_url'] ?? '',
        $data['ordem'] ?? 0
    ]);

    $imovel_id = $pdo->lastInsertId();

    // Salvar imagens adicionais
    if (!empty($data['imagens_adicionais'])) {
        $imagens = array_filter(explode("\n", $data['imagens_adicionais']));
        $ordem = 1;
        foreach ($imagens as $img_url) {
            $img_url = trim($img_url);
            if (!empty($img_url)) {
                $stmt_img = $pdo->prepare("INSERT INTO imovel_imagens (imovel_id, imagem_url, ordem) VALUES (?, ?, ?)");
                $stmt_img->execute([$imovel_id, $img_url, $ordem++]);
            }
        }
    }

    echo json_encode(['success' => true, 'id' => $imovel_id]);
    exit;
}

// PUT - Atualizar imóvel
if ($method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? 0;

    $stmt = $pdo->prepare("
        UPDATE imoveis SET
            titulo = ?, descricao = ?, localizacao = ?, area = ?,
            quartos = ?, suites = ?, banheiros = ?, vagas = ?,
            tipo = ?, status = ?, imagem_url = ?, ordem = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $data['titulo'] ?? '',
        $data['descricao'] ?? '',
        $data['localizacao'] ?? '',
        $data['area'] ?? '',
        $data['quartos'] ?? 0,
        $data['suites'] ?? 0,
        $data['banheiros'] ?? 0,
        $data['vagas'] ?? 0,
        $data['tipo'] ?? 'Residencial',
        $data['status'] ?? 'Lançamento',
        $data['imagem_url'] ?? '',
        $data['ordem'] ?? 0,
        $id
    ]);

    // Atualizar imagens adicionais
    // Primeiro, deletar todas as imagens antigas
    $pdo->prepare("DELETE FROM imovel_imagens WHERE imovel_id = ?")->execute([$id]);

    // Depois, inserir as novas
    if (!empty($data['imagens_adicionais'])) {
        $imagens = array_filter(explode("\n", $data['imagens_adicionais']));
        $ordem = 1;
        foreach ($imagens as $img_url) {
            $img_url = trim($img_url);
            if (!empty($img_url)) {
                $stmt_img = $pdo->prepare("INSERT INTO imovel_imagens (imovel_id, imagem_url, ordem) VALUES (?, ?, ?)");
                $stmt_img->execute([$id, $img_url, $ordem++]);
            }
        }
    }

    echo json_encode(['success' => true]);
    exit;
}

// DELETE - Deletar imóvel
if ($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? 0;
    
    $stmt = $pdo->prepare("UPDATE imoveis SET ativo = 0 WHERE id = ?");
    $stmt->execute([$id]);
    
    echo json_encode(['success' => true]);
    exit;
}
?>

