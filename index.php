<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/auth.php';

// Carregar configurações
$pdo = getConnection();
$stmt = $pdo->query("SELECT * FROM configuracoes");
$configsArray = $stmt->fetchAll();
$configs = [];
foreach ($configsArray as $config) {
    $configs[$config['chave']] = $config['valor'];
}

// Carregar imóveis
$stmt = $pdo->query("SELECT * FROM imoveis WHERE ativo = 1 ORDER BY ordem ASC, id DESC");
$imoveis = $stmt->fetchAll();

// Carregar serviços
$stmt = $pdo->query("SELECT * FROM servicos WHERE ativo = 1 ORDER BY ordem ASC");
$servicos = $stmt->fetchAll();

// Verificar se está logado
$isAdmin = isLoggedIn();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($configs['site_titulo'] ?? 'Silveira Imoveis - Portal Imobiliário') ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="<?= htmlspecialchars($configs['site_descricao'] ?? '') ?>" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;700&family=Work+Sans:wght@400;600&display=swap"
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

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Custom Real Estate Theme - White & Red -->
    <link href="css/real-estate-custom.css" rel="stylesheet">
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
            <a href="#home" class="navbar-brand ms-4 ms-lg-0">
                <img src="logo.PNG" alt="Silveira Imóveis" class="navbar-logo">
            </a>
            <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav mx-auto p-4 p-lg-0">
                    <a href="#sobre" class="nav-item nav-link">Sobre</a>
                    <a href="#servicos" class="nav-item nav-link">Serviços</a>
                    <a href="#imoveis" class="nav-item nav-link">Imóveis</a>
                    <a href="#contato" class="nav-item nav-link">Contato</a>
                </div>
                <div class="d-none d-lg-flex">
                    <a class="btn btn-outline-primary border-2 btn-anunciar" href="#contato">
                        Anunciar Imóvel
                    </a>
                </div>
            </div>
        </nav>

        <div id="header-carousel" class="carousel slide" data-bs-ride="carousel" data-section="home">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <!-- TODO: Substituir por imagem de fachada de imóvel de alto padrão -->
                    <img class="w-100" src="img/carousel-2.jpg" alt="Imóvel de Alto Padrão">
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="title mx-5 px-5 animated slideInDown">
                            <div class="title-center">
                                <h5>Bem-vindo</h5>
                                <h1 class="display-1"><?= htmlspecialchars($configs['carousel_titulo'] ?? 'Encontre seu Imóvel dos Sonhos') ?></h1>
                            </div>
                        </div>
                        <p class="fs-5 mb-5 animated slideInDown"><?= htmlspecialchars($configs['carousel_subtitulo'] ?? '') ?></p>
                        <a href="#properties"
                            class="btn btn-outline-primary border-2 py-3 px-5 animated slideInDown">Explorar
                            Imóveis</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <!-- TODO: Substituir por imagem de interior de imóvel luxuoso -->
                    <img class="w-100" src="img/carousel-2.jpg" alt="Interior de Imóvel Luxuoso">
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="title mx-5 px-5 animated slideInDown">
                            <div class="title-center">
                                <h5>Bem-vindo</h5>
                                <h1 class="display-1"><?= htmlspecialchars($configs['carousel_titulo'] ?? 'Encontre seu Imóvel dos Sonhos') ?></h1>
                            </div>
                        </div>
                        <p class="fs-5 mb-5 animated slideInDown"><?= htmlspecialchars($configs['carousel_subtitulo'] ?? '') ?></p>
                        <a href="#properties"
                            class="btn btn-outline-primary border-2 py-3 px-5 animated slideInDown">Explorar
                            Imóveis</a>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#header-carousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>



    <div class="container-fluid bg-secondary" id="sobre">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-7 pb-0 pb-lg-5 py-5">
                    <div class="pb-0 pb-lg-5 py-5">
                        <div class="title wow fadeInUp" data-wow-delay="0.1s">
                            <div class="title-left">
                                <h5>História</h5>
                                <h1>Sobre Nossa Imobiliária</h1>
                            </div>
                        </div>
                        <p class="mb-4 wow fadeInUp" data-wow-delay="0.2s"><?= htmlspecialchars($configs['sobre_texto'] ?? '') ?></p>
                        <ul class="list-group list-group-flush mb-5 wow fadeInUp" data-wow-delay="0.3s">
                            <li class="list-group-item bg-dark text-body border-secondary ps-0">
                                <i class="fa fa-check-circle text-primary me-1"></i> Assessoria completa em todo
                                processo de compra e venda
                            </li>
                            <li class="list-group-item bg-dark text-body border-secondary ps-0">
                                <i class="fa fa-check-circle text-primary me-1"></i> Documentação segura e regularizada
                            </li>
                            <li class="list-group-item bg-dark text-body border-secondary ps-0">
                                <i class="fa fa-check-circle text-primary me-1"></i> Melhores oportunidades do mercado
                                imobiliário
                            </li>
                        </ul>
                        <div class="row wow fadeInUp" data-wow-delay="0.4s">
                            <div class="col-6">
                                <a href="contact.html" class="btn btn-outline-primary border-2 py-3 w-100">Anunciar
                                    Imóvel</a>
                            </div>
                            <div class="col-6">
                                <a href="contact.html" class="btn btn-primary py-3 w-100">Agendar Visita</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 wow fadeInUp" data-wow-delay="0.5s">
                    <!-- TODO: Substituir por imagem corporativa (equipe, escritório ou handshake) -->
                    <img class="img-fluid" src="img/about.png" alt="Equipe Silveira Imoveis">
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->


    <!-- Service Start -->
    <div class="container-fluid py-5" id="servicos">
        <div class="container py-5">
            <div class="text-center">
                <div class="title wow fadeInUp" data-wow-delay="0.1s">
                    <div class="title-center">
                        <h5>Serviços</h5>
                        <h1>Como Podemos Ajudar</h1>
                    </div>
                </div>
            </div>
            <?php foreach ($servicos as $servico): ?>
            <div class="service-item service-item-<?= $servico['posicao'] ?>">
                <div class="row g-0 align-items-center">
                    <?php if ($servico['posicao'] === 'left'): ?>
                    <div class="col-md-5">
                        <div class="service-img p-5 wow fadeInRight" data-wow-delay="0.2s">
                            <img class="img-fluid rounded-circle" src="<?= htmlspecialchars($servico['imagem_url']) ?>" alt="<?= htmlspecialchars($servico['titulo']) ?>">
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="service-text px-5 px-md-0 py-md-5 wow fadeInRight" data-wow-delay="0.5s">
                            <h3 class="text-uppercase"><?= htmlspecialchars($servico['titulo']) ?></h3>
                            <p class="mb-4"><?= htmlspecialchars($servico['descricao']) ?></p>
                            <a class="btn btn-outline-primary border-2 px-4" href="service.html">Saiba Mais <i
                                    class="fa fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="col-md-5 order-md-1 text-md-end">
                        <div class="service-img p-5 wow fadeInLeft" data-wow-delay="0.2s">
                            <img class="img-fluid rounded-circle" src="<?= htmlspecialchars($servico['imagem_url']) ?>" alt="<?= htmlspecialchars($servico['titulo']) ?>">
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="service-text px-5 px-md-0 py-md-5 text-md-end wow fadeInLeft" data-wow-delay="0.5s">
                            <h3 class="text-uppercase"><?= htmlspecialchars($servico['titulo']) ?></h3>
                            <p class="mb-4"><?= htmlspecialchars($servico['descricao']) ?></p>
                            <a class="btn btn-outline-primary border-2 px-4" href="service.html">Saiba Mais <i
                                    class="fa fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Service End -->


    <!-- Banner Start -->

    <!-- Banner End -->


    <!-- Search Section Start -->
    <section class="search-section" id="imoveis">
        <div class="container">
            <div class="search-wrapper">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="search-info">
                            <span class="search-badge">Busca</span>
                            <h2 class="search-main-title text-dark">Procurando um imóvel?</h2>
                            <p class="search-tagline">Os melhores imóveis estão aqui</p>
                            <p class="search-description">A Silveira Imóveis oferece soluções de alto padrão para quem
                                deseja realizar o sonho de ter um imóvel. Explore nossos empreendimentos, veja o que
                                mais combina com você e escolha seu novo apartamento em Vitória e Serra, nos melhores
                                bairros e com lazer completo.</p>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="search-box-argo">
                            <h3 class="search-title text-white">Buscar imóveis</h3>
                            <form class="search-form">
                                <div class="row g-3">
                                    <!-- Tipo -->
                                    <div class="col-md-6">
                                        <select class="form-select select2-multi" multiple="multiple" id="tipo-imovel"
                                            data-placeholder="Tipo de Imóvel">
                                            <option value="apartamento">Apartamento</option>
                                            <option value="casa">Casa</option>
                                            <option value="cobertura">Cobertura</option>
                                            <option value="comercial">Comercial</option>
                                        </select>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-6">
                                        <select class="form-select select2-multi" multiple="multiple" id="status"
                                            data-placeholder="Status">
                                            <option value="lancamento">Lançamento</option>
                                            <option value="construcao">Em Construção</option>
                                            <option value="pronto">Pronto para Morar</option>
                                        </select>
                                    </div>

                                    <!-- Localização -->
                                    <div class="col-md-6">
                                        <select class="form-select select2-multi" multiple="multiple" id="localizacao"
                                            data-placeholder="Localização">
                                            <option value="vitoria">Vitória</option>
                                            <option value="serra">Serra</option>
                                            <option value="vila-velha">Vila Velha</option>
                                        </select>
                                    </div>

                                    <!-- Quartos -->
                                    <div class="col-md-6">
                                        <select class="form-select select2-multi" multiple="multiple" id="quartos"
                                            data-placeholder="Quartos">
                                            <option value="1">1 Quarto</option>
                                            <option value="2">2 Quartos</option>
                                            <option value="3">3 Quartos</option>
                                            <option value="4">4+ Quartos</option>
                                        </select>
                                    </div>

                                    <!-- Botão de Busca -->
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn-search-argo">
                                            <i class="bi bi-search me-2"></i>Buscar Imóveis
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Search Section End -->

    <!-- Empreendimentos Section Start -->
    <section class="empreendimentos-section py-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="section-title-argo text-dark" id="properties">Imoveis</h2>
            </div>
            <div class="row g-4">
                <?php
                $delay = 0;
                foreach ($imoveis as $imovel):
                ?>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= $delay ?>">
                    <div class="card-argo">
                        <div class="card-argo-image">
                            <img src="<?= htmlspecialchars($imovel['imagem_url']) ?>"
                                alt="<?= htmlspecialchars($imovel['titulo']) ?>">
                            <div class="card-argo-badge"><?= htmlspecialchars($imovel['status']) ?></div>
                        </div>
                        <div class="card-argo-content">
                            <h3 class="card-argo-title"><?= htmlspecialchars($imovel['titulo']) ?></h3>
                            <p class="card-argo-location">
                                <i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($imovel['localizacao']) ?>
                            </p>
                            <div class="card-argo-details">
                                <?php if ($imovel['area']): ?>
                                <div class="detail-item">
                                    <i class="bi bi-rulers"></i>
                                    <span>Área: <?= htmlspecialchars($imovel['area']) ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if ($imovel['quartos']): ?>
                                <div class="detail-item">
                                    <i class="bi bi-door-closed"></i>
                                    <span>Quartos: <?= $imovel['quartos'] ?> Quarto<?= $imovel['quartos'] > 1 ? 's' : '' ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if ($imovel['suites']): ?>
                                <div class="detail-item">
                                    <i class="bi bi-droplet"></i>
                                    <span>Banheiros: <?= $imovel['suites'] ?> Suíte<?= $imovel['suites'] > 1 ? 's' : '' ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if ($imovel['vagas']): ?>
                                <div class="detail-item">
                                    <i class="bi bi-car-front"></i>
                                    <span>Vaga<?= $imovel['vagas'] > 1 ? 's' : '' ?> na garagem: <?= $imovel['vagas'] ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-argo-footer">
                                <span class="card-argo-type">
                                    <i class="bi bi-house-door"></i> <?= htmlspecialchars($imovel['tipo']) ?>
                                </span>
                                <a href="detalhes.php?id=<?= $imovel['id'] ?>" class="btn-card-argo">Ver Detalhes</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $delay += 100;
                endforeach;
                ?>
            </div>
        </div>
    </section>
    <!-- Empreendimentos Section End -->


    <!-- Testimonial Start -->
    <!--<div class="container-fluid py-5 bg-secondary" id="depoimentos">
        <div class="container py-5">
            <div class="text-center">
                <div class="title wow fadeInUp" data-wow-delay="0.1s">
                    <div class="title-center">
                        <h5>Depoimentos</h5>
                        <h1>O Que Nossos Clientes Dizem</h1>
                    </div>
                </div>
            </div>
            <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.3s">
                <div class="testimonial-item text-center"
                    data-dot="<img class='img-fluid' src='img/testimonial-1.jpg' alt='Maria Silva'>">
                    <p class="fs-5">"Excelente atendimento! Consegui vender meu apartamento em menos de 30 dias pelo
                        valor que esperava. A equipe da Silveira Imoveis é muito profissional e atenciosa. Recomendo!"
                    </p>
                    <h5 class="text-uppercase">Maria Silva</h5>
                    <span class="text-primary">Vendedora - Apartamento Moema</span>
                </div>
                <div class="testimonial-item text-center"
                    data-dot="<img class='img-fluid' src='img/testimonial-2.jpg' alt='João Santos'>">
                    <p class="fs-5">"Encontrei a casa dos meus sonhos com a ajuda da Silveira Imoveis. Todo o processo
                        foi
                        transparente e seguro. A equipe me auxiliou em cada etapa, desde a visita até a assinatura do
                        contrato."</p>
                    <h5 class="text-uppercase">João Santos</h5>
                    <span class="text-primary">Comprador - Casa Vila Madalena</span>
                </div>
                <div class="testimonial-item text-center"
                    data-dot="<img class='img-fluid' src='img/testimonial-3.jpg' alt='Ana Paula'>">
                    <p class="fs-5">"Aluguei minha sala comercial através da Silveira Imoveis e não poderia estar mais
                        satisfeita. Eles cuidaram de toda a burocracia e encontraram um inquilino confiável
                        rapidamente."</p>
                    <h5 class="text-uppercase">Ana Paula</h5>
                    <span class="text-primary">Locadora - Sala Comercial Faria Lima</span>
                </div>
            </div>
        </div>
    </div>-->
    <!-- Testimonial End -->


    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer py-5 wow fadeIn" data-wow-delay="0.1s" id="contato">
        <div class="container py-5">
            <div class="row g-5">
                <!-- Sobre a Empresa -->
                <div class="col-lg-4 col-md-6">
                    <p class="mb-4"><?= htmlspecialchars($configs['site_descricao'] ?? '') ?></p>
                    <div class="d-flex">
                        <a class="btn btn-lg-square btn-outline-primary border-2 me-2" href="<?= htmlspecialchars($configs['social_facebook'] ?? '#') ?>"><i
                                class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-lg-square btn-outline-primary border-2 me-2" href="<?= htmlspecialchars($configs['social_instagram'] ?? '#') ?>"><i
                                class="fab fa-instagram"></i></a>
                        <a class="btn btn-lg-square btn-outline-primary border-2 me-2" href="<?= htmlspecialchars($configs['social_youtube'] ?? '#') ?>"><i
                                class="fab fa-youtube"></i></a>
                        <a class="btn btn-lg-square btn-outline-primary border-2" href="<?= htmlspecialchars($configs['social_linkedin'] ?? '#') ?>"><i
                                class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <!-- Links Rápidos -->
                <div class="col-lg-2 col-md-6">
                    <h4 class="text-white mb-4" style="color: #FFFFFF !important;">Links Rápidos</h4>
                    <a class="btn btn-link" href="#home">Início</a>
                    <a class="btn btn-link" href="#sobre">Sobre Nós</a>
                    <a class="btn btn-link" href="#servicos">Serviços</a>
                    <a class="btn btn-link" href="#imoveis">Imóveis</a>
                    <a class="btn btn-link" href="#depoimentos">Depoimentos</a>
                </div>

                <!-- Serviços -->
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-4" style="color: #FFFFFF !important;">Nossos Serviços</h4>
                    <a class="btn btn-link" href="#servicos">Venda de Imóveis</a>
                    <a class="btn btn-link" href="#servicos">Locação</a>
                    <a class="btn btn-link" href="#servicos">Consultoria</a>
                    <a class="btn btn-link" href="#servicos">Avaliação</a>
                    <a class="btn btn-link" href="#contato">Anunciar Imóvel</a>
                </div>

                <!-- Contato -->
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-4" style="color: #FFFFFF !important;">Entre em Contato</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i><?= htmlspecialchars($configs['contato_endereco'] ?? '') ?></p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i><?= htmlspecialchars($configs['contato_telefone'] ?? '') ?></p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i><?= htmlspecialchars($configs['contato_email'] ?? '') ?></p>
                    <p class="mb-0"><i class="fa fa-clock me-3"></i><?= htmlspecialchars($configs['contato_horario'] ?? '') ?></p>
                </div>
            </div>
        </div>

        <!-- Copyright -->
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

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Initialize Select2 -->
    <script>
        $(document).ready(function () {
            // Initialize Select2
            $('.select2-multi').each(function () {
                $(this).select2({
                    placeholder: $(this).data('placeholder'),
                    allowClear: true,
                    width: '100%'
                });
            });

            // Scroll instantâneo para links do menu
            $('a[href^="#"]').on('click', function (e) {
                e.preventDefault();
                var target = $(this.getAttribute('href'));
                if (target.length) {
                    window.scrollTo({
                        top: target.offset().top - 80,
                        behavior: 'auto'
                    });

                    // Atualizar link ativo
                    $('.nav-link').removeClass('active');
                    $(this).addClass('active');

                    // Fechar menu mobile
                    $('.navbar-collapse').collapse('hide');
                }
            });

            // Efeito de scroll no navbar
            $(window).scroll(function () {
                if ($(this).scrollTop() > 100) {
                    $('#mainNav').addClass('navbar-scrolled');
                } else {
                    $('#mainNav').removeClass('navbar-scrolled');
                }
            });

            // Destacar seção ativa no menu ao scrollar
            $(window).on('scroll', function () {
                var scrollPos = $(document).scrollTop() + 100;
                $('.nav-link').each(function () {
                    var currLink = $(this);
                    var refElement = $(currLink.attr("href"));
                    if (refElement.length && refElement.position().top <= scrollPos && refElement.position().top + refElement.height() > scrollPos) {
                        $('.nav-link').removeClass("active");
                        currLink.addClass("active");
                    }
                });
            });
        });
    </script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Admin Editor (apenas para usuários logados) -->
    <?php if ($isAdmin): ?>
    <script>
        // Definir variável global para o admin-editor.js
        window.isAdminLoggedIn = true;
    </script>
    <script src="js/admin-editor.js"></script>
    <?php endif; ?>
</body>

</html>