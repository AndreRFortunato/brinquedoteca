<?php 
require_once 'code/conexao.php';

require_once 'header.php';

?>
<div class="container">
	<?php 
	
	if(isset($_POST['update'])){

		// if( empty($_POST['fk_aluno']) || empty($_POST['fk_funcionario']) || empty($_POST['fk_exemplar'])
        //  || empty($_POST['dataEmprestimo']) || empty($_POST['dataPrevDevolucao']) || empty($_POST['$dataDevolucao']))
		// {
		// 	echo "Please fillout all required fields"; 
		// }else{		
            $Alunoid  = $_POST['id_aluno'];
			$Funcionarioid  = $_POST['id_funcionario'];
            $fk_exemplar  = $_POST['id_exemplar'];
            $dataEmprestimo  = $_POST['dataEmprestimo'];
            $dataPrevDevolucao  = $_POST['dataPrevDevolucao'];
			$dataDevolucao 	= $_POST['dataDevolucao'];
			$sql = "UPDATE Emprestimo SET fk_aluno='{$Alunoid}', fk_funcionario='{$Funcionarioid}', fk_exemplar='{$fk_exemplar}',
                        dataEmprestimo='{$dataEmprestimo}', dataPrevDevolucao='{$dataPrevDevolucao}', dataDevolucao='{$dataDevolucao}'
						WHERE id_emprestimo=" . $_POST['emprestimoid'];

			if( $mysqli->query($sql) === TRUE){
				echo "<div class='alert alert-success'>Successfully updated user</div>";
			}else{
				echo "<div class='alert alert-danger'>Error: There was an error while updating user info</div>";
			}
		// }
	}
	$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
	$sql = "SELECT * FROM Emprestimo WHERE id_emprestimo={$id}";
	$result = $mysqli->query($sql);

    $queryAl = "SELECT nome, id_aluno FROM Aluno";
    $resultAl = $mysqli->query($queryAl);

    $queryFunc = "SELECT nome, id_funcionario FROM Funcionario";
    $resultFunc = $mysqli->query($queryFunc);

    $sqlExemp = "SELECT E.id_exemplar, E.fk_brinquedo, B.nome, E.fk_status, S.descricao FROM Exemplar AS E
	INNER JOIN STATUS AS S ON S.id_status = E.fk_status
    INNER JOIN Brinquedo AS B ON B.id_brinquedo = E.fk_brinquedo
    ORDER BY E.id_exemplar ASC";
    $resultExemp = $mysqli->query($sqlExemp);

	if($result->num_rows < 1){
		header('Location: index.php');
		exit;
	}
	$row = $result->fetch_assoc();

    $exemplarid = $row['fk_exemplar'];
    $sqlExemplar = "SELECT E.id_exemplar, E.fk_brinquedo, B.nome, E.fk_status, S.descricao FROM Exemplar AS E
	INNER JOIN STATUS AS S ON S.id_status = E.fk_status
    INNER JOIN Brinquedo AS B ON B.id_brinquedo = E.fk_brinquedo
	WHERE E.id_exemplar={$exemplarid}";
	$resultExemplar = $mysqli->query($sqlExemplar);
    $rowExemplar = $resultExemplar->fetch_assoc();
    $nomeBrinq = $rowExemplar['nome'];	
    $StatusExem = $rowExemplar['descricao'];	

    $Alunoid = $row['fk_aluno'];
    $queryAluno = "SELECT nome, id_aluno FROM Aluno WHERE id_aluno = $Alunoid";
    $resultAluno = $mysqli->query($queryAluno);
    $rowAluno = $resultAluno->fetch_assoc();
    $nomeAluno = $rowAluno['nome'];	
    $id_aluno = $rowAluno['id_aluno'];

    $FuncionarioId = $row['fk_funcionario'];
    $queryFuncionario = "SELECT nome, id_funcionario FROM Funcionario WHERE id_funcionario = $FuncionarioId";
    $resultFuncionario = $mysqli->query($queryFuncionario);
    $rowFuncionario = $resultFuncionario->fetch_assoc();
    $nomeFuncionario = $rowFuncionario['nome'];
    $id_funcionario = $rowFuncionario['id_funcionario'];


	?>
	<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="box">
			<h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Alterar Empréstimo</h3> 
			<form action="" method="POST">
				<input type="hidden" value="<?php echo $row['id_emprestimo']; ?>" name="emprestimoid">
                <label for="id_aluno">Aluno</label>
                <select name="id_aluno" id="id_aluno" class="form-control">
					<?php
					echo "<option value='" . $id_aluno . "'>" . " Atual = " . $nomeAluno . "</option>";
					if ($resultAl->num_rows > 0) {
						while ($rowAl = $resultAl->fetch_assoc()) {
							echo "<option value='" . $rowAl['id_aluno'] . "'>" . $rowAl['id_aluno'] . " - " . $rowAl['nome'] . "</option>";
						}
					} else {
						echo "<option value=''>Nenhum dado encontrado</option>";
					}
					?>
				</select>
				<br>
                <label for="id_funcionario">Funcionário</label>
                <select name="id_funcionario" id="id_funcionario" class="form-control">
					<?php
					echo "<option value='" . $id_funcionario . "'>" . " Atual = " . $nomeFuncionario . "</option>";
					if ($resultFunc->num_rows > 0) {
						while ($rowFunc = $resultFunc->fetch_assoc()) {
							echo "<option value='" . $rowFunc['id_funcionario'] . "'>" . $rowFunc['id_funcionario'] . " - " . $rowFunc['nome'] . "</option>";
						}
					} else {
						echo "<option value=''>Nenhum dado encontrado</option>";
					}
					?>
				</select>
				<br>
                <label for="id_exemplar">Exemplar</label>
                <select name="id_exemplar" id="id_exemplar" class="form-control">
					<?php
                    echo "<option value='" . $exemplarid . "'>" . " Atual = ". $exemplarid . " - " . $nomeBrinq . " - " . $StatusExem . "</option>";
					if ($resultExemp->num_rows > 0) {
						while ($rowExemp = $resultExemp->fetch_assoc()) {
							echo "<option value='" . $rowExemp['id_exemplar'] . "'>" . $rowExemp['id_exemplar'] . " - " . $rowExemp['nome'] . " - " . $rowExemp['descricao'] . "</option>";
						}
					} else {
						echo "<option value=''>Nenhum dado encontrado</option>";
					}
					?>
				</select>
				<br>
				<!-- <label for="dataEmprestimo">Data do Empréstimo</label>
				<input type="datetime-local" id="dataEmprestimo" name="dataEmprestimo" value="<?php echo $row['dataEmprestimo']; ?>" class="form-control"><br> -->
                <label for="dataPrevDevolucao">Data de Previsão da Devolução</label>
				<input type="datetime-local" id="dataPrevDevolucao" name="dataPrevDevolucao" value="<?php echo $row['dataPrevDevolucao']; ?>" class="form-control"><br>
                <label for="dataDevolucao">Data de Devolução</label>
				<input type="datetime-local" id="dataDevolucao" name="dataDevolucao" value="<?php echo $row['dataDevolucao']; ?>" class="form-control"><br>

				<input type="submit" name="update" class="btn btn-success" value="Alterar">
				<a href="index.php" class="btn btn-danger">Cancelar</a>
			</form>
		</div>
	</div>
</div>
</div>

<?php 

 require_once 'code/footer.php';