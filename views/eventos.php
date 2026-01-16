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
  <title>Eventos | ECAC</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="website icon" type="png" href="../assets/img/logo.png">
  <link rel="stylesheet" href="../assets/css/eventos.css">
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
                <img src="<?= $caminho_perfil ?>" class="img-perfil-nav">
              </div>
              <i class="fa fa-chevron-down arrow-icon"></i>
            </div>
            <div id="dropdownMenu" class="dropdown-content">
              <div class="dropdown-header">
                <strong>Olá, <?= htmlspecialchars($nome_usuario) ?>!</strong>
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
        <div class="logo">
          <img src="../assets/img/icon planta.png">
        </div>
        <strong>E.C.A.C</strong>
      </div>
      <a href="./index.php">
        <div class="sidebar-item"><i class="fa fa-home"></i> Início </div>
      </a>
      <a href="./eventos.php">
        <div class="sidebar-item active"><i class="fa fa-calendar-check"></i> Eventos </div>
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
    <div class="page-content">
      <div class="content-wrapper">
        <h1>Eventos</h1>
        <p>Confira os eventos do Encontro Carioca de Alimentação Coletiva.</p>
        <div class="eventos-cards">
          <div class="evento-card atual">
            <img src="../assets/img/logo com fundo.png">
            <div class="evento-info">
              <span class="badge atual">Evento Atual</span>
              <h3>Encontro Carioca de Alimentação Coletiva</h3>
              <p>Evento presencial com palestras, painéis e networking.</p>
              <button class="btn-primary" onclick="abrirModalEvento()">Detalhes</button>
            </div>
          </div>
          <div class="evento-card passado">
            <img src="../assets/img/evento-passado.jpg">
            <div class="evento-info">
              <span class="badge passado">Encerrado</span>
              <h3>Encontro Carioca 2024</h3>
              <p>Edição anterior do evento.</p>
              <button class="btn-disabled" disabled>Encerrado</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="modalEvento" class="modal-evento">
    <div class="modal-box">
      <span class="fechar-modal" onclick="fecharModalEvento()">&times;</span>
      <img src="../assets/img/logo com fundo.png" class="modal-capa" alt="Capa do Evento">
      <h2>Encontro Carioca de Alimentação Coletiva</h2>
      <p><strong>Descrição:</strong> Evento voltado à troca de experiências, debates e atualização profissional na área
        de Alimentação Coletiva.</p>
      <p><strong>Local:</strong> Auditório Sylvia Bisaggio – UNISUAM (Bonsucesso)</p>
      <p><strong>Data:</strong> 27 de setembro de 2025</p>
      <p><strong>Horário:</strong> 08h30 às 16h30</p>
      <p><strong>Modalidade:</strong> Presencial</p>
      <hr>
      <h3>Programação</h3>
      <ul class="programacao">
        <li><strong>08h30 – 09h00:</strong> Credenciamento e Boas-vindas</li>
        <li><strong>09h10 – 09h40:</strong> Alimentação Coletiva no RJ: Desafios Atuais e Perspectivas para o Futuro
        </li>
        <li><strong>09h45 – 10h15:</strong> Planejamento em Serviços de Alimentação</li>
        <li><strong>10h25 – 10h55:</strong> Elaboração do Termo de Referência em Contas Públicas</li>
        <li><strong>11h00 – 11h30:</strong> Cultura de Segurança dos Alimentos</li>
        <li><strong>11h40 – 12h10:</strong> Sustentabilidade na Produção de Refeições</li>
        <li><strong>12h15 – 12h45:</strong> Marketing Digital e Uso de Mídias Sociais</li>
        <li><strong>14h20 – 14h50:</strong> Uso de Alimentos Regionais nos Cardápios Institucionais</li>
        <li><strong>15h00 – 15h30:</strong> Atuação do Nutricionista em Equipamentos Públicos de SAN</li>
        <li><strong>15h35 – 16h05:</strong> Gestão de Serviços de Alimentação Escolar</li>
      </ul>
      <hr>
      <h3>Palestrantes e Mini Bio</h3>
      <div class="palestrante">
        <strong>Camila das Neves Didini</strong>
        <p>
          Nutricionista pela UFRJ, Especialista em Gastronomia Aplicada à Nutrição (Nutrinew),
          Mestre em Nutrição Humana (UFRJ) e Doutoranda em Alimentos e Nutrição (UNIRIO).
          Atua com ênfase em gastronomia, alimentação coletiva, PANC e rotulagem.
          Professora da PUC-Rio, Centro Universitário La Salle e colaboradora do CEAC/UFRJ.
        </p>
      </div>
      <div class="palestrante">
        <strong>Marcela Maltez</strong>
        <p>
          Nutricionista pela UFF, Especialista em Gestão da Segurança de Alimentos e Bebidas
          (FIRJAN/SENAI-RJ) e Mestre em Ciências e Tecnologia de Alimentos pelo IFRJ.
        </p>
      </div>
      <div class="palestrante">
        <strong>Kátia Alessandra Mendes</strong>
        <p>
          Nutricionista com atuação em Alimentação Coletiva. Mestre em Ciência e Tecnologia de Alimentos.
          Pesquisadora Culinafro, Coordenadora da Linha de Culinária Africana (CM-UFRJ/Macaé),
          Sommelière de Chá, docente de graduação e pós-graduação.
          Coordenadora do MBA em Gestão da Qualidade e Segurança do Alimento (Nutmed/RJ)
          e Conselheira do CRN4 (gestão 2025).
        </p>
      </div>
      <div class="palestrante">
        <strong>Renata Nogueira</strong>
        <p>
          Nutricionista com mais de 20 anos de experiência em Alimentação Coletiva.
          Servidora pública federal desde 2018, Responsável Técnica do PNAE no Colégio Pedro II.
          Especialista em Gestão da Segurança dos Alimentos, Mestre em Educação Profissional em Saúde
          (FIOCRUZ) e Doutora em Ciências (UERJ).
        </p>
      </div>
      <div class="palestrante">
        <strong>Katiana dos Santos Teléfora</strong>
        <p>
          Nutricionista (UERJ) e Sanitarista (UFRJ). Mestre em Saúde Pública (ENSP/FIOCRUZ).
          Servidora Pública da carreira de Especialista em Políticas Públicas e Gestão Governamental
          da Secretaria de Estado de Planejamento e Gestão do RJ.
        </p>
      </div>
      <div class="palestrante">
        <strong>Luana Limoeiro</strong>
        <p>
          Nutricionista, Mestra em Ciência dos Alimentos e Doutora em Ciência e Tecnologia dos Alimentos.
          Possui ampla experiência em produção de refeições, gestão de UAN,
          atuação como nutricionista de produção e planejamento.
          Atualmente é Coordenadora do Curso de Nutrição da UCAM,
          professora da LaSalle e Presidente do CRN4.
        </p>
      </div>
      <div class="palestrante">
        <strong>Fernanda Bainha</strong>
        <p>
          Nutricionista (UFF) com atuação há 15 anos em gestão de UANs offshore, hospitalares e escolares.
          Docente na especialização em Nutrição Hospitalar do Hospital Sírio-Libanês (SP).
          Assessora Técnica em Segurança Alimentar e Nutricional no Governo do Estado.
          Mestra em Engenharia de Produção (UFF) e Doutoranda em Alimentação, Nutrição e Saúde (UERJ).
        </p>
      </div>
      <div class="palestrante">
        <strong>Tatiana Schiavone</strong>
        <p>
          Nutricionista do Sistema Integrado de Alimentação da UFRJ (SIA/UFRJ).
          Atua com consultoria em serviços de alimentação e qualidade.
          Mestre em Ciência e Tecnologia dos Alimentos (IFRJ),
          Pós-graduada em Gestão de Alimentação e Nutrição e em Qualidade de Alimentos.
          Experiência em Alimentação Coletiva desde 2001.
        </p>
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
        <img src="../assets/img/logo com fundo.png" class="logo-rodape">
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
      © 2025 Encontro Carioca de Alimentação Coletiva
    </div>
  </footer>
  <script src="../assets/js/eventos.js"></script>
</body>
</html>