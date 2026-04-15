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
  <link rel="stylesheet" href="../assets/css/normas.css">
</head>
<body>
  <header>
    <div class="header-content">
      <button class="menu-toggle" onclick="toggleMenu()">
  <i class="fa fa-bars"></i>
</button>
      <div class="header-title">
        <div class="logo-header"><img src="../assets/img/Só a Logo ECAC 2026.png" alt=""></div>
        <a href="./index.php">
          <h1>Encontro Carioca de Alimentação Coletiva</h1>
        </a>
      </div>
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
        <div class="logo"><img src="../assets/img/Apenas Logo Circulo.png" alt=""></div>
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
        <div class="sidebar-item active"><i class="fa fa-shield"></i> Normas </div>
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
    <div class="page-content">
  <div class="content-wrapper">
    <br><br><br>

    <h2>Normas e Regulamentos</h2>
    <p class="subtitle">
      Consulte as diretrizes e regras para participação e submissão de trabalhos no E.C.A.C.
    </p>

    <div class="card">

      <h3>Regras de Submissão de Trabalhos</h3>
      <div class="divider"></div>

      <ul>
        <li><strong>Tipos:</strong> Investigativos, descritivos, revisões e relatos de experiência.</li>
        <li><strong>Envio:</strong> Exclusivamente online pelo site oficial.</li>
        <li><strong>Período:</strong> 10 de abril a 10 de junho de 2026.</li>
        <li><strong>Eixo temático:</strong> Submissão em apenas um eixo.</li>
        <li><strong>Importante:</strong> Trabalhos fora das normas serão rejeitados.</li>
      </ul>

      <h3 style="margin-top: 25px;">Eixos Temáticos</h3>
      <div class="divider"></div>

      <ul>
        <li>Inovação e gestão</li>
        <li>Tecnologia aplicada</li>
        <li>Sustentabilidade e ESG</li>
        <li>Qualidade e segurança dos alimentos</li>
        <li>Educação e desenvolvimento profissional</li>
        <li>Alimentação, cultura e identidade</li>
      </ul>

      <h3 style="margin-top: 25px;">Regras do Resumo</h3>
      <div class="divider"></div>

      <ul>
        <li><strong>Tamanho:</strong> 350 a 500 palavras.</li>
        <li><strong>Formato:</strong> Parágrafo único.</li>
        <li><strong>Conteúdo:</strong> Introdução, objetivo, metodologia, resultados e conclusão.</li>
        <li><strong>Proibido:</strong> Imagens, gráficos ou tabelas.</li>
        <li><strong>Palavras-chave:</strong> 3 a 5 termos em ordem alfabética.</li>
      </ul>

      <h3 style="margin-top: 25px;">Autores e Inscrição</h3>
      <div class="divider"></div>

      <ul>
        <li><strong>Limite:</strong> Até 6 autores.</li>
        <li><strong>Autor responsável:</strong> Deve ser indicado.</li>
        <li><strong>Apresentação:</strong> 1 autor inscrito no evento.</li>
        <li><strong>Participação:</strong> 1 trabalho como principal e até 3 no total.</li>
      </ul>

      <h3 style="margin-top: 25px;">Processo de Avaliação</h3>
      <div class="divider"></div>

      <ul>
        <li><strong>Modelo:</strong> Duplo-cego.</li>
        <li><strong>Critérios:</strong> Relevância, originalidade, clareza e metodologia.</li>
        <li><strong>Regra:</strong> Não há correção após envio.</li>
      </ul>

      <h3 style="margin-top: 25px;">Apresentação</h3>
      <div class="divider"></div>

      <ul>
        <li>Formato banner (90x120 cm) ou apresentação oral.</li>
        <li>Melhores trabalhos recebem destaque e menção honrosa.</li>
      </ul>

      <h3 style="margin-top: 25px;">Cronograma</h3>
      <div class="divider"></div>

      <ul>
        <li><strong>Submissão:</strong> 10/04 a 10/06/2026</li>
        <li><strong>Avaliação:</strong> junho a agosto</li>
        <li><strong>Resultado:</strong> setembro</li>
        <li><strong>Evento:</strong> 31/10/2026</li>
      </ul>

      <div style="margin-top: 20px;">
        <a href="../assets/uploads/documentos/REGULAMENTO PARA SUBMISSÃO, AVALIAÇÃO E APRESENTAÇÃO DE TRABALHOS CIENTÍFICOS - EM CONSTRUÇÃO.pdf" target="_blank" class="btn-download">
          📥 Ver regulamento completo
        </a>
      </div>

      <h3 style="margin-top: 25px;">Código de Conduta</h3>
      <div class="divider"></div>

      <p style="font-size: 18px;">
        O evento estabelece diretrizes para garantir respeito, organização e boa convivência.
      </p>

      <ul>
        <li><strong>Participação:</strong> Exclusiva para inscritos.</li>
        <li><strong>Organização:</strong> Respeitar horários.</li>
        <li><strong>Segurança:</strong> Seguir normas em atividades com alimentos.</li>
        <li><strong>Respeito:</strong> Proibidas atitudes ofensivas.</li>
        <li><strong>Imagens:</strong> Uso para divulgação.</li>
        <li><strong>Dados:</strong> Proteção conforme LGPD.</li>
        <li><strong>Sustentabilidade:</strong> Uso consciente de recursos.</li>
      </ul>

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
        <img src="../assets/img/Logo ECAC 2026 vertical.png" alt="Logo ECAC" class="logo-rodape">
      </div>
      <div class="footer-col footer-right">
        <h3>Fale Conosco</h3>
        <div class="social">
          <a href="https://www.instagram.com/ecac.alimentacaocoletiva/" target="_blank">
            <img src="../assets/img/icone_instagram_padrao.png" alt="Link do Instagram" id="icone-instagram" class="img-rodape">
          </a>
          <a href="#" onclick="abrirEmail(event)">
            <img src="../assets/img/icone_email_padrao.png" alt="Link do E-mail" id="icone-email" class="img-rodape">
          </a>
          </a>
          <a href="#" onclick="abrirWhatsApp(event)">
            <img src="../assets/img/icone_whatsapp_padrao.png" alt="Link do WhatsApp" id="icone-whatsapp" class="img-rodape">
          </a>
        </div>
        <p>(21) 99214-1882</p>
      </div>
    </div>
    <div class="footer-bottom">
      © 2025 Encontro Carioca de Alimentação Coletiva | Política de Privacidade
    </div>
  </footer>
  <script src="../assets/js/normas.js"></script>
</body>
</html>