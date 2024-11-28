<?php 
require_once 'code/conexao.php';
require_once 'header.php';
include('protect.php');
?>
<div class="container">
	<?php 

	if(isset($_POST['addnew'])){

		if( empty($_POST['fk_brinquedo']) || empty($_POST['fk_status']) || empty($_POST['dataEntrada']) || empty($_POST['fk_fornecedor']) ){
			echo "Por favor preencha os campos faltantes"; 
		}else{		
			$dataEntrada 	= $_POST['dataEntrada'];
			$fk_fornecedor 	= $_POST['fk_fornecedor'];
			$fk_brinquedo  = $_POST['fk_brinquedo'];
			$fk_status  = $_POST['fk_status'];

			$sqlExemplar = "INSERT INTO Exemplar(fk_brinquedo, fk_status) 
			VALUES('$fk_brinquedo','$fk_status')";
			
			if ($mysqli->query($sqlExemplar) === TRUE) {
				$idExemplar = $mysqli->insert_id;
				
				$sqlEntrada = "INSERT INTO Entrada(dataEntrada, fk_fornecedor, fk_exemplar) 
				VALUES('$dataEntrada','$fk_fornecedor','$idExemplar')";

				if ($mysqli->query($sqlEntrada) === TRUE) {
					echo "<script>alert('Adicionado com sucesso');</script>";
				} else {
					echo "<div class='alert alert-danger'>Erro ao cadastrar entrada de brinquedo.</div>";
				}
			} else {
				echo "<div class='alert alert-danger'>Erro ao cadastrar exemplar.</div>";
			}
		}
	}

	$query_fornecedor = "SELECT id_fornecedor, nome FROM Fornecedor";
	$result_fornecedor = $mysqli->query($query_fornecedor);

	$query = "SELECT B.id_brinquedo, B.nome, T.descricao AS Tipo_descricao, A.descricao AS AreaD_descricao FROM Brinquedo AS B
	INNER JOIN Tipo AS T ON T.id_tipo = B.FK_tipo
	INNER JOIN AreaDesenvolvimento AS A ON A.id_areaDesenvolvimento = B.fk_areaDesenvolvimento";
	$result = $mysqli->query($query);

	$query_status = "SELECT id_status, descricao FROM Status";
	$result_status = $mysqli->query($query_status);

	?>
	<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="box">
			<h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Cadastrar Entrada de Brinquedo</h3> 
			<form action="" method="POST">
				<label for="fk_brinquedo">Brinquedo
					<select name="fk_brinquedo" id="fk_brinquedo" class="form-control">
						<?php
						if ($result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {
							echo "<option value='" . $row['id_brinquedo'] . "'>" . $row['id_brinquedo'] . " - " . $row['nome'] . " - " . $row['Tipo_descricao'] . " - " . $row['AreaD_descricao'] . "</option>";
							}
						} else {
							echo "<option value=''>Nenhum dado encontrado</option>";
						}
						?>
					</select>
				</label>
				<br>
				<label for="fk_status">Status
					<select name="fk_status" id="fk_status" class="form-control">
						<?php
						if ($result_status->num_rows > 0) {
							while ($row = $result_status->fetch_assoc()) {
							echo "<option value='" . $row['id_status'] . "'>" . $row['id_status'] . " - " . $row['descricao'] . "</option>";
							}
						} else {
							echo "<option value=''>Nenhum dado encontrado</option>";
						}
						?>
					</select>
				</label>
				<br>
				<label for="fk_fornecedor">Fornecedor
					<select name="fk_fornecedor" id="fk_fornecedor" class="form-control">
						<?php
						if ($result_fornecedor->num_rows > 0) {
							while ($row = $result_fornecedor->fetch_assoc()) {
							echo "<option value='" . $row['id_fornecedor'] . "'>" . $row['id_fornecedor'] . " - " . $row['nome'] . "</option>";
							}
						} else {
							echo "<option value=''>Nenhum dado encontrado</option>";
						}
						?>
					</select>
				</label>
				<br>
				<!-- <label for="dataEntrada">Data de Entrada</label>
				<input type="datetime-local" name="dataEntrada" id="dataEntrada" class="form-control"><br> -->
				<input type="submit" name="addnew" class="btn btn-success" value="Adicionar">
			</form>
		</div>
	</div>
</div>
</div>

<?php 

 require_once 'code/footer.php';
