-- Banco de Dados para Silveira Imóveis
-- Execute este arquivo no phpMyAdmin ou MySQL

CREATE DATABASE IF NOT EXISTS silveira_imoveis CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE silveira_imoveis;

-- Tabela de usuários admin
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserir usuário padrão (usuário: admin, senha: admin123)
INSERT INTO usuarios (usuario, senha, nome, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'admin@silveiraimoveis.com.br');

-- Tabela de imóveis
CREATE TABLE IF NOT EXISTS imoveis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT,
    localizacao VARCHAR(200),
    area VARCHAR(50),
    quartos INT,
    suites INT,
    banheiros INT,
    vagas INT,
    tipo VARCHAR(50), -- Residencial, Comercial, etc
    status VARCHAR(50), -- Lançamento, Em Construção, Pronto
    imagem_url VARCHAR(500),
    ativo BOOLEAN DEFAULT TRUE,
    ordem INT DEFAULT 0,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserir imóveis de exemplo
INSERT INTO imoveis (titulo, descricao, localizacao, area, quartos, suites, banheiros, vagas, tipo, status, imagem_url, ordem) VALUES
('Condomínio Villa Lobos', 'Apartamentos modernos com lazer completo', 'Jardim Camburi, Serra - ES', '59,03 m² a 174,56 m²', 2, 1, 2, 1, 'Residencial', 'Lançamento', 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=800&q=80', 1),
('Edifício Monte Carlo', 'Localização privilegiada na Praia do Canto', 'Praia do Canto, Vitória - ES', '61,72m² a 144,66m²', 2, 1, 2, 1, 'Residencial', 'Em Construção', 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&q=80', 2),
('Condomínio Enseada Blue', 'Condomínio completo pronto para morar', 'Manguinhos, Serra - ES', '78,44m² a 105,23m²', 3, 1, 2, 2, 'Residencial', 'Pronto', 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&q=80', 3);

-- Tabela de serviços
CREATE TABLE IF NOT EXISTS servicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT,
    imagem_url VARCHAR(500),
    posicao VARCHAR(20), -- left, right
    ativo BOOLEAN DEFAULT TRUE,
    ordem INT DEFAULT 0,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserir serviços de exemplo
INSERT INTO servicos (titulo, descricao, imagem_url, posicao, ordem) VALUES
('Venda de Imóveis', 'Assessoria completa para vender seu imóvel pelo melhor preço. Oferecemos avaliação profissional, marketing digital estratégico, visitas qualificadas e negociação especializada. Nossa equipe trabalha para garantir que você alcance o melhor valor de mercado com segurança e agilidade.', 'img/service-1.jpg', 'left', 1),
('Locação Residencial e Comercial', 'Encontre o imóvel perfeito para alugar. Oferecemos apartamentos, casas, salas comerciais e galpões com contratos seguros e suporte completo durante toda a locação. Facilitamos o processo de análise, documentação e entrega do imóvel.', 'img/service-2.jpg', 'right', 2),
('Consultoria Imobiliária', 'Orientação especializada para investimentos imobiliários. Oferecemos análise detalhada de mercado, estudos de viabilidade de projetos e estratégias personalizadas para maximizar seu patrimônio. Conte com nossa experiência para tomar as melhores decisões.', 'img/service-3.jpg', 'left', 3),
('Avaliação Profissional', 'Laudos técnicos e avaliações precisas do valor de mercado do seu imóvel. Essencial para processos de compra, venda, financiamento ou inventário. Nossos profissionais certificados garantem análises confiáveis e detalhadas.', 'img/service-4.jpg', 'right', 4);

-- Tabela de configurações do site
CREATE TABLE IF NOT EXISTS configuracoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chave VARCHAR(100) UNIQUE NOT NULL,
    valor TEXT,
    tipo VARCHAR(50) DEFAULT 'text', -- text, textarea, image, url
    grupo VARCHAR(50), -- contato, sobre, redes_sociais
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserir configurações padrão
INSERT INTO configuracoes (chave, valor, tipo, grupo) VALUES
('site_titulo', 'Silveira Imoveis - Portal Imobiliário', 'text', 'geral'),
('site_descricao', 'Há mais de 15 anos no mercado imobiliário, oferecendo as melhores soluções em compra, venda e locação de imóveis em Vitória e região.', 'textarea', 'sobre'),
('sobre_texto', 'Com mais de 15 anos de experiência no mercado imobiliário, a Silveira Imoveis se consolidou como referência em compra, venda e locação de imóveis. Nossa equipe especializada trabalha com dedicação para conectar pessoas aos seus imóveis dos sonhos, oferecendo transparência, segurança e as melhores oportunidades do mercado.', 'textarea', 'sobre'),
('contato_endereco', 'Vitória, ES - Brasil', 'text', 'contato'),
('contato_telefone', '(27) 3333-4444', 'text', 'contato'),
('contato_email', 'contato@silveiraimoveis.com.br', 'text', 'contato'),
('contato_horario', 'Seg - Sex: 8h às 18h', 'text', 'contato'),
('social_facebook', '#', 'url', 'redes_sociais'),
('social_instagram', '#', 'url', 'redes_sociais'),
('social_youtube', '#', 'url', 'redes_sociais'),
('social_linkedin', '#', 'url', 'redes_sociais'),
('logo_url', 'logo.PNG', 'image', 'geral'),
('carousel_titulo', 'Encontre seu Imóvel dos Sonhos', 'text', 'home'),
('carousel_subtitulo', 'Há mais de 15 anos conectando pessoas aos melhores imóveis. Compra, venda e locação com segurança e transparência.', 'textarea', 'home');

-- Tabela de imagens de imóveis (galeria)
CREATE TABLE IF NOT EXISTS imovel_imagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    imovel_id INT NOT NULL,
    imagem_url VARCHAR(500) NOT NULL,
    ordem INT DEFAULT 0,
    FOREIGN KEY (imovel_id) REFERENCES imoveis(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

