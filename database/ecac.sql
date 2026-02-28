DROP DATABASE IF EXISTS ecac;
CREATE DATABASE IF NOT EXISTS ecac;

USE ecac;

CREATE TABLE IF NOT EXISTS usuario (
    id_usuario INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    nome_usuario VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha_hash VARCHAR(128) NOT NULL,
    documento VARCHAR(18) NOT NULL UNIQUE,
    data_nascimento DATE NOT NULL,
    telefone VARCHAR(25),
    instagram VARCHAR(30),
    nivel_escolaridade ENUM('Ensino Fundamental', 'Ensino Médio', 'Graduação', 'Pós-graduação', 'Mestrado', 'Doutorado') NOT NULL,
    nome_curso VARCHAR(150) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    estado VARCHAR(2) NOT NULL,
    pais VARCHAR(50) NOT NULL,
    foto_perfil VARCHAR(255) NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status_conta ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo'
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS funcao (
    id_funcao INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    nome_funcao VARCHAR(50) NOT NULL UNIQUE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS funcao_usuario (
    id_funcao_usuario INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    funcao_id INT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (funcao_id) REFERENCES funcao(id_funcao) ON DELETE CASCADE ON UPDATE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS evento (
    id_evento INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    organizador_id INT NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT NOT NULL,
    local_evento VARCHAR(255) NOT NULL,
    data_evento DATE NOT NULL,
    horario_inicio TIME NOT NULL,
    horario_fim TIME NOT NULL,
    data_inscricao_inicio DATE NOT NULL,
    data_inscricao_fim DATE NOT NULL,
    modalidade ENUM('Presencial', 'Online', 'Hibrido') NOT NULL,
    status_evento ENUM('ativo', 'cancelado', 'concluido') NOT NULL DEFAULT 'ativo',
    capa_evento VARCHAR(255) NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (organizador_id) REFERENCES funcao_usuario(id_funcao_usuario) ON DELETE RESTRICT ON UPDATE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS atividade_evento (
    id_atividade_evento INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    evento_id INT NOT NULL, 
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT,
    tipo_atividade VARCHAR(50) NOT NULL,
    horario_inicio TIME NOT NULL,
    horario_fim TIME NOT NULL,
    local_atividade VARCHAR(100) NOT NULL,
    capacidade_max INT,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (evento_id) REFERENCES evento(id_evento) ON DELETE CASCADE ON UPDATE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS palestrante (
    id_palestrante INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    atividade_evento_id INT NOT NULL,
    nome_palestrante VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    telefone VARCHAR(25),
    grau_academico VARCHAR(100),
    nome_curso VARCHAR(150),
    cargo VARCHAR(100),
    linkedin_url VARCHAR(255),
    instagram VARCHAR(30),
    mini_bio TEXT NOT NULL,
    foto_palestrante VARCHAR(255) NOT NULL,
    FOREIGN KEY (atividade_evento_id) REFERENCES atividade_evento(id_atividade_evento) ON DELETE CASCADE ON UPDATE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS inscricao (
    id_inscricao INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    funcao_usuario_id INT NOT NULL,
    evento_id INT NOT NULL,
    categoria ENUM('estudante', 'profissional', 'convidado', 'inscrito'),
    status_inscricao ENUM('Pendente', 'Confirmado', 'Cortesia') NOT NULL DEFAULT 'pendente',
    data_inscricao DATE NOT NULL,
    valor_pago DECIMAL(10, 2) NULL,
    UNIQUE(funcao_usuario_id, evento_id),
    FOREIGN KEY (funcao_usuario_id) REFERENCES funcao_usuario(id_funcao_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (evento_id) REFERENCES evento(id_evento) ON DELETE CASCADE ON UPDATE CASCADE 
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS submissao (
    id_submissao INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    evento_id INT NOT NULL,
    funcao_usuario_id INT NOT NULL,
    titulo VARCHAR(250) NOT NULL,
    resumo TEXT NOT NULL,
    palavras_chave VARCHAR(255), 
    status_arquivo ENUM('enviado', 'em avaliação', 'aceito', 'recusado') NOT NULL DEFAULT 'enviado',
    caminho_arquivo VARCHAR(255) NOT NULL,
    data_envio DATE NOT NULL,
    hora_envio TIME NOT NULL,
    UNIQUE(evento_id, funcao_usuario_id),
    FOREIGN KEY (evento_id) REFERENCES evento(id_evento) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (funcao_usuario_id) REFERENCES funcao_usuario(id_funcao_usuario) ON DELETE CASCADE ON UPDATE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS coautores (
    id_coautor INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    submissao_id INT NOT NULL,
    nome_coautor VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    instituicao VARCHAR(255) NOT NULL,
    FOREIGN KEY (submissao_id) REFERENCES submissao(id_submissao) ON DELETE CASCADE ON UPDATE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS avaliacao (
    id_avaliacao INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    submissao_id INT NOT NULL,
    funcao_usuario_id INT NOT NULL,
    nota DECIMAL(5,2) NOT NULL,
    parecer TEXT,
    recomendacao ENUM('aceito', 'rejeitado', 'corrigir'),
    data_avaliacao DATE NOT NULL,
    hora_avaliacao TIME NOT NULL,
    UNIQUE(submissao_id, funcao_usuario_id), 
    FOREIGN KEY (submissao_id) REFERENCES submissao(id_submissao) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (funcao_usuario_id) REFERENCES funcao_usuario(id_funcao_usuario) ON DELETE CASCADE ON UPDATE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS expositor (
    id_expositor INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    atividade_evento_id INT NOT NULL,
    nome_expositor VARCHAR(255) NOT NULL,
    cargo VARCHAR(100) NOT NULL,
    empresa VARCHAR(150) NOT NULL,
    logo VARCHAR(255) NOT NULL,
    site VARCHAR(255) NOT NULL,
    descricao TEXT,
    tipo_espaco ENUM('estande', 'mesa') NOT NULL,
    necessidades_tecnicas TEXT,
    foto_expositor VARCHAR(255),
    UNIQUE(evento_id, funcao_usuario_id),
    FOREIGN KEY (evento_id) REFERENCES evento(id_evento) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (funcao_usuario_id) REFERENCES funcao_usuario(id_funcao_usuario) ON DELETE CASCADE ON UPDATE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS patrocinador (
    id_patrocinador INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    evento_id INT NOT NULL,
    funcao_usuario_id INT NOT NULL,
    empresa VARCHAR(150) NOT NULL,
    logo VARCHAR(255) NULL,
    site VARCHAR(255) NULL,
    nivel_patrocinio ENUM('bronze', 'prata', 'ouro') NOT NULL,
    beneficios TEXT NULL,
    FOREIGN KEY (evento_id) REFERENCES evento(id_evento) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (funcao_usuario_id) REFERENCES funcao_usuario(id_funcao_usuario) ON DELETE CASCADE ON UPDATE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS certificado (
    id_certificado INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    funcao_usuario_id INT NOT NULL,
    evento_id INT NOT NULL,
    tipo ENUM('participacao', 'apresentacao', 'avaliacao', 'palestrante') NOT NULL,
    data_emissao DATE NOT NULL,
    codigo_validacao VARCHAR(50) NOT NULL UNIQUE,
    UNIQUE(funcao_usuario_id, evento_id, tipo),
    FOREIGN KEY (funcao_usuario_id) REFERENCES funcao_usuario(id_funcao_usuario) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (evento_id) REFERENCES evento(id_evento) ON DELETE CASCADE ON UPDATE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS log_sistema (
    id_log INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    funcao_usuario_id INT NOT NULL,
    acao VARCHAR(100) NOT NULL,
    entidade_afetada VARCHAR(50) NOT NULL,
    id_entidade INT NOT NULL,
    data_log DATE NOT NULL,
    hora_log TIME NOT NULL,
    FOREIGN KEY (funcao_usuario_id) REFERENCES funcao_usuario(id_funcao_usuario) ON DELETE CASCADE ON UPDATE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Inserção das funções solicitadas
INSERT INTO funcao (nome_funcao) VALUES 
('Admin'), 
('Organizador'), 
('Staff'), 
('Comissão Organizadora'),
('Comissão Ciêntifica'), 
('Patrocinador'), 
('Autor'), 
('Inscrito'), 
('Usuario');