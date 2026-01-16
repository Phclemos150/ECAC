<?php
session_start();

$erroTitulo = $_SESSION['modal_erro_titulo'] ?? '';
$erroMensagem = $_SESSION['modal_erro_mensagem'] ?? '';

$sucessoTitulo = $_SESSION['modal_sucesso_titulo'] ?? '';
$sucessoMensagem = $_SESSION['modal_sucesso_mensagem'] ?? '';

$deveRedirecionar = $_SESSION['redirecionar_login'] ?? false;

unset($_SESSION['modal_erro_titulo'], $_SESSION['modal_erro_mensagem']);
unset($_SESSION['modal_sucesso_titulo'], $_SESSION['modal_sucesso_mensagem']);
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
  <link rel="stylesheet" href="../assets/css/cadastro.css">
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
  <main>
    <div class="container">
      <h1>Cadastro de Usuário</h1>
      <form method="post" action="../controllers/AutentController.php?acao=cadastro" novalidate
        enctype="multipart/form-data">
        <div class="grupo">
          <label>Nome completo</label>
          <input type="text" name="nome_usuario">
        </div>
        <div class="grupo">
          <label>E-mail</label>
          <input type="email" name="email">
        </div>
        <div class="grupo senha-grupo">
          <label>Senha</label>
          <div class="senha-container">
            <input type="password" name="senha" id="senha">
            <i class="fa-solid fa-eye" id="toggleSenha"></i>
          </div>
        </div>
        <div class="grupo">
          <label>Documento (CPF ou RG)</label>
          <input type="text" name="documento">
        </div>
        <div class="grupo">
          <label>Data de nascimento</label>
          <input type="date" name="data_nascimento">
        </div>
        <div class="grupo">
          <label>Telefone</label>
          <input type="tel" name="telefone">
        </div>
        <div class="grupo">
          <label>Instagram</label>
          <input type="text" name="instagram" placeholder="@usuario">
        </div>
        <div class="grupo">
          <label>Nível de escolaridade</label>
          <select name="nivel_escolaridade">
            <option value="">Selecione</option>
            <option value="Ensino Fundamental">Ensino Fundamental</option>
            <option value="Ensino Médio">Ensino Médio</option>
            <option value="Graduação">Graduação</option>
            <option value="Pós-graduação">Pós-graduação</option>
            <option value="Mestrado">Mestrado</option>
            <option value="Doutorado">Doutorado</option>
          </select>
        </div>
        <div class="grupo">
          <label>Curso</label>
          <input type="text" name="nome_curso">
        </div>
        <div class="linha">
          <div class="grupo">
            <label>Cidade</label>
            <input type="text" name="cidade" maxlength="50">
          </div>
          <div class="grupo">
            <label>Estado (UF)</label>
            <input type="text" name="estado" maxlength="2">
          </div>
        </div>
        <div class="grupo">
          <label>País</label>
          <input type="text" name="pais" value="Brasil">
        </div>
        <div class="grupo">
          <label>Foto de perfil</label>
          <input type="file" name="foto_perfil" accept="image/*">
        </div>
        <button type="submit">Cadastrar</button>
      </form>
    </div>
  </main>
  <?php if (!empty($erroTitulo) || !empty($erroMensagem)): ?>
    <div class="modal ativo" id="modalErro">
      <div class="modal-box">
        <h3 class="modal-titulo"><?= htmlspecialchars($erroTitulo) ?></h3>
        <p class="modal-mensagem"><?= htmlspecialchars($erroMensagem) ?></p>
        <button class="btn-confirmar-erro" id="fecharModalErro">Ok</button>
      </div>
    </div>
  <?php endif; ?>
  <?php if (!empty($sucessoTitulo)): ?>
    <div class="modal ativo" id="modalSucesso">
      <div class="modal-box">
        <h3 class="modal-titulo"><?= htmlspecialchars($sucessoTitulo) ?></h3>
        <p class="modal-mensagem"><?= htmlspecialchars($sucessoMensagem) ?></p>
        <button class="btn-confirmar-erro" id="btnSucessoOk">Ok</button>
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
            <img src="../assets/img/instagram.png" alt="Instagram" class="img-rodape">
          </a>
          <a href="#">
            <img src="../assets/img/x.png" alt="X" class="img-rodape">
          </a>
          <a href="#">
            <img src="../assets/img/telefone.png" alt="Telefone" class="img-rodape">
          </a>
        </div>
        <p>(21) 99999-9999</p>
      </div>
    </div>
    <div class="footer-bottom">
      © 2025 Encontro Carioca de Alimentação Coletiva | Política de Privacidade
    </div>
  </footer>
  <script>
    window.redirecionarAoFechar = <?= json_encode($deveRedirecionar) ?>;
  </script>
  <script src="../assets/js/cadastro.js"></script>
</body>
</html>