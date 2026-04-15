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
        <div class="logo"><img src="../assets/img/Apenas Logo Circulo.png" alt="logo"></div>
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
    <hr>
    <div class="page-content">
      <br><br><br>
      <div class="content-wrapper">
        <h2>Inscrição</h2>
        <p class="subtitle">Garanta sua participação e conecte-se com profissionais da alimentação coletiva.</p>
        <p class="small-print"><strong>Leia:</strong> Leia atentamente as normas e orientações antes de prosseguir com a inscrição.</p>

        <!-- Seção de Normas (colapsável) -->
        <section class="normas" aria-labelledby="normasTitle">
          <div class="normas-header">
            <h3 id="normasTitle">📜 Normas Gerais de Inscrição</h3>
            <button class="normas-toggle" aria-expanded="false" aria-controls="normasContent" title="Mostrar normas">
              <i class="fa fa-chevron-down" aria-hidden="true"></i>
            </button>
          </div>

          <div id="normasContent" class="normas-content" aria-hidden="true">
            <p>Seja bem-vindo(a)! Para garantir sua participação, leia atentamente as regras abaixo:</p>
            <ol>
              <li>
                <strong>Processo de Inscrição e Pagamento</strong>
                <ul>
                  <li><em>Fluxo de Cadastro:</em> A inscrição inicia-se em nosso site oficial e é processada através da plataforma Even3. O participante deve completar todas as etapas de redirecionamento para garantir a reserva da vaga.</li>
                  <li><em>Confirmação:</em> A vaga só será garantida após a confirmação do pagamento. Você receberá um e-mail automático da Even3 assim que o sistema identificar a transação.</li>
                  <li><em>Meios de Pagamento:</em> Serão aceitos os métodos disponíveis na plataforma (Cartão de Crédito, Boleto ou PIX). Atente-se aos prazos de vencimento de boletos para não perder o lote.</li>
                </ul>
              </li>
              <li>
                <strong>Categorias e Comprovações</strong>
                <p>Estudantes/Profissionais: Certifique-se de selecionar a categoria correta. Inscrições que exigem comprovação (ex: meia-entrada ou estudante) deverão anexar o documento solicitado no painel do inscrito ou apresentá-lo no credenciamento. A ausência de comprovação válida implicará no pagamento da diferença do valor de "Inteira".</p>
              </li>
              <li>
                <strong>Política de Cancelamento e Reembolso</strong>
                <p>Desistência: O cancelamento com reembolso integral pode ser solicitado em até 7 dias corridos após a compra, conforme o Código de Defesa do Consumidor. Após o prazo de 7 dias, não haverá devolução de valores, salvo em casos específicos previstos na legislação ou por cancelamento do evento. Transferência: A transferência da inscrição para outra pessoa pode ser feita através da área do participante na Even3 até [Inserir Prazo, ex: 48 horas] antes do evento.</p>
              </li>
              <li>
                <strong>Certificados e Credenciamento</strong>
                <p>Dados Pessoais: O nome preenchido no formulário será o mesmo utilizado na emissão do certificado. Revise a digitação antes de finalizar. Frequência: Para eventos com certificação, será exigida a presença mínima de [Inserir %, ex: 75%] aferida através do credenciamento ou listas de presença. Disponibilidade: Os certificados ficarão disponíveis para download na plataforma Even3 em até [Inserir dias] após o término do evento.</p>
              </li>
              <li>
                <strong>Disposições Finais</strong>
                <p>Ao realizar a inscrição, o participante autoriza o uso de sua imagem em fotos e vídeos capturados durante o evento para fins de divulgação institucional. A organização reserva-se o direito de alterar a programação por motivos de força maior, comunicando os inscritos via e-mail.</p>
              </li>
            </ol>
          </div>

          <!-- Tabela de Preços (fixa, sempre visível) -->
          <div class="price-wrapper">
            <h4>Tabela de Preços</h4>
            <table class="price-table" aria-label="Tabela de preços">
              <thead>
                <tr>
                  <th>Categoria</th>
                  <th>Valor</th>
                </tr>
              </thead>
              <tbody>
                <tr><td>Estudantes de Graduação</td><td>Isento</td></tr>
                <tr><td>Pós-Graduação</td><td>R$ 100,00</td></tr>
                <tr><td>Profissionais de Educação Básica</td><td>R$ 50,00</td></tr>
                <tr><td>Professores de Ensino Superior</td><td>R$ 150,00</td></tr>
                <tr><td>Outros Profissionais</td><td>R$ 150,00</td></tr>
              </tbody>
            </table>
          </div>

          <!-- CTA para Even3 -->
          <div class="cta-area">
            <a class="cta-button" href="https://www.even3.com.br" target="_blank" rel="noopener noreferrer">INSCREVA-SE NO EVENTO</a>
          </div>
        </section>
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
          <img src="../assets/img/instagram.png" alt="Instagram" class="img-rodape">
        </a>
        <a href="#" onclick="abrirEmail(event)">
          <img src="../assets/img/email.png" alt="Enviar e-mail" class="img-rodape">
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