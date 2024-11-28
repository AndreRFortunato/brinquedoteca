<?php
require_once 'code/conexao.php';
require_once 'header.php';
include('protect.php');
?>

<script type="text/javascript" src="code/Exemplar.js"></script>

<div class="container">
	<?php 
	if (isset($_POST['update'])) {
		if (empty($_POST['nome']) || empty($_POST['descricao']) || empty($_POST['faixaEtaria']) || empty($_POST['fk_status']) || empty($_POST['fk_areaDesenvolvimento'])) {
			echo "Please fill out all required fields"; 
		} else {
			$nome = $_POST['nome'];
			$faixa = $_POST['faixaEtaria'];
			$fk_status = $_POST['fk_status'];
			$fk_tipo = $_POST['fk_tipo'];
			$fk_areaDesenvolvimento = $_POST['fk_areaDesenvolvimento'];
			$descricao = $_POST['descricao'];
			$exemplarId = $_POST['exemplarid'];
			$nome_anterior = $_POST['nome_anterior'];
			$descricao_anterior = $_POST['descricao_anterior'];
			$faixaEtaria_anterior = $_POST['faixaEtaria_anterior'];
			$fk_areaDesenvolvimento_anterior = $_POST['fk_areaDesenvolvimento_anterior'];
			$fk_tipo_anterior = $_POST['fk_tipo_anterior'];

			// Verifica se algum dos itens foi alterado
			if ($nome !== $nome_anterior || $descricao !== $descricao_anterior || $faixa !== $faixaEtaria_anterior
				|| $fk_areaDesenvolvimento !== $fk_areaDesenvolvimento_anterior || $fk_tipo !== $fk_tipo_anterior) {
				// Inserir novo registro de brinquedo e obter o novo id_brinquedo
				$insertQuery = "INSERT INTO Brinquedo (`id_brinquedo`, `nome`, `descricao`, `faixaEtaria`, `fk_areaDesenvolvimento`, `fk_tipo`)
				 VALUES ('','$nome', '$descricao', '$faixa', '$fk_areaDesenvolvimento', '$fk_tipo')";
				$sql =	"UPDATE Exemplar SET fk_status='{$fk_status}'
				WHERE id_exemplar=" . $_POST['exemplarid'];
				if ($mysqli->query($insertQuery) === TRUE) {
					$brinquedoId = $mysqli->insert_id;

					// Transferir o id_exemplar para o novo id_brinquedo
					$updateQuery = "UPDATE Exemplar SET fk_brinquedo = $brinquedoId WHERE id_exemplar = $exemplarId";
					if ($mysqli->query($updateQuery) === TRUE) {
						echo "<div class='alert alert-success'>Successfully updated user</div>";
					} else {
						echo "<div class='alert alert-danger'>Error: There was an error while transferring the exemplar</div>";
					}
				} else {
					echo "<div class='alert alert-danger'>Error: There was an error while creating a new brinquedo</div>";
				}
			} elseif ($nome === $nome_anterior && $descricao === $descricao_anterior && $faixa === $faixaEtaria_anterior
			&& $fk_areaDesenvolvimento === $fk_areaDesenvolvimento_anterior && $fk_tipo === $fk_tipo_anterior && $fk_status !== $row['fk_status']) {
				$sql =	"UPDATE Exemplar SET fk_status='{$fk_status}'
				WHERE id_exemplar=" . $_POST['exemplarid'];
			} else {
				echo "<div class='alert alert-info'>No changes were made</div>";
			}
		}
	}

	$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
	$sql = "SELECT E.id_exemplar, E.fk_brinquedo, E.fk_status, S.descricao FROM Exemplar AS E
	INNER JOIN STATUS AS S ON S.id_status = E.fk_status
	WHERE E.id_exemplar={$id}";
	$result = $mysqli->query($sql);

	$sql_status = "SELECT * FROM Status";
	$resultStatus = $mysqli->query($sql_status);

	$sql_AD = "SELECT * FROM AreaDesenvolvimento";
	$resultAD = $mysqli->query($sql_AD);

	$sql_Tipo = "SELECT * FROM Tipo";
	$resultTipo = $mysqli->query($sql_Tipo);

	if ($result->num_rows < 1) {
		header('Location: index.php');
		exit;
	}

	$row = $result->fetch_assoc();
	$brinquedoId = $row['fk_brinquedo'];

	$queryBrinquedo = "SELECT B.id_brinquedo, B.nome, B.descricao, B.faixaEtaria, T.id_tipo, T.descricao AS 'Tipo_descricao', A.id_areaDesenvolvimento, A.descricao AS 'areaDesenvolvimento_descricao' FROM Brinquedo AS B
	INNER JOIN Exemplar AS E on B.id_brinquedo = E.fk_brinquedo
	INNER JOIN Tipo AS T on B.fk_tipo = T.id_tipo
	INNER JOIN AreaDesenvolvimento AS A on B.fk_areaDesenvolvimento = A.id_areaDesenvolvimento
	WHERE B.id_brinquedo = $brinquedoId";

	$resultBrinquedo = $mysqli->query($queryBrinquedo);
	$rowBrinquedo = $resultBrinquedo->fetch_assoc();

	$descricao = $rowBrinquedo['descricao'];
	$tipoDescricao = $rowBrinquedo['Tipo_descricao'];
	$areaDescricao = $rowBrinquedo['areaDesenvolvimento_descricao'];
	
