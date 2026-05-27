<?php
class UsuarioModel
{

    private mysqli $con;

    public function __construct(mysqli $con)
    {
        $this->con = $con;
    }

    // Consulta do usuário pelo email
    // Consulta do usuário pelo email com permissões
    public function buscarUsuarioPorEmail(string $email): ?array
    {
        $sql = "SELECT 
                    usuario.id_usuario, 
                    usuario.nome_usuario, 
                    usuario.email, 
                    usuario.senha_hash, 
                    usuario.foto_perfil, 
                    usuario.status_conta,
                    funcao.id_funcao,
                    funcao.nome_funcao,
                    permissao.id_permissao,
                    permissao.nome_permissao,
                    permissao.descricao
                FROM usuario
                LEFT JOIN funcao_usuario ON usuario.id_usuario = funcao_usuario.usuario_id
                LEFT JOIN funcao ON funcao_usuario.funcao_id = funcao.id_funcao
                LEFT JOIN funcao_permissao ON funcao.id_funcao = funcao_permissao.funcao_id
                LEFT JOIN permissao ON funcao_permissao.permissao_id = permissao.id_permissao
                WHERE usuario.email = ?";

        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        $usuario = null;
        $permissoes = [];

        // O laço junta todas as permissões encontradas nas várias linhas
        while ($row = $result->fetch_assoc()) {
            if (!$usuario) {
                // Guarda os dados básicos apenas na primeira passagem
                $usuario = [
                    'id_usuario' => $row['id_usuario'],
                    'nome_usuario' => $row['nome_usuario'],
                    'email' => $row['email'],
                    'senha_hash' => $row['senha_hash'],
                    'foto_perfil' => $row['foto_perfil'],
                    'status_conta' => $row['status_conta'],
                    'id_funcao' => $row['id_funcao'],
                    'nome_funcao' => $row['nome_funcao']
                ];
            }
            // Adiciona o nome da permissão na lista
            if ($row['nome_permissao']) {
                $permissoes[] = $row['nome_permissao'];
            }
        }
        $stmt->close();

        // Anexa as permissões dentro dos dados do usuário
        if ($usuario) {
            $usuario['permissoes'] = $permissoes;
        }

        return $usuario;
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


    // Verifica se um dado (email ou documento) já existe no banco
    public function verificarDados(string $campo, string $valor): bool
    {
        // Proteção para aceitar apenas os campos corretos na query
        if ($campo !== 'email' && $campo !== 'documento') {
            return false;
        }

        $sql = "SELECT id_usuario FROM usuario WHERE $campo = ? LIMIT 1";
        $stmt = $this->con->prepare($sql);
        
        $stmt->bind_param("s", $valor);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $existe = $result->num_rows > 0;
        
        $stmt->close();

        return $existe;
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
            return (int) $novoUsuarioId; // Retorna o ID gerado para ser usado no Log!

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