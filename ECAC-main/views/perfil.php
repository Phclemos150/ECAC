<?php
// 1. CHAMA O ARQUIVO DO CONTROLADOR
require_once __DIR__ . '/../controllers/AutentController.php';

// 2. ACIONA O GUARDA-COSTAS (Verifica se está logado)
$user = AutentController::verificarAcesso();

// 3. REDIRECIONA O ADMIN (A mágica acontece aqui)
// Se um Administrador clicar em "Meu Perfil" no menu, ele é mandado pro painel dele
if ($user['nome_funcao'] === 'Admin') {
    header("Location: painel_admin.php");
    exit;
}

// 4. RESGATE DOS DADOS
$nome_usuario = $user['nome'] ?? 'Usuário';
$email_usuario = $user['email'] ?? 'E-mail não informado';
$funcao_usuario = $user['nome_funcao'] ?? 'Visitante';

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
    <title>Meu Perfil - ECAC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/index.css"> 
    
    <style>
        .perfil-container {
            background-color: #ffffff;
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        .foto-perfil-grande {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #3f5d2a;
            margin-bottom: 15px;
        }
        .badge-funcao {
            display: inline-block;
            background-color: #f39c12;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 25px;
        }
        .dados-lista {
            text-align: left;
            margin-top: 20px;
        }
        .dados-lista p {
            font-size: 16px;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .dados-lista i {
            color: #3f5d2a;
            width: 25px;
        }
        .btn-voltar {
            display: inline-block;
            margin-top: 20px;
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

    <div class="perfil-container">
        <h2>Área do Usuário</h2>
        
        <img src="<?php echo htmlspecialchars($caminho_foto); ?>" alt="Foto de Perfil" class="foto-perfil-grande">
        
        <h3><?php echo htmlspecialchars($nome_usuario); ?></h3>
        <span class="badge-funcao"><?php echo htmlspecialchars($funcao_usuario); ?></span>

        <div class="dados-lista">
            <p><i class="fa fa-envelope"></i> <strong>E-mail:</strong> <?php echo htmlspecialchars($email_usuario); ?></p>
            <p><i class="fa fa-id-badge"></i> <strong>Nível de Acesso:</strong> <?php echo htmlspecialchars($funcao_usuario); ?></p>
        </div>

        <a href="index.php" class="btn-voltar"><i class="fa fa-arrow-left"></i> Voltar ao Início</a>
    </div>

</body>
</html>