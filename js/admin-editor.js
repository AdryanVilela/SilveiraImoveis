// Sistema de Edição Inline para Administradores
class AdminEditor {
    constructor() {
        this.isAdmin = false;
        this.configureSweetAlert();
        this.init();
    }

    configureSweetAlert() {
        // Configuração global do SweetAlert2 com as cores do sistema
        this.Swal = window.Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-primary btn-lg px-4 me-2',
                cancelButton: 'btn btn-secondary btn-lg px-4',
                popup: 'swal-custom-popup',
                title: 'swal-custom-title',
                htmlContainer: 'swal-custom-text'
            },
            buttonsStyling: false,
            confirmButtonText: '<i class="fas fa-check me-2"></i>Confirmar',
            cancelButtonText: '<i class="fas fa-xmark me-2"></i>Cancelar',
            showClass: {
                popup: 'animate__animated animate__fadeInDown animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp animate__faster'
            }
        });

        // Adicionar estilos customizados do SweetAlert2
        this.addSweetAlertStyles();
    }

    addSweetAlertStyles() {
        const style = document.createElement('style');
        style.textContent = `
            /* SweetAlert2 sempre na frente de tudo */
            .swal2-container {
                z-index: 99999 !important;
            }

            .swal-custom-popup {
                border-radius: 20px !important;
                padding: 30px !important;
                box-shadow: 0 20px 60px rgba(0,0,0,0.3) !important;
                z-index: 99999 !important;
            }

            .swal2-backdrop-show {
                z-index: 99998 !important;
            }

            .swal-custom-title {
                color: #dc3545 !important;
                font-weight: 700 !important;
                font-size: 28px !important;
                margin-bottom: 15px !important;
            }

            .swal-custom-text {
                font-size: 16px !important;
                color: #495057 !important;
            }

            .swal2-icon.swal2-success {
                border-color: #28a745 !important;
                color: #28a745 !important;
            }

            .swal2-icon.swal2-error {
                border-color: #dc3545 !important;
                color: #dc3545 !important;
            }

            .swal2-icon.swal2-warning {
                border-color: #ffc107 !important;
                color: #ffc107 !important;
            }

            .swal2-icon.swal2-info {
                border-color: #17a2b8 !important;
                color: #17a2b8 !important;
            }

            .swal2-icon.swal2-question {
                border-color: #6c757d !important;
                color: #6c757d !important;
            }
        `;
        document.head.appendChild(style);
    }

    init() {
        // Verificar se está logado
        this.checkAuth();

        if (this.isAdmin) {
            this.createFloatingButton();
            this.loadData();
        }
    }

    checkAuth() {
        // Verificar se existe sessão de admin (será setado pelo PHP)
        this.isAdmin = window.isAdminLoggedIn || false;
    }

    createFloatingButton() {
        const button = document.createElement('div');
        button.id = 'admin-floating-btn';
        button.innerHTML = `
            <div class="admin-fab-menu">
                <button class="admin-fab-main" id="adminMainBtn">
                    <i class="fas fa-gear"></i>
                </button>
                <div class="admin-fab-options" id="adminFabOptions">
                    <button class="admin-fab-option" data-action="edit-imoveis" title="Gerenciar Imóveis">
                        <i class="fas fa-building"></i>
                        <span class="admin-fab-label">Imóveis</span>
                    </button>
                    <button class="admin-fab-option" data-action="edit-servicos" title="Gerenciar Serviços">
                        <i class="fas fa-concierge-bell"></i>
                        <span class="admin-fab-label">Serviços</span>
                    </button>
                    <button class="admin-fab-option" data-action="edit-config" title="Configurações">
                        <i class="fas fa-sliders"></i>
                        <span class="admin-fab-label">Configurações</span>
                    </button>
                    <button class="admin-fab-option" data-action="change-password" title="Alterar Senha">
                        <i class="fas fa-user-lock"></i>
                        <span class="admin-fab-label">Senha</span>
                    </button>
                    <button class="admin-fab-option" data-action="logout" title="Sair">
                        <i class="fas fa-right-from-bracket"></i>
                        <span class="admin-fab-label">Sair</span>
                    </button>
                </div>
            </div>
        `;
        document.body.appendChild(button);

        // Adicionar estilos
        this.addStyles();

        // Adicionar eventos
        this.attachFloatingButtonEvents();
    }

    addStyles() {
        const style = document.createElement('style');
        style.textContent = `
            /* Painel Admin Moderno */
            .admin-fab-menu {
                position: fixed;
                bottom: 30px;
                left: 30px;
                z-index: 9999;
            }

            .admin-fab-main {
                width: 70px;
                height: 70px;
                border-radius: 20px;
                background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
                border: none;
                color: white;
                font-size: 28px;
                cursor: pointer;
                box-shadow: 0 8px 32px rgba(220, 53, 69, 0.4);
                backdrop-filter: blur(10px);
                transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                overflow: hidden;
            }

            .admin-fab-main::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
                transition: left 0.5s;
            }

            .admin-fab-main:hover::before {
                left: 100%;
            }

            .admin-fab-main:hover {
                transform: translateY(-5px) scale(1.05);
                box-shadow: 0 12px 40px rgba(220, 53, 69, 0.6);
            }

            .admin-fab-main.active {
                transform: rotate(90deg) scale(1.05);
                border-radius: 50%;
            }

            .admin-fab-options {
                position: absolute;
                bottom: 85px;
                left: 0;
                display: flex;
                flex-direction: column;
                gap: 12px;
                opacity: 0;
                pointer-events: none;
                transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
                transform: translateY(20px);
            }

            .admin-fab-options.show {
                opacity: 1;
                pointer-events: all;
                transform: translateY(0);
            }

            .admin-fab-option {
                width: auto;
                min-width: 60px;
                height: 60px;
                padding: 0 20px;
                border-radius: 30px;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border: 2px solid rgba(220, 53, 69, 0.3);
                color: #dc3545;
                font-size: 20px;
                cursor: pointer;
                box-shadow: 0 4px 20px rgba(0,0,0,0.1);
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                position: relative;
                overflow: hidden;
            }

            .admin-fab-option::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                width: 0;
                height: 0;
                border-radius: 50%;
                background: rgba(220, 53, 69, 0.1);
                transform: translate(-50%, -50%);
                transition: width 0.4s, height 0.4s;
            }

            .admin-fab-option:hover::before {
                width: 300px;
                height: 300px;
            }

            .admin-fab-option:hover {
                background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
                color: white;
                transform: translateX(10px) scale(1.05);
                border-color: #dc3545;
                box-shadow: 0 6px 30px rgba(220, 53, 69, 0.4);
            }

            .admin-fab-label {
                font-size: 14px;
                font-weight: 600;
                letter-spacing: 0.5px;
                white-space: nowrap;
                position: relative;
                z-index: 1;
            }

            .admin-fab-option i {
                position: relative;
                z-index: 1;
            }

            .admin-edit-badge {
                position: absolute;
                top: -10px;
                right: -10px;
                background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
                color: white;
                border-radius: 50%;
                width: 35px;
                height: 35px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 16px;
                cursor: pointer;
                box-shadow: 0 4px 15px rgba(255,107,107,0.4);
                z-index: 10;
                transition: all 0.3s ease;
            }

            .admin-edit-badge:hover {
                transform: scale(1.2) rotate(10deg);
                box-shadow: 0 6px 20px rgba(255,107,107,0.6);
            }

            /* Animação de entrada dos botões */
            @keyframes slideInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .admin-fab-options.show .admin-fab-option {
                animation: slideInUp 0.3s ease forwards;
            }

            .admin-fab-options.show .admin-fab-option:nth-child(1) { animation-delay: 0.05s; }
            .admin-fab-options.show .admin-fab-option:nth-child(2) { animation-delay: 0.1s; }
            .admin-fab-options.show .admin-fab-option:nth-child(3) { animation-delay: 0.15s; }
            .admin-fab-options.show .admin-fab-option:nth-child(4) { animation-delay: 0.2s; }
            .admin-fab-options.show .admin-fab-option:nth-child(5) { animation-delay: 0.25s; }
        `;
        document.head.appendChild(style);
    }

    attachFloatingButtonEvents() {
        const mainBtn = document.getElementById('adminMainBtn');
        const options = document.getElementById('adminFabOptions');

        mainBtn.addEventListener('click', () => {
            mainBtn.classList.toggle('active');
            options.classList.toggle('show');
        });

        // Eventos dos botões de ação
        document.querySelectorAll('.admin-fab-option').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const action = e.currentTarget.dataset.action;
                this.handleAction(action);
            });
        });
    }

    handleAction(action) {
        switch(action) {
            case 'edit-imoveis':
                this.openImoveisManager();
                break;
            case 'edit-servicos':
                this.openServicosManager();
                break;
            case 'edit-config':
                this.openConfigManager();
                break;
            case 'change-password':
                window.location.href = 'admin/alterar-senha.php';
                break;
            case 'logout':
                this.Swal.fire({
                    title: 'Sair do Sistema?',
                    html: '<i class="fas fa-right-from-bracket fa-3x text-warning mb-3"></i><br>Você será desconectado do painel admin.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-right-from-bracket me-2"></i>Sim, sair',
                    cancelButtonText: '<i class="fas fa-xmark me-2"></i>Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'admin/logout.php';
                    }
                });
                break;
        }
    }

    loadData() {
        // Carregar dados será implementado nas próximas partes
        console.log('Admin Editor carregado!');
    }

    // ========== GERENCIADOR DE IMÓVEIS ==========
    openImoveisManager() {
        fetch('api/imoveis.php')
            .then(res => res.json())
            .then(data => {
                this.showImoveisModal(data.data);
            });
    }

    showImoveisModal(imoveis) {
        const modal = this.createModal('Gerenciar Imóveis', this.renderImoveisList(imoveis));
        document.body.appendChild(modal);

        // Adicionar eventos
        this.attachImoveisEvents(modal, imoveis);
    }

    renderImoveisList(imoveis) {
        let html = `
            <div class="admin-modal-toolbar">
                <button class="btn btn-primary btn-lg shadow-sm" id="addImovelBtn">
                    <i class="fas fa-plus-circle me-2"></i>Adicionar Novo Imóvel
                </button>
            </div>
            <div class="admin-imoveis-grid">
        `;

        imoveis.forEach(imovel => {
            html += `
                <div class="admin-imovel-card" data-id="${imovel.id}">
                    <img src="${imovel.imagem_url}" alt="${imovel.titulo}">
                    <div class="admin-imovel-info">
                        <h4><i class="fas fa-building me-2"></i>${imovel.titulo}</h4>
                        <p><i class="fas fa-map-marker-alt me-1"></i> ${imovel.localizacao}</p>
                        <span class="badge bg-gradient" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">${imovel.status}</span>
                    </div>
                    <div class="admin-imovel-actions">
                        <button class="btn btn-sm btn-warning edit-imovel" data-id="${imovel.id}" title="Editar">
                            <i class="fas fa-pen-to-square"></i>
                        </button>
                        <button class="btn btn-sm btn-danger delete-imovel" data-id="${imovel.id}" title="Deletar">
                            <i class="fas fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        html += `</div>`;
        return html;
    }

    attachImoveisEvents(modal, imoveis) {
        // Botão adicionar
        modal.querySelector('#addImovelBtn')?.addEventListener('click', () => {
            this.showImovelForm();
        });

        // Botões editar
        modal.querySelectorAll('.edit-imovel').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.dataset.id;
                const imovel = imoveis.find(i => i.id == id);
                this.showImovelForm(imovel);
            });
        });

        // Botões deletar
        modal.querySelectorAll('.delete-imovel').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.dataset.id;
                this.Swal.fire({
                    title: 'Deletar Imóvel?',
                    html: '<i class="fas fa-trash-can fa-3x text-danger mb-3"></i><br>Esta ação não pode ser desfeita!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-trash me-2"></i>Sim, deletar!',
                    cancelButtonText: '<i class="fas fa-xmark me-2"></i>Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.deleteImovel(id);
                    }
                });
            });
        });
    }

    showImovelForm(imovel = null) {
        const isEdit = imovel !== null;
        const title = isEdit ? 'Editar Imóvel' : 'Adicionar Imóvel';

        const formHtml = `
            <form id="imovelForm">
                <input type="hidden" name="id" value="${imovel?.id || ''}">

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Título</label>
                        <input type="text" class="form-control" name="titulo" value="${imovel?.titulo || ''}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" required>
                            <option value="Lançamento" ${imovel?.status === 'Lançamento' ? 'selected' : ''}>Lançamento</option>
                            <option value="Em Construção" ${imovel?.status === 'Em Construção' ? 'selected' : ''}>Em Construção</option>
                            <option value="Pronto" ${imovel?.status === 'Pronto' ? 'selected' : ''}>Pronto</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Localização</label>
                    <input type="text" class="form-control" name="localizacao" value="${imovel?.localizacao || ''}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descrição</label>
                    <textarea class="form-control" name="descricao" rows="3">${imovel?.descricao || ''}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Área</label>
                        <input type="text" class="form-control" name="area" value="${imovel?.area || ''}" placeholder="Ex: 59,03 m² a 174,56 m²">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tipo</label>
                        <select class="form-select" name="tipo">
                            <option value="Residencial" ${imovel?.tipo === 'Residencial' ? 'selected' : ''}>Residencial</option>
                            <option value="Comercial" ${imovel?.tipo === 'Comercial' ? 'selected' : ''}>Comercial</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Quartos</label>
                        <input type="number" class="form-control" name="quartos" value="${imovel?.quartos || 0}" min="0">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Suítes</label>
                        <input type="number" class="form-control" name="suites" value="${imovel?.suites || 0}" min="0">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Banheiros</label>
                        <input type="number" class="form-control" name="banheiros" value="${imovel?.banheiros || 0}" min="0">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Vagas</label>
                        <input type="number" class="form-control" name="vagas" value="${imovel?.vagas || 0}" min="0">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Imagem Principal (URL)</label>
                    <input type="url" class="form-control" name="imagem_url" value="${imovel?.imagem_url || ''}" placeholder="https://..." required>
                    <small class="text-muted">Esta será a imagem de capa do imóvel</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Imagens Adicionais (URLs - uma por linha)</label>
                    <textarea class="form-control" name="imagens_adicionais" rows="4" placeholder="https://exemplo.com/foto1.jpg&#10;https://exemplo.com/foto2.jpg&#10;https://exemplo.com/foto3.jpg">${imovel?.imagens_adicionais || ''}</textarea>
                    <small class="text-muted">Cole uma URL por linha. Estas imagens aparecerão na galeria do imóvel.</small>
                </div>

                <div class="text-end">
                    <button type="button" class="btn btn-secondary me-2" onclick="this.closest('.admin-modal').remove()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>${isEdit ? 'Atualizar' : 'Adicionar'}
                    </button>
                </div>
            </form>
        `;

        const modal = this.createModal(title, formHtml, 'large');
        document.body.appendChild(modal);

        // Evento de submit
        modal.querySelector('#imovelForm').addEventListener('submit', (e) => {
            e.preventDefault();
            this.saveImovel(new FormData(e.target), isEdit);
        });
    }

    saveImovel(formData, isEdit) {
        const data = Object.fromEntries(formData);
        const url = 'api/imoveis.php';
        const method = isEdit ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                this.Swal.fire({
                    title: 'Sucesso!',
                    html: `<i class="fas fa-check-circle fa-3x text-success mb-3"></i><br>${isEdit ? 'Imóvel atualizado' : 'Imóvel adicionado'} com sucesso!`,
                    icon: 'success',
                    confirmButtonText: '<i class="fas fa-check me-2"></i>OK',
                    timer: 2000,
                    timerProgressBar: true
                }).then(() => {
                    document.querySelectorAll('.admin-modal').forEach(m => m.remove());
                    window.location.href = window.location.href.split('?')[0] + '?t=' + Date.now();
                });
            } else {
                this.Swal.fire({
                    title: 'Erro!',
                    html: `<i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i><br>${result.message || 'Erro desconhecido'}`,
                    icon: 'error',
                    confirmButtonText: '<i class="fas fa-check me-2"></i>OK'
                });
            }
        })
        .catch(error => {
            this.Swal.fire({
                title: 'Erro!',
                html: `<i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i><br>Erro ao salvar imóvel: ${error.message}`,
                icon: 'error',
                confirmButtonText: '<i class="fas fa-check me-2"></i>OK'
            });
            console.error('Erro:', error);
        });
    }

    deleteImovel(id) {
        fetch('api/imoveis.php', {
            method: 'DELETE',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id: id})
        })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                this.Swal.fire({
                    title: 'Deletado!',
                    html: '<i class="fas fa-check-circle fa-3x text-success mb-3"></i><br>Imóvel deletado com sucesso!',
                    icon: 'success',
                    confirmButtonText: '<i class="fas fa-check me-2"></i>OK',
                    timer: 2000,
                    timerProgressBar: true
                }).then(() => {
                    window.location.href = window.location.href.split('?')[0] + '?t=' + Date.now();
                });
            } else {
                this.Swal.fire({
                    title: 'Erro!',
                    html: `<i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i><br>${result.message || 'Erro desconhecido'}`,
                    icon: 'error',
                    confirmButtonText: '<i class="fas fa-check me-2"></i>OK'
                });
            }
        })
        .catch(error => {
            this.Swal.fire({
                title: 'Erro!',
                html: `<i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i><br>Erro ao deletar imóvel: ${error.message}`,
                icon: 'error',
                confirmButtonText: '<i class="fas fa-check me-2"></i>OK'
            });
            console.error('Erro:', error);
        });
    }

    // ========== GERENCIADOR DE SERVIÇOS ==========
    openServicosManager() {
        fetch('api/servicos.php')
            .then(res => res.json())
            .then(data => {
                this.showServicosModal(data.data);
            });
    }

    showServicosModal(servicos) {
        let html = `
            <div class="admin-modal-toolbar">
                <button class="btn btn-primary" id="addServicoBtn">
                    <i class="fas fa-plus me-2"></i>Adicionar Serviço
                </button>
            </div>
            <div class="list-group">
        `;

        servicos.forEach(servico => {
            html += `
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">${servico.titulo}</h5>
                            <p class="mb-1 text-muted">${servico.descricao.substring(0, 100)}...</p>
                            <small>Posição: ${servico.posicao === 'left' ? 'Esquerda' : 'Direita'}</small>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-warning me-2 edit-servico" data-id="${servico.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-servico" data-id="${servico.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        html += `</div>`;

        const modal = this.createModal('Gerenciar Serviços', html);
        document.body.appendChild(modal);

        // Eventos
        modal.querySelector('#addServicoBtn')?.addEventListener('click', () => {
            this.showServicoForm();
        });

        modal.querySelectorAll('.edit-servico').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.dataset.id;
                const servico = servicos.find(s => s.id == id);
                this.showServicoForm(servico);
            });
        });

        modal.querySelectorAll('.delete-servico').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.dataset.id;
                this.Swal.fire({
                    title: 'Deletar Serviço?',
                    html: '<i class="fas fa-trash-can fa-3x text-danger mb-3"></i><br>Esta ação não pode ser desfeita!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '<i class="fas fa-trash me-2"></i>Sim, deletar!',
                    cancelButtonText: '<i class="fas fa-xmark me-2"></i>Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.deleteServico(id);
                    }
                });
            });
        });
    }

    showServicoForm(servico = null) {
        const isEdit = servico !== null;
        const title = isEdit ? 'Editar Serviço' : 'Adicionar Serviço';

        const formHtml = `
            <form id="servicoForm">
                <input type="hidden" name="id" value="${servico?.id || ''}">

                <div class="mb-3">
                    <label class="form-label">Título</label>
                    <input type="text" class="form-control" name="titulo" value="${servico?.titulo || ''}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descrição</label>
                    <textarea class="form-control" name="descricao" rows="4" required>${servico?.descricao || ''}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">URL da Imagem</label>
                        <input type="text" class="form-control" name="imagem_url" value="${servico?.imagem_url || ''}" placeholder="img/service-1.jpg">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Posição</label>
                        <select class="form-select" name="posicao">
                            <option value="left" ${servico?.posicao === 'left' ? 'selected' : ''}>Esquerda</option>
                            <option value="right" ${servico?.posicao === 'right' ? 'selected' : ''}>Direita</option>
                        </select>
                    </div>
                </div>

                <div class="text-end">
                    <button type="button" class="btn btn-secondary me-2" onclick="this.closest('.admin-modal').remove()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>${isEdit ? 'Atualizar' : 'Adicionar'}
                    </button>
                </div>
            </form>
        `;

        const modal = this.createModal(title, formHtml);
        document.body.appendChild(modal);

        modal.querySelector('#servicoForm').addEventListener('submit', (e) => {
            e.preventDefault();
            this.saveServico(new FormData(e.target), isEdit);
        });
    }

    saveServico(formData, isEdit) {
        const data = Object.fromEntries(formData);
        const url = 'api/servicos.php';
        const method = isEdit ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                this.Swal.fire({
                    title: 'Sucesso!',
                    html: `<i class="fas fa-check-circle fa-3x text-success mb-3"></i><br>${isEdit ? 'Serviço atualizado' : 'Serviço adicionado'} com sucesso!`,
                    icon: 'success',
                    confirmButtonText: '<i class="fas fa-check me-2"></i>OK',
                    timer: 2000,
                    timerProgressBar: true
                }).then(() => {
                    document.querySelectorAll('.admin-modal').forEach(m => m.remove());
                    window.location.href = window.location.href.split('?')[0] + '?t=' + Date.now();
                });
            } else {
                this.Swal.fire({
                    title: 'Erro!',
                    html: `<i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i><br>${result.message || 'Erro desconhecido'}`,
                    icon: 'error',
                    confirmButtonText: '<i class="fas fa-check me-2"></i>OK'
                });
            }
        })
        .catch(error => {
            this.Swal.fire({
                title: 'Erro!',
                html: `<i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i><br>Erro ao salvar serviço: ${error.message}`,
                icon: 'error',
                confirmButtonText: '<i class="fas fa-check me-2"></i>OK'
            });
            console.error('Erro:', error);
        });
    }

    deleteServico(id) {
        fetch('api/servicos.php', {
            method: 'DELETE',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id: id})
        })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                this.Swal.fire({
                    title: 'Deletado!',
                    html: '<i class="fas fa-check-circle fa-3x text-success mb-3"></i><br>Serviço deletado com sucesso!',
                    icon: 'success',
                    confirmButtonText: '<i class="fas fa-check me-2"></i>OK',
                    timer: 2000,
                    timerProgressBar: true
                }).then(() => {
                    window.location.href = window.location.href.split('?')[0] + '?t=' + Date.now();
                });
            } else {
                this.Swal.fire({
                    title: 'Erro!',
                    html: `<i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i><br>${result.message || 'Erro desconhecido'}`,
                    icon: 'error',
                    confirmButtonText: '<i class="fas fa-check me-2"></i>OK'
                });
            }
        })
        .catch(error => {
            this.Swal.fire({
                title: 'Erro!',
                html: `<i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i><br>Erro ao deletar serviço: ${error.message}`,
                icon: 'error',
                confirmButtonText: '<i class="fas fa-check me-2"></i>OK'
            });
            console.error('Erro:', error);
        });
    }

    // ========== GERENCIADOR DE CONFIGURAÇÕES ==========
    openConfigManager() {
        fetch('api/configuracoes.php')
            .then(res => res.json())
            .then(data => {
                this.showConfigModal(data.data);
            });
    }

    showConfigModal(configs) {
        const formHtml = `
            <form id="configForm">
                <h5 class="mb-4" style="color: #dc3545; font-weight: 700;">
                    <i class="fas fa-circle-info me-2"></i>Informações Gerais
                </h5>

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-heading me-2 text-muted"></i>Título do Site</label>
                    <input type="text" class="form-control" name="site_titulo" value="${configs.site_titulo || ''}" placeholder="Ex: Silveira Imóveis">
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-align-left me-2 text-muted"></i>Descrição do Site</label>
                    <textarea class="form-control" name="site_descricao" rows="2" placeholder="Breve descrição do site...">${configs.site_descricao || ''}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-file-lines me-2 text-muted"></i>Texto Sobre a Empresa</label>
                    <textarea class="form-control" name="sobre_texto" rows="3" placeholder="História e informações sobre a empresa...">${configs.sobre_texto || ''}</textarea>
                </div>

                <hr class="my-4" style="border-top: 2px solid #dc3545; opacity: 0.2;">
                <h5 class="mb-4" style="color: #dc3545; font-weight: 700;">
                    <i class="fas fa-address-book me-2"></i>Informações de Contato
                </h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="fas fa-location-dot me-2 text-muted"></i>Endereço</label>
                        <input type="text" class="form-control" name="contato_endereco" value="${configs.contato_endereco || ''}" placeholder="Rua, número, bairro...">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="fas fa-phone me-2 text-muted"></i>Telefone</label>
                        <input type="text" class="form-control" name="contato_telefone" value="${configs.contato_telefone || ''}" placeholder="(00) 0000-0000">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="fas fa-envelope me-2 text-muted"></i>E-mail</label>
                        <input type="email" class="form-control" name="contato_email" value="${configs.contato_email || ''}" placeholder="contato@exemplo.com">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="fas fa-clock me-2 text-muted"></i>Horário de Atendimento</label>
                        <input type="text" class="form-control" name="contato_horario" value="${configs.contato_horario || ''}" placeholder="Seg-Sex: 8h-18h">
                    </div>
                </div>

                <hr class="my-4" style="border-top: 2px solid #dc3545; opacity: 0.2;">
                <h5 class="mb-4" style="color: #dc3545; font-weight: 700;">
                    <i class="fab fa-share-nodes me-2"></i>Redes Sociais
                </h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="fab fa-facebook me-2" style="color: #1877f2;"></i>Facebook</label>
                        <input type="url" class="form-control" name="social_facebook" value="${configs.social_facebook || ''}" placeholder="https://facebook.com/...">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="fab fa-instagram me-2" style="color: #e4405f;"></i>Instagram</label>
                        <input type="url" class="form-control" name="social_instagram" value="${configs.social_instagram || ''}" placeholder="https://instagram.com/...">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="fab fa-youtube me-2" style="color: #ff0000;"></i>YouTube</label>
                        <input type="url" class="form-control" name="social_youtube" value="${configs.social_youtube || ''}" placeholder="https://youtube.com/...">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><i class="fab fa-linkedin me-2" style="color: #0a66c2;"></i>LinkedIn</label>
                        <input type="url" class="form-control" name="social_linkedin" value="${configs.social_linkedin || ''}" placeholder="https://linkedin.com/...">
                    </div>
                </div>

                <hr class="my-4" style="border-top: 2px solid #dc3545; opacity: 0.2;">
                <h5 class="mb-4" style="color: #dc3545; font-weight: 700;">
                    <i class="fas fa-images me-2"></i>Página Inicial
                </h5>

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-text-width me-2 text-muted"></i>Título do Carousel</label>
                    <input type="text" class="form-control" name="carousel_titulo" value="${configs.carousel_titulo || ''}" placeholder="Título principal do carousel">
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-paragraph me-2 text-muted"></i>Subtítulo do Carousel</label>
                    <textarea class="form-control" name="carousel_subtitulo" rows="2" placeholder="Subtítulo do carousel...">${configs.carousel_subtitulo || ''}</textarea>
                </div>

                <div class="text-end mt-5 pt-4" style="border-top: 2px solid #e9ecef;">
                    <button type="button" class="btn btn-secondary btn-lg me-3 px-4" onclick="this.closest('.admin-modal').remove()">
                        <i class="fas fa-xmark me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                        <i class="fas fa-floppy-disk me-2"></i>Salvar Configurações
                    </button>
                </div>
            </form>
        `;

        const modal = this.createModal('Configurações do Site', formHtml, 'large');
        document.body.appendChild(modal);

        modal.querySelector('#configForm').addEventListener('submit', (e) => {
            e.preventDefault();
            this.saveConfig(new FormData(e.target));
        });
    }

    saveConfig(formData) {
        const data = Object.fromEntries(formData);

        fetch('api/configuracoes.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                this.Swal.fire({
                    title: 'Sucesso!',
                    html: '<i class="fas fa-check-circle fa-3x text-success mb-3"></i><br>Configurações atualizadas com sucesso!',
                    icon: 'success',
                    confirmButtonText: '<i class="fas fa-check me-2"></i>OK',
                    timer: 2000,
                    timerProgressBar: true
                }).then(() => {
                    document.querySelectorAll('.admin-modal').forEach(m => m.remove());
                    window.location.href = window.location.href.split('?')[0] + '?t=' + Date.now();
                });
            } else {
                this.Swal.fire({
                    title: 'Erro!',
                    html: `<i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i><br>${result.message || 'Erro desconhecido'}`,
                    icon: 'error',
                    confirmButtonText: '<i class="fas fa-check me-2"></i>OK'
                });
            }
        })
        .catch(error => {
            this.Swal.fire({
                title: 'Erro!',
                html: `<i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i><br>Erro ao salvar configurações: ${error.message}`,
                icon: 'error',
                confirmButtonText: '<i class="fas fa-check me-2"></i>OK'
            });
            console.error('Erro:', error);
        });
    }

    // ========== CRIAR MODAL ==========
    createModal(title, content, size = 'medium') {
        const sizeClass = size === 'large' ? 'modal-lg' : '';

        const modal = document.createElement('div');
        modal.className = 'admin-modal';
        modal.innerHTML = `
            <div class="admin-modal-overlay"></div>
            <div class="admin-modal-container ${sizeClass}">
                <div class="admin-modal-header">
                    <h3>${title}</h3>
                    <button class="admin-modal-close">&times;</button>
                </div>
                <div class="admin-modal-body">
                    ${content}
                </div>
            </div>
        `;

        // Adicionar estilos do modal
        this.addModalStyles();

        // Evento de fechar
        modal.querySelector('.admin-modal-close').addEventListener('click', () => {
            modal.remove();
        });

        modal.querySelector('.admin-modal-overlay').addEventListener('click', () => {
            modal.remove();
        });

        return modal;
    }

    addModalStyles() {
        if (document.getElementById('admin-modal-styles')) return;

        const style = document.createElement('style');
        style.id = 'admin-modal-styles';
        style.textContent = `
            .admin-modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 10000;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .admin-modal-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.7);
                backdrop-filter: blur(5px);
            }
            .admin-modal-container {
                position: relative;
                background: white;
                border-radius: 15px;
                max-width: 600px;
                width: 90%;
                max-height: 90vh;
                overflow: hidden;
                box-shadow: 0 10px 50px rgba(0,0,0,0.3);
                animation: modalSlideIn 0.3s ease;
            }
            .admin-modal-container.modal-lg {
                max-width: 900px;
            }
            @keyframes modalSlideIn {
                from {
                    transform: translateY(-50px);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
            .admin-modal-header {
                padding: 25px 30px;
                background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
                color: white;
                display: flex;
                justify-content: space-between;
                align-items: center;
                position: relative;
                overflow: hidden;
            }
            .admin-modal-header::before {
                content: '';
                position: absolute;
                top: -50%;
                right: -50%;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
                animation: headerPulse 3s ease-in-out infinite;
            }
            @keyframes headerPulse {
                0%, 100% { transform: scale(1); opacity: 0.5; }
                50% { transform: scale(1.1); opacity: 0.8; }
            }
            .admin-modal-header h3 {
                margin: 0;
                font-size: 22px;
                font-weight: 700;
                position: relative;
                z-index: 1;
                display: flex;
                align-items: center;
                gap: 12px;
            }
            .admin-modal-header h3::before {
                content: '✨';
                font-size: 24px;
            }
            .admin-modal-close {
                background: rgba(255,255,255,0.2);
                border: 2px solid rgba(255,255,255,0.3);
                color: white;
                font-size: 24px;
                cursor: pointer;
                line-height: 1;
                padding: 0;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
                position: relative;
                z-index: 1;
            }
            .admin-modal-close:hover {
                background: rgba(255,255,255,0.3);
                transform: rotate(90deg) scale(1.1);
            }
            .admin-modal-body {
                padding: 30px;
                max-height: calc(90vh - 100px);
                overflow-y: auto;
                background: linear-gradient(to bottom, #ffffff 0%, #f8f9fa 100%);
            }
            .admin-modal-body::-webkit-scrollbar {
                width: 8px;
            }
            .admin-modal-body::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 10px;
            }
            .admin-modal-body::-webkit-scrollbar-thumb {
                background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
                border-radius: 10px;
            }
            .admin-modal-body::-webkit-scrollbar-thumb:hover {
                background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
            }
            .admin-modal-toolbar {
                margin-bottom: 25px;
                padding-bottom: 20px;
                border-bottom: 3px solid transparent;
                border-image: linear-gradient(90deg, #dc3545 0%, transparent 100%);
                border-image-slice: 1;
            }
            .admin-imoveis-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 25px;
            }
            .admin-imovel-card {
                border: 2px solid #e9ecef;
                border-radius: 16px;
                overflow: hidden;
                transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
                background: white;
                position: relative;
            }
            .admin-imovel-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg, rgba(220, 53, 69, 0.1) 0%, transparent 100%);
                opacity: 0;
                transition: opacity 0.3s ease;
                pointer-events: none;
            }
            .admin-imovel-card:hover::before {
                opacity: 1;
            }
            .admin-imovel-card:hover {
                transform: translateY(-8px) scale(1.02);
                box-shadow: 0 12px 35px rgba(220, 53, 69, 0.2);
                border-color: #dc3545;
            }
            .admin-imovel-card img {
                width: 100%;
                height: 180px;
                object-fit: cover;
                transition: transform 0.4s ease;
            }
            .admin-imovel-card:hover img {
                transform: scale(1.1);
            }
            .admin-imovel-info {
                padding: 18px;
            }
            .admin-imovel-info h4 {
                font-size: 17px;
                font-weight: 700;
                margin-bottom: 10px;
                color: #2c3e50;
            }
            .admin-imovel-info p {
                font-size: 14px;
                color: #6c757d;
                margin-bottom: 10px;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .admin-imovel-info p i {
                color: #dc3545;
            }
            .admin-imovel-actions {
                padding: 12px 18px;
                background: linear-gradient(to right, #f8f9fa 0%, #e9ecef 100%);
                display: flex;
                gap: 12px;
            }
            .admin-imovel-actions button {
                flex: 1;
                border-radius: 8px;
                font-weight: 600;
                transition: all 0.3s ease;
            }
            .admin-imovel-actions button:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            }

            /* Estilos modernos para formulários */
            .admin-modal-body .form-control,
            .admin-modal-body .form-select {
                border: 2px solid #e9ecef;
                border-radius: 10px;
                padding: 12px 16px;
                transition: all 0.3s ease;
                font-size: 15px;
            }
            .admin-modal-body .form-control:focus,
            .admin-modal-body .form-select:focus {
                border-color: #dc3545;
                box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15);
                transform: translateY(-2px);
            }
            .admin-modal-body .form-label {
                font-weight: 600;
                color: #495057;
                margin-bottom: 8px;
                font-size: 14px;
            }
            .admin-modal-body .btn {
                border-radius: 10px;
                padding: 10px 20px;
                font-weight: 600;
                transition: all 0.3s ease;
                border: none;
            }
            .admin-modal-body .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            }
            .admin-modal-body .btn-primary {
                background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            }
            .admin-modal-body .btn-primary:hover {
                background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
            }
            .admin-modal-body .badge {
                padding: 6px 12px;
                border-radius: 8px;
                font-weight: 600;
                font-size: 12px;
            }
        `;
        document.head.appendChild(style);
    }
}

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    new AdminEditor();
});

