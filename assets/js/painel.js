// ATIVA ÍCONES
lucide.createIcons();

// =========================
// ELEMENTOS
// =========================
const menus = document.querySelectorAll(".menu-group");
const content = document.getElementById("content");

// =========================
// RENDER
// =========================
function renderPage(page) {
  switch (page) {

    case "visao-geral":
  content.innerHTML = `

    <!-- CRONOGRAMA -->
    <div class="vg-card">
      <h2 class="vg-subtitle">CRONOGRAMA DO EVENTO</h2>

      <div class="vg-cronograma">
        <div>Evento<br><strong>2ª Edição do Encontro Carioca de Alimentação Coletiva</strong></div>
        <div>Data<br><strong>8 de outubro de 2026</strong></div>
        <div>Horário<br><strong>08:30 - 16:30</strong></div>
        <div>Modalidade<br><strong>Presencial</strong></div>
        <div>Local<br><strong>Auditório Central</strong></div>
      </div>
    </div>

    <!-- CARDS -->
    <div class="vg-cards">

      <div class="vg-mini-card">
        <span class="vg-badge vg-green">+2,55%</span>
        <p>Total de Usuários</p>
        <h2>1.125</h2>
      </div>

      <div class="vg-mini-card">
        <span class="vg-badge vg-blue">+1,93%</span>
        <p>Total de Inscritos</p>
        <h2>31</h2>
      </div>

      <div class="vg-mini-card">
        <span class="vg-badge vg-orange">+1,42%</span>
        <p>Avaliações Pendentes</p>
        <h2>12</h2>
      </div>

      <div class="vg-mini-card">
        <span class="vg-badge vg-purple">+1,42%</span>
        <p>Time Ativo</p>
        <h2>12</h2>
      </div>

    </div>

    <!-- LINHA 2 -->
    <div class="vg-grid">

      <div class="vg-card">
        <h2 class="vg-subtitle">Últimas Atividades</h2>

        <table class="vg-table">
          <tr>
            <th>Data</th>
            <th>Hora</th>
            <th>Usuário</th>
            <th>Ação</th>
            <th>Detalhes</th>
          </tr>

          <tr><td>14/04</td><td>10:35</td><td>Roberta</td><td>UPDATE</td><td>ID 3</td></tr>
          <tr><td>11/04</td><td>14:23</td><td>Raquel</td><td>CREATE</td><td>ID 15</td></tr>
          <tr><td>02/04</td><td>20:40</td><td>João</td><td>UPDATE</td><td>ID 23</td></tr>
        </table>
      </div>

      <div class="vg-card">
        <h2 class="vg-subtitle">Visão Acadêmica</h2>

        <div class="vg-grafico">
          📊 (gráfico depois)
        </div>
      </div>

    </div>

    <!-- LINHA 3 -->
    <div class="vg-grid">

      <div class="vg-card">
        <h2 class="vg-subtitle">Convites e Confirmações</h2>

        <table class="vg-table">
          <tr>
            <th>Convidados</th>
            <th>Confirmados</th>
            <th>Pendentes</th>
            <th>Contato</th>
          </tr>

          <tr><td>Palestrantes</td><td>13</td><td>5</td><td>Whatsapp</td></tr>
          <tr><td>Expositores</td><td>4</td><td>1</td><td>Email</td></tr>
          <tr><td>Comissão</td><td>8</td><td>11</td><td>Email</td></tr>
        </table>
      </div>

      <div class="vg-card">
        <h2 class="vg-subtitle">Resumo do Sistema</h2>

        <p><strong>Versão:</strong> 3.1.2</p>
        <p><strong>Status:</strong> OK</p>
        <p><strong>Backup:</strong> Completo</p>
        <p><strong>Último backup:</strong> 14/04/2026</p>

      </div>

    </div>

  `;
  break;

    case "patrocinio":
      content.innerHTML = `

    <!-- Seção Patrocinador Master -->
    <section class="partner-section master">
        <div class="section-header">
            <h2><i class="fas fa-crown"></i> PATROCINADOR MASTER</h2>
            <button class="btn-add" onclick="abrirModal('master')"><i class="fas fa-plus"></i> Adicionar</button>
        </div>
        <div class="cards-grid">
            <!-- Os cards entrariam aqui -->
        </div>
    </section>

    <!-- Seção Patrocinadores -->
    <section class="partner-section">
        <div class="section-header">
            <h2><i class="fas fa-users"></i> PATROCINADORES</h2>
            <button class="btn-add" onclick="abrirModal('patrocinador')"><i class="fas fa-plus"></i> Adicionar</button>
        </div>
        <div class="cards-grid">
            <!-- Cards de patrocinadores normais -->
        </div>
    </section>

    <!-- Seção Apoiadores -->
    <section class="partner-section">
        <div class="section-header">
            <h2><i class="fas fa-handshake"></i> APOIADORES</h2>
            <button class="btn-add" onclick="abrirModal('apoiador')"><i class="fas fa-plus"></i> Adicionar</button>
        </div>
        <div class="cards-grid small">
            <!-- Cards menores para apoiadores -->
        </div>
    </section>

      `;
      break;

case "eventos":
content.innerHTML = `
<div class="event-container">
            <div class="header-flex">
                <h2>Lista de Eventos</h2>
                <button class="btn-create">+ Criar Novo Evento</button>
            </div>
            <table class="event-table">
                <thead>
                    <tr>
                        <th>Nome do Evento</th>
                        <th>Data</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>ECAC 2026</td>
                        <td>08/10/2026</td>
                        <td><span class="status-badge status-ativo">ativo</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action btn-ver">Ver</button>
                                <button class="btn-action btn-editar">Editar</button>
                                <button class="btn-action btn-excluir">Excluir</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Workshop Nutrição</td>
                        <td>15/11/2026</td>
                        <td><span class="status-badge status-finalizado">finalizado</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action btn-ver">Ver</button>
                                <button class="btn-action btn-editar">Editar</button>
                                <button class="btn-action btn-excluir">Excluir</button>
                            </div>
                        </td>
                    </tr>
                    <!-- Espaços vazios para mais dois eventos -->
                    <tr class="empty-row"><td></td><td></td><td></td><td></td></tr>
                    <tr class="empty-row"><td></td><td></td><td></td><td></td></tr>
                </tbody>
            </table>
        </div>

       <div class="dashboard-details-grid">
            <div class="left-stack">
                <div class="detail-card">
                    <h3>Inscrições</h3>
                    <div class="placeholder-content">Dados de Inscrições...</div>
                </div>
                <div class="detail-card">
                    <h3>Palestrantes</h3>
                    <div class="placeholder-content">Dados de Palestrantes...</div>
                </div>
                <div class="detail-card">
                    <h3>Expositores</h3>
                    <div class="placeholder-content">Dados de Expositores...</div>
                </div>
            </div>

    <!-- COLUNA DIREITA (Cronograma Único e Alto) -->
    <div class="detail-card crono-card">
        <h3>Cronograma</h3>
        <div class="placeholder-content">
            Visão detalhada da agenda do evento, horários e atividades...
        </div>
    </div>

</div>
    `;
break;
  carregarEventos();
  break;

   case "usuarios":
    content.innerHTML = `
        <!-- Cards Superiores de métricas (mantidos) -->
        <div class="users-summary-grid">
            <div class="stat-card">
                <h3>Total de Usuários</h3>
                <div class="main-number">152</div>
                <div class="sub-info">↑ 12 novos esta semana</div>
            </div>
            <div class="stat-card" style="border-left-color: #1976d2;">
                <h3>Usuários com Cargo</h3>
                <div class="main-number">07</div>
                <div class="sub-info" style="color: #1976d2;">Admins e Equipe</div>
            </div>
        </div>


        <div class="management-layout">
            
            <!-- QUADRADO VERMELHO: Lista de Todos os Usuários -->
            <div class="user-list-container">
                <h2 class="section-title">Lista de Usuários</h2>
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Nickname</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>João Silva</td>
                            <td>joao@exemplo.com</td>
                            <td><span class="nickname-text">@joaosilva</span></td>
                        </tr>
                        <tr>
                            <td>Maria Oliveira</td>
                            <td>maria@exemplo.com</td>
                            <td><span class="nickname-text">@mari_adm</span></td>
                        </tr>
                        <tr>
                            <td>Carlos Santos</td>
                            <td>carlos@exemplo.com</td>
                            <td><span class="nickname-text">@carlito_26</span></td>
                        </tr>
                        <tr>
                            <td>Ana Costa</td>
                            <td>ana@exemplo.com</td>
                            <td><span class="nickname-text">@anacosta_ca</span></td>
                        </tr>
                         <tr>
                            <td>João Silva</td>
                            <td>joao@exemplo.com</td>
                            <td><span class="nickname-text">@joaosilva</span></td>
                        </tr>
                        <tr>
                            <td>Maria Oliveira</td>
                            <td>maria@exemplo.com</td>
                            <td><span class="nickname-text">@mari_adm</span></td>
                        </tr>
                        <tr>
                            <td>Carlos Santos</td>
                            <td>carlos@exemplo.com</td>
                            <td><span class="nickname-text">@carlito_26</span></td>
                        </tr>
                        <tr>
                            <td>Ana Costa</td>
                            <td>ana@exemplo.com</td>
                            <td><span class="nickname-text">@anacosta_ca</span></td>
                        </tr>

                         <tr>
                            <td>João Silva</td>
                            <td>joao@exemplo.com</td>
                            <td><span class="nickname-text">@joaosilva</span></td>
                        </tr>
                        <tr>
                            <td>Maria Oliveira</td>
                            <td>maria@exemplo.com</td>
                            <td><span class="nickname-text">@mari_adm</span></td>
                        </tr>
                       
                    </tbody>
                </table>
            </div>

            <!-- QUADRADO AZUL: Distribuição de Cargos -->
            <div class="roles-container">
                <div class="roles-list-wrapper">
                    <h2 class="section-title">Distribuição de Cargos</h2>
                    <div class="role-item">
                        <span class="role-label">Master (TI)</span>
                        <span class="role-badge badge-master">02</span>
                    </div>
                    <div class="role-item">
                        <span class="role-label">Administradores</span>
                        <span class="role-badge badge-admin">05</span>
                    </div>
                    <div class="role-item">
                        <span class="role-label">Palestrantes</span>
                        <span class="role-badge badge-palestrante">12</span>
                    </div>
                    <div class="role-item">
                        <span class="role-label">Participantes</span>
                        <span class="role-badge badge-user">133</span>
                    </div>
                </div>
                
                <!-- Botão no canto inferior direito do card -->
                <button class="btn-add-role">+ Adicionar Cargo</button>
            </div>
            
        </div>
    `;
    break;

    case "academico":
      content.innerHTML = `

    <h1 class="ge-title">Gestão Acadêmica</h1>
    <p class="ga-subtitle">Visão geral das atividades acadêmicas - Encontro Carioca de Alimentação Coletiva</p>

    <!-- CARDS DE RESUMO -->
    <div class="ga-cards">
        <div class="ga-card">
            <div class="card-header"><i class="far fa-file-alt"></i> Submissões</div>
            <h2>128</h2>
            <span>Total de trabalhos</span>
        </div>
        <div class="ga-card">
            <div class="card-header"><i class="far fa-check-square"></i> Avaliações</div>
            <h2>96</h2>
            <span>Em andamento</span>
        </div>
        <div class="ga-card">
            <div class="card-header"><i class="far fa-id-badge"></i> Aprovados</div>
            <h2>74</h2>
            <span>Trabalhos aprovados</span>
        </div>
        <div class="ga-card">
            <div class="card-header"><i class="far fa-address-book"></i> Comissões</div>
            <h2>5</h2>
            <span>Comissões ativas</span>
        </div>
    </div>

    <!-- SEÇÃO INFERIOR QUADRADA -->
    <div class="bottom-grid-square">
        
        <!-- Bloco do Gráfico Simplificado -->
        <div class="ga-status-section info-box square-card">
            <h3><i class="fas fa-chart-bar"></i> Progresso de Avaliação</h3>
            <div class="chart-container-simple">
                <div class="donut-simple"></div>
                <div class="chart-legend-simple">
                    <div class="legend-item">
                        <span class="dot submetidos"></span> 
                        <strong>128</strong> Submetidos
                    </div>
                    <div class="legend-item">
                        <span class="dot avaliados"></span> 
                        <strong>96</strong> Avaliados
                    </div>
                </div>
            </div>
        </div>

        <!-- Bloco de Prazos -->
        <div class="ga-status-section info-box square-card">
            <div class="box-header">
                <h3><i class="far fa-clock"></i> Próximos Prazos</h3>
                <button class="btn-edit-prazos"><i class="fas fa-edit"></i> Editar</button>
            </div>
            
            <div class="deadline-list-full">
                <div class="deadline-item">
                    <div class="deadline-icon"><i class="far fa-calendar-check"></i></div>
                    <div class="deadline-info">
                        <b>30/04/2026</b>
                        <span>Fim das submissões</span>
                    </div>
                </div>
                <div class="deadline-item">
                    <div class="deadline-icon"><i class="far fa-calendar-check"></i></div>
                    <div class="deadline-info">
                        <b>15/05/2026</b>
                        <span>Divulgação dos resultados</span>
                    </div>
                </div>
                <div class="deadline-item">
                    <div class="deadline-icon"><i class="far fa-calendar-check"></i></div>
                    <div class="deadline-info">
                        <b>30/05/2026</b>
                        <span>Prazo final (versão final)</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
      `;
      break;

    case "sistema":
      content.innerHTML = `
        <h1>Sistema</h1>
        <button class="btn">Salvar Configurações</button>
      `;
      break;

    case "lista-eventos":
      content.innerHTML = `
        <h1>Lista de Eventos</h1>
        <button class="btn">Adicionar Evento</button>
      `;
      break;

    case "inscricoes":
      content.innerHTML = `
        <h1>Inscrições</h1>
        <button class="btn">Nova Inscrição</button>
      `;
      break;

    default:
      content.innerHTML = `<h1>${page}</h1>`;
  }
}

