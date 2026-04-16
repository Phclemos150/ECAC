USE ecac;

INSERT INTO funcao (nome_funcao) VALUES 
('Admin'), 
('Organizador'), 
('Staff'), 
('Comissão Acadêmica'),
('Comissão Ciêntifica'),  
('Autor'), 
('Inscrito'), 
('Usuario');

INSERT INTO `usuario` (`id_usuario`, `nome_usuario`, `email`, `senha_hash`, `documento`, `data_nascimento`, `telefone`, `instagram`, `grau_academico`, `nome_curso`, `cidade`, `estado`, `pais`, `foto_perfil`, `data_criacao`, `data_atualizacao`, `status_conta`) VALUES
(1, 'Roberta', 'roberta@email.com', '$2y$10$KP2tANvF1EUwMXy1N1lCo.vqCz9Xg/No4NvhoV7c2Qq28OdQ8bbfC', '111.111.111-11', '2026-02-16', '(21) 99999-9999', '@roberta', 'Pós-graduação', 'Nutrição', 'Rio de Janeiro', 'RJ', 'Brasil', 'user_69ab0ba51f762.png', '2026-02-16 15:47:13', '2026-02-16 15:47:13', 'ativo'),
(2, 'Raquel', 'raquel@email.com', '$2y$10$AiuC45d1Zi1lR8hJeOVz3.ZgqYhuhAMxjKY5FwDMA64i.eZbQRbWC', '222.222.222-22', '2026-02-20', '(21) 99999-9999', '@raquel', 'Graduação', 'Marketing', 'Rio de Janeiro', 'RJ', 'Brasil', 'user_69ab0b3a4a35d.png', '2026-02-16 15:47:13', '2026-02-16 15:47:13', 'ativo' ),
(3, 'João', 'joao@email.com', '$2y$10$lQKpT9XsZi6.vnF0URfkb.INRg35nKzvZNAqfWTA4DH8qT9.jHYOS', '333.333.333-33', '2000-08-10', '(21) 99999-9999', '@joao', 'Mestrado', 'Gestão de Recursos Humanos', 'Rio de Janeiro', 'RJ', 'Brasil', 'user_69ab0c0521b86.png', '2026-02-16 15:47:13', '2026-02-16 15:47:13', 'ativo' ),
(4, 'Caio Silva', 'caio@email.com', '$2y$10$rkLUY4aoiXlyXac1f0ei0OVWZUJaXyl4Xg7IelVSpnlzsQaZ8Tw/2', '444.444.444-44', '2026-03-12', '(21) 94444-4444', '@caio', 'Graduação', 'Nutrição', 'Rio de Janeiro', 'RJ', 'Brasil', '' ,'2026-03-12 11:22:44', '2026-03-12 11:32:23', 'ativo'),
(5, 'Felipe Santos', 'felipe@email.com', '$2y$10$rX5mSGTDPSNb5miB9hjbhOe.UTEfHLDgM5dCChH/QDNtl/3a.eZwG', '555.555.555-55', '2026-03-12', '(21) 95555-5555', '@felipe', 'Graduação', 'Nutrição', 'Rio de Janeiro', 'RJ', 'Brasil', '', '2026-03-12 11:38:10', '2026-03-12 11:38:10', 'ativo'),
(6, 'Marcela Costa', 'marcela@email.com', '$2y$10$uxVBPDk/z2JbMXy0IuH4Geagr1pheItrJQEEwyrnWRYQLcRdUuFNi', '666.666.666-66', '2026-03-12', '(21) 96666-6666', '@marcela', 'Pós-graduação', 'Nutrição', 'Rio de Janeiro', 'RJ', 'Brasil', '', '2026-03-12 11:39:12', '2026-03-12 11:39:12', 'ativo'),
(7, 'Verônica Almeida', 'veronica@email.com', '$2y$10$XnGrjftI1EAJtQFzk73vXeCnIKZxGqXjp0CrjN1W4SGFLZL6EURLe', '777.777.777-77', '2026-03-12', '(21) 97777-7777', '@veronica', 'Mestrado', 'Nutrição', 'Rio de Janeiro', 'RJ', 'Brasil', '', '2026-03-12 11:39:54', '2026-03-12 11:39:54', 'ativo'),
(8, 'Rebeca Mattos', 'rebeca@email.com', '$2y$10$wd/.1jVNJ3b6ovVGkPtz4.ehulu2U5F1Vu2d6wHLtJBaFKkBvPMjO', '888.888.888-88', '2026-03-12', '(21) 98888-8888', '@rebeca', 'Graduação', 'Nutrição', 'Rio de Janeiro', 'RJ', 'Brasil', '', '2026-03-12 11:40:54', '2026-03-12 11:40:54', 'ativo');

