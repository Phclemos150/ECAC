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
  <link rel="stylesheet" href="../assets/css/index.css">
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
        <div class="sidebar-item active"><i class="fa fa-home"></i> Início</div>
      </a>
      <a href="./eventos.php">
        <div class="sidebar-item"><i class="fa fa-calendar-check"></i> Evento</div>
      </a>
      <a href="./programacao.php">
        <div class="sidebar-item"><i class="fa fa-calendar"></i> Programação</div>
      </a>
      <a href="./normas.php">
        <div class="sidebar-item"><i class="fa fa-shield"></i> Normas</div>
      </a>
      <a href="./submissao.php">
        <div class="sidebar-item"><i class="fa fa-upload"></i> Submissão</div>
      </a>
      <a href="./inscricao.php">
        <div class="sidebar-item"><i class="fa fa-id-card"></i> Inscrição</div>
      </a>
      <a href="./arquivos.php">
        <div class="sidebar-item"><i class="fa fa-download"></i> Arquivos</div>
      </a>
      <a href="./palestrantes.php">
        <div class="sidebar-item"><i class="fa fa-person"></i> Palestrantes</div>
      </a>
    </div>
    <div class="content-area">
      <div class="banner">
        <!-- LOGO (lado esquerdo) -->
        <div class="banner-logo">
          <img src="../assets/img/Logo Comunidade Carioca Melhorada.png" alt="Logo ECAC">
        </div>
        <!-- TEXTO (lado direito) -->
        <div class="banner-text">
          <h2>
            Encontro Carioca<br>
            de Alimentação Coletiva
          </h2>
          <p>
            Uma experiência única para profissionais de nutrição e gastronomia!
          </p>
          <a href="./inscricao.php" class="btn-inscricao" >GARANTA SUA VAGA</a>
        </div>
      </div>
      <section class="section">
        <h1>Sobre o evento</h1>
        <div class="section-divider"></div>
        <p>
          O Encontro Carioca de Alimentação Coletiva reúne profissionais de nutrição,
          pesquisa e gestão para discutir práticas, políticas e inovação na alimentação
          institucional. <br>
          Com palestras, workshops e apresentação de trabalhos,
          o evento promove conexão e atualização técnica.
      </section>
      <section class="section-with-image">
        <div class="image-container">
          <img src="../assets/img/sobreoevento.png" alt="Sobre o Evento">
        </div>
        <div class="text-container">
          <h2>Sobre o Evento</h2>
          <p>O Encontro Carioca de Alimentação Coletiva é um espaço dedicado a profissionais da nutrição, gastronomia e gestão alimentar. Aqui, discutimos as melhores práticas para alimentação coletiva sustentável e saudável.</p>
        </div>
      </section>
      <section class="section-with-image reverse">
        <div class="image-container">
          <img src="../assets/img/objetivoevento.png" alt="Objetivo do Evento">
        </div>
        <div class="text-container">
          <h2>Objetivo do Evento</h2>
          <p>Promover o intercâmbio de conhecimentos, fomentar inovações e fortalecer a rede de profissionais comprometidos com a alimentação coletiva de qualidade em todo o Rio de Janeiro.</p>
        </div>
      </section>
      <div class="slider">
        <div class="slides">
          <div class="slide active" style="background-image: url('../assets/img/ecac\ 18.jpeg');">
          </div>
          <div class="slide" style="background-image: url('../assets/img/ecac\ 13.jpeg');">
          </div>
          <div class="slide" style="background-image: url('../assets/img/ecac\ 7.jpeg');">
          </div>
          <div class="slide" style="background-image: url('../assets/img/ecac7.jpeg');">
          </div>
        </div>
        <!-- BOTÕES -->
        <button class="nav prev">&#10094;</button>
        <button class="nav next">&#10095;</button>
        <!-- INDICADORES -->
        <div class="dots">
          <span class="dot active"></span>
          <span class="dot"></span>
          <span class="dot"></span>
          <span class="dot"></span>
        </div>
      </div>
      <section class="section">
        <h1>Um evento que vai te impressionar</h1>
        <div class="section-divider"></div>
        <p class="subtitle">Para melhorar seu ambiente profissional</p>
        <br><br>
        <div class="cards">
          <div class="card-img"><img src="../assets/img/Conhecimento.png" alt=""></div>
          <div class="card-img"><img src="../assets/img/Certificados.png" alt=""></div>
          <div class="card-img"><img src="../assets/img/Networking.png" alt=""></div>
        </div>
      </section>
      <section class="section">
        <h1>Organizadores</h1>
        <div class="section-divider"></div>
        <p>Conheça a equipe responsável pela organização do Encontro Carioca de Alimentação Coletiva.</p>
        <div class="organizadores-grid">
          <div class="organizador-card">
            <div class="organizador-img">
              <img src="../assets/img/organizador1.png" alt="Organizador 1">
            </div>
            <h3>Maria Silva</h3>
            <p>Coordenadora Geral</p>
          </div>
          <div class="organizador-card">
            <div class="organizador-img">
              <img src="../assets/img/organizador2.png" alt="Organizador 2">
            </div>
            <h3>João Pereira</h3>
            <p>Diretor de Programação</p>
          </div>
          <div class="organizador-card">
            <div class="organizador-img">
              <img src="../assets/img/organizador3.png" alt="Organizador 3">
            </div>
            <h3>Ana Costa</h3>
            <p>Coordenadora de Comunicação</p>
          </div>
        </div>
      </section>
    </div>
  </div>
  <footer class="footer">
    <div class="footer-container">
      <div class="footer-col footer-left">
        <strong>E.C.A.C</strong>
        <ul>
          <li><a href="./index.php">Início</a></li>
          <li><a href="./contato.php">Contato</a></li>
          <li><a href="#">Programação</a></li>
          <li><a href="#">Sobre</a></li>
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
          <a href="#">
            <img src="../assets/img/x.png" alt="X" class="img-rodape">
          </a>
          <a href="#">
            <img src="../assets/img/telefone.png" alt="telefone" class="img-rodape">
          </a>
        </div>
        <p>(21) 99999-9999</p>
      </div>
    </div>
    <div class="footer-bottom">
      © 2025 Encontro Carioca de Alimentação Coletiva | Política de Privacidade
    </div>
  </footer>
  <script src="../assets/js/index.js"></script>
</body>

</html>