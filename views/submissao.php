<?php
session_start();

$user = $_SESSION["user_logado"] ?? null;

$id_usuario = $user["id"] ?? null;
$nome_usuario = $user["nome"] ?? null;
$email = $user["email"] ?? null;
$foto = $user["foto"] ?? null;

$logado = (bool) $user;

/* Recebe os dados de erro de login, se houver */
$erroTitulo = $_SESSION['modal_erro_titulo'] ?? '';
$erroMensagem = $_SESSION['modal_erro_mensagem'] ?? '';

$redirecionarLogin = isset($_SESSION['redirecionar_login']);

unset($_SESSION['modal_erro_titulo'], $_SESSION['modal_erro_mensagem']);
unset($_SESSION['redirecionar_login']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Encontro Carioca de Alimentação Coletiva</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="website icon" type="png" href="../assets/img/logo.png">
  <link rel="stylesheet" href="../assets/css/submissao.css">
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
        <div class="sidebar-item"><i class="fa fa-id-card"></i> Inscrição </div>
      </a>
      <a href="./submissao.php">
        <div class="sidebar-item active"><i class="fa fa-upload"></i> Submissão </div>
      </a>
    </div>
    <div class="page-content">
      <div class="content-wrapper">
        <h2>Submissão de Trabalhos Acadêmicos</h2>
        <p class="subtitle">
          Utilize o formulário abaixo para enviar seu resumo ou trabalho completo.
          A submissão é simples e rápida, focada apenas nas informações essenciais.
        </p>
        <form method="post" action="../controllers/SubmissaoController.php" novalidate enctype="multipart/form-data" class="card">
          <div class="form-group">
            <label>Título do Trabalho</label>
            <input type="text" name="titulo" placeholder="Ex: Análise de Dados em Ambientes Distribuídos">
          </div>
          <div class="form-group">
            <label>Autor</label>
            <input type="text" name="autor" placeholder="Ex: João Silva">
          </div>
          <div class="form-group">
            <label>Resumo</label>
            <textarea name="resumo" rows="4" placeholder="Breve resumo do trabalho..."></textarea>
          </div>
          <div class="form-group">
            <label>Palavras-chave para Busca</label>
            <input type="text" name="palavras_chave" placeholder="Ex: Nutrição, Saúde Pública, Sustentabilidade">
            <small class="help-text">
              <i class="fa fa-info-circle"></i>
              Insira termos que ajudem outros alunos a encontrarem seu trabalho na barra de pesquisa.
            </small>
          </div>
          <div class="form-group">
            <label><strong>Coautores</strong></label>
            <div id="container-coautores">
              <div class="coautor-item">
                <input type="text" name="coautor_nome[]" placeholder="Nome Completo">
                <input type="email" name="coautor_email[]" placeholder="E-mail">
                <input type="text" name="coautor_inst[]" placeholder="Instituição/Afiliação">
              </div>
            </div>
            <button type="button" class="btn-coautor" onclick="addCoautor()">
              <i class="fa fa-plus"></i> Adicionar outro Coautor
            </button>
          </div>
          <div class="form-group">
            <label>Arquivo (PDF, DOCX)</label>
            <div class="upload-box" onclick="document.getElementById('arquivo-input').click()">
              <i class="fa fa-cloud-upload-alt"></i>
              <p><strong>Clique para enviar</strong> ou arraste e solte</p>
              <span class="upload-info">Apenas 1 arquivo PDF ou DOCX (Máx. 10MB)</span>
              <input type="file" name="arquivo" id="arquivo-input" accept=".pdf, .docx"
                onchange="atualizarNomeArquivo(this)">
            </div>
            <small id="nome-arquivo">Nenhum arquivo selecionado.</small>
          </div>
          <div class="rules-box">
            <strong>Regras Importantes (Verifique em Normas):</strong>
            <ul>
              <li>Máximo de 10 páginas por trabalho.</li>
              <li>Formatos permitidos: PDF ou DOCX.</li>
              <li>O arquivo deve conter título, autores e resumo.</li>
            </ul>
          </div>
          <button type="submit" class="btn-submit">ENVIAR TRABALHO</button>
        </form>
      </div>
    </div>
  </div>
  </div>
  <?php if ($erroTitulo || $erroMensagem): ?>
    <div class="modal ativo" id="modalErro">
      <div class="modal-box">
        <h3 class="modal-erro-titulo"><?= htmlspecialchars($erroTitulo) ?></h3>
        <p class="modal-erro-mensagem"><?= htmlspecialchars($erroMensagem) ?></p>
        <button class="btn-confirmar-erro" id="fecharModalErro">Ok</button>
      </div>
    </div>
  <?php endif; ?>
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
  <script>
    window.redirecionarAoFechar = <?= json_encode($redirecionarLogin) ?>;
  </script>
  <script src="../assets/js/submissao.js"></script>
</body>
</html>