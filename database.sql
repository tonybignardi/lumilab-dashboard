-- =============================================
-- Lumilab Database Schema
-- Generated: 2026-02-06
-- =============================================

-- =============================================
-- Tabela: `acessoconteudo`
-- =============================================
DROP TABLE IF EXISTS `acessoconteudo`;
CREATE TABLE `acessoconteudo` (
  `conteudo_conteudo` bigint(20) NOT NULL,
  `aluno_alunoId` bigint(20) NOT NULL,
  `curso_cursoId` bigint(20) NOT NULL,
  `dataVisualizacao` datetime DEFAULT NULL,
  `dataUltimaModificacao` datetime DEFAULT NULL,
  `acessoConteudoStatus` varchar(1) DEFAULT NULL COMMENT '0 incompleto, 1- completo indefinido, 2 - completo com nota, 3 completo nota baixa',
  `conteudoTipo` varchar(20) NOT NULL,
  KEY `fk_acessoConteudo_aluno1_idx` (`aluno_alunoId`),
  KEY `fk_acessoConteudo_conteudo1_idx` (`conteudo_conteudo`),
  KEY `curso_cursoId` (`curso_cursoId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =============================================
-- Tabela: `aluno`
-- =============================================
DROP TABLE IF EXISTS `aluno`;
CREATE TABLE `aluno` (
  `alunoId` bigint(20) NOT NULL,
  `alunoDescricao` varchar(100) DEFAULT NULL,
  `alunoEmail` varchar(300) NOT NULL,
  `alunoDataCriacao` datetime DEFAULT NULL,
  `genero` varchar(100) NOT NULL,
  `racial` varchar(100) NOT NULL,
  `escolaridade` varchar(100) NOT NULL,
  `idade` varchar(100) NOT NULL,
  PRIMARY KEY (`alunoId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =============================================
-- Tabela: `alunoinfo`
-- =============================================
DROP TABLE IF EXISTS `alunoinfo`;
CREATE TABLE `alunoinfo` (
  `aluno_alunoId` bigint(20) NOT NULL,
  `alunoInfoField` bigint(20) DEFAULT NULL,
  `alunoInfoDescricao` varchar(100) DEFAULT NULL,
  `alunoInfoValor` varchar(100) DEFAULT NULL,
  `alunoInfoOrdem` int(11) NOT NULL,
  KEY `fk_alunoInfo_aluno1_idx` (`aluno_alunoId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =============================================
-- Tabela: `alunoinscricao`
-- =============================================
DROP TABLE IF EXISTS `alunoinscricao`;
CREATE TABLE `alunoinscricao` (
  `aluno_alunoId` bigint(20) NOT NULL,
  `curso_cursoId` bigint(20) NOT NULL,
  `alunoInscricaoData` datetime DEFAULT NULL,
  `alunoInscricaoDataInicio` datetime NOT NULL,
  `alunoInscricaoDataConclusao` datetime DEFAULT NULL,
  `alunoInscricaoDataCertificado` datetime DEFAULT NULL,
  `diasUnicosNoCurso` int(11) NOT NULL,
  `modulosCompletos` int(11) NOT NULL,
  `alunoInscricaoNotaFinal` float(5,2) NOT NULL,
  `alunoInscricaoNota1Tent` float(5,2) NOT NULL,
  KEY `fk_alunoInscricao_aluno1_idx` (`aluno_alunoId`),
  KEY `fk_alunoInscricao_curso1_idx` (`curso_cursoId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =============================================
-- Tabela: `areacurso`
-- =============================================
DROP TABLE IF EXISTS `areacurso`;
CREATE TABLE `areacurso` (
  `areaCursoId` bigint(20) NOT NULL,
  `areaCursoDescricao` varchar(100) DEFAULT NULL,
  `areaCursoDataCriacao` datetime DEFAULT NULL,
  `areaVisivel` int(11) NOT NULL,
  PRIMARY KEY (`areaCursoId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =============================================
-- Tabela: `configuracao`
-- =============================================
DROP TABLE IF EXISTS `configuracao`;
CREATE TABLE `configuracao` (
  `configuracaoId` bigint(20) NOT NULL AUTO_INCREMENT,
  `configuracaoDescricao` varchar(200) NOT NULL,
  `configuracaoValor` varchar(200) NOT NULL,
  `configuracaoInfo` varchar(200) NOT NULL,
  PRIMARY KEY (`configuracaoId`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `configuracao` VALUES (1, 'top_inscritos', '10', 'Quantidade do ranking mais inscritos tela inicial');
INSERT INTO `configuracao` VALUES (2, 'top_certificados', '10', 'Qauntidade ranking cursos com mais certificado tela inicial');
INSERT INTO `configuracao` VALUES (3, 'data_inicial_fixa', '2023-01-01', 'Se essa data for vazia 0 vai puxar os dados do mes corrente inicialmente, ou entao quando selecionado todo periodo 2010-01-01');
INSERT INTO `configuracao` VALUES (4, 'data_final_fixa', '2024-01-01', 'Se essa data for 0 será puxado do dia atual');
INSERT INTO `configuracao` VALUES (5, 'field_genero', '34', 'id das resposta do perfil genero');
INSERT INTO `configuracao` VALUES (6, 'field_racial', '38', 'id das resposta do perfil Racial');
INSERT INTO `configuracao` VALUES (7, 'field_idade', '37', 'id das resposta do perfil Idades');
INSERT INTO `configuracao` VALUES (8, 'field_escolaridade', '36', 'id das resposta do perfil Escolaridade');
INSERT INTO `configuracao` VALUES (9, 'email_admin', 'lumilab@gmail.com', 'e-mails com acesso a tabelas de gerenciamento');
INSERT INTO `configuracao` VALUES (10, 'limit_dias', '7', 'Limite para agrupar dias no curso e dias para certificado');
INSERT INTO `configuracao` VALUES (11, 'gab_areas', '1=BIO-SAU,4=HUM-SOC,5=EXA-TERR,6=LING-LET-ART,7=TECH-CRI,8=ENCERR', 'alias para os codigos das areas');

-- =============================================
-- Tabela: `conteudo`
-- =============================================
DROP TABLE IF EXISTS `conteudo`;
CREATE TABLE `conteudo` (
  `conteudoId` bigint(20) NOT NULL,
  `curso_cursoId` bigint(20) NOT NULL,
  `conteudoSecao` int(11) NOT NULL,
  `conteudoOrdem` int(11) DEFAULT NULL,
  `conteudoDescricao` varchar(300) NOT NULL,
  `conteudoTipo` varchar(20) NOT NULL,
  `secaoDescricao` varchar(300) NOT NULL,
  PRIMARY KEY (`conteudoId`,`conteudoTipo`),
  KEY `fk_conteudo_curso1_idx` (`curso_cursoId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =============================================
-- Tabela: `curso`
-- =============================================
DROP TABLE IF EXISTS `curso`;
CREATE TABLE `curso` (
  `cursoId` bigint(20) NOT NULL,
  `cursoDescricao` varchar(200) DEFAULT NULL,
  `cursoCargaHoraria` varchar(45) DEFAULT NULL,
  `cursoDataCriacao` datetime DEFAULT NULL,
  `areaCurso_areaCursoId` bigint(20) NOT NULL,
  `cursoVisivel` int(11) NOT NULL,
  PRIMARY KEY (`cursoId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =============================================
-- Tabela: `cursocarga`
-- =============================================
DROP TABLE IF EXISTS `cursocarga`;
CREATE TABLE `cursocarga` (
  `cursoCargaId` int(11) NOT NULL AUTO_INCREMENT,
  `curso_cursoId` int(11) NOT NULL,
  `cursoDescricao` varchar(350) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `cargahoraria` int(11) NOT NULL,
  `interna` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'S',
  PRIMARY KEY (`cursoCargaId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- =============================================
-- Tabela: `cursoresponsavel`
-- =============================================
DROP TABLE IF EXISTS `cursoresponsavel`;
CREATE TABLE `cursoresponsavel` (
  `cursoResponsavelId` bigint(20) NOT NULL AUTO_INCREMENT,
  `curso_cursoId` bigint(20) NOT NULL,
  `usuario_usuarioId` bigint(20) NOT NULL,
  `cursoResponsavelPapel` int(11) NOT NULL DEFAULT 1,
  `nomePapel` varchar(100) NOT NULL DEFAULT 'manager',
  `interna` varchar(1) NOT NULL DEFAULT 'S',
  PRIMARY KEY (`cursoResponsavelId`),
  KEY `fk_curso_has_usuario_curso_idx` (`curso_cursoId`),
  KEY `usuario_usarioId` (`usuario_usuarioId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =============================================
-- Tabela: `forum`
-- =============================================
DROP TABLE IF EXISTS `forum`;
CREATE TABLE `forum` (
  `forumId` bigint(20) NOT NULL,
  `forumTipo` varchar(20) DEFAULT NULL,
  `forumDescricao` varchar(300) DEFAULT NULL,
  `forumIntro` text DEFAULT NULL,
  `forumDataModificacao` datetime DEFAULT NULL,
  `curso_cursoId` bigint(20) NOT NULL,
  PRIMARY KEY (`forumId`),
  KEY `fk_forum_curso1_idx` (`curso_cursoId`),
  CONSTRAINT `fk_forum_curso1` FOREIGN KEY (`curso_cursoId`) REFERENCES `curso` (`cursoId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =============================================
-- Tabela: `forumpost`
-- =============================================
DROP TABLE IF EXISTS `forumpost`;
CREATE TABLE `forumpost` (
  `forumPostId` bigint(20) NOT NULL,
  `forumPostData` datetime DEFAULT NULL,
  `forumPostMensagem` text DEFAULT NULL,
  `forumtopico_forumTopicoId` bigint(20) NOT NULL,
  `aluno_alunoId` bigint(20) NOT NULL,
  `curso_cursoId` bigint(20) NOT NULL,
  `sent_pos` varchar(10) NOT NULL,
  `sent_neu` varchar(10) NOT NULL,
  `sent_neg` varchar(10) NOT NULL,
  `sent_total` varchar(10) NOT NULL,
  PRIMARY KEY (`forumPostId`),
  KEY `fk_forumpost_forumtopico1_idx` (`forumtopico_forumTopicoId`),
  KEY `fk_forumpost_aluno1_idx` (`aluno_alunoId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =============================================
-- Tabela: `forumtopico`
-- =============================================
DROP TABLE IF EXISTS `forumtopico`;
CREATE TABLE `forumtopico` (
  `forumTopicoId` bigint(20) NOT NULL,
  `forumTopicoDescricao` text DEFAULT NULL,
  `forum_forumId` bigint(20) NOT NULL,
  `curso_cursoId` bigint(20) NOT NULL,
  PRIMARY KEY (`forumTopicoId`),
  KEY `fk_forumtopico_forum1_idx` (`forum_forumId`),
  KEY `fk_forumtopico_curso1_idx` (`curso_cursoId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =============================================
-- Tabela: `import`
-- =============================================
DROP TABLE IF EXISTS `import`;
CREATE TABLE `import` (
  `id` int(11) NOT NULL,
  `valor` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- =============================================
-- Tabela: `indicador`
-- =============================================
DROP TABLE IF EXISTS `indicador`;
CREATE TABLE `indicador` (
  `indicadorId` bigint(20) NOT NULL AUTO_INCREMENT,
  `indicadorRotulo` varchar(200) NOT NULL,
  `indicadorDescricao` varchar(200) NOT NULL,
  `indicadorTipo` varchar(100) NOT NULL COMMENT 'linha,barra,texto',
  `indicadorSQL` text NOT NULL,
  `filtroCurso` text NOT NULL,
  `filtroField` text NOT NULL,
  `filtroData` text NOT NULL,
  `filtroPlataforma` text NOT NULL,
  `filtroGroup` text NOT NULL,
  PRIMARY KEY (`indicadorId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `indicador` VALUES (1, 'lista_inscritos_curso', 'Top Inscricoes', 'texto', 'select c.cursoDescricao as shortname,count(1) as inscritos from alunoinscricao a join curso c on (a.curso_cursoId=c.cursoId) where [filtroData] group by c.cursoId order by inscritos desc limit 0,[config_top_inscritos]', '.', ' .', 'a.alunoInscricaoData between \'[d1]\' and \'[d2]\'', ' .', '');
INSERT INTO `indicador` VALUES (2, 'lista_certificados_no_curso', 'Top Certificados', 'texto', 'select c.cursoDescricao as shortname,count(1) as inscritos from alunoinscricao a join curso c on (a.curso_cursoId=c.cursoId) where a.alunoInscricaoDataCertificado!=\'\' and [filtroData] group by c.cursoId order by inscritos desc limit 0,[config_top_certificados]', '.', '.', 'a.alunoInscricaoDataCertificado between \'[d1]\' and \'[d2]\'', '.', '');
INSERT INTO `indicador` VALUES (3, 'lista_perfil', '.', 'texto', 'select (case WHEN (ai.alunoinfoValor IS NULL or ai.alunoInfoValor=\'\') then \'Nao respondido\' else ai.alunoinfoValor end) as data,count(1) as total,(case WHEN (ai.alunoinfoValor IS NULL) then 20 else ai.alunoInfoOrdem end) as ordem [filtroField] [filtroData][filtroPlataforma] [filtroCurso] [filtroGroup]', 'where a.alunoinscricaoData between \'[c1]\' and \'[c2]\' and [c3]', 'from [f1] a left join alunoinfo ai on (ai.aluno_alunoId=a.[f2] and ai.alunoInfoField=\'[f3]\')', 'where a.alunoDataCriacao between \'[d1]\' and \'[d2]\'', 'where a.alunoinscricaoDataCertificado between \'[p1]\' and \'[p2]\'', 'group by data order by ordem asc');
INSERT INTO `indicador` VALUES (7, 'inscricoes', 'Inscricoes', 'linha', 'select [filtroField] as data, count(1) as total from alunoinscricao ai join aluno a on (a.alunoId=ai.aluno_alunoId) [filtroData][filtroCurso] [filtroPlataforma] group by data order by data', 'and ai.curso_cursoId=[c1]', '[f1]', 'where ai.alunoInscricaoData between \'[d1]\' and \'[d2]\'', '[p1]', '');
INSERT INTO `indicador` VALUES (8, 'certificados', 'Certificados', 'linha', 'select [filtroField] as data, count(1) as total from alunoinscricao ai join aluno a on (a.alunoId=ai.aluno_alunoId) [filtroData] [filtroCurso] [filtroPlataforma] group by data order by data', 'and ai.curso_cursoId=[c1]', '[f1]', 'where ai.alunoInscricaoDataCertificado between \'[d1]\' and \'[d2]\'', '[p1]', '');
INSERT INTO `indicador` VALUES (9, 'dias_no_curso', 'Dias no Curso', 'barra', 'select (case when interna2.totuser>[config_limit_dias] then \'[config_limit_dias]+\' else interna2.totuser end) as data,count(interna2.totuser) as total from (select interna1.cursoid,interna1.alunoid,count(interna1.totdia) as totuser from (SELECT ac.curso_cursoId as cursoid,ac.aluno_alunoId as alunoid,date(ac.dataUltimaModificacao) as sodata,count(1) as totdia FROM acessoconteudo ac join aluno a on (a.alunoId=ac.aluno_alunoId) [filtroData] [filtroCurso] [filtroPlataforma] group by ac.curso_cursoId,ac.aluno_alunoId,sodata) interna1 group by interna1.cursoid, interna1.alunoid) interna2 group by data', 'and ac.curso_cursoId=[c1]', '', 'where ac.dataUltimaModificacao between \'[d1]\' and \'[d2]\' and ac.acessoConteudoStatus in (1,2,3)', '[p1]', '');
INSERT INTO `indicador` VALUES (10, 'dias_para_certificado', 'Dias para Certificacao', 'barra', 'select (case when interna2.totuser>[config_limit_dias] then \'[config_limit_dias]+\' else interna2.totuser end) as data,count(interna2.totuser) as total from (select interna1.cursoid,interna1.alunoid,count(interna1.totdia) as totuser from (SELECT ac.curso_cursoId as cursoid,ac.aluno_alunoId as alunoid,date(ac.dataUltimaModificacao) as sodata,count(1) as totdia FROM acessoconteudo ac join aluno a on (a.alunoId=ac.aluno_alunoId) where ac.dataUltimaModificacao [filtroData] and ac.acessoConteudoStatus in (1,2,3) [filtroCurso] [filtroPlataforma] and exists (select 1 from alunoinscricao ai where ai.curso_cursoId=ac.curso_cursoId and ai.aluno_alunoId=ac.aluno_alunoId and ai.alunoInscricaoDataCertificado [filtroData]) group by ac.curso_cursoId,ac.aluno_alunoId,sodata) interna1 group by interna1.cursoid, interna1.alunoid) interna2 group by data', 'and ac.curso_cursoId=[c1]', '', 'between \'[d1]\' and \'[d2]\'', '[p1]', '');
INSERT INTO `indicador` VALUES (11, 'atividades_concluidas', 'Atividades Concluidas', 'barra', 'SELECT CONCAT(SUBSTRING(concat(c.conteudoSecao+1,\'.\',c.conteudoOrdem,\' - \',c.conteudoDescricao),1,35),\'[..]\') as data,count(ac.conteudo_conteudo) as total FROM conteudo c left join acessoconteudo ac on (ac.conteudo_conteudo=c.conteudoId [filtroData] [filtroField] [filtroPlataforma]) join aluno a on (a.alunoId=ac.aluno_alunoId) [filtroCurso] group by c.conteudoId order by c.conteudoSecao desc,c.conteudoOrdem desc', 'where c.curso_cursoId=[c1] and c.conteudoTipo not in(\'label\') [c2]', 'and c.conteudoTipo=[f1]', 'and ac.dataUltimaModificacao between \'[d1]\' and \'[d2]\'', 'and c.conteudoTipo not in (\'quiz\',\'forum\',\'resource\',\'url\')', '');
INSERT INTO `indicador` VALUES (13, 'lista_quiz', 'Questionarios', 'texto', 'select q.quizDescricao as nome,(select count(1) from questao where quiz_quizid=q.quizId) as total,q.quizId as idunico,\'1\' as descdesc from quiz q [filtroCurso] [filtroData] having total>0 order by quizSecao,quizOrdem [filtroPlataforma]', 'where q.curso_cursoId=\'[c1]\'', ' .', 'and q.quizDataCriacao between \'[d1]\' and \'[d2]\'', 'limit [p1],[p2]', '');
INSERT INTO `indicador` VALUES (15, 'lista_questao_tent', 'Questoes', 'questoes', 'SELECT q.questaoId,q.questaoOrdem,q.questaoNome as nome,i.quizDescricao,q.questaoOrdem,r.questaoTentativa,r.questao_questaoId,sum(case when r.resultado=\'gradedwrong\' then 1 else 0 end) as erros, sum(r.questaoRespostaNota) as acertos,count(1) as respostas,sum(r.questaoRespostaNota)/(count(1)) as media FROM questaoresposta r join questao q on (q.questaoId=r.questao_questaoId) join quiz i ON (i.quizId=q.quiz_quizId and i.quizId=r.questao_quiz_quizId) [filtroCurso] and r.resultado in(\'gradedwrong\',\'gradedright\',\'gradedpartial\') and r.questaoTentativa<4 [filtroData] group by q.questaoId,r.questaoTentativa order by q.questaoOrdem ASC,r.questaoTentativa ASC', 'where r.questao_quiz_quizId=[c1] and r.questaoTentativa=[c2]', '.', 'and r.questaoRespostaData between \'[d1]\' and \'[d2]\'', '.', '');
INSERT INTO `indicador` VALUES (17, 'lista_forum', 'Foruns', 'texto', 'select f.forumDescricao as nome,(select count(1) from forumtopico where forum_forumId=f.forumId) as total,f.forumId as idunico,f.forumDescricao as descdesc from forum f [filtroCurso] having total>0 order by f.forumId asc [filtroPlataforma]', 'where f.curso_cursoId=\'[c1]\'', ' .', '.', 'limit [p1],[p2]', '');

-- =============================================
-- Tabela: `info`
-- =============================================
DROP TABLE IF EXISTS `info`;
CREATE TABLE `info` (
  `infoDescricao` varchar(200) NOT NULL,
  `infoValor` varchar(200) NOT NULL,
  `infoField` int(11) NOT NULL,
  `infoOrdem` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- =============================================
-- Tabela: `log`
-- =============================================
DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `logId` bigint(20) NOT NULL AUTO_INCREMENT,
  `usuario_usuarioId` bigint(20) DEFAULT NULL,
  `usuario_email` varchar(300) DEFAULT NULL,
  `curso_cursoId` bigint(20) DEFAULT NULL,
  `log_rota` varchar(300) DEFAULT NULL,
  `log_intervalo` varchar(30) DEFAULT NULL,
  `log_acao` varchar(30) DEFAULT NULL,
  `log_outros` varchar(300) DEFAULT NULL,
  `log_datahora` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`logId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- =============================================
-- Tabela: `migration`
-- =============================================
DROP TABLE IF EXISTS `migration`;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =============================================
-- Tabela: `pagina`
-- =============================================
DROP TABLE IF EXISTS `pagina`;
CREATE TABLE `pagina` (
  `paginaId` bigint(20) NOT NULL AUTO_INCREMENT,
  `curso_cursoId` bigint(20) NOT NULL,
  `paginaFiltro` varchar(100) NOT NULL,
  `paginaTipo` varchar(100) NOT NULL,
  `paginaFiltroInterno` varchar(10) NOT NULL,
  `paginaPosicao` varchar(2) NOT NULL,
  `paginaHTML` text NOT NULL,
  `dataHora` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`paginaId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =============================================
-- Tabela: `pesquisa`
-- =============================================
DROP TABLE IF EXISTS `pesquisa`;
CREATE TABLE `pesquisa` (
  `uid` bigint(20) NOT NULL,
  `nome` varchar(300) NOT NULL,
  `escolaridade` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- =============================================
-- Tabela: `quiz`
-- =============================================
DROP TABLE IF EXISTS `quiz`;
CREATE TABLE `quiz` (
  `quizId` bigint(20) NOT NULL,
  `quizDescricao` varchar(350) DEFAULT NULL,
  `quizDataCriacao` datetime DEFAULT NULL,
  `curso_cursoId` bigint(20) NOT NULL,
  `quizSecao` int(11) NOT NULL,
  `quizOrdem` int(11) NOT NULL,
  PRIMARY KEY (`quizId`),
  KEY `fk_quiz_curso1_idx` (`curso_cursoId`),
  CONSTRAINT `fk_quiz_curso1` FOREIGN KEY (`curso_cursoId`) REFERENCES `curso` (`cursoId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =============================================
-- Tabela: `questao`
-- =============================================
DROP TABLE IF EXISTS `questao`;
CREATE TABLE `questao` (
  `questaoId` bigint(20) NOT NULL,
  `quiz_quizId` bigint(20) NOT NULL,
  `questaoNome` varchar(350) DEFAULT NULL,
  `questaoTexto` text NOT NULL,
  `questaoOrdem` int(11) NOT NULL,
  `questaoData` datetime NOT NULL,
  PRIMARY KEY (`questaoId`,`quiz_quizId`,`questaoOrdem`),
  KEY `fk_questao_quiz1_idx` (`quiz_quizId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =============================================
-- Tabela: `questaoresposta`
-- =============================================
DROP TABLE IF EXISTS `questaoresposta`;
CREATE TABLE `questaoresposta` (
  `questaorespostaId` bigint(20) NOT NULL AUTO_INCREMENT,
  `resultado` varchar(20) DEFAULT NULL COMMENT 'C ou E',
  `questao_questaoId` bigint(20) NOT NULL,
  `questao_quiz_quizId` bigint(20) NOT NULL,
  `aluno_alunoId` bigint(20) NOT NULL,
  `questaoTentativa` int(11) NOT NULL,
  `questaoRespostaNota` float(5,2) NOT NULL,
  `questaoRespostaData` datetime NOT NULL,
  PRIMARY KEY (`questaorespostaId`),
  KEY `fk_questaoresposta_questao1_idx` (`questao_questaoId`,`questao_quiz_quizId`),
  KEY `fk_questaoresposta_aluno1_idx` (`aluno_alunoId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =============================================
-- Tabela: `relatorio`
-- =============================================
DROP TABLE IF EXISTS `relatorio`;
CREATE TABLE `relatorio` (
  `relatorioId` int(11) NOT NULL AUTO_INCREMENT,
  `chave` varchar(100) NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `txt` text NOT NULL,
  PRIMARY KEY (`relatorioId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =============================================
-- Tabela: `usuario`
-- =============================================
DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `usuarioId` bigint(20) NOT NULL,
  `usuarioNome` varchar(200) DEFAULT NULL,
  `usuarioEmail` varchar(200) NOT NULL,
  `usuarioSenha` varchar(200) DEFAULT NULL,
  `usuarioDataCriacao` datetime DEFAULT NULL,
  PRIMARY KEY (`usuarioId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- =============================================
-- Procedure: `atualiza_alunoinfo`
-- =============================================
CREATE DEFINER=`lumilab`@`%` PROCEDURE `atualiza_alunoinfo`()
BEGIN
update alunoinfo a set alunoInfoValor='Entre 20 e 30 anos',alunoInfoOrdem='1' where 
alunoInfoValor in ('Entre 20 e 24 anos','Entre 25 e 29 anos','25 a 29 anos','de 18 a 29 anos','20 a 24 anos','15 a 29 anos','Entre 20 e 30 anos','Entre 20 e') and alunoInfoField=37;

update alunoinfo a set alunoInfoValor='Entre 30 e 40 anos',alunoInfoOrdem='2' where 
alunoInfoValor in ('Entre 30 e 34 anos','Entre 35 e 39 anos','30 a 34 anos','35 a 39 anos','de 30 a 39 anos','Entre 30 e 40 anos','Entre 30 e') and alunoInfoField=37;

update alunoinfo a set alunoInfoValor='Mais de 40 anos',alunoInfoOrdem='3' where 
alunoInfoValor in ('de 40 a 49 anos','acima de 50 anos','40 a 49 anos','de 50 a 59 anos','de 60 a 69 anos','mais de 50 anos','entre 40 e 50 anos','Mais de 40 anos','Mais de 40') and alunoInfoField=37;

update alunoinfo a set alunoInfoValor='Menos de 20 anos',alunoInfoOrdem='0' where 
alunoInfoValor in ('Menos que 19 anos','até 19 anos','15 a 19 anos','até 20 anos','até 19 anos','Menos de 20 anos','Menos de 19') and alunoInfoField=37;

update alunoinfo a set alunoInfoValor='Branco',alunoInfoOrdem='0' where 
alunoInfoValor in ('Branca','branco','branco/branca','branco/ branca','Branco') and alunoInfoField=38;

update alunoinfo a set alunoInfoValor='Pardo/Preto',alunoInfoOrdem='1' where 
alunoInfoValor in ('Parda, preta, mulata, mestiça','pardo','pardo/parda','pardo/ parda','preto','preto/preta','preto/ preta','Pardo/Preto') and alunoInfoField=38;

update alunoinfo a set alunoInfoValor='Indígena',alunoInfoOrdem='2' where 
alunoInfoValor in ('indígena') and alunoInfoField=38;


update alunoinfo a set alunoInfoValor='Outra',alunoInfoOrdem='3' where 
alunoInfoValor not in ('Indígena','Branco','Pardo/Preto') and alunoInfoField=38;


update alunoinfo a set alunoInfoValor='Fundamental ou médio',alunoInfoOrdem='0' where (
alunoInfoValor in ('Ensino fundamental ou médio completo','Fundamental ou médio') or alunoInfoValor like '%ensino fundamental%' or alunoInfoValor like '%ensino médio%') and alunoInfoField=36;

update alunoinfo a set alunoInfoValor='Superior ou pós-grad',alunoInfoOrdem='1' where (
alunoInfoValor in ('Pós graduação completa','Pós graduação em andamento','Superior completo','Superior em andamento','Superior ou pós-graduação','Superior ou pós-grad') or alunoInfoValor like '%pos-gradu%' or alunoInfoValor like '%especiali%' or alunoInfoValor like '%doutorado' or alunoInfoValor like '%doutorado%' or alunoInfoValor like '%graduação%' or alunoInfoValor like '%superior%' ) and alunoInfoField=36;


update alunoinfo a set alunoInfoValor='Feminino',alunoInfoOrdem='1' where 
alunoInfoValor in ('feminino','mulher cis','mulher cisgênero','Feminino') and alunoInfoField=34;

update alunoinfo a set alunoInfoValor='Masculino',alunoInfoOrdem='0' where 
alunoInfoValor in ('masculino','homem cis','homem cisgênero','Masculino') and alunoInfoField=34;

update alunoinfo a set alunoInfoValor='Outro',alunoInfoOrdem='3' where 
alunoInfoValor not in ('Masculino','Feminino') and alunoInfoField=34;



update alunoinfo a set alunoInfoOrdem='20' where 
alunoInfoValor in ('','Outro','Outras');

update alunoinfo a set alunoInfoOrdem='20' where 
alunoInfoValor is null;
END;

-- =============================================
-- Procedure: `atualiza_perfil`
-- =============================================
CREATE DEFINER=`lumilab`@`%` PROCEDURE `atualiza_perfil`()
BEGIN
UPDATE aluno a,alunoinfo ai set a.racial = ai.alunoInfoValor where a.alunoId=ai.aluno_alunoId and ai.alunoInfoField=38;
UPDATE aluno a,alunoinfo ai set a.escolaridade = ai.alunoInfoValor where a.alunoId=ai.aluno_alunoId and ai.alunoInfoField=36;
UPDATE aluno a,alunoinfo ai set a.genero = ai.alunoInfoValor where a.alunoId=ai.aluno_alunoId and ai.alunoInfoField=34;
UPDATE aluno a,alunoinfo ai set a.idade = ai.alunoInfoValor where a.alunoId=ai.aluno_alunoId and ai.alunoInfoField=37;
END;

-- =============================================
-- Procedure: `gera_info`
-- =============================================
CREATE DEFINER=`lumilab`@`%` PROCEDURE `gera_info`() BEGIN DELETE FROM info; INSERT INTO info (infoDescricao, infoValor, infoField, infoOrdem) SELECT DISTINCT alunoInfoDescricao, CASE WHEN alunoInfoValor = '' THEN 'Nao respondido' ELSE alunoInfoValor END, alunoInfoField, alunoInfoOrdem FROM alunoinfo ORDER BY alunoInfoField, alunoInfoOrdem; END;