INSERT INTO `funcao_usuario` (`id_funcao_usuario`, `usuario_id`, `funcao_id`) VALUES
(1, 1, 2),
(2, 2, 3),
(3, 3, 4),
(4, 4, 6),
(5, 5, 6),
(6, 6, 6),
(7, 7, 6),
(8, 8, 6);

INSERT INTO `evento` (`id_evento`, `organizador_id`, `titulo`, `descricao`, `local_evento`, `data_evento`, `horario_inicio`, `horario_fim`, `data_inscricao_inicio`, `data_inscricao_fim`, `modalidade`, `status_evento`, `capa_evento`, `data_criacao`, `data_atualizacao`) VALUES
(1, 1, '1ª Edição do Encontro Carioca de Alimentação Coletiva', 'O Encontro Carioca de Alimentação Coletiva nasceu com o propósito de valorizar, conectar e fortalecer os profissionais que atuam na alimentação fora do lar, promovendo um espaço de diálogo entre o conhecimento técnico, científico e a prática cotidiana dos serviços de alimentação.', 'Auditório Sylvia Bisaggio - UNISUAM Bonsucesso', '2025-09-27', '08:30:00', '16:30:00', '2025-08-11', '2025-09-15', 'Presencial', 'concluido', '../assets/img/ECAC Banner.png', '2026-02-04 13:44:39', '2026-02-04 13:51:43'),
(2, 1, '2ª Edição do Encontro Carioca de Alimentação Coletiva', 'O Encontro Carioca de Alimentação Coletiva nasceu com o propósito de valorizar, conectar e fortalecer os profissionais que atuam na alimentação fora do lar, promovendo um espaço de diálogo entre o conhecimento técnico, científico e a prática cotidiana dos serviços de alimentação.', 'Auditório UNISUAM Bonsucesso', '2026-02-20', '18:00:00', '21:00:00', '2026-02-02', '2026-02-16', 'Presencial', 'ativo', '../assets/img/ECAC Banner.png', '2026-02-04 13:51:32', '2026-02-04 13:51:32');

