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
    <link rel="stylesheet" href="../assets/css/index.css"> 
    
    <style>
        .admin-container {
            background-color: #ffffff;
            max-width: 800px;
            margin: 50px auto;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
            border-top: 6px solid #d9534f;
        }
        .foto-perfil-admin {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #d9534f;
            margin-bottom: 15px;
        }
        .badge-admin {
            display: inline-block;
            background-color: #d9534f;
            color: white;
            padding: 6px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 25px;
        }
        .admin-mensagem {
            font-size: 16px;
            color: #555;
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            border-left: 4px solid #d9534f;
        }
        .btn-voltar {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #3f5d2a;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }
        .btn-voltar:hover {
            background-color: #2c421d;
        }
    </style>
</head>
<body style="background-color: #f4f4f4;">

    <div class="admin-container">
        <h2>Painel de Gerenciamento</h2>
        
        <img src="<?php echo htmlspecialchars($caminho_foto); ?>" alt="Foto do Administrador" class="foto-perfil-admin">
        
        <h3><?php echo htmlspecialchars($nome_usuario); ?></h3>
        <span class="badge-admin"><i class="fa fa-shield-alt"></i> <?php echo htmlspecialchars($user['nome_funcao']); ?></span>

        <div class="admin-mensagem">
            <p><strong>Acesso Restrito:</strong> Bem-vindo à área de administração do sistema.</p>
            <p>A partir daqui, você poderá gerenciar as contas de usuários, alterar funções, monitorar inscrições e configurar o site do Encontro Carioca de Alimentação Coletiva.</p>
        </div>

        <a href="index.php" class="btn-voltar"><i class="fa fa-arrow-left"></i> Voltar ao Início</a>
    </div>

</body>
</html>