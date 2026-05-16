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
                ORDER BY f.id_funcao ASC 
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
        $colunasPermitidas = ['email', 'documento']; 
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
            return false; 
        }

        if ($usuario['status_conta'] !== 'ativo') {
            return false; 
        }

        return password_verify($senha, $usuario['senha_hash']);
    }

    // Cadastro de Usuário
    public function cadastrarUsuario(array $dados): bool
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
                throw new Exception("Erro ao inserir usuário no banco de dados.");
            }

            $novoUsuarioId = $this->con->insert_id;
            $stmt->close();

            /* Busca a função Usuario ou Usuário (com ou sem acento para evitar erros) */
            $sqlFuncao = "SELECT id_funcao FROM funcao WHERE TRIM(nome_funcao) = 'Usuario' OR TRIM(nome_funcao) = 'Usuário' LIMIT 1";
            $result = $this->con->query($sqlFuncao);

            if ($result && $result->num_rows > 0) {
                $funcao = $result->fetch_assoc();
                $funcaoId = (int) $funcao["id_funcao"];
            } else {
                // Tenta pegar qualquer função disponível como contingência
                $resultFallback = $this->con->query("SELECT id_funcao FROM funcao LIMIT 1");
                if ($resultFallback && $resultFallback->num_rows > 0) {
                    $funcao = $resultFallback->fetch_assoc();
                    $funcaoId = (int) $funcao["id_funcao"];
                } else {
                    // Se o banco estiver totalmente sem dados cadastrados, exibe o diagnóstico real
                    throw new Exception("A tabela 'funcao' está completamente vazia. Você esqueceu de executar os INSERTs do arquivo 'ecac_modelo_preenchido.sql' no seu phpMyAdmin.");
                }
            }

            $sqlVinculo = "INSERT INTO funcao_usuario (usuario_id, funcao_id) VALUES (?, ?)";
            $stmtVinculo = $this->con->prepare($sqlVinculo);
            $stmtVinculo->bind_param("ii", $novoUsuarioId, $funcaoId);

            if (!$stmtVinculo->execute()) {
                throw new Exception("Erro ao vincular função ao usuário.");
            }

            $stmtVinculo->close();
            $this->con->commit();
            return true;

        } catch (Throwable $e) {
            $this->con->rollback();
            die("Erro no Banco: " . $e->getMessage());
            return false;
        }
    }

    /* Função para Validar Recuperação de Senha */
    public function validarUsuarioRecuperacao(string $email, string $documento): ?array
    {
        $sql = "SELECT id_usuario FROM usuario WHERE email = ? AND documento = ? LIMIT 1";

        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("ss", $email, $documento);
        $stmt->execute();

        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc() ?: null;
        $stmt->close();

        return $usuario;
    }

    /* Função para Atualizar a Senha */
    public function atualizarSenha(string $email, string $senhaHash): bool
    {
        $sql = "UPDATE usuario SET senha_hash = ? WHERE email = ?";

        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("ss", $senhaHash, $email);

        $resultado = $stmt->execute();
        $stmt->close();

        return $resultado;
    }


    // =========================================================================
    // REGISTRO DE LOGS DO SISTEMA DIAGNÓSTICO
    // =========================================================================
    public function registrarLogSistema(?int $usuarioId, ?int $funcaoId, string $acao, string $entidade = 'autenticacao', int $idEntidade = 0): void
    {
        $sql = "INSERT INTO log_sistema (usuario_id, funcao_id, acao, entidade_afetada, id_entidade, data_log, hora_log) VALUES (";
        $sql .= ($usuarioId === null) ? "NULL, " : "?, ";
        $sql .= ($funcaoId === null) ? "NULL, " : "?, ";
        $sql .= "?, ?, ?, CURDATE(), CURTIME())";

        $stmt = $this->con->prepare($sql);

        if ($stmt) {
            if ($usuarioId !== null && $funcaoId !== null) {
                $stmt->bind_param("iissi", $usuarioId, $funcaoId, $acao, $entidade, $idEntidade);
            } elseif ($usuarioId !== null) {
                $stmt->bind_param("issi", $usuarioId, $acao, $entidade, $idEntidade);
            } elseif ($funcaoId !== null) {
                $stmt->bind_param("issi", $funcaoId, $acao, $entidade, $idEntidade);
            } else {
                $stmt->bind_param("ssi", $acao, $entidade, $idEntidade);
            }

            if (!$stmt->execute()) {
                // Força o erro a aparecer na tela para descobrirmos o bloqueio da tabela de logs
                die("🚨 ERRO NO BANCO AO GRAVAR LOG: " . $stmt->error . " | Certifique-se de que rodou o ALTER TABLE modificando as colunas para NULL.");
            }
            $stmt->close();
        }
    }

}
?>