INSERT INTO `atividade_evento` (`id_atividade_evento`, `evento_id`, `titulo`, `descricao`, `tipo_atividade`, `horario_inicio`, `horario_fim`, `local_atividade`, `capacidade_max`, `data_criacao`, `data_atualizacao`) VALUES
(1, 1, 'Credenciamento e Boas-vindas', 'Receber e direcionar os inscritos sobre o evento.', 'Pré-Evento', '08:30:00', '09:00:00', 'Auditório Sylvia Bisaggio - UNISUAM Bonsucesso', 200, '2026-02-04 13:53:54', '2026-02-04 13:58:22'),
(2, 1, 'Abertura – Apresentação do Encontro', 'Apresentar o projeto para os inscritos.', 'Abertura', '09:00:00', '09:10:00', 'Auditório Sylvia Bisaggio - UNISUAM Bonsucesso', 200, '2026-02-04 13:54:52', '2026-02-04 13:58:29'),
(3, 1, 'Alimentação Coletiva no Rio de Janeiro: Desafios Atuais e Perspectivas para o Futuro', 'Palestra sobre o alimentação coletiva', 'Palestra', '09:10:00', '09:40:00', 'Auditório Sylvia Bisaggio - UNISUAM Bonsucesso', 200, '2026-02-04 15:30:31', '2026-02-04 15:33:34'),
(4, 1, 'Planejamento em Serviços de Alimentação: Caminhos para uma Gestão de Sucesso', 'Palestra sobre os serviços de alimentação', 'Palestra', '09:45:00', '10:15:00', 'Auditório Sylvia Bisaggio - UNISUAM Bonsucesso', 200, '2026-02-04 13:56:44', '2026-02-04 15:30:46'),
(5, 1, 'Feira de Empreendedores – Comensalidade', 'Intervalo para exposição', 'Exposição', '10:15:00', '10:25:00', 'Pátio - UNISUAM Bonsucesso', 200, '2026-02-04 13:57:58', '2026-02-04 15:30:42'),
(6, 1, 'Elaboração do Termo de Referência em Contas Públicas', 'Palestra sobre referência pública', 'Palestra', '10:25:00', '10:55:00', 'Auditório Sylvia Bisaggio - UNISUAM Bonsucesso', 200, '2026-02-04 15:32:34', '2026-02-04 15:33:40'),
(7, 1, 'Cultura de Segurança dos Alimentos: Transformando Treinamento em Comportamento', 'Palestra sobre cultura dos alimentos', 'Palestra', '11:00:00', '11:30:00', 'Auditório Sylvia Bisaggio - UNISUAM Bonsucesso', 200, '2026-02-04 15:35:40', '2026-02-04 15:36:01'),
(8, 1, 'Sustentabilidade na Produção de Refeições', 'Palestra sobre sustentabilidade', 'Palestra', '11:40:00', '12:10:00', 'Auditório Sylvia Bisaggio - UNISUAM Bonsucesso', 200, '2026-02-04 15:36:20', '2026-02-04 15:37:00'),
(9, 1, 'Marketing Digital e Uso de Mídias Sociais na Captação de Clientes', 'Palestra sobre captação de clientes' , 'Palestra', '12:15:00', '12:45:00', 'Auditório Sylvia Bisaggio - UNISUAM Bonsucesso', 200, '2026-02-04 15:37:51', '2026-02-04 15:38:02'),
(10, 1, 'Intervalo para Almoço', 'Horário de Almoço', 'Invervalo', '12:45:00', '13:45:00' , '', 200, '2026-02-04 15:38:04', '2026-02-04 15:38:21'),
(11, 1, 'Painel do Prato ao Mercado: Panorama Atual da Alimentação Fora do Lar', 'Debate sobre alimentação', 'Painel', '13:45:00', '14:15:00', 'Auditório Sylvia Bisaggio - UNISUAM Bonsucesso', 200, '2026-02-04 15:38:30', '2026-02-04 15:39:05'),
(12, 1, 'Uso de Alimentos Regionais nos Cardápios Institucionais: Um Desafio?', 'Palestra sobre dificuldades nos cardápios institucionais', 'Palestra', '14:20:00', '14:50:00', 'Auditório Sylvia Bisaggio - UNISUAM Bonsucesso', 200, '2026-02-04 15:39:06', '2026-02-04 15:39:43'),
(13, 1, 'Atuação do Nutricionista em Equipamentos Públicos de SAN', 'Palestra sobre atuação do nutricionista', 'Palestra', '15:00:00', '15:30:00', 'Auditório Sylvia Bisaggio - UNISUAM Bonsucesso', 200, '2026-02-04 15:39:55', '2026-02-04 15:40:15'),
(14, 1, 'Gestão de Serviços de Alimentação Escolar', 'Palestra sobre alimentação infanto-juvenil', 'Palestra', '15:35:00', '16:05:00', 'Auditório Sylvia Bisaggio - UNISUAM Bonsucesso', 200, '2026-02-04 15:40:20', '2026-02-04 15:40:59'),
(15, 1, 'Encerramento e Agradecimento', 'Finalização do evento', 'Pós-Evento', '16:10:00', '16:30:00', 'Auditório Sylvia Bisaggio - UNISUAM Bonsucesso', 200, '2026-02-04 15:41:05', '2026-02-04 15:41:28');

