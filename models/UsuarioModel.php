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
        $sql = "SELECT id_usuario, nome_usuario, email, senha_hash, foto_perfil, status_conta FROM usuario WHERE email = ? LIMIT 1";

        $stmt = $this->con->prepare($sql); // Compila a consulta da váriavel sql
        $stmt->bind_param("s", $email); // Define como texto(string) o email, impedindo um comando que altere o sql
        $stmt->execute(); // Executa a consulta

        $result = $stmt->get_result(); // Recebe o resultado da consulta
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
    // Cadastro de Usuário
    public function cadastrarUsuario(array $dados): bool
    {
        $this->con->begin_transaction();

        try {
            $sql = "INSERT INTO usuario (
            nome_usuario, email, senha_hash, documento, data_nascimento, telefone, instagram, nivel_escolaridade,
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
                $dados["nivel_escolaridade"],
                $dados["nome_curso"],
                $dados["cidade"],
                $dados["estado"],
                $dados["pais"],
                $dados["foto_perfil"]
            );

            /* Verifica se ocorreu um erro ao inserir o usuário */
            if (!$stmt->execute()) {
                $stmt->close();
                throw new Exception("Erro ao inserir usuário");
            }

            $novoUsuarioId = $this->con->insert_id;
            $stmt->close();

            /* Busca o ID da função Usuário, que só está cadastrado no site */
            $sqlFuncao = "SELECT id_funcao FROM funcao WHERE nome_funcao = 'Usuario' LIMIT 1";
            $result = $this->con->query($sqlFuncao);

            // Só pode existir uma função por nome, caso tiver mais de 1 não será cadastrado na tabela funcao_usuario
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
            return true;

        } catch (Throwable $e) {
            $this->con->rollback();
            die("Erro no Banco: " . $e->getMessage());
            return false;
        }
    }

}
?>