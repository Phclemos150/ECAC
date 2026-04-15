<?php
// 1. CHAMA O ARQUIVO DO CONTROLADOR
require_once __DIR__ . '/../controllers/AutentController.php';

// 2. ACIONA O GUARDA-COSTAS (Verifica se está logado)
$user = AutentController::verificarAcesso();

// 3. TRAVA EXCLUSIVA DE ADMINISTRADOR
// Se o usuário estiver logado, mas NÃO for Admin, ele é bloqueado e mandado pro index.
if ($user['nome_funcao'] !== 'Admin') {
    header("Location: index.php");
    exit;
}

// 4. RESGATE DOS DADOS
$nome_usuario = $user['nome'] ?? 'Administrador';
$email_usuario = $user['email'] ?? 'E-mail não informado';

// Verifica se tem foto, senão usa a padrão
$caminho_foto = !empty($user['foto']) 
    ? "../assets/uploads/fotos_perfil/" . $user['foto'] 
    : "../assets/img/default-user.png";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - ECAC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/painel_admin.css">
    <script src="../assets/js/painel_admin.js"></script>
</head>
<body style="background-color: #f4f4f4;">
    <header>
    <div class="header-content">
      <button class="menu-toggle" onclick="toggleMenu()">
        <i class="fa fa-bars"></i>
      </button>
        <div class="header-title">
        <div class="logo-header"><img src="../assets/img/Só a Logo ECAC 2026.png" alt="a"></div>
        <a href="./index.php">
          <h1>Encontro Carioca de Alimentação Coletiva</h1>
        </a>
        </div>
        <div class="perfil">
            <h3>ADMIN</h3>
            <img src="<?php echo htmlspecialchars($caminho_foto); ?>" alt="Foto do Administrador" class="foto-perfil-admin">
        </div>
    </div>
  </header>
    <div class="layout">
        <div class="sidebar">
        <div style="background:#f1eada;padding:25px;text-align:center;border-bottom:1px solid #ccc;">
            <div class="logo"><img src="../assets/img/Apenas Logo Circulo.png" alt=""></div>
            <strong>E.C.A.C</strong><br>
        </div>
        <a href="./Painel_admin.php">
            <div class="sidebar-item active"><i class="fa fa-home"></i> Painel </div>
        </a>
        <a href="./Pessoas.php">
            <div class="sidebar-item"><i class="fa fa-calendar-check"></i> Pessoas </div>
        </a>
        </div>
        <div class="admin-container">
            <div id="Painel de Gerenciamento">
                <h2>Painel de Gerenciamento</h2>
                <img src="<?php echo htmlspecialchars($caminho_foto); ?>" alt="Foto do Administrador" class="foto-perfil-admin">
                <h4><?php echo htmlspecialchars($nome_usuario); ?></h4>
                <span class="badge-admin"><i class="fa fa-shield-alt"></i> <?php echo htmlspecialchars($user['nome_funcao']); ?></span>
                <div class="admin-mensagem">
                    <p><strong>Acesso Restrito:</strong> Bem-vindo à área de administração do sistema.</p>
                    <p>A partir daqui, você poderá gerenciar as contas de usuários, alterar funções, monitorar inscrições e configurar o site do Encontro Carioca de Alimentação Coletiva.</p>
                </div>
            </div>
            <div id="dashboard" style="display: none;">
                <h2>Dashboard</h2>
                <div class="cards">
                    <div class="card">
                        <h3>Usuários</h3>
                        <p>120 cadastrados</p>
                    </div>
                    <div class="card">
                        <h3>Pedidos</h3>
                        <p>45 em andamento</p>
                    </div>
                    <div class="card">
                        <h3>Receita</h3>
                        <p>R$ 12.300</p>
                    </div>
                </div>
            </div>
            <button id="btn-dashboard">Ver Dashboard</button>
            <a href="index.php" class="btn-voltar"><i class="fa fa-arrow-left"></i> Voltar a página principal</a>
        </div>
    </div>
</body>
</html>