<?php 
require_once 'code/conexao.php';
require_once 'header.php';
include('protect.php');
?>
<div class="container">
	<?php 
	
	if(isset($_POST['update'])){

		if( empty($_POST['id_fornecedor']) || empty($_POST['id_exemplar']) )
		{
			echo "<script>alert('Por favor, preencha todos os campos');</script>";
		}else{		
			$fk_exemplar  = $_POST['id_exemplar'];
			$fk_fornecedor 	= $_POST['id_fornecedor'];
			$sql = "UPDATE Entrada SET fk_fornecedor='{$fk_fornecedor}',
						fk_exemplar = '{$fk_exemplar}'
						WHERE id_entrada=" . $_POST['entradaid'];

			if( $mysqli->query($sql) === TRUE){
				echo "<script>alert('Modificação realizado com sucesso');</script>";
			}else{
				echo "<div class='alert alert-danger'>Error: There was an error while updating user info</div>";
			}
		}
	}
	$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
	$sql = "SELECT * FROM Entrada WHERE id_entrada={$id}";
	$result = $mysqli->query($sql);

	$sqlExemp = "SELECT E.id_exemplar, E.fk_brinquedo, B.nome, E.fk_status, S.descricao FROM Exemplar AS E
	INNER JOIN STATUS AS S ON S.id_status = E.fk_status
    INNER JOIN Brinquedo AS B ON B.id_brinquedo = E.fk_brinquedo
    ORDER BY E.id_exemplar ASC";
    $resultExemp = $mysqli->query($sqlExemp);

	$sqlForn = "SELECT F.id_fornecedor, F.nome FROM Fornecedor AS F
    ORDER BY F.id_fornecedor ASC";
    $resultForn = $mysqli->query($sqlForn);

	if($result->num_rows < 1){
		header('Location: index.php');
		exit;
	}
	$row = $result->fetch_assoc();

    $exemplarid = $row['fk_exemplar'];
    $sqlExemplar = "SELECT E.id_exemplar, E.fk_brinquedo, B.nome, E.fk_status, S.descricao FROM Exemplar AS E
	INNER JOIN STATUS AS S ON S.id_status = E.fk_status
    INNER JOIN Brinquedo AS B ON B.id_brinquedo = E.fk_brinquedo
	INNER JOIN Entrada AS EN ON EN.fk_exemplar = E.id_exemplar
	WHERE E.id_exemplar={$exemplarid}";
	$resultExemplar = $mysqli->query($sqlExemplar);
    $rowExemplar = $resultExemplar->fetch_assoc();
    $nomeBrinq = $rowExemplar['nome'];	
    $StatusExem = $rowExemplar['descricao'];	

	$fornecedorid = $row['fk_fornecedor'];
    $sqlFornecedor = "SELECT F.id_fornecedor, F.nome FROM Fornecedor AS F
	INNER JOIN Entrada AS EN ON EN.fk_fornecedor = F.id_fornecedor
	WHERE F.id_fornecedor={$fornecedorid}";
	$resultFornecedor = $mysqli->query($sqlFornecedor);
    $rowFornecedor = $resultFornecedor->fetch_assoc();
    $nomeFornecedor = $rowFornecedor['nome'];	

	?>
	<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="box">
			<h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Alterar Entrada</h3> 
			<form action="" method="POST">
				<input type="hidden" value="<?php echo $row['id_entrada']; ?>" name="entradaid">
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
                <select name="id_fornecedor" id="id_fornecedor" class="form-control">
					<?php
                    echo "<option value='" . $fornecedorid . "'>" . " Atual = ". $fornecedorid . " - " . $nomeFornecedor . "</option>";
					if ($resultForn->num_rows > 0) {
						while ($rowForn = $resultForn->fetch_assoc()) {
							echo "<option value='" . $rowForn['id_fornecedor'] . "'>" . $rowForn['id_fornecedor'] . " - " . $rowForn['nome'] . "</option>";
						}
					} else {
						echo "<option value=''>Nenhum dado encontrado</option>";
					}
					?>
				</select>
				<br>
				<input type="submit" name="update" class="btn btn-success" value="Alterar">
				<a href="index.php" class="btn btn-danger">Cancelar</a>
			</form>
		</div>
	</div>
</div>
</div>

<script>
function validarFormulario() {
    var fk_fornecedor = document.getElementById("fk_fornecedor").value;
    var fk_exemplar = document.getElementById("fk_exemplar").value;

    if (fk_fornecedor === "" || fk_exemplar === "" ) {
        alert("Por favor, preencha todos os campos");
        return false;
    }

    return true;
}
</script>

<?php 

 require_once 'code/footer.php';