?>

<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="box">
			<h3><i class="glyphicon"></i>&nbsp;Alterar Brinquedo</h3> 
			<form action="" method="POST">
			<input type="hidden" name="nome_anterior" value="<?php echo $rowBrinquedo['nome']; ?>">
			<input type="hidden" name="descricao_anterior" value="<?php echo $rowBrinquedo['descricao']; ?>">
			<input type="hidden" name="faixaEtaria_anterior" value="<?php echo $rowBrinquedo['faixaEtaria']; ?>">
			<input type="hidden" name="fk_areaDesenvolvimento_anterior" value="<?php echo $rowAD['id_areaDesenvolvimento']; ?>">
			<input type="hidden" name="fk_tipo_anterior" value="<?php echo $rowTipo['id_tipo']; ?>">
				<label for="nome">Nome</label>
				<input type="text" id="nome" name="nome" value="<?php echo $rowBrinquedo['nome']; ?>" class="form-control"><br>
				<label for="exemplarid">ID Exemplar</label> 
				<input type="text" name="exemplarid" id="exemplarid" value="<?php echo $row['id_exemplar']; ?>" class="form-control"><br>
				<label for="faixaEtaria">Faixa Etaria (Anos)</label> 
				<input type="text" name="faixaEtaria" id="faixaEtaria" value="<?php echo $rowBrinquedo['faixaEtaria']; ?>" class="form-control"><br>
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
				<label for="fk_status">Status
					<select name="fk_status" id="fk_status" class="form-control">
						<?php
						echo "<option value='" . $row['fk_status'] . "'>" . " Atual = " . $row['descricao'] . "</option>";
						if ($resultStatus->num_rows > 0) {
							while ($rowStatus = $resultStatus->fetch_assoc()) {
							echo "<option value='" . $rowStatus['id_status'] . "'>" . $rowStatus['id_status'] . " - " . $rowStatus['descricao'] . "</option>";
							}
						} else {
							echo "<option value=''>Nenhum dado encontrado</option>";
						}
						?>
				</select>
				<br>
				<label for="descricao">Descricao</label> 
				<textarea name="descricao" id="descricao" class="form-control"><?php echo $rowBrinquedo['descricao']; ?></textarea><br>
				<input type="submit" name="update" value="Alterar" class="btn btn-success">
				<a href="index.php" class="btn btn-danger">Cancelar</a>
			</form>
		</div>
	</div>
</div>

<?php
require_once 'code/footer.php';
?>
<?php
$mysqli->close();
?>