<?php
session_start();

$user = $_SESSION["user_logado"] ?? null;

$id_usuario = $user["id"] ?? null;
$nome_usuario = $user["nome"] ?? null;
$email = $user["email"] ?? null;
$foto = $user["foto"] ?? null;

$logado = (bool) $user;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Encontro Carioca de Alimentação Coletiva</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="website icon" type="png" href="../assets/img/logo.png">
  <link rel="stylesheet" href="../assets/css/incrição.css">
</head>
<body>
  <header>
    <div class="header-content">
      <a href="./index.php">
        <h1>Encontro Carioca de Alimentação Coletiva</h1>
      </a>
      <div class="header-buttons">
        <?php if (!$logado): ?>
          <a href="./login.php"><button class="btn-login">Login</button></a>
          <a href="./cadastro.php"><button class="btn-cadastro">Cadastro</button></a>
        <?php else: ?>
          <?php
          $caminho_perfil = (!empty($foto))
            ? "../assets/uploads/fotos_perfil/" . $foto
            : "../assets/img/default-user.png";
          ?>
          <div class="user-dropdown">
            <div class="user-profile" onclick="ativarDropdown()">
              <div class="img-wrapper">
                <img src="<?php echo $caminho_perfil; ?>" alt="Perfil" class="img-perfil-nav">
              </div>
              <i class="fa fa-chevron-down arrow-icon"></i>
            </div>
            <div id="dropdownMenu" class="dropdown-content">
              <div class="dropdown-header">
                <strong>Olá, <?php echo htmlspecialchars($nome_usuario); ?>!</strong>
              </div>
              <a href="./perfil.php"><i class="fa fa-user"></i> Meu Perfil</a>
              <a href="./minhas-inscricoes.php"><i class="fa fa-ticket"></i> Inscrições</a>
              <hr>
              <a href="../controllers/AutentController.php?acao=logout" class="logout-item">
                <i class="fa fa-sign-out-alt"></i> Sair
              </a>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </header>
  <div class="layout">
    <div class="sidebar">
      <div style="background:#f1eada;padding:25px;text-align:center;border-bottom:1px solid #ccc;">
        <div class="logo"><img src="../assets/img/icon planta.png" alt=""></div>
        <strong>E.C.A.C</strong><br>
      </div>
      <a href="./index.php">
        <div class="sidebar-item"><i class="fa fa-home"></i> Início </div>
      </a>
      <a href="./eventos.php">
        <div class="sidebar-item"><i class="fa fa-calendar-check"></i> Eventos </div>
      </a>
      <a href="./local.php">
        <div class="sidebar-item"><i class="fa fa-map-marker-alt"></i> Local do Evento </div>
      </a>
      <a href="./contato.php">
        <div class="sidebar-item"><i class="fa fa-phone"></i> Contato </div>
      </a>
      <a href="./normas.php">
        <div class="sidebar-item"><i class="fa fa-shield"></i> Normas </div>
      </a>
      <a href="./arquivos.php">
        <div class="sidebar-item"><i class="fa fa-download"></i> Arquivos </div>
      </a>
      <a href="./inscricao.php">
        <div class="sidebar-item active"><i class="fa fa-id-card"></i> Inscrição </div>
      </a>
      <a href="./submissao.php">
        <div class="sidebar-item"><i class="fa fa-upload"></i> Submissão </div>
      </a>
    </div>
    <div class="page-content">
      <div class="content-wrapper">
        <h2>Inscrição</h2>
        <p class="subtitle">
          Escolha um dos eventos abaixo para realizar sua inscrição.
        </p>
        <div class="cards">
          <div class="card">
            <div class="card-banner">*Banner do evento*</div>
            <div class="card-body">
              <h3>Palestra: Gestão de Estoque</h3>
              <p><i class="fa fa-calendar"></i> Período de inscrição: <strong>01/05/2024</strong> até
                <strong>31/05/2024</strong>
              </p>
            </div>
            <div class="card-footer">
              <button class="btn-inscrever">INSCREVA-SE</button>
            </div>
          </div>
          <div class="card">
            <div class="card-banner">*Banner do evento*</div>
            <div class="card-body">
              <h3>E-Book: Tendências Nutricionais</h3>
              <p><i class="fa fa-calendar"></i> Período de inscrição: <strong>01/04/2024</strong> até
                <strong>30/04/2024</strong>
              </p>
            </div>
            <div class="card-footer">
              <button class="btn-inscrever">INSCREVA-SE</button>
            </div>
          </div>
          <div class="card">
            <div class="card-banner">*Banner do evento*</div>
            <div class="card-body">
              <h3>Artigo: Segurança Alimentar e Coletiva</h3>
              <p><i class="fa fa-calendar"></i> Período de inscrição: <strong>01/12/2023</strong> até
                <strong>11/01/2024</strong>
              </p>
              <div class="alert">
                O período de inscrições para esse evento já acabou!
              </div>
            </div>
            <div class="card-footer">
              <button class="btn-encerrado">ENCERRADO</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <footer class="footer">
    <div class="footer-container">
      <div class="footer-col footer-left">
        <strong>E.C.A.C</strong>
        <ul>
          <li><a href="./index.php">Início</a></li>
          <li><a href="./eventos.php">Eventos</a></li>
          <li><a href="./local.php">Local do Evento</a></li>
          <li><a href="./contato.php">Contato</a></li>
          <li><a href="./normas.php">Normas e Regulamentos</a></li>
          <li><a href="./arquivos.php">Arquivos</a></li>
          <li><a href="./inscricao.php">Inscrição</a></li>
          <li><a href="./submissao.php">Submissao</a></li>
          <li><a href="#">Politicas de Privacidade</a></li>
        </ul>
      </div>
      <div class="footer-col footer-center">
        <img src="../assets/img/logo com fundo.png" alt="Logo ECAC" class="logo-rodape">
      </div>
      <div class="footer-col footer-right">
        <h3>Fale Conosco</h3>
       <div class="social">
        <a href="https://www.instagram.com/ecac.alimentacaocoletiva/" target="_blank">
          <img src="../assets/img/instagram.png" alt="Instagram" class="img-rodape">
        </a>
        <a href="#" onclick="abrirEmail(event)">
          <img src="../assets/img/x.png" alt="Enviar e-mail" class="img-rodape">
        </a>
        </a>
        <a href="#" onclick="abrirWhatsApp(event)">
          <img src="../assets/img/telefone.png" alt="Falar no WhatsApp" class="img-rodape">
        </a>
      </div>
      <p>(21) 99214-1882</p>
    </div>
    </div>
    <div class="footer-bottom">
      © 2025 Encontro Carioca de Alimentação Coletiva | Política de Privacidade
    </div>
  </footer>
  <script src="../assets/js/inscricao.js"></script>
</body>
</html>