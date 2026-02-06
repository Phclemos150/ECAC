<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../controllers/EventosController.php';

$eventoController = new EventoController($con);
$eventos = $eventoController->carregarEventos();

$user = $_SESSION["user_logado"] ?? null;

$id_usuario = $user["id"] ?? null;
$nome_usuario = $user["nome"] ?? null;
$email = $user["email"] ?? null;
$foto = $user["foto"] ?? null;

$logado = (bool) $user;

function formatarLinkExterno($url)
{
  if (empty($url))
    return "javascript:void(0);";

  // Verifica se já começa com http:// ou https://
  if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
    $url = "https://" . $url;
  }
  return htmlspecialchars($url);
}
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
              <div>
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
          <?php if (!empty($eventos)): ?>
            <?php foreach ($eventos as $ev):
              $ehAtivo = ($ev['status_evento'] === 'ativo');
              $classeCard = $ehAtivo ? 'atual' : 'passado';
              $idModalUnico = "modalEvento_" . $ev['id_evento'];
              ?>
              <div class="evento-card <?= $classeCard ?>">
                <div class="card-banner">
                  <img src="<?= !empty($ev['capa_evento']) ? $ev['capa_evento'] : '../assets/img/logo com fundo.png' ?>">
                </div>
                <div class="evento-info">
                  <span class="badge <?= $classeCard ?>">
                    <?= $ehAtivo ? 'Evento Atual' : 'Encerrado' ?>
                  </span>
                  <h3><?= htmlspecialchars($ev['titulo']) ?></h3>
                  <p><i class="fa fa-map-marker-alt"></i> Local:
                    <strong><?= htmlspecialchars($ev['local_evento']) ?></strong>
                  </p>
                  <p><i class="fa fa-calendar-day"></i> Data:
                    <strong><?= date('d/m/Y', strtotime($ev['data_evento'])) ?></strong>
                  </p>
                  <?php if ($ehAtivo): ?>
                    <p><i class="fa fa-hourglass-half"></i> Inscrições:
                      <strong><?= date('d/m/Y', strtotime($ev['data_inscricao_inicio'])) ?></strong> a
                      <strong><?= date('d/m/Y', strtotime($ev['data_inscricao_fim'])) ?></strong>
                    </p>
                  <?php else: ?>
                    <p class="erro-inscricao">Inscrições encerradas!</p>
                  <?php endif; ?>
                </div>
                <div class="card-footer">
                  <button class="btn-primary" onclick="abrirModalEvento('<?= $idModalUnico ?>')">
                    Detalhes do Evento
                  </button>
                  <?php if ($ehAtivo): ?>
                    <button class="btn-inscrever" onclick="irParaInscricao()"> Inscreva-se </button>
                  <?php else: ?>
                    <button class="btn-disabled" disabled> Inscreva-se </button>
                  <?php endif; ?>
                </div>
              </div>
              <div id="<?= $idModalUnico ?>" class="modal-evento" style="display:none;">
                <div class="modal-box">
                  <span class="fechar-modal" onclick="fecharModalEvento('<?= $idModalUnico ?>')">&times;</span>
                  <section class="modal-header">
                    <div class="capa-container">
                      <img src="<?= !empty($ev['capa_evento']) ? $ev['capa_evento'] : '../assets/img/logo com fundo.png' ?>"
                        class="modal-capa">
                    </div>
                    <div class="header-info">
                      <h2 class="evento-titulo"><?= htmlspecialchars($ev['titulo']) ?></h2>
                      <p><?= htmlspecialchars($ev['descricao'] ?? 'Descrição não disponível.') ?></p>
                      <div class="info-colunas">
                        <div class="info-col">
                          <p><i class="fa fa-map-marker-alt"></i> <strong>Local:</strong>
                            <?= htmlspecialchars($ev['local_evento']) ?></p>
                          <p><i class="fa fa-calendar"></i> <strong>Data:</strong>
                            <?= date('d/m/Y', strtotime($ev['data_evento'])) ?></p>
                        </div>
                        <div class="info-col">
                          <p><i class="fa fa-laptop"></i> <strong>Modalidade:</strong>
                            <?= htmlspecialchars($ev['modalidade'] ?? 'Presencial') ?></p>
                          <p><i class="fa fa-clock"></i> <strong>Horário:</strong>
                            <?= htmlspecialchars(date('H:i', strtotime($ev['horario_inicio']))) ?> às
                            <?= htmlspecialchars(date('H:i', strtotime($ev['horario_fim']))) ?>
                          </p>
                        </div>
                      </div>
                    </div>
                  </section>
                  <hr class="divisor">
                  <section class="modal-section">
                    <h3 class="section-title">Programação</h3>
                    <div class="timeline">
                      <?php if (!empty($ev['atividades'])): ?>
                        <?php foreach ($ev['atividades'] as $at): ?>
                          <div class="atividade-item">
                            <div class="hora"><?= date('H:i', strtotime($at['horario_inicio'])) ?></div>
                            <div class="atividade-detalhe">
                              <span class="tipo-badge"><?= htmlspecialchars($at['tipo_atividade']) ?></span>
                              <h4><?= htmlspecialchars($at['titulo']) ?></h4>
                              <p class="atividade-desc">
                                <i class="fa fa-file-lines"></i>
                                <strong>Descrição:</strong>
                                <?= htmlspecialchars($at['descricao'] ?? '') ?>
                              </p>
                              <?php if (!empty($at['local_atividade'])): ?>
                                <p class="atividade-local">
                                  <i class="fa fa-building"></i>
                                  <strong>Local:</strong>
                                  <?= htmlspecialchars($at['local_atividade']) ?>
                                </p>
                              <?php endif; ?>
                              <?php if (!empty($at['palestrantes'])): ?>
                                <div class="atividade-palestrante">
                                  <p> <i class="fa fa-user-tie"></i>
                                    <strong>Palestrante:</strong>
                                    <?= htmlspecialchars($at['palestrantes'][0]['nome_palestrante']) ?>
                                  </p>
                                </div>
                              <?php endif; ?>
                            </div>
                          </div>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <p class="msg-vazia">Nenhuma atividade agendada.</p>
                      <?php endif; ?>
                    </div>
                  </section>
                  <hr class="divisor">
                  <section class="modal-section">
                    <h3 class="section-title">Convidados</h3>
                    <div class="convidados-grid">
                      <?php
                      $temConvidados = false;
                      if (!empty($ev['atividades'])):
                        foreach ($ev['atividades'] as $at):
                          if (!empty($at['palestrantes'])):
                            foreach ($at['palestrantes'] as $pal):
                              $temConvidados = true; ?>
                              <div class="card-convidado">
                                <img
                                  src="<?= (!empty($pal['foto_palestrante'])) ? '../assets/uploads/palestrantes/fotos_perfil/' . htmlspecialchars($pal['foto_palestrante']) : '../assets/uploads/palestrantes/fotos_perfil/org3.png'; ?>"
                                  class="foto-perfil" alt="Palestrante">
                                <div class="convidado-info">
                                  <h4><?= htmlspecialchars($pal['nome_palestrante']) ?></h4>
                                  <p class="tag-funcao"> PALESTRANTE </p>
                                  <p class="cargo"><?= htmlspecialchars($pal['grau_academico']) ?> |
                                    <?= htmlspecialchars($pal['cargo']) ?>
                                  </p>
                                  <p class="mini-bio"><?= htmlspecialchars($pal['mini_bio']) ?></p>
                                  <div class="social-links">
                                    <?php if (!empty($pal['instagram'])):
                                      $instagramLimpo = ltrim($pal['instagram'], '@'); ?>
                                      <a href="https://www.instagram.com/<?= $instagramLimpo; ?>/" target="_blank">
                                        <img src="../assets/img/instagram.png" alt="Instagram">
                                      </a>
                                    <?php endif; ?>
                                    <?php if (!empty($pal['linkedin_url'])): ?>
                                      <a href="<?= htmlspecialchars($pal['linkedin_url']) ?>" target="_blank">
                                        <img src="../assets/img/linkedin.png" alt="LinkedIn">
                                      </a>
                                    <?php endif; ?>
                                  </div>
                                </div>
                              </div>
                            <?php endforeach;
                          endif;
                          if (!empty($at['expositores'])):
                            foreach ($at['expositores'] as $exp):
                              $temConvidados = true; ?>
                              <div class="card-convidado">
                                <img
                                  src="<?= (!empty($exp['foto_expositor'])) ? '../assets/uploads/expositores/fotos_perfil/' . htmlspecialchars($exp['foto_expositor']) : '../assets/uploads/expositores/fotos_perfil/org3.png'; ?>"
                                  class="foto-perfil" alt="Expositor">
                                <div class="convidado-info">
                                  <h4><?= htmlspecialchars($exp['nome_expositor']) ?></h4>
                                  <p class="tag-funcao"> EXPOSITOR </p>
                                  <p class="cargo"><?= htmlspecialchars($exp['cargo']) ?> | <?= htmlspecialchars($exp['empresa']) ?>
                                  </p>
                                  <p class="mini-bio"><?= htmlspecialchars($exp['descricao']) ?></p>
                                  <div class="social-links">
                                    <?php
                                    $caminhoLogo = (!empty($exp['logo']))
                                      ? '../assets/uploads/expositores/logo_empresa/' . htmlspecialchars($exp['logo'])
                                      : '../assets/uploads/expositores/logo_empresa/unisuam.png';
                                    ?>

                                    <?php if (!empty($exp['link_empresa'])): ?>
                                      <a href="<?= formatarLinkExterno($exp['link_empresa']) ?>" target="_blank">
                                        <img src="<?= $caminhoLogo ?>" alt="Logo da Empresa">
                                      </a>
                                    <?php else: ?>
                                      <img src="<?= $caminhoLogo ?>" alt="Logo da Empresa">
                                    <?php endif; ?>
                                  </div>
                                </div>
                              </div>
                            <?php endforeach;
                          endif;
                        endforeach;
                      endif;
                      if (!$temConvidados): ?>
                        <p class="msg-vazia">Nenhum convidado listado.</p>
                      <?php endif; ?>
                    </div>
                  </section>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="erro-evento">Nenhum evento disponível no momento.</p>
          <?php endif; ?>
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