// =========================
// REMOVE ACTIVE
// =========================
function removeActive() {
  document.querySelectorAll(".menu-item").forEach(el => {
    el.classList.remove("active");
  });
}

// =========================
// CLICK NOS MENUS (CORRIGIDO)
// =========================
menus.forEach(menu => {
  const button = menu.querySelector(".menu-item");
  const submenu = menu.querySelector(".submenu");

  button.addEventListener("click", () => {

    const page = button.dataset.page;

    // remove active
    removeActive();
    button.classList.add("active");

    // fecha todos
    menus.forEach(m => m.classList.remove("open"));

    // abre submenu se existir
    if (submenu) {
      menu.classList.add("open");
    }

    // 🔥 SEMPRE renderiza se tiver page
    if (page) {
      renderPage(page);
    }
  });
});

// =========================
// CLICK NOS SUBMENUS
// =========================
document.querySelectorAll(".submenu a").forEach(item => {
  item.addEventListener("click", (e) => {

    e.stopPropagation();

    const page = item.dataset.page;

    const parentMenu = item
      .closest(".menu-group")
      .querySelector(".menu-item");

    removeActive();
    parentMenu.classList.add("active");

    item.closest(".menu-group").classList.add("open");

    renderPage(page);
  });
});

// =========================
// INICIAL
// =========================
renderPage("visao-geral");

function carregarEventos() {

  const eventos = [
    {
      nome: "ECAC 2026",
      data: "08/10/2026",
      status: "ativo"
    },
    {
      nome: "Workshop Nutrição",
      data: "15/11/2026",
      status: "finalizado"
    }
  ];

  const tabela = document.getElementById("tabela-eventos");
  tabela.innerHTML = "";

  eventos.forEach((evento, index) => {

    tabela.innerHTML += `
      <tr>
        <td>${evento.nome}</td>
        <td>${evento.data}</td>
        <td>
          <span class="status ${evento.status}">
            ${evento.status}
          </span>
        </td>
        <td>
          <button class="btn-action view">Ver</button>
          <button class="btn-action edit">Editar</button>
          <button class="btn-action delete">Excluir</button>
        </td>
      </tr>
    `;

  });
}