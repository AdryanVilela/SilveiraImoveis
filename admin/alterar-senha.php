<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

// Verificar se está logado
requireLogin();

$mensagem = '';
$erro = '';

// Processar alteração de senha
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha_atual = $_POST['senha_atual'] ?? '';
    $senha_nova = $_POST['senha_nova'] ?? '';
    $senha_confirma = $_POST['senha_confirma'] ?? '';
    
    $user = getLoggedUser();
    $pdo = getConnection();
    
    // Buscar usuário
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$user['id']]);
    $usuario = $stmt->fetch();
    
    // Validações
    if (!password_verify($senha_atual, $usuario['senha'])) {
        $erro = 'Senha atual incorreta!';
    } elseif (strlen($senha_nova) < 6) {
        $erro = 'A nova senha deve ter no mínimo 6 caracteres!';
    } elseif ($senha_nova !== $senha_confirma) {
        $erro = 'As senhas não coincidem!';
    } else {
        // Atualizar senha
        $senha_hash = password_hash($senha_nova, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
        $stmt->execute([$senha_hash, $user['id']]);
        
        $mensagem = 'Senha alterada com sucesso!';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Senha - Silveira Imóveis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container-box {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header i {
            font-size: 50px;
            margin-bottom: 10px;
        }
        .body {
            padding: 30px;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
        }
        .btn-secondary {
            border-radius: 10px;
            padding: 12px;
        }
    </style>
</head>
<body>
    <div class="container-box">
        <div class="header">
            <i class="fas fa-key"></i>
            <h2>Alterar Senha</h2>
            <p class="mb-0">Silveira Imóveis</p>
        </div>
        <div class="body">
            <?php if ($mensagem): ?>
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($mensagem) ?>
                </div>
            <?php endif; ?>
            
            <?php if ($erro): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($erro) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Senha Atual</label>
                    <input type="password" class="form-control" name="senha_atual" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Nova Senha</label>
                    <input type="password" class="form-control" name="senha_nova" required minlength="6">
                    <small class="text-muted">Mínimo 6 caracteres</small>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Confirmar Nova Senha</label>
                    <input type="password" class="form-control" name="senha_confirma" required minlength="6">
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Alterar Senha
                    </button>
                    <a href="../index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Voltar ao Site
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if ($mensagem): ?>
    <script>
        Swal.fire({
            title: 'Sucesso!',
            html: '<i class="fas fa-check-circle fa-3x text-success mb-3"></i><br><?= htmlspecialchars($mensagem) ?>',
            icon: 'success',
            confirmButtonText: '<i class="fas fa-check me-2"></i>OK',
            customClass: {
                confirmButton: 'btn btn-success btn-lg px-4',
                popup: 'swal-custom-popup'
            },
            buttonsStyling: false,
            timer: 3000,
            timerProgressBar: true
        }).then(() => {
            window.location.href = '../index.php';
        });
    </script>
    <?php endif; ?>

    <?php if ($erro): ?>
    <script>
        Swal.fire({
            title: 'Erro!',
            html: '<i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i><br><?= htmlspecialchars($erro) ?>',
            icon: 'error',
            confirmButtonText: '<i class="fas fa-check me-2"></i>Tentar Novamente',
            customClass: {
                confirmButton: 'btn btn-danger btn-lg px-4',
                popup: 'swal-custom-popup'
            },
            buttonsStyling: false
        });
    </script>
    <?php endif; ?>
</body>
</html>

