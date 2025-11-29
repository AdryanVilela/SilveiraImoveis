<?php
// Iniciar sessão se ainda não foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar se o usuário está logado
function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// Obter dados do usuário logado
function getLoggedUser() {
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['admin_id'] ?? null,
            'usuario' => $_SESSION['admin_usuario'] ?? null,
            'nome' => $_SESSION['admin_nome'] ?? null
        ];
    }
    return null;
}

// Fazer login
function login($usuario, $senha) {
    require_once __DIR__ . '/database.php';
    $pdo = getConnection();
    
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ? LIMIT 1");
    $stmt->execute([$usuario]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_usuario'] = $user['usuario'];
        $_SESSION['admin_nome'] = $user['nome'];
        return true;
    }
    
    return false;
}

// Fazer logout
function logout() {
    session_destroy();
    session_start();
}

// Proteger página (redirecionar se não estiver logado)
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: admin/login.php');
        exit;
    }
}
?>

