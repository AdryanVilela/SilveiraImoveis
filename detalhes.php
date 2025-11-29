<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/auth.php';

// Pegar ID do imóvel da URL
$imovel_id = $_GET['id'] ?? 0;

// Buscar dados do imóvel
$pdo = getConnection();
$stmt = $pdo->prepare("SELECT * FROM imoveis WHERE id = ? AND ativo = 1");
$stmt->execute([$imovel_id]);
$imovel = $stmt->fetch();

// Se não encontrar, redirecionar
if (!$imovel) {
    header('Location: index.php');
    exit;
}

// Buscar imagens adicionais
$stmt_imgs = $pdo->prepare("SELECT imagem_url FROM imovel_imagens WHERE imovel_id = ? ORDER BY ordem ASC");
$stmt_imgs->execute([$imovel_id]);
$imagens_adicionais = $stmt_imgs->fetchAll(PDO::FETCH_COLUMN);

// Buscar configurações
$stmt_config = $pdo->query("SELECT chave, valor FROM configuracoes");
$configs_array = $stmt_config->fetchAll(PDO::FETCH_KEY_PAIR);
$configs = $configs_array;
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($imovel['titulo']) ?> - Silveira Imóveis</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="<?= htmlspecialchars($imovel['titulo']) ?>" name="keywords">
    <meta content="<?= htmlspecialchars($imovel['descricao']) ?>" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <!-- Custom Real Estate Theme -->
    <link href="css/real-estate-custom.css" rel="stylesheet">
    <link href="css/detalhes.css" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-dark position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Header Start -->
    <div class="container-fluid p-0">
        <nav class="navbar navbar-expand-lg navbar-dark px-lg-5 modern-navbar" id="mainNav">
            <a href="index.php#home" class="navbar-brand ms-4 ms-lg-0">
                <img src="logo.PNG" alt="Silveira Imóveis" class="navbar-logo">
            </a>
            <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav mx-auto p-4 p-lg-0">
                    <a href="index.html#home" class="nav-item nav-link">Início</a>
                    <a href="index.html#sobre" class="nav-item nav-link">Sobre</a>
                    <a href="index.html#servicos" class="nav-item nav-link">Serviços</a>
                    <a href="index.html#imoveis" class="nav-item nav-link active">Imóveis</a>
                    <a href="index.html#contato" class="nav-item nav-link">Contato</a>
                </div>
                <div class="d-none d-lg-flex">
                    <a class="btn btn-outline-primary border-2 btn-anunciar" href="index.html#contato">
                        <i class="bi bi-plus-circle me-2"></i>Anunciar Imóvel
                    </a>
                </div>
            </div>
        </nav>
    </div>
    <!-- Header End -->

    <!-- Detalhes do Imóvel Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Início</a></li>
                    <li class="breadcrumb-item"><a href="index.php#imoveis">Imóveis</a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($imovel['titulo']) ?></li>
                </ol>
            </nav>

            <div class="row g-5">
                <!-- Coluna Principal -->
                <div class="col-lg-8">
                    <!-- Galeria de Fotos -->
                    <div class="property-gallery mb-4">
                        <div class="main-image">
                            <img src="<?= htmlspecialchars($imovel['imagem_url']) ?>"
                                alt="<?= htmlspecialchars($imovel['titulo']) ?>" class="img-fluid rounded" id="mainImage">
                            <span class="badge-status"><?= htmlspecialchars($imovel['status']) ?></span>
                        </div>
                        <?php if (!empty($imagens_adicionais)): ?>
                        <div class="thumbnail-images mt-3">
                            <div class="row g-2">
                                <?php foreach ($imagens_adicionais as $img_url): ?>
                                <div class="col-3">
                                    <img src="<?= htmlspecialchars($img_url) ?>"
                                        class="img-fluid rounded thumbnail" alt="Foto do imóvel"
                                        onclick="document.getElementById('mainImage').src = this.src"
                                        style="cursor: pointer;">
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Informações Principais -->
                    <div class="property-header mb-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="property-type-badge" style="color: red;">
                                    <i class="bi bi-house-door me-2"></i><?= htmlspecialchars($imovel['tipo']) ?>
                                </span>
                                <h1 class="property-title mt-2"><?= htmlspecialchars($imovel['titulo']) ?></h1>
                                <p class="property-location">
                                    <i class="bi bi-geo-alt-fill text-danger me-2"></i>
                                    <?= htmlspecialchars($imovel['localizacao']) ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Descrição -->
                    <div class="property-description mb-4">
                        <h3 class="section-title">Sobre o Empreendimento</h3>
                        <p><?= nl2br(htmlspecialchars($imovel['descricao'])) ?></p>
                    </div>

                    <!-- Características -->
                    <div class="property-features mb-4">
                        <h3 class="section-title">Detalhes</h3>
                        <div class="row g-3">
                            <?php if ($imovel['area']): ?>
                            <div class="col-md-3 col-6">
                                <div class="feature-box">
                                    <i class="bi bi-rulers"></i>
                                    <span class="feature-label">Área</span>
                                    <span class="feature-value"><?= htmlspecialchars($imovel['area']) ?></span>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if ($imovel['quartos']): ?>
                            <div class="col-md-3 col-6">
                                <div class="feature-box">
                                    <i class="bi bi-door-closed"></i>
                                    <span class="feature-label">Quartos</span>
                                    <span class="feature-value"><?= $imovel['quartos'] ?> Quarto<?= $imovel['quartos'] > 1 ? 's' : '' ?></span>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if ($imovel['suites']): ?>
                            <div class="col-md-3 col-6">
                                <div class="feature-box">
                                    <i class="bi bi-droplet"></i>
                                    <span class="feature-label">Suítes</span>
                                    <span class="feature-value"><?= $imovel['suites'] ?> Suíte<?= $imovel['suites'] > 1 ? 's' : '' ?></span>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if ($imovel['banheiros']): ?>
                            <div class="col-md-3 col-6">
                                <div class="feature-box">
                                    <i class="bi bi-droplet"></i>
                                    <span class="feature-label">Banheiros</span>
                                    <span class="feature-value"><?= $imovel['banheiros'] ?> Banheiro<?= $imovel['banheiros'] > 1 ? 's' : '' ?></span>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if ($imovel['vagas']): ?>
                            <div class="col-md-3 col-6">
                                <div class="feature-box">
                                    <i class="bi bi-car-front"></i>
                                    <span class="feature-label">Vagas</span>
                                    <span class="feature-value"><?= $imovel['vagas'] ?> Vaga<?= $imovel['vagas'] > 1 ? 's' : '' ?></span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Vídeo do Empreendimento -->
                    <div class="video-box mb-4">
                        <h4 class="video-title">
                            <i class="bi bi-camera-video me-2 "  style="color: red;"></i>Conheça mais
                        </h4>
                        <div class="video-container">
                            <iframe width="100%" height="315" 
                                src="https://www.youtube.com/embed/dQw4w9WgXcQ" 
                                title="Vídeo do Empreendimento" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>

                    <!-- Formulário de Contato -->
                    <div class="contact-form-box">
                        <h4 class="form-title">
                            <i class="fab fa-whatsapp me-2" style="color: #25D366;"></i>Entre em contato
                        </h4>
                        <form class="property-contact-form" id="propertyContactForm">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-user me-2 text-primary"></i>Nome*
                                </label>
                                <input type="text" class="form-control" id="contactNome" placeholder="Seu nome completo" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-envelope me-2 text-primary"></i>E-mail*
                                </label>
                                <input type="email" class="form-control" id="contactEmail" placeholder="seu@email.com" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-phone me-2 text-primary"></i>Telefone*
                                </label>
                                <input type="tel" class="form-control" id="contactTelefone" placeholder="(27) 99999-9999" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-comment-dots me-2 text-primary"></i>Mensagem
                                </label>
                                <textarea class="form-control" id="contactMensagem" rows="4"
                                    placeholder="Gostaria de mais informações sobre este imóvel..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100" style="background: #25D366; border: none; font-weight: 600; padding: 12px;">
                                <i class="fab fa-whatsapp me-2"></i>Enviar para WhatsApp
                            </button>
                        </form>

                        <!-- Informações de Contato -->
                        <div class="contact-info mt-4">
                            <h5 class="mb-3">Ou fale conosco:</h5>
                            <p class="contact-item">
                                <i class="bi bi-phone-fill"></i>
                                <span><?= htmlspecialchars($configs['contato_telefone'] ?? '(27) 3333-4444') ?></span>
                            </p>
                            <p class="contact-item">
                                <i class="bi bi-whatsapp"></i>
                                <span>(27) 99665-1104</span>
                            </p>
                            <p class="contact-item">
                                <i class="bi bi-envelope-fill"></i>
                                <span><?= htmlspecialchars($configs['contato_email'] ?? 'contato@silveiraimoveis.com.br') ?></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Detalhes do Imóvel End -->

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer py-5 wow fadeIn" data-wow-delay="0.1s" id="contato">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-4 col-md-6">
                    <p class="mb-4">Há mais de 15 anos no mercado imobiliário, oferecendo as melhores soluções em
                        compra, venda e locação de imóveis em Vitória e região.</p>
                    <div class="d-flex">
                        <a class="btn btn-lg-square btn-outline-primary border-2 me-2" href="#"><i
                                class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-lg-square btn-outline-primary border-2 me-2" href="#"><i
                                class="fab fa-instagram"></i></a>
                        <a class="btn btn-lg-square btn-outline-primary border-2 me-2" href="#"><i
                                class="fab fa-youtube"></i></a>
                        <a class="btn btn-lg-square btn-outline-primary border-2" href="#"><i
                                class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <h4 class="text-white mb-4" style="color: #FFFFFF !important;">Links Rápidos</h4>
                    <a class="btn btn-link" href="index.html#home">Início</a>
                    <a class="btn btn-link" href="index.html#sobre">Sobre Nós</a>
                    <a class="btn btn-link" href="index.html#servicos">Serviços</a>
                    <a class="btn btn-link" href="index.html#imoveis">Imóveis</a>
                    <a class="btn btn-link" href="index.html#depoimentos">Depoimentos</a>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-4" style="color: #FFFFFF !important;">Nossos Serviços</h4>
                    <a class="btn btn-link" href="index.html#servicos">Venda de Imóveis</a>
                    <a class="btn btn-link" href="index.html#servicos">Locação</a>
                    <a class="btn btn-link" href="index.html#servicos">Consultoria</a>
                    <a class="btn btn-link" href="index.html#servicos">Avaliação</a>
                    <a class="btn btn-link" href="index.html#contato">Anunciar Imóvel</a>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-4" style="color: #FFFFFF !important;">Entre em Contato</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Vitória, ES - Brasil</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>(27) 3333-4444</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>contato@silveiraimoveis.com.br</p>
                    <p class="mb-0"><i class="fa fa-clock me-3"></i>Seg - Sex: 8h às 18h</p>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="#">Silveira Imóveis</a>, Todos os Direitos Reservados.
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        Desenvolvido com <i class="fa fa-heart text-danger"></i> por <a class="border-bottom"
                            href="#">Silveira Imóveis</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-outline-primary border-2 btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            // Trocar imagem principal ao clicar na thumbnail
            $('.thumbnail').click(function () {
                var newSrc = $(this).attr('src').replace('w=300', 'w=1200');
                $('.main-image img').attr('src', newSrc);
            });

            // Scroll suave
            $('a[href^="#"]').on('click', function (e) {
                e.preventDefault();
                var target = $(this.getAttribute('href'));
                if (target.length) {
                    window.scrollTo({
                        top: target.offset().top - 80,
                        behavior: 'auto'
                    });
                }
            });

            // Máscara de telefone
            $('#contactTelefone').on('input', function() {
                let value = $(this).val().replace(/\D/g, '');

                if (value.length <= 11) {
                    value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
                    value = value.replace(/(\d)(\d{4})$/, '$1-$2');
                }

                $(this).val(value);
            });

            // Formulário de Contato - WhatsApp
            $('#propertyContactForm').on('submit', function(e) {
                e.preventDefault();

                // Pegar valores do formulário
                const nome = $('#contactNome').val().trim();
                const email = $('#contactEmail').val().trim();
                const telefone = $('#contactTelefone').val().trim();
                const mensagem = $('#contactMensagem').val().trim();

                // Validar campos obrigatórios
                if (!nome || !email || !telefone) {
                    Swal.fire({
                        title: 'Atenção!',
                        html: '<i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i><br>Por favor, preencha todos os campos obrigatórios!',
                        icon: 'warning',
                        confirmButtonText: '<i class="fas fa-check me-2"></i>OK',
                        customClass: {
                            confirmButton: 'btn btn-warning btn-lg px-4'
                        },
                        buttonsStyling: false
                    });
                    return;
                }

                // Pegar informações do imóvel
                const imovelTitulo = '<?= addslashes($imovel['titulo'] ?? 'Imóvel') ?>';
                const imovelLocalizacao = '<?= addslashes($imovel['localizacao'] ?? '') ?>';
                const imovelPreco = '<?= addslashes($imovel['preco'] ?? '') ?>';
                const imovelUrl = window.location.href;

                // Montar mensagem para WhatsApp
                let textoWhatsApp = `*INTERESSE EM IMÓVEL - SILVEIRA IMÓVEIS*\n\n`;
                textoWhatsApp += `🏠 *Imóvel:* ${imovelTitulo}\n`;
                if (imovelLocalizacao) {
                    textoWhatsApp += `📍 *Localização:* ${imovelLocalizacao}\n`;
                }
                if (imovelPreco) {
                    textoWhatsApp += `💰 *Preço:* ${imovelPreco}\n`;
                }
                textoWhatsApp += `🔗 *Link:* ${imovelUrl}\n\n`;
                textoWhatsApp += `━━━━━━━━━━━━━━━━━━━━\n\n`;
                textoWhatsApp += `👤 *Nome:* ${nome}\n`;
                textoWhatsApp += `📱 *Telefone:* ${telefone}\n`;
                textoWhatsApp += `📧 *E-mail:* ${email}\n\n`;

                if (mensagem) {
                    textoWhatsApp += `💬 *Mensagem:*\n${mensagem}`;
                } else {
                    textoWhatsApp += `💬 *Mensagem:*\nGostaria de mais informações sobre este imóvel.`;
                }

                // Codificar para URL
                const textoEncoded = encodeURIComponent(textoWhatsApp);

                // Número do WhatsApp
                const numeroWhatsApp = '5527996651104';

                // Montar URL do WhatsApp
                const urlWhatsApp = `https://api.whatsapp.com/send/?phone=${numeroWhatsApp}&text=${textoEncoded}&type=phone_number&app_absent=0`;

                // Mostrar confirmação
                Swal.fire({
                    title: 'Enviar para WhatsApp?',
                    html: '<i class="fab fa-whatsapp fa-3x text-success mb-3"></i><br>Você será redirecionado para o WhatsApp com as informações do imóvel',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fab fa-whatsapp me-2"></i>Sim, enviar!',
                    cancelButtonText: '<i class="fas fa-xmark me-2"></i>Cancelar',
                    customClass: {
                        confirmButton: 'btn btn-success btn-lg px-4 me-2',
                        cancelButton: 'btn btn-secondary btn-lg px-4'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Abrir WhatsApp em nova aba
                        window.open(urlWhatsApp, '_blank');

                        // Limpar formulário
                        $('#propertyContactForm')[0].reset();

                        // Mostrar mensagem de sucesso
                        Swal.fire({
                            title: 'Sucesso!',
                            html: '<i class="fas fa-check-circle fa-3x text-success mb-3"></i><br>Redirecionado para o WhatsApp!',
                            icon: 'success',
                            confirmButtonText: '<i class="fas fa-check me-2"></i>OK',
                            customClass: {
                                confirmButton: 'btn btn-success btn-lg px-4'
                            },
                            buttonsStyling: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                    }
                });
            });
        });
    </script>

    <!-- Admin Editor (apenas para usuários logados) -->
    <?php if (isLoggedIn()): ?>
    <script src="js/admin-editor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new AdminEditor();
        });
    </script>
    <?php endif; ?>
</body>

</html>
