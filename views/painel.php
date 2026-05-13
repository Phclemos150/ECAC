<?php
// 1. CHAMA O CONTROLLER DE AUTENTICAÇÃO
require_once __DIR__ . '/../controllers/AutentController.php';

// 2. BARREIRA DE SEGURANÇA: Exige que o usuário esteja logado.
// Se não estiver, ele é chutado pro login. Se estiver, puxamos os dados.
$usuario = AutentController::verificarAcesso();

// Facilitador: coloca o array de permissões em uma variável curta para usarmos nos menus
$permissoes = $usuario['permissoes'] ?? [];

// Define a foto de perfil (usa a padrão se o usuário não tiver feito upload)
$caminhoFoto = !empty($usuario['foto']) 
    ? '../assets/uploads/fotos_perfil/' . htmlspecialchars($usuario['foto']) 
    : '../assets/img/default-user.png';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel - ECAC 2026</title>

  <link rel="stylesheet" href="../assets/css/painel.css">

  <!-- LUCIDE ICONS -->
  <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>

<!-- HEADER -->
<header class="header">

  <div class="header-left">
    <img src="../assets/img/Só a Logo ECAC 2026.png" class="logo" alt="Logo">
    
    <div class="title">
      <span class="verde">Encontro</span>
      <span class="vermelho">Carioca</span>
      <span class="amarelo">de</span>
      <span class="laranja">Alimentação</span>
      <span class="vescuro">Coletiva</span>
    </div>
  </div>

  <div class="header-right">

    <div class="search-box">
      <input type="text" placeholder="Pesquisar...">
    </div>

    <img src="../assets/img/icone_email_padrao.png" class="icon" alt="Mensagens">

    <div class="user">
      <!-- Exibe a foto e o nome reais do usuário logado -->
      <img src="<?php echo $caminhoFoto; ?>" class="avatar" alt="Avatar do Usuário">
      <span><?php echo htmlspecialchars($usuario['nome']); ?></span>
      <span class="arrow">▾</span>
    </div>

  </div>
</header>

<!-- SIDEBAR -->
<aside class="sidebar">

  <div class="menu-title">Menu</div>

  <!-- VISÃO GERAL (Sempre visível para quem está logado) -->
  <div class="menu-group">
    <div class="menu-item active" data-page="visao-geral">
      <i data-lucide="home"></i>
      <p>Visão Geral</p>
    </div>
  </div>

  <!-- EVENTOS -->
  <?php if (in_array('consultar_evento', $permissoes) || in_array('criar_evento', $permissoes)): ?>
  <div class="menu-group">
    <div class="menu-item" data-page="eventos">
      <i data-lucide="calendar"></i>
      <p>Gestão de Eventos</p>
      <b class="arrow">▾</b>
    </div>

    <div class="submenu">
      <a data-page="lista-eventos">Lista de Eventos</a>
      <a data-page="atividades">Atividades e Programação</a>
      <a data-page="inscricoes">Inscrições</a>
      <a data-page="palestrantes">Palestrantes</a>
      <a data-page="expositores">Expositores</a>
    </div>
  </div>
  <?php endif; ?>

  <!-- USUÁRIOS -->
  <?php if (in_array('gerenciar_usuarios', $permissoes)): ?>
  <div class="menu-group">
    <div class="menu-item" data-page="usuarios">
      <i data-lucide="users"></i>
      <p>Gestão de Usuários</p>
      <b class="arrow">▾</b>
    </div>

    <div class="submenu">
      <a data-page="lista-usuarios">Lista de Usuários</a>
      <a data-page="funcoes">Funções de Usuários</a>
    </div>
  </div>
  <?php endif; ?>

  <!-- ACADÊMICO -->
  <?php if (in_array('acessar_gestao_academica', $permissoes) || in_array('submeter_trabalho', $permissoes)): ?>
  <div class="menu-group">
    <div class="menu-item" data-page="academico">
      <i data-lucide="book-open"></i>
      <p>Gestão Acadêmica</p>
      <b class="arrow">▾</b>
    </div>

    <div class="submenu">
      <a data-page="submissoes">Submissões</a>
      <?php if (in_array('validar_trabalho', $permissoes)): ?>
        <a data-page="avaliacoes">Avaliações</a>
      <?php endif; ?>
      <a data-page="comissao-cientifica">Comissão Científica</a>
      <a data-page="comissao-academica">Comissão Organizadora</a>
    </div>
  </div>
  <?php endif; ?>

  <!-- PATROCINADORES -->
  <?php if (in_array('gerenciar_patrocinador', $permissoes)): ?>
  <div class="menu-group">
    <div class="menu-item" data-page="patrocinio">
      <i data-lucide="dollar-sign"></i>
      <p>Patrocinadores</p>
    </div>
  </div>
  <?php endif; ?>

  <!-- SISTEMA -->
  <?php if (in_array('ver_painel_admin', $permissoes)): ?>
  <div class="menu-group">
    <div class="menu-item" data-page="sistema">
      <i data-lucide="settings"></i>
      <p>Sistema</p>
      <b class="arrow">▾</b>
    </div>

    <div class="submenu">
      <a data-page="certificados">Certificados</a>
      <a data-page="avaliacoes-sistema">Avaliações</a>
      <a data-page="backup">Backup do DB</a>
    </div>
  </div>
  <?php endif; ?>

  <!-- BOTÃO DE SAIR -->
  <div class="menu-group" style="margin-top: auto; padding-top: 20px;">
    <a href="../controllers/AutentController.php?acao=logout" class="menu-item" style="text-decoration: none; color: inherit;">
      <i data-lucide="log-out"></i>
      <p>Sair</p>
    </a>
  </div>

</aside>

<main id="content">
    <!-- O conteúdo de cada página será carregado aqui -->
</main>

<!-- JS -->
<script>
  // Inicializa os ícones
  lucide.createIcons();
</script>
<script src="../assets/js/painel.js"></script>

</body>
</html>