<?php
class UsuarioModel
{

    private mysqli $con;

    public function __construct(mysqli $con)
    {
        $this->con = $con;
    }

    // Consulta do usuário pelo email
    public function buscarUsuarioPorEmail(string $email): ?array
    {
        $sql = "SELECT 
                    u.id_usuario, 
                    u.nome_usuario, 
                    u.email, 
                    u.senha_hash, 
                    u.foto_perfil, 
                    u.status_conta,
                    f.id_funcao,
                    f.nome_funcao
                FROM usuario u
                LEFT JOIN funcao_usuario fu ON u.id_usuario = fu.usuario_id
                LEFT JOIN funcao f ON fu.funcao_id = f.id_funcao
                WHERE u.email = ? 
                LIMIT 1";

        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();   

        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc() ?: null;
        $stmt->close();

        return $usuario;
    }

    // Consulta caso o usuário queria cadastrar um email ou um cpf que já exista
    public function verificarDados(string $coluna, string $valor): bool
    {
        $colunasPermitidas = ['email', 'documento']; // Apenas o email e o cpf precisam ser únicos
        if (!in_array($coluna, $colunasPermitidas)) {
            return false;
        }

        $sql = "SELECT id_usuario FROM usuario WHERE $coluna = ? LIMIT 1";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $valor);
        $stmt->execute();
        $result = $stmt->get_result();
        $existe = $result->num_rows > 0;
        $stmt->close();

        return $existe;
    }

    // Consulta para autenticar Usuário 
    public function autenticarLogin(string $email, string $senha): bool
    {
        $usuario = $this->buscarUsuarioPorEmail($email);

        if (!$usuario) {
            return false; // Caso não encontre o usuário no DB
        }

        if ($usuario['status_conta'] !== 'ativo') {
            return false; // Caso a conta não estiver ativa
        }

        return password_verify($senha, $usuario['senha_hash']);
    }

    // Cadastro de Usuário (Modificado para retornar o ID ou null)
    public function cadastrarUsuario(array $dados): ?int
    {
        $this->con->begin_transaction();

        try {
            $sql = "INSERT INTO usuario (
            nome_usuario, email, senha_hash, documento, data_nascimento, telefone, instagram, grau_academico,
            nome_curso, cidade, estado, pais, foto_perfil
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->con->prepare($sql);
            $stmt->bind_param(
                "sssssssssssss",
                $dados["nome"],
                $dados["email"],
                $dados["senha_hash"],
                $dados["documento"],
                $dados["data_nascimento"],
                $dados["telefone"],
                $dados["instagram"],
                $dados["grau_academico"],
                $dados["nome_curso"],
                $dados["cidade"],
                $dados["estado"],
                $dados["pais"],
                $dados["foto_perfil"]
            );

            if (!$stmt->execute()) {
                $stmt->close();
                throw new Exception("Erro ao inserir usuário");
            }

            $novoUsuarioId = $this->con->insert_id;
            $stmt->close();

            $sqlFuncao = "SELECT id_funcao FROM funcao WHERE nome_funcao = 'Usuario' LIMIT 1";
            $result = $this->con->query($sqlFuncao);

            if ($result->num_rows !== 1) {
                throw new Exception("Função inválida");
            }

            $funcao = $result->fetch_assoc();
            $funcaoId = $funcao["id_funcao"];

            $sqlVinculo = "INSERT INTO funcao_usuario (usuario_id, funcao_id) VALUES (?, ?)";
            $stmtVinculo = $this->con->prepare($sqlVinculo);
            $stmtVinculo->bind_param("ii", $novoUsuarioId, $funcaoId);

            if (!$stmtVinculo->execute()) {
                throw new Exception("Erro ao vincular função");
            }

            $stmtVinculo->close();
            $this->con->commit();
            return (int)$novoUsuarioId; // Retorna o ID gerado para ser usado no Log!

        } catch (Throwable $e) {
            $this->con->rollback();
            // Evitamos usar die() para não quebrar a tela do usuário. O Controller lida com a falha.
            return null;
        }
    }

    // Alterado para buscar também a função do usuário para o LOG
    public function validarUsuarioRecuperacao(string $email, string $documento): ?array
    {
        $sql = "SELECT u.id_usuario, fu.funcao_id 
                FROM usuario u
                LEFT JOIN funcao_usuario fu ON u.id_usuario = fu.usuario_id
                WHERE u.email = ? AND u.documento = ? LIMIT 1";
        
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("ss", $email, $documento);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc() ?: null;
        $stmt->close();

        return $usuario;
    }

    public function atualizarSenha(string $email, string $senhaHash): bool
    {
        $sql = "UPDATE usuario SET senha_hash = ? WHERE email = ?";
        
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("ss", $senhaHash, $email);
        
        $resultado = $stmt->execute();
        $stmt->close();

        return $resultado;
    }

    // =====================================================================
    // FUNÇÃO DE LOG DE SISTEMA 
    // =====================================================================
    public function registrarLog(?int $usuario_id, ?int $funcao_id, string $acao, ?string $detalhes, string $entidade_afetada, int $id_entidade): void
    {
        $sql = "INSERT INTO log_sistema (usuario_id, funcao_id, acao, detalhes, entidade_afetada, id_entidade, data_log, hora_log) 
                VALUES (?, ?, ?, ?, ?, ?, CURDATE(), CURTIME())";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("iisssi", $usuario_id, $funcao_id, $acao, $detalhes, $entidade_afetada, $id_entidade);
        $stmt->execute();
        $stmt->close();
    }
}
?>  