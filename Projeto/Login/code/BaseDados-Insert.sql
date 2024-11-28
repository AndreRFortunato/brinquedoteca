-- Table AreaDesenvolvimento
INSERT INTO AreaDesenvolvimento (id_areaDesenvolvimento, descricao) VALUES ('1', 'Social');
INSERT INTO AreaDesenvolvimento (id_areaDesenvolvimento, descricao) VALUES ('2', 'Emocional');
INSERT INTO AreaDesenvolvimento (id_areaDesenvolvimento, descricao) VALUES ('3', 'Estágio sensório-motor');
INSERT INTO AreaDesenvolvimento (id_areaDesenvolvimento, descricao) VALUES ('4', 'Estágio pré-operatório');
INSERT INTO AreaDesenvolvimento (id_areaDesenvolvimento, descricao) VALUES ('5', 'Estágio operatório-concreto');
INSERT INTO AreaDesenvolvimento (id_areaDesenvolvimento, descricao) VALUES ('6', 'Estágio operatório-formal');

-- Table Tipo
INSERT INTO Tipo (id_tipo, descricao) VALUES ('1', 'Boneca');
INSERT INTO Tipo (id_tipo, descricao) VALUES ('2', 'Bola');
INSERT INTO Tipo (id_tipo, descricao) VALUES ('3', 'Carrinho');
INSERT INTO Tipo (id_tipo, descricao) VALUES ('4', 'Casinha');
INSERT INTO Tipo (id_tipo, descricao) VALUES ('5', 'Decoração');
INSERT INTO Tipo (id_tipo, descricao) VALUES ('6', 'Acessórios Femininos');
INSERT INTO Tipo (id_tipo, descricao) VALUES ('7', 'Fantasias');
INSERT INTO Tipo (id_tipo, descricao) VALUES ('8', 'Material escolar');
INSERT INTO Tipo (id_tipo, descricao) VALUES ('9', 'Recurso para contação de histórias');
INSERT INTO Tipo (id_tipo, descricao) VALUES ('10', 'Outro');

-- Table Status
INSERT INTO Status (id_status, descricao) VALUES ('1', 'Novo');
INSERT INTO Status (id_status, descricao) VALUES ('2', 'Semi-novo');
INSERT INTO Status (id_status, descricao) VALUES ('3', 'Desgastado');
INSERT INTO Status (id_status, descricao) VALUES ('4', 'Quebrado');

-- Table Brinquedo
INSERT INTO `brinquedo`(`id_brinquedo`, `nome`, `descricao`, `faixaEtaria`, `fk_areaDesenvolvimento`, `fk_tipo`) VALUES ('','Barbie','Boneca Plastico','5','2','1');
INSERT INTO `brinquedo`(`id_brinquedo`, `nome`, `descricao`, `faixaEtaria`, `fk_areaDesenvolvimento`, `fk_tipo`) VALUES ('','Monica','Boneca Plastico','5','2','1');
INSERT INTO `brinquedo`(`id_brinquedo`, `nome`, `descricao`, `faixaEtaria`, `fk_areaDesenvolvimento`, `fk_tipo`) VALUES ('','Mimosa','Boneca Pano','5','2','1');
INSERT INTO `brinquedo`(`id_brinquedo`, `nome`, `descricao`, `faixaEtaria`, `fk_areaDesenvolvimento`, `fk_tipo`) VALUES ('','Bola de Futebol','Bola Esporte','+3','4','2');
INSERT INTO `brinquedo`(`id_brinquedo`, `nome`, `descricao`, `faixaEtaria`, `fk_areaDesenvolvimento`, `fk_tipo`) VALUES ('','Bola de Basket','Bola Esporte','+3','4','2');
INSERT INTO `brinquedo`(`id_brinquedo`, `nome`, `descricao`, `faixaEtaria`, `fk_areaDesenvolvimento`, `fk_tipo`) VALUES ('','Bola de Volei','Bola Esporte','+3','4','2');
INSERT INTO `brinquedo`(`id_brinquedo`, `nome`, `descricao`, `faixaEtaria`, `fk_areaDesenvolvimento`, `fk_tipo`) VALUES ('','Bola de Futebol Americano','Bola Esporte','+7','4','2');
INSERT INTO `brinquedo`(`id_brinquedo`, `nome`, `descricao`, `faixaEtaria`, `fk_areaDesenvolvimento`, `fk_tipo`) VALUES ('','Bola de Neve','Bola Esporte','+3','4','2');
INSERT INTO `brinquedo`(`id_brinquedo`, `nome`, `descricao`, `faixaEtaria`, `fk_areaDesenvolvimento`, `fk_tipo`) VALUES ('','HotWhells','Carrinho','+3','4','3');
INSERT INTO `brinquedo`(`id_brinquedo`, `nome`, `descricao`, `faixaEtaria`, `fk_areaDesenvolvimento`, `fk_tipo`) VALUES ('','Carinho Barbie','Carrinho','+3','4','3');
INSERT INTO `brinquedo`(`id_brinquedo`, `nome`, `descricao`, `faixaEtaria`, `fk_areaDesenvolvimento`, `fk_tipo`) VALUES ('','Lensol','Fantasias','+3','4','7');
INSERT INTO `brinquedo`(`id_brinquedo`, `nome`, `descricao`, `faixaEtaria`, `fk_areaDesenvolvimento`, `fk_tipo`) VALUES ('','Dentadura','Fantasias','+3','4','7');

