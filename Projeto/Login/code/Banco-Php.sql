CREATE TABLE IF NOT EXISTS `AreaDesenvolvimento` (
  `id_areaDesenvolvimento` INT NOT NULL,
  `descricao` VARCHAR(50) NULL,
  PRIMARY KEY (`id_areaDesenvolvimento`));
  
CREATE TABLE IF NOT EXISTS `Tipo` (
  `id_tipo` INT NOT NULL,
  `descricao` VARCHAR(50) NULL,
  PRIMARY KEY (`id_tipo`));
  
CREATE TABLE IF NOT EXISTS `Brinquedo` (
  `id_brinquedo` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(50) NULL,
  `descricao` VARCHAR(50) NULL,
  `faixaEtaria` VARCHAR(50) NULL,
  `fk_areaDesenvolvimento` INT NOT NULL,
  `fk_tipo` INT NOT NULL,
  PRIMARY KEY (`id_brinquedo`),
  INDEX `fk_Brinquedo_AreaDesenvolvimento_idx` (`fk_areaDesenvolvimento` ASC),
  INDEX `fk_Brinquedo_Tipo_idx` (`fk_tipo` ASC) ,
  CONSTRAINT `fk_Brinquedo_AreaDesenvolvimento`
    FOREIGN KEY (`fk_areaDesenvolvimento`)
    REFERENCES `AreaDesenvolvimento` (`id_areaDesenvolvimento`),
  CONSTRAINT `fk_Brinquedo_Tipo`
    FOREIGN KEY (`fk_tipo`)
    REFERENCES `Tipo` (`id_tipo`));
    
CREATE TABLE IF NOT EXISTS `Status` (
  `id_status` INT NOT NULL,
  `descricao` VARCHAR(50) NULL,
  PRIMARY KEY (`id_status`));

CREATE TABLE IF NOT EXISTS `Exemplar` (
  `id_exemplar` INT NOT NULL AUTO_INCREMENT,
  `fk_status` INT NOT NULL,
  `fk_brinquedo` INT NOT NULL,
  PRIMARY KEY (`id_exemplar`),
  INDEX `fk_Exemplar_Status_idx` (`fk_status` ASC),
  INDEX `fk_Exemplar_Brinquedo_idx` (`fk_brinquedo` ASC),
  CONSTRAINT `fk_Exemplar_Status`
    FOREIGN KEY (`fk_status`)
    REFERENCES `Status` (`id_status`),
  CONSTRAINT `fk_Exemplar_Brinquedo`
    FOREIGN KEY (`fk_brinquedo`)
    REFERENCES `Brinquedo` (`id_brinquedo`));
    
CREATE TABLE IF NOT EXISTS `Fornecedor` (
  `id_fornecedor` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(50) NULL,
  `documento` VARCHAR(14) NULL,
  `ativo` TINYINT(1) NULL,
  PRIMARY KEY (`id_fornecedor`),
  UNIQUE INDEX `documento_UNIQUE` (`documento` ASC));

CREATE TABLE IF NOT EXISTS `Entrada` (
  `id_entrada` INT NOT NULL AUTO_INCREMENT,
  `dataEntrada` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `fk_fornecedor` INT NOT NULL,
  `fk_exemplar` INT NOT NULL,
  PRIMARY KEY (`id_entrada`),
  INDEX `fk_Entrada_Fornecedor_idx` (`fk_fornecedor` ASC),
  INDEX `fk_Entrada_Exemplar_idx` (`fk_exemplar` ASC),
  CONSTRAINT `fk_Entrada_Fornecedor`
    FOREIGN KEY (`fk_fornecedor`)
    REFERENCES `Fornecedor` (`id_fornecedor`),
  CONSTRAINT `fk_Entrada_Exemplar`
    FOREIGN KEY (`fk_exemplar`)
    REFERENCES `Exemplar` (`id_exemplar`));

