<?php
session_start();

/* Recebe os dados de erro de login, se houver */
$erroTitulo = $_SESSION['modal_erro_titulo'] ?? '';
$erroMensagem = $_SESSION['modal_erro_mensagem'] ?? '';

unset($_SESSION['modal_erro_titulo'], $_SESSION['modal_erro_mensagem']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Encontro Carioca de Alimentação Coletiva</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="website icon" type="png" href="../assets/img/logo.png">
  <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
  <header>
    <div class="header-content">
      <div class="header-title">
        <a href="index.php" class="back-arrow" title="Voltar para a página inicial">
          <i class="fa fa-arrow-left"></i>
        </a>
        <h1>Encontro Carioca de Alimentação Coletiva</h1>
      </div>
    </div>
  </header>
  <br><br><br>
  <main>
    <div class="login-box">
      <h2>Login</h2>
      <p>Acesse sua conta para continuar</p>
      <form method="post" action="../controllers/AutentController.php?acao=login" novalidate
        enctype="multipart/form-data">
        <div class="input-group">
          <label>Email</label>
          <input type="email" name="email" placeholder="Digite seu email">
        </div>
        <div class="input-group">
          <label>Senha</label>
          <div class="password-wrapper">
            <input type="password" id="senha" name="senha" placeholder="Digite sua senha">
            <i class="fa-solid fa-eye" id="toggleSenha"></i>
          </div>
        </div>
        <button class="login-btn" type="submit">Entrar</button>
      </form>
      <div class="login-links">
        <p><a href="#" id="abrirModalSenha">Esqueci minha senha</a></p>
        <p>Não tem conta? <a href="./cadastro.php">Criar conta</a></p>
      </div>
    </div>
  </main>
  <div class="modal" id="modalSenha">
    <div class="modal-box">
      <h3>Trocar senha</h3>
      <div class="password-wrapper-cs">
        <input type="email" id="emailConfirmacao" placeholder="Confirmar Email">
      </div>
      <div class="password-wrapper-cs">
        <input type="password" id="novaSenha" placeholder="Nova senha">
        <i class="fa-solid fa-eye" id="toggleNovaSenha"></i>
      </div>
      <div class="password-wrapper-cs">
        <input type="password" id="confirmarNovaSenha" placeholder="Confirmar senha">
        <i class="fa-solid fa-eye" id="toggleConfirmarSenha"></i>
      </div>
      <div class="modal-buttons">
        <button class="btn-cancelar" id="fecharModalSenha">Cancelar</button>
        <button class="btn-confirmar">Salvar</button>
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
  <br><br><br>
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
  <script src="../assets/js/login.js"></script>
</body>
</html>