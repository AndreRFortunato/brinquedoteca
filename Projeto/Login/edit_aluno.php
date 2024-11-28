<?php 
require_once 'code/conexao.php';
require_once 'header.php';
include('protect.php');
?>
<div class="container">
	<?php 
	
	if(isset($_POST['update'])){

		if( empty($_POST['nome']) || empty($_POST['ra']) || 
			empty($_POST['turma']) )
		{
			echo "Please fillout all required fields"; 
		}else{		
			$nome  = $_POST['nome'];
			$ra 	= $_POST['ra'];
			$turma  	= $_POST['turma'];
			$sql = "UPDATE Aluno SET nome='{$nome}',
						ra = '{$ra}', turma = '{$turma}' 
						WHERE id_aluno=" . $_POST['userid'];

			if( $mysqli->query($sql) === TRUE){
				echo "<div class='alert alert-success'>Successfully updated user</div>";
			}else{
				echo "<div class='alert alert-danger'>Error: There was an error while updating user info</div>";
			}
		}
	}
	$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
	$sql = "SELECT * FROM Aluno WHERE id_aluno={$id}";
	$result = $mysqli->query($sql);

	if($result->num_rows < 1){
		header('Location: index.php');
		exit;
	}
	$row = $result->fetch_assoc();
	?>
	<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="box">
			<h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Alterar Aluno</h3> 
			<form action="" method="POST">
				<input type="hidden" value="<?php echo $row['id_aluno']; ?>" name="userid">
				<label for="nome">Nome</label>
				<input type="text" id="nome"  name="nome" value="<?php echo $row['nome']; ?>" class="form-control"><br>
				<label for="ra">RA</label>
				<input type="text" name="ra" id="ra" value="<?php echo $row['ra']; ?>" class="form-control"><br>
				<label for="turma">Turma</label> 
				<input type="text"  name="turma" id="turma"  value="<?php echo $row['turma']; ?>" class="form-control"><br>
				<br>
				<input type="submit" name="update" class="btn btn-success" value="Alterar">
				<a href="index.php" class="btn btn-danger">Cancelar</a>
			</form>
		</div>
	</div>
</div>
</div>

<?php 

 require_once 'code/footer.php';