CREATE TABLE IF NOT EXISTS `Saida` (
  `id_saida` INT NOT NULL AUTO_INCREMENT,
  `motivo` VARCHAR(100) NULL,
  `dataSaida` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `fk_exemplar` INT NOT NULL,
  PRIMARY KEY (`id_saida`),
  INDEX `fk_Saida_Exemplar_idx` (`fk_exemplar` ASC),
  CONSTRAINT `fk_Saida_Exemplar`
    FOREIGN KEY (`fk_exemplar`)
    REFERENCES `Exemplar` (`id_exemplar`));

CREATE TABLE IF NOT EXISTS `Aluno` (
  `id_aluno` INT NOT NULL,
  `nome` VARCHAR(100) NULL,
  `ra` VARCHAR(10) NULL,
  `turma` VARCHAR(50) NULL,
  `ativo` TINYINT(1) NULL,
  PRIMARY KEY (`id_aluno`),
  UNIQUE INDEX `ra_UNIQUE` (`ra` ASC));
  
CREATE TABLE IF NOT EXISTS `Funcionario` (
  `id_funcionario` INT NOT NULL,
  `nome` VARCHAR(50) NULL,
  `cpf` VARCHAR(11) NULL,
  `senha` VARCHAR(50) NULL,
  `ativo` TINYINT(1) NULL,
  PRIMARY KEY (`id_funcionario`),
  UNIQUE INDEX `cpf_UNIQUE` (`cpf` ASC));

CREATE TABLE IF NOT EXISTS `Emprestimo` (
  `id_emprestimo` INT NOT NULL AUTO_INCREMENT,
  `dataEmprestimo` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `dataPrevDevolucao` DATETIME NULL,
  `dataDevolucao` DATETIME NULL,
  `fk_funcionario` INT NOT NULL,
  `fk_aluno` INT NOT NULL,
  `fk_exemplar` INT NOT NULL,
  PRIMARY KEY (`id_emprestimo`),
  INDEX `fk_Emprestimo_Funcionario_idx` (`fk_funcionario` ASC),
  INDEX `fk_Emprestimo_Aluno_idx` (`fk_aluno` ASC),
  INDEX `fk_Emprestimo_Exemplar_idx` (`fk_exemplar` ASC),
  CONSTRAINT `fk_Emprestimo_Funcionario`
    FOREIGN KEY (`fk_funcionario`)
    REFERENCES `Funcionario` (`id_funcionario`),
  CONSTRAINT `fk_Emprestimo_Aluno`
    FOREIGN KEY (`fk_aluno`)
    REFERENCES `Aluno` (`id_aluno`),
  CONSTRAINT `fk_Emprestimo_Exemplar`
    FOREIGN KEY (`fk_exemplar`)
    REFERENCES `Exemplar` (`id_exemplar`));

DELIMITER $$
CREATE FUNCTION gerar_prefixo() RETURNS VARCHAR(4)
BEGIN
    DECLARE ano VARCHAR(4);
    SET ano = DATE_FORMAT(CURDATE(), '%Y');
    RETURN ano;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER gerar_id_senha_funcionario BEFORE INSERT ON Funcionario
FOR EACH ROW
BEGIN
    DECLARE prefixo VARCHAR(4);
    SET prefixo = gerar_prefixo();
    SET NEW.id_funcionario = CONCAT(prefixo, LPAD((SELECT IFNULL(MAX(RIGHT(id_funcionario, 4)) + 1, 1) FROM Funcionario WHERE LEFT(id_funcionario, 4) = prefixo), 4, '0'));
    SET NEW.senha = SUBSTRING(REPLACE(UUID(), '-', ''), 1, 10);
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER gerar_id_aluno BEFORE INSERT ON Aluno
FOR EACH ROW
BEGIN
    DECLARE prefixo VARCHAR(4);
    SET prefixo = gerar_prefixo();
    SET NEW.id_aluno = CONCAT(prefixo, LPAD((SELECT IFNULL(MAX(RIGHT(id_aluno, 6)) + 1, 1) FROM Aluno WHERE LEFT(id_aluno, 4) = prefixo), 6, '0'));
END$$
DELIMITER ;