-- Table Fornecedor
INSERT INTO `fornecedor`(`id_fornecedor`, `nome`, `documento`,`ativo`) VALUES ('','Einstein','123456789','1');
INSERT INTO `fornecedor`(`id_fornecedor`, `nome`, `documento`,`ativo`) VALUES ('','Prefeitura','123456798','1');
INSERT INTO `fornecedor`(`id_fornecedor`, `nome`, `documento`,`ativo`) VALUES ('','NHD','123456777','1');
INSERT INTO `fornecedor`(`id_fornecedor`, `nome`, `documento`,`ativo`) VALUES ('','Indefinido','123456666','1');

-- Table Exemplar
INSERT INTO `exemplar`(`id_Exemplar`, `fk_status`, `fk_brinquedo`) VALUES ('1','1','1');
INSERT INTO `exemplar`(`id_Exemplar`, `fk_status`, `fk_brinquedo`) VALUES ('2','2','1');
INSERT INTO `exemplar`(`id_Exemplar`, `fk_status`, `fk_brinquedo`) VALUES ('3','4','1');
INSERT INTO `exemplar`(`id_Exemplar`, `fk_status`, `fk_brinquedo`) VALUES ('4','3','1');
INSERT INTO `exemplar`(`id_Exemplar`, `fk_status`, `fk_brinquedo`) VALUES ('5','2','1');
INSERT INTO `exemplar`(`id_Exemplar`, `fk_status`, `fk_brinquedo`) VALUES ('6','3','2');
INSERT INTO `exemplar`(`id_Exemplar`, `fk_status`, `fk_brinquedo`) VALUES ('7','1','2');
INSERT INTO `exemplar`(`id_Exemplar`, `fk_status`, `fk_brinquedo`) VALUES ('8','1','2');
INSERT INTO `exemplar`(`id_Exemplar`, `fk_status`, `fk_brinquedo`) VALUES ('9','2','4');
INSERT INTO `exemplar`(`id_Exemplar`, `fk_status`, `fk_brinquedo`) VALUES ('10','4','4');
INSERT INTO `exemplar`(`id_Exemplar`, `fk_status`, `fk_brinquedo`) VALUES ('11','2','4');
INSERT INTO `exemplar`(`id_Exemplar`, `fk_status`, `fk_brinquedo`) VALUES ('12','2','4');
INSERT INTO `exemplar`(`id_Exemplar`, `fk_status`, `fk_brinquedo`) VALUES ('13','1','5');
INSERT INTO `exemplar`(`id_Exemplar`, `fk_status`, `fk_brinquedo`) VALUES ('14','2','5');
INSERT INTO `exemplar`(`id_Exemplar`, `fk_status`, `fk_brinquedo`) VALUES ('15','3','5');
INSERT INTO `exemplar`(`id_Exemplar`, `fk_status`, `fk_brinquedo`) VALUES ('16','1','6');
INSERT INTO `exemplar`(`id_Exemplar`, `fk_status`, `fk_brinquedo`) VALUES ('17','2','6');
INSERT INTO `exemplar`(`id_Exemplar`, `fk_status`, `fk_brinquedo`) VALUES ('18','1','6');
INSERT INTO `exemplar`(`id_Exemplar`, `fk_status`, `fk_brinquedo`) VALUES ('19','1','7');
INSERT INTO `exemplar`(`id_Exemplar`, `fk_status`, `fk_brinquedo`) VALUES ('20','2','7');
INSERT INTO `exemplar`(`id_Exemplar`, `fk_status`, `fk_brinquedo`) VALUES ('21','4','7');

-- Table Aluno
INSERT INTO `aluno`(`id_aluno`, `nome`, `ra`, `turma`,`ativo`) VALUES ('','Andre','123456789','1','1');
INSERT INTO `aluno`(`id_aluno`, `nome`, `ra`, `turma`,`ativo`) VALUES ('','Leo','223456789','2','1');
INSERT INTO `aluno`(`id_aluno`, `nome`, `ra`, `turma`,`ativo`) VALUES ('','Gustavo','323456789','2','1');
INSERT INTO `aluno`(`id_aluno`, `nome`, `ra`, `turma`,`ativo`) VALUES ('','Murilo','423456789','3','1');
INSERT INTO `aluno`(`id_aluno`, `nome`, `ra`, `turma`,`ativo`) VALUES ('','Cesar','523456789','3','1');
INSERT INTO `aluno`(`id_aluno`, `nome`, `ra`, `turma`,`ativo`) VALUES ('','Juan','623456789','3','1');
INSERT INTO `aluno`(`id_aluno`, `nome`, `ra`, `turma`,`ativo`) VALUES ('','Paula','723456789','4','1');
INSERT INTO `aluno`(`id_aluno`, `nome`, `ra`, `turma`,`ativo`) VALUES ('','Caio','823456789','5','1');
INSERT INTO `aluno`(`id_aluno`, `nome`, `ra`, `turma`,`ativo`) VALUES ('','Chris','923456789','4','1');
INSERT INTO `aluno`(`id_aluno`, `nome`, `ra`, `turma`,`ativo`) VALUES ('','Michael','1023456789','2','1');
INSERT INTO `aluno`(`id_aluno`, `nome`, `ra`, `turma`,`ativo`) VALUES ('','Otávio','1123456789','2','1');

