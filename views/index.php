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
        <div class="sidebar-item active"><i class="fa fa-home"></i> Início </div>
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
        <div class="sidebar-item"><i class="fa fa-id-card"></i> Inscrição </div>
      </a>
      <a href="./submissao.php">
        <div class="sidebar-item"><i class="fa fa-upload"></i> Submissão </div>
      </a>
    </div>
    <div class="content-area">
      <div class="banner">
        <div class="banner-logo">
          <img src="../assets/img/Logo Comunidade Carioca Melhorada.png" alt="Logo ECAC">
        </div>
        <div class="banner-text">
          <h2>
            Encontro Carioca<br>
            de Alimentação Coletiva
          </h2>
          <p style="font-size:21px;">
            Uma experiência única para profissionais de
            <span style="color:#3f5d2a; font-weight:600;">nutrição</span> e
            <span style="color:#f39c12; font-weight:600;">gastronomia</span>!
          </p>
          <a href="./inscricao.php" class="btn-inscricao">GARANTA SUA VAGA</a>
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
        </p>
      </section>
      <br><br><br>
      <section class="sobre-evento">
        <div class="sobre-container">
          <div class="sobre-imagem">
            <img src="../assets/img/objetivoevento.png" alt="Evento ECAC">
          </div>
          <div class="sobre-texto">
            <h2 class="titulo-com-linha">Propósito do Encontro</h2>
            <div class="linha-titulo"></div>
            <p>
              O Encontro Carioca de Alimentação Coletiva nasceu com o propósito de valorizar,
              conectar e fortalecer os profissionais que atuam na alimentação fora do lar,
              promovendo um espaço de diálogo entre o conhecimento técnico, científico e a
              prática cotidiana dos serviços de alimentação.
            </p>
          </div>
        </div>
      </section>
      <br><br><br>
      <div class="slider">
        <div class="slides">
          <div class="slide active" style="background-image: url('../assets/img/ecac\ 13\ 28.jpeg.');">
          </div>
          <div class="slide" style="background-image: url('../assets/img/ecac\ 13.jpeg');">
          </div>
          <div class="slide" style="background-image: url('../assets/img/ecac\ 7.jpeg');">
          </div>
          <div class="slide" style="background-image: url('../assets/img/ecac7.jpeg');">
          </div>
        </div>
        <button class="nav prev">&#10094;</button>
        <button class="nav next">&#10095;</button>
        <div class="dots">
          <span class="dot active"></span>
          <span class="dot"></span>
          <span class="dot"></span>
          <span class="dot"></span>
        </div>
      </div>
      <br><br>
      <section class="section">
        <h1>Propostas inovadoras</h1>
        <div class="section-divider"></div>
        <p>
          Com o tema "Inovação, Tecnologia e Sustentabilidade: caminhos para a excelência na gestão da alimentação fora
          do lar", o II Encontro Carioca de Alimentação Coletiva
          busca inspirar a transformação dos serviços de alimentação por meio da troca de experiências, da produção de
          conhecimento e do fortalecimento de redes colaborativas
        </p>
      </section>
      <section class="proposta-2026">
        <div class="texto">
          <br>
          <p style="font-size:25px;"><strong>O evento contará com:</strong></p>
          <ul>
            <li>
              <strong>Apresentação de trabalhos científicos e relatos de experiência exitosas</strong>
              no campo da alimentação fora do lar, com menção honrosa para os quatro melhores resumos;
            </li>
            <li>
              <strong>Painel com empresas do setor de alimentação</strong>, promovendo diálogo entre
              academia, mercado e gestão pública;
            </li>
            <li>
              <strong>Feira de oportunidades de emprego</strong>, voltada a nutricionistas, técnicos
              e demais profissionais da área de alimentos;
            </li>
            <li>
              <strong>Pré-evento com oficinas práticas</strong>, abordando:
              <ul class="sublista">
                <li>Precificação de preparações e produtos alimentícios;</li>
                <li>Cálculo de ficha técnica e planejamento de cardápios;</li>
                <li>Dimensionamento de equipamentos, inovação e tecnologia a favor da sustentabilidade;</li>
                <li>Cardápios inclusivos e sustentáveis.</li>
              </ul>
            </li>
          </ul>
        </div>
        <div class="imagem">
          <img src="../assets/img/ecac 1.jpeg" alt="Palestra e público do evento">
        </div>
      </section>
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
      <br><br><br>
      <section class="section">
        <h1>Organizadores do Evento</h1>
        <div class="section-divider"></div>
        <p class="subtitle">Conheça as pessoas e instituições responsáveis por tornar o Encontro Carioca de Alimentação
          Coletiva possível</p>
        <br><br>
      </section>
      <section class="organizadores">
        <div class="organizador-card">
          <img src="../assets/img/org1.png" alt="Organizador 1">
          <h3>Aline</h3>
          <p class="cargo">Diretor de Marketing</p>
          <p class="descricao"></p>
        </div>
        <div class="organizador-card">
          <img src="../assets/img/org2.png" alt="Organizador 2">
          <h3>Roberta</h3>
          <p class="cargo">Organizadora</p>
          <p class="descricao"></p>
        </div>
        <div class="organizador-card">
          <img src="../assets/img/org3.png" alt="Organizador 3">
          <h3>Roberto</h3>
          <p class="cargo">Gerente de Palestras</p>
          <p class="descricao"></p>
        </div>
      </section>
      <section class="section">
        <h1>Patrocinadores</h1>
        <div class="section-divider"></div>
        <p>
          Junte-se a nós na construção de um evento que conecta profissionais, conhecimento e propósito em torno de uma
          alimentação mais inovadora, sustentável e humana
        </p>
      </section>
      <section class="caixa-branca patrocinio">
        <div class="titulo-simples">
          <span>Patrocinador Master</span>
        </div>
        <div class="logos patrocinador-master">
          <a href="https://www.unisuam.edu.br/" target="_blank"><img src="../assets/img/unisuam.png" alt="UNISUAM"></a>
        </div>
        <div class="titulo-linha">
          <span>Patrocinadores</span>
        </div>
        <div class="logos patrocinadores">
          <img src="../assets/img/logo1.png" alt="">
          <img src="../assets/img/logo2.png" alt="">
          <img src="../assets/img/logo3.png" alt="">
        </div>
        <div class="titulo-linha">
          <span>Apoiadores</span>
        </div>
        <div class="logos apoiadores">
          <img src="../assets/img/logo4.png" alt="">
          <img src="../assets/img/logo5.png" alt="">
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
            <img src="../assets/img/instagram.png" alt="Link do Instagram" class="img-rodape">
          </a>
          <a href="#" onclick="abrirEmail(event)">
            <img src="../assets/img/email.png" alt="Link do E-mail" class="img-rodape">
          </a>
          </a>
          <a href="#" onclick="abrirWhatsApp(event)">
            <img src="../assets/img/telefone.png" alt="Link do WhatsApp" class="img-rodape">
          </a>
        </div>
        <p>(21) 99214-1882</p>
      </div>
    </div>
    <div class="footer-bottom">
      © 2025 Encontro Carioca de Alimentação Coletiva | Política de Privacidade
    </div>
  </footer>
  <script src="../assets/js/index.js"></script>
</body>
</html>