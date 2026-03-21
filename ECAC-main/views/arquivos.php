<?php
require_once __DIR__ . '/../controllers/ArquivosController.php';

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
  <link rel="stylesheet" href="../assets/css/arquivos.css">
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
        <div class="sidebar-item active"><i class="fa fa-download"></i> Arquivos </div>
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
        <h2>Arquivos e Publicações do Encontro</h2>
        <p class="subtitle">
          Aqui você encontra os anais, guias e publicações oficiais do congresso.
        </p>

        <div class="filter-box">
          <form class="filter-form-inline" method="GET" action="arquivos.php">
            <div class="filter-field search-field">
              <label for="busca">Pesquisa Geral</label>
              <input type="text" id="busca" name="termo"
                placeholder="Procure por Título, autor, coautor ou palavras-chave">
            </div>

            <div class="filter-field select-field">
              <label for="evento">Evento</label>
              <select id="evento" name="evento">
                <option value="">Selecione o Evento</option>
                <?php foreach ($filtros['eventos'] as $ev): ?>
                  <option value="<?php echo $ev['id_evento']; ?>" <?php echo (isset($_GET['evento']) && $_GET['evento'] == $ev['id_evento']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($ev['titulo']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="filter-field select-field">
              <label for="ano">Ano</label>
              <select id="ano" name="ano">
                <option value="">Selecione o ano</option>
                <?php foreach ($filtros['anos'] as $a): ?>
                  <option value="<?php echo $a['ano']; ?>" <?php echo (isset($_GET['ano']) && $_GET['ano'] == $a['ano']) ? 'selected' : ''; ?>>
                    <?php echo $a['ano']; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="filter-actions-inline">
              <button type="submit" class="btn-filtrar-icon">
                Filtrar
              </button>
              <a href="../views/arquivos.php" type="reset" class="btn-limpar-text">
                Limpar
              </a>
            </div>
          </form>
        </div>

        <div class="file-list">
          <div class="file-header">
            <div class="col-titulo">Título do Arquivo</div>
            <div class="col-evento">Evento</div>
            <div class="col-autor">Autor</div>
            <div class="col-resumo">Resumo</div>
            <div class="col-data">Data</div>
            <div class="col-arquivo">Arquivo</div>
          </div>

          <?php if (!empty($listaArquivos)): ?>
            <?php foreach ($listaArquivos as $arq): ?>
              <div class="file-item-row">
                <div class="col-titulo">
                  <strong><?php echo htmlspecialchars($arq['titulo']); ?></strong>
                </div>
                <div class="col-evento">
                  <span class="evento-tag"><?php echo htmlspecialchars($arq['evento_titulo']); ?></span>
                </div>
                <div class="col-autor">
                  <span><?php echo htmlspecialchars($arq['autor_nome']); ?></span>

                  <?php if (!empty($arq['coautores'])): ?>
                    <?php
                    $jsonCoautores = htmlspecialchars(json_encode($arq['coautores']));
                    ?>
                    <button class="btn-coautor" onclick='abrirModalCoautores(<?php echo $jsonCoautores; ?>)'
                      title="Ver coautores">
                      +<?php echo count($arq['coautores']); ?>
                    </button>
                    </button>
                  <?php endif; ?>
                </div>

                <div class="col-resumo">
                  <p class="resumo-texto" title="<?php echo htmlspecialchars($arq['resumo']); ?>">
                    <?php echo htmlspecialchars($arq['resumo']); ?>
                  </p>
                </div>

                <div class="col-data">
                  <?php echo date('d/m/Y', strtotime($arq['data_evento'])); ?>
                </div>

                <div class="col-arquivo">
                  <a href="../assets/uploads/arquivos/<?php echo $arq['caminho_arquivo']; ?>" download
                    class="btn-download-table">
                    <i class="fa fa-download"></i> Baixar
                  </a>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="file-item-row no-results">
              <div class="file-item-error">
                <i class="fa fa-search-minus file-icon"></i>
                <p>Nenhum arquivo encontrado para os filtros selecionados.</p>
                <a href="arquivos.php" class="file-a">Limpar todos os filtros</a>
              </div>
            </div>
          <?php endif; ?>
        </div>
        <?php if ($totalPaginas > 1): ?>
          <div class="pagination">
            <?php if ($paginaAtual > 1): ?>
              <a href="?pagina=<?php echo $paginaAtual - 1; ?>&termo=<?php echo $termo; ?>&evento=<?php echo $eventoId; ?>&ano=<?php echo $ano; ?>"
                class="page-link">
                <i class="fa fa-chevron-left"></i>
              </a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
              <a href="?pagina=<?php echo $i; ?>&termo=<?php echo $termo; ?>&evento=<?php echo $eventoId; ?>&ano=<?php echo $ano; ?>"
                class="page-link <?php echo ($i == $paginaAtual) ? 'active' : ''; ?>">
                <?php echo $i; ?>
              </a>
            <?php endfor; ?>

            <?php if ($paginaAtual < $totalPaginas): ?>
              <a href="?pagina=<?php echo $paginaAtual + 1; ?>&termo=<?php echo $termo; ?>&evento=<?php echo $eventoId; ?>&ano=<?php echo $ano; ?>"
                class="page-link">
                <i class="fa fa-chevron-right"></i>
              </a>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  </div>
  </div>

  <!-- Modal Coautores -->
  <div id="modalCoautores" class="modal-overlay">
    <div class="modal-card">
      <div class="modal-header">
        <h3>Coautores do Arquivo</h3>
        <button class="close-btn" onclick="fecharModal()">&times;</button>
      </div>
      <div class="modal-body">
        <table class="table-modal">
          <thead>
            <tr>
              <th>Nome</th>
              <th>Instituição</th>
            </tr>
          </thead>
          <tbody id="conteudo-modal">
          </tbody>
        </table>
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
  <script src="../assets/js/arquivos.js"></script>
</body>

</html>