-- Table Funcionário
INSERT INTO `Funcionario`(`id_funcionario`, `nome`, `cpf`, `senha`,`ativo`) VALUES ('','Andre','1324568915','','1');
INSERT INTO `Funcionario`(`id_funcionario`, `nome`, `cpf`, `senha`,`ativo`) VALUES ('','Renata','1324568916','','1');
INSERT INTO `Funcionario`(`id_funcionario`, `nome`, `cpf`, `senha`,`ativo`) VALUES ('','Túlio','1324568917','','1');

-- Table Entrada
INSERT INTO `Entrada`(`id_entrada`, `dataEntrada`, `fk_fornecedor`, `fk_exemplar`) VALUES ('','2023-05-25 00:00:00','2','1');
INSERT INTO `Entrada`(`id_entrada`, `dataEntrada`, `fk_fornecedor`, `fk_exemplar`) VALUES ('','2023-05-25 00:00:00','2','2');
INSERT INTO `Entrada`(`id_entrada`, `dataEntrada`, `fk_fornecedor`, `fk_exemplar`) VALUES ('','2023-05-25 00:00:00','2','3');
INSERT INTO `Entrada`(`id_entrada`, `dataEntrada`, `fk_fornecedor`, `fk_exemplar`) VALUES ('','2023-05-25 00:00:00','2','4');
INSERT INTO `Entrada`(`id_entrada`, `dataEntrada`, `fk_fornecedor`, `fk_exemplar`) VALUES ('','2023-05-25 00:00:00','2','5');
INSERT INTO `Entrada`(`id_entrada`, `dataEntrada`, `fk_fornecedor`, `fk_exemplar`) VALUES ('','2022-05-25 00:00:00','3','6');
INSERT INTO `Entrada`(`id_entrada`, `dataEntrada`, `fk_fornecedor`, `fk_exemplar`) VALUES ('','2022-05-25 00:00:00','3','7');
INSERT INTO `Entrada`(`id_entrada`, `dataEntrada`, `fk_fornecedor`, `fk_exemplar`) VALUES ('','2022-05-25 00:00:00','1','8');
INSERT INTO `Entrada`(`id_entrada`, `dataEntrada`, `fk_fornecedor`, `fk_exemplar`) VALUES ('','2022-05-25 00:00:00','4','9');

-- Table Saída
INSERT INTO `Saida`(`id_saida`, `motivo`, `dataSaida`, `fk_exemplar`) VALUES ('','Quebrado','2022-07-25 00:00:00','7');
INSERT INTO `Saida`(`id_saida`, `motivo`, `dataSaida`, `fk_exemplar`) VALUES ('','Quebrado','2022-08-25 00:00:00','10');

-- Table Empréstimo
INSERT INTO `emprestimo`(`id_emprestimo`, `dataEmprestimo`, `dataPrevDevolucao`, `dataDevolucao`, `fk_funcionario`, `fk_aluno`, `fk_exemplar`)
VALUES ('', '2023-02-11 08:00:00', '2023-02-18 08:00:00', '2023-02-21 08:00:00', 20230001, 2023000001, '1');
INSERT INTO `emprestimo`(`id_emprestimo`, `dataEmprestimo`, `dataPrevDevolucao`, `dataDevolucao`, `fk_funcionario`, `fk_aluno`, `fk_exemplar`)
VALUES ('', '2023-02-13 08:00:00', '2023-02-21 08:00:00', '2023-02-20 08:00:00', 20230001, 2023000002, '5');
INSERT INTO `emprestimo`(`id_emprestimo`, `dataEmprestimo`, `dataPrevDevolucao`, `dataDevolucao`, `fk_funcionario`, `fk_aluno`, `fk_exemplar`)
VALUES ('', '2023-02-13 08:00:00', '2023-02-21 08:00:00', '2023-02-21 08:00:00', 20230001, 2023000005, '6');
INSERT INTO `emprestimo`(`id_emprestimo`, `dataEmprestimo`, `dataPrevDevolucao`, `dataDevolucao`, `fk_funcionario`, `fk_aluno`, `fk_exemplar`)
VALUES ('', '2023-02-13 08:00:00', '2023-02-21 08:00:00', '2023-02-22 08:00:00', 20230002, 2023000003, '8');
INSERT INTO `emprestimo`(`id_emprestimo`, `dataEmprestimo`, `dataPrevDevolucao`, `dataDevolucao`, `fk_funcionario`, `fk_aluno`, `fk_exemplar`)
VALUES ('', '2023-02-14 08:00:00', '2023-02-22 08:00:00', '2023-02-23 08:00:00', 20230003, 2023000004, '3');