INSERT INTO `palestrante` (`id_palestrante`, `atividade_evento_id`, `nome_palestrante`, `email`, `telefone`, `grau_academico`, `nome_curso`, `cargo`, `linkedin_url`, `instagram`, `mini_bio`, `foto_palestrante`) VALUES
(1, 3, 'Cíntia Teixeira', 'cintia@email.com', '(21) 91111-1111', 'Doutorado', 'Ciências', 'Pesquisadora', '', '', 'Uma mini bio aqui...', 'user_69e0ed591f85f.jpeg'),
(2, 4, 'Luana Limoeiro', 'luana@email.com', '(21) 92222-2222', 'Doutorado', 'Ciência e Tecnologia dos Alimentos', 'Coordenadora do Curso de Nutrição UCAM' , '', '', 'Nutricionista, mãe de duas adolescentes e de 1 pet canino e 3 pets felinos, professora, pós graduada em Gestão de Restaurantes, Mestra em Ciência dos Alimentos e Doutora em Ciência e Tecnologia dos Alimentos. ', 'user_69e0edc4b15b7.jpeg'),
(3, 6, 'Kátia Mendes', 'katia@email.com', '(21) 93333-3333', 'Mestrado', 'Ciência e Tecnologia de Alimentos', 'Nutricionista', '', '', 'Nutricionista com atuação em Nutrição em Alimentação Coletiva. Mestre em Ciência e Tecnologia de Alimentos. Pesquisadora Culinafro e Coordenadora da Linha Culinária Africana - CM-UFRJ/Macaé. Pesquisadora-Extensionista do Projeto Interinstitucional Painel das manifestações da gastronomia ancestral no Rio de Janeiro (UNIRIO e Solares Ação Social e Cidadania).', 'user_69e0ee1b6afd7.jpg'),
(4, 7, 'Fernanda Bainha', 'fernanda@email.com', '(21) 94444-4444', 'Doutorado', 'Alimentação, Nutrição e Saúde', 'Assessora Técnica em Segurança Alimentar e Nutricional', '', '', 'Nutricionista (UFF), atua há 15 anos em gestão de UANs Offhore, hospitalares e escolares. Docente na especialização em Nutrição Hospitalar do Hospital Sírio Libanês (SP) e consultora. Assessora Técnica em Segurança Alimentar e Nutricional no governo do Estado. Pesquisadora e ativista sobre as condições de trabalho dos trabalhadores das cozinhas, Risco Sanitário, Lean e PNAE. Mestra em Engenharia de Produção (UFF), Doutoranda em Alimentação, Nutrição e Saúde (UERJ) com MBA em Gestão Integrada de Qualidade, Segurança, Meio Ambiente e Saúde.', 'user_69e0ee4c8646f.jpeg'),
(5, 8, 'Marcela Maltez', 'marcela@email.com', '(21)95555-5555', 'Mestrado', 'Ciência e Tecnologia de Alimentos', 'Especialista em Gestão de Segurança de Alimentos', '', '', 'Nutricionista pela Universidade Federal Fluminense (UFF). Especialista em Gestão na Segurança de Alimentos e Bebidas pela FIRJAN/ SENAI/RJ. Mestre em Ciências e Tecnologia de Alimentos  pelo Instituto Federal do Rio de Janeiro (IFRJ).', 'user_69e0ee8a1663a.jpg'),
(6, 9, 'Camila Didini', 'camila@email.com', '(21) 96666-6666', 'Mestrado', 'Nutrição Humana', 'Professora da PUC-Rio e do Centro Universitário La Salle', '', '', 'Nutricionista pela Universidade Federal do Rio de Janeiro (UFRJ), Especialista em Gastronomia Aplicada a Nutrição pela Nutrinew (2016), Mestre em Nutrição Humana pela Universidade Federal do Rio de Janeiro (UFRJ) (2019), e atualmente com Doutorado em andamento em Alimentos e Nutrição pela UNIRIO (2022). Possui experiência na área de Nutrição, com ênfase em nos seguintes temas: gastronomia, alimentação coletiva, PANC e rotulagem. Atualmente é Professora da PUC-Rio e do Centro Universitário La Salle, onde leciona disciplinas para a graduação em Nutrição e Gastronomia e Professora Colaboradora no Curso de Especialização em Alimentação Coletiva (CEAC) da Universidade Federal do Rio de Janeiro (UFRJ).', 'user_69e0eeb9e7451.jpg'),
(7, 12, 'Tatiana Schiavone', 'tatianaschiavone@gmail.com', '(21) 97777-7777', 'Mestrado', 'Ciência e Tecnologia de Alimentos', 'Nutricionista', '', '@tatischinutricionista', 'Nutricionista do Sistema Integrado de Alimentação da Universidade Federal do Rio de Janeiro (SIA/UFRJ). Consultoria em Serviços de Alimentação e Qualidade. Mestrado em Ciência e Tecnologia dos Alimentos (IFRJ). Pós-graduada em Gestão de Alimentação e Nutrição. Pós-graduada em Qualidade de Alimentos. Experiência na área de Alimentação Coletiva desde 2001.', 'user_69e0ef357f42f.jpeg'),
(8, 13, 'Katiana Telefora', 'katiana@email.com', '(21) 98888-8888', 'Mestrado', 'Saúde Pública', 'Especialista em Políticas Públicas', '', '', 'Nutricionista (UERJ) e Sanitarista (UFRJ). Mestre em Saúde Pública (ENSP/FIOCRUZ). Servidora Pública da carreira de Especialista em Políticas Públicas e Gestão Governamental da Secretaria de Estado de Planejamento e Gestão-RJ.', 'user_69e0ef6e2d0d2.jpeg'),
(9, 14, 'Renata Nogueira', 'renata@email.com', '(21) 99999-9999', 'Doutorado', 'Ciências', 'Responsável Técnica do Programa Nacional da Alimentação Escolar', '', '', 'Nutricionista com experiência de mais de 20 anos no ramo da Alimentação Coletiva, atuando em diferentes segmentos. É servidora pública federal desde 2018, atuando como Responsável Técnica do Programa Nacional da Alimentação Escolar no Colégio Pedro II, campus Tijuca 2. Tem especialização em Gestão da Segurança dos Alimentos, é Mestre em Educação Profissional em Saúde, pela Fundação Oswaldo Cruz  e  Doutora em Ciências, pelo Programa de Pós Graduação em Alimentação, Nutrição e Saúde da UERJ.', 'user_69e0efc91dfa7.jpeg');

