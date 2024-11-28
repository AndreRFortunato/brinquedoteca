<?php 
require_once 'code/conexao.php';
require_once 'header.php';
include('protect.php');
?>
<div class="container">
	<?php 
	
	if(isset($_POST['update'])){

		if( empty($_POST['motivo']) || empty($_POST['id_exemplar']) )
		{
			echo "<script>alert('Por favor, preencha todos os campos');</script>";
		}else{		
			$motivo  = $_POST['motivo'];
			$fk_exemplar 	= $_POST['id_exemplar'];
			$sql = "UPDATE Saida SET motivo='{$motivo}',
						fk_exemplar = '{$fk_exemplar}' 
						WHERE id_saida=" . $_POST['saidaid'];

			if( $mysqli->query($sql) === TRUE){
				echo "<script>alert('Modificação realizado com sucesso');</script>";
			}else{
				echo "<div class='alert alert-danger'>Error: There was an error while adding new user</div>";
			}
		}
	}
	$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
	$sql = "SELECT * FROM Saida WHERE id_saida={$id}";
	$result = $mysqli->query($sql);

	$sqlExemp = "SELECT E.id_exemplar, E.fk_brinquedo, B.nome, E.fk_status, S.descricao FROM Exemplar AS E
	INNER JOIN STATUS AS S ON S.id_status = E.fk_status
    INNER JOIN Brinquedo AS B ON B.id_brinquedo = E.fk_brinquedo
	WHERE EXISTS (SELECT fk_exemplar FROM Saida WHERE E.id_exemplar = fk_exemplar)
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
	INNER JOIN Saida AS SA ON SA.fk_exemplar = E.id_exemplar
	WHERE E.id_exemplar={$exemplarid} AND EXISTS (SELECT fk_exemplar FROM Saida WHERE E.id_exemplar = fk_exemplar)";
	$resultExemplar = $mysqli->query($sqlExemplar);
    $rowExemplar = $resultExemplar->fetch_assoc();
    $nomeBrinq = $rowExemplar['nome'];	
    $StatusExem = $rowExemplar['descricao'];	

	?>
	<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="box">
			<h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Alterar Saída</h3> 
			<form action="" method="POST">
				<input type="hidden" value="<?php echo $row['id_saida']; ?>" name="saidaid">
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
				<label for="motivo">Motivo de Saída</label> 
				<textarea name="motivo" id="motivo" class="form-control"><?php echo $row['motivo']; ?></textarea><br>
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
    var motivo = document.getElementById("motivo").value;
    var fk_exemplar = document.getElementById("fk_exemplar").value;

    if (motivo === "" || fk_exemplar === "" ) {
        alert("Por favor, preencha todos os campos");
        return false;
    }

    return true;
}
</script>

<?php 

 require_once 'code/footer.php';