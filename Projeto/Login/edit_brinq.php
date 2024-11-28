<?php 
require_once 'code/conexao.php';
require_once 'header.php';
include('protect.php');
?>
<div class="container">
	<?php 
	
	if (isset($_POST['update'])) {
		if (empty($_POST['fk_status']) || empty($_POST['fk_brinquedo'])) {
			echo "Please fill out all required fields"; 
		} else {
			$id_exemplar = $_POST['id_exemplar'];
			$fk_status = $_POST['fk_status'];
			$fk_brinquedo = $_POST['fk_brinquedo'];
			$sql = "UPDATE Exemplar SET fk_status='{$fk_status}', faixaEtaria = '{$fk_brinquedo}'
						WHERE id_exemplar=" . $_POST['exemplarid'];

			if( $mysqli->query($sql) === TRUE){
				echo "<div class='alert alert-success'>Successfully updated user</div>";
			}else{
				echo "<div class='alert alert-danger'>Error: There was an error while updating user info</div>";
			}
		}
	}
	$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
	$sql = "SELECT * FROM Exemplar WHERE id_exemplar={$id}";
	$result = $mysqli->query($sql);

    $sql_AD = "SELECT * FROM Status";
	$resultAD = $mysqli->query($sql_AD);

	$sql_Tipo = "SELECT * FROM Tipo";
	$resultTipo = $mysqli->query($sql_Tipo);

	if($result->num_rows < 1){
		header('Location: index.php');
		exit;
	}
	$row = $result->fetch_assoc();

	$brinquedoId = $row['fk_brinquedo'];

	$queryBrinquedo = "SELECT B.id_brinquedo, B.nome,   FROM Brinquedo AS B
	INNER JOIN Exemplar AS E on B.id_brinquedo = E.fk_brinquedo
	WHERE B.id_brinquedo = $brinquedoId";

	$resultBrinquedo = $mysqli->query($queryBrinquedo);
	$rowBrinquedo = $resultBrinquedo->fetch_assoc();


	?>
	<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="box">
			<h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Alterar exemplar</h3> 
			<form action="" method="POST">
				<input type="hidden" value="<?php echo $row['id_brinquedo']; ?>" name="brinquedoid">
				<label for="nome">Nome</label>
				<input type="text" id="nome"  name="nome" value="<?php echo $row['nome']; ?>" class="form-control"><br>
				<label for="faixaEtaria">Faixa Etária</label>
				<input type="text" name="faixaEtaria" id="faixaEtaria" value="<?php echo $row['faixaEtaria']; ?>" class="form-control"><br>
                <label for="fk_tipo">Tipo</label>
				<select name="fk_tipo" id="fk_tipo" class="form-control">
					<?php
					echo "<option value='" . $rowBrinquedo['id_tipo'] . "'>" . " Atual = " . $tipoDescricao . "</option>";
					if ($resultTipo->num_rows > 0) {
						while ($rowTipo = $resultTipo->fetch_assoc()) {
							echo "<option value='" . $rowTipo['id_tipo'] . "'>" . $rowTipo['id_tipo'] . " - " . $rowTipo['descricao'] . "</option>";
						}
					} else {
						echo "<option value=''>Nenhum dado encontrado</option>";
					}
					?>
				</select>
				<br>
				<label for="fk_areaDesenvolvimento">Area de Desenvolvimento</label>
				<select name="fk_areaDesenvolvimento" id="fk_areaDesenvolvimento" class="form-control">
					<?php
					echo "<option value='" . $rowBrinquedo['id_areaDesenvolvimento'] . "'>" . " Atual = " . $areaDescricao . "</option>";
					if ($resultAD->num_rows > 0) {
						while ($rowAD = $resultAD->fetch_assoc()) {
							echo "<option value='" . $rowAD['id_areaDesenvolvimento'] . "'>" . $rowAD['id_areaDesenvolvimento'] . " - " . $rowAD['descricao'] . "</option>";
						}
					} else {
						echo "<option value=''>Nenhum dado encontrado</option>";
					}
					?>
				</select>
                <br>
                <label for="descricao">Descricao</label> 
				<textarea name="descricao" id="descricao" class="form-control"><?php echo $rowBrinquedo['descricao']; ?></textarea><br>
				<input type="submit" name="update" class="btn btn-success" value="Alterar">
				<a href="index.php" class="btn btn-danger">Cancelar</a>
			</form>
		</div>
	</div>
</div>
</div>

<?php 

 require_once 'code/footer.php';