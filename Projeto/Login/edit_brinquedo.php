<?php 
require_once 'code/conexao.php';
require_once 'header.php';
include('protect.php');
?>
<div class="container">
	<?php 
	
	if (isset($_POST['update'])) {
		if (empty($_POST['nome']) || empty($_POST['descricao']) || empty($_POST['faixaEtaria'])
         || empty($_POST['fk_tipo']) || empty($_POST['fk_areaDesenvolvimento'])) {
			echo "Please fill out all required fields"; 
		} else {
			$nome = $_POST['nome'];
			$faixa = $_POST['faixaEtaria'];
			$fk_tipo = $_POST['fk_tipo'];
			$fk_areaDesenvolvimento = $_POST['fk_areaDesenvolvimento'];
			$descricao = $_POST['descricao'];
			$sql = "UPDATE Brinquedo SET nome='{$nome}', faixaEtaria = '{$faixa}', fk_tipo = '{$fk_tipo}',
                         fk_areaDesenvolvimento = '{$fk_areaDesenvolvimento}', descricao = '{$descricao}'
						WHERE id_brinquedo=" . $_POST['brinquedoid'];

			if( $mysqli->query($sql) === TRUE){
				echo "<div class='alert alert-success'>Successfully updated user</div>";
			}else{
				echo "<div class='alert alert-danger'>Error: There was an error while updating user info</div>";
			}
		}
	}
	$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
	$sql = "SELECT * FROM Brinquedo WHERE id_brinquedo={$id}";
	$result = $mysqli->query($sql);

    $sql_AD = "SELECT * FROM AreaDesenvolvimento";
	$resultAD = $mysqli->query($sql_AD);

	$sql_Tipo = "SELECT * FROM Tipo";
	$resultTipo = $mysqli->query($sql_Tipo);

	if($result->num_rows < 1){
		header('Location: index.php');
		exit;
	}
	$row = $result->fetch_assoc();

    $queryBrinquedo = "SELECT B.id_brinquedo, B.nome, B.descricao, B.faixaEtaria, T.id_tipo, T.descricao 
    AS 'Tipo_descricao', A.id_areaDesenvolvimento, A.descricao AS 'areaDesenvolvimento_descricao' FROM Brinquedo AS B
	INNER JOIN Tipo AS T on B.fk_tipo = T.id_tipo
	INNER JOIN AreaDesenvolvimento AS A on B.fk_areaDesenvolvimento = A.id_areaDesenvolvimento
	WHERE B.id_brinquedo = $id";

	$resultBrinquedo = $mysqli->query($queryBrinquedo);
	$rowBrinquedo = $resultBrinquedo->fetch_assoc();

	$descricao = $rowBrinquedo['descricao'];
	$tipoDescricao = $rowBrinquedo['Tipo_descricao'];
	$areaDescricao = $rowBrinquedo['areaDesenvolvimento_descricao'];

	?>
	<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="box">
			<h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Alterar Brinquedo</h3> 
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