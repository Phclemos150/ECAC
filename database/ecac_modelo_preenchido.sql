USE ecac;

-- 2.1 FUNÇÕES (Atores extraídos do seu Diagrama UML)
INSERT INTO funcao (id_funcao, nome_funcao) VALUES
(1, 'Super-Admin'),
(2, 'Comissão Organizadora'), 
(3, 'Staff / Credenciamento'),
(4, 'Comissão Acadêmica'),
(5, 'Comissão Científica'),
(6, 'Autor / Apresentador'),
(7, 'Participante'), 
(8, 'Usuario'),
(9, 'Palestrante'),
(10, 'Coordenação da Comissão Científica');

-- 2.2 PERMISSÕES (Os balões de Casos de Uso do UML)
INSERT INTO permissao (id_permissao, nome_permissao, descricao) VALUES
(1, 'ver_painel_admin', 'Acesso base ao painel e menus'),
(2, 'gerenciar_usuarios', 'Gerenciar usuários e permissões'),
(3, 'criar_evento', 'Criar e configurar evento'),
(4, 'cadastrar_auxiliar', 'Cadastrar Auxiliar (Nutricionista)'),
(5, 'excluir_evento', 'Excluir Evento (Alterar Status)'),
(6, 'aprovar_inscricoes', 'Aprovar inscrições'),
(7, 'emitir_relatorios', 'Emitir relatórios gerenciais'),
(8, 'baixar_certificado', 'Baixar certificado'),
(9, 'realizar_inscricao', 'Realizar inscrição e pagamento'),
(10, 'consultar_inscritos', 'Consultar lista de inscrições'),
(11, 'marcar_presenca', 'Marcar presença / Credenciamento'),
(12, 'emitir_certificados', 'Emitir certificados (Sistema)'),
(13, 'entregar_crachas', 'Entregar crachás e kits'),
(14, 'preencher_perfil_palestrante', 'Preencher perfil e detalhes da palestra'),
(15, 'definir_diretrizes', 'Definir diretrizes e eixos temáticos'),
(16, 'organizar_programacao', 'Organizar programação científica'),
(17, 'publicar_anais', 'Publicar nos anais'),
(18, 'configurar_submissao', 'Configurar sistema de submissão'),
(19, 'atribuir_avaliadores', 'Recrutar/Atribuir avaliadores'),
(20, 'homologar_resultados', 'Gerenciar e homologar resultados'),
(21, 'avaliar_trabalhos', 'Avaliar trabalhos (Parecer/Nota)'),
(22, 'decidir_formatacao', 'Decidir formatação e questões críticas'),
(23, 'submeter_trabalho', 'Submeter trabalho'),
(24, 'acompanhar_trabalho', 'Acompanhar próprio trabalho');

-- 2.3 LIGAÇÃO (O Molho de Chaves ligando os bonecos aos balões do UML)
INSERT INTO funcao_permissao (funcao_id, permissao_id) VALUES
-- Super Admin (Permissão total do sistema)
(1,1), (1,2), (1,3), (1,4), (1,5), (1,6), (1,7), (1,8), (1,9), (1,10), (1,11), (1,12), (1,13), (1,14), (1,15), (1,16), (1,17), (1,18), (1,19), (1,20), (1,21), (1,22), (1,23), (1,24),
-- Comissão Organizadora
(2,1), (2,3), (2,4), (2,5), (2,6), (2,7), (2,8), (2,9), (2,10), (2,11), (2,12), (2,13), (2,14), (2,15), (2,16), (2,17), (2,18), (2,19), (2,20),
-- Staff / Credenciamento
(3,1), (3,10), (3,11), (3,13),
-- Comissão Científica
(5,1), (5,18), (5,19), (5,20), (5,21),
-- Autor / Apresentador
(6,23), (6,24),
-- Participante
(7,8), (7,9),
-- Palestrante
(9,14),
-- Coordenação da Comissão Científica
(10,1), (10,18), (10,19), (10,20), (10,21), (10,22);