INSERT INTO `expositor` (`id_expositor`, `atividade_evento_id`, `nome_expositor`, `email`, `telefone` , `empresa` , `cargo`, `logo`, `link_empresa`, `linkedin_url`, `instagram` , `descricao`, `tipo_espaco`, `necessidades_tecnicas`, `foto_expositor`) VALUES
(1, 5, 'André Matos', 'andrematos@email.com' , '(21) 99999-9999' , 'Nutri' , 'Vendedor', 'logo nutri.png', 'www.google.com.br', 'https://br.linkedin.com/', '@nutri' , 'Breve descrição aqui...', 'estande', 'Acesso a tomada.', 'org3.png'),
(2, 5, 'Marcos Lopes', 'andrematos@email.com' , '(21) 98888-8888' , 'Empresa X' , 'Pesquisador', 'logo.png', 'www.google.com', 'https://br.linkedin.com/', '@empresaX' , 'Breve descrição aqui...', 'mesa', 'Acesso a tomada.', 'org3.png');

INSERT INTO `patrocinador` (`id_patrocinador`, `nome_empresa`, `logo`, `site_empresa`, `nivel_patrocinio`, `beneficios`) VALUES
(1, 'UNISUAM', 'unisuam.png', 'https://www.unisuam.edu.br/', 'bronze', '');

