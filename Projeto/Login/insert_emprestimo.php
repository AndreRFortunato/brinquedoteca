<?php
require_once 'code/conexao.php';
require_once 'header.php';
include('protect.php');

if(isset($_POST['addnew'])){
	if(empty($_POST['select_aluno']) || empty($_POST['select_funcionario']) || empty($_POST['select_exemplar']) || empty($_POST['dataEmprestimo'])){
		echo "<script>alert('Preencha os campos que estiverem vazios');</script>";
	} else {
		$id_aluno = $_POST['select_aluno'];
		$id_funcionario = $_POST['select_funcionario'];
		$id_exemplar = $_POST['select_exemplar'];
		$dataEmprestimo = $_POST['dataEmprestimo'];
		$dataPrevDevolucao = $_POST['dataPrevDevolucao'];

		$sql = "INSERT INTO emprestimo (dataEmprestimo, dataPrevDevolucao, fk_funcionario, fk_aluno, fk_exemplar) VALUES ('$dataEmprestimo', '$dataPrevDevolucao', '$id_funcionario', '$id_aluno', '$id_exemplar')";

        if ($mysqli->query($sql) === TRUE) {
            echo "<script>alert('Adicionado com sucesso');</script>";
        } else {
            echo "<script>alert('Erro: Houve um erro ao adicionar o empréstimo');</script>";
        }
	}
}
?>

<div class="container">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="box">
				<script type="text/javascript" src="script.js"></script> 
				<h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Registrar Empréstimo</h3> 
				<form action="" method="POST">
					Aluno: 
					<select name="select_aluno" class="form-control">
						<option>Selecione o Aluno....</option>
						<?php
						$script_aluno = "SELECT * FROM aluno";
						$resultado_script_aluno = $mysqli->query($script_aluno);
						while($row_aluno = mysqli_fetch_assoc($resultado_script_aluno)){?>
							<option value="<?php echo $row_aluno['id_aluno']; ?>">
								<?php echo $row_aluno['nome']; ?>
							</option>
						<?php	
						}
						?>
					</select>
					<br>

					Funcionário: 
					<select name="select_funcionario" class="form-control">
						<option>Selecione o Funcionário....</option>
						<?php
						$script_funcionario = "SELECT * FROM funcionario";
						$resultado_script_funcionario = $mysqli->query($script_funcionario);
						while($row_funcionario = mysqli_fetch_assoc($resultado_script_funcionario)){?>
							<option value="<?php echo $row_funcionario['id_funcionario']; ?>">
								<?php echo $row_funcionario['nome']; ?>
							</option>
						<?php	
						}
						?>
					</select>
					<br>

					Brinquedo - Status: 
					<select name="select_exemplar" class="form-control">
						<option>Selecione o Exemplar....</option>
						<?php
						$script_exemplar = "SELECT E.id_exemplar, CONCAT(E.id_exemplar,' - ',B.descricao,' - ',S.descricao) as descricao FROM Exemplar AS E
							INNER JOIN brinquedo AS B on E.fk_brinquedo = B.id_brinquedo
							INNER JOIN status AS S on E.fk_status = S.id_status 
							WHERE NOT EXISTS (SELECT fk_exemplar FROM saida WHERE E.id_Exemplar = fk_exemplar)
							AND NOT EXISTS (SELECT fk_exemplar FROM emprestimo WHERE E.id_Exemplar = fk_exemplar AND datadevolucao is null)
							ORDER BY E.id_exemplar";
						$resultado_script_exemplar = $mysqli->query($script_exemplar);
						while($row_exemplar = mysqli_fetch_assoc($resultado_script_exemplar)){?>
							<option value="<?php echo $row_exemplar['id_exemplar']; ?>">
								<?php echo $row_exemplar['descricao']; ?>
							</option>
						<?php	
						}
						?>
					</select>
					<br>

					<!-- <label for="nome">Selecione a Data do Empréstimo</label>
					<input type="datetime-local" name="dataEmprestimo" id="dataEmprestimo" class="form-control">
					<br> -->

					<label for="nome">Data de Previsão da Devolução</label>
					<input type="datetime-local" name="dataPrevDevolucao" id="dataPrevDevolucao" class="form-control">
					<br>

					<input type="submit" name="addnew" class="btn btn-success" value="Adicionar">
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	function validarCampos() {
		var select_aluno = document.getElementById("select_aluno").value;
		var select_funcionario = document.getElementById("select_funcionario").value;
		var select_exemplar = document.getElementById("select_exemplar").value;
		var dataEmprestimo = document.getElementById("dataEmprestimo").value;
		var dataPrevDevolucao = document.getElementById("dataPrevDevolucao").value;

		if (select_aluno === "" || select_funcionario === "" || select_exemplar === "" || dataEmprestimo === "" || dataPrevDevolucao === "") {
			alert("Preencha os campos que estiverem vazios");
			return false;
		} else {
			alert("Adicionado com sucesso");
			return true;
		}
	}
</script>

<?php 
require_once 'code/footer.php';
?>