-- 2.4 DADOS DOS USUÁRIOS DE TESTE
INSERT INTO `usuario` (`id_usuario`, `nome_usuario`, `email`, `senha_hash`, `documento`, `data_nascimento`, `telefone`, `instagram`, `grau_academico`, `nome_curso`, `cidade`, `estado`, `pais`, `foto_perfil`, `data_criacao`, `data_atualizacao`, `status_conta`) VALUES
(1, 'Roberta', 'roberta@email.com', '$2y$10$KP2tANvF1EUwMXy1N1lCo.vqCz9Xg/No4NvhoV7c2Qq28OdQ8bbfC', '111.111.111-11', '2026-02-16', '(21) 99999-9999', '@roberta', 'Pós-graduação', 'Nutrição', 'Rio de Janeiro', 'RJ', 'Brasil', 'user_69ab0ba51f762.png', '2026-02-16 15:47:13', '2026-02-16 15:47:13', 'ativo'),
(2, 'Raquel', 'raquel@email.com', '$2y$10$AiuC45d1Zi1lR8hJeOVz3.ZgqYhuhAMxjKY5FwDMA64i.eZbQRbWC', '222.222.222-22', '2026-02-20', '(21) 99999-9999', '@raquel', 'Graduação', 'Marketing', 'Rio de Janeiro', 'RJ', 'Brasil', 'user_69ab0b3a4a35d.png', '2026-02-16 15:47:13', '2026-02-16 15:47:13', 'ativo' ),
(3, 'João', 'joao@email.com', '$2y$10$lQKpT9XsZi6.vnF0URfkb.INRg35nKzvZNAqfWTA4DH8qT9.jHYOS', '333.333.333-33', '2000-08-10', '(21) 99999-9999', '@joao', 'Mestrado', 'Gestão de Recursos Humanos', 'Rio de Janeiro', 'RJ', 'Brasil', 'user_69ab0c0521b86.png', '2026-02-16 15:47:13', '2026-02-16 15:47:13', 'ativo' ),
(4, 'Caio Silva', 'caio@email.com', '$2y$10$rkLUY4aoiXlyXac1f0ei0OVWZUJaXyl4Xg7IelVSpnlzsQaZ8Tw/2', '444.444.444-44', '2026-03-12', '(21) 94444-4444', '@caio', 'Graduação', 'Nutrição', 'Rio de Janeiro', 'RJ', 'Brasil', '' ,'2026-03-12 11:22:44', '2026-03-12 11:32:23', 'ativo'),
(5, 'Felipe Santos', 'felipe@email.com', '$2y$10$rX5mSGTDPSNb5miB9hjbhOe.UTEfHLDgM5dCChH/QDNtl/3a.eZwG', '555.555.555-55', '2026-03-12', '(21) 95555-5555', '@felipe', 'Graduação', 'Nutrição', 'Rio de Janeiro', 'RJ', 'Brasil', '', '2026-03-12 11:38:10', '2026-03-12 11:38:10', 'ativo'),
(6, 'Marcela Costa', 'marcela@email.com', '$2y$10$uxVBPDk/z2JbMXy0IuH4Geagr1pheItrJQEEwyrnWRYQLcRdUuFNi', '666.666.666-66', '2026-03-12', '(21) 96666-6666', '@marcela', 'Pós-graduação', 'Nutrição', 'Rio de Janeiro', 'RJ', 'Brasil', '', '2026-03-12 11:39:12', '2026-03-12 11:39:12', 'ativo'),
(7, 'Verônica Almeida', 'veronica@email.com', '$2y$10$XnGrjftI1EAJtQFzk73vXeCnIKZxGqXjp0CrjN1W4SGFLZL6EURLe', '777.777.777-77', '2026-03-12', '(21) 97777-7777', '@veronica', 'Mestrado', 'Nutrição', 'Rio de Janeiro', 'RJ', 'Brasil', '', '2026-03-12 11:39:54', '2026-03-12 11:39:54', 'ativo'),
(8, 'Rebeca Mattos', 'rebeca@email.com', '$2y$10$wd/.1jVNJ3b6ovVGkPtz4.ehulu2U5F1Vu2d6wHLtJBaFKkBvPMjO', '888.888.888-88', '2026-03-12', '(21) 98888-8888', '@rebeca', 'Graduação', 'Nutrição', 'Rio de Janeiro', 'RJ', 'Brasil', '', '2026-03-12 11:40:54', '2026-03-12 11:40:54', 'ativo');

-- 2.5 ATRIBUIÇÃO DE CARGOS AOS USUÁRIOS
INSERT INTO `funcao_usuario` (`id_funcao_usuario`, `usuario_id`, `funcao_id`) VALUES
(1, 1, 2), -- Roberta -> Comissão Organizadora
(2, 2, 3), -- Raquel -> Staff
(3, 3, 4), -- João -> Comissão Acadêmica
(4, 4, 6), -- Caio -> Autor
(5, 5, 6), -- Felipe -> Autor
(6, 6, 6), -- Marcela -> Autor
(7, 7, 6), -- Verônica -> Autor
(8, 8, 6); -- Rebeca -> Autor

