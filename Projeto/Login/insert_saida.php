<?php 
require_once 'code/conexao.php';
require_once 'header.php';
include('protect.php');
?>
<div class="container">
	<?php 

	if(isset($_POST['addnew'])){

		if( empty($_POST['motivo']) || empty($_POST['dataSaida']) || empty($_POST['fk_exemplar']) ){
			echo "<script>alert('Por favor preencha todos os campos');</script>"; 
		}else{		
			$motivo  = $_POST['motivo'];
			$dataSaida 	= $_POST['dataSaida'];
			$fk_exemplar = $_POST['fk_exemplar'];

			$sql = "INSERT INTO Saida (motivo, dataSaida, fk_exemplar) 
		    VALUES ('$motivo','$dataSaida','$fk_exemplar')";

			if( $mysqli->query($sql) === TRUE){
				echo "<script>alert('Adicionado com sucesso');</script>";
			}else{
				echo "<script>alert('Erro: Ocorreu um erro ao adicionar');</script>";
			}
		}
	}

	$query_exemplar = "SELECT E.id_exemplar, B.id_brinquedo, B.nome, S.descricao FROM Exemplar E
	 INNER JOIN Brinquedo B ON B.id_brinquedo = E.fk_brinquedo
	 INNER JOIN status AS S on E.fk_status = S.id_status
	 WHERE NOT EXISTS (SELECT fk_exemplar FROM saida WHERE E.id_Exemplar = fk_exemplar)
	 ORDER BY E.id_exemplar";
	$result_exemplar = $mysqli->query($query_exemplar);

	?>
	<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="box">
			<h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Cadastrar Saída</h3> 
			<form action="" method="POST">
				<!-- <label for="dataSaida">Data de Saída</label>
				<input type="datetime-local" name="dataSaida" id="dataSaida" class="form-control"><br> -->
				<label for="fk_exemplar">Exemplar
					<select name="fk_exemplar" id="fk_exemplar" class="form-control">
						<?php
						if ($result_exemplar->num_rows > 0) {
							while ($row = $result_exemplar->fetch_assoc()) {
							echo "<option value='" . $row['id_exemplar'] . "'>" . $row['id_exemplar'] . " - " . $row['nome'] . " - " . $row['descricao'] . "</option>";
							}
						} else {
							echo "<option value=''>Nenhum dado encontrado</option>";
						}
						?>
					</select>
				</label>
				<br>
				<label for="motivo">Motivo</label>
				<textarea rows="4" name="motivo" id="motivo" class="form-control"></textarea><br>
				<input type="submit" name="addnew" class="btn btn-success" value="Adicionar">
				<br>
			</form>
		</div>
	</div>
</div>
</div>

<script>
function cadastrarSaida() {
  var motivo = document.getElementById("motivo").value;
  var dataSaida = document.getElementById("dataSaida").value;
  var fk_exemplar = document.getElementById("fk_exemplar").value;

  if (motivo === "" || dataSaida === "" || fk_exemplar === "") {
    alert("Por favor preencha todos os campos");
    return false;
  } else {
    alert("Adicionado com sucesso");
    return true;
  }
}
</script>

<?php 

 require_once 'code/footer.php';