<?php
session_start();

// Expulsa se não estiver logado
if (!isset($_SESSION['user_logado'])) {
    header("Location: ./login.php");
    exit;
}

$usuario = $_SESSION['user_logado']['nome'];
$permissoes = $_SESSION['user_logado']['permissoes'] ?? [];

// PROTEÇÃO EXTRA: Se não tem permissão de ver o painel admin (Ex: Inscrito Comum/Usuário Base), volta pra home
if (!in_array('ver_painel_admin', $permissoes)) {
    header("Location: ./index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel</title>

  <link rel="stylesheet" href="../assets/css/painel.css">

  <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>

<header class="header">
  <div class="header-left">
    <img src="../assets/img/Só a Logo ECAC 2026.png" class="logo">
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
    <img src="../assets/img/icone_email_padrao.png" class="icon">
    <div class="user">
      <img src="../assets/img/default-user.png" class="avatar">
      <span><?php echo htmlspecialchars($usuario); ?></span>
      <span class="arrow">▾</span>
    </div>
  </div>
</header>

<aside class="sidebar">
  <div class="menu-title">Menu</div>

  <div class="menu-group">
    <div class="menu-item active" data-page="visao-geral">
      <i data-lucide="home"></i>
      <p>Visão Geral</p>
    </div>
  </div>

  <?php if (in_array('consultar_evento', $permissoes) || in_array('criar_evento', $permissoes)): ?>
  <div class="menu-group">
    <div class="menu-item" data-page="eventos">
      <i data-lucide="calendar"></i>
      <p>Gestão de Eventos</p>
      <b class="arrow">▾</b>
    </div>

    <div class="submenu">
      <?php if (in_array('criar_evento', $permissoes) || in_array('editar_evento', $permissoes)): ?>
          <a data-page="lista-eventos">Lista de Eventos</a>
      <?php endif; ?>
      <?php if (in_array('gerenciar_agenda', $permissoes)): ?>
          <a data-page="atividades">Atividades e Programação</a>
      <?php endif; ?>
      <?php if (in_array('gerenciar_inscricoes', $permissoes)): ?>
          <a data-page="inscricoes">Inscrições</a>
      <?php endif; ?>
      <?php if (in_array('gerenciar_palestrantes', $permissoes)): ?>
          <a data-page="palestrantes">Palestrantes</a>
          <a data-page="expositores">Expositores</a>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>

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

  <?php if (in_array('acessar_gestao_academica', $permissoes) || in_array('validar_trabalho', $permissoes)): ?>
  <div class="menu-group">
    <div class="menu-item" data-page="academico">
      <i data-lucide="book-open"></i>
      <p>Gestão Acadêmica</p>
      <b class="arrow">▾</b>
    </div>

    <div class="submenu">
      <a data-page="submissoes">Submissões</a>
      <a data-page="avaliacoes">Avaliações</a>
      <a data-page="comissao-cientifica">Comissão Científica</a>
      <a data-page="comissao-academica">Comissão Acadêmica</a>
    </div>
  </div>
  <?php endif; ?>

  <?php if (in_array('gerenciar_patrocinador', $permissoes)): ?>
  <div class="menu-group">
    <div class="menu-item" data-page="patrocinio">
      <i data-lucide="dollar-sign"></i>
      <p>Patrocinadores</p>
    </div>
  </div>
  <?php endif; ?>

  <?php if (in_array('acessar_logs', $permissoes) || in_array('gerenciar_certificados', $permissoes)): ?>
  <div class="menu-group">
    <div class="menu-item" data-page="sistema">
      <i data-lucide="settings"></i>
      <p>Sistema</p>
      <b class="arrow">▾</b>
    </div>

    <div class="submenu">
      <?php if (in_array('gerenciar_certificados', $permissoes)): ?>
          <a data-page="certificados">Certificados</a>
      <?php endif; ?>
      <?php if (in_array('acessar_logs', $permissoes)): ?>
          <a data-page="avaliacoes-sistema">Avaliações do Sistema</a>
          <a data-page="backup">Backup do DB</a>
      <?php endif; ?>
    </div>
  </div>
  <?php endif; ?>

  <div class="menu-group" style="margin-top: 20px;">
    <a href="../controllers/AutentController.php?acao=logout" style="text-decoration: none;">
      <div class="menu-item" style="color: #e74c3c;">
        <i data-lucide="log-out"></i>
        <p>Sair</p>
      </div>
    </a>
  </div>
</aside>

<main id="content"></main>

<script src="../assets/js/painel.js"></script>

</body>
</html>