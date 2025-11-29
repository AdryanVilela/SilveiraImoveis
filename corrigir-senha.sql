-- Corrigir senha do usuário admin
-- Execute este arquivo no phpMyAdmin ou copie e cole o comando abaixo

USE silveira_imoveis;

-- Deletar usuário admin antigo (se existir)
DELETE FROM usuarios WHERE usuario = 'admin';

-- Criar novo usuário admin com senha correta
-- Usuário: admin
-- Senha: admin123
INSERT INTO usuarios (usuario, senha, nome, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'admin@silveiraimoveis.com.br');

-- Verificar se foi criado
SELECT id, usuario, nome, email FROM usuarios WHERE usuario = 'admin';