INSERT INTO `submissao` (`evento_id`, `funcao_usuario_id` , `titulo`, `resumo`, `palavras_chave`, `status_arquivo`, `caminho_arquivo`, `data_envio`, `hora_envio`) VALUES
(2, 4, 'Nutrição de Alimentos', 'Descrição detalhada e minuciosa dos nutrientes de cada alimento...', 'Nutrição, Saúde Alimentar, Alimentos', 'enviado', 'edicao2/trab_69b2cc643c264_1773325412.pdf', '2026-03-12', '11:23:32'),
(2, 5, 'Alimentação Coletiva', 'Descrição da produção e oferta de refeições para grupo de pessoas em comum voltado para saúde e segurança alimentar de todos.', 'Alimentação Coletiva, Refeição para Grupos, Saúde Alimentar, Segurança Alimentar', 'enviado', 'edicao2/trab_69b2d507f3f86_1773327623.pdf', '2026-03-12', '12:00:24'),
(2, 6, 'Importância da Nutrição na rotina', 'Descrição de como a nutrição impacta no dia a dia da pessoa.', 'Nutrição, Rotina, Saúde', 'enviado', 'edicao2/trab_69b2d5cdd6df0_1773327821.pdf', '2026-03-12', '12:04:26'),
(2, 7, 'Teste', 'Descrição sobre o teste', 'Teste', 'enviado', 'edicao2/trab_69b2d5faa22be_1773327866.pdf', '2026-03-12', '12:04:26'),
(2, 8, 'Tecnologia dos Alimentos', 'Descrição de como os alimentos afetam seu corpo e como usar isso para melhorar seu desempenho...', 'Saúde Alimentar, Desempenho, Segurança Alimentar', 'enviado', 'edicao2/trab_69b2d777cec52_1773328247.pdf', '2026-03-12', '12:10:47');

INSERT INTO `coautores` (`submissao_id`, `nome_coautor`, `email`, `instituicao`) VALUES
(2, 'João', 'joaoaux@email.com', 'Uni1'),
(2, 'Maria', 'mariaaux@email.com', 'Uni1'),
(3, 'Pedro', 'pedroaux@email.com', 'Universidade do Mundo'),
(3, 'Carlos', 'carlosaux@email.com', 'Universidade Gringa'),
(3, 'Amanda', 'amandaaux@email.com', 'Universidade do Mundo'),
(5, 'Renan', 'renanaux@email.com', 'Uni2'),
(5, 'Lucas', 'lucasaux@email.com', 'Universidade do Campo'),
(5, 'Marcos', 'marcosaux@email.com', 'Univer'),
(5, 'Carla', 'carlaaux@email.com', 'Universidade do Campo'),
(5, 'Patricia', 'patriciaaux@email.com', 'Uni2');

INSERT INTO `comissao_org` (`id_comissao_org`, `funcao_usuario_id`, `funcao_org`, `linkedin_url`) VALUES
(1, 1, 'Organizadora', 'https://br.linkedin.com/'),
(2,2, 'Operador de Marketing', 'https://br.linkedin.com/'),
(3,3, 'Gestor do Projeto', 'https://br.linkedin.com/');

-- INSERT INTO `comissao_cient` (`id_comissao_cient`, `funcao_usuario_id`, `funcao_cient`, `lindekin_url`) VALUES
-- ();