-- 2.6 MODELOS DE EVENTOS E ENTIDADES DEPENDENTES
INSERT INTO `evento` (`id_evento`, `organizador_id`, `titulo`, `descricao`, `local_evento`, `data_evento`, `horario_inicio`, `horario_fim`, `data_inscricao_inicio`, `data_inscricao_fim`, `modalidade`, `status_evento`, `capa_evento`, `data_criacao`, `data_atualizacao`) VALUES
(1, 1, '1ª Edição do Encontro Carioca de Alimentação Coletiva', 'O Encontro Carioca de Alimentação Coletiva nasceu...', 'Auditório Sylvia Bisaggio - UNISUAM Bonsucesso', '2025-09-27', '08:30:00', '16:30:00', '2025-08-11', '2025-09-15', 'Presencial', 'concluido', '../assets/img/ECAC Banner.png', '2026-02-04 13:44:39', '2026-02-04 13:51:43'),
(2, 1, '2ª Edição do Encontro Carioca de Alimentação Coletiva', 'O Encontro Carioca de Alimentação Coletiva nasceu...', 'Auditório UNISUAM Bonsucesso', '2026-02-20', '18:00:00', '21:00:00', '2026-02-02', '2026-02-16', 'Presencial', 'ativo', '../assets/img/ECAC Banner.png', '2026-02-04 13:51:32', '2026-02-04 13:51:32');

INSERT INTO `atividade_evento` (`id_atividade_evento`, `evento_id`, `titulo`, `descricao`, `tipo_atividade`, `horario_inicio`, `horario_fim`, `local_atividade`, `capacidade_max`, `data_criacao`, `data_atualizacao`) VALUES
(1, 1, 'Credenciamento e Boas-vindas', 'Receber e direcionar os inscritos.', 'Pré-Evento', '08:30:00', '09:00:00', 'Auditório UNISUAM', 200, '2026-02-04 13:53:54', '2026-02-04 13:58:22');

INSERT INTO `palestrante` (`id_palestrante`, `atividade_evento_id`, `nome_palestrante`, `email`, `telefone`, `grau_academico`, `nome_curso`, `cargo`, `linkedin_url`, `instagram`, `mini_bio`, `foto_palestrante`) VALUES
(1, 1, 'Cíntia Teixeira', 'cintia@email.com', '(21) 91111-1111', 'Doutorado', 'Ciências', 'Pesquisadora', '', '', 'Uma mini bio aqui...', 'user_69e0ed591f85f.jpeg');

INSERT INTO `expositor` (`id_expositor`, `atividade_evento_id`, `nome_expositor`, `email`, `telefone` , `empresa` , `cargo`, `logo`, `link_empresa`, `linkedin_url`, `instagram` , `descricao`, `tipo_espaco`, `necessidades_tecnicas`, `foto_expositor`) VALUES
(1, 1, 'André Matos', 'andrematos@email.com' , '(21) 99999-9999' , 'Nutri' , 'Vendedor', 'logo nutri.png', 'www.google.com.br', 'https://br.linkedin.com/', '@nutri' , 'Breve descrição...', 'estande', 'Tomada', 'org3.png');

INSERT INTO `patrocinador` (`id_patrocinador`, `nome_empresa`, `logo`, `site_empresa`, `nivel_patrocinio`, `beneficios`) VALUES
(1, 'UNISUAM', 'unisuam.png', 'https://www.unisuam.edu.br/', 'bronze', '');

INSERT INTO `submissao` (`evento_id`, `funcao_usuario_id` , `titulo`, `resumo`, `palavras_chave`, `status_arquivo`, `caminho_arquivo`, `data_envio`, `hora_envio`) VALUES
(2, 4, 'Nutrição de Alimentos', 'Descrição detalhada...', 'Nutrição', 'enviado', 'trab_69b2cc64.pdf', '2026-03-12', '11:23:32');

INSERT INTO `coautores` (`submissao_id`, `nome_coautor`, `email`, `instituicao`) VALUES
(1, 'João', 'joaoaux@email.com', 'Uni1');

INSERT INTO `comissao_org` (`id_comissao_org`, `funcao_usuario_id`, `funcao_org`, `linkedin_url`) VALUES
(1, 1, 'Organizadora', 'https://br.linkedin.com/');