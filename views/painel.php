<?php
// Exemplo de variável (pode vir do banco depois)
$usuario = "Admin";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel</title>

  <link rel="stylesheet" href="painel.css">

  <!-- LUCIDE ICONS -->
  <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>

<!-- HEADER -->
<header class="header">

  <div class="header-left">
    <img src="img/Só a Logo ECAC 2026.png" class="logo">
    
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

    <img src="img/icone_email_padrao.png" class="icon">

    <div class="user">
      <img src="img/default-user.png" class="avatar">
      <span><?php echo $usuario; ?></span>
      <span class="arrow">▾</span>
    </div>

  </div>
</header>

<!-- SIDEBAR -->
<aside class="sidebar">

  <div class="menu-title">Menu</div>

  <!-- VISÃO GERAL -->
  <div class="menu-group">
    <div class="menu-item active" data-page="visao-geral">
      <i data-lucide="home"></i>
      <p>Visão Geral</p>
    </div>
  </div>

  <!-- EVENTOS -->
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

  <!-- USUÁRIOS -->
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

  <!-- ACADÊMICO -->
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

  <!-- PATROCINADORES -->
  <div class="menu-group">
    <div class="menu-item" data-page="patrocinio">
      <i data-lucide="dollar-sign"></i>
      <p>Patrocinadores</p>
    </div>
  </div>

  <!-- SISTEMA -->
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

</aside>

<main id="content"></main>

<!-- JS -->
<script src="painel.js"></script>

</body>
</html>